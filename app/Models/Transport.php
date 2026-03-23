<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',   // ضفناه جديد
        'user_id',
        'transport_type',
        'passengers',
        'driver_id',
        'vehicle_id',   // ضفناه جديد
        'farm_id',
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
}
