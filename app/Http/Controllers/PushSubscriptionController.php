<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PushSubscriptionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        if (! Schema::hasTable('push_subscriptions')) {
            return response()->json(['message' => 'Browser notifications are not available yet.'], 503);
        }

        $validated = $request->validate([
            'endpoint' => ['required', 'url', 'max:2048'],
            'keys.p256dh' => ['required', 'string', 'max:255'],
            'keys.auth' => ['required', 'string', 'max:255'],
            'content_encoding' => ['nullable', 'string', 'in:aes128gcm,aesgcm'],
        ]);

        PushSubscription::query()->updateOrCreate(
            ['endpoint' => $validated['endpoint']],
            [
                'user_id' => $request->user()->id,
                'public_key' => $validated['keys']['p256dh'],
                'auth_token' => $validated['keys']['auth'],
                'content_encoding' => $validated['content_encoding'] ?? 'aes128gcm',
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
            ],
        );

        return response()->json(['message' => 'Browser push subscription registered.']);
    }

    public function destroy(Request $request): JsonResponse
    {
        if (Schema::hasTable('push_subscriptions')) {
            PushSubscription::query()
                ->where('user_id', $request->user()->id)
                ->where('endpoint', (string) $request->string('endpoint'))
                ->delete();
        }

        return response()->json(['message' => 'Browser push subscription removed.']);
    }
}
