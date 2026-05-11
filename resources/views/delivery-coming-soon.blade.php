@extends('layouts.app')

@section('title', 'Delivery Services Coming Soon')

@section('styles')
<style>
    .coming-soon-wrap {
        max-width: 980px;
        margin: 26px auto 40px;
        padding: 0 16px;
    }
    .coming-soon-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    }
    .coming-soon-card img {
        width: 100%;
        height: auto;
        display: block;
    }
    .coming-soon-body {
        padding: 20px;
    }
    .coming-soon-title {
        margin: 0 0 8px;
        color: var(--text-color);
        font-weight: 800;
    }
    .coming-soon-copy {
        margin: 0;
        color: var(--muted-color);
    }
    .coming-soon-link {
        margin-top: 14px;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        color: #0f6b37;
        font-weight: 700;
    }
    .coming-soon-link:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="coming-soon-wrap">
    <div class="coming-soon-card">
        <img src="{{ asset('images/delivery-services-coming-soon.png') }}" alt="Delivery services coming soon">
        <div class="coming-soon-body">
            <h1 class="coming-soon-title">Delivery Services Coming Soon</h1>
            <p class="coming-soon-copy">We are preparing faster delivery options for your orders. Stay tuned for launch updates.</p>
            <a href="{{ route('home') }}" class="coming-soon-link">Back to home</a>
        </div>
    </div>
</div>
@endsection
