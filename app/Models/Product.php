<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = ['name', 'category_id', 'price', 'discount', 'description', 'discount_until', 'stock'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


    /**
     * Retorna todos los registros de order_products asociados a un producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderProducts::class, 'product_id');
    }


    /**
     * Retorna todas las imagenes asociadas a un prod
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pictures()
    {
        return $this->hasMany(Picture::class, 'product_id')->orderBy('order');
    }


    /**
     * Retorna todos los carritos que tengan dicho producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cartProducts()
    {
        return $this->hasMany(CartProducts::class, 'product_id');
    }


    public function sizes()
    {
        return $this->hasMany(ProductSize::class, 'product_id');
    }






}
