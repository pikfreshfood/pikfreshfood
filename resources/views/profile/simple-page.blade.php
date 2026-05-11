@extends('layouts.app')

@section('title', $title . ' - PikFreshFood')

@section('styles')
<style>
    .main-content { padding: 0 0 90px; }
    .profile-shell { max-width: 720px; margin: 0 auto; padding: 0 14px 24px; }
    .profile-topbar { height: 28px; background: var(--primary-color); margin: 0 -14px 12px; }
    .page-card, .detail-card, .action-card {
        background: #123f34;
        color: white;
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 10px;
    }
    .page-title { font-size: 1.35rem; font-weight: 800; margin-bottom: 6px; }
    .page-description { color: rgba(255,255,255,0.74); line-height: 1.45; font-size: 0.9rem; }
    .detail-card { padding: 0; overflow: hidden; }
    .detail-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 14px;
        border-top: 1px solid rgba(255,255,255,0.08);
    }
    .detail-row:first-child { border-top: none; }
    .detail-label { color: rgba(255,255,255,0.72); }
    .detail-value { color: white; font-weight: 700; text-align: right; }
    .page-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 46px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        border: 1px solid rgba(255,255,255,0.12);
    }
    .page-btn.primary { background: #2f8369; color: white; }
    .page-btn.secondary { background: #0f352c; color: white; }
</style>
@endsection

@section('content')
<div class="profile-shell">
    <div class="profile-topbar"></div>

    <div class="page-card">
        <div class="page-title">{{ $title }}</div>
        <div class="page-description">{{ $description }}</div>
    </div>

    @if(!empty($details))
        <div class="detail-card">
            @foreach($details as $detail)
                <div class="detail-row">
                    <div class="detail-label">{{ $detail['label'] }}</div>
                    <div class="detail-value">{{ $detail['value'] }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="action-card">
        <div class="page-actions">
            <a href="{{ $primaryRoute }}" class="page-btn primary">{{ $primaryLabel }}</a>
            <a href="{{ route('profile.edit') }}" class="page-btn secondary">Back To Profile</a>
        </div>
    </div>
</div>
@endsection
