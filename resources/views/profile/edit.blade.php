@extends('layouts.app')

@section('title', 'Profile - PikFreshFood')

@section('styles')
<style>
    .main-content { padding: 0 0 90px; }
    .profile-shell { max-width: 720px; margin: 0 auto; padding: 0 14px 24px; }
    .profile-topbar { height: 28px; background: var(--primary-color); margin: 0 -14px 12px; }

    .profile-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 0;
        background: transparent;
        box-shadow: none;
    }

    .profile-card,
    .social-card,
    .menu-card,
    .logout-card,
    .profile-form-card {
        background: #123f34;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        box-shadow: none;
    }

    .profile-card { padding: 14px; margin-bottom: 10px; }
    .profile-hero { display: flex; gap: 12px; align-items: center; margin-bottom: 12px; }
    .profile-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d8d1bf 0%, #84796f 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #123f34;
        flex-shrink: 0;
    }
    .profile-meta h1 {
        color: white;
        margin: 0;
        font-size: 1.5rem;
        line-height: 1;
    }
    .profile-badge {
        color: #ffc107;
        font-size: 0.75rem;
        font-weight: 700;
        margin-left: 6px;
        vertical-align: middle;
    }
    .profile-subtext {
        margin-top: 4px;
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.35;
    }

    .profile-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .profile-action-btn,
    .profile-button {
        width: 100%;
        padding: 11px 14px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 700;
        text-align: center;
    }
    .profile-action-btn {
        background: #0f352c;
        color: white;
    }
    .profile-action-btn.upgrade {
        background: #2f8369;
        color: #ffe082;
    }

    .profile-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-bottom: 10px;
    }
    .stat-tile {
        background: #123f34;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        padding: 14px 10px;
        text-align: center;
    }
    .stat-value {
        color: #14d3b5;
        font-size: 2rem;
        line-height: 1;
        font-weight: 800;
        margin-bottom: 4px;
    }
    .stat-label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.72rem;
    }

    .menu-card,
    .profile-form-card {
        padding: 10px 0;
        margin-bottom: 10px;
        overflow: hidden;
    }
    .card-title {
        padding: 0 14px 10px;
        font-size: 0.95rem;
        font-weight: 800;
        color: white;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .menu-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 14px;
        text-decoration: none;
        color: white;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }
    .menu-row:first-of-type { border-top: none; }
    .menu-left {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }
    .menu-icon {
        width: 22px;
        height: 22px;
        text-align: center;
        color: #9dd8ca;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .menu-icon svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.9;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .menu-name {
        font-size: 0.95rem;
        font-weight: 700;
        color: white;
    }
    .menu-arrow {
        color: rgba(255, 255, 255, 0.35);
        font-size: 1.1rem;
    }

    .profile-form-card {
        display: none;
        padding: 14px;
    }
    .profile-form-card.is-open { display: block; }
    .profile-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .profile-input,
    .profile-select {
        width: 100%;
        padding: 12px 14px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        background: #0f352c;
        color: white;
        font-size: 0.9rem;
    }
    .profile-input::placeholder { color: rgba(255, 255, 255, 0.55); }
    .profile-button {
        background: #2f8369;
        color: white;
        border: none;
        margin-top: 4px;
    }
    .profile-message {
        margin: 0 14px 10px;
        padding: 10px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
    }
    .profile-message.success {
        background: rgba(47, 131, 105, 0.22);
        color: #d7fff3;
    }
    .profile-message.error {
        background: rgba(255, 107, 107, 0.18);
        color: #ffd7d7;
    }

    @media (max-width: 520px) {
        .profile-stats,
        .profile-actions {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-shell">
    <div class="profile-topbar"></div>

    <div class="profile-container">
        @if(session('success'))
            <div class="profile-message success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="profile-message error">{{ session('error') }}</div>
        @endif

        <div class="profile-card">
            <div class="profile-hero">
                <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div class="profile-meta">
                    <h1>{{ $user->name }}<span class="profile-badge">Pro</span></h1>
                    <div class="profile-subtext">
                        {{ $user->email }}<br>
                        {{ $user->phone ?: '+234 801 234 5678' }}
                    </div>
                </div>
            </div>

        </div>

        <div class="profile-form-card" id="profileFormCard">
            <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
                @csrf
                @method('PUT')
                <input type="text" name="name" value="{{ $user->name }}" placeholder="Full Name" class="profile-input" required>
                <input type="text" name="phone" value="{{ $user->phone }}" placeholder="Phone" class="profile-input">
                <input type="email" name="email" value="{{ $user->email }}" placeholder="Email (optional)" class="profile-input">
                <input type="text" name="address" value="{{ $user->address }}" placeholder="Address" class="profile-input">
                <select name="language" class="profile-select" required>
                    <option value="en" {{ $user->language == 'en' ? 'selected' : '' }}>English</option>
                    <option value="fr" {{ $user->language == 'fr' ? 'selected' : '' }}>French</option>
                    <option value="es" {{ $user->language == 'es' ? 'selected' : '' }}>Spanish</option>
                </select>
                <button type="submit" class="profile-button">Save Changes</button>
            </form>
        </div>

        <div class="profile-stats">
            <div class="stat-tile">
                <div class="stat-value">{{ $stats['orders'] }}</div>
                <div class="stat-label">Orders</div>
            </div>
            <div class="stat-tile">
                <div class="stat-value">{{ $stats['wishlist'] }}</div>
                <div class="stat-label">Wishlist</div>
            </div>
            <div class="stat-tile">
                <div class="stat-value">{{ $stats['addresses'] }}</div>
                <div class="stat-label">Addresses</div>
            </div>
        </div>

        <div class="menu-card">
            <button type="button" class="menu-row" id="toggleProfileFormMenu" style="width:100%; background:transparent; border:none; cursor:pointer;">
                <div class="menu-left">
                    <span class="menu-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 20h9"></path>
                            <path d="m16.5 3.5 4 4L8 20l-5 1 1-5Z"></path>
                        </svg>
                    </span>
                    <span class="menu-name">Edit Profile</span>
                </div>
                <span class="menu-arrow">›</span>
            </button>
            <a href="{{ route('orders.index') }}" class="menu-row">
                <div class="menu-left">
                    <span class="menu-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <rect x="4" y="5" width="16" height="15" rx="2"></rect>
                            <path d="M8 3v4"></path>
                            <path d="M16 3v4"></path>
                            <path d="M4 10h16"></path>
                        </svg>
                    </span>
                    <span class="menu-name">My Orders</span>
                </div>
                <span class="menu-arrow">›</span>
            </a>
            <a href="{{ route('profile.wishlist') }}" class="menu-row">
                <div class="menu-left">
                    <span class="menu-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 20s-7-4.5-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.5-7 10-7 10Z"></path>
                        </svg>
                    </span>
                    <span class="menu-name">Wishlist</span>
                </div>
                <span class="menu-arrow">›</span>
            </a>
            <a href="{{ route('profile.addresses') }}" class="menu-row">
                <div class="menu-left">
                    <span class="menu-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 21s6-5.4 6-11a6 6 0 1 0-12 0c0 5.6 6 11 6 11Z"></path>
                            <circle cx="12" cy="10" r="2.2"></circle>
                        </svg>
                    </span>
                    <span class="menu-name">Saved Addresses</span>
                </div>
                <span class="menu-arrow">›</span>
            </a>
            <a href="{{ route('profile.payment-methods') }}" class="menu-row">
                <div class="menu-left">
                    <span class="menu-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                            <path d="M3 10h18"></path>
                            <path d="M7 15h3"></path>
                        </svg>
                    </span>
                    <span class="menu-name">Payment Methods</span>
                </div>
                <span class="menu-arrow">›</span>
            </a>
            <a href="{{ route('profile.notifications') }}" class="menu-row">
                <div class="menu-left">
                    <span class="menu-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M6 9a6 6 0 1 1 12 0c0 7 3 7 3 7H3s3 0 3-7"></path>
                            <path d="M10 20a2 2 0 0 0 4 0"></path>
                        </svg>
                    </span>
                    <span class="menu-name">Notifications</span>
                </div>
                <span class="menu-arrow">›</span>
            </a>
            <div class="menu-row">
                <div class="menu-left">
                    <span class="menu-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M9.1 9a3 3 0 1 1 5.2 2c-.8.8-1.3 1.3-1.3 3"></path>
                            <circle cx="12" cy="17" r=".8"></circle>
                        </svg>
                    </span>
                    <span class="menu-name">Help & Support</span>
                </div>
                <span class="menu-arrow">›</span>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const formCard = document.getElementById('profileFormCard');
        const toggleButton = document.getElementById('toggleProfileForm');
        const toggleMenuButton = document.getElementById('toggleProfileFormMenu');

        function toggleForm() {
            formCard.classList.toggle('is-open');
        }

        toggleButton?.addEventListener('click', toggleForm);
        toggleMenuButton?.addEventListener('click', toggleForm);
    })();
</script>
@endsection
