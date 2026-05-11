@extends('layouts.app')

@section('title', ucfirst($category) . ' - PikFreshFood')

@section('styles')
<style>
    .category-container { max-width: 1200px; margin: 40px auto; padding: 0 16px; }
    .category-container h1 { color: var(--primary-color); margin-bottom: 10px; text-align: center; }
    .category-copy { color: var(--muted-color); text-align: center; margin: 0 0 28px; }
    .products { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
    .product { background: var(--bottom-sheet-bg); padding: 25px; border-radius: 16px; border: 1px solid var(--border-color); box-shadow: 0 2px 10px rgba(0,0,0,0.06); transition: transform 0.2s; }
    .product:hover { transform: translateY(-2px); }
    .product img { width: 100%; height: 180px; object-fit: cover; border-radius: 10px; margin-bottom: 15px; }
    .product h3 { margin: 0 0 10px 0; font-size: 18px; }
    .product h3 a { color: var(--text-color); text-decoration: none; }
    .product h3 a:hover { color: var(--primary-color); }
    .product p { color: var(--muted-color); margin: 0 0 15px 0; line-height: 1.4; }
    .price { font-weight: bold; color: var(--primary-color); font-size: 18px; margin-bottom: 10px; }
    .vendor { color: var(--muted-color); font-size: 14px; }
    .vendor-status { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700; margin-top: 10px; }
    .vendor-status.is-live { background: rgba(39, 174, 96, 0.12); color: #1f7a43; }
    .vendor-status.is-offline { background: rgba(127, 140, 141, 0.14); color: #667085; }
    .back-link { color: var(--primary-color); text-decoration: none; font-weight: bold; margin-bottom: 20px; display: inline-block; }
    .back-link:hover { text-decoration: underline; }
    .empty-state {
        padding: 24px;
        text-align: center;
        color: var(--muted-color);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        background: var(--bottom-sheet-bg);
    }
</style>
@endsection

@section('content')
<div class="category-container">
    <a href="{{ route('home') }}" class="back-link">Back to Home</a>

    <h1>{{ ucfirst($category) }} Products</h1>
    <p class="category-copy">Showing every available product in this category, whether the vendor is live right now or not.</p>

    <div class="products">
        @forelse($products as $product)
            <div class="product">
                <img src="{{ $product->primary_image ? \App\Support\PublicStorage::url($product->primary_image) : 'https://placehold.co/640x480?text=' . urlencode($product->name) }}" alt="{{ $product->name }}">
                <h3><a href="{{ route('product.show', $product) }}">{{ $product->name }}</a></h3>
                <p>{{ Str::limit($product->description, 100) }}</p>
                <div class="price">₦{{ $product->price }}</div>
                <div class="vendor">By {{ $product->vendor->shop_name }}</div>
                <div class="vendor-status {{ $product->vendor->is_live ? 'is-live' : 'is-offline' }}">
                    {{ $product->vendor->is_live ? 'Live Now' : 'Offline' }}
                </div>
            </div>
        @empty
            <div class="empty-state">No available products were found in this category yet.</div>
        @endforelse
    </div>
</div>
@endsection
