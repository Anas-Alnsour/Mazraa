<?php

namespace App\Providers;

use App\Models\Transport;
use App\Policies\TransportPolicy;
use Illuminate\Support\ServiceProvider;

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
        //
    }

protected $policies = [
    Transport::class => TransportPolicy::class,
    
];


}
