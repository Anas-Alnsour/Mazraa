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
        'start_point',
        'destination',
        'distance',
        'price',
        'departure_time',
        'arrival_time',
        'notes',
        'status',
    ];
    public function driver()
{
    return $this->belongsTo(Driver::class);
}

}
