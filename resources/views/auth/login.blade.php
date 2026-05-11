@extends('layouts.app')

@section('title', 'Login - PikFreshFood')

@section('styles')
<style>
    .auth-container { max-width: 400px; margin: 40px auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .auth-container h1 { text-align: center; color: #27ae60; margin-bottom: 30px; }
    .auth-form { display: flex; flex-direction: column; }
    .auth-input, .auth-select { width: 100%; margin-bottom: 15px; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
    .auth-password-field { position: relative; width: 100%; margin-bottom: 15px; }
    .auth-password-field .auth-input { margin-bottom: 0; padding-right: 54px; }
    .auth-password-toggle {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border: 1px solid #d7e6dc;
        border-radius: 999px;
        background: #f3fbf6;
        color: #1f7a43;
        box-shadow: 0 1px 2px rgba(0,0,0,0.08);
        cursor: pointer;
        padding: 0;
        z-index: 2;
    }
    .auth-password-toggle:hover { background: #e9f8ef; color: #27ae60; }
    .auth-password-toggle:focus-visible { outline: 2px solid #27ae60; outline-offset: 2px; border-radius: 4px; }
    .auth-password-toggle svg {
        width: 19px;
        height: 19px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .auth-password-toggle .eye-off { display: none; }
    .auth-password-toggle.is-visible .eye-on { display: none; }
    .auth-password-toggle.is-visible .eye-off { display: block; }
    .auth-button { background: #27ae60; color: white; padding: 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
    .auth-button:hover { background: #229954; }
    .auth-checkline {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin: -2px 0 14px;
        color: #4a4a4a;
        font-size: 13px;
        line-height: 1.45;
    }
    .auth-checkline input[type="checkbox"] {
        margin-top: 2px;
        width: 16px;
        height: 16px;
        accent-color: #27ae60;
        flex: 0 0 auto;
    }
    .auth-checkline a {
        color: #1f7a43;
        font-weight: 700;
        text-decoration: none;
    }
    .auth-checkline a:hover { text-decoration: underline; }
    .auth-tabs { display: flex; margin-bottom: 20px; }
    .auth-tab { flex: 1; padding: 12px; text-align: center; cursor: pointer; border-radius: 4px 4px 0 0; }
    .auth-tab:not(.active) { background: #d1d5db; color: #000000; }
    .auth-tab.active { background: #27ae60; color: white; }
    .auth-hidden { display: none; }
    .auth-alert { margin-bottom: 16px; padding: 12px 14px; border-radius: 6px; font-size: 14px; }
    .auth-alert.error { background: #fdecea; color: #b3261e; border: 1px solid #f3c4c0; }
    .auth-alert.success { background: #eaf7ef; color: #1f7a43; border: 1px solid #c9e8d3; }

    body.safplace-theme .auth-password-toggle {
        border-color: rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.14);
        color: #ffffff;
    }

    body.safplace-theme .auth-password-toggle:hover {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <h1>PikFreshFood</h1>

    @if ($errors->any())
        <div class="auth-alert error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if (session('success'))
        <div class="auth-alert success">{{ session('success') }}</div>
    @endif

    <div class="auth-tabs">
        <div class="auth-tab active" onclick="switchTab('login')">Login</div>
        <div class="auth-tab" onclick="switchTab('register')">Register</div>
    </div>

    <form id="loginForm" action="{{ route('auth.login') }}" method="POST" class="auth-form">
        @csrf
        <input type="text" name="login" value="{{ old('login') }}" placeholder="Email or Phone Number" class="auth-input" required>
        <div class="auth-password-field">
            <input type="password" name="password" placeholder="Password" class="auth-input" required>
            <button type="button" class="auth-password-toggle" data-toggle-password aria-label="Show password" aria-pressed="false">
                <svg class="eye-on" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <svg class="eye-off" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 3l18 18"></path>
                    <path d="M10.6 10.7a3 3 0 0 0 4.2 4.2"></path>
                    <path d="M9.9 5.1A11.5 11.5 0 0 1 12 5c6.4 0 10 7 10 7a18.7 18.7 0 0 1-4 4.8"></path>
                    <path d="M6.6 6.7C4.1 8.4 2 12 2 12s3.6 7 10 7a9.7 9.7 0 0 0 3-.5"></path>
                </svg>
            </button>
        </div>
        <button type="submit" class="auth-button">Login</button>
    </form>

    <form id="registerForm" class="auth-hidden auth-form" action="{{ route('auth.register') }}" method="POST">
        @csrf
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Full Name" class="auth-input" required>
        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Phone Number" class="auth-input" required>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email (optional, you can add later)" class="auth-input">
        <div class="auth-password-field">
            <input type="password" name="password" placeholder="Password" class="auth-input" required>
            <button type="button" class="auth-password-toggle" data-toggle-password aria-label="Show password" aria-pressed="false">
                <svg class="eye-on" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <svg class="eye-off" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 3l18 18"></path>
                    <path d="M10.6 10.7a3 3 0 0 0 4.2 4.2"></path>
                    <path d="M9.9 5.1A11.5 11.5 0 0 1 12 5c6.4 0 10 7 10 7a18.7 18.7 0 0 1-4 4.8"></path>
                    <path d="M6.6 6.7C4.1 8.4 2 12 2 12s3.6 7 10 7a9.7 9.7 0 0 0 3-.5"></path>
                </svg>
            </button>
        </div>
        <div class="auth-password-field">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" class="auth-input" required>
            <button type="button" class="auth-password-toggle" data-toggle-password aria-label="Show password" aria-pressed="false">
                <svg class="eye-on" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <svg class="eye-off" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 3l18 18"></path>
                    <path d="M10.6 10.7a3 3 0 0 0 4.2 4.2"></path>
                    <path d="M9.9 5.1A11.5 11.5 0 0 1 12 5c6.4 0 10 7 10 7a18.7 18.7 0 0 1-4 4.8"></path>
                    <path d="M6.6 6.7C4.1 8.4 2 12 2 12s3.6 7 10 7a9.7 9.7 0 0 0 3-.5"></path>
                </svg>
            </button>
        </div>
        <select name="role" class="auth-select" required>
            <option value="buyer" {{ old('role') === 'buyer' ? 'selected' : '' }}>Buyer</option>
            <option value="vendor" {{ old('role') === 'vendor' ? 'selected' : '' }}>Vendor</option>
        </select>
        <label class="auth-checkline">
            <input type="checkbox" name="terms_accepted" value="1" {{ old('terms_accepted') ? 'checked' : '' }} required>
            <span>I agree to the <a href="{{ route('terms-and-condition') }}" target="_blank" rel="noopener">Terms and Condition</a>.</span>
        </label>
        <button type="submit" class="auth-button">Register</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function switchTab(tab) {
        document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.auth-form').forEach(f => f.classList.add('auth-hidden'));
        if (tab === 'login') {
            document.querySelector('.auth-tab:first-child').classList.add('active');
            document.getElementById('loginForm').classList.remove('auth-hidden');
        } else {
            document.querySelector('.auth-tab:last-child').classList.add('active');
            document.getElementById('registerForm').classList.remove('auth-hidden');
        }
    }

    const shouldShowRegister = window.location.pathname === '/auth/register'
        || {{ $errors->has('name') || $errors->has('phone') || $errors->has('email') || $errors->has('password_confirmation') || $errors->has('role') || $errors->has('terms_accepted') ? 'true' : 'false' }}
        || {{ old('name') || old('phone') || old('role') ? 'true' : 'false' }};

    if (shouldShowRegister) {
        switchTab('register');
    }

    document.querySelectorAll('[data-toggle-password]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const input = toggle.parentElement.querySelector('input');
            const isVisible = input.type === 'text';

            input.type = isVisible ? 'password' : 'text';
            toggle.classList.toggle('is-visible', !isVisible);
            toggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
            toggle.setAttribute('aria-pressed', String(!isVisible));
        });
    });
</script>
@endsection
