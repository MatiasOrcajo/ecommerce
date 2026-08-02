<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    private static int $slugCounter = 0;

    public function definition()
    {
        $names = [
            'Vestido Floral PRIMAVERA',
            'Blusa Seda NATURAL',
            'Pantalon Palazzo ELEGANCE',
            'Campera SIENA Cuero',
            'Remera Basica COTTON',
            'Falda Plisada LUNA',
            'Jean Recto CLASSIC Denim',
            'Top Deportivo ACTIVE FIT',
            'Mono Enterizo VERANO',
            'Sweater Tejido KNIT LUXE',
            'Chaqueta Denim URBAN STYLE',
            'Vestido Largo NOCHE FIESTA',
            'Pantalon Jogger CASUAL SOFT',
            'Camisa Lino NATURAL RELAX',
            'Short Cintura Alta TREND',
            'Blazer Sastre OFFICE CHIC',
            'Pollera Tubo VINTAGE',
            'Cardigan Largo BOHO',
            'Remera Oversize URBAN',
            'Vestido Midi ESTAMPA',
            'Pantalon Cargo UTILITY',
            'Body Mangas Largas SEXY',
            'Tapado Lana WINTER',
            'Bermuda Denim SUMMER',
            'Camisola Playa BEACH',
        ];

        $name = $this->faker->randomElement($names);
        $discount = $this->faker->randomElement([0, 0, 0, 5, 10, 15, 20, 25, 30]);
        $price = $this->faker->numberBetween(8000, 65000);

        return [
            'name' => $name,
            'category_id' => fn () => Category::query()->inRandomOrder()->value('id') ?? Category::factory(),
            'price' => $price,
            'discount' => $discount,
            'discount_until' => $discount > 0
                ? $this->faker->optional(0.6)->dateTimeBetween('now', '+2 months')
                : null,
            'sales' => $this->faker->numberBetween(0, 500),
            'visits' => $this->faker->numberBetween(100, 12000),
            'description' => ucfirst($this->faker->words($this->faker->numberBetween(8, 25), true).'.'),
            'sizes_description' => $this->faker->optional(0.4)->paragraph(),
            'model_reference' => $this->faker->optional(0.7)->bothify('MOD-####-????'),
            'featured' => $this->faker->boolean(15),
            'code' => 'PRD-'.strtoupper($this->faker->unique()->lexify('??????')).'-'.$this->faker->unique()->randomNumber(4),
            'slug' => fn (array $attrs) => Str::slug($attrs['name']).'-'.++self::$slugCounter,
            'visible' => $this->faker->boolean(90),
            'youtube_link' => $this->faker->optional(0.1)->url(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fn (array $attrs) => $this->faker->dateTimeBetween($attrs['created_at'], 'now'),
        ];
    }
}
