<?php

namespace App\Services;

use App\Models\PushSubscription;
use Illuminate\Support\Facades\Schema;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Throwable;

class BrowserPushNotificationService
{
    public function count(): int
    {
        return Schema::hasTable('push_subscriptions')
            ? PushSubscription::query()->whereHas('user', fn ($query) => $query->where('notifications_enabled', true))->count()
            : 0;
    }

    public function isConfigured(): bool
    {
        return filled(config('webpush.vapid.public_key'))
            && filled(config('webpush.vapid.private_key'))
            && filled(config('webpush.vapid.subject'));
    }

    public function send(string $title, string $body, ?string $url = null): int
    {
        if (! Schema::hasTable('push_subscriptions') || ! $this->isConfigured()) {
            return 0;
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => config('webpush.vapid.subject'),
                'publicKey' => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ]);

        $subscriptions = PushSubscription::query()
            ->whereHas('user', fn ($query) => $query->where('notifications_enabled', true))
            ->get();
        foreach ($subscriptions as $subscription) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $subscription->endpoint,
                    'publicKey' => $subscription->public_key,
                    'authToken' => $subscription->auth_token,
                    'contentEncoding' => $subscription->content_encoding ?: 'aes128gcm',
                ]),
                json_encode([
                    'title' => $title,
                    'body' => $body,
                    'url' => $url ?: '/',
                    'icon' => '/images/logo.png',
                    'badge' => '/images/logo.png',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            );
        }

        $sent = 0;
        try {
            foreach ($webPush->flush() as $report) {
                if ($report->isSuccess()) {
                    $sent++;
                    continue;
                }

                if (in_array($report->getResponse()?->getStatusCode(), [404, 410], true)) {
                    PushSubscription::query()
                        ->where('endpoint', (string) $report->getRequest()->getUri())
                        ->delete();
                }
            }
        } catch (Throwable) {
            return $sent;
        }

        return $sent;
    }
}
