<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 👈 الاستدعاء موجود

class Farm extends Model
{
    use HasFactory, SoftDeletes; // 👈 تم تفعيل الـ SoftDeletes هنا

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

       /**
     * Get the blocked dates for the farm.
     */
    public function blockedDates()
    {
        return $this->hasMany(FarmBlockedDate::class);
    }

    // ==================================================
    // علاقات نظام التقييم (Reviews)
    // ==================================================
    public function reviews()
    {
        return $this->morphMany(\App\Models\Review::class, 'reviewable');
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

}
