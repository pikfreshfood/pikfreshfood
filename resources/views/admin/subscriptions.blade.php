@extends('admin.layouts.app')

@section('title', 'Admin Subscriptions - PikFreshFood')
@section('page_title', 'Subscriptions')
@section('page_copy', 'Track vendor plans, statuses, and expiry details')

@section('styles')
.panel { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 14px; }
table { width: 100%; border-collapse: collapse; }
th, td { text-align: left; padding: 10px; border-bottom: 1px solid var(--line); font-size: 0.88rem; }
th { color: var(--muted); font-size: 0.76rem; text-transform: uppercase; }
.badge { display: inline-flex; padding: 4px 8px; border-radius: 999px; font-size: 0.74rem; font-weight: 800; }
.badge.premium { background: #fff4cf; color: #8a5d00; }
.badge.free { background: #eef2f5; color: #4f5d67; }
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
    <h3 class="panel-title"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M3 10h18"></path><path d="M8 15h2"></path></svg>Subscription Details</h3>
    <table>
        <thead>
            <tr>
                <th>Shop</th>
                <th>Owner</th>
                <th>Plan</th>
                <th>Status</th>
                <th>Expires At</th>
                <th>Boosted Until</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vendors as $vendor)
                @php
                    $isPremium = in_array($vendor->subscription_plan, ['premium_3m', 'premium_6m', 'premium_12m'], true);
                @endphp
                <tr>
                    <td>{{ $vendor->shop_name }}</td>
                    <td>{{ $vendor->user->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $isPremium ? 'premium' : 'free' }}">
                            {{ strtoupper(str_replace('_', ' ', $vendor->subscription_plan ?? 'free')) }}
                        </span>
                    </td>
                    <td>{{ ucfirst($vendor->subscription_status ?? 'active') }}</td>
                    <td>{{ $vendor->subscription_expires_at?->format('d M, Y H:i') ?? 'N/A' }}</td>
                    <td>{{ $vendor->boosted_until?->format('d M, Y H:i') ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No subscription data found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px;">{{ $vendors->links() }}</div>
</article>
@endsection
