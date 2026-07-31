<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $colors = [
            ['hex' => '#000000', 'name' => 'Negro'],
            ['hex' => '#FFFFFF', 'name' => 'Blanco'],
            ['hex' => '#C41E3A', 'name' => 'Rojo'],
            ['hex' => '#2E4A8E', 'name' => 'Azul Marino'],
            ['hex' => '#D4AF37', 'name' => 'Dorado'],
            ['hex' => '#B76E79', 'name' => 'Rosa Viejo'],
            ['hex' => '#A0A0A0', 'name' => 'Gris'],
            ['hex' => '#8B4513', 'name' => 'Marron'],
            ['hex' => '#D2B48C', 'name' => 'Beige'],
            ['hex' => '#6B8E23', 'name' => 'Verde Oliva'],
            ['hex' => '#FF69B4', 'name' => 'Rosa'],
            ['hex' => '#1B1B3A', 'name' => 'Azul Medianoche'],
        ];

        $color = $this->faker->randomElement($colors);

        return [
            'product_id' => Product::factory(),
            'size' => $this->faker->randomElement($sizes),
            'color' => $color['hex'],
            'color_name' => $color['name'],
            'stock' => $this->faker->numberBetween(0, 30),
            'created_at' => now(),
        ];
    }
}
