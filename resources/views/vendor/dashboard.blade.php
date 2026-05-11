@extends('layouts.app')

@section('title', $vendor->shop_name . ' Dashboard - PikFreshFood')

@section('styles')
<style>
    .dashboard-container { max-width: 1200px; margin: 40px auto; }
    .dashboard-container h1 { color: #27ae60; margin-bottom: 30px; }
    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
    .stat { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; transition: transform 0.2s; }
    .stat:hover { transform: translateY(-2px); }
    .stat h3 { margin: 0 0 10px 0; color: #27ae60; font-size: 28px; }
    .stat p { margin: 0; color: #7f8c8d; font-size: 14px; }
    .actions { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
    .actions h2 { color: #27ae60; margin-bottom: 20px; }
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 12px;
    }
    .action-btn {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 74px;
        padding: 14px 12px;
        text-align: center;
        text-decoration: none;
        border-radius: 14px;
        font-weight: 700;
        color: color-mix(in srgb, var(--text-color) 82%, var(--primary-color) 18%);
        background: linear-gradient(
            180deg,
            color-mix(in srgb, var(--surface-bg) 76%, var(--primary-color) 24%) 0%,
            color-mix(in srgb, var(--surface-alt) 82%, var(--primary-color) 18%) 100%
        );
        border: 1px solid color-mix(in srgb, var(--border-color) 72%, var(--primary-color) 28%);
        box-shadow: 0 10px 24px color-mix(in srgb, var(--primary-color) 12%, transparent);
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }
    .action-btn-icon {
        width: 22px;
        height: 22px;
        margin-bottom: 8px;
        color: currentColor;
        flex: 0 0 auto;
        opacity: 0.95;
    }
    .action-btn-label {
        display: block;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px color-mix(in srgb, var(--primary-color) 18%, transparent);
        border-color: color-mix(in srgb, var(--primary-color) 60%, var(--border-color) 40%);
        text-decoration: none;
        color: color-mix(in srgb, var(--text-color) 72%, var(--primary-color) 28%);
    }
    .action-btn:focus-visible {
        outline: 2px solid #27ae60;
        outline-offset: 2px;
    }
    .orders { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .orders h2 { color: #27ae60; margin-bottom: 20px; }
    .order { border-bottom: 1px solid #eee; padding: 15px 0; }
    .order:last-child { border-bottom: none; }
    .order strong { color: #333; }
    .order-status { color: #27ae60; font-weight: bold; }
    .products-panel { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
    .products-panel h2 { color: #27ae60; margin-bottom: 20px; }
    .live-panel { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
    .live-panel h2 { color: #27ae60; margin-bottom: 8px; }
    .live-panel-copy { color: #7f8c8d; margin-bottom: 16px; font-size: 0.94rem; }
    .live-upload-form {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto;
        gap: 10px;
        align-items: center;
        margin-bottom: 18px;
    }
    .live-upload-input,
    .live-upload-file {
        min-height: 42px;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 0 12px;
        background: #fff;
    }
    .live-upload-file {
        padding: 8px 10px;
    }
    .live-upload-btn {
        min-height: 42px;
        border: 1px solid var(--primary-color);
        border-radius: 10px;
        padding: 0 14px;
        background: var(--primary-color);
        color: #fff;
        font-weight: 700;
        cursor: pointer;
    }
    .live-upload-note {
        color: #7f8c8d;
        font-size: 0.85rem;
        margin: -6px 0 12px;
    }
    .live-upload-progress {
        display: none;
        margin: 8px 0 14px;
    }
    .live-upload-progress.is-visible {
        display: block;
    }
    .live-upload-progress-bar {
        width: 100%;
        height: 10px;
        border-radius: 999px;
        background: #ececec;
        overflow: hidden;
    }
    .live-upload-progress-fill {
        height: 100%;
        width: 0%;
        border-radius: inherit;
        background: linear-gradient(90deg, #27ae60 0%, #1f8a4d 100%);
        transition: width 0.12s linear;
    }
    .live-upload-progress-text {
        margin-top: 6px;
        font-size: 0.84rem;
        color: #4d4d4d;
    }
    .live-upload-error {
        display: none;
        margin: 4px 0 10px;
        color: #c0392b;
        font-weight: 700;
        font-size: 0.88rem;
    }
    .live-upload-error.is-visible {
        display: block;
    }
    .live-video-list {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }
    .live-video-item {
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 10px;
        background: #fff;
        box-shadow: 0 6px 16px rgba(0,0,0,0.05);
        display: grid;
        gap: 10px;
    }
    .live-video-item video {
        width: 100%;
        height: 180px;
        border-radius: 10px;
        object-fit: cover;
        background: #000;
    }
    .live-video-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        color: #333;
        font-size: 0.88rem;
    }
    .live-video-delete {
        min-height: 36px;
        border: 1px solid #e74c3c;
        border-radius: 9px;
        background: #e74c3c;
        color: #fff;
        font-weight: 700;
        cursor: pointer;
    }
    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }
    .product-row {
        display: grid;
        gap: 10px;
        padding: 14px;
        border: 1px solid #eee;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
    }
    .product-thumb {
        width: 100%;
        height: 146px;
        object-fit: cover;
        border-radius: 12px;
        background: #f3f3f3;
    }
    .product-row h3 {
        margin: 0;
        color: #333;
        font-size: 1.02rem;
        line-height: 1.35;
    }
    .product-row p {
        margin: 0;
        color: #27ae60;
        font-size: 1.04rem;
        font-weight: 800;
    }
    .product-row-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .product-row-actions form {
        margin: 0;
    }
    .product-icon-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid #ddd;
        background: #f8f8f8;
        color: #333;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        cursor: pointer;
        transition: transform 0.15s ease, border-color 0.15s ease;
    }
    .product-icon-btn:hover {
        transform: translateY(-1px);
        border-color: #27ae60;
    }
    .product-icon-btn svg {
        width: 18px;
        height: 18px;
    }
    .product-icon-btn.delete {
        background: #e74c3c;
        border-color: #e74c3c;
        color: #fff;
    }
    .product-icon-btn.boost {
        background: #f4c400;
        border-color: #f4c400;
        color: #111;
    }
    .boost-inline-form {
        display: flex;
        gap: 6px;
        flex: 1 1 0;
    }
    .boost-plan-select {
        min-height: 38px;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 0 8px;
        background: #fff;
        color: #333;
        font-size: 0.84rem;
        width: 100%;
    }
    .product-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 0 12px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        border: 1px solid #ddd;
        background: #f8f8f8;
        color: #333;
        flex: 1 1 0;
    }
    .product-action-btn.delete {
        background: #e74c3c;
        border-color: #e74c3c;
        color: white;
        cursor: pointer;
    }
    .products-pagination {
        margin-top: 18px;
    }
    .products-pagination nav {
        display: flex;
        justify-content: center;
    }
    .products-pagination svg {
        width: 16px;
        height: 16px;
    }
    .confirm-modal {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(10, 16, 14, 0.58);
        z-index: 1300;
    }
    .confirm-modal.is-visible { display: flex; }
    .confirm-modal-card {
        width: 100%;
        max-width: 380px;
        padding: 24px;
        border-radius: 18px;
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        box-shadow: 0 24px 54px rgba(0, 0, 0, 0.22);
    }
    .confirm-modal-card h3 {
        margin: 0 0 8px;
        color: var(--text-color);
    }
    .confirm-modal-card p {
        margin: 0 0 18px;
        color: var(--muted-color);
        line-height: 1.5;
    }
    .confirm-modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
    .confirm-modal-btn {
        min-height: 42px;
        padding: 0 16px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--surface-alt);
        color: var(--text-color);
        font-weight: 700;
        cursor: pointer;
    }
    .confirm-modal-btn.danger {
        background: #e74c3c;
        border-color: #e74c3c;
        color: white;
    }
    @media (max-width: 900px) {
        .actions-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .products-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .live-video-list {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .live-upload-form {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 640px) {
        .dashboard-container {
            margin: 24px auto;
            padding: 0 12px;
        }
        .stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .stat {
            padding: 20px 14px;
        }
        .actions {
            padding: 18px;
        }
        .actions-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }
        .action-btn {
            min-height: 66px;
            padding: 12px 8px;
            font-size: 0.82rem;
            line-height: 1.25;
            border-radius: 12px;
        }
        .action-btn-icon {
            width: 20px;
            height: 20px;
            margin-bottom: 6px;
        }
        .products-panel {
            padding: 18px;
        }
        .products-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .live-video-list {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        .product-row {
            padding: 12px;
        }
        .product-thumb {
            height: 126px;
        }
        .product-icon-btn {
            width: 38px;
            height: 38px;
        }
        .boost-inline-form {
            flex-direction: column;
        }
        .live-panel {
            padding: 18px;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    @if(session('success'))
        <div style="margin-bottom:16px; padding:12px 14px; border-radius:12px; background:rgba(47,131,105,.18); color:#d7fff3;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:18px; flex-wrap:wrap;">
        <h1 style="margin-bottom:0;">{{ $vendor->shop_name }} Dashboard</h1>
        <form action="{{ route('vendor.live.toggle') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="confirm-modal-btn {{ $vendor->is_live ? 'danger' : '' }}" style="border-color:{{ $vendor->is_live ? '#e74c3c' : 'var(--primary-color)' }}; background:{{ $vendor->is_live ? '#e74c3c' : 'var(--primary-color)' }}; color:white;">
                {{ $vendor->is_live ? 'Pause Live Selling' : 'Go Live Now' }}
            </button>
        </form>
    </div>

    <div class="vendor-card" style="display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:12px; margin-bottom:24px; padding:18px;">
        <div>
            <div style="color:var(--muted-color); font-size:.85rem;">Live Status</div>
            <strong>{{ $vendor->is_live ? 'Live Now' : 'Paused' }}</strong>
        </div>
        <div>
            <div style="color:var(--muted-color); font-size:.85rem;">Wallet Balance</div>
            <strong>₦{{ number_format((float) $vendor->wallet_balance, 2) }}</strong>
        </div>
        <div>
            <div style="color:var(--muted-color); font-size:.85rem;">Subscription</div>
            <strong>
                {{
                    $vendor->subscription_plan === 'free'
                        ? 'Free Trial'
                        : ($vendor->subscription_plan === 'premium_3m'
                            ? 'Premium (3 Months)'
                            : ($vendor->subscription_plan === 'premium_6m'
                                ? 'Premium (6 Months)'
                                : ($vendor->subscription_plan === 'premium_12m' ? 'Premium (1 Year)' : ucfirst($vendor->subscription_plan))))
                }}
            </strong>
        </div>
    </div>

    <div class="vendor-card" style="padding:14px; margin-bottom:18px; display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
        <div>
            <div style="color:var(--muted-color); font-size:.84rem;">Shop Boost</div>
            <strong>
                {{ $vendor->isBoosted() ? 'Boosted until ' . $vendor->boosted_until->format('d M Y') : 'Not boosted' }}
            </strong>
            <div style="color:var(--muted-color); font-size:.82rem;">3 months: ₦700 • 6 months: ₦1000 • 1 year: ₦1500</div>
        </div>
        <form action="{{ route('vendor.boost.shop') }}" method="POST" class="boost-inline-form" style="max-width:360px; width:100%;">
            @csrf
            <select name="boost_plan" class="boost-plan-select" required>
                <option value="premium_3m">Boost Shop - 3 Months (₦700)</option>
                <option value="premium_6m">Boost Shop - 6 Months (₦1000)</option>
                <option value="premium_12m">Boost Shop - 1 Year (₦1500)</option>
            </select>
            <button type="submit" class="product-action-btn" style="border-color:#f4c400; background:#f4c400;">Boost Shop</button>
        </form>
    </div>

    @if($subscriptionExpired)
        <div style="margin-bottom:18px; padding:12px 14px; border-radius:12px; background:rgba(192,57,43,.12); color:#c0392b; font-weight:700; display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
            <span>Your free trial has expired. Upgrade now to continue uploading or editing products.</span>
            <a href="{{ route('vendor.subscription') }}" class="confirm-modal-btn danger" style="text-decoration:none;">Upgrade Plan</a>
        </div>
    @else
        <div style="margin-bottom:18px; padding:12px 14px; border-radius:12px; background:rgba(47,131,105,.12); color:var(--primary-color); font-weight:700; display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
            <span>Trial active until {{ $subscriptionExpiresAt ? $subscriptionExpiresAt->format('d M Y') : 'N/A' }}. You can upgrade anytime for premium visibility.</span>
            <a href="{{ route('vendor.subscription') }}" class="confirm-modal-btn" style="text-decoration:none;">See Upgrade Plans</a>
        </div>
    @endif

    <div class="stats">
        <div class="stat">
            <h3>{{ $vendor->total_orders ?? 0 }}</h3>
            <p>Total Orders</p>
        </div>
        <div class="stat">
            <h3>{{ $productCount }}</h3>
            <p>Products</p>
        </div>
        <div class="stat">
            <h3>{{ $vendor->rating ?? 'N/A' }}</h3>
            <p>Rating</p>
        </div>
        <div class="stat">
            <h3>₦{{ $vendor->total_revenue ?? 0 }}</h3>
            <p>Revenue</p>
        </div>
    </div>

    <div class="actions">
        <h2>Quick Actions</h2>
        <div class="actions-grid">
            <a href="{{ route('vendor.profile.edit') }}" class="action-btn">
                <svg class="action-btn-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 7.5A3.5 3.5 0 0 1 7.5 4h9A3.5 3.5 0 0 1 20 7.5v9a3.5 3.5 0 0 1-3.5 3.5h-9A3.5 3.5 0 0 1 4 16.5v-9Z" stroke="currentColor" stroke-width="1.8"/>
                    <path d="m9 15 5.8-5.8 1.8 1.8L10.8 16.8H9V15Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                </svg>
                <span class="action-btn-label">Edit Shop Info</span>
            </a>
            <a href="{{ route('vendor.add-product') }}" class="action-btn">
                <svg class="action-btn-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M6 6.5A2.5 2.5 0 0 1 8.5 4h7A2.5 2.5 0 0 1 18 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 6 17.5v-11Z" stroke="currentColor" stroke-width="1.8"/>
                </svg>
                <span class="action-btn-label">Add New Product</span>
            </a>
            <a href="{{ route('vendor.products') }}" class="action-btn">
                <svg class="action-btn-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M7 7h10M7 12h10M7 17h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M5.5 4h13A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13A1.5 1.5 0 0 1 5.5 4Z" stroke="currentColor" stroke-width="1.8"/>
                </svg>
                <span class="action-btn-label">Manage Products</span>
            </a>
            <a href="{{ route('vendor.orders') }}" class="action-btn">
                <svg class="action-btn-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M7 6h10M7 12h10M7 18h7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M5.5 4h13A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13A1.5 1.5 0 0 1 5.5 4Z" stroke="currentColor" stroke-width="1.8"/>
                </svg>
                <span class="action-btn-label">View Orders</span>
            </a>
            <a href="{{ route('vendor.wallet') }}" class="action-btn">
                <svg class="action-btn-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5H18a2 2 0 0 1 2 2v2H6.5A2.5 2.5 0 0 0 4 11.5v-4Z" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M4 11.5A2.5 2.5 0 0 1 6.5 9H20v8a2 2 0 0 1-2 2H6.5A2.5 2.5 0 0 1 4 16.5v-5Z" stroke="currentColor" stroke-width="1.8"/>
                    <circle cx="16.5" cy="14" r="1" fill="currentColor"/>
                </svg>
                <span class="action-btn-label">Wallet</span>
            </a>
            <a href="{{ route('vendor.subscription') }}" class="action-btn">
                <svg class="action-btn-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 4 14.5 9l5.5.8-4 3.9.9 5.5L12 16.8 7.1 19.2 8 13.7 4 9.8 9.5 9 12 4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                </svg>
                <span class="action-btn-label">Subscription</span>
            </a>
        </div>
    </div>

    <div class="live-panel">
        <h2>Live Videos</h2>
        <p class="live-panel-copy">Upload short videos (max 60 seconds). Buyers will see them in Lives feed.</p>
        <form action="{{ route('vendor.live-videos.store') }}" method="POST" enctype="multipart/form-data" class="live-upload-form" id="liveVideoUploadForm">
            @csrf
            <input type="text" name="title" class="live-upload-input" placeholder="Video title (optional)" maxlength="120">
            <input type="file" name="video" class="live-upload-file" id="liveVideoFileInput" accept="video/mp4,video/webm,video/quicktime" required>
            <button type="submit" class="live-upload-btn" id="liveUploadSubmitBtn">Upload Video</button>
            <input type="hidden" name="duration_seconds" id="liveVideoDurationInput" value="">
        </form>
        <div class="live-upload-progress" id="liveUploadProgress">
            <div class="live-upload-progress-bar">
                <div class="live-upload-progress-fill" id="liveUploadProgressFill"></div>
            </div>
            <div class="live-upload-progress-text" id="liveUploadProgressText">Uploading... 0%</div>
        </div>
        <div class="live-upload-error" id="liveUploadError"></div>
        <div class="live-upload-note">1 minute max. Supported formats: MP4, WEBM, MOV.</div>

        @if($errors->has('video') || $errors->has('duration_seconds'))
            <div style="margin-bottom:12px; color:#c0392b; font-weight:600;">
                {{ $errors->first('duration_seconds') ?: $errors->first('video') }}
            </div>
        @endif

        @if($liveVideos->isEmpty())
            <p style="color:#7f8c8d;">No live videos uploaded yet.</p>
        @else
            <div class="live-video-list">
                @foreach($liveVideos as $liveVideo)
                    <div class="live-video-item">
                        <video src="{{ \App\Support\PublicStorage::url($liveVideo->video_path) }}" controls preload="metadata"></video>
                        <div class="live-video-meta">
                            <strong>{{ $liveVideo->title ?: 'Live video' }}</strong>
                            <span>{{ $liveVideo->duration_seconds }}s</span>
                        </div>
                        <form action="{{ route('vendor.live-videos.destroy', $liveVideo) }}" method="POST" class="js-confirm-delete" data-delete-label="{{ $liveVideo->title ?: 'this live video' }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="live-video-delete">Delete Video</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="products-panel">
        <h2>Your Products</h2>
        @if($products->isEmpty())
            <p style="color: #7f8c8d;">No products yet.</p>
        @else
            <div class="products-grid">
                @foreach($products as $product)
                    <div class="product-row">
                        @if($product->primary_image)
                            <img src="{{ \App\Support\PublicStorage::url($product->primary_image) }}" alt="{{ $product->name }}" class="product-thumb">
                        @else
                            <div class="product-thumb"></div>
                        @endif
                        <h3>{{ $product->name }}</h3>
                        <p>₦{{ $product->price }}</p>

                        <div class="product-row-actions">
                            <a href="{{ route('vendor.products.edit', $product) }}" class="product-icon-btn" title="Edit Product" aria-label="Edit Product">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 20h9"/>
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
                                </svg>
                            </a>
                            <form action="{{ route('vendor.products.destroy', $product) }}" method="POST" class="js-confirm-delete" data-delete-label="{{ $product->name }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="product-icon-btn delete" title="Delete Product" aria-label="Delete Product">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2"/>
                                        <path d="M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6"/>
                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                    </svg>
                                </button>
                            </form>
                            <form action="{{ route('vendor.products.boost', $product) }}" method="POST">
                                @csrf
                                <input type="hidden" name="boost_plan" value="premium_3m">
                                <button type="submit" class="product-icon-btn boost" title="Boost Product (3 months)" aria-label="Boost Product">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M4.5 16.5c2.5.3 4.7-.4 6.5-2.2 2.1-2.1 2.8-5.3 1.9-8.3 3 .9 6.2.2 8.3-1.9.2 1 .3 2.1.3 3.2 0 6-4.9 10.9-10.9 10.9-1.1 0-2.2-.1-3.2-.3z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="products-pagination">
                    {{ $products->onEachSide(1)->links() }}
                </div>
            @endif
        @endif
    </div>

    <div class="orders" id="recent-orders">
        <h2>Recent Orders</h2>
        @if($orders->isEmpty())
            <p style="color: #7f8c8d;">No orders yet.</p>
        @else
            @foreach($orders as $order)
                <div class="order">
                    <div style="float:right;">
                        <a href="{{ route('vendor.orders.show', $order) }}" style="color:var(--primary-color); font-weight:700; text-decoration:none;">Open Order</a>
                    </div>
                    <strong>Order #{{ $order->id }}</strong> -
                    <span class="order-status">{{ ucfirst($order->status) }}</span> -
                    ₦{{ $order->total_amount }}
                </div>
                @endforeach
        @endif
    </div>
</div>

<div class="confirm-modal" id="deleteProductModal" aria-hidden="true">
    <div class="confirm-modal-card">
        <h3>Delete Product?</h3>
        <p id="deleteProductMessage">Are you sure you want to delete this item?</p>
        <div class="confirm-modal-actions">
            <button type="button" class="confirm-modal-btn" id="cancelDeleteProduct">Cancel</button>
            <button type="button" class="confirm-modal-btn danger" id="confirmDeleteProduct">Yes, Delete</button>
        </div>
    </div>
</div>

<div class="confirm-modal" id="uploadSuccessModal" aria-hidden="true">
    <div class="confirm-modal-card">
        <h3>Upload Successful</h3>
        <p>Your live video was uploaded successfully.</p>
        <div class="confirm-modal-actions">
            <button type="button" class="confirm-modal-btn danger" id="closeUploadSuccessModal">OK</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    (function () {
        const modal = document.getElementById('deleteProductModal');
        const message = document.getElementById('deleteProductMessage');
        const cancelButton = document.getElementById('cancelDeleteProduct');
        const confirmButton = document.getElementById('confirmDeleteProduct');
        const forms = document.querySelectorAll('.js-confirm-delete');
        const uploadForm = document.getElementById('liveVideoUploadForm');
        const videoFileInput = document.getElementById('liveVideoFileInput');
        const durationInput = document.getElementById('liveVideoDurationInput');
        const uploadButton = document.getElementById('liveUploadSubmitBtn');
        const uploadProgress = document.getElementById('liveUploadProgress');
        const uploadProgressFill = document.getElementById('liveUploadProgressFill');
        const uploadProgressText = document.getElementById('liveUploadProgressText');
        const uploadError = document.getElementById('liveUploadError');
        const uploadSuccessModal = document.getElementById('uploadSuccessModal');
        const closeUploadSuccessModal = document.getElementById('closeUploadSuccessModal');
        let activeForm = null;

        function closeModal() {
            modal.classList.remove('is-visible');
            modal.setAttribute('aria-hidden', 'true');
            activeForm = null;
        }

        forms.forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                activeForm = form;
                const label = form.dataset.deleteLabel || 'this item';
                message.textContent = `Are you sure you want to delete "${label}"?`;
                modal.classList.add('is-visible');
                modal.setAttribute('aria-hidden', 'false');
            });
        });

        const showUploadError = function (text) {
            if (!uploadError) {
                return;
            }

            uploadError.textContent = text;
            uploadError.classList.add('is-visible');
        };

        const clearUploadError = function () {
            if (!uploadError) {
                return;
            }

            uploadError.textContent = '';
            uploadError.classList.remove('is-visible');
        };

        const setUploadProgress = function (value) {
            if (!uploadProgress || !uploadProgressFill || !uploadProgressText) {
                return;
            }

            const safeValue = Math.max(0, Math.min(100, Math.round(value)));
            uploadProgress.classList.add('is-visible');
            uploadProgressFill.style.width = safeValue + '%';
            uploadProgressText.textContent = 'Uploading... ' + safeValue + '%';
        };

        const resetUploadProgress = function () {
            if (!uploadProgress || !uploadProgressFill || !uploadProgressText) {
                return;
            }

            uploadProgress.classList.remove('is-visible');
            uploadProgressFill.style.width = '0%';
            uploadProgressText.textContent = 'Uploading... 0%';
        };

        const setUploadBusy = function (busy) {
            if (!uploadButton || !videoFileInput) {
                return;
            }

            uploadButton.disabled = busy;
            videoFileInput.disabled = busy;
            uploadButton.textContent = busy ? 'Uploading...' : 'Upload Video';
        };

        const openUploadSuccessModal = function () {
            if (!uploadSuccessModal) {
                return;
            }

            uploadSuccessModal.classList.add('is-visible');
            uploadSuccessModal.setAttribute('aria-hidden', 'false');
        };

        const closeUploadModal = function () {
            if (!uploadSuccessModal) {
                return;
            }

            uploadSuccessModal.classList.remove('is-visible');
            uploadSuccessModal.setAttribute('aria-hidden', 'true');
        };

        if (closeUploadSuccessModal) {
            closeUploadSuccessModal.addEventListener('click', function () {
                closeUploadModal();
                window.location.reload();
            });
        }

        if (uploadSuccessModal) {
            uploadSuccessModal.addEventListener('click', function (event) {
                if (event.target === uploadSuccessModal) {
                    closeUploadModal();
                    window.location.reload();
                }
            });
        }

        if (uploadForm && videoFileInput && durationInput) {
            uploadForm.addEventListener('submit', function (event) {
                const file = videoFileInput.files && videoFileInput.files[0];
                if (!file) {
                    showUploadError('Please select a video file first.');
                    return;
                }

                event.preventDefault();
                clearUploadError();

                const tempVideo = document.createElement('video');
                tempVideo.preload = 'metadata';
                tempVideo.src = URL.createObjectURL(file);

                tempVideo.onloadedmetadata = function () {
                    const duration = Math.ceil(tempVideo.duration || 0);
                    URL.revokeObjectURL(tempVideo.src);

                    if (!duration || duration > 60) {
                        showUploadError('Video must be 60 seconds or less.');
                        durationInput.value = '';
                        return;
                    }

                    durationInput.value = String(duration);

                    const formData = new FormData(uploadForm);
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', uploadForm.action, true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                    setUploadBusy(true);
                    setUploadProgress(0);

                    xhr.upload.addEventListener('progress', function (progressEvent) {
                        if (!progressEvent.lengthComputable) {
                            return;
                        }

                        const percent = (progressEvent.loaded / progressEvent.total) * 100;
                        setUploadProgress(percent);
                    });

                    xhr.addEventListener('load', function () {
                        setUploadBusy(false);

                        if (xhr.status >= 200 && xhr.status < 300) {
                            setUploadProgress(100);
                            if (uploadProgressText) {
                                uploadProgressText.textContent = 'Upload complete.';
                            }
                            openUploadSuccessModal();
                            return;
                        }

                        resetUploadProgress();
                        showUploadError('Upload failed. Please try again.');
                    });

                    xhr.addEventListener('error', function () {
                        setUploadBusy(false);
                        resetUploadProgress();
                        showUploadError('Network error during upload. Please try again.');
                    });

                    xhr.addEventListener('abort', function () {
                        setUploadBusy(false);
                        resetUploadProgress();
                        showUploadError('Upload was cancelled.');
                    });

                    xhr.send(formData);
                };

                tempVideo.onerror = function () {
                    URL.revokeObjectURL(tempVideo.src);
                    showUploadError('Could not read video duration. Try another file.');
                };
            });
        }

        cancelButton.addEventListener('click', closeModal);
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });
        confirmButton.addEventListener('click', function () {
            if (activeForm) {
                activeForm.submit();
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('is-visible')) {
                closeModal();
            }
        });
    })();
</script>
@endsection


