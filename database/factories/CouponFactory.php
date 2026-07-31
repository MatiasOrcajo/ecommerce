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
        $discount = $this->faker->randomElement([5, 10, 15, 20, 25, 30, 40]);

        return [
            'code' => strtoupper($this->faker->bothify('ATICA-####-????')),
            'discount' => $discount,
            'quantity' => $this->faker->numberBetween(10, 500),
            'valid_until' => $this->faker->dateTimeBetween('-1 month', '+3 months'),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
