<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $guarded;


    /**
     * Retorna el cliente asociado a un carrito
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }


    /**
     * Retorna todos los prod asociados a un carrito
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     */
    public function products()
    {
        return $this->belongsToMany(CartProducts::class, 'cart_id');
    }


}
