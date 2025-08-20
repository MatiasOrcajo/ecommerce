<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';

    use hasFactory;


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }

    public function findFirstSimilarVariantWithPicture()
    {
        return ProductVariant::where('product_id', $this->product_id)
                        ->where('color', $this->color)
                        ->whereHas('pictures')
                        ->first()
                        ->pictures()
                        ->first()
                        ->path;
    }

}
