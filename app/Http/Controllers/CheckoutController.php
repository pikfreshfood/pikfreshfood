<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can checkout.');

        $carts = Auth::user()->carts()->with('product.vendor')->get();
        $total = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);
        return view('checkout.index', compact('carts', 'total'));
    }

    public function store(Request $request, PaystackService $paystack)
    {
        abort_unless(Auth::user()->isBuyer(), 403, 'Only buyers can place orders.');

        $request->validate([
            'delivery_address' => 'required|string',
            'payment_method' => 'required|in:paystack,wallet,cash',
        ]);

        $user = Auth::user();
        $carts = $user->carts()->with('product.vendor')->get();

        if ($carts->isEmpty()) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $total = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

        $order = DB::transaction(function () use ($request, $user, $carts, $total, $paystack) {
            $order = Order::create([
                'user_id' => $user->id,
                'vendor_id' => $carts->first()->product->vendor_id,
                'total_price' => $total,
                'total_amount' => $total,
                'delivery_address' => $request->delivery_address,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                ]);
            }

            $user->carts()->delete();

            return $order;
        });

        // Cash on delivery: order recorded, no gateway.
        if ($request->payment_method === 'cash') {
            return redirect()->route('orders.index')->with('success', 'Order placed successfully (Cash on Delivery)!');
        }

        // Pay with Paystack.
        if (! $paystack->ready()) {
            return redirect()->route('orders.index')
                ->with('error', 'Online payment is temporarily unavailable. Please try cash on delivery.');
        }

        $reference = 'PIK-' . $order->id . '-' . strtoupper(Str::random(8));
        $order->update(['transaction_reference' => $reference]);

        $gateway = $paystack->initializeTransaction([
            'email' => $user->email,
            'amount' => (int) round($total * 100),
            'reference' => $reference,
            'currency' => 'NGN',
            'callback_url' => route('checkout.paystack.callback', ['reference' => $reference]),
            'metadata' => [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'cancel_action' => route('orders.index'),
            ],
        ]);

        if (! $gateway['status']) {
            return redirect()->route('orders.index')
                ->with('error', $gateway['message'] ?? 'Could not reach the payment gateway.');
        }

        return redirect()->away($gateway['authorization_url']);
    }

    public function callback(Request $request, PaystackService $paystack, string $reference)
    {
        $order = Order::where('transaction_reference', $reference)->first();

        if (! $order) {
            return redirect()->route('orders.index')->with('error', 'Order not found for this payment.');
        }

        $verification = $paystack->verifyTransaction($reference);
        $paid = $verification['status']
            && ($verification['data']['status'] ?? '') === 'success'
            && ($verification['data']['amount'] ?? 0) >= (int) round($order->total_amount * 100);

        if ($paid) {
            $order->update([
                'payment_status' => 'paid',
                'transaction_reference' => $verification['data']['reference'] ?? $reference,
            ]);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Payment successful. Your order has been confirmed.');
        }

        $order->update(['payment_status' => 'failed']);

        return redirect()->route('orders.show', $order)
            ->with('error', 'Payment was not completed. You can try again.');
    }

    public function webhook(Request $request, PaystackService $paystack)
    {
        // Paystack sends the raw body as the signature input.
        $payload = $request->getContent();
        $signature = $request->header('x-paystack-signature');

        if (! $paystack->verifySignature($payload, (string) $signature)) {
            return response()->json(['status' => 'invalid signature'], 401);
        }

        $event = $request->input('event');
        $data = $request->input('data', []);

        if ($event === 'charge.success') {
            $reference = $data['reference'] ?? null;
            $order = $reference ? Order::where('transaction_reference', $reference)->first() : null;

            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'transaction_reference' => $reference,
                ]);
            }
        }

        return response()->json(['status' => 'received']);
    }
}
