<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SizeFactory extends Factory
{
    private static array $sizes = [
        'XXS' => 0,
        'XS' => 1,
        'S' => 2,
        'M' => 3,
        'L' => 4,
        'XL' => 5,
        'XXL' => 6,
        'XXXL' => 7,
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $names = array_keys(self::$sizes);
        $name = $names[self::$index % count($names)];
        self::$index++;

        return [
            'name' => $name,
            'sort_order' => self::$sizes[$name],
        ];
    }
}
