@extends('layouts.app')

@section('title', 'Vendor Products - PikFreshFood')

@section('styles')
<style>
    .vendor-products-container { max-width: 1100px; margin: 30px auto; padding: 0 16px; }
    .vendor-products-header {
        display: flex; justify-content: space-between; align-items: center; gap: 12px;
        margin-bottom: 22px;
    }
    .vendor-products-title h1 { font-size: 1.9rem; margin-bottom: 4px; color: var(--text-color); }
    .vendor-products-title p { color: var(--muted-color); }
    .vendor-products-action {
        display: inline-flex; align-items: center; justify-content: center;
        min-height: 44px; padding: 0 16px; border-radius: 10px;
        background: var(--primary-color); color: white; text-decoration: none; font-weight: 700;
    }
    .vendor-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }
    .vendor-product-card {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 14px;
        display: grid;
        gap: 10px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
    }
    .vendor-product-thumb {
        width: 100%;
        height: 146px;
        object-fit: cover;
        border-radius: 12px;
        background: color-mix(in srgb, var(--primary-color) 12%, white 88%);
    }
    .vendor-product-name {
        font-size: 1.02rem;
        font-weight: 800;
        margin: 0;
        color: var(--text-color);
        line-height: 1.35;
    }
    .vendor-product-price { color: var(--primary-color); font-size: 1.15rem; font-weight: 800; }
    .vendor-product-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .vendor-product-actions form {
        margin: 0;
    }
    .vendor-icon-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--surface-alt);
        color: var(--text-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        transition: transform 0.15s ease, border-color 0.15s ease, background-color 0.15s ease;
    }
    .vendor-icon-btn:hover {
        transform: translateY(-1px);
        border-color: var(--primary-color);
    }
    .vendor-icon-btn svg {
        width: 18px;
        height: 18px;
    }
    .vendor-icon-btn.delete {
        background: #842d2d;
        border-color: #842d2d;
        color: #fff;
    }
    .vendor-icon-btn.boost {
        background: #f4c400;
        border-color: #f4c400;
        color: #111;
    }
    .empty-card {
        background: var(--bottom-sheet-bg); border: 1px solid var(--border-color); border-radius: 16px;
        padding: 28px; text-align: center; color: var(--muted-color);
    }
    .confirm-modal {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(10, 16, 14, 0.58);
        z-index: 1300;
    }
    .confirm-modal.is-visible { display: flex; }
    .confirm-modal-card {
        width: 100%;
        max-width: 380px;
        padding: 24px;
        border-radius: 18px;
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        box-shadow: 0 24px 54px rgba(0, 0, 0, 0.22);
    }
    .confirm-modal-card h3 {
        margin: 0 0 8px;
        color: var(--text-color);
    }
    .confirm-modal-card p {
        margin: 0 0 18px;
        color: var(--muted-color);
        line-height: 1.5;
    }
    .confirm-modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
    .confirm-modal-btn {
        min-height: 42px;
        padding: 0 16px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--surface-alt);
        color: var(--text-color);
        font-weight: 700;
        cursor: pointer;
    }
    .confirm-modal-btn.danger {
        background: #e74c3c;
        border-color: #e74c3c;
        color: white;
    }
    @media (max-width: 900px) {
        .vendor-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 640px) {
        .vendor-products-header {
            flex-direction: column;
            align-items: stretch;
        }
        .vendor-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .vendor-product-card {
            padding: 12px;
        }
        .vendor-product-thumb {
            height: 126px;
        }
        .vendor-icon-btn {
            width: 38px;
            height: 38px;
        }
    }
</style>
@endsection

@section('content')
<div class="vendor-products-container">
    @if(session('success'))
        <div style="margin-bottom:16px; padding:12px 14px; border-radius:12px; background:rgba(47,131,105,.18); color:#d7fff3;">
            {{ session('success') }}
        </div>
    @endif

    <div class="vendor-products-header">
        <div class="vendor-products-title">
            <h1>{{ $vendor->shop_name }} Products</h1>
            <p>Manage your listings, update stock, and edit or delete products.</p>
        </div>
        <a href="{{ route('vendor.add-product') }}" class="vendor-products-action">Add Product</a>
    </div>

    <div class="vendor-grid">
        @forelse($products as $product)
            <div class="vendor-product-card">
                @if($product->primary_image)
                    <img src="{{ \App\Support\PublicStorage::url($product->primary_image) }}" alt="{{ $product->name }}" class="vendor-product-thumb">
                @else
                    <div class="vendor-product-thumb"></div>
                @endif
                <div class="vendor-product-name">{{ $product->name }}</div>
                <div class="vendor-product-price">₦{{ $product->price }}</div>
                <div class="vendor-product-actions">
                    <a href="{{ route('vendor.products.edit', $product) }}" class="vendor-icon-btn" title="Edit Product" aria-label="Edit Product">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 20h9"/>
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
                        </svg>
                    </a>
                    <form action="{{ route('vendor.products.destroy', $product) }}" method="POST" class="delete-product-form" data-product-name="{{ $product->name }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="vendor-icon-btn delete" title="Delete Product" aria-label="Delete Product">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2"/>
                                <path d="M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6"/>
                                <line x1="10" y1="11" x2="10" y2="17"/>
                                <line x1="14" y1="11" x2="14" y2="17"/>
                            </svg>
                        </button>
                    </form>
                    <form action="{{ route('vendor.products.boost', $product) }}" method="POST">
                        @csrf
                        <input type="hidden" name="boost_plan" value="premium_3m">
                        <button type="submit" class="vendor-icon-btn boost" title="Boost Product (3 months)" aria-label="Boost Product">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4.5 16.5c2.5.3 4.7-.4 6.5-2.2 2.1-2.1 2.8-5.3 1.9-8.3 3 .9 6.2.2 8.3-1.9.2 1 .3 2.1.3 3.2 0 6-4.9 10.9-10.9 10.9-1.1 0-2.2-.1-3.2-.3z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-card">No products yet. Add your first product to start selling.</div>
        @endforelse
    </div>
</div>

<div class="confirm-modal" id="deleteProductModal" aria-hidden="true">
    <div class="confirm-modal-card">
        <h3>Delete Product?</h3>
        <p id="deleteProductMessage">Are you sure you want to delete this item?</p>
        <div class="confirm-modal-actions">
            <button type="button" class="confirm-modal-btn" id="cancelDeleteProduct">Cancel</button>
            <button type="button" class="confirm-modal-btn danger" id="confirmDeleteProduct">Yes, Delete</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const modal = document.getElementById('deleteProductModal');
        const message = document.getElementById('deleteProductMessage');
        const cancelButton = document.getElementById('cancelDeleteProduct');
        const confirmButton = document.getElementById('confirmDeleteProduct');
        const forms = document.querySelectorAll('.delete-product-form');
        let activeForm = null;

        function closeModal() {
            modal.classList.remove('is-visible');
            modal.setAttribute('aria-hidden', 'true');
            activeForm = null;
        }

        forms.forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                activeForm = form;
                const productName = form.dataset.productName || 'this item';
                message.textContent = `Are you sure you want to delete "${productName}"?`;
                modal.classList.add('is-visible');
                modal.setAttribute('aria-hidden', 'false');
            });
        });

        cancelButton.addEventListener('click', closeModal);
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });
        confirmButton.addEventListener('click', function () {
            if (activeForm) {
                activeForm.submit();
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('is-visible')) {
                closeModal();
            }
        });
    })();
</script>
@endsection



