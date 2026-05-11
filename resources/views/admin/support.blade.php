@extends('admin.layouts.app')

@section('title', 'Admin Support - PikFreshFood')
@section('page_title', 'Support')
@section('page_copy', 'Manage guest and user live chat conversations with AJAX replies')

@section('styles')
.support-shell {
    display: grid;
    grid-template-columns: 320px minmax(0, 1fr);
    gap: 16px;
}
.support-card {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: var(--radius);
}
.support-sidebar {
    padding: 14px;
    max-height: calc(100vh - 180px);
    overflow-y: auto;
}
.support-title {
    font-size: 1.05rem;
    font-weight: 800;
    margin-bottom: 12px;
}
.support-thread {
    display: block;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid transparent;
    background: #f7f9ff;
    margin-bottom: 10px;
    cursor: pointer;
}
.support-thread.active {
    border-color: #b9caef;
    background: #edf3ff;
}
.support-thread-top {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 6px;
}
.support-thread-name {
    font-weight: 800;
}
.support-thread-time,
.support-thread-email,
.support-thread-preview {
    color: var(--muted);
    font-size: 0.84rem;
}
.support-thread-preview {
    margin-top: 4px;
    line-height: 1.5;
}
.support-thread-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 22px;
    margin-top: 8px;
    padding: 0 7px;
    border-radius: 999px;
    background: #e74c3c;
    color: #fff;
    font-size: 0.72rem;
    font-weight: 800;
}
.support-conversation {
    padding: 16px;
    min-height: calc(100vh - 180px);
    display: flex;
    flex-direction: column;
}
.support-header {
    padding-bottom: 14px;
    margin-bottom: 14px;
    border-bottom: 1px solid var(--line);
}
.support-header h3 {
    margin-bottom: 4px;
}
.support-header p {
    color: var(--muted);
}
.support-messages {
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding-right: 4px;
}
.support-row {
    display: flex;
}
.support-row.admin {
    justify-content: flex-end;
}
.support-row.client {
    justify-content: flex-start;
}
.support-bubble {
    max-width: 78%;
    padding: 12px 14px;
    border-radius: 16px;
}
.support-bubble.admin {
    background: #1d2f63;
    color: #fff;
}
.support-bubble.client {
    background: #f4f6fb;
    color: var(--text);
    border: 1px solid var(--line);
}
.support-author {
    font-size: 0.76rem;
    font-weight: 800;
    opacity: 0.78;
    margin-bottom: 5px;
}
.support-copy {
    line-height: 1.6;
    white-space: pre-wrap;
    word-break: break-word;
}
.support-time {
    display: block;
    margin-top: 6px;
    font-size: 0.74rem;
    opacity: 0.7;
}
.support-form {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid var(--line);
}
.support-form textarea {
    width: 100%;
    min-height: 110px;
    resize: vertical;
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 12px 14px;
    font: inherit;
}
.support-form button {
    margin-top: 10px;
    min-height: 44px;
    padding: 0 18px;
    border: none;
    border-radius: 12px;
    background: #1d2f63;
    color: #fff;
    font-weight: 800;
    cursor: pointer;
}
.support-empty {
    color: var(--muted);
    text-align: center;
    padding: 28px 14px;
}
@media (max-width: 980px) {
    .support-shell {
        grid-template-columns: 1fr;
    }
    .support-sidebar,
    .support-conversation {
        max-height: none;
        min-height: 0;
    }
    .support-bubble {
        max-width: 100%;
    }
}
@endsection

@section('content')
<div class="support-shell">
    <section class="support-card support-sidebar">
        <div class="support-title">Live Chat Threads</div>
        <div id="supportThreadList">
            <div class="support-empty">Loading conversations...</div>
        </div>
    </section>

    <section class="support-card support-conversation">
        <div class="support-header" id="supportHeader">
            <h3>Select a thread</h3>
            <p>Choose a visitor conversation to respond.</p>
        </div>

        <div class="support-messages" id="supportMessages">
            <div class="support-empty">No thread selected yet.</div>
        </div>

        <form class="support-form" id="supportReplyForm" style="display:none;">
            @csrf
            <textarea name="message" id="supportReplyInput" placeholder="Type your reply..."></textarea>
            <button type="submit" id="supportReplyButton">Send Reply</button>
        </form>
    </section>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const threadList = document.getElementById('supportThreadList');
        const header = document.getElementById('supportHeader');
        const messagesBox = document.getElementById('supportMessages');
        const form = document.getElementById('supportReplyForm');
        const input = document.getElementById('supportReplyInput');
        const button = document.getElementById('supportReplyButton');
        const threadsUrl = '{{ route('admin.support.threads') }}';
        const threadUrlBase = '{{ url('/admin/support/threads') }}';
        let currentThreadId = null;
        let currentSignature = '';

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function scrollToBottom() {
            requestAnimationFrame(function () {
                messagesBox.scrollTop = messagesBox.scrollHeight;
            });
        }

        function renderThreads(threads) {
            if (!threads.length) {
                threadList.innerHTML = '<div class="support-empty">No live chat conversations yet.</div>';
                return;
            }

            threadList.innerHTML = threads.map(function (thread) {
                const activeClass = String(thread.id) === String(currentThreadId) ? ' active' : '';
                const badge = Number(thread.unread_count || 0) > 0
                    ? '<span class="support-thread-badge">' + (Number(thread.unread_count) > 9 ? '9+' : Number(thread.unread_count)) + '</span>'
                    : '';

                return '<div class="support-thread' + activeClass + '" data-thread-id="' + thread.id + '">' +
                    '<div class="support-thread-top">' +
                    '<span class="support-thread-name">' + escapeHtml(thread.name) + '</span>' +
                    '<span class="support-thread-time">' + escapeHtml(thread.latest_time) + '</span>' +
                    '</div>' +
                    '<div class="support-thread-email">' + escapeHtml(thread.email) + '</div>' +
                    '<div class="support-thread-preview">' + escapeHtml(thread.preview) + '</div>' +
                    badge +
                    '</div>';
            }).join('');

            threadList.querySelectorAll('[data-thread-id]').forEach(function (node) {
                node.addEventListener('click', function () {
                    loadThread(node.getAttribute('data-thread-id'), true);
                });
            });
        }

        function renderThread(payload) {
            currentSignature = payload.thread_signature || '';
            header.innerHTML = '<h3>' + escapeHtml(payload.thread.name) + '</h3><p>' + escapeHtml(payload.thread.email) + '</p>';
            form.style.display = 'block';

            if (!payload.messages.length) {
                messagesBox.innerHTML = '<div class="support-empty">No messages yet in this thread.</div>';
            } else {
                messagesBox.innerHTML = payload.messages.map(function (message) {
                    const rowClass = message.sender_type === 'admin' ? 'admin' : 'client';
                    return '<div class="support-row ' + rowClass + '">' +
                        '<div class="support-bubble ' + rowClass + '">' +
                        '<div class="support-author">' + escapeHtml(message.sender_name) + '</div>' +
                        '<div class="support-copy">' + escapeHtml(message.message) + '</div>' +
                        '<span class="support-time">' + escapeHtml(message.sent_at || message.time) + '</span>' +
                        '</div>' +
                        '</div>';
                }).join('');
            }

            scrollToBottom();
        }

        async function loadThreads() {
            const response = await fetch(threadsUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                cache: 'no-store',
            });
            const data = await response.json();
            renderThreads(data.threads || []);

            if (!currentThreadId && data.threads && data.threads.length) {
                loadThread(data.threads[0].id, true);
            }
        }

        async function loadThread(threadId, force) {
            currentThreadId = threadId;
            const response = await fetch(threadUrlBase + '/' + threadId, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                cache: 'no-store',
            });
            const data = await response.json();

            if (force || data.thread_signature !== currentSignature) {
                renderThread(data);
            }

            await loadThreads();
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            if (!currentThreadId) {
                return;
            }

            const message = input.value.trim();
            if (!message) {
                return;
            }

            button.disabled = true;

            try {
                const response = await fetch(threadUrlBase + '/' + currentThreadId + '/reply', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ message: message }),
                });

                const data = await response.json();
                input.value = '';
                renderThread(data);
                await loadThreads();
            } finally {
                button.disabled = false;
            }
        });

        loadThreads();
        window.setInterval(function () {
            loadThreads();

            if (currentThreadId) {
                loadThread(currentThreadId, false);
            }
        }, 3000);
    })();
</script>
@endsection
