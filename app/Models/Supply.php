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
    'company_id',
    'name',
    'description',
    'category', 
    'price',
    'stock',
    'image',
];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function orders()
    {
        return $this->hasMany(SupplyOrder::class);
    }
}
