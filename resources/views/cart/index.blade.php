@extends('layouts.app')

@section('title', 'Cart - PikFreshFood')

@php
    $subtotal = $carts->sum(fn ($cart) => $cart->product->price * $cart->quantity);
    $deliveryFee = $carts->isEmpty() ? 0 : 200;
    $total = $subtotal + $deliveryFee;
@endphp

@section('styles')
<style>
    .main-content { padding: 0 0 120px; }

    .cart-shell {
        max-width: 1180px;
        margin: 0 auto;
        min-height: calc(100vh - 150px);
        padding: 12px 16px 0;
    }

    .cart-topbar {
        background: var(--bottom-sheet-bg);
        color: var(--text-color);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.05rem;
        font-weight: 800;
        border: 1px solid var(--border-color);
        border-radius: 14px;
    }

    .cart-topbar a {
        color: inherit;
        text-decoration: none;
        font-size: 1.35rem;
        line-height: 1;
    }

    .cart-body { padding: 18px 0 0; }

    .cart-layout {
        display: grid;
        gap: 16px;
    }

    .cart-items-col {
        display: grid;
        gap: 12px;
        align-content: start;
    }

    .cart-summary-col {
        align-content: start;
    }

    .cart-empty,
    .cart-item-card,
    .cart-summary-card {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        color: var(--text-color);
    }

    .cart-empty {
        padding: 40px 24px;
        text-align: center;
    }

    .cart-empty p {
        color: var(--muted-color);
        margin-bottom: 16px;
        font-size: 1rem;
    }

    .cart-empty a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 48px;
        padding: 0 18px;
        border-radius: 12px;
        background: var(--primary-color);
        color: white;
        text-decoration: none;
        font-weight: 700;
    }

    .cart-item-card {
        display: grid;
        grid-template-columns: 88px 1fr auto;
        gap: 16px;
        align-items: center;
        padding: 14px 16px;
    }

    .cart-image {
        width: 80px;
        height: 80px;
        border-radius: 14px;
        overflow: hidden;
        background: linear-gradient(135deg, #f2e3da 0%, #d6c3bc 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-image-fallback {
        font-size: 1.9rem;
        color: #7a5d4f;
        font-weight: 800;
    }

    .cart-item-main { min-width: 0; }

    .cart-item-name {
        font-size: 1.2rem;
        line-height: 1.25;
        font-weight: 800;
        color: var(--text-color);
        margin-bottom: 4px;
    }

    .cart-item-vendor {
        color: var(--muted-color);
        font-size: 0.92rem;
        margin-bottom: 12px;
    }

    .cart-item-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .qty-form { margin: 0; }

    .qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid var(--border-color);
        color: var(--text-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
    }

    .qty-btn:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }

    .qty-value {
        min-width: 26px;
        text-align: center;
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-color);
    }

    .cart-item-side {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: space-between;
        min-height: 96px;
        min-width: 120px;
    }

    .delete-form { margin: 0; }

    .delete-btn {
        background: transparent;
        border: none;
        color: #ff4d4d;
        cursor: pointer;
        padding: 2px;
    }

    .delete-btn svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .cart-item-price { text-align: right; }

    .cart-item-total {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 4px;
    }

    .cart-item-unit {
        font-size: 0.88rem;
        color: var(--muted-color);
    }

    .cart-summary-card {
        padding: 20px 18px 22px;
    }

    .cart-summary-title {
        font-size: 1.05rem;
        font-weight: 800;
        margin-bottom: 14px;
        color: var(--text-color);
    }

    .cart-summary-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: center;
        padding: 9px 0;
        color: var(--muted-color);
        font-size: 0.95rem;
    }

    .cart-summary-row strong {
        color: var(--text-color);
        font-size: 0.95rem;
    }

    .cart-summary-row:last-child {
        border-top: 1px solid var(--border-color);
        margin-top: 8px;
        padding-top: 14px;
    }

    .cart-summary-row:last-child strong {
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .cart-footer {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 86px;
        background: #fff;
        border-top: 1px solid var(--border-color);
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        z-index: 95;
    }

    .cart-footer-label {
        color: var(--muted-color);
        font-size: 0.9rem;
        margin-bottom: 2px;
    }

    .cart-footer-total {
        color: var(--primary-color);
        font-size: 1.45rem;
        line-height: 1;
        font-weight: 800;
    }

    .checkout-btn {
        min-width: 190px;
        min-height: 44px;
        border-radius: 14px;
        background: var(--primary-color);
        color: #fff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 22px;
        font-size: 0.95rem;
        font-weight: 800;
    }

    .cart-flash {
        margin: 0 0 6px;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid color-mix(in srgb, var(--primary-color) 35%, #fff 65%);
        background: color-mix(in srgb, var(--primary-color) 10%, #fff 90%);
        color: var(--text-color);
    }

    @media (min-width: 1024px) {
        .main-content { padding-bottom: 24px; }
        .cart-shell { padding-top: 18px; }
        .cart-layout { grid-template-columns: minmax(0, 1fr) 340px; align-items: start; }
        .cart-summary-card { position: sticky; top: 114px; }
        .cart-footer { display: none; }
    }

    @media (max-width: 860px) {
        .cart-item-card {
            grid-template-columns: 80px 1fr;
        }

        .cart-item-side {
            grid-column: 1 / -1;
            min-height: auto;
            min-width: 0;
            flex-direction: row;
            align-items: center;
            margin-left: 96px;
        }

        .cart-item-name { font-size: 1.05rem; }
        .cart-item-vendor { font-size: 0.95rem; }
        .cart-item-total { font-size: 1.1rem; }
        .cart-footer { flex-direction: column; align-items: stretch; }
        .checkout-btn { width: 100%; }
    }
</style>
@endsection

@section('scripts')
<script>
    (function () {
        const qtyForms = document.querySelectorAll('[data-qty-form]');

        if (!qtyForms.length) {
            return;
        }

        const money = function (value) {
            return '₦' + Number(value);
        };

        const topbarCount = document.getElementById('cartTopbarCount');
        const subtotalLabel = document.getElementById('cartSubtotalLabel');
        const summaryValues = document.querySelectorAll('.cart-summary-row strong');
        const footerTotal = document.querySelector('.cart-footer-total');

        qtyForms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const button = form.querySelector('.qty-btn');
                const card = form.closest('[data-cart-item]');
                const qtyValue = card?.querySelector('[data-qty-value]');
                const itemTotal = card?.querySelector('.cart-item-total');
                const hiddenInput = form.querySelector('input[name="quantity"]');
                const siblingForms = card ? card.querySelectorAll('[data-qty-form]') : [];

                if (!button || !card || !qtyValue || !itemTotal || !hiddenInput) {
                    return;
                }

                const originalLabel = button.textContent;
                button.disabled = true;
                button.textContent = '...';

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: new FormData(form),
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Unable to update cart.');
                        }

                        return response.json();
                    })
                    .then(function (payload) {
                        const quantity = Number(payload.cart.quantity);

                        qtyValue.textContent = quantity;
                        itemTotal.textContent = money(payload.cart.item_total);

                        siblingForms.forEach(function (qtyForm, index) {
                            const qtyInput = qtyForm.querySelector('input[name="quantity"]');
                            const qtyButton = qtyForm.querySelector('.qty-btn');

                            if (!qtyInput || !qtyButton) {
                                return;
                            }

                            if (index === 0) {
                                qtyInput.value = Math.max(1, quantity - 1);
                                qtyButton.disabled = quantity <= 1;
                            } else {
                                qtyInput.value = quantity + 1;
                                qtyButton.disabled = false;
                            }
                        });

                        if (topbarCount) {
                            topbarCount.textContent = 'My Cart (' + payload.summary.cart_count + ')';
                        }

                        if (subtotalLabel) {
                            subtotalLabel.textContent = 'Subtotal (' + payload.summary.item_count + ' items)';
                        }

                        if (summaryValues[0]) {
                            summaryValues[0].textContent = money(payload.summary.subtotal);
                        }

                        if (summaryValues[1]) {
                            summaryValues[1].textContent = money(payload.summary.delivery_fee);
                        }

                        if (summaryValues[2]) {
                            summaryValues[2].textContent = money(payload.summary.total);
                        }

                        if (footerTotal) {
                            footerTotal.textContent = money(payload.summary.total);
                        }
                    })
                    .catch(function () {
                        window.alert('We could not update the cart right now.');
                    })
                    .finally(function () {
                        button.textContent = originalLabel;
                        button.disabled = false;
                    });
            });
        });
    })();
</script>
@endsection

@section('content')
<div class="cart-shell">
    <div class="cart-topbar">
        <a href="{{ url()->previous() }}">&larr;</a>
        <span id="cartTopbarCount">My Cart ({{ $carts->count() }})</span>
    </div>

    <div class="cart-body">
        @if(session('success'))
            <div class="cart-flash">
                {{ session('success') }}
            </div>
        @endif

        @if($carts->isEmpty())
            <div class="cart-empty">
                <p>Your cart is empty.</p>
                <a href="{{ route('home') }}">Continue Shopping</a>
            </div>
        @else
            <div class="cart-layout">
                <div class="cart-items-col">
                    @foreach($carts as $cart)
                        <div class="cart-item-card" data-cart-item data-cart-id="{{ $cart->id }}">
                            <div class="cart-image">
                                @if($cart->product->primary_image)
                                    <img src="{{ \App\Support\PublicStorage::url($cart->product->primary_image) }}" alt="{{ $cart->product->name }}">
                                @else
                                    <div class="cart-image-fallback">{{ strtoupper(substr($cart->product->name, 0, 1)) }}</div>
                                @endif
                            </div>

                            <div class="cart-item-main">
                                <div class="cart-item-name">{{ $cart->product->name }}</div>
                                <div class="cart-item-vendor">Sold by {{ $cart->product->vendor->shop_name }}</div>

                                <div class="cart-item-controls">
                                    <form action="{{ route('cart.update', $cart) }}" method="POST" class="qty-form" data-qty-form>
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="quantity" value="{{ max(1, $cart->quantity - 1) }}">
                                        <button type="submit" class="qty-btn" {{ $cart->quantity <= 1 ? 'disabled' : '' }}>&minus;</button>
                                    </form>

                                    <div class="qty-value" data-qty-value>{{ $cart->quantity }}</div>

                                    <form action="{{ route('cart.update', $cart) }}" method="POST" class="qty-form" data-qty-form>
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="quantity" value="{{ $cart->quantity + 1 }}">
                                        <button type="submit" class="qty-btn">+</button>
                                    </form>
                                </div>
                            </div>

                            <div class="cart-item-side">
                                <form action="{{ route('cart.destroy', $cart) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" aria-label="Remove {{ $cart->product->name }}">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M4 7h16"></path>
                                            <path d="M9 7V4h6v3"></path>
                                            <path d="M7 7l1 13h8l1-13"></path>
                                            <path d="M10 11v5"></path>
                                            <path d="M14 11v5"></path>
                                        </svg>
                                    </button>
                                </form>

                                <div class="cart-item-price">
                                    <div class="cart-item-total">₦{{ $cart->product->price * $cart->quantity }}</div>
                                    <div class="cart-item-unit">₦{{ $cart->product->price }}/{{ $cart->product->unit }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="cart-summary-col">
                    <div class="cart-summary-card">
                        <div class="cart-summary-title">Price Details</div>
                        <div class="cart-summary-row">
                            <span id="cartSubtotalLabel">Subtotal ({{ $carts->sum('quantity') }} items)</span>
                            <strong>₦{{ $subtotal }}</strong>
                        </div>
                        <div class="cart-summary-row">
                            <span>Delivery Fee</span>
                            <strong>₦{{ $deliveryFee }}</strong>
                        </div>
                        <div class="cart-summary-row">
                            <span>Total</span>
                            <strong>₦{{ $total }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@if(!$carts->isEmpty())
    <div class="cart-footer">
        <div>
            <div class="cart-footer-label">Total</div>
            <div class="cart-footer-total">₦{{ $total }}</div>
        </div>
        <a href="{{ route('checkout.index') }}" class="checkout-btn">Proceed to Checkout</a>
    </div>
@endif
@endsection
