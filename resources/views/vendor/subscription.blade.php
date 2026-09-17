@extends('layouts.app')

@section('title', 'Vendor Subscription - PikFreshFood')

@section('styles')
<style>
    .sub-back { display:inline-flex; align-items:center; gap:8px; text-decoration:none; color:var(--muted-color); font-weight:700; font-size:0.9rem; margin-bottom:16px; transition:color 0.15s; }
    .sub-back:hover { color:var(--primary-color); }
    .sub-back svg { width:18px; height:18px; stroke:currentColor; fill:none; stroke-width:2; stroke-linecap:round; stroke-linejoin:round; }
    .sub-hero { background:linear-gradient(135deg, var(--primary-color) 0%, #1a7a3a 55%, #0f5c28 100%); color:white; border-radius:20px; padding:22px 20px; position:relative; overflow:hidden; margin-bottom:14px; box-shadow:0 10px 24px rgba(22,132,71,0.22); }
    .sub-hero::after { content:''; position:absolute; top:-40px; right:-40px; width:140px; height:140px; background:radial-gradient(circle, var(--secondary-color) 0%, transparent 70%); opacity:0.35; }
    .sub-hero h1 { margin:0 0 6px; font-size:1.45rem; font-weight:900; position:relative; z-index:1; }
    .sub-hero p { margin:0; opacity:0.92; line-height:1.5; position:relative; z-index:1; font-size:0.94rem; }
    .sub-hero-badge { display:inline-flex; align-items:center; gap:6px; margin-top:12px; padding:7px 12px; border-radius:999px; background:var(--secondary-color); color:#111; font-weight:800; font-size:0.82rem; position:relative; z-index:1; }
    .sub-hero-badge svg { width:14px; height:14px; }
    .sub-card { background:var(--bottom-sheet-bg); border:1px solid var(--border-color); border-radius:16px; padding:16px; box-shadow:0 2px 10px var(--shadow-color); }
    .sub-status-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
    .sub-status-item { text-align:center; padding:10px; border-radius:12px; background:color-mix(in srgb, var(--primary-color) 6%, white 94%); border:1px solid color-mix(in srgb, var(--primary-color) 10%, transparent); }
    .sub-status-item strong { display:block; margin-top:4px; font-size:0.96rem; }
    .sub-plan-grid { display:grid; gap:12px; }
    .sub-plan { display:flex; gap:12px; align-items:flex-start; padding:16px; border:2px solid var(--border-color); border-radius:14px; cursor:pointer; transition:all 0.16s; background:white; position:relative; }
    .sub-plan:hover { border-color:color-mix(in srgb, var(--secondary-color) 60%, var(--border-color)); transform:translateY(-1px); box-shadow:0 6px 14px rgba(0,0,0,0.06); }
    .sub-plan:has(input:checked) { border-color:var(--secondary-color); background:linear-gradient(135deg, #fffdf0 0%, #fff9db 100%); box-shadow:0 8px 18px rgba(244,196,0,0.18); }
    .sub-plan input { margin-top:4px; accent-color:var(--primary-color); width:18px; height:18px; flex:0 0 auto; }
    .sub-plan-content { flex:1; min-width:0; }
    .sub-plan-title { font-weight:800; color:var(--text-color); font-size:0.98rem; display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .sub-plan-badge { display:inline-flex; padding:3px 8px; border-radius:999px; background:var(--secondary-color); color:#111; font-size:0.7rem; font-weight:800; letter-spacing:0.02em; text-transform:uppercase; }
    .sub-plan-price { color:var(--primary-color); font-weight:800; font-size:1.05rem; margin-top:2px; }
    .sub-plan-desc { color:var(--muted-color); font-size:0.86rem; margin-top:2px; line-height:1.45; }
    .sub-upgrade-btn { width:100%; min-height:56px; border:none; border-radius:14px; background:linear-gradient(135deg, var(--primary-color) 0%, #1e8a4a 100%); color:white; font-weight:800; font-size:1rem; display:inline-flex; align-items:center; justify-content:center; gap:10px; cursor:pointer; box-shadow:0 10px 20px rgba(22,132,71,0.26); transition:transform 0.14s, box-shadow 0.14s, filter 0.14s; margin-top:16px; position:relative; overflow:hidden; }
    .sub-upgrade-btn::before { content:''; position:absolute; left:0; top:0; bottom:0; width:56px; background:var(--secondary-color); opacity:0.95; border-radius:14px 0 0 14px; }
    .sub-upgrade-btn svg { width:22px; height:22px; position:relative; z-index:1; flex:0 0 auto; }
    .sub-upgrade-btn svg.icon-bg { background:var(--secondary-color); border-radius:50%; padding:4px; color:#111; width:28px; height:28px; }
    .sub-upgrade-btn span { position:relative; z-index:1; }
    .sub-upgrade-btn:hover { transform:translateY(-1px); box-shadow:0 14px 26px rgba(22,132,71,0.32); filter:brightness(1.03); }
    .sub-upgrade-btn:active { transform:translateY(0); }
    .sub-secure { display:flex; align-items:center; justify-content:center; gap:8px; margin-top:10px; color:var(--muted-color); font-size:0.82rem; }
    .sub-secure svg { width:14px; height:14px; }
    @media (max-width:640px){ .sub-status-grid{grid-template-columns:1fr; } .sub-hero{padding:18px 16px;} .sub-hero h1{font-size:1.25rem;} }
</style>
@endsection

@section('content')
<div class="home-container" style="padding-top:18px; max-width:760px;">
    <a href="{{ route('vendor.dashboard') }}" class="sub-back" aria-label="Back to dashboard">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 18l-6-6 6-6"></path><path d="M9 12h10"></path></svg>
        Back to Dashboard
    </a>
    <div class="sub-hero">
        <h1>Vendor Subscription</h1>
        <p>New vendors get 1 month free trial. Upgrade anytime to keep editing, uploading and boosting products with premium visibility.</p>
        <div class="sub-hero-badge">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2l3 7h7l-5.6 4 2.1 7L12 16l-6.5 4 2.1-7L2 9h7z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
            Trusted by vendors across Nigeria
        </div>
    </div>

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

    <div class="sub-card" style="margin-top:4px;">
        <div class="sub-status-grid">
            <div class="sub-status-item">
                <div style="color:var(--muted-color); font-size:.82rem; font-weight:700; text-transform:uppercase; letter-spacing:0.04em;">Current Plan</div>
                <strong>{{ $vendor->subscription_plan === 'free' ? 'Free Trial' : 'Premium' }}</strong>
            </div>
            <div class="sub-status-item">
                <div style="color:var(--muted-color); font-size:.82rem; font-weight:700; text-transform:uppercase; letter-spacing:0.04em;">Status</div>
                <strong style="color:{{ $isExpired ? '#c0392b' : 'var(--primary-color)' }};">{{ $isExpired ? 'Expired' : 'Active' }}</strong>
            </div>
            <div class="sub-status-item">
                <div style="color:var(--muted-color); font-size:.82rem; font-weight:700; text-transform:uppercase; letter-spacing:0.04em;">Expires</div>
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

    <form action="{{ route('vendor.subscription.update') }}" method="POST" class="sub-card" style="margin-top:16px;">
        @csrf
        <div class="sub-plan-grid">
            @foreach($plans as $value => $plan)
                <label class="sub-plan">
                    <input type="radio" name="subscription_plan" value="{{ $value }}" {{ $vendor->subscription_plan === $value ? 'checked' : '' }} required>
                    <span class="sub-plan-content">
                        <span class="sub-plan-title">{{ $plan['label'] }} @if($value==='premium_12m')<span class="sub-plan-badge">Best value</span>@elseif($value==='premium_6m')<span class="sub-plan-badge" style="background:var(--primary-color); color:white;">Popular</span>@endif</span>
                        <div class="sub-plan-price">₦{{ number_format($plan['price']) }}</div>
                        <div class="sub-plan-desc">{{ $plan['months'] }} month{{ $plan['months'] > 1 ? 's' : '' }} premium • Boosted visibility • Priority support • Unlimited edits</div>
                    </span>
                </label>
            @endforeach
        </div>
        <button type="submit" class="sub-upgrade-btn">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2l3 7h7l-5.6 4 2.1 7L12 16l-6.5 4 2.1-7L2 9h7z" stroke="white" stroke-width="1.8" stroke-linejoin="round"/><circle cx="12" cy="12" r="10" stroke="white" stroke-opacity="0.35" stroke-width="1.2"/></svg>
            <span>Pay &amp; Upgrade Securely</span>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true" style="width:18px;height:18px;"><path d="M5 12h14M13 6l6 6-6 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="sub-secure">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Payments securely processed via Paystack • 256-bit SSL • Instant activation
        </div>
    </form>
</div>
@endsection
