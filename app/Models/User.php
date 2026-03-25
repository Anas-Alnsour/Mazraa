<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'role',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function favorites()
    {
        return $this->belongsToMany(\App\Models\Farm::class, 'favorites')->withTimestamps();
    }

    // ==================================================
    // علاقات الـ Multi-Vendor اللي بتحتاجها لوحة السوبر أدمن
    // ==================================================

    // 1. علاقة شركة المواصلات برحلاتها
    public function transportCompanyJobs()
    {
        return $this->hasMany(\App\Models\Transport::class, 'company_id');
    }

// 2. علاقة صاحب المزرعة بالمزارع تبعته
    public function farms()
    {
        return $this->hasMany(\App\Models\Farm::class, 'owner_id'); // 👈 التعديل هون
    }

    // 3. علاقة شركة التوريد بالمنتجات تبعتها
    public function supplies()
    {
        // ملاحظة: إذا كان موديل التوريد عندك اسمه SupplyItem استبدل كلمة Supply بـ SupplyItem
        return $this->hasMany(\App\Models\Supply::class, 'company_id');
    }

    // ==================================================
    // علاقات نظام التقييم (Reviews & Ratings)
    // ==================================================
    public function receivedReviews()
    {
        return $this->morphMany(\App\Models\Review::class, 'reviewable');
    }

    public function givenReviews()
    {
        return $this->hasMany(\App\Models\Review::class, 'user_id');
    }

    public function getCompanyRatingAttribute()
    {
        return $this->receivedReviews()->avg('rating') ?: 0;
    }
    
}
