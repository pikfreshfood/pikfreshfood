<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MapController extends Controller
{
    protected function viewerCoordinates(): array
    {
        if (auth()->check() && auth()->user()->latitude && auth()->user()->longitude) {
            return [
                'latitude' => (float) auth()->user()->latitude,
                'longitude' => (float) auth()->user()->longitude,
                'label' => session('location_label', 'your current location'),
            ];
        }

        return [
            'latitude' => (float) session('user_latitude', 9.0765),
            'longitude' => (float) session('user_longitude', 7.3986),
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

        $vendors = Vendor::with(['products' => fn ($query) => $query->where('is_available', true)])
            ->where('is_live', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $vendors = $this->decorateVendors($vendors, $viewer)
            ->sortBy('distance_km')
            ->values();

        $nearbyVendors = $vendors
            ->filter(fn (Vendor $vendor) => $vendor->distance_km <= 3)
            ->values();

        $mapConfig = [
            'viewer' => $viewer,
            'vendors' => $vendors->map(fn (Vendor $vendor) => [
                'id' => $vendor->id,
                'name' => $vendor->shop_name,
                'address' => $vendor->address,
                'latitude' => (float) $vendor->latitude,
                'longitude' => (float) $vendor->longitude,
                'distance_km' => $vendor->distance_km,
                'rating' => $vendor->rating ? (float) $vendor->rating : null,
                'products_count' => $vendor->products->count(),
                'profile_image_url' => $vendor->profile_image ? \App\Support\PublicStorage::url($vendor->profile_image) : null,
                'url' => route('vendor.show', $vendor),
            ])->values(),
            'mapUrl' => route('map.index'),
        ];

        return view('map.index', compact('vendors', 'nearbyVendors', 'viewer', 'mapConfig'));
    }
}
