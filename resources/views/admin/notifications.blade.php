@extends('admin.layouts.app')

@section('title', 'Admin Notifications - PikFreshFood')
@section('page_title', 'Push Notifications')
@section('page_copy', 'Send an occasional announcement to mobile users')

@section('styles')
.notification-panel { max-width: 720px; background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 18px; }
.notification-panel p { color: var(--muted); margin-bottom: 16px; }
.field { display: grid; gap: 7px; margin-bottom: 14px; }
.field label { font-weight: 800; font-size: 0.88rem; }
.field input, .field textarea { width: 100%; border: 1px solid var(--line); border-radius: 9px; padding: 11px 12px; font: inherit; color: var(--text); }
.field textarea { min-height: 120px; resize: vertical; }
.send-button { border: 0; border-radius: 9px; padding: 11px 16px; background: #168447; color: #fff; font-weight: 800; cursor: pointer; }
.device-count { display: inline-block; margin-bottom: 16px; padding: 7px 10px; border-radius: 999px; background: #e9f7ef; color: #126438; font-weight: 800; font-size: 0.85rem; }
@endsection

@section('content')
<article class="notification-panel">
    <span class="device-count">{{ $deviceCount }} registered mobile device(s)</span>
    <p>Messages are delivered to users who have opened the mobile app and granted notification permission.</p>
    <form method="POST" action="{{ route('admin.notifications.store') }}">
        @csrf
        <div class="field">
            <label for="title">Title</label>
            <input id="title" name="title" value="{{ old('title') }}" maxlength="100" required>
        </div>
        <div class="field">
            <label for="body">Message</label>
            <textarea id="body" name="body" maxlength="500" required>{{ old('body') }}</textarea>
        </div>
        <div class="field">
            <label for="url">Open link (optional)</label>
            <input id="url" name="url" type="url" value="{{ old('url') }}" placeholder="https://pikfreshfood.com/">
        </div>
        <button class="send-button" type="submit">Send to mobile users</button>
    </form>
</article>
@endsection