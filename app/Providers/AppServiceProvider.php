<?php

namespace App\Providers;

use App\Models\Transport;
use App\Policies\TransportPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\FarmBooking;
use App\Models\Farm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model; // 👈 إضافة الاستدعاء اللي طلبه Jules

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
        // 👈 حماية النظام من الـ N+1 Queries في بيئة التطوير (كود Jules)
        Model::preventLazyLoading(! app()->isProduction());
        Model::preventSilentlyDiscardingAttributes(! app()->isProduction());
        Model::preventAccessingMissingAttributes(! app()->isProduction());

        // Share pending bookings count with all relevant layouts (كودك الأصلي)
        View::composer(['layouts.navigation', 'components.dashboard-layout'], function ($view) {
            if (Auth::check() && Auth::user()->role === 'farm_owner') {
                $farmIds = Farm::where('owner_id', Auth::id())->pluck('id');
                $pendingCount = FarmBooking::whereIn('farm_id', $farmIds)
                    ->where('status', 'pending')
                    ->count();
                $view->with('pendingApprovalCount', $pendingCount);
            }
        });

        // 🔗 Global Pagination Design Link - Directing Laravel to use our custom SaaS view
        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.custom');
    }

    protected $policies = [
        Transport::class => TransportPolicy::class,
    ];
}
