<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorFinanceController extends Controller
{
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

        $plans = [
            'premium_1m' => [
                'label' => 'Premium - 1 Month',
                'months' => 1,
                'price' => 200,
            ],
            'premium_3m' => [
                'label' => 'Premium - 3 Months',
                'months' => 3,
                'price' => 500,
            ],
            'premium_6m' => [
                'label' => 'Premium - 6 Months',
                'months' => 6,
                'price' => 700,
            ],
            'premium_12m' => [
                'label' => 'Premium - 1 Year',
                'months' => 12,
                'price' => 1000,
            ],
        ];

        $expiresAt = $vendor->resolvedSubscriptionExpiresAt();
        $isExpired = $expiresAt ? now()->gt($expiresAt) : false;

        return view('vendor.subscription', compact('vendor', 'plans', 'expiresAt', 'isExpired'));
    }

    public function updateSubscription(Request $request)
    {
        $vendor = $this->vendor();
        abort_unless($vendor, 403);

        $request->validate([
            'subscription_plan' => 'required|in:premium_1m,premium_3m,premium_6m,premium_12m',
        ]);

        $months = match ($request->subscription_plan) {
            'premium_1m' => 1,
            'premium_3m' => 3,
            'premium_6m' => 6,
            'premium_12m' => 12,
            default => 1,
        };

        $vendor->update([
            'subscription_plan' => $request->subscription_plan,
            'subscription_status' => 'active',
            'subscription_expires_at' => now()->addMonths($months),
        ]);

        return back()->with('success', 'Subscription upgraded successfully.');
    }
}
