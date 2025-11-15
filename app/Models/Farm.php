<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FarmImage;
use App\Models\FarmBooking;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Farm extends Model
{
    use HasFactory;
    public function images()
{
    return $this->hasMany(FarmImage::class);
}

public function bookings()
{
    return $this->hasMany(FarmBooking::class);
}

public function favoritedBy()
{
    return $this->belongsToMany(\App\Models\User::class, 'favorites')->withTimestamps();
}



}
