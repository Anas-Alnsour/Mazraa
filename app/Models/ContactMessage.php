<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    // 1. تحديد اسم الجدول (لأن اسم الجدول عندك contact_us وليس contact_messages)
    protected $table = 'contact_us';

    // 2. الحقول القابلة للتعبئة (Mass Assignment)
    protected $fillable = [
        'name',
        'email',
        'message',
        'status', // read or unread
    ];
}
