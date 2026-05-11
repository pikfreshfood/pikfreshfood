@extends('admin.layouts.app')

@section('title', 'Admin Products - PikFreshFood')
@section('page_title', 'Products')
@section('page_copy', 'Review all products across vendor shops')

@section('styles')
.panel { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 14px; }
table { width: 100%; border-collapse: collapse; }
th, td { text-align: left; padding: 10px; border-bottom: 1px solid var(--line); font-size: 0.88rem; }
th { color: var(--muted); font-size: 0.76rem; text-transform: uppercase; }
.badge { display: inline-flex; padding: 4px 8px; border-radius: 999px; font-size: 0.74rem; font-weight: 800; }
.badge.on { background: #e9f8ef; color: #1e6b3e; }
.badge.off { background: #f4f5f6; color: #55636e; }
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
    <h3 class="panel-title"><svg viewBox="0 0 24 24"><path d="M3 7 12 3l9 4-9 4-9-4Z"></path><path d="M3 12l9 4 9-4"></path><path d="M3 17l9 4 9-4"></path></svg>Product List</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Shop</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->vendor->shop_name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($product->category) }}</td>
                    <td>₦{{ number_format((float) $product->price, 2) }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>
                        <span class="badge {{ $product->is_available ? 'on' : 'off' }}">
                            {{ $product->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">No products yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px;">{{ $products->links() }}</div>
</article>
@endsection
