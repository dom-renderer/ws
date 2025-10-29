<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $simpleProducts = [
            [
                'name' => 'Lay’s Classic Salted Chips 150g',
                'sku' => 'LAYS-150G',
                'short_description' => 'Crispy potato chips with a hint of salt.',
                'long_description' => 'Lay’s Classic Salted Chips are made from the finest potatoes and seasoned to perfection for a timeless taste.',
                'type' => 'simple',
                'single_product_price' => 1.25,
                'tags' => ['snack', 'chips', 'crisps'],
                'category' => 'Snacks',
                'brand' => 'pepsico',
                'unit' => 'Gram (g)',
                'image' => 'lays-150g.jpg',
            ],
            [
                'name' => 'Nestlé Pure Life Water 1L Bottle',
                'sku' => 'NESTLE-1L',
                'short_description' => 'Clean, purified bottled water for everyday hydration.',
                'long_description' => 'Nestlé Pure Life Water goes through a rigorous purification process to ensure great taste and safety.',
                'type' => 'simple',
                'single_product_price' => 0.60,
                'tags' => ['water', 'hydration'],
                'category' => 'Water',
                'brand' => 'nestle',
                'unit' => 'Bottle',
                'image' => 'nestle-water-1l.jpg',
            ],
        ];

        $variableParent = [
            'name' => 'Coca-Cola',
            'sku' => 'CC-VAR',
            'short_description' => 'Classic Coca-Cola soft drink.',
            'long_description' => 'Enjoy the world’s most loved carbonated beverage in multiple packaging sizes.',
            'type' => 'variable',
            'single_product_price' => null,
            'tags' => ['cola', 'soft drink'],
            'category' => 'Soft Drinks',
            'brand' => 'coca-cola',
            'unit' => 'Can',
            'image' => 'coca-cola-main.jpg',
        ];

        $variableVariants = [
            [
                'name' => 'Coca-Cola 250mL Can',
                'sku' => 'CC-250-CAN',
                'price' => 0.75,
                'unit' => 'Can',
                'image' => 'coca-cola-250ml-can.jpg',
            ],
            [
                'name' => 'Coca-Cola 500mL Bottle',
                'sku' => 'CC-500-BTL',
                'price' => 1.10,
                'unit' => 'Bottle',
                'image' => 'coca-cola-500ml-btl.jpg',
            ],
            [
                'name' => 'Coca-Cola 1L Bottle',
                'sku' => 'CC-1L-BTL',
                'price' => 1.60,
                'unit' => 'Bottle',
                'image' => 'coca-cola-1l-btl.jpg',
            ],
        ];

        $bundledProduct = [
            'name' => 'Heineken Party Pack (6 x 330mL Bottles)',
            'sku' => 'HEI-BUNDLE-6PK',
            'short_description' => 'Six-pack of Heineken premium lager bottles.',
            'long_description' => 'Ideal for parties and events — contains 6 x 330mL bottles of Heineken Lager.',
            'type' => 'bundled',
            'single_product_price' => 9.90,
            'tags' => ['beer', 'bundle', 'party pack'],
            'category' => 'Beer',
            'brand' => 'heineken',
            'unit' => 'Case',
            'image' => 'heineken-bundle-6pk.jpg',
        ];

        $allProducts = array_merge($simpleProducts, [$variableParent, $bundledProduct]);
        foreach ($allProducts as $p) {
            $productId = DB::table('products')->insertGetId([
                'name' => $p['name'],
                'sku' => $p['sku'],
                'short_description' => $p['short_description'],
                'long_description' => $p['long_description'],
                'status' => 1,
                'in_stock' => 1,
                'type' => $p['type'],
                'should_feature_on_home_page' => 0,
                'is_new_product' => 0,
                'is_best_seller' => 0,
                'tags' => json_encode($p['tags']),
                'seo_title' => $p['name'],
                'seo_description' => $p['short_description'],
                'single_product_price' => $p['single_product_price'],
                'in_draft' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $brandId = DB::table('brands')->where('slug', $p['brand'])->value('id');
            $categoryId = DB::table('categories')->where('name', $p['category'])->value('id');
            $unitId = DB::table('units')->where('title', $p['unit'])->value('id');

            if ($brandId) {
                DB::table('brand_product')->insert([
                    'brand_id' => $brandId,
                    'product_id' => $productId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            if ($categoryId) {
                DB::table('product_categories')->insert([
                    'product_id' => $productId,
                    'category_id' => $categoryId,
                    'is_primary' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            if ($unitId) {
                DB::table('product_base_units')->insert([
                    'product_id' => $productId,
                    'varient_id' => 0,
                    'unit_id' => $unitId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::table('product_images')->insert([
                'product_id' => $productId,
                'is_primary' => 1,
                'file' => 'products/' . $p['image'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $parentId = DB::table('products')->where('sku', 'CC-VAR')->value('id');
        foreach ($variableVariants as $v) {
            $varId = DB::table('product_varients')->insertGetId([
                'product_id' => $parentId,
                'name' => $v['name'],
                'sku' => $v['sku'],
                'barcode' => strtoupper(Str::random(12)),
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('product_varient_images')->insert([
                'product_id' => $parentId,
                'varient_id' => $varId,
                'is_primary' => 1,
                'file' => 'products/' . $v['image'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $unitId = DB::table('units')->where('title', $v['unit'])->value('id');
            DB::table('product_base_units')->insert([
                'product_id' => $parentId,
                'varient_id' => $varId,
                'unit_id' => $unitId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('product_tier_pricings')->insert([
                'product_id' => $parentId,
                'product_varient_id' => $varId,
                'product_additional_unit_id' => 0,
                'min_qty' => 12,
                'max_qty' => 120,
                'price_per_unit' => $v['price'] * 0.95,
                'discount_type' => 1,
                'discount_amount' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $bundleId = DB::table('products')->where('sku', 'HEI-BUNDLE-6PK')->value('id');
        $beerId = DB::table('products')->insertGetId([
            'name' => 'Heineken 330mL Bottle (Single)',
            'sku' => 'HEI-330-SINGLE',
            'short_description' => 'Single bottle version of Heineken Premium Lager.',
            'long_description' => 'Premium quality lager beer brewed in Amsterdam.',
            'status' => 1,
            'in_stock' => 1,
            'type' => 'simple',
            'single_product_price' => 1.75,
            'tags' => json_encode(['beer', 'single']),
            'seo_title' => 'Heineken 330mL Bottle',
            'seo_description' => 'Single 330mL bottle of Heineken Lager.',
            'in_draft' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('product_attributes')->insert([
            'product_id' => $bundleId,
            'title' => 'Included Items',
            'value' => '6 x Heineken 330mL Bottle',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
