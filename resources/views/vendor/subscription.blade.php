@extends('layouts.app')

@section('title', 'Vendor Subscription - PikFreshFood')

@section('content')
<div class="home-container" style="padding-top:24px; max-width:760px;">
    <div class="section-heading">Vendor Subscription</div>
    <p class="section-copy">New vendors get 1 month free trial. You can upgrade anytime to keep editing and uploading products.</p>

    @if(session('success'))
        <div style="margin-top:12px; padding:12px 14px; border-radius:12px; background:rgba(47,131,105,.12); color:var(--primary-color); font-weight:700;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="margin-top:12px; padding:12px 14px; border-radius:12px; background:rgba(192,57,43,.12); color:#c0392b; font-weight:700;">
            {{ session('error') }}
        </div>
    @endif

    <div class="vendor-card" style="padding:16px; margin-top:12px;">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap;">
            <div>
                <div style="color:var(--muted-color); font-size:.86rem;">Current Plan</div>
                <strong>{{ $vendor->subscription_plan === 'free' ? 'Free Trial' : 'Premium' }}</strong>
            </div>
            <div>
                <div style="color:var(--muted-color); font-size:.86rem;">Status</div>
                <strong style="color:{{ $isExpired ? '#c0392b' : 'var(--primary-color)' }};">
                    {{ $isExpired ? 'Expired' : 'Active' }}
                </strong>
            </div>
            <div>
                <div style="color:var(--muted-color); font-size:.86rem;">Expires</div>
                <strong>{{ $expiresAt ? $expiresAt->format('d M Y') : 'No expiry' }}</strong>
            </div>
        </div>
    </div>

    @if($isExpired)
        <div style="margin-top:12px; padding:12px 14px; border-radius:12px; background:rgba(192,57,43,.12); color:#c0392b; font-weight:700;">
            Free trial expired. Upgrade to continue adding or editing products.
        </div>
    @else
        <div style="margin-top:12px; padding:12px 14px; border-radius:12px; background:rgba(47,131,105,.12); color:var(--primary-color); font-weight:700;">
            Your trial is active. You can still upgrade now for premium visibility.
        </div>
    @endif

    <form action="{{ route('vendor.subscription.update') }}" method="POST" class="product-container" style="margin-top:16px;">
        @csrf
        <div style="display:grid; gap:12px;">
            @foreach($plans as $value => $plan)
                <label style="display:flex; gap:10px; align-items:flex-start; padding:14px; border:1px solid var(--border-color); border-radius:12px;">
                    <input type="radio" name="subscription_plan" value="{{ $value }}" {{ $vendor->subscription_plan === $value ? 'checked' : '' }}>
                    <span>
                        <strong>{{ $plan['label'] }}</strong><br>
                        <span style="color:var(--muted-color);">₦{{ number_format($plan['price']) }} • {{ $plan['months'] }} month{{ $plan['months'] > 1 ? 's' : '' }} premium access</span>
                    </span>
                </label>
            @endforeach
        </div>
        <button type="submit" class="add-to-cart-btn" style="margin-top:18px;">Upgrade Plan</button>
    </form>
</div>
@endsection
