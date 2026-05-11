<?php

namespace App\Http\Controllers;

use App\Models\PeerCall;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReactCallerController extends Controller
{
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

    protected function authorizeParticipant(PeerCall $call): array
    {
        abort_unless(Auth::check(), 403);

        $user = Auth::user();
        $isCaller = $call->caller_id === $user->id;
        $isCallee = $call->callee_id === $user->id;

        abort_unless($isCaller || $isCallee, 403);

        return [$user, $isCaller, $isCallee];
    }

    protected function serializeCall(PeerCall $call, User $viewer): array
    {
        $isCaller = $call->caller_id === $viewer->id;
        $otherUser = $isCaller ? $call->callee : $call->caller;

        return [
            'id' => $call->id,
            'room_name' => $call->room_name,
            'call_type' => $call->call_type,
            'status' => $call->status,
            'role' => $isCaller ? 'caller' : 'callee',
            'peer' => [
                'id' => $otherUser?->id,
                'name' => $otherUser?->name ?? 'User',
                'email' => $otherUser?->email,
            ],
            'created_at_human' => $call->created_at?->diffForHumans(),
        ];
    }

    public function dashboard()
    {
        abort_unless(Auth::check(), 403);

        $user = Auth::user();

        $users = User::query()
            ->whereKeyNot($user->id)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);

        $incomingCalls = PeerCall::query()
            ->with(['caller:id,name,email', 'callee:id,name,email'])
            ->where('callee_id', $user->id)
            ->whereIn('status', ['ringing', 'accepted', 'connected'])
            ->latest()
            ->take(10)
            ->get();

        $recentCalls = PeerCall::query()
            ->with(['caller:id,name,email', 'callee:id,name,email'])
            ->where(function ($query) use ($user) {
                $query->where('caller_id', $user->id)
                    ->orWhere('callee_id', $user->id);
            })
            ->latest()
            ->take(20)
            ->get();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'users' => $users->map(fn (User $other) => [
                'id' => $other->id,
                'name' => $other->name,
                'email' => $other->email,
                'role' => $other->role,
            ])->values(),
            'incoming_calls' => $incomingCalls->map(fn (PeerCall $call) => $this->serializeCall($call, $user))->values(),
            'recent_calls' => $recentCalls->map(fn (PeerCall $call) => $this->serializeCall($call, $user))->values(),
        ]);
    }

    public function start(Request $request, User $user)
    {
        abort_unless(Auth::check(), 403);
        abort_if(Auth::id() === $user->id, 422, 'You cannot call yourself.');

        $validated = $request->validate([
            'type' => ['nullable', 'in:audio,video'],
        ]);

        $callType = $validated['type'] ?? 'audio';

        $call = DB::transaction(function () use ($user, $callType) {
            PeerCall::query()
                ->where(function ($query) use ($user) {
                    $query->where('caller_id', Auth::id())->where('callee_id', $user->id);
                })
                ->orWhere(function ($query) use ($user) {
                    $query->where('caller_id', $user->id)->where('callee_id', Auth::id());
                })
                ->whereIn('status', ['ringing', 'accepted', 'connected'])
                ->update([
                    'status' => 'ended',
                    'ended_at' => now(),
                ]);

            return PeerCall::query()->create([
                'caller_id' => Auth::id(),
                'callee_id' => $user->id,
                'room_name' => sprintf('react-peer-%d-%d-%s', Auth::id(), $user->id, now()->timestamp),
                'call_type' => $callType,
                'status' => 'ringing',
                'offer_sdp' => null,
                'answer_sdp' => null,
                'caller_candidates' => [],
                'callee_candidates' => [],
            ]);
        });

        return response()->json([
            'call' => $this->serializeCall($call->load(['caller:id,name,email', 'callee:id,name,email']), Auth::user()),
        ], 201);
    }

    public function show(PeerCall $call)
    {
        [$user] = $this->authorizeParticipant($call);
        $call->load(['caller:id,name,email', 'callee:id,name,email']);

        $iceServers = [
            [
                'urls' => [
                    'stun:stun.l.google.com:19302',
                    'stun:stun1.l.google.com:19302',
                    'stun:openrelay.metered.ca:80',
                ],
            ],
            [
                'urls' => [
                    'turn:openrelay.metered.ca:80',
                    'turn:openrelay.metered.ca:80?transport=tcp',
                    'turn:openrelay.metered.ca:443',
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

        return response()->json([
            'call' => $this->serializeCall($call, $user),
            'offer_sdp' => $call->offer_sdp,
            'answer_sdp' => $call->answer_sdp,
            'caller_candidates' => $call->caller_candidates ?? [],
            'callee_candidates' => $call->callee_candidates ?? [],
            'ice_servers' => $iceServers,
        ]);
    }

    public function accept(PeerCall $call)
    {
        [, , $isCallee] = $this->authorizeParticipant($call);
        abort_unless($isCallee, 403);

        if ($call->status === 'ringing') {
            $call->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);
        }

        return response()->json([
            'ok' => true,
        ]);
    }

    public function poll(PeerCall $call)
    {
        [$user, $isCaller] = $this->authorizeParticipant($call);
        $call->load(['caller:id,name,email', 'callee:id,name,email']);

        return response()->json([
            'call' => $this->serializeCall($call, $user),
            'offer_sdp' => $call->offer_sdp,
            'answer_sdp' => $call->answer_sdp,
            'peer_candidates' => $isCaller ? ($call->callee_candidates ?? []) : ($call->caller_candidates ?? []),
        ]);
    }

    public function offer(Request $request, PeerCall $call)
    {
        [, $isCaller] = $this->authorizeParticipant($call);
        abort_unless($isCaller, 403);

        $validated = $request->validate([
            'sdp' => ['required', 'array'],
        ]);

        $call->update([
            'offer_sdp' => $this->normalizeSessionDescription($validated['sdp']),
        ]);

        return response()->json(['ok' => true]);
    }

    public function answer(Request $request, PeerCall $call)
    {
        [, , $isCallee] = $this->authorizeParticipant($call);
        abort_unless($isCallee, 403);

        $validated = $request->validate([
            'sdp' => ['required', 'array'],
        ]);

        $call->update([
            'answer_sdp' => $this->normalizeSessionDescription($validated['sdp']),
            'status' => 'connected',
        ]);

        return response()->json(['ok' => true]);
    }

    public function candidate(Request $request, PeerCall $call)
    {
        [, $isCaller, $isCallee] = $this->authorizeParticipant($call);

        $validated = $request->validate([
            'candidate' => ['required', 'array'],
        ]);

        if ($isCaller) {
            $candidates = $call->caller_candidates ?? [];
            $candidates[] = $validated['candidate'];
            $call->update(['caller_candidates' => $candidates]);
        }

        if ($isCallee) {
            $candidates = $call->callee_candidates ?? [];
            $candidates[] = $validated['candidate'];
            $call->update(['callee_candidates' => $candidates]);
        }

        return response()->json(['ok' => true]);
    }

    public function connected(PeerCall $call)
    {
        $this->authorizeParticipant($call);

        if ($call->status !== 'ended') {
            $call->update(['status' => 'connected']);
        }

        return response()->json(['ok' => true]);
    }

    public function end(PeerCall $call)
    {
        $this->authorizeParticipant($call);

        $call->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }
}
