<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PaymentHistory extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $guarded = [];

    // user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // payment
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    // status
    public function getStatusAttribute($value)
    {
        return ucfirst($value); // Capitalize the status value
    }
}
