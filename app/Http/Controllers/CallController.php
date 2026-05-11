<?php

namespace App\Http\Controllers;

use App\Models\CallInvite;
use App\Models\Vendor;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CallController extends Controller
{
    protected function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    protected function generateLiveKitToken(string $identity, string $name, string $roomName): ?string
    {
        $apiKey = (string) config('services.livekit.api_key');
        $apiSecret = (string) config('services.livekit.api_secret');

        if ($apiKey === '' || $apiSecret === '') {
            return null;
        }

        $issuedAt = time();
        $payload = [
            'iss' => $apiKey,
            'sub' => $identity,
            'name' => $name,
            'nbf' => $issuedAt,
            'iat' => $issuedAt,
            'exp' => $issuedAt + 60 * 60 * 6,
            'video' => [
                'room' => $roomName,
                'roomJoin' => true,
                'canPublish' => true,
                'canSubscribe' => true,
                'canPublishData' => true,
            ],
        ];

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        $encodedHeader = $this->base64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $signature = hash_hmac('sha256', $encodedHeader.'.'.$encodedPayload, $apiSecret, true);

        return $encodedHeader.'.'.$encodedPayload.'.'.$this->base64UrlEncode($signature);
    }

    protected function normalizeSessionDescription(array $description): array
    {
        $sdp = (string) ($description['sdp'] ?? '');
        $sdp = str_replace("\r\n", "\n", $sdp);
        $sdp = str_replace("\r", "\n", $sdp);
        $sdp = preg_replace("/^\xEF\xBB\xBF/", '', $sdp) ?? $sdp;
        $sdp = trim($sdp);

        if ($sdp !== '') {
            $sdp = preg_replace("/\n+/", "\r\n", $sdp) ?? $sdp;
            if (! str_ends_with($sdp, "\r\n")) {
                $sdp .= "\r\n";
            }
        }

        return [
            'type' => (string) ($description['type'] ?? ''),
            'sdp' => $sdp,
        ];
    }

    public function index(Request $request)
    {
        abort_unless(Auth::check(), 403);

        $user = Auth::user();
        $mode = $request->string('mode')->lower()->value() ?: 'audio';
        $mode = in_array($mode, ['audio', 'video'], true) ? $mode : 'audio';

        $calls = CallInvite::query()
            ->with('vendor.user', 'buyer.vendor')
            ->where('call_type', $mode)
            ->when($user->isVendor() && $user->vendor, function ($query) use ($user) {
                $query->where('vendor_id', $user->vendor->id);
            }, function ($query) use ($user) {
                $query->where('buyer_id', $user->id);
            })
            ->latest()
            ->get();

        return view('calls.index', [
            'calls' => $calls,
            'mode' => $mode,
        ]);
    }

    protected function authorizeParticipant(CallInvite $callInvite): array
    {
        abort_unless(Auth::check(), 403);

        $user = Auth::user();
        $isBuyer = $callInvite->buyer_id === $user->id;
        $isVendor = $user->isVendor() && $user->vendor && $callInvite->vendor_id === $user->vendor->id;

        abort_unless($isBuyer || $isVendor, 403);

        return [$user, $isBuyer, $isVendor];
    }

    public function start(Request $request, Vendor $vendor)
    {
        if (! Auth::check()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Please log in before starting a call.'], 401)
                : redirect()->route('login');
        }

        if (! Auth::user()->isBuyer()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Only buyer accounts can start calls to vendors.'], 403)
                : abort(403, 'Only buyer accounts can start calls to vendors.');
        }

        if (Auth::id() === $vendor->user_id) {
            return $request->expectsJson()
                ? response()->json(['message' => 'You cannot call your own vendor account.'], 403)
                : abort(403, 'You cannot call your own vendor account.');
        }

        $validated = $request->validate([
            'type' => 'nullable|in:audio,video',
        ]);

        $callType = $validated['type'] ?? 'audio';

        try {
            $callInvite = DB::transaction(function () use ($vendor, $callType) {
                CallInvite::query()
                    ->where('vendor_id', $vendor->id)
                    ->where('buyer_id', Auth::id())
                    ->whereIn('status', ['ringing', 'accepted', 'connected'])
                    ->update([
                        'status' => 'ended',
                        'ended_at' => now(),
                    ]);

                return CallInvite::query()->create([
                    'vendor_id' => $vendor->id,
                    'buyer_id' => Auth::id(),
                    'room_name' => sprintf('pikfresh-vendor-%d-buyer-%d-%s', $vendor->id, Auth::id(), now()->timestamp),
                    'call_type' => $callType,
                    'status' => 'ringing',
                    'offer_sdp' => null,
                    'answer_sdp' => null,
                    'buyer_candidates' => [],
                    'vendor_candidates' => [],
                    'accepted_at' => null,
                    'ended_at' => null,
                ]);
            });
        } catch (Throwable $exception) {
            Log::error('Unable to start vendor call.', [
                'vendor_id' => $vendor->id,
                'buyer_id' => Auth::id(),
                'call_type' => $callType,
                'exception' => $exception,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'The call could not be created on the server. Please check the Laravel log and database migrations.',
                ], 500);
            }

            throw $exception;
        }

        $callUrl = route('calls.show', ['callInvite' => $callInvite], false);

        if ($request->expectsJson()) {
            return response()->json([
                'call_url' => $callUrl,
                'call_type' => $callType,
            ]);
        }

        return redirect()->to($callUrl);
    }

    public function show(CallInvite $callInvite)
    {
        [$user, $isBuyer, $isVendor] = $this->authorizeParticipant($callInvite);

        $callInvite->load('vendor.user', 'buyer');
        $livekitUrl = (string) config('services.livekit.url');
        $livekitReady = $livekitUrl !== ''
            && filled(config('services.livekit.api_key'))
            && filled(config('services.livekit.api_secret'));
        $identity = $isBuyer
            ? 'buyer-'.$user->id
            : 'vendor-'.($user->vendor->id ?? $user->id);
        $participantName = $isBuyer
            ? ($user->name ?? 'Buyer')
            : ($user->vendor->shop_name ?? $user->name ?? 'Vendor');
        $livekitToken = $livekitReady
            ? $this->generateLiveKitToken($identity, $participantName, $callInvite->room_name)
            : null;

        $iceServers = [
            [
                'urls' => [
                    'stun:stun.l.google.com:19302',
                    'stun:stun1.l.google.com:19302',
                    'stun:stun2.l.google.com:19302',
                    'stun:stun3.l.google.com:19302',
                    'stun:stun4.l.google.com:19302',
                    'stun:stun.nextcloud.com:3478',
                    'stun:stun.coturn.net:3478',
                    'stun:stun.cloudflare.com:3478',
                    'stun:stun.twilio.com:3478',
                ],
            ],
            [
                'urls' => [
                    'turn:openrelay.metered.ca:80',
                    'turn:openrelay.metered.ca:3478',
                    'turn:openrelay.metered.ca:443',
                    'turn:openrelay.metered.ca:80?transport=tcp',
                    'turn:openrelay.metered.ca:443?transport=tcp',
                ],
                'username' => 'openrelayproject',
                'credential' => 'openrelayproject',
            ],
        ];

        if (filled(env('WEBRTC_TURN_URL')) && filled(env('WEBRTC_TURN_USERNAME')) && filled(env('WEBRTC_TURN_CREDENTIAL'))) {
            $iceServers[] = [
                'urls' => env('WEBRTC_TURN_URL'),
                'username' => env('WEBRTC_TURN_USERNAME'),
                'credential' => env('WEBRTC_TURN_CREDENTIAL'),
            ];
        }

        return view('calls.show', [
            'callInvite' => $callInvite,
            'isBuyer' => $isBuyer,
            'isVendor' => $isVendor,
            'currentUser' => $user,
            'iceServers' => $iceServers,
            'callType' => $callInvite->call_type ?: 'audio',
            'livekitReady' => $livekitReady && $livekitToken,
            'livekitUrl' => $livekitUrl,
            'livekitToken' => $livekitToken,
            'livekitIdentity' => $identity,
            'participantName' => $participantName,
        ]);
    }

    public function poll(CallInvite $callInvite)
    {
        [, $isBuyer, $isVendor] = $this->authorizeParticipant($callInvite);
        $callInvite->load('buyer');

        return response()->json([
            'id' => $callInvite->id,
            'status' => $callInvite->status,
            'offer_sdp' => $callInvite->offer_sdp,
            'answer_sdp' => $callInvite->answer_sdp,
            'buyer_candidates' => $callInvite->buyer_candidates ?? [],
            'vendor_candidates' => $callInvite->vendor_candidates ?? [],
            'room_name' => $callInvite->room_name,
            'call_type' => $callInvite->call_type ?: 'audio',
            'peer_name' => $isBuyer
                ? ($callInvite->vendor->shop_name ?? 'Vendor')
                : ($callInvite->buyer?->name ?? 'Buyer'),
            'role' => $isBuyer ? 'buyer' : 'vendor',
        ]);
    }

    public function incoming()
    {
        abort_unless(Auth::check() && Auth::user()->isVendor() && Auth::user()->vendor, 403);

        $invite = CallInvite::query()
            ->with('buyer')
            ->where('vendor_id', Auth::user()->vendor->id)
            ->where('status', 'ringing')
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
                'call_type' => $invite->call_type ?: 'audio',
                'call_url' => route('calls.show', ['callInvite' => $invite], false),
            ],
        ]);
    }

    public function accept(CallInvite $callInvite)
    {
        [, , $isVendor] = $this->authorizeParticipant($callInvite);
        abort_unless($isVendor, 403);

        $callInvite->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        return response()->json([
            'call_url' => route('calls.show', ['callInvite' => $callInvite], false),
            'call_type' => $callInvite->call_type ?: 'audio',
        ]);
    }

    public function offer(Request $request, CallInvite $callInvite)
    {
        [, $isBuyer] = $this->authorizeParticipant($callInvite);
        abort_unless($isBuyer, 403);

        $validated = $request->validate([
            'sdp' => 'required|array',
        ]);

        $callInvite->update([
            'offer_sdp' => $this->normalizeSessionDescription($validated['sdp']),
        ]);

        return response()->json(['ok' => true]);
    }

    public function answer(Request $request, CallInvite $callInvite)
    {
        [, , $isVendor] = $this->authorizeParticipant($callInvite);
        abort_unless($isVendor, 403);

        $validated = $request->validate([
            'sdp' => 'required|array',
        ]);

        $callInvite->update([
            'answer_sdp' => $this->normalizeSessionDescription($validated['sdp']),
            'status' => 'connected',
        ]);

        return response()->json(['ok' => true]);
    }

    public function connect(CallInvite $callInvite)
    {
        $this->authorizeParticipant($callInvite);

        if ($callInvite->status !== 'ended') {
            $callInvite->update([
                'status' => 'connected',
            ]);
        }

        return response()->json(['ok' => true]);
    }

    public function candidate(Request $request, CallInvite $callInvite)
    {
        [, $isBuyer, $isVendor] = $this->authorizeParticipant($callInvite);

        $validated = $request->validate([
            'candidate' => 'required|array',
        ]);

        if ($isBuyer) {
            $candidates = $callInvite->buyer_candidates ?? [];
            $candidates[] = $validated['candidate'];
            $callInvite->update(['buyer_candidates' => $candidates]);
        }

        if ($isVendor) {
            $candidates = $callInvite->vendor_candidates ?? [];
            $candidates[] = $validated['candidate'];
            $callInvite->update(['vendor_candidates' => $candidates]);
        }

        return response()->json(['ok' => true]);
    }

    public function end(CallInvite $callInvite)
    {
        $this->authorizeParticipant($callInvite);

        $callInvite->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }
}
