<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartProducts;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\MailingList;
use App\Models\Order;
use App\Models\OrderProducts;
use App\Models\Picture;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Category::factory(20)->create();

        Product::factory(100)->create()->each(function ($product) {
            ProductVariant::factory()->create(['product_id' => $product->id]);
            ProductVariant::factory()->create(['product_id' => $product->id]);
            ProductVariant::factory(rand(1, 4))->create(['product_id' => $product->id]);

            Picture::factory(rand(1, 5))->create([
                'product_id' => $product->id,
                'product_variant_id' => $product->sizes()->inRandomOrder()->first()?->id,
            ]);
        });

        $variantIds = ProductVariant::pluck('id')->toArray();

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
