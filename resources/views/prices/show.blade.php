@extends('layouts.app')

@section('title', $name . ' Price in Nigeria - PikFreshFood')

@section('styles')
<style>
    .prices-page { max-width: 1240px; margin: 0 auto; padding: 24px 16px 10px; }
    .prices-back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 800;
    }
    .prices-back-link:hover { text-decoration: underline; }
    .prices-summary {
        margin-bottom: 24px;
        padding: 20px;
        border-radius: 20px;
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
    }
    .prices-summary h1 {
        margin: 0 0 10px;
        color: var(--text-color);
        font-size: clamp(1.5rem, 3vw, 2.2rem);
    }
    .prices-summary p {
        margin: 0;
        color: var(--muted-color);
        line-height: 1.6;
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
    .price-card-vendor {
        font-size: 0.9rem;
        color: var(--muted-color);
        line-height: 1.45;
    }
    .price-card-stock {
        font-size: 0.82rem;
        color: var(--muted-color);
    }
    .price-card-price {
        font-size: 1.05rem;
        font-weight: 900;
        color: var(--primary-color);
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
    <a href="{{ route('prices.index') }}" class="prices-back-link">Back to Prices</a>

    <div class="prices-summary">
        <h1>Price of {{ $name }} in Nigeria</h1>
        <p>Lowest price: ₦{{ $insights['lowest']->price }}. Highest price: ₦{{ $insights['highest']->price }}. Average market price: ₦{{ $insights['average'] }}.</p>
    </div>

    <div class="prices-grid">
        @foreach($products as $product)
            <a href="{{ route('product.show', $product) }}" class="price-card">
                <div class="price-card-media">
                    <img src="{{ $product->primary_image ? \App\Support\PublicStorage::url($product->primary_image) : 'https://placehold.co/520x520?text=' . urlencode($product->name) }}" alt="{{ $product->name }}">
                </div>
                <div class="price-card-body">
                    <div class="price-card-title">{{ $product->name }}</div>
                    <div class="price-card-vendor">Sold by {{ $product->vendor->shop_name }}</div>
                    <div class="price-card-stock">{{ $product->stock_quantity }} {{ $product->unit }} available</div>
                    <div class="price-card-price">₦{{ $product->price }}</div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
