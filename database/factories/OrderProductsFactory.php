<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderProduct>
 */
class OrderProductsFactory extends Factory
{
    public function definition()
    {
        return [
            'order_id' => \App\Models\Order::factory(),
            'product_id' => \App\Models\Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'unit_price' => $this->faker->randomFloat(2, 10, 500),
            'subtotal' => function (array $attributes) {
                return $attributes['quantity'] * $attributes['unit_price'];
            },
            'total_amount' => function (array $attributes) {
                return $attributes['quantity'] * $attributes['unit_price'] - 10;
            },
            'created_at' => now(),
        ];
    }
}
