@extends('layouts.app')

@section('title', 'My Orders - PikFreshFood')

@section('styles')
<style>
    .orders-container { max-width: 1000px; margin: 40px auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .orders-container h1 { color: #27ae60; margin-bottom: 30px; }
    .order { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; transition: box-shadow 0.2s; }
    .order:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .order-info h3 { margin: 0 0 5px 0; color: #333; }
    .order-info p { margin: 0; color: #7f8c8d; font-size: 14px; }
    .order-status { padding: 5px 12px; border-radius: 4px; color: white; font-size: 12px; font-weight: bold; }
    .status-pending { background: #f39c12; }
    .status-confirmed { background: #3498db; }
    .status-delivered { background: #27ae60; }
    .order-total { font-weight: bold; font-size: 18px; color: #27ae60; }
    .order-link { color: #27ae60; text-decoration: none; font-weight: bold; }
    .order-link:hover { text-decoration: underline; }
    .empty-orders { text-align: center; color: #7f8c8d; font-size: 18px; padding: 40px; }
</style>
@endsection

@section('content')
<div class="orders-container">
    <h1>My Orders</h1>
    @if($orders->isEmpty())
        <div class="empty-orders">
            <p>You have no orders yet.</p>
            <p style="margin-top: 20px;"><a href="{{ route('home') }}" style="color: #27ae60; text-decoration: none;">Start Shopping</a></p>
        </div>
    @else
        @foreach($orders as $order)
            <div class="order">
                <div class="order-header">
                    <div class="order-info">
                        <h3>Order #{{ $order->id }}</h3>
                        <p>From {{ $order->vendor->shop_name }}</p>
                        <p>{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <div style="text-align: right;">
                        <span class="order-status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                        <div class="order-total">₦{{ $order->total_amount }}</div>
                    </div>
                </div>
                <a href="{{ route('orders.show', $order) }}" class="order-link">View Details</a>
            </div>
        @endforeach
    @endif
</div>
@endsection
