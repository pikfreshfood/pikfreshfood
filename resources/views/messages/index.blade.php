@extends('layouts.app')

@section('title', 'Messages - PikFreshFood')

@section('styles')
<style>
    .messages-shell {
        max-width: 1120px;
        margin: 28px auto;
        padding: 0 16px;
        display: grid;
        grid-template-columns: 1fr;
        gap: 18px;
    }
    .messages-card {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        box-shadow: 0 2px 10px var(--shadow-color);
    }
    .messages-sidebar {
        padding: 16px;
        max-height: calc(100vh - 170px);
        overflow-y: auto;
    }
    .messages-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text-color);
        margin-bottom: 14px;
    }
    .thread-link {
        display: block;
        padding: 14px;
        border-radius: 14px;
        text-decoration: none;
        color: inherit;
        border: 1px solid transparent;
        margin-bottom: 10px;
        background: color-mix(in srgb, var(--primary-color) 5%, white 95%);
    }
    .thread-link.active {
        border-color: color-mix(in srgb, var(--primary-color) 45%, white 55%);
        background: color-mix(in srgb, var(--primary-color) 12%, white 88%);
    }
    .thread-row {
        display: grid;
        grid-template-columns: 48px minmax(0, 1fr);
        gap: 12px;
        align-items: start;
    }
    .thread-main {
        min-width: 0;
    }
    .chat-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        background: color-mix(in srgb, var(--primary-color) 16%, white 84%);
        color: var(--primary-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .chat-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .thread-top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 6px;
    }
    .thread-name {
        font-weight: 800;
        color: var(--text-color);
    }
    .thread-time {
        font-size: 0.75rem;
        color: var(--muted-color);
        white-space: nowrap;
    }
    .thread-preview {
        color: var(--muted-color);
        font-size: 0.92rem;
        line-height: 1.5;
        word-break: break-word;
    }
    .thread-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 22px;
        height: 22px;
        border-radius: 999px;
        background: #e74c3c;
        color: white;
        font-size: 0.72rem;
        font-weight: 800;
        margin-top: 8px;
    }
    .conversation-card {
        padding: 18px;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 170px);
        min-height: 620px;
    }
    .conversation-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding-bottom: 14px;
        margin-bottom: 16px;
        border-bottom: 1px solid var(--border-color);
    }
    .conversation-user {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }
    .conversation-user-meta h2 {
        color: var(--text-color);
        margin-bottom: 4px;
        font-size: 1.1rem;
    }
    .conversation-user-meta p {
        color: var(--muted-color);
    }
    .conversation-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .icon-button {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 1px solid var(--border-color);
        background: white;
        color: var(--text-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        cursor: pointer;
        flex-shrink: 0;
    }
    .icon-button svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.9;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .icon-button.is-disabled {
        opacity: 0.45;
        cursor: not-allowed;
        pointer-events: none;
    }
    .message-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex: 1;
        overflow-y: auto;
        padding-right: 4px;
    }
    .message-row {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }
    .message-row.sent {
        justify-content: flex-end;
    }
    .message-row.received {
        justify-content: flex-start;
    }
    .message-row.sent .chat-avatar {
        order: 2;
    }
    .message-bubble {
        max-width: 78%;
        padding: 12px 14px;
        border-radius: 18px;
        position: relative;
    }
    .message-bubble.sent {
        background: color-mix(in srgb, var(--primary-color) 14%, white 86%);
        border: 1px solid color-mix(in srgb, var(--primary-color) 30%, white 70%);
        color: #173b2a !important;
    }
    .message-bubble.received {
        background: #f3f5f7;
        border: 1px solid #dde3e8;
        color: #24303a !important;
    }
    .message-image {
        width: min(280px, 100%);
        max-height: 280px;
        object-fit: cover;
        border-radius: 14px;
        display: block;
        margin-bottom: 8px;
    }
    .message-image-wrap {
        position: relative;
        display: inline-block;
        max-width: 100%;
        margin-bottom: 8px;
    }
    .message-image-delete {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: rgba(0, 0, 0, 0.72);
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .message-image-delete svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .message-body {
        line-height: 1.6;
        color: inherit;
        white-space: pre-wrap;
        word-break: break-word;
    }
    .message-time {
        display: block;
        margin-top: 6px;
        font-size: 0.75rem;
        color: var(--muted-color) !important;
    }
    .message-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid var(--border-color);
    }
    .attachment-preview {
        display: none;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 12px;
        background: color-mix(in srgb, var(--primary-color) 8%, white 92%);
        color: var(--text-color);
        font-size: 0.9rem;
    }
    .attachment-preview.is-visible {
        display: flex;
    }
    .attachment-thumb {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        object-fit: cover;
        background: #eef3ef;
        flex-shrink: 0;
    }
    .attachment-meta {
        min-width: 0;
        flex: 1;
    }
    .attachment-name {
        font-weight: 700;
        color: var(--text-color);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .attachment-status {
        color: var(--muted-color);
        font-size: 0.78rem;
        margin-top: 4px;
    }
    .attachment-remove {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1px solid var(--border-color);
        background: white;
        color: #c0392b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
    }
    .attachment-remove svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .attachment-send {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: var(--primary-color);
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
    }
    .attachment-send svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .attachment-loader {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid rgba(39, 174, 96, 0.2);
        border-top-color: var(--primary-color);
        animation: attachment-spin 0.8s linear infinite;
        flex-shrink: 0;
        display: none;
    }
    .attachment-preview.is-uploading .attachment-loader {
        display: inline-block;
    }
    .attachment-preview.is-uploading .attachment-send {
        display: none;
    }
    .composer-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto auto;
        gap: 10px;
        align-items: end;
    }
    .message-textarea {
        min-height: 56px;
        max-height: 140px;
        padding: 12px 14px;
        border-radius: 14px;
        border: 1px solid #cfd8d3;
        background: #ffffff;
        color: #173b2a !important;
        resize: vertical;
        box-shadow: inset 0 0 0 1px rgba(23, 59, 42, 0.02);
    }
    .message-textarea::placeholder {
        color: #6a7b72 !important;
    }
    .message-textarea:focus {
        outline: 2px solid rgba(39, 174, 96, 0.22);
        outline-offset: 0;
        border-color: var(--primary-color);
    }
    .message-button {
        min-width: 110px;
        min-height: 48px;
        border: none;
        border-radius: 14px;
        font-weight: 800;
        cursor: pointer;
    }
    .message-button.is-sending {
        opacity: 0.7;
        cursor: wait;
    }
    .empty-messages {
        text-align: center;
        color: var(--muted-color);
        padding: 40px 20px;
    }
    @keyframes attachment-spin {
        to { transform: rotate(360deg); }
    }
    @media (max-width: 860px) {
        .messages-shell {
            grid-template-columns: 1fr;
        }
        .conversation-card,
        .messages-sidebar {
            max-height: none;
            height: auto;
        }
        .composer-row { grid-template-columns: minmax(0, 1fr) auto auto; }
        .message-button {
            grid-column: 1 / -1;
        }
        .message-bubble {
            max-width: 100%;
        }
    }

    body.safplace-theme .message-bubble.sent {
        background: rgba(255, 255, 255, 0.22);
        border-color: rgba(255, 255, 255, 0.2);
        color: #ffffff !important;
    }

    body.safplace-theme .message-bubble.received {
        background: rgba(255, 255, 255, 0.92);
        border-color: rgba(255, 255, 255, 0.4);
        color: #173b2a !important;
    }

    body.safplace-theme .message-time {
        color: rgba(255, 255, 255, 0.72) !important;
    }

    body.safplace-theme .message-bubble.received .message-time {
        color: rgba(23, 59, 42, 0.62) !important;
    }

    body.safplace-theme .message-textarea {
        background: #fffdf7 !important;
        color: #173b2a !important;
        border-color: #d4c59a !important;
        box-shadow: inset 0 0 0 1px rgba(212, 197, 154, 0.18);
    }

    body.safplace-theme .message-textarea::placeholder {
        color: #7b7258 !important;
    }
</style>
@endsection

@section('content')
<div class="messages-shell">
    @if(!$selectedUser)
        <div class="messages-card messages-sidebar" style="max-height: none; height: auto;">
            <div class="messages-title" style="margin-bottom: 20px;">Your Conversations</div>
            <div id="threadList">
                @if($conversations->isEmpty())
                    <div class="empty-messages">
                        <p>No conversations yet.</p>
                        <p style="margin-top: 10px;">Your chats with buyers and vendors will appear here.</p>
                    </div>
                @else
                    @foreach($conversations as $thread)
                        @php
                            $threadUser = $thread['user'];
                            $threadAvatar = $threadUser->vendor?->profile_image
                                ? \App\Support\PublicStorage::url($threadUser->vendor->profile_image)
                                : null;
                        @endphp
                        <a href="{{ route('messages.show', $threadUser->id) }}" class="thread-link">
                            <div class="thread-row">
                                <span class="chat-avatar" aria-hidden="true">
                                    @if($threadAvatar)
                                        <img src="{{ $threadAvatar }}" alt="{{ $threadUser->name }}">
                                    @else
                                        {{ strtoupper(substr($threadUser->name, 0, 1)) }}
                                    @endif
                                </span>
                                <div class="thread-main">
                                    <div class="thread-top">
                                        <span class="thread-name">{{ $threadUser->name }}</span>
                                        <span class="thread-time">{{ $thread['latest']->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="thread-preview">{{ \Illuminate\Support\Str::limit($thread['preview'], 70) }}</div>
                                    @if($thread['unread_count'] > 0)
                                        <span class="thread-badge">{{ $thread['unread_count'] > 9 ? '9+' : $thread['unread_count'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    @else
        <div class="messages-card conversation-card">
            @php
                $selectedAvatar = $selectedUser->vendor?->profile_image
                    ? \App\Support\PublicStorage::url($selectedUser->vendor->profile_image)
                    : null;
                $selectedPhone = $selectedUser->vendor?->phone ?: $selectedUser->phone;
                $selectedVideo = $selectedUser->vendor?->promo_video_url;
                $currentUserAvatar = auth()->user()->vendor?->profile_image
                    ? \App\Support\PublicStorage::url(auth()->user()->vendor->profile_image)
                    : null;
            @endphp

            <div class="conversation-header">
                <div class="conversation-user">
                    <a href="{{ route('messages.index') }}" class="icon-button" style="margin-right: 8px; border: none; background: transparent;" aria-label="Back to messages">
                        <svg viewBox="0 0 24 24" style="width: 24px; height: 24px;">
                            <path d="M19 12H5M12 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <span class="chat-avatar" aria-hidden="true">
                        @if($selectedAvatar)
                            <img src="{{ $selectedAvatar }}" alt="{{ $selectedUser->name }}">
                        @else
                            {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                        @endif
                    </span>
                    <div class="conversation-user-meta">
                        <h2>{{ $selectedUser->name }}</h2>
                        <p>{{ $selectedUser->vendor ? 'Vendor account' : 'Buyer account' }}</p>
                    </div>
                </div>

                <div class="conversation-actions">
                    @if($selectedUser->vendor && auth()->id() !== $selectedUser->vendor->user_id)
                        <button
                            type="button"
                            class="icon-button js-message-call"
                            data-call-url="{{ route('vendor.call.online', $selectedUser->vendor) }}"
                            data-call-type="audio"
                            data-peer-name="{{ addslashes($selectedUser->name) }}"
                            aria-label="Audio Call"
                        >
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.5 3a2 2 0 0 1-.6 1.8l-1.3 1.3a16 16 0 0 0 6.4 6.4l1.3-1.3a2 2 0 0 1 1.8-.6l3 .5A2 2 0 0 1 22 16.9Z"></path>
                            </svg>
                        </button>
                        <button
                            type="button"
                            class="icon-button js-message-call"
                            data-call-url="{{ route('vendor.call.online', $selectedUser->vendor) }}"
                            data-call-type="video"
                            data-peer-name="{{ addslashes($selectedUser->name) }}"
                            aria-label="Video Call"
                        >
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <rect x="3" y="6" width="13" height="12" rx="2"></rect>
                                <path d="m16 10 5-3v10l-5-3"></path>
                            </svg>
                        </button>
                    @elseif(auth()->id() !== $selectedUser->id)
                        <a href="{{ $selectedPhone ? 'tel:' . preg_replace('/\s+/', '', $selectedPhone) : '#' }}" class="icon-button {{ $selectedPhone ? '' : 'is-disabled' }}" aria-label="Call">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.5 3a2 2 0 0 1-.6 1.8l-1.3 1.3a16 16 0 0 0 6.4 6.4l1.3-1.3a2 2 0 0 1 1.8-.6l3 .5A2 2 0 0 1 22 16.9Z"></path>
                            </svg>
                        </a>
                        <a href="{{ $selectedVideo ?: '#' }}" target="_blank" rel="noopener" class="icon-button {{ $selectedVideo ? '' : 'is-disabled' }}" aria-label="Video">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <rect x="3" y="6" width="13" height="12" rx="2"></rect>
                                <path d="m16 10 5-3v10l-5-3"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <div
                class="message-list"
                id="messageList"
                data-poll-url="{{ route('messages.show', $selectedUser->id) }}"
                data-selected-user-id="{{ $selectedUser->id }}"
                data-thread-signature="{{ optional($selectedMessages->last())->id ?? 0 }}:{{ $selectedMessages->count() }}"
            >
                @forelse($selectedMessages as $message)
                    @php
                        $isSent = $message->sender_id == auth()->id();
                        $avatarSrc = $isSent ? $currentUserAvatar : $selectedAvatar;
                        $avatarName = $isSent ? auth()->user()->name : $selectedUser->name;
                    @endphp
                    <div class="message-row {{ $isSent ? 'sent' : 'received' }}">
                        <span class="chat-avatar" aria-hidden="true">
                            @if($avatarSrc)
                                <img src="{{ $avatarSrc }}" alt="{{ $avatarName }}">
                            @else
                                {{ strtoupper(substr($avatarName, 0, 1)) }}
                            @endif
                        </span>
                        <div class="message-bubble {{ $isSent ? 'sent' : 'received' }}">
                            @if($message->image)
                                <div class="message-image-wrap" data-message-id="{{ $message->id }}">
                                    <img src="{{ \App\Support\PublicStorage::url($message->image) }}" alt="Shared attachment" class="message-image">
                                    @if($isSent)
                                        <button type="button" class="message-image-delete" data-delete-image="{{ $message->id }}" aria-label="Delete sent image">
                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M18 6 6 18"></path>
                                                <path d="m6 6 12 12"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endif
                            @if($message->message)
                                <div class="message-body">{{ $message->message }}</div>
                            @endif
                            <small class="message-time">{{ $message->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @empty
                    <div class="empty-messages" style="padding: 16px 0;">
                        No messages yet. Start the conversation below.
                    </div>
                @endforelse
            </div>

            <form action="{{ route('messages.store', [], false) }}" method="POST" class="message-form" enctype="multipart/form-data" id="messageForm">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}">
                <input type="file" name="image" id="messageImageInput" accept="image/*" hidden>

                <div class="attachment-preview" id="attachmentPreview">
                    <img src="" alt="Attachment preview" class="attachment-thumb" id="attachmentThumb">
                    <div class="attachment-meta">
                        <div class="attachment-name" id="attachmentName">No file selected</div>
                        <div class="attachment-status" id="attachmentStatus">Ready to send</div>
                    </div>
                    <span class="attachment-loader" id="attachmentLoader" aria-hidden="true"></span>
                    <button type="button" class="attachment-send" id="attachmentSend" aria-label="Send attachment">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M22 2 11 13"></path>
                            <path d="m22 2-7 20-4-9-9-4Z"></path>
                        </svg>
                    </button>
                    <button type="button" class="attachment-remove" id="attachmentRemove" aria-label="Remove attachment">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="composer-row">
                    <textarea name="message" placeholder="Type your message..." class="message-textarea" id="messageTextarea">{{ old('message') }}</textarea>
                    <button type="button" class="icon-button" id="attachmentButton" aria-label="Attach image">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M21.4 11.1 12 20.5a6 6 0 0 1-8.5-8.5l9.2-9.2a4 4 0 1 1 5.7 5.7l-9.2 9.2a2 2 0 1 1-2.8-2.8l8.5-8.5"></path>
                        </svg>
                    </button>
                    <button type="submit" class="message-button" id="messageSubmit">Send</button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const messageList = document.getElementById('messageList');
        const messageForm = document.getElementById('messageForm');
        const attachmentButton = document.getElementById('attachmentButton');
        const messageImageInput = document.getElementById('messageImageInput');
        const attachmentPreview = document.getElementById('attachmentPreview');
        const attachmentThumb = document.getElementById('attachmentThumb');
        const attachmentName = document.getElementById('attachmentName');
        const attachmentStatus = document.getElementById('attachmentStatus');
        const attachmentRemove = document.getElementById('attachmentRemove');
        const attachmentSend = document.getElementById('attachmentSend');
        const messageTextarea = document.getElementById('messageTextarea');
        const messageSubmit = document.getElementById('messageSubmit');
        const selectedUserInput = messageForm?.querySelector('input[name="receiver_id"]');
        const pollUrl = messageList?.dataset.pollUrl || window.location.href;
        let currentSelectedUserId = selectedUserInput?.value || messageList?.dataset.selectedUserId || '';

        let shouldAutoScroll = true;
        let currentSignature = messageList ? messageList.dataset.threadSignature : '';
        let pollInFlight = false;
        let isSendingMessage = false;
        let lastSendTimestamp = 0;

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function scrollToBottom(force = false) {
            if (messageList) {
                requestAnimationFrame(function () {
                    if (force || shouldAutoScroll) {
                        messageList.scrollTop = messageList.scrollHeight;
                    }
                });
            }
        }

        function updateAutoScrollState() {
            if (!messageList) {
                return;
            }

            const threshold = 48;
            const distanceFromBottom = messageList.scrollHeight - messageList.scrollTop - messageList.clientHeight;
            shouldAutoScroll = distanceFromBottom <= threshold;
        }

        function resetAttachment() {
            if (attachmentThumb && attachmentThumb.src.startsWith('blob:')) {
                URL.revokeObjectURL(attachmentThumb.src);
            }

            if (messageImageInput) {
                messageImageInput.value = '';
            }

            attachmentPreview?.classList.remove('is-visible', 'is-uploading');

            if (attachmentThumb) {
                attachmentThumb.src = '';
            }

            if (attachmentName) {
                attachmentName.textContent = 'No file selected';
            }

            if (attachmentStatus) {
                attachmentStatus.textContent = 'Ready to send';
            }
        }

        function appendMessage(chatMessage) {
            if (!messageList) {
                return;
            }

            const row = document.createElement('div');
            row.className = `message-row ${chatMessage.is_sent ? 'sent' : 'received'}`;

            const avatar = document.createElement('span');
            avatar.className = 'chat-avatar';
            avatar.setAttribute('aria-hidden', 'true');

            if (chatMessage.author_avatar) {
                const img = document.createElement('img');
                img.src = chatMessage.author_avatar;
                img.alt = chatMessage.author_name;
                avatar.appendChild(img);
            } else {
                avatar.textContent = chatMessage.author_name.charAt(0).toUpperCase();
            }

            const bubble = document.createElement('div');
            bubble.className = `message-bubble ${chatMessage.is_sent ? 'sent' : 'received'}`;

            if (chatMessage.image_url) {
                const imageWrap = document.createElement('div');
                imageWrap.className = 'message-image-wrap';
                imageWrap.dataset.messageId = chatMessage.id;

                const image = document.createElement('img');
                image.src = chatMessage.image_url;
                image.alt = 'Shared attachment';
                image.className = 'message-image';
                imageWrap.appendChild(image);

                if (chatMessage.can_delete_image) {
                    const deleteButton = document.createElement('button');
                    deleteButton.type = 'button';
                    deleteButton.className = 'message-image-delete';
                    deleteButton.setAttribute('data-delete-image', chatMessage.id);
                    deleteButton.setAttribute('aria-label', 'Delete sent image');
                    deleteButton.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>';
                    imageWrap.appendChild(deleteButton);
                }

                bubble.appendChild(imageWrap);
            }

            if (chatMessage.message) {
                const body = document.createElement('div');
                body.className = 'message-body';
                body.textContent = chatMessage.message;
                bubble.appendChild(body);
            }

            const time = document.createElement('small');
            time.className = 'message-time';
            time.textContent = chatMessage.time;
            bubble.appendChild(time);

            row.appendChild(avatar);
            row.appendChild(bubble);
            messageList.appendChild(row);
            scrollToBottom(true);
        }

        function renderMessages(messages, signature, isPoll = false) {
            if (!messageList) {
                return;
            }

            // If this is a poll and the messages are empty but we currently have messages,
            // ignore it to prevent accidental clearing of the chat due to stale data/lag.
            if (isPoll && (!messages || !messages.length) && messageList.querySelectorAll('.message-row').length > 0) {
                return;
            }

            // If we are polling and the message count is LESS than what we already have,
            // ignore it (stale data from server cache/lag).
            if (isPoll && Array.isArray(messages) && messages.length < messageList.querySelectorAll('.message-row').length) {
                return;
            }

            if (!messages || !messages.length) {
                messageList.innerHTML = '<div class="empty-messages" style="padding: 16px 0;">No messages yet. Start the conversation below.</div>';
            } else {
                // Build all HTML at once to prevent flickering from repeated DOM updates
                const html = messages.map(function (chatMessage) {
                    const avatarContent = chatMessage.author_avatar
                        ? '<img src="' + escapeHtml(chatMessage.author_avatar) + '" alt="' + escapeHtml(chatMessage.author_name) + '">'
                        : escapeHtml(String(chatMessage.author_name).charAt(0).toUpperCase());

                    const imageMarkup = chatMessage.image_url
                        ? '<div class="message-image-wrap" data-message-id="' + chatMessage.id + '">' +
                          '<img src="' + escapeHtml(chatMessage.image_url) + '" alt="Shared attachment" class="message-image">' +
                          (chatMessage.can_delete_image
                              ? '<button type="button" class="message-image-delete" data-delete-image="' + chatMessage.id + '" aria-label="Delete sent image">' +
                                '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg></button>'
                              : '') +
                          '</div>'
                        : '';

                    const bodyMarkup = chatMessage.message
                        ? '<div class="message-body">' + escapeHtml(chatMessage.message) + '</div>'
                        : '';

                    return '<div class="message-row ' + (chatMessage.is_sent ? 'sent' : 'received') + '">' +
                        '<span class="chat-avatar" aria-hidden="true">' + avatarContent + '</span>' +
                        '<div class="message-bubble ' + (chatMessage.is_sent ? 'sent' : 'received') + '">' +
                        imageMarkup + bodyMarkup +
                        '<small class="message-time">' + escapeHtml(chatMessage.time) + '</small>' +
                        '</div>' +
                        '</div>';
                }).join('');

                messageList.innerHTML = html;
            }

            if (signature) {
                currentSignature = signature;
                messageList.dataset.threadSignature = signature;
            }

            scrollToBottom(true);
            bindImageDeleteButtons();
            bindImageLoadScroll();
        }

        async function deleteSentImage(messageId, trigger) {
            try {
                const response = await fetch(`/messages/${messageId}/image`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                });

                if (!response.ok) {
                    throw new Error('Unable to delete image.');
                }

                const data = await response.json();
                const wrapper = trigger.closest('.message-image-wrap');

                wrapper?.remove();

                if (!data.chat_message?.message) {
                    trigger.closest('.message-row')?.remove();
                }
            } catch (error) {
                // Ignore delete failure for now; user can retry.
            }
        }

        function bindImageDeleteButtons() {
            document.querySelectorAll('[data-delete-image]').forEach(function (button) {
                if (button.dataset.boundDelete === 'true') {
                    return;
                }

                button.dataset.boundDelete = 'true';
                button.addEventListener('click', function () {
                    deleteSentImage(button.getAttribute('data-delete-image'), button);
                });
            });
        }

        function bindImageLoadScroll() {
            document.querySelectorAll('.message-image').forEach(function (image) {
                if (image.dataset.boundLoad === 'true') {
                    return;
                }

                image.dataset.boundLoad = 'true';

                if (image.complete) {
                    scrollToBottom(true);
                    return;
                }

                image.addEventListener('load', function () {
                    scrollToBottom(true);
                });
            });
        }

        if (attachmentButton && messageImageInput) {
            attachmentButton.addEventListener('click', function () {
                messageImageInput.click();
            });

            messageImageInput.addEventListener('change', function () {
                const file = messageImageInput.files && messageImageInput.files[0];

                if (!file) {
                    resetAttachment();
                    return;
                }

                if (attachmentThumb) {
                    attachmentThumb.src = URL.createObjectURL(file);
                }

                if (attachmentName) {
                    attachmentName.textContent = file.name;
                }

                if (attachmentStatus) {
                    attachmentStatus.textContent = 'Ready to send';
                }

                attachmentPreview?.classList.add('is-visible');
            });
        }

        attachmentRemove?.addEventListener('click', function () {
            resetAttachment();
        });

        async function submitMessage() {
            if (!messageSubmit || !messageTextarea) {
                return false;
            }

            const hasText = messageTextarea.value.trim().length > 0;
            const hasImage = messageImageInput?.files && messageImageInput.files.length > 0;

            if (!hasText && !hasImage) {
                return false;
            }

            const formData = new FormData(messageForm);

            isSendingMessage = true;
            lastSendTimestamp = Date.now();
            messageSubmit.disabled = true;
            messageSubmit.classList.add('is-sending');
            messageSubmit.textContent = 'Sending...';

            if (hasImage) {
                attachmentPreview?.classList.add('is-uploading');
                if (attachmentStatus) {
                    attachmentStatus.textContent = 'Uploading image...';
                }
            }

            try {
                const response = await fetch(messageForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'ngrok-skip-browser-warning': 'true'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error('Unable to send message.');
                }

                const data = await response.json();

                if (Array.isArray(data.messages) && data.thread_signature) {
                    renderMessages(data.messages, data.thread_signature);
                } else {
                    appendMessage(data.chat_message);
                    if (data.thread_signature) {
                        currentSignature = data.thread_signature;
                        messageList.dataset.threadSignature = data.thread_signature;
                    }
                }

                messageTextarea.value = '';
                resetAttachment();
                return true;
            } catch (error) {
                if (attachmentStatus && hasImage) {
                    attachmentStatus.textContent = 'Upload failed. Try again.';
                }
                return false;
            } finally {
                isSendingMessage = false;
                messageSubmit.disabled = false;
                messageSubmit.classList.remove('is-sending');
                messageSubmit.textContent = 'Send';
                attachmentPreview?.classList.remove('is-uploading');
            }
        }

        messageForm?.addEventListener('submit', async function (event) {
            event.preventDefault();
            await submitMessage();
        });

        attachmentSend?.addEventListener('click', async function () {
            await submitMessage();
        });

        if (messageList) {
            scrollToBottom(true);
            bindImageDeleteButtons();
            bindImageLoadScroll();

            messageList.addEventListener('scroll', function () {
                updateAutoScrollState();
            });

            const observer = new MutationObserver(function () {
                scrollToBottom();
            });

            observer.observe(messageList, {
                childList: true,
                subtree: true,
            });

            window.addEventListener('load', function () {
                scrollToBottom(true);
                bindImageLoadScroll();
                setTimeout(function () {
                    scrollToBottom(true);
                }, 120);
            });
        }

        const poll = async () => {
            // Reduced lock time from 5s to 2s to make chat feel more responsive
            const recentlySent = (Date.now() - lastSendTimestamp) < 2000;
            if (pollInFlight || document.hidden || isSendingMessage || recentlySent) {
                return;
            }

            pollInFlight = true;

            try {
                // Ensure the URL is always fresh and not cached by adding a timestamp
                const freshPollUrl = new URL(pollUrl, window.location.origin);
                freshPollUrl.searchParams.set('_t', Date.now());

                const response = await fetch(freshPollUrl.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                        'Pragma': 'no-cache',
                        'Accept': 'application/json',
                        'ngrok-skip-browser-warning': 'true'
                    },
                    cache: 'no-store',
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error('Poll failed with status: ' + response.status);
                }

                const data = await response.json();
                const nextSignature = data.thread_signature || '';

                if (messageList && nextSignature && nextSignature !== currentSignature) {
                    renderMessages(data.messages || [], nextSignature, true);
                }
            } catch (error) {
                console.warn('Message poll error:', error.message);
            } finally {
                pollInFlight = false;
            }
        };

        if (messageList) {
            setInterval(poll, 2500);
        }

        // --- New Call Logic ---
        document.addEventListener('click', async function (event) {
            const button = event.target.closest('.js-message-call');
            if (!button || button.disabled) {
                return;
            }

            const callUrl = button.dataset.callUrl;
            const callType = button.dataset.callType || 'audio';
            const peerName = button.dataset.peerName || 'User';

            if (!callUrl) {
                return;
            }

            button.disabled = true;
            const originalHtml = button.innerHTML;
            button.innerHTML = '<span style="font-size: 11px; font-weight: 900;">' + (callType === 'video' ? 'Video...' : 'Audio...') + '</span>';

            try {
                if (window.PikFreshCallLauncher) {
                    await window.PikFreshCallLauncher.prepareMedia({
                        type: callType,
                    });
                }

                const response = await fetch(callUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'ngrok-skip-browser-warning': 'true'
                    },
                    body: JSON.stringify({
                        type: callType,
                    }),
                    credentials: 'same-origin'
                });

                const payload = window.PikFreshCallLauncher
                    ? await window.PikFreshCallLauncher.parseJsonResponse(response, 'Unable to start call.')
                    : await response.json();

                if (payload.call_url) {
                    if (window.PikFreshCallLauncher) {
                        window.PikFreshCallLauncher.open(payload.call_url, {
                            title: callType === 'video' ? 'Video call with ' + peerName : 'Audio call with ' + peerName,
                        });
                    } else {
                        window.location.href = payload.call_url;
                    }
                }
            } catch (error) {
                console.error('Call initiation error:', error);
                window.alert(window.PikFreshCallLauncher
                    ? window.PikFreshCallLauncher.callErrorMessage(error, 'start the call')
                    : 'Could not start the call. Please try again.');
            } finally {
                button.disabled = false;
                button.innerHTML = originalHtml;
            }
        });
        // --- End Call Logic ---
    })();
</script>
@endsection
