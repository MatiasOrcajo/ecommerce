<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Picture>
 */
class PictureFactory extends Factory
{
    public function definition()
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'order' => $this->faker->numberBetween(1, 5),
            'path' => $this->faker->imageUrl(),
            'created_at' => now(),
        ];
    }
}
