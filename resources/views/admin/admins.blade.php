@extends('admin.layouts.app')

@section('title', 'Manage Admins - PikFreshFood')
@section('page_title', 'Manage Admins')
@section('page_copy', 'Create administrator accounts and assign access roles')

@section('styles')
.grid { display:grid; grid-template-columns: minmax(280px, .8fr) minmax(420px, 1.2fr); gap:12px; }
.form-card, .panel { background:#fff; border:1px solid var(--line); border-radius:var(--radius); padding:16px; }
.form-card h3, .panel h3 { margin-bottom:12px; }
.field { margin-bottom:11px; } label { display:block; margin-bottom:6px; color:var(--muted); font-size:.84rem; font-weight:800; }
input, select { width:100%; min-height:42px; border:1px solid var(--line); border-radius:9px; padding:0 10px; font:inherit; }
.button { min-height:42px; border:0; border-radius:9px; padding:0 14px; background:var(--dark-soft); color:#fff; font-weight:800; cursor:pointer; }
table { width:100%; border-collapse:collapse; } th, td { text-align:left; padding:9px; border-bottom:1px solid var(--line); font-size:.86rem; } th { color:var(--muted); font-size:.74rem; text-transform:uppercase; }
.action-link { color:var(--dark-soft); font-weight:800; text-decoration:none; }
.pagination { display:flex; gap:6px; align-items:center; flex-wrap:wrap; margin-top:14px; } .pagination a,.pagination span { min-width:32px; min-height:32px; padding:0 9px; display:inline-flex; align-items:center; justify-content:center; border:1px solid var(--line); border-radius:7px; text-decoration:none; color:var(--dark-soft); font-size:.82rem; font-weight:800; background:#fff; } .pagination .active { background:var(--dark-soft); color:#fff; border-color:var(--dark-soft); } .pagination .disabled { color:#aab2c0; }
@media(max-width:900px){.grid{grid-template-columns:1fr}.table-wrap{overflow:auto}table{min-width:560px}}
@endsection

@section('content')
<section class="grid">
    <article class="form-card">
        <h3>Create Admin Account</h3>
        <form method="POST" action="{{ route('admin.admins.store') }}">
            @csrf
            <div class="field"><label for="name">Name</label><input id="name" name="name" value="{{ old('name') }}" required></div>
            <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" required></div>
            <div class="field"><label for="admin_role">Role</label><select id="admin_role" name="admin_role" required>@foreach(['super_admin' => 'Super Admin', 'manager' => 'Manager', 'support' => 'Support', 'finance' => 'Finance'] as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></div>
            <div class="field"><label for="password">Password</label><input id="password" name="password" type="password" required></div>
            <div class="field"><label for="password_confirmation">Confirm password</label><input id="password_confirmation" name="password_confirmation" type="password" required></div>
            <button class="button" type="submit">Create Admin</button>
        </form>
    </article>
    <article class="panel">
        <h3>Administrator Accounts</h3>
        <div class="table-wrap"><table>
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th>Action</th></tr></thead>
            <tbody>@forelse($admins as $admin)<tr><td>{{ $admin->name }}</td><td>{{ $admin->email }}</td><td>{{ str_replace('_', ' ', $admin->admin_role ?: 'admin') }}</td><td>{{ $admin->created_at?->format('d M, Y') }}</td><td><a class="action-link" href="{{ route('admin.users.edit', $admin) }}">View / Edit</a></td></tr>@empty<tr><td colspan="5">No administrator accounts found.</td></tr>@endforelse</tbody>
        </table></div>
        <div class="pagination">
            @if($admins->onFirstPage()) <span class="disabled">Previous</span> @else <a href="{{ $admins->previousPageUrl() }}">Previous</a> @endif
            @foreach($admins->getUrlRange(max(1, $admins->currentPage() - 2), min($admins->lastPage(), $admins->currentPage() + 2)) as $page => $url)
                @if($page === $admins->currentPage()) <span class="active">{{ $page }}</span> @else <a href="{{ $url }}">{{ $page }}</a> @endif
            @endforeach
            @if($admins->hasMorePages()) <a href="{{ $admins->nextPageUrl() }}">Next</a> @else <span class="disabled">Next</span> @endif
        </div>
    </article>
</section>
@endsection
