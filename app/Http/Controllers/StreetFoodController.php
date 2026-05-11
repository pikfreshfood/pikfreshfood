<?php

namespace App\Http\Controllers;

use App\Models\Product;

class StreetFoodController extends Controller
{
    public function index()
    {
        $vendors = Product::with('vendor')
            ->where('is_available', true)
            ->where(function ($query) {
                $query->where('category', 'roasted foods')
                    ->orWhere('name', 'like', '%suya%')
                    ->orWhere('name', 'like', '%roast%')
                    ->orWhere('name', 'like', '%bbq%');
            })
            ->get()
            ->groupBy('vendor_id')
            ->map(function ($items) {
                return [
                    'vendor' => $items->first()->vendor,
                    'products' => $items->take(4),
                ];
            })
            ->values();

        return view('street-food.index', compact('vendors'));
    }
}
