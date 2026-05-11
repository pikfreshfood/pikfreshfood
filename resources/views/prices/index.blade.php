@extends('layouts.app')

@section('title', 'Price Pages - PikFreshFood')

@section('styles')
<style>
    .prices-page { max-width: 1240px; margin: 0 auto; padding: 24px 16px 10px; }
    .prices-hero { margin-bottom: 20px; }
    .prices-hero h1 { margin: 0 0 8px; color: var(--text-color); font-size: clamp(1.5rem, 3vw, 2.2rem); }
    .prices-hero p { margin: 0; color: var(--muted-color); line-height: 1.6; max-width: 760px; }
    .prices-categories { display: flex; flex-wrap: wrap; gap: 10px; margin: 18px 0 24px; }
    .prices-category-pill {
        padding: 8px 14px;
        border-radius: 999px;
        background: color-mix(in srgb, var(--primary-color) 10%, white 90%);
        color: var(--text-color);
        border: 1px solid color-mix(in srgb, var(--primary-color) 18%, var(--border-color) 82%);
        font-size: 0.88rem;
        font-weight: 700;
    }
    .prices-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }
    .price-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.06);
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }
    .price-card:hover {
        transform: translateY(-3px);
        border-color: color-mix(in srgb, var(--primary-color) 30%, var(--border-color) 70%);
        box-shadow: 0 16px 30px rgba(0, 0, 0, 0.1);
    }
    .price-card-media {
        aspect-ratio: 1 / 1;
        background: color-mix(in srgb, var(--primary-color) 12%, white 88%);
        overflow: hidden;
    }
    .price-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .price-card-body {
        display: grid;
        gap: 8px;
        padding: 14px;
    }
    .price-card-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--text-color);
        line-height: 1.35;
    }
    .price-card-range {
        font-size: 0.92rem;
        color: var(--muted-color);
        line-height: 1.45;
    }
    .price-card-meta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: fit-content;
        min-height: 30px;
        padding: 0 12px;
        border-radius: 999px;
        background: color-mix(in srgb, var(--primary-color) 12%, white 88%);
        color: var(--primary-color);
        font-size: 0.78rem;
        font-weight: 800;
    }
    @media (min-width: 900px) {
        .prices-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }
    }
</style>
@endsection

@section('content')
<div class="prices-page">
    <div class="prices-hero">
        <h1>Live Price Pages</h1>
        <p>Track current vendor prices, compare market ranges, and open any product page directly from the grid below.</p>
    </div>

    <div class="prices-categories">
        @foreach($categories as $category)
            <span class="prices-category-pill">{{ ucfirst($category) }}</span>
        @endforeach
    </div>

    <div class="prices-grid">
        @foreach($productGroups as $group)
            <a href="{{ route('prices.show', $group['slug']) }}" class="price-card">
                <div class="price-card-media">
                    <img src="{{ $group['image'] ? \App\Support\PublicStorage::url($group['image']) : 'https://placehold.co/520x520?text=' . urlencode($group['name']) }}" alt="{{ $group['name'] }}">
                </div>
                <div class="price-card-body">
                    <div class="price-card-title">{{ $group['name'] }}</div>
                    <div class="price-card-range">From ₦{{ $group['min_price'] }} to ₦{{ $group['max_price'] }}</div>
                    <div class="price-card-meta">{{ $group['vendors'] }} vendor prices live</div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
