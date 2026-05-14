<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PortalController as AdminPortalController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\CallerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\MockupController;
use App\Http\Controllers\LiveController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReactAuthController;
use App\Http\Controllers\ReactCallerController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\StreetFoodController;
use App\Http\Controllers\VendorFinanceController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/storage/{path}', function (string $path) {
    $path = ltrim(str_replace('\\', '/', $path), '/');

    abort_if($path === '' || str_contains($path, '..'), 404);
    abort_unless(Storage::disk('public')->exists($path), 404);

    return response()->file(Storage::disk('public')->path($path), [
        'Cache-Control' => 'public, max-age=604800',
        'X-Content-Type-Options' => 'nosniff',
    ]);
})->where('path', '.*')->name('storage.public');

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/search/suggestions', [ProductController::class, 'suggestions'])->name('search.suggestions');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [AdminPortalController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminPortalController::class, 'updateProfile'])->name('profile.update');
        Route::post('/admins', [AdminPortalController::class, 'storeAdmin'])->name('admins.store');
        Route::get('/products', [AdminPortalController::class, 'products'])->name('products');
        Route::get('/shops', [AdminPortalController::class, 'shops'])->name('shops');
        Route::get('/subscriptions', [AdminPortalController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/support', [AdminPortalController::class, 'support'])->name('support');
        Route::get('/support/threads', [AdminPortalController::class, 'supportThreads'])->name('support.threads');
        Route::get('/support/threads/{thread}', [AdminPortalController::class, 'supportThread'])->name('support.thread');
        Route::post('/support/threads/{thread}/reply', [AdminPortalController::class, 'replyToSupportThread'])->name('support.reply');
        Route::get('/emails', [AdminPortalController::class, 'emails'])->name('emails');
        Route::get('/barcodes', [AdminPortalController::class, 'barcodes'])->name('barcodes');
        Route::post('/barcodes', [AdminPortalController::class, 'storeBarcode'])->name('barcodes.store');
        Route::get('/barcodes/{barcode}/download', [AdminPortalController::class, 'downloadBarcode'])->name('barcodes.download');
        Route::delete('/barcodes/{barcode}', [AdminPortalController::class, 'destroyBarcode'])->name('barcodes.destroy');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});

Route::get('/mockups', [MockupController::class, 'index'])->name('mockups.index');
Route::get('/mockups/{slug}', [MockupController::class, 'show'])->name('mockups.show');
Route::get('/map', [MapController::class, 'index'])->name('map.index');
Route::get('/prices', [PriceController::class, 'index'])->name('prices.index');
Route::get('/live', [LiveController::class, 'index'])->name('live.index');
Route::get('/prices/{slug}', [PriceController::class, 'show'])->name('prices.show');
Route::get('/caller', [CallerController::class, 'index'])->name('caller.index');
Route::view('/about', 'about')->name('about');
Route::view('/contact-us', 'contact-us')->name('contact-us');
Route::view('/faq', 'faq')->name('faq');
Route::view('/terms-and-condition', 'terms-and-condition')->name('terms-and-condition');
Route::view('/privacy-and-policy', 'privacy-and-policy')->name('privacy-and-policy');
Route::view('/delivery-coming-soon', 'delivery-coming-soon')->name('delivery-coming-soon');
Route::get('/support/live-chat', [\App\Http\Controllers\SupportChatController::class, 'bootstrap'])->name('support.chat');
Route::post('/support/live-chat/start', [\App\Http\Controllers\SupportChatController::class, 'start'])->name('support.chat.start');
Route::post('/support/live-chat/messages', [\App\Http\Controllers\SupportChatController::class, 'store'])->name('support.chat.store');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/auth', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/auth/login', function () {
        return redirect()->route('login');
    })->name('auth.login.form');

    Route::get('/auth/register', function () {
        return view('auth.login');
    })->name('register');

    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/react-auth/login', [ReactAuthController::class, 'login'])->name('react-auth.login');
    Route::post('/react-auth/register', [ReactAuthController::class, 'register'])->name('react-auth.register');
});

Route::get('/react-auth/bootstrap', [ReactAuthController::class, 'bootstrap'])->name('react-auth.bootstrap');

Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout.get');

Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');
Route::post('/react-auth/logout', [ReactAuthController::class, 'logout'])->middleware('auth')->name('react-auth.logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/location', [ProfileController::class, 'updateLocation'])->name('profile.location');
    Route::get('/profile/social-links', [ProfileController::class, 'socialLinks'])->name('profile.social-links');
    Route::get('/profile/social-links/{channel}', [ProfileController::class, 'socialChannel'])->name('profile.social-channel');
    Route::get('/profile/wishlist', [ProfileController::class, 'wishlist'])->name('profile.wishlist');
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::get('/profile/addresses', [ProfileController::class, 'addresses'])->name('profile.addresses');
    Route::get('/profile/payment-methods', [ProfileController::class, 'paymentMethods'])->name('profile.payment-methods');
    Route::get('/profile/notifications', [ProfileController::class, 'notifications'])->name('profile.notifications');
    Route::get('/profile/notifications/summary', [ProfileController::class, 'notificationsSummary'])->name('profile.notifications.summary');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{message}/image', [MessageController::class, 'destroyImage'])->name('messages.image.destroy');

    // Vendor routes
    Route::get('/calls', [CallController::class, 'index'])->name('calls.index');
    Route::get('/caller/data', [CallerController::class, 'data'])->name('caller.data');
    Route::get('/react-caller/dashboard', [ReactCallerController::class, 'dashboard'])->name('react-caller.dashboard');
    Route::post('/react-caller/users/{user}/start', [ReactCallerController::class, 'start'])->name('react-caller.start');
    Route::get('/react-caller/calls/{call}', [ReactCallerController::class, 'show'])->name('react-caller.show');
    Route::get('/react-caller/calls/{call}/poll', [ReactCallerController::class, 'poll'])->name('react-caller.poll');
    Route::post('/react-caller/calls/{call}/accept', [ReactCallerController::class, 'accept'])->name('react-caller.accept');
    Route::post('/react-caller/calls/{call}/offer', [ReactCallerController::class, 'offer'])->name('react-caller.offer');
    Route::post('/react-caller/calls/{call}/answer', [ReactCallerController::class, 'answer'])->name('react-caller.answer');
    Route::post('/react-caller/calls/{call}/candidate', [ReactCallerController::class, 'candidate'])->name('react-caller.candidate');
    Route::post('/react-caller/calls/{call}/connected', [ReactCallerController::class, 'connected'])->name('react-caller.connected');
    Route::post('/react-caller/calls/{call}/end', [ReactCallerController::class, 'end'])->name('react-caller.end');
    Route::get('/vendor/onboarding', [VendorController::class, 'onboarding'])->name('vendor.onboarding');
    Route::post('/vendor/onboarding', [VendorController::class, 'store'])->name('vendor.store');
    Route::post('/vendor/{vendor}/call-online', [CallController::class, 'start'])->name('vendor.call.online');
    Route::get('/vendor/{vendor}/review', [VendorController::class, 'review'])->name('vendor.reviews.create');
    Route::post('/vendor/{vendor}/reviews', [VendorController::class, 'storeReview'])->name('vendor.reviews.store');
    Route::get('/calls/{callInvite}', [CallController::class, 'show'])->name('calls.show');
    Route::get('/calls/{callInvite}/poll', [CallController::class, 'poll'])->name('calls.poll');
    Route::post('/calls/{callInvite}/offer', [CallController::class, 'offer'])->name('calls.offer');
    Route::post('/calls/{callInvite}/answer', [CallController::class, 'answer'])->name('calls.answer');
    Route::post('/calls/{callInvite}/candidate', [CallController::class, 'candidate'])->name('calls.candidate');
    Route::post('/calls/{callInvite}/connected', [CallController::class, 'connect'])->name('calls.connected');
    Route::post('/calls/{callInvite}/end', [CallController::class, 'end'])->name('calls.end');

    Route::middleware('vendor')->group(function () {
        Route::get('/vendor/dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
        Route::get('/vendor/incoming-call', [CallController::class, 'incoming'])->name('vendor.call.incoming');
        Route::post('/vendor/incoming-call/{callInvite}/accept', [CallController::class, 'accept'])->name('vendor.call.accept');
        Route::get('/vendor/orders', [VendorController::class, 'orders'])->name('vendor.orders');
        Route::get('/vendor/orders/{order}', [VendorController::class, 'showOrder'])->name('vendor.orders.show');
        Route::patch('/vendor/orders/{order}/status', [VendorController::class, 'updateOrderStatus'])->name('vendor.orders.status');
        Route::get('/vendor/products', [VendorController::class, 'products'])->name('vendor.products');
        Route::get('/vendor/profile/edit', [VendorController::class, 'editProfile'])->name('vendor.profile.edit');
        Route::put('/vendor/profile', [VendorController::class, 'updateProfile'])->name('vendor.profile.update');
        Route::post('/vendor/live-toggle', [VendorController::class, 'toggleLive'])->name('vendor.live.toggle');
        Route::post('/vendor/live-videos', [VendorController::class, 'storeLiveVideo'])->name('vendor.live-videos.store');
        Route::delete('/vendor/live-videos/{liveVideo}', [VendorController::class, 'destroyLiveVideo'])->name('vendor.live-videos.destroy');
        Route::post('/vendor/boost-shop', [VendorController::class, 'boostShop'])->name('vendor.boost.shop');
        Route::post('/vendor/products/{product}/boost', [VendorController::class, 'boostProduct'])->name('vendor.products.boost');
        Route::get('/vendor/wallet', [VendorFinanceController::class, 'wallet'])->name('vendor.wallet');
        Route::get('/vendor/subscription', [VendorFinanceController::class, 'subscription'])->name('vendor.subscription');
        Route::post('/vendor/subscription', [VendorFinanceController::class, 'updateSubscription'])->name('vendor.subscription.update');
        Route::get('/vendor/add-product', [VendorController::class, 'addProduct'])->name('vendor.add-product');
        Route::post('/vendor/products', [VendorController::class, 'storeProduct'])->name('vendor.store-product');
        Route::get('/vendor/products/{product}/edit', [VendorController::class, 'editProduct'])->name('vendor.products.edit');
        Route::put('/vendor/products/{product}', [VendorController::class, 'updateProduct'])->name('vendor.products.update');
        Route::delete('/vendor/products/{product}', [VendorController::class, 'destroyProduct'])->name('vendor.products.destroy');
    });
});

// Public routes
Route::get('/category/{category}', [ProductController::class, 'category'])->name('category.show');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('/vendor/{vendor}', [VendorController::class, 'show'])->name('vendor.show');
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{slug}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('/street-food', [StreetFoodController::class, 'index'])->name('street-food.index');
