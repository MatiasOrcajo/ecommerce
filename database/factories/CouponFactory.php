<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    public function definition()
    {
        return [
            'code' => $this->faker->bothify('COUPON-####'),
            'discount' => $this->faker->numberBetween(5, 50),
            'quantity' => $this->faker->numberBetween(1, 300),
            'valid_until' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'created_at' => now(),
        ];
    }
}
