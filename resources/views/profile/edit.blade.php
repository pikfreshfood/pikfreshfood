@extends('layouts.app')

@section('title', 'Profile - PikFreshFood')

@section('styles')
<style>
    .main-content { padding: 0 0 90px; }
    .profile-shell { max-width: 720px; margin: 0 auto; padding: 0 14px 24px; }
    .profile-topbar { height: 6px; background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%); margin: 0 -14px 12px; border-radius: 0 0 10px 10px; }

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
        background: white;
        color: #111;
        border: 1px solid #e8eee9;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }

    .profile-card { padding: 16px; margin-bottom: 12px; border-left: 4px solid var(--secondary-color); }
    .profile-hero { display: flex; gap: 12px; align-items: center; margin-bottom: 12px; }
    .profile-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, #1e8a4a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: white;
        flex-shrink: 0;
        border: 3px solid var(--secondary-color);
        box-shadow: 0 4px 12px rgba(22,132,71,0.2);
    }
    .profile-meta h1 {
        color: #111;
        margin: 0;
        font-size: 1.5rem;
        line-height: 1;
    }
    .profile-badge {
        background: var(--secondary-color);
        color: #111;
        font-size: 0.72rem;
        font-weight: 800;
        margin-left: 8px;
        vertical-align: middle;
        padding: 2px 8px;
        border-radius: 999px;
    }
    .profile-subtext {
        margin-top: 4px;
        font-size: 0.74rem;
        color: #5b6b5f;
        line-height: 1.4;
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
        background: white;
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .profile-action-btn:hover { background: #eef8f1; }
    .profile-action-btn.upgrade {
        background: var(--secondary-color);
        color: #111;
        border-color: #e6b800;
        box-shadow: 0 4px 12px rgba(244,196,0,0.24);
    }

    .profile-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 12px;
    }
    .stat-tile {
        background: white;
        border: 1px solid #e8eee9;
        border-top: 3px solid var(--secondary-color);
        border-radius: 12px;
        padding: 16px 10px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: transform 0.15s;
    }
    .stat-tile:hover { transform: translateY(-2px); }
    .stat-value {
        color: var(--primary-color);
        font-size: 1.9rem;
        line-height: 1;
        font-weight: 900;
        margin-bottom: 4px;
    }
    .stat-label {
        color: #5b6b5f;
        font-size: 0.74rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
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
        width: 32px;
        height: 32px;
        text-align: center;
        color: var(--primary-color);
        background: var(--secondary-color);
        border-radius: 8px;
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
        border: 1px solid #dfe8e1;
        background: #f8faf9;
        color: #111;
        font-size: 0.9rem;
    }
    .profile-input::placeholder { color: #8a9a8f; }
    .profile-input:focus, .profile-select:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(22,132,71,0.12); }
    .profile-button {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1e8a4a 100%);
        color: white;
        border: none;
        margin-top: 4px;
        box-shadow: 0 6px 14px rgba(22,132,71,0.2);
    }
    .profile-button:hover { filter: brightness(1.03); }
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
<a href="{{ route('home') }}" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none; color:var(--muted-color); font-weight:700; font-size:0.9rem; margin:14px 0 12px;">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 12h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        Back to Home
    </a>
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
