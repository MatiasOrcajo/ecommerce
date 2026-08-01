<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variants_id',
        'order_id',
        'quantity',
        'unit_price',
        'total',
        'discount',
    ];

    /**
     * Defines a relationship where this model belongs to a Product.
     */
    public function productVariant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variants_id');
    }
}
