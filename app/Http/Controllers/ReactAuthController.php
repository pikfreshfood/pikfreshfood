<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CallInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ReactAuthController extends Controller
{
    protected function endActiveCalls($user)
    {
        if (!$user) return;

        CallInvite::query()
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id);
                if ($user->isVendor() && $user->vendor) {
                    $query->orWhere('vendor_id', $user->vendor->id);
                }
            })
            ->whereIn('status', ['ringing', 'accepted', 'connected'])
            ->update([
                'status' => 'ended',
                'ended_at' => now(),
            ]);
    }

    protected function payload(Request $request): array
    {
        $user = $request->user();

        return [
            'authenticated' => (bool) $user,
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ] : null,
            'csrf_token' => csrf_token(),
        ];
    }

    public function bootstrap(Request $request)
    {
        return response()->json($this->payload($request));
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $loginValue = trim($validated['login']);
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = [
            $field => $loginValue,
            'password' => $validated['password'],
        ];

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'The provided credentials do not match our records.',
            ], 422);
        }

        $user = Auth::user();
        $this->endActiveCalls($user);
        $request->session()->regenerate();

        return response()->json($this->payload($request));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:7', 'max:20', 'unique:users,phone'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $normalizedPhone = preg_replace('/\D+/', '', (string) $validated['phone']) ?: (string) time();
        $email = $validated['email'] ?: ('phone_' . $normalizedPhone . '@pikfreshfood.local');

        $user = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $email,
            'password' => $validated['password'],
            'role' => 'buyer',
        ]);

        Auth::login($user);
        $this->endActiveCalls($user);
        $request->session()->regenerate();

        return response()->json($this->payload($request), 201);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $this->endActiveCalls($user);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'authenticated' => false,
            'user' => null,
            'csrf_token' => csrf_token(),
        ]);
    }
}
