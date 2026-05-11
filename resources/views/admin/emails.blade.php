@extends('admin.layouts.app')

@section('title', 'Admin Emails - PikFreshFood')
@section('page_title', 'Emails')
@section('page_copy', 'User email directory for announcements and campaigns')

@section('styles')
.panel { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 14px; }
table { width: 100%; border-collapse: collapse; }
th, td { text-align: left; padding: 10px; border-bottom: 1px solid var(--line); font-size: 0.88rem; }
th { color: var(--muted); font-size: 0.76rem; text-transform: uppercase; }
.role { padding: 4px 8px; border-radius: 999px; font-size: 0.74rem; font-weight: 800; text-transform: uppercase; background: #eef2f5; color: #4d5a64; }
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
@endsection

@section('content')
<article class="panel">
    <h3 class="panel-title"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>Email Directory</h3>
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
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="role">{{ $user->isAdmin() ? 'admin' : $user->role }}</span></td>
                    <td>{{ $user->created_at?->format('d M, Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px;">{{ $users->links() }}</div>
</article>
@endsection
