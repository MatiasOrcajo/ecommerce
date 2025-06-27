<?php

namespace App\Services;


use App\Models\Picture;
use App\Models\Product;

class ProductService {


    public function calculateProductPriceWithDiscount(Product $product)
    {
        return ($product->price * $product->discount) / 100;
    }


    /**
     * @param Product $product
     * @return array
     */
    public function getVariants(Product $product)
    {
        $productVariants = $product->variants;

        $availableColors = $productVariants->select(["id", "color", "color_name"])
            ->unique('color_name')
            ->values()
            ->toArray();

        $pictures = Picture::whereIn('product_variant_id', $productVariants->pluck('id'))
            ->orderBy('order')
            ->get();

        $availableColors = collect($availableColors)
            ->transform(function ($color) use ($pictures) {
                $paths = [];
                $pics = $pictures->where('product_variant_id', $color['id']);
                $paths["paths"] = $pics->map(fn ($pic) => $pic->path);
                $color['pics'] = $paths;

                return $color;
            })
            ->toArray();

        $availableSizes = $productVariants->select("size")
            ->unique('size')
            ->toArray();

        $productsVariantsArray = $productVariants->select(["size", "color", "stock"])->toArray();

        return [
            "availableColors" => $availableColors,
            "availableSizes" => $availableSizes,
            "productsVariantsArray" => $productsVariantsArray,
        ];
    }

}
