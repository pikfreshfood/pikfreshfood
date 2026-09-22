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
.toolbar { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:14px; flex-wrap:wrap; }
.search-form { display:flex; gap:8px; flex:1; max-width:520px; }
.search-form input { flex:1; min-height:40px; border:1px solid var(--line); border-radius:9px; padding:0 11px; }
.button, .action-link { display:inline-flex; align-items:center; justify-content:center; min-height:36px; padding:0 11px; border:0; border-radius:8px; background:var(--dark-soft); color:#fff; text-decoration:none; font-weight:800; font-size:.8rem; cursor:pointer; }
.action-link.delete { background:#a52b39; }
.action-cell { display:flex; gap:6px; align-items:center; }
.pagination { display:flex; gap:6px; align-items:center; flex-wrap:wrap; margin-top:14px; }
.pagination a, .pagination span { min-width:32px; min-height:32px; padding:0 9px; display:inline-flex; align-items:center; justify-content:center; border:1px solid var(--line); border-radius:7px; text-decoration:none; color:var(--dark-soft); font-size:.82rem; font-weight:800; background:#fff; }
.pagination .active { background:var(--dark-soft); color:#fff; border-color:var(--dark-soft); }
.pagination .disabled { color:#aab2c0; }
@endsection

@section('content')
<article class="panel">
    <div class="toolbar">
        <h3 class="panel-title"><svg viewBox="0 0 24 24"><path d="M3 7 12 3l9 4-9 4-9-4Z"></path><path d="M3 12l9 4 9-4"></path><path d="M3 17l9 4 9-4"></path></svg>Product List</h3>
        <form class="search-form" method="GET" action="{{ route('admin.products') }}">
            <input type="search" name="search" value="{{ $search }}" placeholder="Search products, categories, shops" aria-label="Search products">
            <button class="button" type="submit">Search</button>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Shop</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
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
                    <td class="action-cell">
                        <a class="action-link" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                            @csrf @method('DELETE')
                            <button class="action-link delete" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ route('admin.dashboard') }}" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none; color:var(--muted-color); font-weight:700; font-size:0.9rem; margin:14px 0 12px;">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 12h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        Back to Dashboard
    </a>
    <div class="pagination">
        @if($products->onFirstPage()) <span class="disabled">Previous</span> @else <a href="{{ $products->previousPageUrl() }}">Previous</a> @endif
        @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
            @if($page === $products->currentPage()) <span class="active">{{ $page }}</span> @else <a href="{{ $url }}">{{ $page }}</a> @endif
        @endforeach
        @if($products->hasMorePages()) <a href="{{ $products->nextPageUrl() }}">Next</a> @else <span class="disabled">Next</span> @endif
    </div>
</article>
@endsection
