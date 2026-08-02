<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = ['name', 'category_id', 'price', 'discount', 'description', 'discount_until', 'featured', 'sizes_description', 'model_reference', 'slug', 'visible', 'youtube_link'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProducts::class, 'product_id');
    }

    public function productPictures()
    {
        return $this->hasMany(ProductPicture::class, 'product_id')->orderBy('order');
    }

    public function pictures()
    {
        return $this->productPictures();
    }

    public function cartProducts()
    {
        return $this->hasMany(CartProducts::class, 'product_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
