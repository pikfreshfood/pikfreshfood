<?php

namespace App\Http\Controllers;

use App\Models\CallInvite;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Review;
use App\Models\VendorLiveVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    protected function ensureCanManageProducts(Vendor $vendor)
    {
        if ($vendor->canManageProducts()) {
            return null;
        }

        return redirect()
            ->route('vendor.subscription')
            ->with('error', 'Your free trial has expired. Upgrade your subscription to upload or edit products.');
    }

    protected function boostMonths(string $plan): int
    {
        return match ($plan) {
            'premium_1m' => 1,
            'premium_3m' => 3,
            'premium_6m' => 6,
            'premium_12m' => 12,
            default => 1,
        };
    }

    protected function buyerReviewState(Vendor $vendor): array
    {
        $buyerReviewableOrder = null;

        if (Auth::check() && Auth::user()->isBuyer()) {
            $buyerReviewableOrder = Auth::user()->orders()
                ->where('vendor_id', $vendor->id)
                ->where('status', 'delivered')
                ->latest()
                ->first();
        }

        return [$buyerReviewableOrder, null];
    }

    protected function resolveVendor()
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            abort(403, 'Vendor profile required.');
        }

        return $vendor;
    }

    public function show(Vendor $vendor)
    {
        $products = $vendor->products()->where('is_available', true)->get();
        $reviews = $vendor->reviews()->with('user')->latest()->take(6)->get();
        [$buyerReviewableOrder, $buyerExistingReview] = $this->buyerReviewState($vendor);

        return view('vendor.show', compact('vendor', 'products', 'reviews', 'buyerReviewableOrder', 'buyerExistingReview'));
    }

    public function review(Vendor $vendor)
    {
        abort_unless(Auth::check() && Auth::user()->isBuyer(), 403);
        abort_if(Auth::id() === $vendor->user_id, 403);

        return redirect()->to(route('vendor.show', $vendor) . '#rate-vendor');
    }

    public function onlineCall(Vendor $vendor)
    {
        abort_unless(Auth::check() && Auth::user()->isBuyer(), 403);
        abort_if(Auth::id() === $vendor->user_id, 403);

        $roomName = sprintf('pikfresh-vendor-%d-buyer-%d-audio', $vendor->id, Auth::id());
        $invite = CallInvite::query()
            ->updateOrCreate(
                [
                    'vendor_id' => $vendor->id,
                    'buyer_id' => Auth::id(),
                    'status' => 'pending',
                ],
                [
                    'room_name' => $roomName,
                ]
            );

        $callUrl = 'https://meet.jit.si/' . $invite->room_name . '#config.prejoinPageEnabled=false&config.startWithVideoMuted=true';

        return redirect()->away($callUrl);
    }

    public function incomingCall()
    {
        $vendor = $this->resolveVendor();

        $invite = CallInvite::query()
            ->with('buyer')
            ->where('vendor_id', $vendor->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (! $invite) {
            return response()->json([
                'incoming' => false,
            ]);
        }

        return response()->json([
            'incoming' => true,
            'invite' => [
                'id' => $invite->id,
                'buyer_name' => $invite->buyer?->name ?? 'Buyer',
                'room_url' => 'https://meet.jit.si/' . $invite->room_name . '#config.prejoinPageEnabled=false&config.startWithVideoMuted=true',
            ],
        ]);
    }

    public function acceptIncomingCall(CallInvite $callInvite)
    {
        $vendor = $this->resolveVendor();
        abort_unless($callInvite->vendor_id === $vendor->id, 403);

        $callInvite->update([
            'status' => 'accepted',
        ]);

        return response()->json([
            'room_url' => 'https://meet.jit.si/' . $callInvite->room_name . '#config.prejoinPageEnabled=false&config.startWithVideoMuted=true',
        ]);
    }

    public function storeReview(Request $request, Vendor $vendor)
    {
        abort_unless(Auth::check() && Auth::user()->isBuyer(), 403);
        abort_if(Auth::id() === $vendor->user_id, 403);

        $reviewableOrder = Auth::user()->orders()
            ->where('vendor_id', $vendor->id)
            ->where('status', 'delivered')
            ->latest()
            ->first();

        if (! $reviewableOrder) {
            return back()->with('error', 'You can only rate vendors after a delivered order.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'vendor_id' => $vendor->id,
            'order_id' => $reviewableOrder->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        $vendor->update([
            'rating' => round((float) $vendor->reviews()->avg('rating'), 1),
        ]);

        return back()->with('success', 'Thanks for rating this vendor.');
    }

    public function onboarding()
    {
        return view('vendor.onboarding', [
            'vendor' => Auth::user()->vendor,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'profile_image' => 'nullable|image',
            'promo_video_url' => 'nullable|url',
        ]);

        $profileImage = $request->file('profile_image')
            ? $request->file('profile_image')->store('vendors', 'public')
            : null;

        Vendor::create([
            'user_id' => Auth::id(),
            'shop_name' => $request->shop_name,
            'profile_image' => $profileImage,
            'description' => $request->description,
            'phone' => $request->phone,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'promo_video_url' => $request->promo_video_url,
            'subscription_plan' => 'free',
            'subscription_status' => 'trial',
            'subscription_expires_at' => now()->addMonth(),
        ]);

        return redirect()->route('vendor.dashboard')->with('success', 'Vendor profile created!');
    }

    public function dashboard()
    {
        $vendor = Auth::user()->vendor;
        if (!$vendor) {
            return redirect()->route('vendor.onboarding');
        }

        $orders = $vendor->orders()->with('user')->latest()->take(10)->get();
        $products = $vendor->products()->latest()->paginate(10);
        $productCount = $vendor->products()->count();
        $liveVideos = $vendor->liveVideos()->latest()->get();
        $subscriptionExpiresAt = $vendor->resolvedSubscriptionExpiresAt();
        $subscriptionExpired = $subscriptionExpiresAt ? now()->gt($subscriptionExpiresAt) : false;

        return view('vendor.dashboard', compact(
            'vendor',
            'orders',
            'products',
            'productCount',
            'liveVideos',
            'subscriptionExpiresAt',
            'subscriptionExpired'
        ));
    }

    public function orders()
    {
        $vendor = $this->resolveVendor();
        $orders = $vendor->orders()
            ->with('user', 'items.product')
            ->latest()
            ->get();

        return view('vendor.orders', compact('vendor', 'orders'));
    }

    public function showOrder(\App\Models\Order $order)
    {
        $vendor = $this->resolveVendor();
        abort_unless($order->vendor_id === $vendor->id, 403);

        $order->load('user', 'items.product', 'review');

        return view('vendor.order-show', compact('vendor', 'order'));
    }

    public function updateOrderStatus(Request $request, \App\Models\Order $order)
    {
        $vendor = $this->resolveVendor();
        abort_unless($order->vendor_id === $vendor->id, 403);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,delivered',
        ]);

        $order->update([
            'status' => $validated['status'],
            'delivered_at' => $validated['status'] === 'delivered' ? now() : null,
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }

    public function products()
    {
        $vendor = $this->resolveVendor();
        $products = $vendor->products()->latest()->get();
        $orders = $vendor->orders()->latest()->take(10)->get();

        return view('vendor.products', compact('vendor', 'products', 'orders'));
    }

    public function editProfile()
    {
        return view('vendor.onboarding', [
            'vendor' => $this->resolveVendor(),
        ]);
    }

    public function addProduct()
    {
        $vendor = $this->resolveVendor();
        if ($redirect = $this->ensureCanManageProducts($vendor)) {
            return $redirect;
        }

        return view('vendor.add-product');
    }

    public function editProduct(Product $product)
    {
        $vendor = $this->resolveVendor();
        abort_unless($product->vendor_id === $vendor->id, 403);
        if ($redirect = $this->ensureCanManageProducts($vendor)) {
            return $redirect;
        }

        return view('vendor.edit-product', compact('product'));
    }

    public function storeProduct(Request $request)
    {
        $vendor = $this->resolveVendor();
        if ($redirect = $this->ensureCanManageProducts($vendor)) {
            return $redirect;
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'required|string',
            'images' => 'required|array|min:1|max:6',
            'images.*' => 'image',
        ]);

        $imagePaths = collect($request->file('images', []))
            ->map(fn ($file) => $file->store('products', 'public'))
            ->values()
            ->all();

        $vendor->products()->create([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'unit' => $request->unit,
            'image' => $imagePaths[0] ?? null,
            'images' => $imagePaths,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Product added!',
                'redirect' => route('vendor.products'),
            ]);
        }

        return redirect()->route('vendor.products')->with('success', 'Product added!');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $vendor = $this->resolveVendor();
        abort_unless($product->vendor_id === $vendor->id, 403);
        if ($redirect = $this->ensureCanManageProducts($vendor)) {
            return $redirect;
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'required|string',
            'images' => 'nullable|array|max:6',
            'images.*' => 'image',
            'removed_existing_images' => 'nullable|array',
            'removed_existing_images.*' => 'string',
            'is_available' => 'nullable|boolean',
        ]);

        $existingImages = collect($product->image_gallery);
        $removedImages = collect($request->input('removed_existing_images', []));
        $keptImages = $existingImages
            ->reject(fn ($path) => $removedImages->contains($path))
            ->values();

        $newImages = collect($request->file('images', []))
            ->map(fn ($file) => $file->store('products', 'public'))
            ->values();

        $imagePaths = $keptImages
            ->concat($newImages)
            ->take(6)
            ->values()
            ->all();

        foreach ($removedImages as $removedImage) {
            if ($existingImages->contains($removedImage)) {
                Storage::disk('public')->delete($removedImage);
            }
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'unit' => $request->unit,
            'image' => $imagePaths[0] ?? null,
            'images' => $imagePaths,
            'is_available' => $request->boolean('is_available', true),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Product updated!',
                'redirect' => route('vendor.products'),
            ]);
        }

        return redirect()->route('vendor.products')->with('success', 'Product updated!');
    }

    public function destroyProduct(Product $product)
    {
        abort_unless($product->vendor_id === $this->resolveVendor()->id, 403);
        $product->delete();

        return redirect()->route('vendor.products')->with('success', 'Product deleted.');
    }

    public function updateProfile(Request $request)
    {
        $vendor = $this->resolveVendor();

        $request->validate([
            'shop_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'profile_image' => 'nullable|image',
            'promo_video_url' => 'nullable|url',
        ]);

        $profileImage = $vendor->profile_image;

        if ($request->file('profile_image')) {
            if ($profileImage) {
                Storage::disk('public')->delete($profileImage);
            }

            $profileImage = $request->file('profile_image')->store('vendors', 'public');
        }

        $vendor->update([
            'shop_name' => $request->shop_name,
            'profile_image' => $profileImage,
            'description' => $request->description,
            'phone' => $request->phone,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'promo_video_url' => $request->promo_video_url,
        ]);

        return redirect()->route('vendor.dashboard')->with('success', 'Shop information updated!');
    }

    public function toggleLive()
    {
        $vendor = $this->resolveVendor();

        $vendor->update([
            'is_live' => ! $vendor->is_live,
        ]);

        return back()->with('success', $vendor->is_live ? 'Shop is now live.' : 'Shop has been paused.');
    }

    public function storeLiveVideo(Request $request)
    {
        $vendor = $this->resolveVendor();

        $validated = $request->validate([
            'title' => 'nullable|string|max:120',
            'video' => 'required|file|mimetypes:video/mp4,video/webm,video/quicktime|max:51200',
            'duration_seconds' => 'required|integer|min:1|max:60',
        ], [
            'duration_seconds.max' => 'Live video must be 60 seconds or less.',
            'video.max' => 'Video size is too large. Use 50MB or less.',
        ]);

        $videoPath = $request->file('video')->store('live-videos', 'public');

        $vendor->liveVideos()->create([
            'title' => $validated['title'] ?? null,
            'video_path' => $videoPath,
            'duration_seconds' => (int) $validated['duration_seconds'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Live video uploaded successfully.');
    }

    public function destroyLiveVideo(VendorLiveVideo $liveVideo)
    {
        $vendor = $this->resolveVendor();
        abort_unless($liveVideo->vendor_id === $vendor->id, 403);

        Storage::disk('public')->delete($liveVideo->video_path);
        $liveVideo->delete();

        return back()->with('success', 'Live video removed.');
    }

    public function boostShop(Request $request)
    {
        $vendor = $this->resolveVendor();

        $validated = $request->validate([
            'boost_plan' => 'required|in:premium_1m,premium_3m,premium_6m,premium_12m',
        ]);

        $months = $this->boostMonths($validated['boost_plan']);
        $baseDate = $vendor->boosted_until && now()->lte($vendor->boosted_until)
            ? $vendor->boosted_until
            : now();

        $vendor->update([
            'boosted_until' => $baseDate->copy()->addMonths($months),
        ]);

        return back()->with('success', 'Shop boosted successfully.');
    }

    public function boostProduct(Request $request, Product $product)
    {
        $vendor = $this->resolveVendor();
        abort_unless($product->vendor_id === $vendor->id, 403);

        $validated = $request->validate([
            'boost_plan' => 'required|in:premium_1m,premium_3m,premium_6m,premium_12m',
        ]);

        $months = $this->boostMonths($validated['boost_plan']);
        $baseDate = $product->boosted_until && now()->lte($product->boosted_until)
            ? $product->boosted_until
            : now();

        $product->update([
            'boosted_until' => $baseDate->copy()->addMonths($months),
        ]);

        return back()->with('success', 'Product boosted successfully.');
    }
}
