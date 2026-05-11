<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal - PikFreshFood')</title>
    <style>
        :root {
            --bg: #eef2ff;
            --card: #ffffff;
            --line: #d4dcf3;
            --text: #16213f;
            --muted: #5f6f9f;
            --dark: #0f1b3d;
            --dark-soft: #1d2f63;
            --accent: #38bdf8;
            --accent-soft: #dff3ff;
            --radius: 14px;
            --sidebar-width: 270px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: "Segoe UI", Tahoma, sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        .admin-shell {
            display: grid;
            grid-template-columns: var(--sidebar-width) 1fr;
            min-height: 100vh;
        }
        .admin-sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            background: linear-gradient(180deg, var(--dark) 0%, var(--dark-soft) 100%);
            color: #e8f2ed;
            padding: 18px 14px;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            overflow-y: auto;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            margin-bottom: 12px;
        }
        .brand-badge {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--accent);
            color: #062031;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .brand small {
            display: block;
            font-size: 0.75rem;
            color: #aec7bb;
        }
        .brand strong {
            font-size: 0.98rem;
            color: #fff;
        }
        .menu {
            display: grid;
            gap: 6px;
            margin-top: 8px;
        }
        .menu a, .menu button {
            width: 100%;
            text-align: left;
            min-height: 42px;
            border-radius: 10px;
            border: 1px solid transparent;
            background: transparent;
            color: #dce9e2;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 12px;
            font-weight: 700;
            cursor: pointer;
        }
        .menu a:hover, .menu button:hover, .menu a.active {
            background: rgba(56, 189, 248, 0.18);
            border-color: rgba(125, 211, 252, 0.36);
            color: #fff;
        }
        .menu-icon {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: rgba(125, 211, 252, 0.25);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
        }
        .menu-icon svg {
            width: 15px;
            height: 15px;
            stroke: #e9f4ff;
            fill: none;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .main {
            min-width: 0;
            padding: 16px 20px 24px;
        }
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 12px 14px;
        }
        .toggle-btn {
            width: 42px;
            height: 42px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
            cursor: pointer;
            font-weight: 800;
        }
        .topbar h1 {
            font-size: 1.2rem;
            margin-bottom: 2px;
        }
        .topbar p {
            color: var(--muted);
            font-size: 0.87rem;
        }
        .admin-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 16px;
        }
        .flash-success {
            margin-bottom: 12px;
            padding: 12px 14px;
            border: 1px solid #8ecae6;
            background: #e5f6ff;
            color: #0d3f6d;
            border-radius: 10px;
            font-weight: 700;
        }
        .error-list {
            margin-bottom: 12px;
            padding: 12px 14px;
            border: 1px solid #f2b6b6;
            background: #fff0f0;
            color: #7d1f1f;
            border-radius: 10px;
        }
        .error-list ul { padding-left: 16px; }
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(9, 17, 24, 0.45);
            z-index: 30;
        }
        @media (max-width: 1024px) {
            .admin-shell {
                grid-template-columns: 1fr;
            }
            .admin-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                width: min(84vw, 300px);
                z-index: 40;
                transform: translateX(-110%);
                transition: transform 0.2s ease;
            }
            body.sidebar-open .admin-sidebar {
                transform: translateX(0);
            }
            body.sidebar-open .sidebar-backdrop {
                display: block;
            }
            .main {
                padding: 14px;
            }
        }
        @media (min-width: 1025px) {
            body.sidebar-collapsed .admin-shell {
                grid-template-columns: 88px 1fr;
            }
            body.sidebar-collapsed .brand > div,
            body.sidebar-collapsed .menu a span,
            body.sidebar-collapsed .menu button span {
                display: none;
            }
            body.sidebar-collapsed .menu a,
            body.sidebar-collapsed .menu button {
                justify-content: center;
                padding: 0;
            }
        }
        @yield('styles')
    </style>
</head>
<body>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    <div class="admin-shell">
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="brand">
                <span class="brand-badge">PF</span>
                <div>
                    <small>Admin Portal</small>
                    <strong>PikFreshFood</strong>
                </div>
            </div>
            <nav class="menu">
                @php($adminUser = auth()->user())
                @if($adminUser && $adminUser->hasAdminPermission('dashboard'))
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="11" width="7" height="10"></rect><rect x="3" y="13" width="7" height="8"></rect></svg></i><span>Dashboard</span></a>
                @endif
                @if($adminUser && $adminUser->hasAdminPermission('profile'))
                    <a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}"><i class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"></circle><path d="M5 20a7 7 0 0 1 14 0"></path></svg></i><span>Profile</span></a>
                @endif
                @if($adminUser && $adminUser->hasAdminPermission('products'))
                    <a href="{{ route('admin.products') }}" class="{{ request()->routeIs('admin.products') ? 'active' : '' }}"><i class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7 12 3l9 4-9 4-9-4Z"></path><path d="M3 12l9 4 9-4"></path><path d="M3 17l9 4 9-4"></path></svg></i><span>Products</span></a>
                @endif
                @if($adminUser && $adminUser->hasAdminPermission('shops'))
                    <a href="{{ route('admin.shops') }}" class="{{ request()->routeIs('admin.shops') ? 'active' : '' }}"><i class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10h18"></path><path d="M5 10V5h14v5"></path><path d="M5 10v9h14v-9"></path><path d="M9 19v-5h6v5"></path></svg></i><span>Shops</span></a>
                @endif
                @if($adminUser && $adminUser->hasAdminPermission('subscriptions'))
                    <a href="{{ route('admin.subscriptions') }}" class="{{ request()->routeIs('admin.subscriptions') ? 'active' : '' }}"><i class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M3 10h18"></path><path d="M8 15h2"></path></svg></i><span>Subscriptions</span></a>
                @endif
                @if($adminUser && $adminUser->hasAdminPermission('support'))
                    <a href="{{ route('admin.support') }}" class="{{ request()->routeIs('admin.support') ? 'active' : '' }}"><i class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"></path></svg></i><span>Support</span></a>
                @endif
                @if($adminUser && $adminUser->hasAdminPermission('emails'))
                    <a href="{{ route('admin.emails') }}" class="{{ request()->routeIs('admin.emails') ? 'active' : '' }}"><i class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg></i><span>Emails</span></a>
                @endif
                @if($adminUser && $adminUser->hasAdminPermission('barcodes'))
                    <a href="{{ route('admin.barcodes') }}" class="{{ request()->routeIs('admin.barcodes*') ? 'active' : '' }}"><i class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7v10"></path><path d="M7 7v10"></path><path d="M11 7v10"></path><path d="M15 7v10"></path><path d="M18 7v10"></path><path d="M20 7v10"></path><path d="M3 7h18"></path><path d="M3 17h18"></path></svg></i><span>Barcodes</span></a>
                @endif
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"><i class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><path d="M10 17l5-5-5-5"></path><path d="M15 12H3"></path></svg></i><span>Logout</span></button>
                </form>
            </nav>
        </aside>

        <main class="main">
            <div class="topbar">
                <button type="button" id="sidebarToggle" class="toggle-btn" aria-label="Toggle sidebar">&#9776;</button>
                <div>
                    <h1>@yield('page_title', 'Admin Portal')</h1>
                    <p>@yield('page_copy', 'Platform control center')</p>
                </div>
                <div class="admin-card" style="padding:10px 12px;background:var(--accent-soft);border-color:#f1e4a4;">
                    {{ auth()->user()->name }} ({{ auth()->user()->adminRole() }})
                </div>
            </div>

            @if(session('success'))
                <div class="flash-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="error-list">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        (function () {
            const toggle = document.getElementById('sidebarToggle');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (!toggle) {
                return;
            }

            const mobileScreen = function () {
                return window.matchMedia('(max-width: 1024px)').matches;
            };

            toggle.addEventListener('click', function () {
                if (mobileScreen()) {
                    document.body.classList.toggle('sidebar-open');
                } else {
                    document.body.classList.toggle('sidebar-collapsed');
                }
            });

            if (backdrop) {
                backdrop.addEventListener('click', function () {
                    document.body.classList.remove('sidebar-open');
                });
            }
        })();
    </script>
    @yield('scripts')
</body>
</html>
