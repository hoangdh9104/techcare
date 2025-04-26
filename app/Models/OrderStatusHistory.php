<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'reason',
        'updated_by',
        'changed_at'
    ];

    protected $appends = ['updater_role'];

    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getUpdaterRoleAttribute()
    {
        if (!$this->updated_by) {
            return 'Hệ thống';
        }

        if (!$this->user) {
            return 'Không xác định';
        }

        return match($this->user->role) {
            'admin' => 'Quản trị viên',
            'shipper' => 'Shipper',
            default => 'Người dùng'
        };
    }
}
