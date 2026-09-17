@extends('layouts.app')

@section('title', 'Recipes - PikFreshFood')

@section('content')
<div style="max-width:1100px; margin:14px auto 0; padding:0 16px;">
<a href="{{ route('home') }}" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none; color:var(--muted-color); font-weight:700; font-size:0.9rem; margin:14px 0 12px;">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 12h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        Back to Home
    </a>
</div>
<div class="home-container" style="padding-top:24px;">
    <div class="section-heading">Recipe Pages</div>
    <p class="section-copy">SEO-friendly recipe pages that connect ingredients to nearby products.</p>
    <div class="vendor-strip">
        @foreach($recipes as $recipe)
            <a href="{{ route('recipes.show', $recipe['slug']) }}" class="vendor-card">
                <strong>{{ $recipe['title'] }}</strong>
                <div class="vendor-meta">{{ $recipe['description'] }}</div>
                <div class="vendor-distance">Ingredients: {{ implode(', ', $recipe['ingredients']) }}</div>
            </a>
        @endforeach
    </div>
</div>
@endsection
