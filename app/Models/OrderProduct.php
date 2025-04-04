<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    protected $table = 'order_products'; // Đảm bảo tên bảng đúng

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
