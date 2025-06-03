<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{

    protected $guarded = [];

    // cart
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // total price

}
