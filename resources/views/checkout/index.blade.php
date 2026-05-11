@extends('layouts.app')

@section('title', 'Checkout - PikFreshFood')

@section('styles')
<style>
    .main-content { padding: 0 0 120px; }

    .checkout-shell {
        max-width: 1180px;
        margin: 0 auto;
        padding: 14px 16px 0;
    }

    .checkout-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .checkout-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text-color);
        margin: 0;
    }

    .checkout-note {
        margin-bottom: 14px;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid color-mix(in srgb, var(--primary-color) 35%, #fff 65%);
        background: color-mix(in srgb, var(--primary-color) 10%, #fff 90%);
        color: var(--text-color);
        line-height: 1.5;
        font-size: 0.92rem;
    }

    .checkout-layout {
        display: grid;
        gap: 14px;
    }

    .checkout-card {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 18px 16px;
    }

    .checkout-card h3 {
        margin: 0 0 12px;
        color: var(--text-color);
        font-size: 1.02rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid var(--border-color);
        color: var(--muted-color);
        font-size: 0.95rem;
    }

    .summary-item:last-child {
        border-bottom: 0;
    }

    .summary-total {
        margin-top: 10px;
        padding-top: 12px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 800;
        color: var(--text-color);
    }

    .summary-total strong {
        color: var(--primary-color);
        font-size: 1.15rem;
    }

    .checkout-form {
        display: grid;
        gap: 12px;
    }

    .checkout-field {
        display: grid;
        gap: 6px;
    }

    .checkout-label {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-color);
    }

    .checkout-input,
    .checkout-select {
        width: 100%;
        min-height: 44px;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        font-size: 0.95rem;
        color: var(--text-color);
        background: #fff;
    }

    .checkout-button {
        min-height: 46px;
        border: none;
        border-radius: 12px;
        background: var(--primary-color);
        color: #fff;
        padding: 0 16px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 800;
    }

    .checkout-button:hover {
        filter: brightness(0.95);
    }

    @media (min-width: 1024px) {
        .main-content { padding-bottom: 24px; }

        .checkout-layout {
            grid-template-columns: 380px minmax(0, 1fr);
            align-items: start;
        }

        .checkout-card.sticky {
            position: sticky;
            top: 114px;
        }
    }
</style>
@endsection

@section('content')
<div class="checkout-shell">
    <div class="checkout-head">
        <h1 class="checkout-title">Checkout</h1>
    </div>

    <div class="checkout-note">
        Payment-ready flow enabled for Paystack, Flutterwave, wallet, and cash on delivery. External gateway callbacks can be connected next.
    </div>

    <div class="checkout-layout">
        <div class="checkout-card sticky">
            <h3>Order Summary</h3>
            @foreach($carts as $cart)
                <div class="summary-item">
                    <span>{{ $cart->product->name }} (x{{ $cart->quantity }})</span>
                    <span>₦{{ $cart->product->price * $cart->quantity }}</span>
                </div>
            @endforeach
            <div class="summary-total">
                <span>Total</span>
                <strong>₦{{ $total }}</strong>
            </div>
        </div>

        <div class="checkout-card">
            <h3>Delivery & Payment</h3>
            <form action="{{ route('checkout.store') }}" method="POST" class="checkout-form">
                @csrf
                <div class="checkout-field">
                    <label class="checkout-label" for="delivery_address">Delivery Address</label>
                    <input
                        id="delivery_address"
                        type="text"
                        name="delivery_address"
                        placeholder="Delivery Address"
                        value="{{ auth()->user()->address }}"
                        class="checkout-input"
                        required
                    >
                </div>

                <div class="checkout-field">
                    <label class="checkout-label" for="payment_method">Payment Method</label>
                    <select id="payment_method" name="payment_method" class="checkout-select" required>
                        <option value="paystack">Paystack</option>
                        <option value="flutterwave">Flutterwave</option>
                        <option value="wallet">Wallet</option>
                        <option value="cash">Cash on Delivery</option>
                    </select>
                </div>

                <button type="submit" class="checkout-button">Place Order</button>
            </form>
        </div>
    </div>
</div>
@endsection
