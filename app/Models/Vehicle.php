<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'type',
        'license_plate',
        'capacity',
        'status',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }
}
