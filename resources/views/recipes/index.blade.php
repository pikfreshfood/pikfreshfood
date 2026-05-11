@extends('layouts.app')

@section('title', 'Recipes - PikFreshFood')

@section('content')
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
