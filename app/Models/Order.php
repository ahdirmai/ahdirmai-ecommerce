<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'order_number'; // Assuming you want to use order_number as the route key
    }

    // user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }


    // shipping address
    public function OrderShippingAddress()
    {
        return $this->hasOne(OrderShippingAddress::class);
    }
    // payment

    // total price
    // public function getTotalPriceAttribute()
    // {
    //     return $this->items->sum(function ($item) {
    //         return $item->total_price; // Assuming OrderItem has a total_price attribute
    //     });
    // }

    // total quantity
    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('quantity'); // Assuming OrderItem has a quantity attribute
    }
}
