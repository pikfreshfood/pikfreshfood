@extends('layouts.app')

@section('title', 'Street Food - PikFreshFood')

@section('content')
<div class="home-container" style="padding-top:24px;">
    <div class="section-heading">Street Food Near You</div>
    <p class="section-copy">Vendors selling hot roasted and ready-to-eat items right now.</p>
    <div class="vendor-strip">
        @forelse($vendors as $entry)
            <a href="{{ route('vendor.show', $entry['vendor']) }}" class="vendor-card">
                <strong>{{ $entry['vendor']->shop_name }}</strong>
                <div class="live-badge">🟢 Selling Now</div>
                <div class="vendor-meta">{{ $entry['products']->pluck('name')->implode(', ') }}</div>
            </a>
        @empty
            <div class="vendor-card">No roasted food vendors are live right now.</div>
        @endforelse
    </div>
</div>
@endsection
