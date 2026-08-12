<?php

namespace App\Http\Controllers;

use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VendorFinanceController extends Controller
{
    protected array $plans = [
        'premium_1m' => ['label' => 'Premium - 1 Month', 'months' => 1, 'price' => 200],
        'premium_3m' => ['label' => 'Premium - 3 Months', 'months' => 3, 'price' => 500],
        'premium_6m' => ['label' => 'Premium - 6 Months', 'months' => 6, 'price' => 700],
        'premium_12m' => ['label' => 'Premium - 1 Year', 'months' => 12, 'price' => 1000],
    ];

    protected function vendor()
    {
        return Auth::user()->vendor;
    }

    public function wallet()
    {
        $vendor = $this->vendor();
        abort_unless($vendor, 403);

        $transactions = $vendor->walletTransactions()->latest()->get();

        return view('vendor.wallet', compact('vendor', 'transactions'));
    }

    public function subscription()
    {
        $vendor = $this->vendor();
        abort_unless($vendor, 403);

        $plans = $this->plans;

        $expiresAt = $vendor->resolvedSubscriptionExpiresAt();
        $isExpired = $expiresAt ? now()->gt($expiresAt) : false;

        return view('vendor.subscription', compact('vendor', 'plans', 'expiresAt', 'isExpired'));
    }

    public function updateSubscription(Request $request, PaystackService $paystack)
    {
        $vendor = $this->vendor();
        abort_unless($vendor, 403);

        $request->validate([
            'subscription_plan' => 'required|in:premium_1m,premium_3m,premium_6m,premium_12m',
        ]);

        $plan = $this->plans[$request->subscription_plan];
        $user = Auth::user();

        if (! $paystack->ready()) {
            return back()->with('error', 'Online payment is temporarily unavailable. Please try again later.');
        }

        $reference = 'SUB-' . $vendor->id . '-' . strtoupper(Str::random(8));

        $gateway = $paystack->initializeTransaction([
            'email' => $user->email,
            'amount' => (int) round($plan['price'] * 100),
            'reference' => $reference,
            'currency' => 'NGN',
            'callback_url' => route('vendor.subscription.callback', ['reference' => $reference]),
            'metadata' => [
                'type' => 'subscription',
                'vendor_id' => $vendor->id,
                'subscription_plan' => $request->subscription_plan,
                'cancel_action' => route('vendor.subscription'),
            ],
        ]);

        if (! $gateway['status']) {
            return back()->with('error', $gateway['message'] ?? 'Could not reach the payment gateway.');
        }

        // Store the plan intent so it can be applied after a successful charge.
        session()->put("paystack_subscription_{$reference}", $request->subscription_plan);

        return redirect()->away($gateway['authorization_url']);
    }

    public function subscriptionCallback(Request $request, PaystackService $paystack, string $reference)
    {
        $vendor = $this->vendor();
        abort_unless($vendor, 403);

        $verification = $paystack->verifyTransaction($reference);

        if (
            $verification['status']
            && ($verification['data']['status'] ?? '') === 'success'
            && ($verification['data']['metadata']['type'] ?? '') === 'subscription'
        ) {
            $planKey = session()->pull("paystack_subscription_{$reference}", 'premium_1m');
            $plan = $this->plans[$planKey];

            $base = $vendor->subscription_expires_at && now()->lt($vendor->subscription_expires_at)
                ? $vendor->subscription_expires_at
                : now();

            $vendor->update([
                'subscription_plan' => $planKey,
                'subscription_status' => 'active',
                'subscription_expires_at' => $base->copy()->addMonths($plan['months']),
            ]);

            return redirect()->route('vendor.subscription')
                ->with('success', 'Subscription activated successfully.');
        }

        return redirect()->route('vendor.subscription')
            ->with('error', 'Subscription payment was not completed.');
    }
}
