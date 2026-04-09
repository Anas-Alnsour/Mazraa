<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farm_id',
        'user_id',
        'start_time',
        'end_time',
        'event_type',
        'total_price',
        'commission_amount',
        'tax_amount',
        'net_owner_amount',
        'payment_status',
        'status',
        'stripe_payment_intent_id',
        'stripe_session_id',

        // Transport integration columns
        'requires_transport',
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

    /**
     * العلاقة مع طلبات المواصلات
     * هذه العلاقة ضرورية لعرض حالة النقل ومعلومات السائق في لوحة تحكم المستخدم
     */
    public function transport()
    {
        return $this->hasOne(Transport::class, 'farm_booking_id');
    }

    /**
     * العلاقة مع طلبات اللوازم (Supply Orders)
     * ضرورية لعرض اللوازم المطلوبة مع هذا الحجز في صفحة تفاصيل الحجز للمستخدم
     */
    public function supplyOrders()
    {
        return $this->hasMany(SupplyOrder::class, 'booking_id');
    }

    /**
     * هل يحق للمستخدم الدفع لشراء المنتجات الآن؟
     * مسموح فقط: خلال فترة الحجز، أو قبل الحجز بـساعتين كحد أقصى.
     */
    public function isWithinSupplyCheckoutWindow(): bool
    {
        $now = \Carbon\Carbon::now();
        // وقت بداية الحجز ناقص ساعتين
        $twoHoursBeforeStart = \Carbon\Carbon::parse($this->start_time)->subHours(2);
        $endTime = \Carbon\Carbon::parse($this->end_time);

        return $now->between($twoHoursBeforeStart, $endTime);
    }

}
