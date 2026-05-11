@extends('layouts.app')

@section('title', 'Rate ' . $vendor->shop_name)

@section('styles')
<style>
    .vendor-review-page { max-width: 760px; margin: 30px auto; padding: 0 16px; }
    .review-shell {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 22px;
        padding: 24px;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.06);
    }
    .review-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 700;
    }
    .review-vendor-head {
        display: grid;
        grid-template-columns: 88px 1fr;
        gap: 18px;
        align-items: center;
        margin-bottom: 20px;
    }
    .review-vendor-head img {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        object-fit: cover;
        background: color-mix(in srgb, var(--primary-color) 12%, white 88%);
    }
    .review-vendor-head p { color: var(--muted-color); margin-top: 6px; line-height: 1.6; }
    .review-title { font-size: 1.7rem; font-weight: 900; color: var(--text-color); }
    .review-copy { color: var(--muted-color); margin-bottom: 18px; line-height: 1.7; }
    .review-form { display: grid; gap: 14px; }
    .review-stars {
        display: inline-flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 8px;
    }
    .review-star-option { position: relative; }
    .review-star-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .review-star-option label {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: 1px solid color-mix(in srgb, var(--border-color) 80%, #f5c542 20%);
        background: white;
        color: #c3cad0;
        cursor: pointer;
        transition: transform 0.18s ease, color 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
    }
    .review-star-option label svg {
        width: 24px;
        height: 24px;
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
        min-height: 160px;
        padding: 14px 16px;
        border-radius: 14px;
        border: 1px solid var(--border-color);
        resize: vertical;
        background: white;
        color: var(--text-color);
    }
    .review-submit {
        min-height: 48px;
        border: none;
        border-radius: 14px;
        background: var(--primary-color);
        color: white;
        font-weight: 800;
        cursor: pointer;
    }
    .review-note {
        padding: 16px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        background: color-mix(in srgb, var(--primary-color) 8%, white 92%);
        color: var(--muted-color);
        line-height: 1.6;
    }
    @media (max-width: 640px) {
        .review-shell { padding: 18px; border-radius: 18px; }
        .review-vendor-head { grid-template-columns: 1fr; text-align: center; }
        .review-vendor-head img { margin: 0 auto; }
    }
</style>
@endsection

@section('content')
<div class="vendor-review-page">
    <a href="{{ route('vendor.show', $vendor) }}" class="review-back">← Back to Vendor</a>

    <div class="review-shell">
        <div class="review-vendor-head">
            <img src="{{ $vendor->profile_image ? \App\Support\PublicStorage::url($vendor->profile_image) : 'https://placehold.co/220x220?text=' . urlencode($vendor->shop_name) }}" alt="{{ $vendor->shop_name }}">
            <div>
                <div class="review-title">Rate {{ $vendor->shop_name }}</div>
                <p>{{ $vendor->description ?: 'Share your experience with this vendor to help other buyers.' }}</p>
            </div>
        </div>

        @if($buyerReviewableOrder)
            <p class="review-copy">Choose a star rating, write your review, and submit it. If you have ordered from this vendor before, you can come back and add another review anytime.</p>

            <form action="{{ route('vendor.reviews.store', $vendor) }}" method="POST" class="review-form">
                @csrf
                <div class="review-stars">
                    @for($star = 5; $star >= 1; $star--)
                        <div class="review-star-option">
                            <input type="radio" id="review-rating-{{ $star }}" name="rating" value="{{ $star }}" {{ old('rating') == $star ? 'checked' : '' }} required>
                            <label for="review-rating-{{ $star }}" aria-label="{{ $star }} star{{ $star > 1 ? 's' : '' }}">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 2.8 14.9 8.7l6.5.9-4.7 4.5 1.1 6.4L12 17.4 6.2 20.5l1.1-6.4-4.7-4.5 6.5-.9L12 2.8Z"></path>
                                </svg>
                            </label>
                        </div>
                    @endfor
                </div>

                <textarea name="comment" class="review-textarea" id="vendorReviewComment" placeholder="Write a review about this vendor...">{{ old('comment') }}</textarea>

                <button type="submit" class="review-submit">Submit Rating And Review</button>
            </form>
        @else
            <div class="review-note">You can rate this vendor after at least one delivered order has been completed with them.</div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const reviewComment = document.getElementById('vendorReviewComment');

        if (reviewComment) {
            window.setTimeout(function () {
                reviewComment.focus();
            }, 120);
        }
    })();
</script>
@endsection
