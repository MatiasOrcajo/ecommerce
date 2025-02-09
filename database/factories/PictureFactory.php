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
            'path' => $this->faker->randomElement(['https://sublitextil.com.ar/wp-content/uploads/2019/01/Remera-sublimar-hombre-.jpg', 'https://tascani.vtexassets.com/arquivos/ids/175953/tascani19507921.jpg?v=637985814929000000', 'https://dcdn.mitiendanube.com/stores/002/364/252/products/whatsapp-image-2023-04-06-at-15-45-17-11-64fae6cfeeeb6d8df816808072429618-1024-1024.jpeg', 'https://www.stockcenter.com.ar/on/demandware.static/-/Sites-365-dabra-catalog/default/dwacd4d4f6/products/AD_GM5534/AD_GM5534-1.JPG', 'https://www.shopcoveusa.com/cdn/shop/files/BlackShortsFrontFinal2_1_1024x1024.jpg?v=1718226693']),
            'created_at' => now(),
        ];
    }
}
