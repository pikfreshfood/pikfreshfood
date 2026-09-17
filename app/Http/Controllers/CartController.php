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
        if (! Auth::check()) {
            $guestCart = collect(session('guest_cart', []));
            $products = Product::with('vendor')->whereIn('id', $guestCart->keys())->get()->keyBy('id');
            $carts = $guestCart->map(function ($quantity, $productId) use ($products) {
                $product = $products->get((int) $productId);

                if (! $product) {
                    return null;
                }

                return (object) ['id' => $product->id, 'product' => $product, 'quantity' => (int) $quantity];
            })->filter()->values();

            return view('cart.index', compact('carts'));
        }

        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can access the cart.');

        $carts = Auth::user()->carts()->with('product.vendor')->get();
        return view('cart.index', compact('carts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if (! Auth::check()) {
            $guestCart = session('guest_cart', []);
            $productId = (string) $request->integer('product_id');
            $guestCart[$productId] = (int) ($guestCart[$productId] ?? 0) + $request->integer('quantity');
            session(['guest_cart' => $guestCart]);

            return back()->with('success', 'Product added to your cart. Sign in when you are ready to checkout.');
        }

        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can add items to the cart.');

        Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id],
            ['quantity' => $request->quantity]
        );

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, string $cart)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        if (! Auth::check()) {
            $guestCart = session('guest_cart', []);
            abort_unless(array_key_exists($cart, $guestCart), 404);
            $guestCart[$cart] = $request->integer('quantity');
            session(['guest_cart' => $guestCart]);

            if ($request->expectsJson()) {
                $products = Product::with('vendor')->whereIn('id', array_keys($guestCart))->get()->keyBy('id');
                $product = $products->get((int) $cart);
                $subtotal = collect($guestCart)->sum(fn ($quantity, $productId) => ($products->get((int) $productId)?->price ?? 0) * $quantity);
                $deliveryFee = empty($guestCart) ? 0 : 200;

                return response()->json([
                    'cart' => [
                        'quantity' => $guestCart[$cart],
                        'item_total' => $product->price * $guestCart[$cart],
                    ],
                    'summary' => [
                        'cart_count' => count($guestCart),
                        'item_count' => array_sum($guestCart),
                        'subtotal' => $subtotal,
                        'delivery_fee' => $deliveryFee,
                        'total' => $subtotal + $deliveryFee,
                    ],
                ]);
            }

            return back()->with('success', 'Cart updated.');
        }

        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can update the cart.');
        $cartModel = Cart::findOrFail($cart);
        $this->authorize('update', $cartModel);

        $cartModel->update(['quantity' => $request->quantity]);

        if ($request->expectsJson()) {
            $cartModel->load('product.vendor');
            $carts = Auth::user()->carts()->with('product')->get();
            $subtotal = $carts->sum(fn (Cart $item) => $item->product->price * $item->quantity);
            $deliveryFee = $carts->isEmpty() ? 0 : 200;

            return response()->json([
                'message' => 'Cart updated.',
                'cart' => [
                    'id' => $cartModel->id,
                    'quantity' => $cartModel->quantity,
                    'item_total' => $cartModel->product->price * $cartModel->quantity,
                    'unit_price' => $cartModel->product->price,
                    'unit' => $cartModel->product->unit,
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

    public function destroy(string $cart)
    {
        if (! Auth::check()) {
            $guestCart = session('guest_cart', []);
            unset($guestCart[$cart]);
            session(['guest_cart' => $guestCart]);

            return back()->with('success', 'Item removed from cart.');
        }

        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can remove items from the cart.');
        $cartModel = Cart::findOrFail($cart);
        $this->authorize('delete', $cartModel);
        $cartModel->delete();
        return back()->with('success', 'Item removed from cart.');
    }
}
