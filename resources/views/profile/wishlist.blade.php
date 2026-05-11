@extends('layouts.app')

@section('title', 'Watch List - PikFreshFood')

@section('styles')
<style>
    .wishlist-page { max-width: 1180px; margin: 0 auto; padding: 24px 16px 18px; }
    .wishlist-topbar { margin-bottom: 18px; }
    .wishlist-title { margin: 0 0 8px; color: var(--text-color); font-size: clamp(1.5rem, 3vw, 2rem); }
    .wishlist-copy { margin: 0; color: var(--muted-color); line-height: 1.6; }
    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-top: 22px;
    }
    .wishlist-card {
        display: flex;
        flex-direction: column;
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.06);
    }
    .wishlist-card-media {
        aspect-ratio: 1 / 1;
        background: color-mix(in srgb, var(--primary-color) 12%, white 88%);
    }
    .wishlist-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .wishlist-card-body {
        display: grid;
        gap: 8px;
        padding: 14px;
    }
    .wishlist-card-title {
        color: var(--text-color);
        font-size: 1rem;
        font-weight: 800;
        line-height: 1.35;
        text-decoration: none;
    }
    .wishlist-card-vendor,
    .wishlist-card-stock {
        color: var(--muted-color);
        font-size: 0.9rem;
    }
    .wishlist-card-price {
        color: var(--primary-color);
        font-size: 1.05rem;
        font-weight: 900;
    }
    .wishlist-card-actions {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
        margin-top: 6px;
    }
    .wishlist-action,
    .wishlist-remove {
        min-height: 42px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-weight: 800;
        border: 1px solid var(--border-color);
        cursor: pointer;
    }
    .wishlist-action {
        background: var(--primary-color);
        color: white;
    }
    .wishlist-remove {
        background: transparent;
        color: #d64545;
    }
    .wishlist-empty {
        margin-top: 22px;
        padding: 24px;
        border-radius: 18px;
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        color: var(--muted-color);
    }
    @media (min-width: 900px) {
        .wishlist-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }
    }
</style>
@endsection

@section('content')
<div class="wishlist-page">
    <div class="wishlist-topbar">
        <h1 class="wishlist-title">Watch List</h1>
        <p class="wishlist-copy">Products you want to keep an eye on stay here until you are ready to buy them.</p>
    </div>

    @if(session('success'))
        <div class="wishlist-empty" style="margin-top:0; margin-bottom:18px; color:#1f7a43; border-color:#cce7d5; background:#eef8f1;">
            {{ session('success') }}
        </div>
    @endif

    @if($wishlistItems->isEmpty())
        <div class="wishlist-empty">
            Your watch list is empty. Save products from any product page and they will appear here.
        </div>
    @else
        <div class="wishlist-grid">
            @foreach($wishlistItems as $wishlistItem)
                @php($product = $wishlistItem->product)
                <div class="wishlist-card">
                    <div class="wishlist-card-media">
                        <img src="{{ $product->primary_image ? \App\Support\PublicStorage::url($product->primary_image) : 'https://placehold.co/520x520?text=' . urlencode($product->name) }}" alt="{{ $product->name }}">
                    </div>
                    <div class="wishlist-card-body">
                        <a href="{{ route('product.show', $product) }}" class="wishlist-card-title">{{ $product->name }}</a>
                        <div class="wishlist-card-vendor">Sold by {{ $product->vendor->shop_name }}</div>
                        <div class="wishlist-card-stock">{{ $product->stock_quantity }} {{ $product->unit }} in stock</div>
                        <div class="wishlist-card-price">₦{{ $product->price }}</div>
                        <div class="wishlist-card-actions">
                            <a href="{{ route('product.show', $product) }}" class="wishlist-action">View Product</a>
                            <form action="{{ route('wishlist.destroy', $product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="wishlist-remove">Remove from Watch List</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
