<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'location', 'capacity', 'description', 'main_image',
        'owner_id', 'rating', 'commission_rate', 'latitude', 'longitude',
        'is_approved', 'governorate', 'location_link',
        'price_per_night', 'price_per_morning_shift', 'price_per_evening_shift', 'price_per_full_day'
    ];

    public function images()
    {
        return $this->hasMany(FarmImage::class);
    }

    public function bookings()
    {
        return $this->hasMany(FarmBooking::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function blockedDates()
    {
        return $this->hasMany(FarmBlockedDate::class);
    }

    // علاقات نظام التقييم (Reviews)
    public function reviews()
    {
        return $this->morphMany(\App\Models\Review::class, 'reviewable');
    }

    public function getAverageRatingAttribute()
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round($avg, 1) : 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }
}
