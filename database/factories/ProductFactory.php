<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition()
    {
        $names = [
            "Campera SIENA",
            "Campera SILVER",
            "Campera GOLD",
            "Campera PLATINUM",
        ];

        $name = $names[$this->faker->numberBetween(0, 3)];

        return [
            'name' => $name,
            'category_id' => \App\Models\Category::factory(),
            'price' => 1000,
            'discount' => $this->faker->randomElement([0, 10]),
            'sales' => $this->faker->numberBetween(0, 500),
            'visits' => $this->faker->numberBetween(0, 10000),
            'stock' => $this->faker->numberBetween(0, 100),
            'description' => $this->faker->sentence(),
            'specs' => $this->faker->words(5, true),
            'code' => $this->faker->unique()->ean13(),
            'brand' => $this->faker->company(),
            'slug' => Str::slug($name),
            'created_at' => now(),
        ];
    }
}
