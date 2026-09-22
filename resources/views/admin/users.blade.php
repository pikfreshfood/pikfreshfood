@extends('admin.layouts.app')

@section('title', 'Users - PikFreshFood')
@section('page_title', 'Users')
@section('page_copy', 'View and manage customer, vendor, and admin profiles')

@section('styles')
.panel { background:#fff; border:1px solid var(--line); border-radius:var(--radius); padding:14px; }
.toolbar { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:14px; }
.search-form { display:flex; gap:8px; flex:1; max-width:520px; }
.search-form input { flex:1; min-height:40px; border:1px solid var(--line); border-radius:9px; padding:0 11px; }
.button, .action-link { display:inline-flex; align-items:center; justify-content:center; min-height:36px; padding:0 11px; border:0; border-radius:8px; background:var(--dark-soft); color:#fff; text-decoration:none; font-weight:800; font-size:.8rem; cursor:pointer; }
.action-link.delete { background:#a52b39; }
.action-cell { display:flex; gap:6px; align-items:center; }
table { width:100%; border-collapse:collapse; } th,td { text-align:left; padding:10px; border-bottom:1px solid var(--line); font-size:.86rem; } th { color:var(--muted); font-size:.74rem; text-transform:uppercase; }
.pagination { display:flex; gap:6px; align-items:center; flex-wrap:wrap; margin-top:14px; } .pagination a,.pagination span { min-width:32px; min-height:32px; padding:0 9px; display:inline-flex; align-items:center; justify-content:center; border:1px solid var(--line); border-radius:7px; text-decoration:none; color:var(--dark-soft); font-size:.82rem; font-weight:800; background:#fff; } .pagination .active { background:var(--dark-soft); color:#fff; border-color:var(--dark-soft); } .pagination .disabled { color:#aab2c0; }
@media(max-width:800px){.table-wrap{overflow:auto}table{min-width:760px}}
@endsection

@section('content')
<article class="panel">
    <div class="toolbar">
        <strong>{{ $users->total() }} profiles</strong>
        <form class="search-form" method="GET" action="{{ route('admin.users') }}">
            <input type="search" name="search" value="{{ $search }}" placeholder="Search name, email, phone" aria-label="Search users">
            <button class="button" type="submit">Search</button>
        </form>
    </div>
    <div class="table-wrap"><table>
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Type</th><th>Joined</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->phone ?: '—' }}</td>
                <td>{{ str_replace('_', ' ', $user->admin_role ?: $user->role) }}</td><td>{{ $user->created_at?->format('d M, Y') }}</td>
                <td class="action-cell"><a class="action-link" href="{{ route('admin.users.edit', $user) }}">View / Edit</a>
                    @if($user->id !== auth()->id())<form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?');">@csrf @method('DELETE')<button class="action-link delete" type="submit">Delete</button></form>@endif
                </td>
            </tr>
        @empty <tr><td colspan="6">No users found.</td></tr>@endforelse
        </tbody>
    </table></div>
    <div class="pagination">
        @if($users->onFirstPage()) <span class="disabled">Previous</span> @else <a href="{{ $users->previousPageUrl() }}">Previous</a> @endif
        @foreach($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
            @if($page === $users->currentPage()) <span class="active">{{ $page }}</span> @else <a href="{{ $url }}">{{ $page }}</a> @endif
        @endforeach
        @if($users->hasMorePages()) <a href="{{ $users->nextPageUrl() }}">Next</a> @else <span class="disabled">Next</span> @endif
    </div>
</article>
@endsection
