@extends('admin.layouts.app')

@section('title', 'Edit Product - PikFreshFood')
@section('page_title', 'Edit Product')
@section('page_copy', 'Update product details and availability')

@section('styles')
.form-card { max-width: 760px; background:#fff; border:1px solid var(--line); border-radius:var(--radius); padding:18px; }
.grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.field { margin-bottom:12px; }
.field.full { grid-column:1 / -1; }
label { display:block; margin-bottom:6px; color:var(--muted); font-size:.84rem; font-weight:800; }
input, textarea, select { width:100%; border:1px solid var(--line); border-radius:9px; padding:10px; font:inherit; }
textarea { min-height:110px; resize:vertical; }
.checkbox { display:flex; gap:8px; align-items:center; min-height:42px; }
.checkbox input { width:auto; }
.actions { display:flex; gap:8px; margin-top:6px; }
.button { min-height:42px; border:0; border-radius:9px; padding:0 14px; background:var(--dark-soft); color:#fff; font-weight:800; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; }
.button.secondary { background:#eef2f8; color:var(--dark-soft); }
@media(max-width:700px){.grid{grid-template-columns:1fr}.field.full{grid-column:auto}}
@endsection

@section('content')
<article class="form-card">
    <form method="POST" action="{{ route('admin.products.update', $product) }}">
        @csrf @method('PUT')
        <div class="grid">
            <div class="field full"><label for="name">Product name</label><input id="name" name="name" value="{{ old('name', $product->name) }}" required></div>
            <div class="field"><label for="category">Category</label><input id="category" name="category" value="{{ old('category', $product->category) }}"></div>
            <div class="field"><label for="unit">Unit</label><input id="unit" name="unit" value="{{ old('unit', $product->unit) }}"></div>
            <div class="field"><label for="price">Price</label><input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $product->price) }}" required></div>
            <div class="field"><label for="stock_quantity">Stock quantity</label><input id="stock_quantity" name="stock_quantity" type="number" min="0" value="{{ old('stock_quantity', $product->stock_quantity) }}" required></div>
            <div class="field full"><label for="description">Description</label><textarea id="description" name="description">{{ old('description', $product->description) }}</textarea></div>
            <div class="field full checkbox"><input id="is_available" name="is_available" type="checkbox" value="1" @checked(old('is_available', $product->is_available))><label for="is_available">Available for customers</label></div>
        </div>
        <div class="actions"><button class="button" type="submit">Save changes</button><a class="button secondary" href="{{ route('admin.products') }}">Cancel</a></div>
    </form>
</article>
@endsection
