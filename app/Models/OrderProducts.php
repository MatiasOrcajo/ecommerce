<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'order_id',
        'quantity',
        'unit_price',
        'subtotal',
        'total_amount',
        'discount',
    ];


    /**
     * Defines a relationship where this model belongs to a Product.
     */
    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
