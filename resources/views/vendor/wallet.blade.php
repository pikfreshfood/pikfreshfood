@extends('layouts.app')

@section('title', 'Vendor Wallet - PikFreshFood')

@section('content')
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
