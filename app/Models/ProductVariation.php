<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $guarded = [];

    // Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessor for attribute_icon

}
