<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaystackService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->with('vendor')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load('items.product', 'vendor');

        return view('orders.show', compact('order'));
    }

    public function pay(Order $order, PaystackService $paystack)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)->with('success', 'This order is already paid.');
        }

        if ($order->payment_method === 'cash') {
            return redirect()->route('orders.show', $order)->with('error', 'This order was placed for cash on delivery.');
        }

        if (! $paystack->ready()) {
            return redirect()->route('orders.show', $order)->with('error', 'Online payment is temporarily unavailable.');
        }

        $user = Auth::user();
        $reference = $order->transaction_reference ?? ('PIK-' . $order->id . '-' . strtoupper(Str::random(8)));

        if (! $order->transaction_reference) {
            $order->update(['transaction_reference' => $reference]);
        }

        $gateway = $paystack->initializeTransaction([
            'email' => $user->email,
            'amount' => (int) round($order->total_amount * 100),
            'reference' => $reference,
            'currency' => 'NGN',
            'callback_url' => route('checkout.paystack.callback', ['reference' => $reference]),
            'metadata' => [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'cancel_action' => route('orders.show', $order),
            ],
        ]);

        if (! $gateway['status']) {
            return redirect()->route('orders.show', $order)->with('error', $gateway['message'] ?? 'Could not reach the payment gateway.');
        }

        return redirect()->away($gateway['authorization_url']);
    }
}
