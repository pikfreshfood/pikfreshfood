<?php

namespace App\Http\Middleware;

use App\Models\SiteMetric;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->is('admin*', 'storage/*') && Schema::hasTable('site_metrics')) {
            $visitSessionKey = 'tracked_site_visit_' . now()->toDateString();

            if (! $request->session()->has($visitSessionKey)) {
                $metric = SiteMetric::firstOrCreate(
                    ['metric_key' => 'total_site_visits'],
                    ['metric_value' => 0],
                );

                $metric->increment('metric_value');
                $request->session()->put($visitSessionKey, true);
            }
        }

        return $next($request);
    }
}
