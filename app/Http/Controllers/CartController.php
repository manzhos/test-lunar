<?php

namespace App\Http\Controllers;

use App\Support\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Lunar\Facades\CartSession;
use Lunar\Models\ProductVariant;

class CartController extends Controller
{
    public function __construct(private CartService $cartService)
    {
    }

    public function show(): JsonResponse
    {
        return response()->json(
            $this->cartService->formatted($this->cartService->getCart())
        );
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'variant_id' => ['required', Rule::exists((new ProductVariant)->getTable(), 'id')],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $this->cartService->getCart();
        $variant = ProductVariant::findOrFail($data['variant_id']);

        $cart?->add($variant, $data['quantity']);

        return response()->json(
            $this->cartService->formatted($cart?->refresh())
        );
    }

    public function update(Request $request, int $lineId): JsonResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $this->cartService->getCart();

        if (! $cart || ! $cart->lines->contains('id', $lineId)) {
            return response()->json(['message' => 'Товар не найден в корзине'], 404);
        }

        $cart->updateLine($lineId, $data['quantity']);

        return response()->json(
            $this->cartService->formatted($cart->refresh())
        );
    }

    public function destroy(int $lineId): JsonResponse
    {
        $cart = $this->cartService->getCart();

        if (! $cart || ! $cart->lines->contains('id', $lineId)) {
            return response()->json(['message' => 'Товар не найден в корзине'], 404);
        }

        $cart->remove($lineId);

        return response()->json(
            $this->cartService->formatted($cart->refresh())
        );
    }

    public function clear(): JsonResponse
    {
        $cart = $this->cartService->getCart();

        if ($cart) {
            CartSession::forget();
        }

        return response()->json(
            $this->cartService->formatted($this->cartService->getCart())
        );
    }
}
