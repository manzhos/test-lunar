<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Lunar\FieldTypes\Text;
use Lunar\Models\Brand;
use Lunar\Models\Channel;
use Lunar\Models\Currency;
use Lunar\Models\CustomerGroup;
use Lunar\Models\Language;
use Lunar\Models\Price;
use Lunar\Models\Product;
use Lunar\Models\ProductType;
use Lunar\Models\ProductVariant;

class LunarDemoSeeder extends Seeder
{
    public function run(): void
    {
        $currency = Currency::firstOrCreate(
            ['code' => 'USD'],
            [
                'name' => 'US Dollar',
                'exchange_rate' => 1,
                'decimal_places' => 2,
                'default' => true,
                'enabled' => true,
                'sync_prices' => true,
            ]
        );

        $language = Language::firstOrCreate(
            ['code' => 'en'],
            ['name' => 'English', 'default' => true]
        );

        $channel = Channel::firstOrCreate(
            ['handle' => 'web'],
            ['name' => 'Web', 'default' => true, 'url' => config('app.url')]
        );

        CustomerGroup::firstOrCreate(
            ['default' => true],
            ['name' => 'Retail', 'handle' => 'retail']
        );

        $productType = ProductType::firstOrCreate(['name' => 'Default']);
        $brand = Brand::firstOrCreate(['name' => 'Lunar Goods']);

        $products = [
            [
                'name' => 'Lunar product1',
                'description' => 'Description product 1.',
                'price' => 4900,
                'sku' => 'LP-001',
            ],
            [
                'name' => 'Lunar product2',
                'description' => 'Description product 2.',
                'price' => 3200,
                'sku' => 'LP-002',
            ],
            [
                'name' => 'Lunar product3',
                'description' => 'Description product 3.',
                'price' => 1200,
                'sku' => 'LP-003',
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::factory()
                ->for($productType)
                ->state([
                    'brand_id' => $brand->id,
                    'status' => 'published',
                    'attribute_data' => collect([
                        'name' => new Text($productData['name']),
                        'description' => new Text($productData['description']),
                    ]),
                ])
                ->has(
                    ProductVariant::factory()
                        ->state([
                            'sku' => $productData['sku'],
                            'shippable' => true,
                        ])
                        ->has(
                            Price::factory()->state([
                                'price' => $productData['price'],
                                'compare_price' => null,
                                'currency_id' => $currency->id,
                            ]),
                            'prices'
                        ),
                    'variants'
                )
                ->create();

            $product->channels()->syncWithoutDetaching([$channel->id]);

            $product->urls()->updateOrCreate(
                [
                    'language_id' => $language->id,
                    'slug' => Str::slug($productData['sku']),
                ],
                ['default' => true]
            );
        }
    }
}
