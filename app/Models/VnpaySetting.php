<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VnpaySetting extends Model
{
    use HasFactory;
    protected $table = 'vnpay_settings';
    protected $fillable = [
        'status',
        'mode',
        'tmn_code',
        'hash_secret',
        'payment_url',
        'return_url',
        'ipn_url',
    ];

}
