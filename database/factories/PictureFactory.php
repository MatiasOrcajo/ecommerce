<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Picture>
 */
class PictureFactory extends Factory
{
    public function definition()
    {
        $images = [
            'https://images.unsplash.com/photo-1434389677669-e08b4cda5a19?w=600',
            'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=600',
            'https://images.unsplash.com/photo-1485968579580-b6d095142e6e?w=600',
            'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=600',
            'https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?w=600',
            'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600',
            'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?w=600',
            'https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=600',
            'https://images.unsplash.com/photo-1551218372-a8789b81b253?w=600',
            'https://images.unsplash.com/photo-1445384763658-0400939829cd?w=600',
        ];

        return [
            'product_id' => Product::factory(),
            'product_variant_id' => $this->faker->boolean(60) ? ProductVariant::factory() : null,
            'order' => $this->faker->numberBetween(1, 5),
            'path' => $this->faker->randomElement($images),
            'created_at' => now(),
        ];
    }
}
