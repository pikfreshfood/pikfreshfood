<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can access the cart.');

        $carts = Auth::user()->carts()->with('product.vendor')->get();
        return view('cart.index', compact('carts'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can add items to the cart.');

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id],
            ['quantity' => $request->quantity]
        );

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, Cart $cart)
    {
        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can update the cart.');

        $this->authorize('update', $cart);

        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart->update(['quantity' => $request->quantity]);

        if ($request->expectsJson()) {
            $cart->load('product.vendor');
            $carts = Auth::user()->carts()->with('product')->get();
            $subtotal = $carts->sum(fn (Cart $item) => $item->product->price * $item->quantity);
            $deliveryFee = $carts->isEmpty() ? 0 : 200;

            return response()->json([
                'message' => 'Cart updated.',
                'cart' => [
                    'id' => $cart->id,
                    'quantity' => $cart->quantity,
                    'item_total' => $cart->product->price * $cart->quantity,
                    'unit_price' => $cart->product->price,
                    'unit' => $cart->product->unit,
                ],
                'summary' => [
                    'cart_count' => $carts->count(),
                    'item_count' => $carts->sum('quantity'),
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'total' => $subtotal + $deliveryFee,
                ],
            ]);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(Cart $cart)
    {
        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can remove items from the cart.');

        $this->authorize('delete', $cart);
        $cart->delete();
        return back()->with('success', 'Item removed from cart.');
    }
}
