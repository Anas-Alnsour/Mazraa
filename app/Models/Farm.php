<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

protected $fillable = ['name', 'location', 'price_per_night', 'capacity', 'description', 'main_image', 'owner_id', 'rating', 'commission_rate', 'latitude', 'longitude'];

    public function images()
    {
        return $this->hasMany(FarmImage::class);
    }

    public function bookings()
    {
        return $this->hasMany(FarmBooking::class);
    }
}
