<?php

namespace App\Http\Controllers;

use App\Support\CartService;
use Illuminate\View\View;
use Lunar\Models\Currency;
use Lunar\Models\Product;

class StorefrontController extends Controller
{
    public function __construct(private CartService $cartService)
    {
    }

    public function index(): View
    {
        $currency = Currency::whereDefault(true)->first() ?? Currency::first();

        $products = Product::with(['variants.prices'])
            ->where('status', 'published')
            ->take(9)
            ->get();

        $cart = $this->cartService->formatted($this->cartService->getCart());

        return view('welcome', [
            'products' => $products,
            'currency' => $currency,
            'cart' => $cart,
        ]);
    }
}
