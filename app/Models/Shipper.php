<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipper extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'banner',
        'name',
        'phone',
        'email',
        'address',
        'description',
        'fb_link',
        'tw_link',
        'insta_link'
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
