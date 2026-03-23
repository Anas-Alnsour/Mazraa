<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmBlockedDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'date',
        'shift',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the farm that owns the blocked date.
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
