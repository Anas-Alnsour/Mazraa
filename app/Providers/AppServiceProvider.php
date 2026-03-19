<?php

namespace App\Providers;

use App\Models\Transport;
use App\Policies\TransportPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\FarmBooking;
use App\Models\Farm;
use Illuminate\Support\Facades\Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
public function boot(): void
{
    // مشاركة عدد الحجوزات المعلقة مع جميع الصفحات ليعمل الجرس دائماً
    View::composer('layouts.navigation', function ($view) {
        if (Auth::check() && Auth::user()->role === 'farm_owner') {
            $farmIds = Farm::where('owner_id', Auth::id())->pluck('id');
            $pendingCount = FarmBooking::whereIn('farm_id', $farmIds)
                ->where('status', 'pending')
                ->count();
            $view->with('pendingApprovalCount', $pendingCount);
        }
    });
}

protected $policies = [
    Transport::class => TransportPolicy::class,

];


}
