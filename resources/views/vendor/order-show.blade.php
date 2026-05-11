@extends('layouts.app')

@section('title', 'Vendor Order #' . $order->id)

@section('styles')
<style>
    .vendor-order-page { max-width: 900px; margin: 30px auto; padding: 0 16px; }
    .vendor-order-shell {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 22px;
        padding: 24px;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.06);
    }
    .vendor-order-back { display: inline-flex; margin-bottom: 16px; color: var(--primary-color); text-decoration: none; font-weight: 700; }
    .vendor-order-title { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 18px; }
    .vendor-order-title h1 { margin: 0; color: var(--text-color); }
    .vendor-order-pill {
        display: inline-flex;
        align-items: center;
        min-height: 38px;
        padding: 0 14px;
        border-radius: 999px;
        color: white;
        font-weight: 800;
        background: #7f8c8d;
    }
    .vendor-order-pill.pending { background: #f39c12; }
    .vendor-order-pill.confirmed { background: #3498db; }
    .vendor-order-pill.delivered { background: #27ae60; }
    .vendor-order-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; margin-bottom: 20px; }
    .vendor-order-box {
        padding: 16px;
        border-radius: 16px;
        background: color-mix(in srgb, var(--primary-color) 8%, white 92%);
    }
    .vendor-order-box strong { display: block; margin-bottom: 6px; color: var(--text-color); }
    .vendor-order-box div { color: var(--muted-color); line-height: 1.6; }
    .vendor-order-items { margin: 20px 0; }
    .vendor-order-item {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 14px 0;
        border-bottom: 1px solid var(--border-color);
    }
    .vendor-order-item:last-child { border-bottom: none; }
    .vendor-order-item strong { color: var(--text-color); }
    .vendor-order-item span { color: var(--muted-color); }
    .vendor-order-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 20px; }
    .vendor-order-actions button {
        min-height: 44px;
        padding: 0 16px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: var(--bottom-sheet-bg);
        color: var(--text-color);
        font-weight: 700;
        cursor: pointer;
    }
    .vendor-order-actions button.primary {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    .vendor-order-note { margin-top: 18px; color: var(--muted-color); line-height: 1.6; }
    @media (max-width: 720px) {
        .vendor-order-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="vendor-order-page">
    <a href="{{ route('vendor.orders') }}" class="vendor-order-back">← Back to Orders</a>

    <div class="vendor-order-shell">
        <div class="vendor-order-title">
            <h1>Order #{{ $order->id }}</h1>
            <span class="vendor-order-pill {{ $order->status }}">{{ ucfirst($order->status) }}</span>
        </div>

        <div class="vendor-order-grid">
            <div class="vendor-order-box">
                <strong>Buyer</strong>
                <div>{{ $order->user?->name ?? 'Buyer' }}</div>
                <div>{{ $order->user?->email ?? 'No email available' }}</div>
            </div>
            <div class="vendor-order-box">
                <strong>Delivery</strong>
                <div>{{ $order->delivery_address }}</div>
                <div>Ordered on {{ $order->created_at->format('M d, Y H:i') }}</div>
            </div>
            <div class="vendor-order-box">
                <strong>Payment</strong>
                <div>{{ ucfirst($order->payment_method) }}</div>
                <div>Status: {{ ucfirst($order->payment_status) }}</div>
            </div>
            <div class="vendor-order-box">
                <strong>Total</strong>
                <div>₦{{ $order->total_amount }}</div>
                <div>{{ $order->items->sum('quantity') }} item(s)</div>
            </div>
        </div>

        <div class="vendor-order-items">
            @foreach($order->items as $item)
                <div class="vendor-order-item">
                    <div>
                        <strong>{{ $item->product?->name ?? 'Product removed' }}</strong>
                        <span>Quantity: {{ $item->quantity }}</span>
                    </div>
                    <strong>₦{{ $item->price * $item->quantity }}</strong>
                </div>
            @endforeach
        </div>

        <div class="vendor-order-actions">
            @if($order->status === 'pending')
                <form action="{{ route('vendor.orders.status', $order) }}" method="POST" style="margin:0;">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="primary">Approve Order</button>
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
</div>
@endsection
