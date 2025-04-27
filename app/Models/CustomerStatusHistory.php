<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'reason', 'updated_by', 'changed_at'];
    public $timestamps = false;
    public function customer()
    {
        return $this->belongsTo(User::class);
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    
}
