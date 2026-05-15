<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplyOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'supply_id',
        'booking_id',
        'quantity',
        'total_price',
        'status',
        'order_id',
        'latitude',
        'longitude',
        'delivery_address',
        'destination_governorate',
        'driver_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function booking()
    {
        return $this->belongsTo(FarmBooking::class, 'booking_id');
    }

    /**
     * هل يحق للمستخدم تعديل أو إلغاء الطلب؟
     * مسموح فقط خلال 10 دقائق من إنشاء الطلب.
     */
    public function canBeModifiedOrCancelled(): bool
    {
        return $this->created_at->diffInMinutes(\Carbon\Carbon::now()) <= 10;
    }
}
