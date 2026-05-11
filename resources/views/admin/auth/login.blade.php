<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - PikFreshFood</title>
    <style>
        :root {
            --bg: #0a1022;
            --card: #111a33;
            --line: #24365f;
            --text: #edf2ff;
            --muted: #9fb0d9;
            --accent: #38bdf8;
            --accent-2: #1d4ed8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: radial-gradient(circle at top right, #1d3b8b 0%, var(--bg) 56%);
            color: var(--text);
            font-family: "Segoe UI", Tahoma, sans-serif;
            padding: 18px;
        }
        .login-card {
            width: min(460px, 100%);
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 20px;
            box-shadow: 0 26px 50px rgba(0, 0, 0, 0.35);
            padding: 28px;
        }
        .eyebrow { color: var(--accent); font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.76rem; }
        h1 { margin-top: 8px; margin-bottom: 8px; font-size: 1.6rem; }
        p { color: var(--muted); margin-bottom: 20px; line-height: 1.5; }
        .field { margin-bottom: 14px; }
        label { display: block; margin-bottom: 6px; font-weight: 700; font-size: 0.88rem; }
        input {
            width: 100%;
            min-height: 44px;
            border-radius: 10px;
            border: 1px solid #2d4374;
            background: #0c142b;
            color: var(--text);
            padding: 0 12px;
        }
        .row { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin: 12px 0 20px; }
        .remember { display: inline-flex; align-items: center; gap: 8px; color: var(--muted); font-size: 0.9rem; }
        .remember input { width: auto; min-height: 0; }
        button {
            width: 100%;
            min-height: 46px;
            border-radius: 12px;
            border: 0;
            background: linear-gradient(135deg, var(--accent), #60a5fa);
            color: #081226;
            font-weight: 800;
            cursor: pointer;
        }
        .error { margin-top: 12px; color: #ff9b9b; font-size: 0.88rem; }
        .back-link { display: inline-block; margin-top: 18px; color: var(--muted); text-decoration: none; font-size: 0.88rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="eyebrow">PikFreshFood Admin</div>
        <h1>Sign in to Admin Portal</h1>
        <p>Manage users, shops, products, subscriptions, support, and platform operations from one place.</p>

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="field">
                <label for="login">Username or Email</label>
                <input id="login" type="text" name="login" value="{{ old('login') }}" required autocomplete="username">
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
            </div>
            <div class="row">
                <label class="remember"><input type="checkbox" name="remember" value="1"> Keep me signed in</label>
            </div>
            <button type="submit">Login to Admin</button>
        </form>

        <p style="margin-top:12px;margin-bottom:0;font-size:0.82rem;">
            Default full access login: <strong>admin</strong> / <strong>admin</strong>
        </p>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <a href="{{ route('home') }}" class="back-link">Back to website</a>
    </div>
</body>
</html>
