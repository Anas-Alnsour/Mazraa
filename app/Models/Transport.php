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
    ];
    public function driver()
{
    return $this->belongsTo(Driver::class);
}

public function farm()
{
    return $this->belongsTo(Farm::class);
}

}
