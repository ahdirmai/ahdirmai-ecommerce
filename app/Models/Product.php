<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    // Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Cart Items
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Order Items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Reviews
    public function variants()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function details()
    {
        return $this->hasMany(ProductDetail::class);
    }
}
