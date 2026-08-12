<?php

namespace App\Http\Controllers;

use App\Models\SupportChatMessage;
use App\Models\SupportChatThread;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:4000'],
        ]);

        $thread = SupportChatThread::query()->create([
            'user_id' => $request->user()?->id,
            'guest_name' => trim($validated['name']),
            'guest_email' => strtolower(trim($validated['email'])),
            'session_token' => (string) Str::uuid(),
            'last_message_at' => now(),
        ]);

        $message = SupportChatMessage::query()->create([
            'thread_id' => $thread->id,
            'sender_type' => $request->user() ? 'user' : 'guest',
            'user_id' => $request->user()?->id,
            'sender_name' => trim($validated['name']),
            'message' => "[{$validated['subject']}]\n\n" . trim($validated['message']),
            'is_read_by_admin' => false,
            'is_read_by_client' => true,
        ]);

        $thread->update(['last_message_at' => $message->created_at]);

        return back()->with('success', 'Your message has been sent. Our support team will get back to you soon.');
    }
}
