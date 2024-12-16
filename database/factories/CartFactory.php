<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    public function definition()
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'status' => $this->faker->randomElement(['active', 'abandoned']),
            'created_at' => now(),
        ];
    }
}
