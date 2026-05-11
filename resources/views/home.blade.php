@extends('layouts.app')

@section('title', 'PikFreshFood')

@section('styles')
<style>
    .home-container { max-width: 1240px; margin: 0 auto 30px; padding: 0 16px; }
    .home-hero {
        position: relative;
        min-height: 430px;
        border-radius: 0 0 34px 34px;
        padding: 0 26px 30px;
        margin: 0 -16px 28px;
        overflow: hidden;
        background: #0a3426;
        color: white;
    }
    .home-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 7%;
        right: 7%;
        height: 136px;
        border-radius: 0 0 42px 42px;
        background-image: inherit;
        background-size: cover;
        background-position: center;
        opacity: 0.45;
    }
    .home-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background:
            linear-gradient(180deg, rgba(10, 52, 38, 0.12) 0%, rgba(10, 52, 38, 0.64) 24%, rgba(10, 52, 38, 0.98) 100%);
    }
    .home-hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        min-height: 350px;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        text-align: center;
        padding-top: 44px;
    }
    .home-hero h1 {
        max-width: 860px;
        margin: 0;
        font-size: clamp(3rem, 6.8vw, 5.45rem);
        line-height: 0.9;
        letter-spacing: -0.04em;
        font-weight: 900;
        text-wrap: balance;
    }
    .hero-search-shell {
        width: min(674px, calc(100% - 28px));
        margin-top: 26px;
        padding: 0;
        border-radius: 18px;
        background: transparent;
        box-shadow: 0 24px 44px rgba(0, 0, 0, 0.2);
    }
    .hero-search-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        border-radius: 18px;
        background: rgba(33, 66, 52, 0.92);
        border: 1px solid rgba(255, 255, 255, 0.06);
        padding: 0 18px;
    }
    .hero-search-icon,
    .hero-search-submit {
        flex: 0 0 auto;
        color: rgba(255, 255, 255, 0.72);
        font-size: 1rem;
    }
    .hero-search-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
    }
    .hero-search-icon svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
    }
    .hero-search-input {
        width: 100%;
        min-height: 56px;
        border: 0;
        outline: none;
        background: transparent;
        color: white;
        font-size: 1rem;
    }
    .hero-search-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border: 0;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.08);
        cursor: pointer;
    }
    .hero-search-submit svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
    }
    .hero-search-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    .hero-actions {
        position: absolute;
        left: 24px;
        right: 24px;
        bottom: 16px;
        z-index: 2;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .hero-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 54px;
        padding: 12px 18px;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.32);
        background: rgba(82, 198, 112, 0.18);
        color: white;
        text-decoration: none;
        font-weight: 700;
        cursor: pointer;
        backdrop-filter: blur(10px);
        box-shadow: 0 16px 30px rgba(6, 27, 18, 0.22);
    }
    .hero-btn svg {
        width: 16px;
        height: 16px;
        margin-right: 8px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .hero-btn:hover { background: rgba(84, 203, 116, 0.28); }
    .location-banner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 14px;
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        margin-bottom: 18px;
    }
    .location-banner strong { color: var(--text-color); }
    .section-heading { font-size: 1.25rem; font-weight: 800; margin-bottom: 16px; color: var(--text-color); }
    .section-copy { color: var(--muted-color); margin: -8px 0 16px; }
    .category-strip { display: flex; flex-wrap: wrap; gap: 10px; margin: 20px 0 24px; }
    .category-pill {
        padding: 10px 14px;
        border-radius: 999px;
        background: var(--surface-alt);
        text-decoration: none;
        color: var(--text-color);
        font-weight: 700;
        border: 1px solid var(--border-color);
    }
    .vendor-scroll-wrap {
        position: relative;
        margin-bottom: 28px;
    }
    .vendor-strip {
        display: grid;
        grid-auto-flow: column;
        grid-auto-columns: calc((100% - (16px * 3)) / 4);
        gap: 16px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding-bottom: 6px;
    }
    .vendor-strip::-webkit-scrollbar {
        display: none;
    }
    .vendor-strip .vendor-card {
        scroll-snap-align: start;
    }
    .vendor-card {
        display: grid;
        gap: 12px;
        padding: 18px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        background: var(--bottom-sheet-bg);
        text-decoration: none;
        color: inherit;
    }
    .vendor-card-head {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
    }
    .vendor-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        background: color-mix(in srgb, var(--primary-color) 14%, white 86%);
    }
    .live-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #1f7a43;
        background: rgba(39, 174, 96, 0.12);
        padding: 6px 10px;
        border-radius: 999px;
    }
    .vendor-distance,
    .vendor-meta { color: var(--muted-color); font-size: 0.9rem; }
    .filters-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    .sort-links { display: flex; gap: 8px; flex-wrap: wrap; }
    .sort-link {
        padding: 8px 12px;
        border-radius: 999px;
        text-decoration: none;
        border: 1px solid var(--border-color);
        color: var(--text-color);
        background: var(--bottom-sheet-bg);
        font-weight: 700;
        font-size: 0.88rem;
    }
    .sort-link.is-active {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    .products-scroll-wrap {
        position: relative;
        margin-bottom: 28px;
    }
    .products-scroll {
        display: grid;
        grid-auto-flow: column;
        grid-auto-columns: calc((100% - (18px * 3)) / 4);
        gap: 18px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding-bottom: 8px;
    }
    .products-scroll::-webkit-scrollbar {
        display: none;
    }
    .products-scroll .product-card {
        scroll-snap-align: start;
    }
    .scroll-nav-btn {
        position: absolute;
        top: 40%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border: 1px solid var(--border-color);
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.95);
        color: var(--text-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        font-weight: 800;
        cursor: pointer;
        z-index: 3;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.18);
    }
    .scroll-nav-btn.prev { left: -10px; }
    .scroll-nav-btn.next { right: -10px; }
    .product-card {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
    }
    .product-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background: color-mix(in srgb, var(--primary-color) 12%, white 88%);
    }
    .product-card-body { padding: 16px; }
    .product-card h3 { font-size: 1.1rem; margin-bottom: 6px; }
    .product-vendor { color: var(--muted-color); font-size: 0.92rem; margin-bottom: 12px; }
    .product-meta { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
    .product-price { color: var(--primary-color); font-weight: 800; font-size: 1.1rem; }
    .product-category { color: var(--muted-color); font-size: 0.85rem; text-transform: capitalize; }
    .product-distance { margin-top: 10px; color: var(--muted-color); font-size: 0.84rem; }
    .product-status { margin-top: 10px; display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700; }
    .product-status.is-live { background: rgba(39, 174, 96, 0.12); color: #1f7a43; }
    .product-status.is-offline { background: rgba(127, 140, 141, 0.14); color: #667085; }
    .boosted-badge {
        margin-top: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #8a5a00;
        background: rgba(244, 196, 0, 0.2);
        border: 1px solid rgba(244, 196, 0, 0.45);
    }
    .delivery-coming-banner {
        display: block;
        margin: 26px 0 8px;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
        background: #fff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .delivery-coming-banner:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 34px rgba(0, 0, 0, 0.14);
    }
    .delivery-coming-banner img {
        width: 100%;
        height: auto;
        display: block;
    }
    @media (max-width: 900px) {
        .vendor-strip {
            grid-auto-flow: column;
            grid-auto-columns: calc((100% - 12px) / 2);
            gap: 12px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-bottom: 6px;
        }
        .vendor-strip::-webkit-scrollbar {
            display: none;
        }
        .products-scroll {
            grid-auto-columns: calc((100% - 12px) / 2);
            gap: 12px;
        }
        .scroll-nav-btn {
            width: 34px;
            height: 34px;
            font-size: 1rem;
            top: 38%;
            z-index: 6;
        }
        .scroll-nav-btn.prev { left: 4px; }
        .scroll-nav-btn.next { right: 4px; }
    }
    @media (max-width: 640px) {
        .home-hero {
            min-height: 440px;
            padding: 0 18px 22px;
            border-radius: 0 0 26px 26px;
        }
        .home-hero::before {
            left: 5%;
            right: 5%;
            height: 112px;
            border-radius: 0 0 30px 30px;
        }
        .home-hero-inner {
            min-height: 360px;
            padding-top: 42px;
        }
        .hero-search-shell {
            margin-top: 18px;
        }
        .hero-search-bar {
            padding: 0 12px;
        }
        .hero-search-input {
            min-height: 50px;
            font-size: 0.95rem;
        }
        .hero-actions {
            left: 18px;
            right: 18px;
            bottom: 18px;
            justify-content: flex-start;
            gap: 10px;
        }
        .hero-btn {
            min-height: 48px;
            padding: 10px 14px;
            font-size: 0.92rem;
        }
        .location-banner {
            align-items: flex-start;
            flex-direction: column;
        }
        .vendor-strip {
            grid-auto-flow: column;
            grid-auto-columns: calc((100% - 12px) / 2);
            gap: 12px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-bottom: 6px;
        }
        .vendor-strip::-webkit-scrollbar {
            display: none;
        }
        .products-scroll {
            grid-auto-columns: calc((100% - 12px) / 2);
            gap: 12px;
        }
        .product-card img {
            height: 132px;
        }
        .product-card-body {
            padding: 12px;
        }
        .product-card h3 {
            font-size: 0.98rem;
        }
        .product-vendor,
        .product-category,
        .product-distance {
            font-size: 0.82rem;
        }
        .product-price {
            font-size: 1rem;
        }
        .delivery-coming-banner {
            margin: 20px 0 4px;
            border-radius: 14px;
        }
    }
</style>
@endsection

@section('content')
<div class="home-container">
    @php
        $heroBackground = asset('images/home-hero-bg.jpg');
    @endphp

    <div class="home-hero" style="background-image: url('{{ $heroBackground }}');">
        <div class="home-hero-inner">
            <h1>Find local products in your neighborhood</h1>
        </div>
        <div class="hero-actions">
            <button type="button" class="hero-btn" id="detectLocationButton">Use My GPS</button>
            <a href="{{ route('live.index') }}" class="hero-btn">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <polygon points="9,7 18,12 9,17"></polygon>
                    <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                </svg>
                Lives
            </a>
            <button type="button" class="hero-btn" id="heroSearchTrigger">Voice Search</button>
        </div>
    </div>

    <div class="location-banner">
        <div>
            <div>📍 <strong>Showing vendors near {{ $locationLabel }}</strong></div>
            <div class="section-copy" style="margin:6px 0 0;">Sorted by {{ $sort === 'distance' ? 'distance' : ($sort === 'price' ? 'best price' : 'vendor rating') }}</div>
        </div>
        <div class="sort-links">
            <a href="{{ route('home', ['sort' => 'distance']) }}" class="sort-link {{ $sort === 'distance' ? 'is-active' : '' }}">Nearest</a>
            <a href="{{ route('home', ['sort' => 'price']) }}" class="sort-link {{ $sort === 'price' ? 'is-active' : '' }}">Cheapest</a>
            <a href="{{ route('home', ['sort' => 'rating']) }}" class="sort-link {{ $sort === 'rating' ? 'is-active' : '' }}">Top Rated</a>
        </div>
    </div>

    <div class="category-strip">
        @foreach($categories as $category)
            <a href="{{ route('category.show', $category) }}" class="category-pill">{{ ucfirst($category) }}</a>
        @endforeach
    </div>

    <div class="filters-row">
        <div>
            <div class="section-heading" style="margin-bottom:6px;">Popular Near You</div>
            <div class="section-copy">Good and popular products close to your location.</div>
        </div>
    </div>

    <div class="products-scroll-wrap">
        <button type="button" class="scroll-nav-btn prev" id="popularScrollPrev" aria-label="Scroll products left">&#8249;</button>
        <button type="button" class="scroll-nav-btn next" id="popularScrollNext" aria-label="Scroll products right">&#8250;</button>
        <div class="products-scroll" id="popularProductsScroll">
        @forelse($popularProducts as $product)
            <a href="{{ route('product.show', $product) }}" class="product-card">
                @if($product->primary_image)
                    <img src="{{ \App\Support\PublicStorage::url($product->primary_image) }}" alt="{{ $product->name }}">
                @else
                    <img src="https://placehold.co/640x480?text={{ urlencode($product->name) }}" alt="{{ $product->name }}">
                @endif
                <div class="product-card-body">
                    <h3>{{ $product->name }}</h3>
                    <div class="product-vendor">By {{ $product->vendor->shop_name }}</div>
                    <div class="product-meta">
                        <div class="product-price">₦{{ $product->price }}</div>
                        <div class="product-category">{{ $product->category }}</div>
                    </div>
                    <div class="product-distance">{{ $product->distance_km }} km away • Vendor rating {{ $product->vendor->rating ?: 'N/A' }}</div>
                    @if($product->isBoosted() || $product->vendor->isBoosted())
                        <div class="boosted-badge">Boosted</div>
                    @endif
                    <div class="product-status {{ $product->vendor->is_live ? 'is-live' : 'is-offline' }}">
                        {{ $product->vendor->is_live ? 'Vendor Live Now' : 'Vendor Offline' }}
                    </div>
                </div>
            </a>
        @empty
            <p>No products available yet.</p>
        @endforelse
        </div>
    </div>

    <div class="section-heading">Vendors Close to You</div>
    <p class="section-copy">Live sellers around you, ready to accept orders.</p>
    <div class="vendor-scroll-wrap">
        <button type="button" class="scroll-nav-btn prev" id="vendorsScrollPrev" aria-label="Scroll vendors left">&#8249;</button>
        <button type="button" class="scroll-nav-btn next" id="vendorsScrollNext" aria-label="Scroll vendors right">&#8250;</button>
        <div class="vendor-strip" id="nearbyVendorsScroll">
        @forelse($nearbyVendors as $vendor)
            <a href="{{ route('vendor.show', $vendor) }}" class="vendor-card">
                <div class="vendor-card-head">
                    <img src="{{ $vendor->profile_image ? \App\Support\PublicStorage::url($vendor->profile_image) : 'https://placehold.co/120x120?text=' . urlencode($vendor->shop_name) }}" alt="{{ $vendor->shop_name }}" class="vendor-avatar">
                    @if($vendor->is_live)
                        <span class="live-badge">🟢 Live Now</span>
                    @endif
                </div>
                <div>
                    <strong>{{ $vendor->shop_name }}</strong>
                    <div class="vendor-meta">{{ $vendor->products->count() }} products available</div>
                </div>
                @if($vendor->isBoosted())
                    <div class="boosted-badge">Boosted</div>
                @endif
                <div class="vendor-distance">{{ $vendor->distance_km }} km away • Rating {{ $vendor->rating ?: 'N/A' }}</div>
            </a>
        @empty
            <div class="vendor-card">No live vendors found nearby yet.</div>
        @endforelse
        </div>
    </div>

    <a href="{{ route('delivery-coming-soon') }}" class="delivery-coming-banner" aria-label="Delivery services coming soon">
        <img src="{{ asset('images/delivery-services-coming-soon.png') }}" alt="Delivery services coming soon">
    </a>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const detectButton = document.getElementById('detectLocationButton');
        const voiceButton = document.getElementById('voiceSearchButton');
        const voiceTriggerButton = document.getElementById('heroSearchTrigger');
        const heroSearchInput = document.getElementById('heroSearchInput');
        const headerSearch = document.querySelector('.search-bar');

        if (detectButton) {
            detectButton.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    alert('GPS location is not supported on this device.');
                    return;
                }

                detectButton.disabled = true;
                detectButton.textContent = 'Detecting...';

                navigator.geolocation.getCurrentPosition(async function (position) {
                    @auth
                    const response = await fetch('{{ route('profile.location') }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude,
                            label: 'your current location',
                        }),
                    }).catch(() => null);

                    if (response && response.ok) {
                        window.location.reload();
                        return;
                    }
                    @endauth

                    const params = new URLSearchParams(window.location.search);
                    params.set('lat', position.coords.latitude);
                    params.set('lng', position.coords.longitude);
                    params.set('label', 'your current location');
                    window.location.search = params.toString();
                }, function () {
                    alert('We could not access your current location.');
                    detectButton.disabled = false;
                    detectButton.textContent = 'Use My GPS';
                });
            });
        }

        if (heroSearchInput && headerSearch) {
            headerSearch.value = heroSearchInput.value;

            heroSearchInput.addEventListener('input', function () {
                headerSearch.value = heroSearchInput.value;
            });
        }

        const startVoiceSearch = function () {
            const supportsVoice = 'webkitSpeechRecognition' in window || 'SpeechRecognition' in window;
            const targetInput = heroSearchInput || headerSearch;

            if (!supportsVoice || !targetInput) {
                return;
            }

            const Recognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            const recognition = new Recognition();
            recognition.lang = 'en-NG';
            recognition.start();
            recognition.onresult = function (event) {
                const transcript = event.results[0][0].transcript;
                targetInput.value = transcript;

                if (heroSearchInput) {
                    heroSearchInput.value = transcript;
                }

                if (headerSearch) {
                    headerSearch.value = transcript;
                }
            };
        };

        if (voiceButton) {
            voiceButton.addEventListener('click', startVoiceSearch);
        }

        if (voiceTriggerButton) {
            voiceTriggerButton.addEventListener('click', startVoiceSearch);
        }

        if (heroSearchInput) {
            heroSearchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' && headerSearch) {
                    headerSearch.value = heroSearchInput.value;
                }
            });
        }

        if (voiceTriggerButton && !('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {
            voiceTriggerButton.style.display = 'none';
        }

        const popularScroll = document.getElementById('popularProductsScroll');
        const popularPrev = document.getElementById('popularScrollPrev');
        const popularNext = document.getElementById('popularScrollNext');
        const vendorsScroll = document.getElementById('nearbyVendorsScroll');
        const vendorsPrev = document.getElementById('vendorsScrollPrev');
        const vendorsNext = document.getElementById('vendorsScrollNext');

        if (popularScroll && popularPrev && popularNext) {
            const scrollByCards = function (direction) {
                const card = popularScroll.querySelector('.product-card');
                const gap = 18;
                const cardWidth = card ? card.getBoundingClientRect().width : 260;
                popularScroll.scrollBy({
                    left: direction * (cardWidth + gap) * 2,
                    behavior: 'smooth',
                });
            };

            popularPrev.addEventListener('click', function () {
                scrollByCards(-1);
            });

            popularNext.addEventListener('click', function () {
                scrollByCards(1);
            });
        }

        if (vendorsScroll && vendorsPrev && vendorsNext) {
            const scrollVendorsByCards = function (direction) {
                const card = vendorsScroll.querySelector('.vendor-card');
                const gap = window.innerWidth <= 900 ? 12 : 16;
                const cardWidth = card ? card.getBoundingClientRect().width : 260;
                vendorsScroll.scrollBy({
                    left: direction * (cardWidth + gap) * 2,
                    behavior: 'smooth',
                });
            };

            vendorsPrev.addEventListener('click', function () {
                scrollVendorsByCards(-1);
            });

            vendorsNext.addEventListener('click', function () {
                scrollVendorsByCards(1);
            });
        }

        const enableManualDrag = function (container) {
            if (!container) {
                return;
            }

            let isDown = false;
            let startX = 0;
            let scrollLeft = 0;

            container.addEventListener('mousedown', function (event) {
                isDown = true;
                startX = event.pageX - container.offsetLeft;
                scrollLeft = container.scrollLeft;
                container.style.cursor = 'grabbing';
                container.style.userSelect = 'none';
            });

            container.addEventListener('mouseleave', function () {
                isDown = false;
                container.style.cursor = '';
                container.style.userSelect = '';
            });

            container.addEventListener('mouseup', function () {
                isDown = false;
                container.style.cursor = '';
                container.style.userSelect = '';
            });

            container.addEventListener('mousemove', function (event) {
                if (!isDown) {
                    return;
                }

                event.preventDefault();
                const x = event.pageX - container.offsetLeft;
                const walk = (x - startX) * 1.2;
                container.scrollLeft = scrollLeft - walk;
            });
        };

        enableManualDrag(popularScroll);
        enableManualDrag(vendorsScroll);

    })();
</script>
@endsection
