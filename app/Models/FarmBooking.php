<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'user_id',
        'start_time',
        'end_time',
        'event_type',
        'total_price',
        'commission_amount',
        'tax_amount',
        'net_profit',
        'net_owner_amount', // 💡 هاد هو الحقل اللي بيعمل المشكلة
        'status',
        'requires_transport', // 💡 وحقول المواصلات ضفناهم كمان
        'transport_cost',
        'pickup_lat',
        'pickup_lng',
    ];

    /**
     * العلاقة مع المزرعة
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    /**
     * العلاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
