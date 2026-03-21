<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

    // تمت إضافة is_approved هنا لتجنب خطأ الـ Mass Assignment عند الموافقة على المزرعة
    protected $fillable = [
        'name', 'location', 'price_per_night', 'capacity',
        'description', 'main_image', 'owner_id', 'rating',
        'commission_rate', 'latitude', 'longitude', 'is_approved'
    ];

    public function images()
    {
        return $this->hasMany(FarmImage::class);
    }

    public function bookings()
    {
        return $this->hasMany(FarmBooking::class);
    }

    /**
     * Get the owner of the farm.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
