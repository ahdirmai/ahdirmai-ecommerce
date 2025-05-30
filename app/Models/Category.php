<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];


    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getFormattedTypeAttribute()
    {
        return ucfirst($this->type);
    }
    // products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
