<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MailingList>
 */
class MailingListFactory extends Factory
{
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->optional(0.7)->lastName(),
            'birthdate' => $this->faker->optional(0.4)->date('d/m/Y', '-18 years'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
