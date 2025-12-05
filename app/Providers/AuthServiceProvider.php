<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\SupplyOrder;
use App\Models\Transport;
use App\Policies\SupplyOrderPolicy;
use App\Policies\TransportPolicy;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\User::class => \App\Policies\AdminPolicy::class,
        SupplyOrder::class => SupplyOrderPolicy::class,
        Transport::class => TransportPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

    }
}
