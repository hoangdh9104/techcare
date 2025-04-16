<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShipper extends Model
{
    use HasFactory;
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    // public function shippingAddress()
    // {
    //     return $this->hasOne(ShippingAddress::class, 'order_shipper_id');
    // }
    // public function orderItems()
    // {
    //     return $this->hasMany(OrderItem::class, 'order_shipper_id');
    // }

}
