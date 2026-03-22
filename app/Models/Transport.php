<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transport_type',
        'passengers',
        'driver_id',
        'farm_id',
        'start_and_return_point',
        //'destination',
        'distance',
        'price',
        'Farm_Arrival_Time',
        'Farm_Departure_Time',
        'notes',
        'status',
        'pickup_lat',
        'pickup_lng',
        'commission_amount',
        'net_company_amount',
    ];

    /**
     * العلاقة مع الزبون اللي حجز الرحلة
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * العلاقة مع السائق اللي رح يوصل الرحلة
     */
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * العلاقة مع المزرعة المقصودة
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
