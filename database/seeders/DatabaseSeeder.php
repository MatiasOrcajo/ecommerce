<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartProducts;
use App\Models\Category;
use App\Models\Color;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\MailingList;
use App\Models\Order;
use App\Models\OrderProducts;
use App\Models\Product;
use App\Models\ProductPicture;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\User;
use App\Models\VariantPicture;
use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $sizes = [
            ['name' => 'XXS', 'sort_order' => 0],
            ['name' => 'XS', 'sort_order' => 1],
            ['name' => 'S', 'sort_order' => 2],
            ['name' => 'M', 'sort_order' => 3],
            ['name' => 'L', 'sort_order' => 4],
            ['name' => 'XL', 'sort_order' => 5],
            ['name' => 'XXL', 'sort_order' => 6],
            ['name' => 'XXXL', 'sort_order' => 7],
        ];

        foreach ($sizes as $size) {
            Size::create($size);
        }

        $colors = [
            ['hex' => '#000000', 'name' => 'Negro'],
            ['hex' => '#FFFFFF', 'name' => 'Blanco'],
            ['hex' => '#C41E3A', 'name' => 'Rojo'],
            ['hex' => '#2E4A8E', 'name' => 'Azul Marino'],
            ['hex' => '#D4AF37', 'name' => 'Dorado'],
            ['hex' => '#B76E79', 'name' => 'Rosa Viejo'],
            ['hex' => '#A0A0A0', 'name' => 'Gris'],
            ['hex' => '#8B4513', 'name' => 'Marron'],
            ['hex' => '#D2B48C', 'name' => 'Beige'],
            ['hex' => '#6B8E23', 'name' => 'Verde Oliva'],
            ['hex' => '#FF69B4', 'name' => 'Rosa'],
            ['hex' => '#1B1B3A', 'name' => 'Azul Medianoche'],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }

        Category::factory(20)->create();

        $sizeIds = Size::pluck('id')->toArray();
        $colorIds = Color::pluck('id')->toArray();

        Product::factory(100)->create()->each(function ($product) use ($sizeIds, $colorIds) {
            $usedCombinations = [];

            $variantCount = rand(3, 6);
            for ($i = 0; $i < $variantCount; $i++) {
                $sizeId = $sizeIds[array_rand($sizeIds)];
                $colorId = $colorIds[array_rand($colorIds)];
                $key = $sizeId.'-'.$colorId;

                if (isset($usedCombinations[$key])) {
                    $i--;

                    continue;
                }
                $usedCombinations[$key] = true;

                ProductVariant::factory()->create([
                    'product_id' => $product->id,
                    'size_id' => $sizeId,
                    'color_id' => $colorId,
                ]);
            }

            ProductPicture::factory(rand(1, 3))->create([
                'product_id' => $product->id,
            ]);

            $product->variants->each(function ($variant) {
                $count = rand(0, 3);
                if ($count > 0) {
                    VariantPicture::factory($count)->create([
                        'product_variant_id' => $variant->id,
                    ]);
                }
            });
        });

        $variantIds = ProductVariant::pluck('id')->toArray();

        $this->call(AdminUserSeeder::class);

        User::factory(5)->create();

        Customer::factory(200)->create();

        Coupon::factory(20)->create();

        Cart::factory(50)->create()->each(function ($cart) use ($variantIds) {
            $count = rand(1, 5);
            for ($i = 0; $i < $count; $i++) {
                CartProducts::factory()->create([
                    'cart_id' => $cart->id,
                    'product_variants_id' => $variantIds[array_rand($variantIds)],
                ]);
            }
        });

        Order::factory(500)->create()->each(function ($order) use ($variantIds) {
            $count = rand(1, 4);
            for ($i = 0; $i < $count; $i++) {
                OrderProducts::factory()->create([
                    'order_id' => $order->id,
                    'product_variants_id' => $variantIds[array_rand($variantIds)],
                ]);
            }
        });

        Visitor::factory(500)->create();

        MailingList::factory(80)->create();

        Visit::factory(2000)->create();
    }
}
