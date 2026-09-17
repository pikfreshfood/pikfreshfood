@extends('layouts.app')

@section('title', 'Vendor Wallet - PikFreshFood')

@section('content')
<div style="max-width:1100px; margin:14px auto 0; padding:0 16px;">
<a href="{{ route('vendor.dashboard') }}" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none; color:var(--muted-color); font-weight:700; font-size:0.9rem; margin:14px 0 12px;">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 12h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        Back to Dashboard
    </a>
</div>
<div class="home-container" style="padding-top:24px;">
    <div class="section-heading">Vendor Wallet</div>
    <div class="vendor-card" style="margin-bottom:18px;">
        <strong>Available Balance</strong>
        <div class="product-price" style="margin-top:8px;">₦{{ number_format((float) $vendor->wallet_balance, 2) }}</div>
        <div class="vendor-meta">Use this as the settlement overview while payment rails are being integrated.</div>
    </div>
    <div class="section-heading">Transaction History</div>
    <div class="vendor-strip">
        @forelse($transactions as $transaction)
            <div class="vendor-card">
                <strong>{{ ucfirst($transaction->type) }}</strong>
                <div class="vendor-meta">{{ $transaction->description ?: 'Wallet activity' }}</div>
                <div class="product-price">₦{{ $transaction->amount }}</div>
            </div>
        @empty
            <div class="vendor-card">No wallet transactions yet.</div>
        @endforelse
    </div>
</div>
@endsection
