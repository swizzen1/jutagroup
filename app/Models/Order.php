<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{
    protected $fillable = [
        'user_id', 
        'district_id', 
        'payment_type', 
        'code', 
        'transaction_id', 
        'pay_status',
        'address', 
        'total'    
    ];

    public function products() 
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
