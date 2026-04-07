<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transport extends Model
{
    use HasFactory, SoftDeletes; // 👈 تم تفعيل الـ SoftDeletes هنا

    protected $fillable = [
        'company_id',
        'user_id',
        'transport_type',
        'passengers',
        'driver_id',
        'vehicle_id',
        'farm_id',
        'farm_booking_id',
        'start_and_return_point',
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
        'origin_governorate',
        'destination_governorate',
        'return_scheduled_at',
    ];

    protected $casts = [
        'Farm_Arrival_Time' => 'datetime',
        'Farm_Departure_Time' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    /**
     * The FarmBooking that this transport trip was created for.
     * Links via the farm_booking_id foreign key.
     */
    public function farmBooking()
    {
        return $this->belongsTo(FarmBooking::class, 'farm_booking_id');
    }
}
