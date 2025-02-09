<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'category_id' => \App\Models\Category::factory(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'discount' => 10,
            'sales' => $this->faker->numberBetween(0, 500),
            'visits' => $this->faker->numberBetween(0, 10000),
            'stock' => $this->faker->numberBetween(0, 100),
            'description' => $this->faker->sentence(),
            'specs' => $this->faker->words(5, true),
            'code' => $this->faker->unique()->ean13(),
            'brand' => $this->faker->company(),
            'created_at' => now(),
        ];
    }
}
