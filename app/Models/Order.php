<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

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
