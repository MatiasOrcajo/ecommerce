<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartProducts;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderProducts;
use App\Models\Picture;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Seed Categories
        Category::factory(10)->create();

        // Seed Products
        Product::factory(50)->create();

        // Seed Users
        User::factory(20)->create();

        // Seed Carts and CartProducts
        Cart::factory(20)->create()->each(function ($cart) {
            CartProducts::factory(3)->create(['cart_id' => $cart->id]);
        });

        // Seed Coupons
        Coupon::factory(10)->create();

        // Seed Orders and OrderProducts
        Order::factory(20)->create()->each(function ($order) {
            OrderProducts::factory(3)->create(['order_id' => $order->id]);
        });

        // Seed Pictures for Products
        Product::all()->each(function ($product) {
            Picture::factory(3)->create(['product_id' => $product->id]);
        });
    }
}
