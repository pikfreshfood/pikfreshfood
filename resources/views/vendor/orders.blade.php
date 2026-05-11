@extends('layouts.app')

@section('title', 'Vendor Orders - PikFreshFood')

@section('styles')
<style>
    .vendor-orders-page { max-width: 1100px; margin: 30px auto; padding: 0 16px; }
    .vendor-orders-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
    .vendor-orders-head h1 { color: var(--primary-color); margin: 0; }
    .vendor-orders-back { color: var(--primary-color); text-decoration: none; font-weight: 700; }
    .vendor-orders-grid { display: grid; gap: 16px; }
    .vendor-order-card {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.06);
    }
    .vendor-order-top { display: flex; justify-content: space-between; gap: 12px; align-items: flex-start; margin-bottom: 10px; }
    .vendor-order-top h2 { margin: 0 0 6px; font-size: 1.05rem; color: var(--text-color); }
    .vendor-order-copy { color: var(--muted-color); line-height: 1.6; }
    .vendor-order-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 98px;
        min-height: 36px;
        padding: 0 12px;
        border-radius: 999px;
        font-weight: 800;
        color: white;
        background: #7f8c8d;
    }
    .vendor-order-status.pending { background: #f39c12; }
    .vendor-order-status.confirmed { background: #3498db; }
    .vendor-order-status.delivered { background: #27ae60; }
    .vendor-order-meta { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; margin: 16px 0; }
    .vendor-order-meta-item {
        padding: 12px 14px;
        border-radius: 14px;
        background: color-mix(in srgb, var(--primary-color) 8%, white 92%);
    }
    .vendor-order-meta-item strong { display: block; margin-bottom: 4px; color: var(--text-color); }
    .vendor-order-actions { display: flex; gap: 10px; flex-wrap: wrap; }
    .vendor-order-link,
    .vendor-order-actions button {
        min-height: 42px;
        padding: 0 16px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: var(--bottom-sheet-bg);
        color: var(--text-color);
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }
    .vendor-order-actions button.primary,
    .vendor-order-link.primary {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    .vendor-order-empty {
        padding: 24px;
        text-align: center;
        color: var(--muted-color);
        border-radius: 18px;
        border: 1px solid var(--border-color);
        background: var(--bottom-sheet-bg);
    }
    @media (max-width: 720px) {
        .vendor-order-top { flex-direction: column; }
        .vendor-order-meta { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="vendor-orders-page">
    <div class="vendor-orders-head">
        <h1>Vendor Orders</h1>
        <a href="{{ route('vendor.dashboard') }}" class="vendor-orders-back">Back to Dashboard</a>
    </div>

    <div class="vendor-orders-grid">
        @forelse($orders as $order)
            <div class="vendor-order-card">
                <div class="vendor-order-top">
                    <div>
                        <h2>Order #{{ $order->id }}</h2>
                        <div class="vendor-order-copy">Buyer: {{ $order->user?->name ?? 'Buyer' }}</div>
                        <div class="vendor-order-copy">Address: {{ $order->delivery_address }}</div>
                    </div>
                    <span class="vendor-order-status {{ $order->status }}">{{ ucfirst($order->status) }}</span>
                </div>

                <div class="vendor-order-meta">
                    <div class="vendor-order-meta-item">
                        <strong>Total</strong>
                        ₦{{ $order->total_amount }}
                    </div>
                    <div class="vendor-order-meta-item">
                        <strong>Payment</strong>
                        {{ ucfirst($order->payment_method) }} / {{ ucfirst($order->payment_status) }}
                    </div>
                    <div class="vendor-order-meta-item">
                        <strong>Items</strong>
                        {{ $order->items->sum('quantity') }}
                    </div>
                </div>

                <div class="vendor-order-actions">
                    <a href="{{ route('vendor.orders.show', $order) }}" class="vendor-order-link primary">Open Order</a>

                    @if($order->status === 'pending')
                        <form action="{{ route('vendor.orders.status', $order) }}" method="POST" style="margin:0;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit">Approve Order</button>
                        </form>
                    @endif

                    @if($order->status !== 'delivered')
                        <form action="{{ route('vendor.orders.status', $order) }}" method="POST" style="margin:0;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="delivered">
                            <button type="submit">Mark Delivered</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="vendor-order-empty">No buyer orders yet.</div>
        @endforelse
    </div>
</div>
@endsection
