<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected function notificationSummaryFor($user): array
    {
        $unreadMessages = Message::query()
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        $newReviews = 0;
        $incomingCalls = 0;

        if ($user->isVendor() && $user->vendor) {
            $newReviews = Review::query()
                ->where('vendor_id', $user->vendor->id)
                ->where('is_seen', false)
                ->count();

            $incomingCalls = \App\Models\CallInvite::query()
                ->where('vendor_id', $user->vendor->id)
                ->where('status', 'ringing')
                ->count();
        }

        $preview = [];

        if ($unreadMessages > 0) {
            $preview[] = [
                'label' => 'Unread messages',
                'message' => $unreadMessages === 1
                    ? 'You have 1 unread message waiting.'
                    : "You have {$unreadMessages} unread messages waiting.",
            ];
        }

        if ($incomingCalls > 0) {
            $preview[] = [
                'label' => 'Incoming calls',
                'message' => $incomingCalls === 1
                    ? 'A buyer call is waiting for your response.'
                    : "{$incomingCalls} buyer calls are waiting for your response.",
            ];
        }

        if ($newReviews > 0) {
            $preview[] = [
                'label' => 'New reviews',
                'message' => $newReviews === 1
                    ? 'You received 1 new vendor review.'
                    : "You received {$newReviews} new vendor reviews.",
            ];
        }

        if (empty($preview)) {
            $preview[] = [
                'label' => 'All caught up',
                'message' => 'No new alerts right now. We will show updates here as they happen.',
            ];
        }

        $latestConversationMessage = Message::query()
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->latest()
            ->first();

        $messageRoute = route('messages.index');

        if ($latestConversationMessage) {
            $otherUserId = $latestConversationMessage->sender_id === $user->id
                ? $latestConversationMessage->receiver_id
                : $latestConversationMessage->sender_id;

            $messageRoute = route('messages.show', $otherUserId);
        }

        return [
            'notification_count' => $unreadMessages + $newReviews + $incomingCalls,
            'unread_messages' => $unreadMessages,
            'new_reviews' => $newReviews,
            'incoming_calls' => $incomingCalls,
            'message_route' => $messageRoute,
            'preview' => $preview,
        ];
    }

    protected function statsFor($user): array
    {
        return [
            'orders' => $user->orders()->count(),
            'wishlist' => $user->wishlistItems()->count(),
            'addresses' => filled($user->address) ? 1 : 0,
        ];
    }

    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', [
            'user' => $user,
            'stats' => $this->statsFor($user),
        ]);
    }

    public function socialLinks()
    {
        return view('profile.social-links', ['user' => Auth::user()]);
    }

    public function socialChannel(string $channel)
    {
        $pages = [
            'whatsapp' => [
                'title' => 'WhatsApp',
                'description' => 'Connect your WhatsApp account to receive quick order updates and customer support messages.',
            ],
            'facebook' => [
                'title' => 'Facebook',
                'description' => 'Link Facebook to make sharing and account access easier across your profile.',
            ],
            'instagram' => [
                'title' => 'Instagram',
                'description' => 'Connect Instagram so your social identity can be tied to your PikFresh profile.',
            ],
        ];

        abort_unless(isset($pages[$channel]), 404);

        return view('profile.simple-page', [
            'user' => Auth::user(),
            'title' => $pages[$channel]['title'],
            'description' => $pages[$channel]['description'],
            'primaryLabel' => 'Connect',
            'primaryRoute' => route('profile.social-links'),
            'details' => [
                ['label' => 'Status', 'value' => 'Not connected'],
            ],
        ]);
    }

    public function wishlist()
    {
        $wishlistItems = Auth::user()
            ->wishlistItems()
            ->with('product.vendor')
            ->latest()
            ->get();

        return view('profile.wishlist', compact('wishlistItems'));
    }

    public function addresses()
    {
        $user = Auth::user();

        return view('profile.simple-page', [
            'user' => $user,
            'title' => 'Saved Addresses',
            'description' => 'Manage your saved delivery addresses for faster checkout.',
            'primaryLabel' => 'Edit Profile',
            'primaryRoute' => route('profile.edit'),
            'details' => [
                ['label' => 'Current Address', 'value' => $user->address ?: 'No saved address yet'],
            ],
        ]);
    }

    public function paymentMethods()
    {
        return view('profile.simple-page', [
            'user' => Auth::user(),
            'title' => 'Payment Methods',
            'description' => 'Store your preferred payment methods here when payments are enabled.',
            'primaryLabel' => 'Go To Checkout',
            'primaryRoute' => route('checkout.index'),
            'details' => [
                ['label' => 'Saved Methods', 'value' => '0'],
            ],
        ]);
    }

    public function notifications()
    {
        $user = Auth::user();
        $summary = $this->notificationSummaryFor($user);

        if ($user->isVendor() && $user->vendor) {
            Review::query()
                ->where('vendor_id', $user->vendor->id)
                ->where('is_seen', false)
                ->update(['is_seen' => true]);
        }

        return view('profile.simple-page', [
            'user' => $user,
            'title' => 'Notifications',
            'description' => 'Review your alerts and notification preferences for orders and account activity.',
            'primaryLabel' => 'Open Messages',
            'primaryRoute' => route('messages.index'),
            'details' => [
                ['label' => 'Unread Messages', 'value' => (string) $summary['unread_messages']],
                ['label' => 'New Reviews', 'value' => (string) $summary['new_reviews']],
                ['label' => 'Order Updates', 'value' => 'Enabled'],
            ],
        ]);
    }

    public function notificationsSummary()
    {
        return response()->json($this->notificationSummaryFor(Auth::user()));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string',
            'language' => 'required|in:en,fr,es',
        ]);

        $user->update($request->only(['name', 'phone', 'email', 'address', 'language']));

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'label' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        $user->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        session([
            'user_latitude' => $request->latitude,
            'user_longitude' => $request->longitude,
            'location_label' => $request->input('label', 'your current location'),
        ]);

        return response()->json([
            'message' => 'Location updated successfully.',
        ]);
    }
}
