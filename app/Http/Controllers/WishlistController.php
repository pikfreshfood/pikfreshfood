<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can access the watch list.');

        $wishlistItems = Auth::user()
            ->wishlistItems()
            ->with('product.vendor')
            ->latest()
            ->get();

        return view('profile.wishlist', compact('wishlistItems'));
    }

    public function store(Product $product)
    {
        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can save products to the watch list.');

        Auth::user()->wishlistItems()->firstOrCreate([
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Product added to watch list.');
    }

    public function destroy(Product $product)
    {
        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can update the watch list.');

        Auth::user()->wishlistItems()
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Product removed from watch list.');
    }
}
