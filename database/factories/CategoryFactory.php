<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition()
    {
        $categories = [
            'Vestidos',
            'Blusas',
            'Pantalones',
            'Camperas',
            'Remeras',
            'Faldas',
            'Jeans',
            'Tops',
            'Monos',
            'Sweaters',
            'Chaquetas',
            'Shorts',
            'Blazers',
            'Polleras',
            'Cardigans',
            'Bodies',
            'Tapados',
            'Bermudas',
            'Accesorios',
            'Ofertas',
        ];

        $name = $this->faker->unique()->randomElement($categories);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'created_at' => now(),
        ];
    }
}
