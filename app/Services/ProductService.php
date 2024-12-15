<?php

namespace App\Services;


use App\Models\Product;

class ProductService {


    public function calculateProductPriceWithDiscount(Product $product)
    {
        return ($product->price * $product->discount) / 100;
    }

}
