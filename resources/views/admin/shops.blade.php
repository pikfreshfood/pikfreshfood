@extends('admin.layouts.app')

@section('title', 'Admin Shops - PikFreshFood')
@section('page_title', 'Shops')
@section('page_copy', 'Monitor vendor shop accounts and verification')

@section('styles')
.panel { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 14px; }
table { width: 100%; border-collapse: collapse; }
th, td { text-align: left; padding: 10px; border-bottom: 1px solid var(--line); font-size: 0.88rem; }
th { color: var(--muted); font-size: 0.76rem; text-transform: uppercase; }
.action-cell { display:flex; gap:6px; align-items:center; flex-wrap:wrap; }
.action-link { display:inline-flex; align-items:center; min-height:34px; padding:0 10px; border-radius:8px; background:var(--dark-soft); color:#fff; text-decoration:none; font-weight:800; font-size:.78rem; }
.action-link.delete { border:0; background:#a52b39; cursor:pointer; }
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
    <h3 class="panel-title"><svg viewBox="0 0 24 24"><path d="M3 10h18"></path><path d="M5 10V5h14v5"></path><path d="M5 10v9h14v-9"></path><path d="M9 19v-5h6v5"></path></svg>Shop Accounts</h3>
    <table>
        <thead>
            <tr>
                <th>Shop Name</th>
                <th>Owner</th>
                <th>Email</th>
                <th>Verification</th>
                <th>Rating</th>
                <th>Total Orders</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shops as $shop)
                <tr>
                    <td>{{ $shop->shop_name }}</td>
                    <td>{{ $shop->user->name ?? 'N/A' }}</td>
                    <td>{{ $shop->user->email ?? 'N/A' }}</td>
                    <td>{{ ucfirst($shop->verification_status ?? 'pending') }}</td>
                    <td>{{ $shop->rating ?: 'N/A' }}</td>
                    <td>{{ $shop->total_orders ?? 0 }}</td>
                    <td class="action-cell">
                        <a class="action-link" href="{{ route('admin.shops.edit', $shop) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.shops.destroy', $shop) }}" onsubmit="return confirm('Delete this shop, owner account, and related shop records?');">
                            @csrf @method('DELETE')
                            <button class="action-link delete" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No shops found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ route('admin.dashboard') }}" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none; color:var(--muted-color); font-weight:700; font-size:0.9rem; margin:14px 0 12px;">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 12h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        Back to Dashboard
    </a>
<div style="margin-top:12px;">{{ $shops->links() }}</div>
</article>
@endsection
