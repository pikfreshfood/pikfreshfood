<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = collect([
            [
                'slug' => 'how-to-cook-jollof-rice',
                'title' => 'How to Cook Jollof Rice',
                'description' => 'Find the core ingredients for smoky jollof rice from nearby vendors.',
                'ingredients' => ['Tomato', 'Pepper', 'Rice', 'Onion'],
            ],
            [
                'slug' => 'pepper-soup-starter-pack',
                'title' => 'Pepper Soup Starter Pack',
                'description' => 'Everything you need to make rich, spicy pepper soup at home.',
                'ingredients' => ['Pepper', 'Spices', 'Tomato', 'Onion'],
            ],
        ]);

        return view('recipes.index', compact('recipes'));
    }

    public function show(string $slug)
    {
        $recipes = collect([
            'how-to-cook-jollof-rice' => [
                'title' => 'How to Cook Jollof Rice',
                'description' => 'Rich, smoky jollof starts with fresh tomatoes, peppers, onions, and quality rice.',
                'steps' => [
                    'Blend tomato, pepper, and onion into a smooth base.',
                    'Fry the base until it thickens and tastes rich.',
                    'Add rice, stock, and seasoning, then cook until tender.',
                ],
                'ingredients' => ['Tomato', 'Pepper', 'Rice', 'Onion'],
            ],
            'pepper-soup-starter-pack' => [
                'title' => 'Pepper Soup Starter Pack',
                'description' => 'Make a warming pepper soup with fresh pepper, spice blends, and aromatic vegetables.',
                'steps' => [
                    'Boil your protein with onion and seasoning.',
                    'Add pepper soup spices and blended pepper.',
                    'Simmer until fragrant and serve hot.',
                ],
                'ingredients' => ['Pepper', 'Spices', 'Tomato', 'Onion'],
            ],
        ]);

        abort_unless($recipes->has($slug), 404);

        $recipe = $recipes->get($slug);
        $ingredients = collect($recipe['ingredients']);

        $products = Product::with('vendor')
            ->where('is_available', true)
            ->get()
            ->filter(function (Product $product) use ($ingredients) {
                return $ingredients->contains(function ($ingredient) use ($product) {
                    return Str::contains(Str::lower($product->name), Str::lower($ingredient))
                        || Str::contains(Str::lower($product->category), Str::lower($ingredient));
                });
            })
            ->take(8)
            ->values();

        return view('recipes.show', compact('recipe', 'products'));
    }
}
