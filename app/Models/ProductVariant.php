<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';

    protected $fillable = ['product_id', 'color_id', 'size_id', 'sku', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function pictures()
    {
        return $this->hasMany(VariantPicture::class, 'product_variant_id')->orderBy('order');
    }

    public function findFirstSimilarVariantWithPicture(): ?string
    {
        $ownPath = $this->pictures()->value('path');

        if ($ownPath) {
            return $ownPath;
        }

        $variant = self::query()
            ->where('product_id', $this->product_id)
            ->where('color_id', $this->color_id)
            ->whereHas('pictures')
            ->with(['pictures' => fn ($q) => $q->select('id', 'product_variant_id', 'path')->orderBy('order')])
            ->first();

        return $variant?->pictures->first()?->path;
    }
}
