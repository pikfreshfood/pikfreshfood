@extends('layouts.app')

@section('title', 'Caller - PikFreshFood')

@section('styles')
<style>
    .caller-page {
        max-width: 1100px;
        margin: 28px auto;
        padding: 0 16px 40px;
        display: grid;
        gap: 18px;
    }
    .caller-hero,
    .caller-panel {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 22px;
        padding: 22px;
        box-shadow: 0 10px 24px var(--shadow-color);
    }
    .caller-hero h1,
    .caller-panel h2 {
        margin: 0 0 8px;
        color: var(--text-color);
    }
    .caller-copy,
    .caller-meta,
    .caller-status-line {
        color: var(--muted-color);
        line-height: 1.6;
    }
    .caller-status-box {
        margin-top: 14px;
        padding: 14px 16px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        background: color-mix(in srgb, var(--primary-color) 8%, white 92%);
    }
    .caller-status-box.warning {
        background: #fff7ed;
        border-color: #fdba74;
        color: #9a3412;
    }
    .caller-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }
    .caller-list {
        display: grid;
        gap: 12px;
        margin-top: 14px;
    }
    .caller-card {
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 16px;
        background: color-mix(in srgb, var(--primary-color) 4%, var(--bottom-sheet-bg));
    }
    .caller-card-top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: start;
        margin-bottom: 10px;
    }
    .caller-card-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--text-color);
    }
    .caller-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 28px;
        padding: 0 10px;
        border-radius: 999px;
        background: color-mix(in srgb, var(--primary-color) 12%, white 88%);
        color: var(--primary-color);
        font-size: 0.82rem;
        font-weight: 800;
        white-space: nowrap;
    }
    .caller-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 12px;
    }
    .caller-btn {
        min-height: 42px;
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
        cursor: pointer;
    }
    .caller-btn.primary {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    @media (max-width: 860px) {
        .caller-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="caller-page">
    <div class="caller-hero">
        <h1>Caller</h1>
        <div class="caller-copy">
            This page is back on pure Laravel. The calling API settings now come from <code>.env</code>, so the Laravel project no longer depends on the React caller app.
        </div>

        @if($livekitReady)
            <div class="caller-status-box">
                <div class="caller-status-line"><strong>Call API ready.</strong></div>
                <div class="caller-status-line">Server: <code>{{ $livekitUrl }}</code></div>
                <div class="caller-status-line">API key: <code>{{ $livekitApiKey }}</code></div>
            </div>
        @else
            <div class="caller-status-box warning">
                <div class="caller-status-line"><strong>Call API is not fully configured yet.</strong></div>
                <div class="caller-status-line">Current server: <code>{{ $livekitUrl ?: 'missing' }}</code></div>
                <div class="caller-status-line">Current API key: <code>{{ $livekitApiKey ?: 'missing' }}</code></div>
                <div class="caller-status-line">The remaining missing value is <code>LIVEKIT_API_SECRET</code> in your <code>.env</code>.</div>
            </div>
        @endif
    </div>

    <div class="caller-grid">
        @if($viewer->isBuyer() || $viewer->isVendor())
            <div class="caller-panel">
                <h2>Start Calls</h2>
                <div class="caller-copy">Pick a vendor and start a fresh call from the Laravel side.</div>

                <div class="caller-list">
                    @forelse($vendors as $vendor)
                        <div class="caller-card">
                            <div class="caller-card-top">
                                <div>
                                    <div class="caller-card-title">{{ $vendor->shop_name }}</div>
                                    <div class="caller-meta">{{ $vendor->address ?: 'No address added yet' }}</div>
                                </div>
                                <span class="caller-badge">{{ $vendor->is_live ? 'Live' : 'Offline' }}</span>
                            </div>
                            <div class="caller-actions">
                                <button type="button" class="caller-btn primary js-start-call" data-url="{{ route('vendor.call.online', $vendor, false) }}" data-type="audio">Start Audio</button>
                                <button type="button" class="caller-btn js-start-call" data-url="{{ route('vendor.call.online', $vendor, false) }}" data-type="video">Start Video</button>
                            </div>
                        </div>
                    @empty
                        <div class="caller-card">
                            <div class="caller-copy">No vendors available for testing yet.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        @if($viewer->isVendor())
            <div class="caller-panel">
                <h2>Incoming Calls</h2>
                <div class="caller-copy">Accept fresh calls here after another account starts one.</div>

                <div class="caller-list">
                    @forelse($incomingCalls as $call)
                        <div class="caller-card">
                            <div class="caller-card-top">
                                <div>
                                    <div class="caller-card-title">{{ $call->buyer?->vendor?->shop_name ?? $call->buyer?->name ?? 'Caller' }}</div>
                                    <div class="caller-meta">Call #{{ $call->id }} | {{ ucfirst($call->call_type ?? 'audio') }} | {{ ucfirst($call->status) }}</div>
                                </div>
                                <span class="caller-badge">{{ ucfirst($call->status) }}</span>
                            </div>
                            <div class="caller-actions">
                                @if($call->status === 'ringing')
                                    <button type="button" class="caller-btn primary js-accept-call" data-url="{{ route('vendor.call.accept', $call, false) }}" data-call-type="{{ $call->call_type ?? 'audio' }}">Accept</button>
                                @endif
                                <a href="{{ route('calls.show', $call) }}" class="caller-btn js-open-call">Open Call</a>
                            </div>
                        </div>
                    @empty
                        <div class="caller-card">
                            <div class="caller-copy">No incoming calls right now.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        <div class="caller-panel">
            <h2>Recent Calls</h2>
            <div class="caller-copy">Use this list to reopen the same call row from the Laravel call screen.</div>

            <div class="caller-list">
                @forelse($recentCalls as $call)
                    <div class="caller-card">
                        <div class="caller-card-top">
                            <div>
                                <div class="caller-card-title">
                                    Call #{{ $call->id }}
                                    @if($call->buyer_id === $viewer->id)
                                        with {{ $call->vendor->shop_name ?? 'Vendor' }}
                                    @else
                                        with {{ $call->buyer?->vendor?->shop_name ?? $call->buyer?->name ?? 'Caller' }}
                                    @endif
                                </div>
                                <div class="caller-meta">{{ ucfirst($call->call_type ?? 'audio') }} | {{ ucfirst($call->status) }} | {{ $call->created_at?->diffForHumans() }}</div>
                            </div>
                            <span class="caller-badge">#{{ $call->id }}</span>
                        </div>
                        <div class="caller-actions">
                            <a href="{{ route('calls.show', $call) }}" class="caller-btn js-open-call">Open Call</a>
                        </div>
                    </div>
                @empty
                    <div class="caller-card">
                        <div class="caller-copy">No call invites recorded yet.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        document.querySelectorAll('.js-start-call').forEach(function (button) {
            button.addEventListener('click', async function () {
                try {
                    if (window.PikFreshCallLauncher) {
                        await window.PikFreshCallLauncher.prepareMedia({
                            type: button.dataset.type || 'audio',
                        });
                    }

                    const response = await fetch(button.dataset.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': @json(csrf_token()),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ type: button.dataset.type || 'audio' }),
                        credentials: 'same-origin',
                    });

                    const payload = window.PikFreshCallLauncher
                        ? await window.PikFreshCallLauncher.parseJsonResponse(response, 'Unable to start call.')
                        : await response.json().catch(function () { return {}; });

                    if (payload.call_url) {
                        if (window.PikFreshCallLauncher) {
                            window.PikFreshCallLauncher.open(payload.call_url, {
                                title: (button.dataset.type || 'audio') === 'video' ? 'Outgoing video call' : 'Outgoing audio call',
                            });
                        } else {
                            window.location.href = payload.call_url;
                        }
                    }
                } catch (error) {
                    window.alert(window.PikFreshCallLauncher
                        ? window.PikFreshCallLauncher.callErrorMessage(error, 'start the call')
                        : 'Could not start the call. Please try again.');
                }
            });
        });

        document.querySelectorAll('.js-accept-call').forEach(function (button) {
            button.addEventListener('click', async function () {
                try {
                    if (window.PikFreshCallLauncher) {
                        await window.PikFreshCallLauncher.prepareMedia({
                            type: button.dataset.callType || 'audio',
                        });
                    }

                    const response = await fetch(button.dataset.url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': @json(csrf_token()),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                    });

                    const payload = window.PikFreshCallLauncher
                        ? await window.PikFreshCallLauncher.parseJsonResponse(response, 'Unable to accept call.')
                        : await response.json().catch(function () { return {}; });

                    if (payload.call_url) {
                        if (window.PikFreshCallLauncher) {
                            window.PikFreshCallLauncher.open(payload.call_url, {
                                title: (button.dataset.callType || 'audio') === 'video' ? 'Accepted video call' : 'Accepted audio call',
                            });
                        } else {
                            window.location.href = payload.call_url;
                        }
                    }
                } catch (error) {
                    window.alert(window.PikFreshCallLauncher
                        ? window.PikFreshCallLauncher.callErrorMessage(error, 'open the call')
                        : 'Could not open the call. Please try again.');
                }
            });
        });

        document.querySelectorAll('.js-open-call').forEach(function (link) {
            link.addEventListener('click', async function (event) {
                if (!window.PikFreshCallLauncher) {
                    return;
                }

                event.preventDefault();
                const callCard = link.closest('.caller-card');
                const callTypeMatch = callCard ? callCard.textContent.match(/\b(audio|video)\b/i) : null;
                try {
                    await window.PikFreshCallLauncher.prepareMedia({
                        type: callTypeMatch ? callTypeMatch[1].toLowerCase() : 'audio',
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
