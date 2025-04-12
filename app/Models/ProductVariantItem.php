<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantItem extends Model
{
    use HasFactory;
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
    protected $fillable = [
        'product_variant_id',
        'name',
        'price',
        'qty',
        'is_default',
        'status',
    ];
}
