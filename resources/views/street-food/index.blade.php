@extends('layouts.app')

@section('title', 'Street Food - PikFreshFood')

@section('content')
<div style="max-width:1100px; margin:14px auto 0; padding:0 16px;">
<a href="{{ route('home') }}" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none; color:var(--muted-color); font-weight:700; font-size:0.9rem; margin:14px 0 12px;">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 12h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        Back to Home
    </a>
</div>
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
