<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SiteMetric;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->hasAdminPermission('dashboard'), 403);

        $totalUsers = User::query()->count();
        $totalShops = Vendor::query()->count();
        $totalProducts = Product::query()->count();
        $totalSiteVisits = (int) SiteMetric::query()
            ->where('metric_key', 'total_site_visits')
            ->value('metric_value');

        $totalSubscriptions = Vendor::query()
            ->whereIn('subscription_plan', ['premium_3m', 'premium_6m', 'premium_12m'])
            ->count();

        $recentUsers = User::query()
            ->latest()
            ->limit(6)
            ->get(['id', 'name', 'email', 'role', 'created_at']);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalShops',
            'totalProducts',
            'totalSiteVisits',
            'totalSubscriptions',
            'recentUsers',
        ));
    }
}
