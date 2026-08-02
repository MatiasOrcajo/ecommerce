<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function calculateProductPriceWithDiscount(Product $product): float
    {
        return ($product->price * $product->discount) / 100;
    }

    public function getVariants(Product $product): array
    {
        $variants = $product->variants()
            ->with(['color', 'size', 'pictures' => fn ($q) => $q->orderBy('order')])
            ->get();

        $colors = $variants
            ->groupBy(fn ($v) => $v->color->name)
            ->map(function ($group) {
                $best = $group->first(fn ($v) => $v->pictures->isNotEmpty()) ?? $group->first();

                return [
                    'id' => $best->id,
                    'color' => $best->color->hex,
                    'color_name' => $best->color->name,
                    'pics' => [
                        'paths' => $best->pictures->pluck('path')->values()->toArray(),
                    ],
                ];
            })
            ->values()
            ->toArray();

        $sizes = $variants
            ->map(fn ($v) => ['size' => $v->size->name])
            ->unique('size')
            ->values()
            ->toArray();

        $variantsArray = $variants->map(fn ($v) => [
            'size' => $v->size->name,
            'color' => $v->color->hex,
            'stock' => $v->stock,
        ])->toArray();

        return [
            'availableColors' => $colors,
            'availableSizes' => $sizes,
            'productsVariantsArray' => $variantsArray,
            'youtube_link' => $product->youtube_link,
        ];
    }

    public function productsData($products): array
    {
        $ids = $products->pluck('id');

        $products = Product::whereIn('id', $ids)
            ->with(['variants' => fn ($q) => $q
                ->whereHas('pictures')
                ->with(['color', 'pictures' => fn ($q) => $q->orderBy('order')]),
            ])
            ->get()
            ->keyBy('id');

        $data = [];
        foreach ($products as $product) {
            $data[$product->id] = [
                'product' => [
                    'name' => $product->name,
                    'price' => $product->price,
                    'discount' => $product->discount,
                    'discount_until' => $product->discount_until,
                    'slug' => $product->slug,
                ],
                'colors' => $product->variants
                    ->groupBy(fn ($v) => $v->color->name)
                    ->map(fn ($group, $name) => [
                        'name' => $name,
                        'hex' => $group->first()->color->hex,
                        'paths' => $group->first()->pictures->pluck('path')->take(2)->values()->toArray(),
                    ])
                    ->values()
                    ->toArray(),
            ];
        }

        return $data;
    }
}
