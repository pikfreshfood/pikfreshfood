<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushToken;
use App\Services\BrowserPushNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request, BrowserPushNotificationService $browserPush): View
    {
        abort_unless($request->user()->hasAdminPermission('notifications'), 403);

        return view('admin.notifications', [
            'deviceCount' => (Schema::hasTable('push_tokens') ? PushToken::query()->count() : 0)
                + (Schema::hasTable('push_subscriptions') ? $browserPush->count() : 0),
        ]);
    }

    public function store(Request $request, BrowserPushNotificationService $browserPush): RedirectResponse
    {
        abort_unless($request->user()->hasAdminPermission('notifications'), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'body' => ['required', 'string', 'max:500'],
            'url' => ['nullable', 'url', 'max:500'],
        ]);

        if (! Schema::hasTable('push_tokens') && ! Schema::hasTable('push_subscriptions')) {
            return back()->withErrors([
                'body' => 'Push notifications are not available until the database migration is applied.',
            ]);
        }

        $tokens = Schema::hasTable('push_tokens')
            ? PushToken::query()->pluck('token')->unique()->values()
            : collect();
        $browserCount = Schema::hasTable('push_subscriptions') ? $browserPush->count() : 0;
        if ($tokens->isEmpty() && $browserCount === 0) {
            return back()->withErrors(['body' => 'No mobile devices have registered for push notifications yet.']);
        }

        $messages = $tokens->map(fn (string $token) => [
            'to' => $token,
            'title' => trim($validated['title']),
            'body' => trim($validated['body']),
            'sound' => 'default',
            'channelId' => 'default',
            'data' => array_filter([
                'url' => $validated['url'] ?? null,
                'link' => $validated['url'] ?? null,
            ]),
        ])->values()->all();

        $sent = 0;
        $invalidTokens = [];
        foreach (array_chunk($messages, 100) as $chunk) {
            $http = Http::acceptJson()->timeout(20);
            if ($accessToken = config('services.expo.access_token')) {
                $http = $http->withToken($accessToken);
            }

            $response = $http->post('https://exp.host/--/api/v2/push/send', $chunk);
            if (! $response->successful()) {
                return back()->withErrors(['body' => 'Expo Push Service could not be reached. Try again later.']);
            }

            foreach ($response->json('data', []) as $index => $ticket) {
                if (($ticket['status'] ?? null) === 'ok') {
                    $sent++;
                }

                if (($ticket['details']['error'] ?? null) === 'DeviceNotRegistered') {
                    $invalidTokens[] = $chunk[$index]['to'];
                }
            }
        }

        if ($invalidTokens !== []) {
            PushToken::query()->whereIn('token', $invalidTokens)->delete();
        }

        $sent += $browserPush->send(trim($validated['title']), trim($validated['body']), $validated['url'] ?? null);

        return back()->with('success', "Notification sent to {$sent} device(s).");
    }
}