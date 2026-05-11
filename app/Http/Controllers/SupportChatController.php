<?php

namespace App\Http\Controllers;

use App\Models\SupportChatMessage;
use App\Models\SupportChatThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SupportChatController extends Controller
{
    protected function ensureSessionToken(Request $request): string
    {
        $token = (string) $request->session()->get('support_chat_session_token', '');

        if ($token !== '') {
            return $token;
        }

        $token = (string) Str::uuid();
        $request->session()->put('support_chat_session_token', $token);

        return $token;
    }

    protected function resolveThread(Request $request): ?SupportChatThread
    {
        if (Auth::check() && ! Auth::user()->isAdmin()) {
            return SupportChatThread::query()
                ->where('user_id', Auth::id())
                ->latest('last_message_at')
                ->latest('id')
                ->first();
        }

        $threadId = $request->session()->get('support_chat_thread_id');
        if ($threadId) {
            return SupportChatThread::query()->find($threadId);
        }

        $sessionToken = $this->ensureSessionToken($request);

        return SupportChatThread::query()
            ->where('session_token', $sessionToken)
            ->latest('last_message_at')
            ->latest('id')
            ->first();
    }

    protected function rememberThread(Request $request, SupportChatThread $thread): void
    {
        $request->session()->put('support_chat_thread_id', $thread->id);
        $request->session()->put('support_chat_session_token', $thread->session_token ?: $this->ensureSessionToken($request));

        if ($thread->guest_name) {
            $request->session()->put('support_chat_guest_name', $thread->guest_name);
        }

        if ($thread->guest_email) {
            $request->session()->put('support_chat_guest_email', $thread->guest_email);
        }
    }

    protected function serializeMessage(SupportChatMessage $message): array
    {
        $currentUserId = Auth::id();
        $isSent = match ($message->sender_type) {
            'admin' => false,
            'user' => $currentUserId && (int) $message->user_id === (int) $currentUserId,
            default => true,
        };

        return [
            'id' => $message->id,
            'message' => $message->message,
            'sender_type' => $message->sender_type,
            'author_name' => $message->sender_name,
            'is_sent' => $isSent,
            'time' => $message->created_at->diffForHumans(),
        ];
    }

    protected function payload(Request $request, ?SupportChatThread $thread): array
    {
        $messages = $thread
            ? $thread->messages()->orderBy('created_at')->get()
            : collect();

        return [
            'thread_exists' => (bool) $thread,
            'thread_id' => $thread?->id,
            'needs_identity' => ! Auth::check() && ! $thread,
            'visitor_name' => Auth::check()
                ? Auth::user()->name
                : ($thread?->guest_name ?: $request->session()->get('support_chat_guest_name', '')),
            'visitor_email' => Auth::check()
                ? Auth::user()->email
                : ($thread?->guest_email ?: $request->session()->get('support_chat_guest_email', '')),
            'thread_signature' => optional($messages->last())->id . ':' . $messages->count(),
            'messages' => $messages->map(fn (SupportChatMessage $message) => $this->serializeMessage($message))->values(),
        ];
    }

    public function bootstrap(Request $request)
    {
        $thread = $this->resolveThread($request);

        if ($thread) {
            $thread->messages()
                ->where('sender_type', 'admin')
                ->where('is_read_by_client', false)
                ->update(['is_read_by_client' => true]);

            $this->rememberThread($request, $thread);
            $thread->load('messages');
        }

        return response()->json($this->payload($request, $thread));
    }

    public function start(Request $request)
    {
        if (Auth::check() && ! Auth::user()->isAdmin()) {
            $thread = $this->resolveThread($request);

            if (! $thread) {
                $thread = SupportChatThread::query()->create([
                    'user_id' => Auth::id(),
                    'guest_name' => Auth::user()->name,
                    'guest_email' => Auth::user()->email,
                    'session_token' => $this->ensureSessionToken($request),
                    'last_message_at' => now(),
                ]);
            }
        } else {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
            ]);

            $thread = $this->resolveThread($request);

            if (! $thread) {
                $thread = SupportChatThread::query()->create([
                    'guest_name' => trim($validated['name']),
                    'guest_email' => strtolower(trim($validated['email'])),
                    'session_token' => $this->ensureSessionToken($request),
                    'last_message_at' => now(),
                ]);
            } else {
                $thread->update([
                    'guest_name' => trim($validated['name']),
                    'guest_email' => strtolower(trim($validated['email'])),
                ]);
            }
        }

        $this->rememberThread($request, $thread);

        return response()->json([
            'message' => 'Support chat ready.',
            ...$this->payload($request, $thread),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
        ]);

        $thread = $this->resolveThread($request);

        if (! $thread) {
            return response()->json([
                'message' => 'Start the support chat first.',
                'needs_identity' => ! Auth::check(),
            ], 422);
        }

        $message = SupportChatMessage::query()->create([
            'thread_id' => $thread->id,
            'sender_type' => Auth::check() && ! Auth::user()->isAdmin() ? 'user' : 'guest',
            'user_id' => Auth::check() ? Auth::id() : null,
            'sender_name' => Auth::check() ? Auth::user()->name : ($thread->guest_name ?: 'Guest'),
            'message' => trim($validated['message']),
            'is_read_by_admin' => false,
            'is_read_by_client' => true,
        ]);

        $thread->update(['last_message_at' => $message->created_at]);
        $this->rememberThread($request, $thread);

        return response()->json([
            'message' => 'Support message sent.',
            ...$this->payload($request, $thread->fresh()),
        ]);
    }
}
