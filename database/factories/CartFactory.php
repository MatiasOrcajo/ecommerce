<?php

namespace Database\Factories;

use App\Models\Constants;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    public function definition()
    {
        $status = $this->faker->randomElement([
            Constants::ACTIVE,
            Constants::ACTIVE,
            Constants::ACTIVE,
            Constants::ABANDONED,
            Constants::PENDING,
            Constants::CONFIRMED,
            Constants::EMPTY,
            Constants::SAVED,
        ]);

        $createdAt = $this->faker->dateTimeBetween('-3 months', 'now');

        return [
            'customer_id' => $this->faker->boolean(60) ? Customer::factory() : null,
            'status' => $status,
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween($createdAt, 'now'),
        ];
    }
}
