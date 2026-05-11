<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected function viewerCoordinates(): array
    {
        if (Auth::check() && Auth::user()->latitude && Auth::user()->longitude) {
            return [
                'latitude' => (float) Auth::user()->latitude,
                'longitude' => (float) Auth::user()->longitude,
                'label' => session('location_label', 'your current location'),
            ];
        }

        if (session()->has('user_latitude') && session()->has('user_longitude')) {
            return [
                'latitude' => (float) session('user_latitude'),
                'longitude' => (float) session('user_longitude'),
                'label' => session('location_label', 'your current location'),
            ];
        }

        return [
            'latitude' => 9.0765,
            'longitude' => 7.3986,
            'label' => session('location_label', 'Abuja'),
        ];
    }

    protected function distanceBetween(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lngDelta / 2) ** 2;

        return round($earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a))), 1);
    }

    protected function decorateProducts(Collection $products, array $viewer): Collection
    {
        return $products->map(function (Product $product) use ($viewer) {
            $vendorLatitude = (float) ($product->vendor->latitude ?? $viewer['latitude']);
            $vendorLongitude = (float) ($product->vendor->longitude ?? $viewer['longitude']);

            $distance = $this->distanceBetween(
                $viewer['latitude'],
                $viewer['longitude'],
                $vendorLatitude,
                $vendorLongitude
            );

            $product->distance_km = $distance;

            return $product;
        });
    }

    protected function decorateVendors(Collection $vendors, array $viewer): Collection
    {
        return $vendors->map(function (Vendor $vendor) use ($viewer) {
            $vendor->distance_km = $this->distanceBetween(
                $viewer['latitude'],
                $viewer['longitude'],
                (float) $vendor->latitude,
                (float) $vendor->longitude
            );

            return $vendor;
        });
    }

    protected function categories(): Collection
    {
        return collect(['fruits', 'vegetables', 'roasted foods'])
            ->merge(
                Product::query()
                    ->where('is_available', true)
                    ->select('category')
                    ->distinct()
                    ->pluck('category')
            )
            ->filter()
            ->unique()
            ->values();
    }

    public function index(Request $request)
    {
        if ($request->filled('lat') && $request->filled('lng')) {
            session([
                'user_latitude' => (float) $request->lat,
                'user_longitude' => (float) $request->lng,
                'location_label' => $request->input('label', 'your current location'),
            ]);
        }

        $viewer = $this->viewerCoordinates();
        $sort = $request->string('sort', 'distance')->toString();
        $search = trim($request->string('search')->toString());

        $products = Product::query()
            ->with('vendor')
            ->whereHas('vendor')
            ->where('is_available', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('category', 'like', '%' . $search . '%')
                        ->orWhereHas('vendor', function ($vendorQuery) use ($search) {
                            $vendorQuery
                                ->where('shop_name', 'like', '%' . $search . '%')
                                ->orWhere('address', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->get();

        $products = $this->decorateProducts($products, $viewer);

        $products = match ($sort) {
            'price' => $products->sortBy('price')->values(),
            'rating' => $products->sortByDesc(fn (Product $product) => $product->vendor->rating ?? 0)->values(),
            default => $products->sortBy('distance_km')->values(),
        };

        $products = $products
            ->sortByDesc(function (Product $product) {
                return ($product->isBoosted() || $product->vendor->isBoosted()) ? 1 : 0;
            })
            ->values();

        $popularProducts = $products
            ->sort(function (Product $a, Product $b) {
                $aBoost = ($a->isBoosted() || $a->vendor->isBoosted()) ? 1 : 0;
                $bBoost = ($b->isBoosted() || $b->vendor->isBoosted()) ? 1 : 0;
                if ($bBoost !== $aBoost) {
                    return $bBoost <=> $aBoost;
                }

                $ratingDiff = (float) ($b->vendor->rating ?? 0) <=> (float) ($a->vendor->rating ?? 0);
                if ($ratingDiff !== 0) {
                    return $ratingDiff;
                }

                $ordersDiff = (int) ($b->vendor->total_orders ?? 0) <=> (int) ($a->vendor->total_orders ?? 0);
                if ($ordersDiff !== 0) {
                    return $ordersDiff;
                }

                return (float) ($a->distance_km ?? 0) <=> (float) ($b->distance_km ?? 0);
            })
            ->values();

        $vendors = Vendor::query()
            ->with(['products' => fn ($query) => $query->where('is_available', true)->latest()])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($vendorQuery) use ($search) {
                    $vendorQuery
                        ->where('shop_name', 'like', '%' . $search . '%')
                        ->orWhere('address', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            }, function ($query) {
                $query->where('is_live', true);
            })
            ->get();

        $nearbyVendors = $this->decorateVendors($vendors, $viewer)
            ->sort(function (Vendor $a, Vendor $b) {
                $aBoost = $a->isBoosted() ? 1 : 0;
                $bBoost = $b->isBoosted() ? 1 : 0;
                if ($bBoost !== $aBoost) {
                    return $bBoost <=> $aBoost;
                }

                return (float) $a->distance_km <=> (float) $b->distance_km;
            })
            ->values();

        return view('home', [
            'products' => $products,
            'popularProducts' => $popularProducts,
            'categories' => $this->categories(),
            'nearbyVendors' => $nearbyVendors,
            'locationLabel' => $viewer['label'],
            'sort' => $sort,
            'search' => $search,
            'viewer' => $viewer,
        ]);
    }

    public function category(string $category)
    {
        $viewer = $this->viewerCoordinates();

        $products = Product::query()
            ->with('vendor')
            ->whereHas('vendor')
            ->where('category', $category)
            ->where('is_available', true)
            ->latest()
            ->get();

        $products = $this->decorateProducts($products, $viewer)
            ->sortBy('distance_km')
            ->values();

        return view('category.show', compact('products', 'category'));
    }

    public function show(Product $product)
    {
        $viewer = $this->viewerCoordinates();

        $product->load('vendor');
        $isInWishlist = Auth::check() && Auth::user()->isBuyer()
            ? Auth::user()->wishlistItems()->where('product_id', $product->id)->exists()
            : false;

        $relatedProducts = Product::with('vendor')
            ->where('is_available', true)
            ->where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                $query->where('category', $product->category)
                    ->orWhere('name', $product->name);
            })
            ->get();

        $relatedProducts = $this->decorateProducts($relatedProducts, $viewer)
            ->sortBy(function (Product $product) {
                return ($product->distance_km * 100000) + (float) $product->price;
            })
            ->take(4)
            ->values();

        $categoryProducts = Product::with('vendor')
            ->where('is_available', true)
            ->where('category', $product->category)
            ->get();

        $categoryProducts = $this->decorateProducts($categoryProducts, $viewer);

        $priceInsights = [
            'cheapest' => $categoryProducts->sortBy('price')->first(),
            'average' => round((float) $categoryProducts->avg('price'), 2),
            'nearest' => $categoryProducts->sortBy('distance_km')->first(),
        ];

        return view('product.show', compact('product', 'relatedProducts', 'priceInsights', 'isInWishlist'));
    }

    public function suggestions(Request $request): JsonResponse
    {
        $term = trim($request->string('q')->toString());
        if ($term === '' || mb_strlen($term) < 2) {
            return response()->json([
                'items' => [],
            ]);
        }

        $productItems = Product::query()
            ->with('vendor:id,shop_name')
            ->where('is_available', true)
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', '%' . $term . '%')
                    ->orWhere('category', 'like', '%' . $term . '%');
            })
            ->latest()
            ->limit(6)
            ->get()
            ->map(function (Product $product) {
                return [
                    'type' => 'product',
                    'title' => $product->name,
                    'subtitle' => $product->vendor?->shop_name ?: ucfirst((string) $product->category),
                    'url' => route('product.show', $product),
                ];
            });

        $vendorItems = Vendor::query()
            ->where(function ($query) use ($term) {
                $query->where('shop_name', 'like', '%' . $term . '%')
                    ->orWhere('address', 'like', '%' . $term . '%');
            })
            ->latest()
            ->limit(6)
            ->get()
            ->map(function (Vendor $vendor) {
                return [
                    'type' => 'vendor',
                    'title' => $vendor->shop_name,
                    'subtitle' => $vendor->address ?: 'Vendor',
                    'url' => route('vendor.show', $vendor),
                ];
            });

        $items = $productItems
            ->concat($vendorItems)
            ->take(10)
            ->values();

        return response()->json([
            'items' => $items,
        ]);
    }
}
