@extends('admin.layouts.app')

@section('title', 'Admin Profile - PikFreshFood')
@section('page_title', 'Profile & Admin Roles')
@section('page_copy', 'Update your profile and create new admin accounts with roles')

@section('styles')
.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.form-card { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 16px; }
.form-card h3 { margin-bottom: 10px; }
.card-title {
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-title svg {
    width: 18px;
    height: 18px;
    stroke: var(--dark-soft);
    fill: none;
    stroke-width: 1.9;
    stroke-linecap: round;
    stroke-linejoin: round;
}
.field { margin-bottom: 11px; }
label { display: block; margin-bottom: 6px; font-size: 0.85rem; font-weight: 700; color: var(--muted); }
input, select {
    width: 100%;
    min-height: 42px;
    border: 1px solid var(--line);
    border-radius: 10px;
    padding: 0 10px;
}
button {
    min-height: 42px;
    border-radius: 10px;
    border: 0;
    background: var(--dark-soft);
    color: #fff;
    font-weight: 800;
    cursor: pointer;
    padding: 0 14px;
}
table { width: 100%; border-collapse: collapse; margin-top: 14px; }
th, td { text-align: left; padding: 9px; border-bottom: 1px solid var(--line); font-size: 0.88rem; }
th { color: var(--muted); font-size: 0.76rem; text-transform: uppercase; }
@media (max-width: 1024px) { .grid { grid-template-columns: 1fr; } }
@endsection

@section('content')
<section class="grid">
    <article class="form-card">
        <h3 class="card-title"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.5"></circle><path d="M5 20a7 7 0 0 1 14 0"></path></svg>Update Profile</h3>
        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PUT')
            <div class="field">
                <label for="name">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
            </div>
            <div class="field">
                <label for="phone">Phone</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
            </div>
            <div class="field">
                <label for="address">Address</label>
                <input id="address" type="text" name="address" value="{{ old('address', auth()->user()->address) }}">
            </div>
            <button type="submit">Save Profile</button>
        </form>
    </article>

    <article class="form-card">
        <h3 class="card-title"><svg viewBox="0 0 24 24"><path d="M11 3h2v18h-2z"></path><path d="M3 11h18v2H3z"></path></svg>Create Admin With Role</h3>
        @if(auth()->user()->adminRole() === 'super_admin')
            <form method="POST" action="{{ route('admin.admins.store') }}">
                @csrf
                <div class="field">
                    <label for="admin_name">Name</label>
                    <input id="admin_name" type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="field">
                    <label for="admin_email">Email</label>
                    <input id="admin_email" type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="field">
                    <label for="admin_role">Role</label>
                    <select id="admin_role" name="admin_role" required>
                        <option value="super_admin">Super Admin</option>
                        <option value="manager">Manager</option>
                        <option value="support">Support</option>
                        <option value="finance">Finance</option>
                    </select>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>
                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>
                <button type="submit">Create Admin</button>
            </form>
        @else
            <p style="color:var(--muted);line-height:1.6;">Only super admins can create new admin users.</p>
        @endif
    </article>
</section>

<article class="form-card" style="margin-top:12px;">
    <h3 class="card-title"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><path d="M20 8v6"></path><path d="M23 11h-6"></path></svg>Current Admin Accounts</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
        @forelse($adminUsers as $admin)
            <tr>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td>{{ str_replace('_', ' ', $admin->admin_role ?: 'admin') }}</td>
                <td>{{ $admin->created_at?->format('d M, Y H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No admin users found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px;">{{ $adminUsers->links() }}</div>
</article>
@endsection
