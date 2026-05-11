@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' - PikFreshFood')

@section('styles')
<style>
    .order-container { max-width: 800px; margin: 40px auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .order-container h1 { color: #27ae60; margin-bottom: 30px; }
    .order-info { background: #f9f9f9; padding: 20px; border-radius: 4px; margin-bottom: 30px; }
    .order-info p { margin: 8px 0; color: #555; }
    .order-info strong { color: #333; }
    .order-items h3 { color: #27ae60; margin-bottom: 20px; }
    .order-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #eee; }
    .item-info h4 { margin: 0 0 5px 0; color: #333; }
    .item-info p { margin: 0; color: #7f8c8d; font-size: 14px; }
    .item-price { font-weight: bold; color: #27ae60; font-size: 16px; }
    .order-total { text-align: right; font-size: 20px; font-weight: bold; color: #27ae60; margin-top: 20px; padding-top: 20px; border-top: 2px solid #eee; }
    .back-link { color: #27ae60; text-decoration: none; font-weight: bold; }
    .back-link:hover { text-decoration: underline; }
</style>
@endsection

@section('content')
<div class="order-container">
    <h1>Order #{{ $order->id }}</h1>
    <p style="margin-bottom: 30px;"><a href="{{ route('orders.index') }}" class="back-link">← Back to Orders</a></p>

    <div class="order-info">
        <p><strong>Status:</strong> <span style="color: #27ae60; font-weight: bold;">{{ ucfirst($order->status) }}</span></p>
        <p><strong>Vendor:</strong> {{ $order->vendor->shop_name }}</p>
        <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
        <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
        <p><strong>Ordered on:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
    </div>

    <h3>Order Items</h3>
    @foreach($order->items as $item)
        <div class="order-item">
            <div class="item-info">
                <h4>{{ $item->product->name }}</h4>
                <p>Quantity: {{ $item->quantity }}</p>
            </div>
            <div class="item-price">₦{{ $item->price * $item->quantity }}</div>
        </div>
    @endforeach

    <div class="order-total">
        Total: ₦{{ $order->total_amount }}
    </div>
</div>
@endsection