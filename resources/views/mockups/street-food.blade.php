@extends('layouts.app')

@section('title', 'Street Food Discovery - PikFreshFood')

@section('styles')
<style>
    .hero-section { background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 70%, #f7931e 30%) 100%); color: white; padding: 30px 15px; text-align: center; margin: 0 0 20px; }
    .hero-title { font-size: 22px; font-weight: bold; margin-bottom: 8px; }
    .hero-subtitle { font-size: 13px; opacity: 0.9; margin-bottom: 15px; }

    .category-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; padding: 15px 0; margin-bottom: 10px; }
    .food-tile { background: linear-gradient(135deg, color-mix(in srgb, var(--primary-color) 10%, white 90%) 0%, color-mix(in srgb, var(--primary-color) 22%, white 78%) 100%); border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; transition: transform 0.2s; }
    .food-tile:hover { transform: translateY(-4px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .food-icon { font-size: 40px; margin-bottom: 10px; }
    .food-name { font-weight: bold; font-size: 14px; margin-bottom: 8px; }
    .food-desc { font-size: 11px; color: var(--muted-color); line-height: 1.4; }
    .food-price { color: var(--primary-color); font-weight: bold; font-size: 12px; margin-top: 8px; }

    .vendor-tile { background: linear-gradient(135deg, color-mix(in srgb, var(--primary-color) 15%, white 85%) 0%, color-mix(in srgb, var(--primary-color) 28%, white 72%) 100%); }
    .vendor-tile .food-icon { font-size: 30px; }

    .featured-section { padding: 15px 0; margin-bottom: 10px; }
    .section-title { font-size: 16px; font-weight: bold; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    .section-title-badge { background: var(--primary-color); color: white; padding: 4px 10px; border-radius: 12px; font-size: 11px; }

    .vendor-card { border-radius: 8px; padding: 12px; margin-bottom: 10px; border-left: 4px solid var(--primary-color); }
    .vendor-header { display: flex; gap: 12px; align-items: flex-start; }
    .vendor-avatar { font-size: 40px; }
    .vendor-info { flex: 1; }
    .vendor-name { font-weight: bold; font-size: 13px; }
    .vendor-food { font-size: 11px; color: var(--primary-color); margin: 4px 0; }
    .vendor-distance { font-size: 11px; color: var(--muted-color); }
    .vendor-available { display: inline-block; background: var(--primary-color); color: white; padding: 3px 8px; border-radius: 12px; font-size: 10px; margin-top: 4px; }
    .vendor-unavailable { display: inline-block; background: #95a5a6; color: white; padding: 3px 8px; border-radius: 12px; font-size: 10px; margin-top: 4px; }

    .time-badge { background: color-mix(in srgb, var(--primary-color) 18%, white 82%); color: var(--text-color); padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; }

    .action-buttons { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 10px; }
    .btn-small { padding: 8px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold; }
    .btn-order { background: var(--primary-color); color: white; }
    .btn-vendor { background: var(--surface-alt); color: var(--text-color); }

    .recruitment-card { background: linear-gradient(135deg, color-mix(in srgb, var(--primary-color) 12%, white 88%) 0%, color-mix(in srgb, var(--primary-color) 28%, white 72%) 100%); padding: 15px; border-radius: 8px; border-left: 4px solid var(--primary-color); margin: 10px 0; }
    .recruitment-title { font-weight: bold; margin-bottom: 8px; }
    .recruitment-text { font-size: 13px; line-height: 1.5; margin-bottom: 10px; color: var(--muted-color); }
    .recruitment-btn { background: var(--primary-color); color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; width: 100%; }

    .location-display { text-align: center; font-size: 13px; color: var(--muted-color); margin-bottom: 15px; padding: 10px; background: var(--surface-alt); border-radius: 6px; }
</style>
@endsection

@section('content')
    <!-- Location Display -->
    <div class="location-display">📍 Showing vendors in: Yaba, Lagos</div>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-title">Popular Nigerian Street Food</div>
        <div class="hero-subtitle">Discover vendors selling near you</div>
    </div>

    <!-- Food Categories Grid -->
    <div class="category-grid">
        <div class="food-tile">
            <div class="food-icon">🌯</div>
            <div class="food-name">Suya</div>
            <div class="food-desc">Grilled spiced meat strips</div>
            <div class="food-price">From ₦500</div>
        </div>
        <div class="food-tile">
            <div class="food-icon">🟤</div>
            <div class="food-name">Akara</div>
            <div class="food-desc">Deep-fried bean balls</div>
            <div class="food-price">From ₦200</div>
        </div>
        <div class="food-tile">
            <div class="food-icon">🍌</div>
            <div class="food-name">Puff Puff</div>
            <div class="food-desc">Sweet fried dough balls</div>
            <div class="food-price">From ₦100</div>
        </div>
        <div class="food-tile">
            <div class="food-icon">🥟</div>
            <div class="food-name">Ewa Aganyin</div>
            <div class="food-desc">Beans with fried peppers</div>
            <div class="food-price">From ₦300</div>
        </div>
        <div class="food-tile">
            <div class="food-icon">🧃</div>
            <div class="food-name">Zobo</div>
            <div class="food-desc">Hibiscus juice drink</div>
            <div class="food-price">From ₦200</div>
        </div>
        <div class="food-tile">
            <div class="food-icon">🍲</div>
            <div class="food-name">Rice & Sauce</div>
            <div class="food-desc">Quick-serve meals</div>
            <div class="food-price">From ₦800</div>
        </div>
    </div>

    <!-- Featured Vendors Selling Now -->
    <div class="featured-section">
        <div class="section-title">
            🔴 <span style="flex: 1;">Live & Selling Now</span>
            <span class="section-title-badge">4 Vendors</span>
        </div>

        <div class="vendor-card">
            <div class="vendor-header">
                <div class="vendor-avatar">👩</div>
                <div class="vendor-info">
                    <div class="vendor-name">Mama Tayo's Suya Stand</div>
                    <div class="vendor-food">🌯 Suya • 🟤 Akara</div>
                    <div class="vendor-distance">150m from you</div>
                    <div class="vendor-available">✓ Available Now</div>
                    <span class="time-badge">20-30 mins</span>
                </div>
            </div>
            <div class="action-buttons">
                <button class="btn-small btn-order">🛒 Order Now</button>
                <button class="btn-small btn-vendor">👀 View Vendor</button>
            </div>
        </div>

        <div class="vendor-card">
            <div class="vendor-header">
                <div class="vendor-avatar">👨</div>
                <div class="vendor-info">
                    <div class="vendor-name">Uncle Emeka's Puff Puff</div>
                    <div class="vendor-food">🍌 Puff Puff • 🥟 Ewa Aganyin</div>
                    <div class="vendor-distance">280m from you</div>
                    <div class="vendor-available">✓ Available Now</div>
                    <span class="time-badge">25-35 mins</span>
                </div>
            </div>
            <div class="action-buttons">
                <button class="btn-small btn-order">🛒 Order Now</button>
                <button class="btn-small btn-vendor">👀 View Vendor</button>
            </div>
        </div>

        <div class="vendor-card">
            <div class="vendor-header">
                <div class="vendor-avatar">👩</div>
                <div class="vendor-info">
                    <div class="vendor-name">Sister Ada's Zobo Bar</div>
                    <div class="vendor-food">🧃 Zobo • 🥤 Juice</div>
                    <div class="vendor-distance">350m from you</div>
                    <div class="vendor-available">✓ Available Now</div>
                    <span class="time-badge">15-20 mins</span>
                </div>
            </div>
            <div class="action-buttons">
                <button class="btn-small btn-order">🛒 Order Now</button>
                <button class="btn-small btn-vendor">👀 View Vendor</button>
            </div>
        </div>

        <div class="vendor-card">
            <div class="vendor-header">
                <div class="vendor-avatar">👨</div>
                <div class="vendor-info">
                    <div class="vendor-name">Brother Tunde's Rice Stop</div>
                    <div class="vendor-food">🍲 Rice & Sauce • 🥘 Meals</div>
                    <div class="vendor-distance">420m from you</div>
                    <div class="vendor-unavailable">Closed - Opens 5PM</div>
                </div>
            </div>
            <div class="action-buttons">
                <button class="btn-small btn-order" style="opacity: 0.5; cursor: not-allowed;">🛒 Coming Soon</button>
                <button class="btn-small btn-vendor">👀 View Vendor</button>
            </div>
        </div>
    </div>

    <!-- Recruitment CTA -->
    <div class="recruitment-card">
        <div class="recruitment-title">🎯 Want to Sell Street Food?</div>
        <div class="recruitment-text">
            Become a PikFreshFood vendor and reach thousands of hungry customers in your area. Set your own hours and manage your business.
        </div>
        <button class="recruitment-btn">Become a Vendor</button>
    </div>
@endsection
