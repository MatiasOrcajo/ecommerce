<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'order_date' => $this->faker->dateTimeBetween('2022-01-01', now()),
            'total_amount' => $this->faker->randomFloat(2, 50, 1000),
//            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
            'status' => $this->faker->randomElement(['completed']),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal']),
            'shipping_address' => $this->faker->address(),
            'payment_id' => $this->faker->creditCardNumber,
            'coupon_id' => \App\Models\Coupon::factory(),
            'created_at' => now(),
        ];
    }
}
