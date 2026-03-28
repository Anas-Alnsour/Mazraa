<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_type',
        'reference_id',
        'transaction_type',
        'amount',
        'description',
        'farm_id', // 👈 ضفناها عشان الكنترولر يقدر يمررها
        'status',  // 👈 وضفنا حالة الدفعة
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class, 'farm_id');
    }
}
