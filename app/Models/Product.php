<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
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

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }
}
