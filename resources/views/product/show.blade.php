@extends('layouts.app')

@section('title', $product->name . ' - PikFreshFood')

@section('styles')
<style>
    .product-container {
        max-width: 860px;
        margin: 40px auto;
        background: white;
        padding: 40px;
        border-radius: 18px;
        box-shadow: 0 10px 28px rgba(0,0,0,0.08);
    }
    .product-gallery { display: grid; gap: 14px; margin-bottom: 22px; }
    .product-gallery-stage { position: relative; overflow: hidden; border-radius: 16px; background: #f4f7f5; }
    .product-gallery-track { display: flex; transition: transform 0.3s ease; }
    .product-gallery-slide { min-width: 100%; }
    .product-gallery-slide img { width: 100%; height: 360px; object-fit: cover; display: block; }
    .product-gallery-nav { position: absolute; inset: 0; display: flex; align-items: center; justify-content: space-between; padding: 0 12px; pointer-events: none; }
    .product-gallery-nav button { pointer-events: auto; width: 42px; height: 42px; border: none; border-radius: 50%; background: rgba(0, 0, 0, 0.62); color: white; font-size: 1.2rem; cursor: pointer; }
    .product-gallery-nav button[disabled] { opacity: 0.35; cursor: not-allowed; }
    .product-gallery-thumbs { display: grid; grid-template-columns: repeat(auto-fill, minmax(78px, 1fr)); gap: 10px; }
    .product-gallery-thumb { border: 2px solid transparent; border-radius: 12px; overflow: hidden; padding: 0; background: none; cursor: pointer; }
    .product-gallery-thumb.is-active { border-color: #27ae60; }
    .product-gallery-thumb img { width: 100%; height: 78px; object-fit: cover; display: block; }
    .product-container h1 { color: #27ae60; margin-bottom: 10px; }
    .product-description { color: #555; margin-bottom: 20px; line-height: 1.6; }
    .product-price { font-size: 28px; font-weight: bold; color: #27ae60; margin-bottom: 10px; }
    .product-vendor { color: #666; margin-bottom: 10px; }
    .product-stock { color: #7f8c8d; margin-bottom: 20px; }
    .product-form { margin-top: 30px; display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
    .quantity-input { padding: 12px; width: 80px; border: 1px solid #ddd; border-radius: 8px; }
    .add-to-cart-btn { background: #27ae60; color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; }
    .add-to-cart-btn:hover { background: #229954; }
    .login-link { color: #27ae60; text-decoration: none; font-weight: bold; }
    .login-link:hover { text-decoration: underline; }
    .back-link { color: #27ae60; text-decoration: none; font-weight: bold; margin-bottom: 20px; display: inline-block; }
    .back-link:hover { text-decoration: underline; }
    .buyer-note { margin-top: 24px; padding: 14px 16px; border-radius: 12px; background: #f5f7f6; color: #4f5d56; border: 1px solid #dde7e1; }
    .product-actions-row { display:flex; gap:10px; flex-wrap:wrap; margin-top:16px; }
    .ghost-action { display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:0 14px; border-radius:10px; border:1px solid #d8e2dc; text-decoration:none; color:#27513b; font-weight:700; }
    .product-insights, .related-products { margin-top: 26px; display: grid; gap: 14px; }
    .insights-grid, .related-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
    .insight-card, .related-card { border: 1px solid #e3e7e4; border-radius: 14px; padding: 14px; background: #fbfcfb; }
    .related-card img { width: 100%; height: 120px; object-fit: cover; border-radius: 10px; margin-bottom: 10px; }
    @media (max-width: 640px) {
        .product-container { margin: 20px 12px; padding: 20px; }
        .product-gallery-slide img { height: 240px; }
        .product-gallery-thumb img { height: 68px; }
        .insights-grid, .related-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@php
    $galleryImages = $product->image_gallery;
@endphp

@section('content')
<div class="product-container">
    <a href="{{ route('home') }}" class="back-link">Back to Products</a>

    <div class="product-gallery" id="productGallery">
        <div class="product-gallery-stage">
            <div class="product-gallery-track" id="productGalleryTrack">
                @foreach($galleryImages as $image)
                    <div class="product-gallery-slide">
                        <img src="{{ \App\Support\PublicStorage::url($image) }}" alt="{{ $product->name }}">
                    </div>
                @endforeach
            </div>

            @if(count($galleryImages) > 1)
                <div class="product-gallery-nav">
                    <button type="button" id="productGalleryPrev" aria-label="Previous image">&lsaquo;</button>
                    <button type="button" id="productGalleryNext" aria-label="Next image">&rsaquo;</button>
                </div>
            @endif
        </div>

        @if(count($galleryImages) > 1)
            <div class="product-gallery-thumbs" id="productGalleryThumbs">
                @foreach($galleryImages as $image)
                    <button type="button" class="product-gallery-thumb{{ $loop->first ? ' is-active' : '' }}">
                        <img src="{{ \App\Support\PublicStorage::url($image) }}" alt="{{ $product->name }} thumbnail {{ $loop->iteration }}">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <h1>{{ $product->name }}</h1>
    <p class="product-description">{{ $product->description }}</p>
    <div class="product-price">₦{{ $product->price }}</div>
    <div class="product-vendor">Sold by {{ $product->vendor->shop_name }}</div>
    <div class="product-stock">Stock: {{ $product->stock_quantity }} {{ $product->unit }}</div>
    <div class="product-actions-row">
        <a href="{{ route('vendor.show', $product->vendor) }}" class="ghost-action">View Vendor</a>
        @auth
            @if(auth()->id() !== $product->vendor->user_id)
                <a href="{{ route('messages.show', $product->vendor->user_id) }}" class="ghost-action">Message Vendor</a>
            @endif
        @endauth
        <a href="{{ route('prices.show', \Illuminate\Support\Str::slug(\Illuminate\Support\Str::lower($product->name))) }}" class="ghost-action">Compare Prices</a>
    </div>

    @auth
        @if(auth()->user()->isBuyer())
            <form action="{{ route('cart.store') }}" method="POST" class="product-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <label>Quantity: <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="quantity-input"></label>
                <button type="submit" class="add-to-cart-btn">Add to Cart</button>
            </form>
            <div class="product-actions-row" style="margin-top:12px;">
                @if($isInWishlist)
                    <form action="{{ route('wishlist.destroy', $product) }}" method="POST" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ghost-action" style="background:#fff3f3; color:#b73b3b; cursor:pointer;">Remove from Watch List</button>
                    </form>
                @else
                    <form action="{{ route('wishlist.store', $product) }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="ghost-action" style="background:#f5f7f6; cursor:pointer;">Add to Watch List</button>
                    </form>
                @endif
            </div>
        @else
            <div class="buyer-note">Only buyer accounts can add items to cart.</div>
        @endif
    @else
        <p><a href="{{ route('login') }}" class="login-link">Login</a> to add to cart or open your watch list.</p>
    @endauth

    <div class="product-insights">
        <div class="section-heading" style="font-size:1.05rem; margin-bottom:0;">Dynamic Pricing Insights</div>
        <div class="insights-grid">
            <div class="insight-card">
                <strong>Cheapest Nearby</strong>
                <div class="product-price" style="font-size:1.1rem; margin:8px 0 4px;">₦{{ $priceInsights['cheapest']?->price ?? $product->price }}</div>
                <div class="product-vendor">{{ $priceInsights['cheapest']?->vendor->shop_name ?? $product->vendor->shop_name }}</div>
            </div>
            <div class="insight-card">
                <strong>Average Price</strong>
                <div class="product-price" style="font-size:1.1rem; margin:8px 0 4px;">₦{{ $priceInsights['average'] }}</div>
                <div class="product-vendor">Across similar products</div>
            </div>
            <div class="insight-card">
                <strong>Nearest Seller</strong>
                <div class="product-price" style="font-size:1.1rem; margin:8px 0 4px;">{{ $priceInsights['nearest']?->vendor->shop_name ?? $product->vendor->shop_name }}</div>
                <div class="product-vendor">Closest option in this category</div>
            </div>
        </div>
    </div>

    @if($relatedProducts->isNotEmpty())
        <div class="related-products">
            <div class="section-heading" style="font-size:1.05rem; margin-bottom:0;">People Also Bought</div>
            <div class="related-grid">
                @foreach($relatedProducts as $relatedProduct)
                    <a href="{{ route('product.show', $relatedProduct) }}" class="related-card" style="text-decoration:none; color:inherit;">
                        <img src="{{ $relatedProduct->primary_image ? \App\Support\PublicStorage::url($relatedProduct->primary_image) : 'https://placehold.co/320x220?text=' . urlencode($relatedProduct->name) }}" alt="{{ $relatedProduct->name }}">
                        <strong>{{ $relatedProduct->name }}</strong>
                        <div class="product-vendor">{{ $relatedProduct->vendor->shop_name }}</div>
                        <div class="product-price" style="font-size:1rem; margin-top:8px;">₦{{ $relatedProduct->price }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const track = document.getElementById('productGalleryTrack');
        const prevButton = document.getElementById('productGalleryPrev');
        const nextButton = document.getElementById('productGalleryNext');
        const thumbs = Array.from(document.querySelectorAll('.product-gallery-thumb'));

        if (!track || thumbs.length === 0) {
            return;
        }

        let currentIndex = 0;

        function renderGallery() {
            track.style.transform = `translateX(-${currentIndex * 100}%)`;

            thumbs.forEach((thumb, index) => {
                thumb.classList.toggle('is-active', index === currentIndex);
            });

            if (prevButton) {
                prevButton.disabled = currentIndex === 0;
            }

            if (nextButton) {
                nextButton.disabled = currentIndex === thumbs.length - 1;
            }
        }

        thumbs.forEach((thumb, index) => {
            thumb.addEventListener('click', function () {
                currentIndex = index;
                renderGallery();
            });
        });

        if (prevButton) {
            prevButton.addEventListener('click', function () {
                if (currentIndex > 0) {
                    currentIndex -= 1;
                    renderGallery();
                }
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', function () {
                if (currentIndex < thumbs.length - 1) {
                    currentIndex += 1;
                    renderGallery();
                }
            });
        }

        renderGallery();
    })();
</script>
@endsection
