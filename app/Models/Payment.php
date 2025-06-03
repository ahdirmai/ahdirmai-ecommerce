<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    // hasuuid
    public $incrementing = false;
    protected $keyType = 'string';
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = \Str::uuid();
        });
    }
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
    // payment history
    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class);
    }
}
