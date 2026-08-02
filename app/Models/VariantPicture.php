<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantPicture extends Model
{
    use HasFactory;

    protected $table = 'variant_pictures';

    protected $fillable = ['product_variant_id', 'path', 'order'];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
