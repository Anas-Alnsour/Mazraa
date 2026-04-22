<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // 👈 تم إضافة الاستدعاء هنا

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes; // 👈 تم تفعيل الـ SoftDeletes هنا

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'governorate',
        'bank_name',
        'account_holder_name',
        'iban',
        'company_id',           // 👈 مهم جداً لربط السواقين بالشركات
        'transport_vehicle_id', // 👈 1-to-1 permanent vehicle link for transport drivers
        'shift',                // [Geo-Dispatch] morning or evening
        'latitude',             // [Geo-Dispatch] standby location
        'longitude',            // [Geo-Dispatch] standby location
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

    // 1b. علاقة السائق بالمركبة المخصصة له بشكل دائم (1-to-1)
    public function transportVehicle()
    {
        return $this->belongsTo(\App\Models\Vehicle::class, 'transport_vehicle_id');
    }

    // 2. علاقة صاحب المزرعة بالمزارع تبعته
    public function farms()
    {
        return $this->hasMany(\App\Models\Farm::class, 'owner_id');
    }

    // 3. علاقة شركة التوريد بالمخزون تبعها
    public function supplyInventories()
    {
        return $this->hasMany(\App\Models\SupplyInventory::class, 'company_id');
    }

    // 4. Financial transactions ledger entries for this user
    public function financialTransactions()
    {
        return $this->hasMany(\App\Models\FinancialTransaction::class, 'user_id');
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

    // ==================================================
    // [Sprint 4] Dispatch Algorithm Relationships & Scopes
    // ==================================================

    /**
     * Scope a query to only include users of a given role.
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Transport jobs assigned to this driver.
     */
    public function transportDriverJobs()
    {
        return $this->hasMany(\App\Models\Transport::class, 'driver_id');
    }

    /**
     * Supply orders assigned to this driver.
     */
    public function supplyDriverJobs()
    {
        return $this->hasMany(\App\Models\SupplyOrder::class, 'driver_id');
    }

    // ==================================================
    // اضافة علاقة 1-to-1 بين السائق والمركبة المخصصة له بشكل دائم  
    // ==================================================
    public function vehicle()
    {
        return $this->hasOne(Vehicle::class, 'id', 'transport_vehicle_id');
    }
}
