@extends('admin.layouts.app')

@section('title', 'Edit User - PikFreshFood')
@section('page_title', 'User Profile')
@section('page_copy', 'View and update this account profile')

@section('styles')
.form-card { max-width:760px; background:#fff; border:1px solid var(--line); border-radius:var(--radius); padding:18px; }
.grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; } .field { margin-bottom:12px; } .field.full { grid-column:1 / -1; }
label { display:block; margin-bottom:6px; color:var(--muted); font-size:.84rem; font-weight:800; } input,select { width:100%; min-height:42px; border:1px solid var(--line); border-radius:9px; padding:0 10px; font:inherit; }
.checkbox { display:flex; gap:8px; align-items:center; min-height:42px; } .checkbox input { width:auto; } .actions { display:flex; gap:8px; } .button { min-height:42px; border:0; border-radius:9px; padding:0 14px; background:var(--dark-soft); color:#fff; font-weight:800; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; } .button.secondary { background:#eef2f8; color:var(--dark-soft); }
@media(max-width:700px){.grid{grid-template-columns:1fr}.field.full{grid-column:auto}}
@endsection

@section('content')
<article class="form-card">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="grid">
            <div class="field"><label for="name">Full name</label><input id="name" name="name" value="{{ old('name', $user->name) }}" required></div>
            <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required></div>
            <div class="field"><label for="phone">Phone</label><input id="phone" name="phone" value="{{ old('phone', $user->phone) }}"></div>
            <div class="field"><label for="role">Account type</label><select id="role" name="role"><option value="buyer" @selected(old('role', $user->role) === 'buyer')>Buyer</option><option value="vendor" @selected(old('role', $user->role) === 'vendor')>Vendor</option><option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option></select></div>
            <div class="field full"><label for="address">Address</label><input id="address" name="address" value="{{ old('address', $user->address) }}"></div>
            <div class="field"><label for="admin_role">Admin role</label><select id="admin_role" name="admin_role"><option value="">Not an admin</option>@foreach(['super_admin' => 'Super Admin', 'manager' => 'Manager', 'support' => 'Support', 'finance' => 'Finance'] as $value => $label)<option value="{{ $value }}" @selected(old('admin_role', $user->admin_role) === $value)>{{ $label }}</option>@endforeach</select></div>
            <div class="field checkbox"><input id="notifications_enabled" name="notifications_enabled" type="checkbox" value="1" @checked(old('notifications_enabled', $user->notifications_enabled))><label for="notifications_enabled">Notifications enabled</label></div>
        </div>
        <div class="actions"><button class="button" type="submit">Save profile</button><a class="button secondary" href="{{ route('admin.users') }}">Cancel</a></div>
    </form>
</article>
@endsection
