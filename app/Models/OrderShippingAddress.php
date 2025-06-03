<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShippingAddress extends Model
{
    protected $guarded = [];

    // Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Accessor for full address
    public function getFullAddressAttribute()
    {
        return "{$this->address_line1}, {$this->city}, {$this->state}, {$this->postal_code}, {$this->country}";
    }
}
