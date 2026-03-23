<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'price',
        'stock',
        'image'
    ];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function orders()
    {
        return $this->hasMany(SupplyOrder::class);
    }
}
