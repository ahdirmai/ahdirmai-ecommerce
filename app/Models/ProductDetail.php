<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    protected $guarded = [];

    // Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessor for attribute_icon
    // public function getAttributeIconAttribute($value)
    // {
    //     return $value ? asset('storage/' . $value) : null; // Assuming the icon is stored in the storage directory
    // }
}
