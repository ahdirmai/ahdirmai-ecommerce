<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $guarded = [];

    // User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for full address
    public function getFullAddressAttribute()
    {
        return "{$this->address_line1}, {$this->city}, {$this->state}, {$this->postal_code}, {$this->country}";
    }
}
