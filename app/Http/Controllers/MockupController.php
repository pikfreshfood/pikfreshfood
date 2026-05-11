<?php

namespace App\Http\Controllers;

class MockupController extends Controller
{
    private array $mockups = [
        'home' => ['view' => 'home', 'title' => 'Home Page'],
        'recipe' => ['view' => 'recipe', 'title' => 'Recipe Page'],
        'product-detail' => ['view' => 'product-detail', 'title' => 'Product Detail'],
        'vendor-profile' => ['view' => 'vendor-profile', 'title' => 'Vendor Profile'],
        'checkout' => ['view' => 'checkout', 'title' => 'Checkout Page'],
        'map' => ['view' => 'map', 'title' => 'Map View'],
        'vendor-dashboard' => ['view' => 'vendor-dashboard', 'title' => 'Vendor Dashboard'],
        'street-food' => ['view' => 'street-food', 'title' => 'Street Food Page'],
        'user-profile' => ['view' => 'user-profile', 'title' => 'User Profile'],
        'auth-signup' => ['view' => 'auth-signup', 'title' => 'Auth / Signup'],
    ];

    public function index()
    {
        return view('mockups.index', ['mockups' => $this->mockups]);
    }

    public function show(string $slug)
    {
        if (! isset($this->mockups[$slug])) {
            abort(404);
        }

        if ($slug === 'map') {
            return redirect()->route('map.index');
        }

        $viewName = $this->mockups[$slug]['view'];

        if (! view()->exists('mockups.' . $viewName)) {
            abort(404);
        }

        $html = view('mockups.' . $viewName)->render();
        $html = $this->injectNavigationScript($html, $slug);

        return response($html, 200)->header('Content-Type', 'text/html');
    }

    private function injectNavigationScript(string $html, string $slug): string
    {
        $navigation = [
            'home' => [
                'button.cta-primary' => '/mockups/map',
            ],
            'recipe' => [
                '.back-btn' => '/mockups/home',
                'button.cta-primary' => '/mockups/product-detail',
                'button.checkout-btn' => '/mockups/checkout',
            ],
            'product-detail' => [
                'button.btn-cart' => '/mockups/checkout',
                'button.btn-buy' => '/mockups/checkout',
            ],
            'vendor-profile' => [
                '.btn-message' => '/mockups/user-profile',
                '.action-bar-btn.btn-order' => '/mockups/checkout',
            ],
            'checkout' => [
                'button.checkout-btn' => '/mockups/user-profile',
            ],
            'map' => [
                '.action-btn' => '/mockups/vendor-profile',
            ],
            'street-food' => [
                '.btn-vendor' => '/mockups/vendor-profile',
                '.btn-order' => '/mockups/checkout',
                '.recruitment-btn' => '/mockups/auth-signup',
            ],
            'auth-signup' => [
                'button[type="submit"]' => '/mockups/user-profile',
            ],
        ];

        if (! isset($navigation[$slug])) {
            return $html;
        }

        $script = '<script>document.addEventListener("DOMContentLoaded", function() {';
        foreach ($navigation[$slug] as $selector => $target) {
            $script .= 'document.querySelectorAll("'.addslashes($selector).'" ).forEach(function(el){ el.style.cursor = "pointer"; el.addEventListener("click", function(){ window.location.href = "'.addslashes($target).'"; }); });';
        }
        $script .= '});</script>';

        if (str_contains($html, '</body>')) {
            return str_replace('</body>', $script.'</body>', $html);
        }

        return $html.$script;
    }
}
