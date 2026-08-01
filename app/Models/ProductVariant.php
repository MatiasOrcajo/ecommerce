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

    public function findFirstSimilarVariantWithPicture(): ?string
    {
        // 1) Prioriza foto del propio variant
        $ownPath = $this->pictures()->select('path')->first()?->path;

        if ($ownPath) {

            return $ownPath;
        }

        // 2) Busca un variant similar que tenga fotos, cargándolas con eager loading
        $variant = self::query()
            ->where('product_id', $this->product_id)
            ->where('color', $this->color)
            ->whereHas('pictures')
            ->with(['pictures' => fn ($q) => $q->select('id', 'product_variant_id', 'path')->orderBy('id')])
            ->first();

        $path = $variant?->pictures->first()?->path;

        return $path ?: null;
    }
}
