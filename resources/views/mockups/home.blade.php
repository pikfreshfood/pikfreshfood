@extends('layouts.app')

@section('title', 'PikFreshFood - Home Page')

@section('styles')
<style>
    .hero-section { background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 82%, #000 18%) 100%); color: white; padding: 30px 15px; text-align: center; margin: 0 0 20px; }
    .hero-section h1 { font-size: 24px; margin-bottom: 10px; }
    .hero-section p { font-size: 14px; margin-bottom: 15px; opacity: 0.9; }
    .cta-primary { background: white; color: var(--primary-color); padding: 12px 30px; border: none; border-radius: 25px; font-weight: bold; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
    .cta-primary:hover { background: var(--surface-alt); }

    .category-filter { display: flex; overflow-x: auto; padding: 15px 0; gap: 10px; margin: 10px 0; }
    .category-chip { background: var(--surface-alt); padding: 10px 15px; border-radius: 20px; white-space: nowrap; cursor: pointer; border: 2px solid transparent; text-decoration: none; color: var(--text-color); }
    .category-chip.active { background: var(--primary-color); color: white; border-color: var(--primary-color); }
    .category-chip:hover { background: color-mix(in srgb, var(--surface-alt) 80%, var(--primary-color) 20%); }

    .section { margin: 15px 0; padding: 15px 0; }
    .section-title { font-size: 18px; font-weight: bold; margin-bottom: 15px; margin-left: 0; }

    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; padding: 0; }
    .product-card { border-radius: 8px; padding: 10px; cursor: pointer; transition: transform 0.2s; border: 1px solid var(--border-color); text-decoration: none; color: inherit; display: block; }
    .product-card:hover { transform: translateY(-4px); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
    .product-image { width: 100%; height: 120px; background: linear-gradient(135deg, color-mix(in srgb, var(--primary-color) 15%, white 85%) 0%, color-mix(in srgb, var(--primary-color) 30%, white 70%) 100%); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 40px; }
    .product-name { font-size: 13px; font-weight: bold; margin-top: 8px; }
    .product-price { color: var(--primary-color); font-weight: bold; font-size: 14px; margin: 5px 0; }
    .product-distance { font-size: 12px; color: var(--muted-color); }
    .product-rating { font-size: 12px; color: #f39c12; }

    .vendor-card { border-radius: 8px; padding: 12px; margin: 0 0 12px 0; border: 1px solid var(--border-color); display: block; text-decoration: none; color: inherit; }
    .vendor-badge { display: inline-block; background: var(--primary-color); color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; margin-bottom: 8px; }
    .vendor-name { font-weight: bold; font-size: 14px; margin-bottom: 4px; }
    .vendor-distance { font-size: 12px; color: var(--muted-color); }

    .banner { padding: 15px; margin: 10px 0; background: color-mix(in srgb, var(--primary-color) 18%, white 82%); border-left: 4px solid var(--primary-color); border-radius: 4px; font-size: 14px; }

    .horizontal-scroll { display: flex; overflow-x: auto; gap: 10px; padding: 10px 0; padding-bottom: 20px; }
    .horizontal-scroll::-webkit-scrollbar { height: 6px; }
    .horizontal-scroll::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 3px; }

    .street-food-item { border-radius: 8px; padding: 12px; min-width: 140px; text-align: center; text-decoration: none; color: inherit; }
</style>
@endsection

@section('content')
    <!-- Hero Section -->
    <div class="hero-section">
        <h1>Find Fresh Food<br/>In Your Neighborhood</h1>
        <p>Local vendors. Real freshness. Just minutes away.</p>
        <a href="{{ route('mockups.show', ['slug' => 'map']) }}" class="cta-primary">🗺️ View Map</a>
    </div>

    <!-- Category Filter -->
    <div class="category-filter">
        <a href="{{ route('mockups.show', ['slug' => 'home']) }}" class="category-chip active">🥕 All</a>
        <a href="{{ route('category.show', 'fruits') }}" class="category-chip">🍎 Fruits</a>
        <a href="{{ route('category.show', 'vegetables') }}" class="category-chip">🥬 Vegetables</a>
        <a href="{{ route('category.show', 'grains') }}" class="category-chip">🍠 Grains</a>
        <a href="{{ route('category.show', 'baked') }}" class="category-chip">🍞 Baked</a>
        <a href="{{ route('category.show', 'nuts') }}" class="category-chip">🥜 Nuts</a>
        <a href="{{ route('category.show', 'spices') }}" class="category-chip">🌶️ Spices</a>
    </div>

    <!-- Today's Freshest Section -->
    <div class="section">
        <div class="section-title">🔥 Today's Freshest (2 mins away)</div>
        <div class="horizontal-scroll">
            <a href="#" class="street-food-item">
                <div style="font-size: 40px;">🌯</div>
                <div style="font-weight: bold; font-size: 13px;">Suya</div>
                <div style="color: #27ae60; font-size: 12px;">₦500</div>
            </a>
            <a href="#" class="street-food-item">
                <div style="font-size: 40px;">🟤</div>
                <div style="font-weight: bold; font-size: 13px;">Akara</div>
                <div style="color: #27ae60; font-size: 12px;">₦200</div>
            </a>
            <a href="#" class="street-food-item">
                <div style="font-size: 40px;">🍎</div>
                <div style="font-weight: bold; font-size: 13px;">Fruits</div>
                <div style="color: #27ae60; font-size: 12px;">₦300+</div>
            </a>
            <a href="#" class="street-food-item">
                <div style="font-size: 40px;">🥬</div>
                <div style="font-weight: bold; font-size: 13px;">Vegetables</div>
                <div style="color: #27ae60; font-size: 12px;">Fresh Daily</div>
            </a>
        </div>
    </div>

    <!-- Live Now Section -->
    <div class="section">
        <div class="section-title">🔴 Live Now - Order Immediately</div>
        <div style="padding: 0;">
            <a href="#" class="vendor-card">
                <div class="vendor-badge">LIVE</div>
                <div class="vendor-name">Mama Tayo</div>
                <div style="font-size: 12px; color: #27ae60; margin-bottom: 4px;">Suya & Akara</div>
                <div class="vendor-distance">500m from you</div>
                <div style="color: #f39c12; font-size: 12px;">⭐ 4.8 (120 reviews)</div>
            </a>
            <a href="#" class="vendor-card">
                <div class="vendor-badge">LIVE</div>
                <div class="vendor-name">Sister Blessing</div>
                <div style="font-size: 12px; color: #27ae60; margin-bottom: 4px;">Fresh Vegetables</div>
                <div class="vendor-distance">300m from you</div>
                <div style="color: #f39c12; font-size: 12px;">⭐ 4.9 (89 reviews)</div>
            </a>
        </div>
    </div>

    <!-- Trending Products -->
    <div class="section">
        <div class="section-title">📈 Trending in Your Area</div>
        <div class="product-grid">
            <a href="#" class="product-card">
                <div class="product-image">🍅</div>
                <div class="product-name">Fresh Tomatoes</div>
                <div class="product-price">₦500</div>
                <div class="product-distance">300m away</div>
                <div class="product-rating">⭐ 4.7</div>
            </a>
            <a href="#" class="product-card">
                <div class="product-image">🍆</div>
                <div class="product-name">Garden Eggs</div>
                <div class="product-price">₦300</div>
                <div class="product-distance">500m away</div>
                <div class="product-rating">⭐ 4.5</div>
            </a>
            <a href="#" class="product-card">
                <div class="product-image">🐟</div>
                <div class="product-name">Fresh Catfish</div>
                <div class="product-price">₦2500</div>
                <div class="product-distance">800m away</div>
                <div class="product-rating">⭐ 4.9</div>
            </a>
            <a href="#" class="product-card">
                <div class="product-image">🍌</div>
                <div class="product-name">Roasted Plantain</div>
                <div class="product-price">₦400</div>
                <div class="product-distance">200m away</div>
                <div class="product-rating">⭐ 4.6</div>
            </a>
            <a href="#" class="product-card">
                <div class="product-image">🥤</div>
                <div class="product-name">Kunu Aya Bottle</div>
                <div class="product-price">₦200</div>
                <div class="product-distance">400m away</div>
                <div class="product-rating">⭐ 4.8</div>
            </a>
            <a href="#" class="product-card">
                <div class="product-image">🍠</div>
                <div class="product-name">Yam Porridge</div>
                <div class="product-price">₦1200</div>
                <div class="product-distance">600m away</div>
                <div class="product-rating">⭐ 4.7</div>
            </a>
        </div>
    </div>

    <!-- Flash Deals Banner -->
    <div class="banner">
        ⚡ <strong>Flash Deals Today!</strong> Get 20% off with vendors marked 🎯
    </div>
@endsection
