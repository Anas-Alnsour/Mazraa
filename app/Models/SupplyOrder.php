<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyOrder extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'supply_id', 'quantity', 'total_price', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }
}
