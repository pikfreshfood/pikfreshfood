<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;

class PriceController extends Controller
{
    public function index()
    {
        $categories = Product::query()
            ->where('is_available', true)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $productGroups = Product::with('vendor')
            ->where('is_available', true)
            ->get()
            ->groupBy(fn (Product $product) => Str::lower($product->name))
            ->map(function ($items, $key) {
                return [
                    'slug' => Str::slug($key),
                    'name' => $items->first()->name,
                    'image' => $items->first()->primary_image,
                    'min_price' => $items->min('price'),
                    'max_price' => $items->max('price'),
                    'vendors' => $items->count(),
                ];
            })
            ->values();

        return view('prices.index', compact('categories', 'productGroups'));
    }

    public function show(string $slug)
    {
        $products = Product::with('vendor')
            ->where('is_available', true)
            ->get()
            ->filter(fn (Product $product) => Str::slug(Str::lower($product->name)) === $slug)
            ->sortBy('price')
            ->values();

        abort_if($products->isEmpty(), 404);

        $name = $products->first()->name;
        $insights = [
            'lowest' => $products->first(),
            'highest' => $products->last(),
            'average' => round((float) $products->avg('price'), 2),
        ];

        return view('prices.show', compact('products', 'name', 'insights'));
    }
}
