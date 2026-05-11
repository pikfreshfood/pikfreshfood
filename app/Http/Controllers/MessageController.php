<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    protected function supportAgents()
    {
        return User::query()
            ->where(function ($query) {
                $query->where('role', 'admin')
                    ->orWhereIn('admin_role', ['super_admin', 'support']);
            })
            ->get()
            ->filter(fn (User $user) => $user->hasAdminPermission('support'))
            ->values();
    }

    protected function resolveSupportAgent(): ?User
    {
        $supportAgents = $this->supportAgents();

        if ($supportAgents->isEmpty()) {
            return null;
        }

        return $supportAgents
            ->sortBy(function (User $user) {
                return match ($user->adminRole()) {
                    'support' => 0,
                    'super_admin' => 1,
                    default => 2,
                };
            })
            ->first();
    }

    protected function supportThreadFor(User $user)
    {
        $supportAgentIds = $this->supportAgents()->pluck('id')->all();

        if (empty($supportAgentIds)) {
            return collect();
        }

        return Message::query()
            ->where(function ($query) use ($user, $supportAgentIds) {
                $query->where('sender_id', $user->id)
                    ->whereIn('receiver_id', $supportAgentIds);
            })
            ->orWhere(function ($query) use ($user, $supportAgentIds) {
                $query->where('receiver_id', $user->id)
                    ->whereIn('sender_id', $supportAgentIds);
            })
            ->with('sender.vendor', 'receiver.vendor')
            ->orderBy('created_at')
            ->get();
    }

    protected function supportThreadPayload($messages): array
    {
        return [
            'thread_signature' => optional($messages->last())->id . ':' . $messages->count(),
            'messages' => $messages->map(fn (Message $message) => $this->serializeMessage($message))->values(),
            'support_agent_name' => $this->resolveSupportAgent()?->name ?? 'Support team',
        ];
    }

    protected function serializeConversation(array $thread): array
    {
        $threadUser = $thread['user'];

        return [
            'user_id' => $threadUser->id,
            'user_name' => $threadUser->name,
            'user_avatar' => $threadUser->vendor?->profile_image
                ? \App\Support\PublicStorage::url($threadUser->vendor->profile_image)
                : null,
            'show_url' => route('messages.show', $threadUser->id),
            'latest_time' => $thread['latest']->created_at->diffForHumans(),
            'preview' => Str::limit($thread['preview'], 70),
            'unread_count' => $thread['unread_count'],
        ];
    }

    protected function threadPayload($conversations, $selectedUser, $selectedMessages): array
    {
        return [
            'selected_user_id' => $selectedUser?->id,
            'thread_signature' => optional($selectedMessages->last())->id . ':' . $selectedMessages->count(),
            'messages' => $selectedMessages->map(fn (Message $message) => $this->serializeMessage($message))->values(),
            'conversations' => $conversations->map(fn (array $thread) => $this->serializeConversation($thread))->values(),
        ];
    }

    protected function serializeMessage(Message $message): array
    {
        $message->loadMissing('sender.vendor', 'receiver.vendor');
        $isSent = $message->sender_id === Auth::id();
        $author = $message->sender;

        return [
            'id' => $message->id,
            'message' => $message->message,
            'image_url' => $message->image ? \App\Support\PublicStorage::url($message->image) : null,
            'is_sent' => $isSent,
            'can_delete_image' => $isSent && filled($message->image),
            'time' => $message->created_at->diffForHumans(),
            'author_name' => $author->name,
            'author_avatar' => $author->vendor?->profile_image
                ? \App\Support\PublicStorage::url($author->vendor->profile_image)
                : null,
        ];
    }

    protected function conversationList()
    {
        return Message::query()
            ->where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->with('sender.vendor', 'receiver.vendor')
            ->latest()
            ->get()
            ->groupBy(function (Message $message) {
                return $message->sender_id === Auth::id()
                    ? $message->receiver_id
                    : $message->sender_id;
            })
            ->map(function ($thread, $userId) {
                $latest = $thread->sortByDesc('created_at')->first();
                $otherUser = $latest->sender_id === Auth::id()
                    ? $latest->receiver
                    : $latest->sender;
                $otherUser->loadMissing('vendor');

                return [
                    'user' => $otherUser,
                    'latest' => $latest,
                    'messages' => $thread->sortBy('created_at')->values(),
                    'unread_count' => $thread->where('receiver_id', Auth::id())->where('is_read', false)->count(),
                    'preview' => $latest->message ?: ($latest->image ? 'Sent an image' : ''),
                ];
            })
            ->sortByDesc(fn ($thread) => $thread['latest']->created_at)
            ->values();
    }

    protected function threadWith(User $user)
    {
        return Message::query()
            ->where(function ($query) use ($user) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $user->id);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', Auth::id());
            })
            ->with('sender.vendor', 'receiver.vendor')
            ->orderBy('created_at')
            ->get();
    }

    public function index(Request $request)
    {
        $conversations = $this->conversationList();

        $selectedUser = null;
        if ($request->filled('user')) {
            $selectedUser = User::with('vendor')->find($request->integer('user'));
        }

        $selectedMessages = collect();

        if ($selectedUser && $selectedUser->id !== Auth::id()) {
            Message::query()
                ->where('sender_id', $selectedUser->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $selectedMessages = $this->threadWith($selectedUser);
            $conversations = $this->conversationList();
        }

        if ($request->expectsJson()) {
            return response()->json($this->threadPayload($conversations, $selectedUser, $selectedMessages));
        }

        return view('messages.index', compact('conversations', 'selectedUser', 'selectedMessages'));
    }

    public function show(Request $request, User $user)
    {
        abort_if($user->id === Auth::id(), 404);
        $user->loadMissing('vendor');

        Message::query()
            ->where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $conversations = $this->conversationList();
        $selectedUser = $user;
        $selectedMessages = $this->threadWith($user);

        if ($request->expectsJson()) {
            return response()->json($this->threadPayload($conversations, $selectedUser, $selectedMessages));
        }

        return view('messages.index', compact('conversations', 'selectedUser', 'selectedMessages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string|required_without:image',
            'image' => 'nullable|image|max:5120|required_without:message',
        ]);

        $imagePath = $request->file('image')
            ? $request->file('image')->store('messages', 'public')
            : null;

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'message' => Str::of($validated['message'] ?? '')->trim()->value(),
            'image' => $imagePath,
        ]);

        if ($request->expectsJson()) {
            $receiver = User::with('vendor')->findOrFail($validated['receiver_id']);
            $selectedMessages = $this->threadWith($receiver);
            $conversations = $this->conversationList();

            return response()->json([
                'message' => 'Message sent.',
                'chat_message' => $this->serializeMessage($message),
                ...$this->threadPayload($conversations, $receiver, $selectedMessages),
            ]);
        }

        return redirect()->route('messages.show', $validated['receiver_id'])->with('success', 'Message sent.');
    }

    public function destroyImage(Request $request, Message $message)
    {
        abort_unless($message->sender_id === Auth::id(), 403);
        abort_if(blank($message->image), 404);

        Storage::disk('public')->delete($message->image);
        $message->update(['image' => null]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Attachment deleted.',
                'chat_message' => $this->serializeMessage($message->fresh(['sender.vendor', 'receiver.vendor'])),
            ]);
        }

        return back()->with('success', 'Attachment deleted.');
    }

    public function supportChat(Request $request)
    {
        $supportAgentIds = $this->supportAgents()->pluck('id')->all();
        abort_if(empty($supportAgentIds), 404, 'Support chat is not available right now.');

        Message::query()
            ->where('receiver_id', Auth::id())
            ->whereIn('sender_id', $supportAgentIds)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $this->supportThreadFor($request->user());

        return response()->json($this->supportThreadPayload($messages));
    }

    public function supportStore(Request $request)
    {
        $supportAgent = $this->resolveSupportAgent();
        abort_if(! $supportAgent, 404, 'Support chat is not available right now.');

        $validated = $request->validate([
            'message' => 'required|string|max:4000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $supportAgent->id,
            'message' => Str::of($validated['message'])->trim()->value(),
        ]);

        $messages = $this->supportThreadFor($request->user());

        return response()->json([
            'message' => 'Support message sent.',
            ...$this->supportThreadPayload($messages),
        ]);
    }
}
