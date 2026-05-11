@extends('layouts.app')

@section('title', $vendor->shop_name . ' - PikFreshFood')

@section('styles')
<style>
    .vendor-show-page { max-width: 1100px; margin: 30px auto; padding: 0 16px; }
    .vendor-hero {
        display: grid;
        grid-template-columns: 120px 1fr auto;
        gap: 20px;
        align-items: center;
        padding: 24px;
        border-radius: 20px;
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        margin-bottom: 24px;
    }
    .vendor-hero img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        background: color-mix(in srgb, var(--primary-color) 14%, white 86%);
    }
    .vendor-hero p { color: var(--muted-color); line-height: 1.6; }
    .vendor-actions { display: grid; gap: 10px; }
    .vendor-action-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }
    .vendor-action {
        min-height: 42px;
        padding: 0 16px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: var(--primary-color);
        color: white;
        text-decoration: none;
        font-weight: 700;
        border: none;
        cursor: pointer;
    }
    .vendor-action svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.9;
        stroke-linecap: round;
        stroke-linejoin: round;
        flex-shrink: 0;
    }
    .vendor-action.secondary {
        background: color-mix(in srgb, var(--primary-color) 14%, white 86%);
        color: var(--primary-color);
    }
    .vendor-action.muted {
        background: #e8ecef;
        color: #5f6b73;
        cursor: not-allowed;
    }
    .vendor-action.outline {
        background: transparent;
        color: var(--text-color);
        border: 1px solid var(--border-color);
    }
    .vendor-meta {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin: 18px 0 0;
    }
    .vendor-meta-item {
        padding: 12px 14px;
        border-radius: 14px;
        background: color-mix(in srgb, var(--primary-color) 10%, white 90%);
    }
    .vendor-meta-label {
        font-size: 0.78rem;
        color: var(--muted-color);
        margin-bottom: 4px;
    }
    .vendor-meta-value {
        font-size: 1rem;
        font-weight: 800;
        color: var(--text-color);
    }
    .vendor-section {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 20px;
        margin-bottom: 22px;
    }
    .section-heading {
        font-size: 1.05rem;
        font-weight: 800;
        margin-bottom: 8px;
        color: var(--text-color);
    }
    .section-copy {
        color: var(--muted-color);
        margin-bottom: 14px;
        line-height: 1.6;
    }
    .review-form {
        display: grid;
        gap: 12px;
    }
    .review-form:target {
        scroll-margin-top: 110px;
    }
    .review-stars {
        display: inline-flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: nowrap;
    }
    .review-star-option {
        position: relative;
    }
    .review-star-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .review-star-option label {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        border: 1px solid color-mix(in srgb, var(--border-color) 80%, #f5c542 20%);
        background: white;
        color: #c3cad0;
        cursor: pointer;
        transition: transform 0.18s ease, color 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
    }
    .review-star-option label svg {
        width: 23px;
        height: 23px;
        fill: currentColor;
        stroke: none;
    }
    .review-star-option:hover label,
    .review-star-option:hover ~ .review-star-option label,
    .review-star-option:has(input:checked) label,
    .review-star-option:has(input:checked) ~ .review-star-option label {
        color: #f5c542;
        border-color: rgba(245, 197, 66, 0.5);
        box-shadow: 0 10px 22px rgba(245, 197, 66, 0.18);
    }
    .review-star-option:hover label,
    .review-star-option:has(input:checked) label {
        transform: translateY(-2px) scale(1.04);
    }
    .review-textarea {
        min-height: 110px;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        resize: vertical;
    }
    .review-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }
    .review-card {
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 16px;
        background: color-mix(in srgb, var(--primary-color) 6%, white 94%);
    }
    .review-card strong {
        display: block;
        margin-bottom: 6px;
    }
    .review-rating {
        color: #d08a00;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .review-comment {
        color: var(--muted-color);
        line-height: 1.6;
    }
    .vendor-products-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }
    .vendor-product-card {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
    }
    .vendor-product-card img {
        width: 100%;
        height: 170px;
        object-fit: cover;
        background: color-mix(in srgb, var(--primary-color) 12%, white 88%);
    }
    .vendor-product-card-body { padding: 14px; }
    .live-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; background:rgba(39,174,96,.12); color:#1f7a43; font-weight:700; font-size:.82rem; margin-bottom:10px; }
    .call-choice-modal {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(10, 16, 14, 0.58);
        z-index: 1300;
    }
    .call-choice-modal.is-visible { display: flex; }
    .call-choice-card {
        width: 100%;
        max-width: 420px;
        padding: 24px;
        border-radius: 20px;
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        box-shadow: 0 24px 54px rgba(0, 0, 0, 0.22);
    }
    .call-choice-card h3 { margin: 0 0 8px; color: var(--text-color); }
    .call-choice-card p { margin: 0 0 18px; color: var(--muted-color); line-height: 1.6; }
    .call-choice-actions { display: grid; gap: 10px; }
    .call-choice-btn {
        min-height: 46px;
        padding: 0 16px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: var(--bottom-sheet-bg);
        color: var(--text-color);
        font-weight: 700;
        cursor: pointer;
    }
    .call-choice-btn.primary {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    .call-choice-btn:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }
    @media (max-width: 900px) {
        .vendor-hero { grid-template-columns: 96px 1fr; }
        .vendor-actions { grid-column: 1 / -1; }
        .vendor-action-row { grid-template-columns: 1fr; }
        .vendor-meta { grid-template-columns: 1fr; }
        .review-grid { grid-template-columns: 1fr; }
        .vendor-products-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .vendor-hero { grid-template-columns: 1fr; text-align:center; }
        .vendor-hero img { margin: 0 auto; }
        .vendor-products-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .vendor-product-card img { height: 132px; }
    }
</style>
@endsection

@section('content')
<div class="vendor-show-page">
    @php
        $cleanPhone = preg_replace('/\D+/', '', (string) $vendor->phone);
        $canCallVendor = filled($cleanPhone);
        $directionDestination = filled($vendor->latitude) && filled($vendor->longitude)
            ? $vendor->latitude . ',' . $vendor->longitude
            : $vendor->address;
        $googleDirectionsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode((string) $directionDestination);
    @endphp

    <div class="vendor-hero">
        <img src="{{ $vendor->profile_image ? \App\Support\PublicStorage::url($vendor->profile_image) : 'https://placehold.co/220x220?text=' . urlencode($vendor->shop_name) }}" alt="{{ $vendor->shop_name }}">
        <div>
            @if($vendor->is_live)
                <div class="live-badge">Live Now</div>
            @endif
            <h1>{{ $vendor->shop_name }}</h1>
            <p>{{ $vendor->description ?: 'Fresh products from a trusted local seller.' }}</p>
            <p>{{ $vendor->address }}</p>

            <div class="vendor-meta">
                <div class="vendor-meta-item">
                    <div class="vendor-meta-label">Rating</div>
                    <div class="vendor-meta-value">{{ $vendor->rating ?: 'N/A' }}</div>
                </div>
                <div class="vendor-meta-item">
                    <div class="vendor-meta-label">Orders</div>
                    <div class="vendor-meta-value">{{ $vendor->total_orders }}</div>
                </div>
                <div class="vendor-meta-item">
                    <div class="vendor-meta-label">Plan</div>
                    <div class="vendor-meta-value">
                        {{
                            $vendor->subscription_plan === 'free'
                                ? 'Free Trial'
                                : ($vendor->subscription_plan === 'premium_3m'
                                    ? 'Premium (3 Months)'
                                    : ($vendor->subscription_plan === 'premium_6m'
                                        ? 'Premium (6 Months)'
                                        : ($vendor->subscription_plan === 'premium_12m' ? 'Premium (1 Year)' : ucfirst($vendor->subscription_plan))))
                        }}
                    </div>
                </div>
            </div>
        </div>
        <div class="vendor-actions">
            @auth
                @if(auth()->id() !== $vendor->user_id)
                    <a href="{{ route('messages.show', $vendor->user_id) }}" class="vendor-action">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"></path>
                        </svg>
                        Message Vendor
                    </a>
                @endif

                @if(auth()->user()->isBuyer())
                    <div class="vendor-action-row">
                        @if($canCallVendor)
                            <button
                                type="button"
                                class="vendor-action"
                                id="voiceCallButton"
                                data-online-url="{{ route('vendor.call.online', $vendor, false) }}"
                                data-offline-url="tel:{{ $cleanPhone }}"
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.5 3a2 2 0 0 1-.6 1.8l-1.3 1.3a16 16 0 0 0 6.4 6.4l1.3-1.3a2 2 0 0 1 1.8-.6l3 .5A2 2 0 0 1 22 16.9Z"></path>
                                </svg>
                                Voice Call
                            </button>
                        @else
                            <button
                                type="button"
                                class="vendor-action"
                                id="voiceCallButton"
                                data-online-url="{{ route('vendor.call.online', $vendor, false) }}"
                                data-offline-url=""
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.5 3a2 2 0 0 1-.6 1.8l-1.3 1.3a16 16 0 0 0 6.4 6.4l1.3-1.3a2 2 0 0 1 1.8-.6l3 .5A2 2 0 0 1 22 16.9Z"></path>
                                </svg>
                                Voice Call
                            </button>
                        @endif

                        <button
                            type="button"
                            class="vendor-action secondary"
                            id="videoCallButton"
                            data-online-url="{{ route('vendor.call.online', $vendor, false) }}"
                        >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="3" y="6" width="13" height="12" rx="2"></rect>
                                    <path d="m16 10 5-3v10l-5-3"></path>
                                </svg>
                                Video Call
                        </button>
                    </div>

                    <div class="vendor-action-row">
                        <a href="#rate-vendor" class="vendor-action outline">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="m12 17.3-6.2 3.3 1.2-7-5.1-5 7-1 3.1-6.3 3.1 6.3 7 1-5.1 5 1.2 7z"></path>
                            </svg>
                            Rate Vendor
                        </a>
                        <a href="#buyer-reviews" class="vendor-action outline">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"></path>
                            </svg>
                            Read Reviews
                        </a>
                    </div>
                @endif
            @endauth

            <a href="{{ $googleDirectionsUrl }}" class="vendor-action secondary" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 21s6-5.4 6-11a6 6 0 1 0-12 0c0 5.6 6 11 6 11Z"></path>
                    <circle cx="12" cy="10" r="2.2"></circle>
                </svg>
                Get Direction
            </a>
        </div>
    </div>

    @if($vendor->promo_video_url)
        <div class="vendor-section">
            <div class="section-heading">Vendor Video</div>
            <p class="section-copy">Watch a food reel or promo video from this vendor.</p>
            <a href="{{ $vendor->promo_video_url }}" target="_blank" rel="noopener" class="vendor-action">Watch Video</a>
        </div>
    @endif

    @auth
        @if(auth()->user()->isBuyer() && auth()->id() !== $vendor->user_id)
            <div class="vendor-section" id="rate-vendor">
                <div class="section-heading">Rate This Vendor</div>

                @if($buyerReviewableOrder)
                    <p class="section-copy">Choose a rating, write your review about this vendor, and submit it. Buyers who have ordered from this vendor can come back and add more reviews anytime.</p>
                    <form action="{{ route('vendor.reviews.store', $vendor) }}" method="POST" class="review-form" id="vendor-review-form">
                        @csrf
                        <div class="review-stars">
                            @for($star = 5; $star >= 1; $star--)
                                <div class="review-star-option">
                                    <input type="radio" id="rating{{ $star }}" name="rating" value="{{ $star }}" {{ old('rating') == $star ? 'checked' : '' }} required>
                                    <label for="rating{{ $star }}" aria-label="{{ $star }} star{{ $star > 1 ? 's' : '' }}">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M12 2.8 14.9 8.7l6.5.9-4.7 4.5 1.1 6.4L12 17.4 6.2 20.5l1.1-6.4-4.7-4.5 6.5-.9L12 2.8Z"></path>
                                        </svg>
                                    </label>
                                </div>
                            @endfor
                        </div>
                        <textarea name="comment" class="review-textarea" id="vendorReviewComment" placeholder="Write your review about this vendor...">{{ old('comment') }}</textarea>
                        <button type="submit" class="vendor-action">Submit Rating</button>
                    </form>
                @else
                    <p class="section-copy">Buyers can rate vendors after at least one delivered order.</p>
                @endif
            </div>
        @endif
    @endauth

    <div class="vendor-section" id="buyer-reviews">
        <div class="section-heading">Buyer Reviews</div>
        @if($reviews->isNotEmpty())
            <div class="review-grid">
                @foreach($reviews as $review)
                    <div class="review-card">
                        <strong>{{ $review->user->name }}</strong>
                        <div class="review-rating">{{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }}</div>
                        <div class="review-comment">{{ $review->comment ?: 'No written comment provided.' }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="section-copy">No ratings yet. Buyers will see reviews here after completed orders.</p>
        @endif
    </div>

    <div class="section-heading">Products</div>
    <div class="vendor-products-grid">
        @foreach($products as $product)
            <a href="{{ route('product.show', $product) }}" class="vendor-product-card">
                <img src="{{ $product->primary_image ? \App\Support\PublicStorage::url($product->primary_image) : 'https://placehold.co/640x480?text=' . urlencode($product->name) }}" alt="{{ $product->name }}">
                <div class="vendor-product-card-body">
                    <strong>{{ $product->name }}</strong>
                    <div class="product-vendor">{{ ucfirst($product->category) }}</div>
                    <div class="product-price">N{{ $product->price }}</div>
                </div>
            </a>
        @endforeach
    </div>
</div>

<div class="call-choice-modal" id="callChoiceModal" aria-hidden="true">
    <div class="call-choice-card">
        <h3>How do you want to call this vendor?</h3>
        <p>Choose an online browser call or switch to your phone dialer for an offline call.</p>
        <div class="call-choice-actions">
            <button type="button" class="call-choice-btn primary" id="callOnlineButton">Online Call</button>
            <button type="button" class="call-choice-btn" id="callOfflineButton">Offline Call</button>
            <button type="button" class="call-choice-btn" id="closeCallChoiceButton">Cancel</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const voiceCallButton = document.getElementById('voiceCallButton');
        const videoCallButton = document.getElementById('videoCallButton');
        const modal = document.getElementById('callChoiceModal');
        const onlineButton = document.getElementById('callOnlineButton');
        const offlineButton = document.getElementById('callOfflineButton');
        const closeButton = document.getElementById('closeCallChoiceButton');
        let selectedCallType = 'audio';

        if (!voiceCallButton || !modal || !onlineButton || !offlineButton || !closeButton) {
            return;
        }

        const onlineUrl = voiceCallButton.dataset.onlineUrl || '';
        const offlineUrl = voiceCallButton.dataset.offlineUrl || '';

        if (!offlineUrl) {
            offlineButton.disabled = true;
            offlineButton.textContent = 'Offline Call Unavailable';
        }

        const closeModal = function () {
            modal.classList.remove('is-visible');
            modal.setAttribute('aria-hidden', 'true');
        };

        const openModal = function (callType) {
            selectedCallType = callType;
            onlineButton.textContent = callType === 'video' ? 'Online Video Call' : 'Online Call';
            offlineButton.style.display = callType === 'video' ? 'none' : '';
            modal.classList.add('is-visible');
            modal.setAttribute('aria-hidden', 'false');
        };

        voiceCallButton.addEventListener('click', function () {
            openModal('audio');
        });

        if (videoCallButton) {
            videoCallButton.addEventListener('click', function () {
                openModal('video');
            });
        }

        onlineButton.addEventListener('click', function () {
            if (!onlineUrl) {
                return;
            }

            onlineButton.disabled = true;
            onlineButton.textContent = selectedCallType === 'video' ? 'Preparing Video...' : 'Preparing Call...';

            const preparePromise = window.PikFreshCallLauncher
                ? window.PikFreshCallLauncher.prepareMedia({ type: selectedCallType })
                : Promise.resolve(true);

            preparePromise
                .then(function () {
                    return fetch(onlineUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            type: selectedCallType,
                        }),
                        credentials: 'same-origin',
                    });
                })
                .then(function (response) {
                    return window.PikFreshCallLauncher
                        ? window.PikFreshCallLauncher.parseJsonResponse(response, 'Unable to start call.')
                        : response.json();
                })
                .then(function (payload) {
                    if (payload.call_url) {
                        if (window.PikFreshCallLauncher) {
                            window.PikFreshCallLauncher.open(payload.call_url, {
                                title: selectedCallType === 'video' ? 'Video call with {{ addslashes($vendor->shop_name) }}' : 'Audio call with {{ addslashes($vendor->shop_name) }}',
                            });
                        } else {
                            window.location.href = payload.call_url;
                        }
                    }
                })
                .catch(function (error) {
                    onlineButton.disabled = false;
                    onlineButton.textContent = selectedCallType === 'video' ? 'Online Video Call' : 'Online Call';
                    window.alert(window.PikFreshCallLauncher
                        ? window.PikFreshCallLauncher.callErrorMessage(error, 'start the call')
                        : 'Could not start the call. Please try again.');
                })
                .finally(function () {
                    closeModal();
                });
        });

        offlineButton.addEventListener('click', function () {
            if (offlineUrl) {
                window.location.href = offlineUrl;
            }
        });

        closeButton.addEventListener('click', closeModal);
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
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
