<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\CallInvite;
use App\Models\Message;
use App\Models\Review;
use App\Models\WishlistItem;
use App\Policies\CartPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Cart::class, CartPolicy::class);

        View::composer('layouts.app', function ($view): void {
            $headerUnreadMessageCount = 0;
            $headerUnseenReviewCount = 0;
            $headerNotificationCount = 0;
            $headerMessageRoute = route('messages.index');
            $headerCartCount = 0;
            $headerWishlistCount = 0;
            $headerIncomingCallCount = 0;
            $headerNotificationPreview = [];

            if (Auth::check()) {
                $user = Auth::user();

                $headerUnreadMessageCount = Message::query()
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->count();

                $latestConversationMessage = Message::query()
                    ->where(function ($query) use ($user) {
                        $query->where('sender_id', $user->id)
                            ->orWhere('receiver_id', $user->id);
                    })
                    ->latest()
                    ->first();

                if ($latestConversationMessage) {
                    $otherUserId = $latestConversationMessage->sender_id === $user->id
                        ? $latestConversationMessage->receiver_id
                        : $latestConversationMessage->sender_id;

                    $headerMessageRoute = route('messages.show', $otherUserId);
                }

                if ($user->isVendor() && $user->vendor) {
                    $headerUnseenReviewCount = Review::query()
                        ->where('vendor_id', $user->vendor->id)
                        ->where('is_seen', false)
                        ->count();

                    $headerIncomingCallCount = CallInvite::query()
                        ->where('vendor_id', $user->vendor->id)
                        ->where('status', 'ringing')
                        ->count();
                }

                $headerCartCount = Cart::query()
                    ->where('user_id', $user->id)
                    ->sum('quantity');

                $headerWishlistCount = WishlistItem::query()
                    ->where('user_id', $user->id)
                    ->count();

                $headerNotificationCount = $headerUnreadMessageCount + $headerUnseenReviewCount + $headerIncomingCallCount;

                if ($headerUnreadMessageCount > 0) {
                    $headerNotificationPreview[] = [
                        'label' => 'Unread messages',
                        'message' => $headerUnreadMessageCount === 1
                            ? 'You have 1 unread message waiting.'
                            : "You have {$headerUnreadMessageCount} unread messages waiting.",
                    ];
                }

                if ($headerIncomingCallCount > 0) {
                    $headerNotificationPreview[] = [
                        'label' => 'Incoming calls',
                        'message' => $headerIncomingCallCount === 1
                            ? 'A buyer call is waiting for your response.'
                            : "{$headerIncomingCallCount} buyer calls are waiting for your response.",
                    ];
                }

                if ($headerUnseenReviewCount > 0) {
                    $headerNotificationPreview[] = [
                        'label' => 'New reviews',
                        'message' => $headerUnseenReviewCount === 1
                            ? 'You received 1 new vendor review.'
                            : "You received {$headerUnseenReviewCount} new vendor reviews.",
                    ];
                }
            }

            if (empty($headerNotificationPreview)) {
                $headerNotificationPreview[] = [
                    'label' => 'All caught up',
                    'message' => 'No new alerts right now. We will show updates here as they happen.',
                ];
            }

            $view->with([
                'headerUnreadMessageCount' => $headerUnreadMessageCount,
                'headerUnseenReviewCount' => $headerUnseenReviewCount,
                'headerNotificationCount' => $headerNotificationCount,
                'headerMessageRoute' => $headerMessageRoute,
                'headerCartCount' => $headerCartCount,
                'headerWishlistCount' => $headerWishlistCount,
                'headerIncomingCallCount' => $headerIncomingCallCount,
                'headerNotificationPreview' => $headerNotificationPreview,
            ]);
        });
    }
}
