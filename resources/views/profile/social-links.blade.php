@extends('layouts.app')

@section('title', 'Social Media Links - PikFreshFood')

@section('styles')
<style>
    .main-content { padding: 0 0 90px; }
    .profile-shell { max-width: 720px; margin: 0 auto; padding: 0 14px 24px; }
    .profile-topbar { height: 28px; background: var(--primary-color); margin: 0 -14px 12px; }
    .page-card, .social-list {
        background: #123f34;
        color: white;
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px;
        margin-bottom: 10px;
    }
    .page-card { padding: 14px; }
    .page-title { font-size: 1.35rem; font-weight: 800; margin-bottom: 6px; }
    .page-description { color: rgba(255,255,255,0.74); line-height: 1.45; font-size: 0.9rem; }
    .social-list { overflow: hidden; }
    .social-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px;
        color: white;
        text-decoration: none;
        border-top: 1px solid rgba(255,255,255,0.08);
    }
    .social-row:first-child { border-top: none; }
    .social-name { font-weight: 700; }
    .social-action {
        background: #0f352c;
        border: 1px solid rgba(255,255,255,0.12);
        color: white;
        border-radius: 999px;
        padding: 5px 12px;
        font-size: 0.72rem;
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<div class="profile-shell">
    <div class="profile-topbar"></div>

    <div class="page-card">
        <div class="page-title">Social Media Links</div>
        <div class="page-description">Choose a platform to connect and manage your social presence from your profile.</div>
    </div>

    <div class="social-list">
        <a href="{{ route('profile.social-channel', 'whatsapp') }}" class="social-row">
            <span class="social-name">WhatsApp</span>
            <span class="social-action">Connect</span>
        </a>
        <a href="{{ route('profile.social-channel', 'facebook') }}" class="social-row">
            <span class="social-name">Facebook</span>
            <span class="social-action">Connect</span>
        </a>
        <a href="{{ route('profile.social-channel', 'instagram') }}" class="social-row">
            <span class="social-name">Instagram</span>
            <span class="social-action">Connect</span>
        </a>
    </div>
</div>
@endsection
