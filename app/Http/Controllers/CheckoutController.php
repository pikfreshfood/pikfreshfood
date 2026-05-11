<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can checkout.');

        $carts = Auth::user()->carts()->with('product.vendor')->get();
        $total = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);
        return view('checkout.index', compact('carts', 'total'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can place orders.');

        $request->validate([
            'delivery_address' => 'required|string',
            'payment_method' => 'required|in:paystack,flutterwave,wallet,cash',
        ]);

        $user = Auth::user();
        $carts = $user->carts()->with('product.vendor')->get();

        if ($carts->isEmpty()) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        DB::transaction(function () use ($request, $user, $carts) {
            $total = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

            $order = Order::create([
                'user_id' => $user->id,
                'vendor_id' => $carts->first()->product->vendor_id, // Assuming single vendor for simplicity
                'total_price' => $total,
                'total_amount' => $total,
                'delivery_address' => $request->delivery_address,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cash' ? 'pending' : 'paid',
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                ]);
            }

            $user->carts()->delete(); // Clear cart
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }
}
