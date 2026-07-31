<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartProducts>
 */
class CartProductsFactory extends Factory
{
    public function definition()
    {
        return [
            'cart_id' => Cart::factory(),
            'product_variants_id' => ProductVariant::factory(),
            'created_at' => now(),
        ];
    }
}
