<?php

namespace App\Http\Controllers;

use App\Models\PushToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PushTokenController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        if (! Schema::hasTable('push_tokens')) {
            return response()->json([
                'message' => 'Push notifications are not available until the database migration is applied.',
            ], 503);
        }

        $validated = $request->validate([
            'token' => ['required', 'string', 'max:255'],
            'platform' => ['nullable', 'string', 'in:android,ios,web'],
        ]);

        PushToken::query()->updateOrCreate(
            ['token' => $validated['token']],
            [
                'user_id' => $request->user()?->id,
                'platform' => $validated['platform'] ?? null,
            ],
        );

        if ($request->user()) {
            $request->user()->forceFill(['notifications_enabled' => true])->save();
        }

        return response()->json(['message' => 'Push token registered.']);
    }
}