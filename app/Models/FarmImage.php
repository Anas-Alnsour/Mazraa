<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmImage extends Model
{
    use HasFactory; // مهم جداً لعمل factory

    protected $fillable = [
        'farm_id',
        'image_url',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
