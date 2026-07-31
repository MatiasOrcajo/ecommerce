<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderProducts>
 */
class OrderProductsFactory extends Factory
{
    public function definition()
    {
        $quantity = $this->faker->numberBetween(1, 4);
        $unitPrice = $this->faker->randomFloat(2, 5000, 45000);
        $discount = $this->faker->optional(0.3)->randomFloat(2, 0, $unitPrice * 0.3);

        return [
            'order_id' => Order::factory(),
            'product_variants_id' => ProductVariant::factory(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount' => $discount,
            'total' => fn (array $attrs) => round(
                $attrs['quantity'] * $attrs['unit_price'] - ($attrs['discount'] ?? 0),
                2
            ),
            'created_at' => now(),
        ];
    }
}
