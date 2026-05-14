<?php

namespace App\Http\Controllers;

use App\Models\CallInvite;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class CallerController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $payload = $this->buildCallerPayload();
        $payload['livekitUrl'] = config('services.livekit.url');
        $payload['livekitApiKey'] = config('services.livekit.api_key');
        $payload['livekitReady'] = filled(config('services.livekit.url'))
            && filled(config('services.livekit.api_key'))
            && filled(config('services.livekit.api_secret'));

        return view('caller.index', $payload);
    }

    protected function buildCallerPayload(): array
    {
        abort_unless(Auth::check(), 403);

        $user = Auth::user();

        $vendors = Vendor::query()
            ->where('user_id', '!=', $user->id)
            ->orderByDesc('is_live')
            ->orderBy('shop_name')
            ->get();
        $incomingCalls = collect();
        $recentCalls = collect();

        if ($user->isBuyer()) {
            $recentCalls = CallInvite::query()
                ->with('vendor', 'buyer.vendor')
                ->where('buyer_id', $user->id)
                ->latest()
                ->take(10)
                ->get();
        }

        if ($user->isVendor() && $user->vendor) {
            $incomingCalls = CallInvite::query()
                ->with('buyer.vendor')
                ->where('vendor_id', $user->vendor->id)
                ->whereIn('status', ['ringing', 'accepted', 'connected'])
                ->latest()
                ->take(10)
                ->get();

            $recentCalls = CallInvite::query()
                ->with('vendor', 'buyer.vendor')
                ->where(function ($query) use ($user) {
                    $query->where('vendor_id', $user->vendor->id)
                        ->orWhere('buyer_id', $user->id);
                })
                ->latest()
                ->take(10)
                ->get();
        }

        return [
            'viewer' => $user,
            'vendors' => $vendors,
            'incomingCalls' => $incomingCalls,
            'recentCalls' => $recentCalls,
        ];
    }

    protected function participantName($user, string $fallback = 'Caller'): string
    {
        if (! $user) {
            return $fallback;
        }

        if ($user->isVendor() && $user->vendor) {
            return $user->vendor->shop_name ?: ($user->name ?? 'Vendor');
        }

        return $user->name ?? $fallback;
    }

    public function data()
    {
        if (! Auth::check()) {
            return response()->json([
                'message' => 'Authentication required.',
                'login_url' => route('login'),
            ], Response::HTTP_UNAUTHORIZED);
        }

        $payload = $this->buildCallerPayload();
        $viewer = $payload['viewer'];

        return response()->json([
            'viewer' => [
                'id' => $viewer->id,
                'name' => $viewer->name,
                'role' => $viewer->role,
                'is_buyer' => $viewer->isBuyer(),
                'is_vendor' => $viewer->isVendor(),
            ],
            'vendors' => $payload['vendors']->map(function (Vendor $vendor) {
                return [
                    'id' => $vendor->id,
                    'shop_name' => $vendor->shop_name,
                    'address' => $vendor->address,
                    'is_live' => (bool) $vendor->is_live,
                    'call_url' => route('vendor.call.online', ['vendor' => $vendor], false),
                    'vendor_url' => route('vendor.show', ['vendor' => $vendor], false),
                ];
            })->values(),
            'incomingCalls' => $payload['incomingCalls']->map(function (CallInvite $call) {
                return [
                    'id' => $call->id,
                    'buyer_name' => $this->participantName($call->buyer, 'Caller'),
                    'caller_name' => $this->participantName($call->buyer, 'Caller'),
                    'call_type' => $call->call_type ?? 'audio',
                    'status' => $call->status,
                    'accept_url' => route('vendor.call.accept', ['callInvite' => $call], false),
                    'call_url' => route('calls.show', ['callInvite' => $call], false),
                ];
            })->values(),
            'recentCalls' => $payload['recentCalls']->map(function (CallInvite $call) use ($viewer) {
                $viewerIsCaller = $call->buyer_id === $viewer->id;

                return [
                    'id' => $call->id,
                    'with_name' => $viewerIsCaller
                        ? ($call->vendor->shop_name ?? 'Vendor')
                        : $this->participantName($call->buyer, 'Caller'),
                    'call_type' => $call->call_type ?? 'audio',
                    'status' => $call->status,
                    'created_at_human' => $call->created_at?->diffForHumans(),
                    'call_url' => route('calls.show', ['callInvite' => $call], false),
                ];
            })->values(),
            'csrf_token' => csrf_token(),
            'base_url' => url('/'),
        ]);
    }
}
