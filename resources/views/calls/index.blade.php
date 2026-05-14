@extends('layouts.app')

@section('title', 'Call History - PikFreshFood')

@section('styles')
<style>
    .calls-history-page {
        max-width: 960px;
        margin: 28px auto;
        padding: 0 16px 40px;
    }
    .calls-history-hero,
    .calls-history-card {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 22px;
        box-shadow: 0 8px 24px var(--shadow-color);
    }
    .calls-history-hero {
        padding: 24px;
        margin-bottom: 18px;
    }
    .calls-history-hero h1 {
        margin: 0 0 8px;
        font-size: 1.7rem;
        color: var(--text-color);
    }
    .calls-history-hero p {
        margin: 0;
        color: var(--muted-color);
        line-height: 1.6;
    }
    .calls-history-tabs {
        display: inline-flex;
        gap: 10px;
        margin-top: 18px;
        flex-wrap: wrap;
    }
    .calls-history-tab {
        min-height: 42px;
        padding: 0 16px;
        border-radius: 999px;
        border: 1px solid var(--border-color);
        color: var(--text-color);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        background: white;
    }
    .calls-history-tab.active {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    .calls-history-card {
        padding: 18px;
    }
    .call-history-row {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 14px;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid var(--border-color);
    }
    .call-history-row:last-child {
        border-bottom: none;
        padding-bottom: 4px;
    }
    .call-history-avatar {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: color-mix(in srgb, var(--primary-color) 14%, white 86%);
        color: var(--primary-color);
        font-weight: 900;
        font-size: 1.05rem;
    }
    .call-history-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .call-history-name {
        font-size: 1rem;
        font-weight: 800;
        color: var(--text-color);
        margin-bottom: 4px;
    }
    .call-history-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        color: var(--muted-color);
        font-size: 0.9rem;
    }
    .call-history-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 28px;
        padding: 0 10px;
        border-radius: 999px;
        background: color-mix(in srgb, var(--primary-color) 10%, white 90%);
        color: var(--primary-color);
        font-weight: 700;
        font-size: 0.82rem;
    }
    .call-history-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    .call-history-btn {
        min-height: 40px;
        padding: 0 14px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: white;
        color: var(--text-color);
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
    }
    .call-history-btn.primary {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    .calls-empty {
        text-align: center;
        color: var(--muted-color);
        padding: 40px 16px;
    }
    .calls-empty strong {
        display: block;
        margin-bottom: 8px;
        color: var(--text-color);
        font-size: 1.02rem;
    }
    @media (max-width: 720px) {
        .call-history-row {
            grid-template-columns: auto 1fr;
        }
        .call-history-actions {
            grid-column: 1 / -1;
            justify-content: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="calls-history-page">
    <div class="calls-history-hero">
        <h1>Call History</h1>
        <p>Review recent calls, reopen active ones, and quickly reach back out from one place.</p>

        <div class="calls-history-tabs">
            <a href="{{ route('calls.index', ['mode' => 'audio']) }}" class="calls-history-tab {{ $mode === 'audio' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                    <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.5 3a2 2 0 0 1-.6 1.8l-1.3 1.3a16 16 0 0 0 6.4 6.4l1.3-1.3a2 2 0 0 1 1.8-.6l3 .5A2 2 0 0 1 22 16.9Z" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                Audio Calls
            </a>
            <a href="{{ route('calls.index', ['mode' => 'video']) }}" class="calls-history-tab {{ $mode === 'video' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                    <path d="m22 8-6 4 6 4V8Z" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"></path>
                    <rect x="2" y="6" width="14" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="1.9"></rect>
                </svg>
                Video Calls
            </a>
        </div>
    </div>

    <div class="calls-history-card">
        @if($calls->isEmpty())
            <div class="calls-empty">
                <strong>No {{ $mode }} calls yet</strong>
                Your recent buyer and vendor call activity will show up here.
            </div>
        @else
            @foreach($calls as $call)
                @php
                    $viewer = auth()->user();
                    $viewerIsCaller = $call->buyer_id === $viewer->id;
                    $otherName = $viewerIsCaller
                        ? ($call->vendor->shop_name ?? 'Vendor')
                        : ($call->buyer?->vendor?->shop_name ?? $call->buyer?->name ?? 'Caller');
                    $otherImage = $viewerIsCaller
                        ? ($call->vendor->profile_image ? \App\Support\PublicStorage::url($call->vendor->profile_image) : null)
                        : ($call->buyer?->vendor?->profile_image ? \App\Support\PublicStorage::url($call->buyer->vendor->profile_image) : null);
                    $statusLabel = match ($call->status) {
                        'ringing' => 'Ringing',
                        'accepted' => 'Accepted',
                        'connected' => 'Connected',
                        'ended' => 'Ended',
                        default => ucfirst((string) $call->status),
                    };
                @endphp
                <div class="call-history-row">
                    <span class="call-history-avatar" aria-hidden="true">
                        @if($otherImage)
                            <img src="{{ $otherImage }}" alt="{{ $otherName }}">
                        @else
                            {{ strtoupper(substr($otherName, 0, 1)) }}
                        @endif
                    </span>

                    <div>
                        <div class="call-history-name">{{ $otherName }}</div>
                        <div class="call-history-meta">
                            <span class="call-history-badge">{{ $statusLabel }}</span>
                            <span>{{ $call->created_at?->diffForHumans() }}</span>
                            <span>Browser {{ $call->call_type ?? 'audio' }} call</span>
                        </div>
                    </div>

                    <div class="call-history-actions">
                        @if(in_array($call->status, ['ringing', 'accepted', 'connected'], true))
                            <a href="{{ route('calls.show', $call) }}" class="call-history-btn js-open-call" data-call-type="{{ $call->call_type ?? 'audio' }}">Open Call</a>
                        @endif

                        @if($viewerIsCaller && $call->vendor)
                            <button
                                type="button"
                                class="call-history-btn primary js-call-back"
                                data-call-url="{{ route('vendor.call.online', $call->vendor, false) }}"
                                data-call-type="{{ $call->call_type ?? 'audio' }}"
                                data-call-label="{{ ucfirst($call->call_type ?? 'audio') }} Call Back"
                            >
                                {{ ucfirst($call->call_type ?? 'audio') }} Call Back
                            </button>
                        @elseif($call->buyer?->vendor && $call->buyer_id !== $viewer->id)
                            <button
                                type="button"
                                class="call-history-btn primary js-call-back"
                                data-call-url="{{ route('vendor.call.online', $call->buyer->vendor, false) }}"
                                data-call-type="{{ $call->call_type ?? 'audio' }}"
                                data-call-label="{{ ucfirst($call->call_type ?? 'audio') }} Call Back"
                            >
                                {{ ucfirst($call->call_type ?? 'audio') }} Call Back
                            </button>
                        @elseif($call->buyer)
                            <a href="{{ route('messages.show', $call->buyer) }}" class="call-history-btn primary">Message Caller</a>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const callBackButtons = document.querySelectorAll('.js-call-back');

        callBackButtons.forEach(function (button) {
            button.addEventListener('click', async function () {
                const callUrl = button.dataset.callUrl;
                const callType = button.dataset.callType || 'audio';
                const defaultLabel = button.dataset.callLabel || 'Call Back';

                if (!callUrl) {
                    return;
                }

                button.disabled = true;
                button.textContent = 'Starting...';

                try {
                    if (window.PikFreshCallLauncher) {
                        await window.PikFreshCallLauncher.prepareMedia({
                            type: callType,
                        });
                    }

                    fetch(callUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            type: callType,
                        }),
                        credentials: 'same-origin',
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
                                        title: callType === 'video' ? 'Video call back' : 'Audio call back',
                                    });
                                } else {
                                    window.location.href = payload.call_url;
                                }
                            }
                        })
                        .catch(function () {
                            button.disabled = false;
                            button.textContent = defaultLabel;
                        });
                } catch (error) {
                    button.disabled = false;
                    button.textContent = defaultLabel;
                    window.alert(window.PikFreshCallLauncher
                        ? window.PikFreshCallLauncher.callErrorMessage(error, 'start the call')
                        : 'Could not start the call. Please try again.');
                }
            });
        });

        document.querySelectorAll('.js-open-call').forEach(function (link) {
            link.addEventListener('click', async function (event) {
                if (!window.PikFreshCallLauncher) {
                    return;
                }

                event.preventDefault();
                try {
                    await window.PikFreshCallLauncher.prepareMedia({
                        type: link.dataset.callType || 'audio',
                    });
                    window.PikFreshCallLauncher.open(link.href, {
                        title: 'Active call',
                    });
                } catch (error) {
                    window.alert(window.PikFreshCallLauncher
                        ? window.PikFreshCallLauncher.callErrorMessage(error, 'open the call')
                        : 'Could not open the call. Please try again.');
                }
            });
        });
    })();
</script>
@endsection
