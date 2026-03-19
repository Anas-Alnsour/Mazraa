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
        'status',
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
