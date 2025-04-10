<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'user_id',
        'sub_total',
        'amount',
        'currency_name',
        'currency_icon',
        'product_qty',
        'payment_method',
        'payment_status',
        'order_address',
        'shipping_method',
        'coupon',
        'order_status', // Thêm dòng này
        'created_at',
        'updated_at'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id')->orderBy('changed_at', 'desc');
    }
}
