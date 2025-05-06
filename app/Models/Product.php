<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [

        'warranty_code', // Mã bảo hành
        'warranty_duration',
        'warranty_expiration_date', // Ngày hết hạn bảo hành
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function productImageGalleries()
    {
        return $this->hasMany(ProductImageGallery::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function getIsDiscountedAttribute()
    {
        return $this->offer_price > 0 &&
            now()->toDateString() >= $this->offer_start_date &&
            now()->toDateString() <= $this->offer_end_date;
    }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function variantCombinations()
    {
        return $this->hasMany(ProductVariantCombination::class);
    }
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Tự động áp dụng điều kiện status=1 cho mọi query ngoài admin
        static::addGlobalScope('active', function ($builder) {
            if (!request()->is('admin/*')) {
                $builder->where('status', 1);
            }
        });
    }
}
