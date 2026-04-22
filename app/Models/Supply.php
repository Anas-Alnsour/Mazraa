<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes; // 👈 تمت إضافة الاستدعاء هنا

class Supply extends Model
{
    use HasFactory, SoftDeletes; // 👈 تم تفعيل الـ SoftDeletes هنا
protected $fillable = [
    'name',
    'description',
    'category', 
    'price',
    'image',
];

    public function inventories()
    {
        return $this->hasMany(SupplyInventory::class);
    }

    public function orders()
    {
        return $this->hasMany(SupplyOrder::class);
    }

    public function reviews()
    {
        return $this->morphMany(\App\Models\Review::class, 'reviewable');
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }
}
