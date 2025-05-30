<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    // user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // payment method
    // public function paymentMethod()
    // {
    //     return $this->belongsTo(PaymentMethod::class);
    // }

    // status
    public function getStatusAttribute($value)
    {
        return ucfirst($value); // Capitalize the status value
    }
}
