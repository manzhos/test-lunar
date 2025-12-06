<?php

namespace App\Support;
use Lunar\Facades\CartSession;
use Lunar\Models\Cart;
use Lunar\Models\CartLine;
use Lunar\Models\Channel;
use Lunar\Models\Currency;

class CartService
{
    public function getCart(): ?Cart
    {
        $currency = Currency::whereDefault(true)->first() ?? Currency::first();
        $channel = Channel::whereDefault(true)->first() ?? Channel::first();

        if ($currency) {
            CartSession::setCurrency($currency);
        }

        if ($channel) {
            CartSession::setChannel($channel);
        }

        $cart = CartSession::current(calculate: true);

        // Если корзины ещё нет в сессии, создаём новую.
        if (! $cart) {
            $cart = CartSession::manager();
            $cart?->calculate();
        }

        return $cart?->load(['lines.purchasable.product', 'lines.purchasable.prices']);
    }

    public function formatted(?Cart $cart): array
    {
        if (! $cart) {
            return [
                'id' => null,
                'lines' => [],
                'quantity' => 0,
                'totals' => [
                    'subTotal' => '0.00',
                    'discounts' => '0.00',
                    'tax' => '0.00',
                    'total' => '0.00',
                ],
            ];
        }

        $cart->recalculate();

        $lines = $cart->lines->map(function (CartLine $line) {
            $purchasable = $line->purchasable;
            $product = $purchasable?->product;

            return [
                'id' => $line->id,
                'variant_id' => $purchasable?->id,
                'name' => (string) ($product?->attribute_data['name'] ?? 'Товар'),
                'sku' => $purchasable?->sku,
                'quantity' => $line->quantity,
                'unit_price' => $line->unitPrice?->formatted() ?? '0.00',
                'total' => $line->total?->formatted() ?? '0.00',
            ];
        });

        return [
            'id' => $cart->id,
            'lines' => $lines,
            'quantity' => $lines->sum('quantity'),
            'totals' => [
                'subTotal' => $cart->subTotal?->formatted() ?? '0.00',
                'discounts' => $cart->discountTotal?->formatted() ?? '0.00',
                'tax' => $cart->taxTotal?->formatted() ?? '0.00',
                'total' => $cart->total?->formatted() ?? '0.00',
            ],
        ];
    }
}
