<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartProduct>
 */
class CartProductsFactory extends Factory
{
    public function definition()
    {
        return [
            'cart_id' => \App\Models\Cart::factory(),
            'product_id' => \App\Models\Product::factory(),
            'created_at' => now(),
        ];
    }
}
