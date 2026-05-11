@extends('admin.layouts.app')

@section('title', 'Admin Dashboard - PikFreshFood')
@section('page_title', 'Dashboard')
@section('page_copy', 'Overview of users, shops, products, subscriptions, and traffic')

@section('styles')
.stats-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 14px;
}
.stat-card {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 14px;
}
.stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.stat-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--accent-soft);
}
.stat-icon svg {
    width: 18px;
    height: 18px;
    stroke: var(--dark-soft);
    fill: none;
    stroke-width: 1.9;
    stroke-linecap: round;
    stroke-linejoin: round;
}
.stat-card small { color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; font-size: 0.72rem; }
.stat-card strong { display: block; font-size: 1.5rem; margin-top: 6px; }
.panel-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 12px;
}
table { width: 100%; border-collapse: collapse; }
th, td { text-align: left; padding: 10px; border-bottom: 1px solid var(--line); font-size: 0.9rem; }
th { color: var(--muted); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; }
.badge { padding: 4px 8px; border-radius: 999px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; }
.badge.admin { background: #e6f1ff; color: #1f4d8f; }
.badge.vendor { background: #e9f8ef; color: #116235; }
.badge.buyer { background: #f4f5f6; color: #4e5d68; }
.quick-links { display: grid; gap: 10px; }
.quick-links a {
    text-decoration: none;
    color: var(--text);
    padding: 12px;
    border-radius: 10px;
    border: 1px solid var(--line);
    background: #fff;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}
.quick-links a svg {
    width: 16px;
    height: 16px;
    stroke: var(--dark-soft);
    fill: none;
    stroke-width: 1.9;
    stroke-linecap: round;
    stroke-linejoin: round;
}
.panel-title {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.panel-title svg {
    width: 18px;
    height: 18px;
    stroke: var(--dark-soft);
    fill: none;
    stroke-width: 1.9;
    stroke-linecap: round;
    stroke-linejoin: round;
}
@media (max-width: 1200px) {
    .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .panel-grid { grid-template-columns: 1fr; }
}
@endsection

@section('content')
<section class="stats-grid">
    <article class="stat-card">
        <div class="stat-top"><small>Total Users</small><span class="stat-icon"><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"></circle><path d="M3.8 18a5.2 5.2 0 0 1 10.4 0"></path><path d="M16 11a2.5 2.5 0 1 0 0-5"></path><path d="M21 18a4.2 4.2 0 0 0-3.2-4.1"></path></svg></span></div>
        <strong>{{ number_format($totalUsers) }}</strong>
    </article>
    <article class="stat-card">
        <div class="stat-top"><small>Total Shops</small><span class="stat-icon"><svg viewBox="0 0 24 24"><path d="M3 10h18"></path><path d="M5 10V5h14v5"></path><path d="M5 10v9h14v-9"></path><path d="M9 19v-5h6v5"></path></svg></span></div>
        <strong>{{ number_format($totalShops) }}</strong>
    </article>
    <article class="stat-card">
        <div class="stat-top"><small>Total Products</small><span class="stat-icon"><svg viewBox="0 0 24 24"><path d="M3 7 12 3l9 4-9 4-9-4Z"></path><path d="M3 12l9 4 9-4"></path><path d="M3 17l9 4 9-4"></path></svg></span></div>
        <strong>{{ number_format($totalProducts) }}</strong>
    </article>
    <article class="stat-card">
        <div class="stat-top"><small>Total Site Visits</small><span class="stat-icon"><svg viewBox="0 0 24 24"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg></span></div>
        <strong>{{ number_format($totalSiteVisits) }}</strong>
    </article>
    <article class="stat-card">
        <div class="stat-top"><small>Total Subscriptions</small><span class="stat-icon"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M3 10h18"></path><path d="M8 15h2"></path></svg></span></div>
        <strong>{{ number_format($totalSubscriptions) }}</strong>
    </article>
</section>

<section class="panel-grid">
    <article class="admin-card">
        <h3 class="panel-title"><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"></circle><path d="M3.8 18a5.2 5.2 0 0 1 10.4 0"></path><path d="M16 11a2.5 2.5 0 1 0 0-5"></path><path d="M21 18a4.2 4.2 0 0 0-3.2-4.1"></path></svg>Recent Users</h3>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined</th>
            </tr>
            </thead>
            <tbody>
            @forelse($recentUsers as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge {{ $user->isAdmin() ? 'admin' : $user->role }}">{{ $user->isAdmin() ? 'admin' : $user->role }}</span></td>
                    <td>{{ $user->created_at?->format('d M, Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No users yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </article>

    <article class="admin-card">
        <h3 class="panel-title"><svg viewBox="0 0 24 24"><path d="M11 3h2v18h-2z"></path><path d="M3 11h18v2H3z"></path></svg>Quick Access</h3>
        <div class="quick-links">
            @if(auth()->user()->hasAdminPermission('profile'))
                <a href="{{ route('admin.profile') }}"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.5"></circle><path d="M5 20a7 7 0 0 1 14 0"></path></svg>Update profile and create admin with roles</a>
            @endif
            @if(auth()->user()->hasAdminPermission('products'))
                <a href="{{ route('admin.products') }}"><svg viewBox="0 0 24 24"><path d="M3 7 12 3l9 4-9 4-9-4Z"></path><path d="M3 12l9 4 9-4"></path><path d="M3 17l9 4 9-4"></path></svg>Manage products and monitoring</a>
            @endif
            @if(auth()->user()->hasAdminPermission('shops'))
                <a href="{{ route('admin.shops') }}"><svg viewBox="0 0 24 24"><path d="M3 10h18"></path><path d="M5 10V5h14v5"></path><path d="M5 10v9h14v-9"></path><path d="M9 19v-5h6v5"></path></svg>Manage shop accounts</a>
            @endif
            @if(auth()->user()->hasAdminPermission('subscriptions'))
                <a href="{{ route('admin.subscriptions') }}"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M3 10h18"></path><path d="M8 15h2"></path></svg>Subscription details</a>
            @endif
            @if(auth()->user()->hasAdminPermission('support'))
                <a href="{{ route('admin.support') }}"><svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"></path></svg>Support center inbox</a>
            @endif
            @if(auth()->user()->hasAdminPermission('emails'))
                <a href="{{ route('admin.emails') }}"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>Email audience list</a>
            @endif
            @if(auth()->user()->hasAdminPermission('barcodes'))
                <a href="{{ route('admin.barcodes') }}"><svg viewBox="0 0 24 24"><path d="M4 7v10"></path><path d="M7 7v10"></path><path d="M11 7v10"></path><path d="M15 7v10"></path><path d="M18 7v10"></path><path d="M20 7v10"></path><path d="M3 7h18"></path><path d="M3 17h18"></path></svg>Create and manage barcodes</a>
            @endif
        </div>
    </article>
</section>
@endsection
