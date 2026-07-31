<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    public function definition()
    {
        $dates = [
            $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
        ];

        return [
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'visited_at' => $this->faker->dateTimeBetween('-90 days', 'now')->format('Y-m-d'),
            'created_at' => $this->faker->dateTimeBetween('-90 days', 'now'),
            'updated_at' => now(),
        ];
    }
}
