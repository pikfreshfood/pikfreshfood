@extends('layouts.app')

@section('title', $recipe['title'] . ' - PikFreshFood')

@section('content')
<div class="home-container" style="padding-top:24px;">
    <a href="{{ route('recipes.index') }}" class="back-link">Back to Recipes</a>
    <div class="product-container" style="margin-top:16px;">
        <h1>{{ $recipe['title'] }}</h1>
        <p class="product-description">{{ $recipe['description'] }}</p>
        <div class="section-heading" style="font-size:1.05rem;">Steps</div>
        <ol style="padding-left:18px; color:var(--muted-color); line-height:1.8;">
            @foreach($recipe['steps'] as $step)
                <li>{{ $step }}</li>
            @endforeach
        </ol>
    </div>

    <div class="section-heading" style="margin-top:20px;">Buy Ingredients</div>
    <div class="products-grid">
        @foreach($products as $product)
            <a href="{{ route('product.show', $product) }}" class="product-card">
                <img src="{{ $product->primary_image ? \App\Support\PublicStorage::url($product->primary_image) : 'https://placehold.co/640x480?text=' . urlencode($product->name) }}" alt="{{ $product->name }}">
                <div class="product-card-body">
                    <h3>{{ $product->name }}</h3>
                    <div class="product-vendor">By {{ $product->vendor->shop_name }}</div>
                    <div class="product-price">₦{{ $product->price }}</div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
