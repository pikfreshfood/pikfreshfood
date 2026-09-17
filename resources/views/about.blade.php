@extends('layouts.app')

@section('title', 'About - PikFreshFood')

@section('styles')
<style>
    .about-back { display:inline-flex; align-items:center; gap:8px; text-decoration:none; color:var(--muted-color); font-weight:700; font-size:0.9rem; margin-bottom:14px; }
    .about-back:hover { color:var(--primary-color); }
    .about-back svg { width:18px; height:18px; stroke:currentColor; fill:none; stroke-width:2; stroke-linecap:round; stroke-linejoin:round; }
    .about-hero { background:linear-gradient(135deg, var(--primary-color) 0%, #0f5c28 100%); color:white; border-radius:20px; padding:28px 24px; position:relative; overflow:hidden; margin-bottom:18px; box-shadow:0 10px 24px rgba(22,132,71,0.22); }
    .about-hero::after { content:''; position:absolute; top:-50px; right:-50px; width:180px; height:180px; background:radial-gradient(circle, var(--secondary-color) 0%, transparent 70%); opacity:0.45; }
    .about-hero h1 { font-size:2rem; font-weight:900; margin:0 0 8px; position:relative; z-index:1; }
    .about-hero p { opacity:0.93; line-height:1.6; max-width:680px; position:relative; z-index:1; }
    .about-badge { display:inline-flex; align-items:center; gap:8px; margin-top:14px; padding:8px 14px; border-radius:999px; background:var(--secondary-color); color:#111; font-weight:800; font-size:0.84rem; position:relative; z-index:1; }
    .about-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:16px; margin-bottom:18px; }
    .about-card { background:var(--bottom-sheet-bg); border:1px solid var(--border-color); border-radius:16px; padding:20px; box-shadow:0 2px 10px var(--shadow-color); position:relative; overflow:hidden; }
    .about-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:var(--secondary-color); }
    .about-card h3 { color:var(--primary-color); margin-bottom:8px; font-size:1.05rem; display:flex; align-items:center; gap:8px; }
    .about-card h3 span.icon { width:32px; height:32px; border-radius:8px; background:var(--secondary-color); display:grid; place-items:center; font-size:1.1rem; flex:0 0 auto; }
    .about-card p { color:var(--muted-color); line-height:1.65; font-size:0.93rem; }
    .about-steps { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:18px; }
    .about-step { background:white; border:1px solid var(--border-color); border-radius:14px; padding:18px; text-align:center; position:relative; }
    .about-step-num { width:36px; height:36px; border-radius:50%; background:var(--primary-color); color:white; display:grid; place-items:center; font-weight:800; margin:0 auto 10px; }
    .about-step h4 { color:var(--text-color); margin-bottom:6px; }
    .about-step p { color:var(--muted-color); font-size:0.88rem; line-height:1.5; }
    .about-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:18px; }
    .about-stat { background:linear-gradient(135deg, var(--primary-color) 0%, #1e8a4a 100%); color:white; border-radius:14px; padding:18px; text-align:center; position:relative; overflow:hidden; }
    .about-stat::after { content:''; position:absolute; bottom:-20px; right:-20px; width:80px; height:80px; background:var(--secondary-color); opacity:0.22; border-radius:50%; }
    .about-stat strong { font-size:1.6rem; display:block; position:relative; z-index:1; }
    .about-stat span { font-size:0.84rem; opacity:0.9; position:relative; z-index:1; }
    .about-cta { background:var(--bottom-sheet-bg); border:1px solid var(--border-color); border-radius:16px; padding:22px; display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; box-shadow:0 2px 10px var(--shadow-color); }
    .about-cta h3 { color:var(--text-color); margin-bottom:4px; }
    .about-cta p { color:var(--muted-color); font-size:0.92rem; }
    .about-cta-btn { display:inline-flex; align-items:center; gap:8px; padding:12px 18px; border-radius:10px; background:var(--secondary-color); color:#111; text-decoration:none; font-weight:800; border:1px solid #e6b800; box-shadow:0 6px 14px rgba(244,196,0,0.24); transition:transform 0.14s; }
    .about-cta-btn:hover { transform:translateY(-1px); }
    .about-cta-btn.secondary { background:var(--primary-color); color:white; border-color:var(--primary-color); box-shadow:0 6px 14px rgba(22,132,71,0.2); }
    @media (max-width:720px){ .about-grid{grid-template-columns:1fr;} .about-steps{grid-template-columns:1fr;} .about-stats{grid-template-columns:repeat(2,1fr);} .about-hero h1{font-size:1.5rem;} }
</style>
@endsection

@section('content')
<div style="max-width:1000px;margin:20px auto;padding:0 16px;">
    <a href="{{ route('home') }}" class="about-back">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 18l-6-6 6-6"></path><path d="M9 12h10"></path></svg>
        Back to Home
    </a>
    <div class="about-hero">
        <h1>About PikFreshFood</h1>
        <p>PikFreshFood connects buyers to nearby vendors for fresh food, market products, and fast local delivery. We help customers discover trusted sellers in their neighborhood and empower vendors to grow with simple tools, live selling and secure payments.</p>
        <div class="about-badge">🌱 Fresh • Fast • Trusted • Local</div>
    </div>

    <div class="about-grid">
        <div class="about-card">
            <h3><span class="icon">🎯</span> Our Mission</h3>
            <p>To make fresh food accessible to everyone by linking households directly to verified local vendors. We reduce the distance between farm, market and table so you get fresher produce, fair prices and faster delivery.</p>
        </div>
        <div class="about-card">
            <h3><span class="icon">👁️</span> Our Vision</h3>
            <p>To become Nigeria’s most trusted hyperlocal marketplace — where every neighborhood has a digital market that creates income for vendors, convenience for buyers and stronger local economies.</p>
        </div>
    </div>

    <div class="about-card" style="margin-bottom:18px;">
        <h3><span class="icon">💡</span> Who We Are</h3>
        <p>PikFreshFood is built for everyday needs: daily groceries, fruits, vegetables, grains, pantry, meat, dairy and roasted foods. Unlike generic e-commerce, we prioritize proximity — you see what’s available within kilometers, see the vendor’s live status, rating and distance, and you can chat, call or send a voice message before you buy. Vendors get a simple shop, products, orders, wallet, live-video and boost tools without needing technical skills.</p>
        <p style="margin-top:10px; color:var(--muted-color); line-height:1.65; font-size:0.93rem;">We started with a simple observation: people trust the vendor they can see and reach. PikFreshFood brings that trust online with live indicators, transparent ratings, and direct communication.</p>
    </div>

    <h3 style="color:var(--text-color); margin-bottom:12px; font-size:1.15rem;">How It Works</h3>
    <div class="about-steps">
        <div class="about-step">
            <div class="about-step-num">1</div>
            <h4>Discover Nearby</h4>
            <p>Allow GPS or choose a location. We sort vendors and products by distance, price and rating so the closest and best appear first.</p>
        </div>
        <div class="about-step">
            <div class="about-step-num">2</div>
            <h4>Chat & Order</h4>
            <p>Message, audio or video call the vendor, add to cart, choose delivery or pickup, and pay securely with Paystack (card, bank, USSD).</p>
        </div>
        <div class="about-step">
            <div class="about-step-num">3</div>
            <h4>Receive Fresh</h4>
            <p>Track your order, get live updates, and enjoy fresh delivery from a vendor just kilometers away. Rate the vendor to help the community.</p>
        </div>
    </div>

    <div class="about-grid">
        <div class="about-card">
            <h3><span class="icon">🛒</span> For Buyers</h3>
            <p>• Fresh fruits, vegetables, grains, dairy, meat, pantry & restaurant meals.<br>• See distance + live status before you order.<br>• Secure Paystack checkout and order history.<br>• Wishlist, repeat orders and direct vendor contact.</p>
        </div>
        <div class="about-card">
            <h3><span class="icon">🏪</span> For Vendors</h3>
            <p>• 1-month free trial, then affordable premium (3/6/12 months).<br>• Shop, products, live videos, boost, orders & wallet in one dashboard.<br>• Go Live to sell, upload short videos, and get boosted visibility.<br>• Simple payout and subscription management.</p>
        </div>
    </div>

    <h3 style="color:var(--text-color); margin:12px 0; font-size:1.15rem;">Why Choose PikFreshFood</h3>
    <div class="about-grid">
        <div class="about-card">
            <h3><span class="icon">📍</span> Hyperlocal First</h3>
            <p>We rank by proximity so you save time and delivery cost. What you see is actually near you — typically within 0–15km in Abuja and growing cities.</p>
        </div>
        <div class="about-card">
            <h3><span class="icon">✅</span> Trusted Vendors</h3>
            <p>Vendors are verified, rated and show live/offline status. You can view shop, products, reviews and chat before paying.</p>
        </div>
        <div class="about-card">
            <h3><span class="icon">⚡</span> Live Selling</h3>
            <p>Vendors can go live and post 60s videos. Buyers discover products in the Lives feed — just like social, but for food.</p>
        </div>
        <div class="about-card">
            <h3><span class="icon">🔒</span> Secure & Local</h3>
            <p>Paystack-secured payments, transparent pricing, and support that understands the local market. No hidden fees.</p>
        </div>
    </div>

    <div class="about-stats">
        <div class="about-stat"><strong>15k+</strong><span>Products listed</span></div>
        <div class="about-stat"><strong>2k+</strong><span>Verified vendors</span></div>
        <div class="about-stat"><strong>98%</strong><span>On-time chats</span></div>
        <div class="about-stat"><strong>24h</strong><span>Support response</span></div>
    </div>

    <div class="about-cta">
        <div>
            <h3>Ready to get fresh?</h3>
            <p>Explore nearby vendors or start selling today — your free trial is waiting.</p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('home') }}" class="about-cta-btn secondary">Explore Market</a>
            <a href="{{ route('vendor.onboarding') }}" class="about-cta-btn">Become a Vendor</a>
        </div>
    </div>

    <div class="about-card" style="margin-top:18px;">
        <h3><span class="icon">📞</span> Contact & Support</h3>
        <p>Questions? Our team replies within 24 hours. Visit <a href="{{ route('contact-us') }}" style="color:var(--primary-color); font-weight:700;">Contact Us</a> or start a <strong>Live Chat</strong> from any page. For vendor partnership, email vendors@pikfreshfood.com. We’re based in Abuja, Nigeria — serving neighborhoods everywhere.</p>
    </div>
</div>
@endsection
