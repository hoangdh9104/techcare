<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantCombination extends Model
{
    use HasFactory;
    // Đặt tên bảng
    protected $table = 'product_variant_combinations';
    // Các thuộc tính có thể gán giá trị
    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'value',
        'price',
        'quantity',
        'status',
        'image', // Thêm 'image' vào fillable
    ];

    // Nếu bạn muốn làm việc với cột 'value' dưới dạng mảng JSON, bạn có thể sử dụng:
    protected $casts = [
        'value' => 'array',
    ];

    // Quan hệ với bảng product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
