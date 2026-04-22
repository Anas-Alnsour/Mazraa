<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'supply_id',
        'company_id',
        'stock',
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }
}
