<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CallInvite;

class AuthController extends Controller
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

    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required|string|max:255',
            'password' => 'required',
        ]);

        $loginValue = trim($validated['login']);
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = [
            $field => $loginValue,
            'password' => $validated['password'],
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $this->endActiveCalls($user);
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:7|max:20|unique:users,phone',
            'email' => 'nullable|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:buyer,vendor',
            'terms_accepted' => 'accepted',
        ]);

        $normalizedPhone = preg_replace('/\D+/', '', (string) $validated['phone']) ?: (string) time();
        $email = $validated['email'] ?: ('phone_' . $normalizedPhone . '@pikfreshfood.local');

        $user = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $email,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        Auth::login($user);
        $this->endActiveCalls($user);

        if ($user->isVendor()) {
            return redirect()->route('vendor.onboarding');
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $this->endActiveCalls($user);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
