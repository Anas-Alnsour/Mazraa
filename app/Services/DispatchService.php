<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DispatchService
{
    /**
     * Assign a Transport Driver based on load-balancing.
     */
    public function assignTransportDriver(string $gov, ?string $shift = null): ?User
    {
        return $this->findHungriestDriver('transport_driver', $gov, $shift);
    }

    /**
     * Assign a Supply Driver based on load-balancing.
     */
    public function assignSupplyDriver(string $gov, ?string $shift = null): ?User
    {
        return $this->findHungriestDriver('supply_driver', $gov, $shift);
    }

    /**
     * Core Algorithm: Finds the driver with the least number of jobs this month.
     */
    private function findHungriestDriver(string $role, string $gov, ?string $shift): ?User
    {
        $now = Carbon::now();

        // Define the relationship name based on the role
        $relation = $role === 'transport_driver' ? 'transportDriverJobs' : 'supplyDriverJobs';

        // 💡 تحويل اسم العلاقة من CamelCase إلى snake_case لحل مشكلة الـ orderBy
        // example: 'supplyDriverJobs' becomes 'supply_driver_jobs_count'
        $relationCountColumn = Str::snake($relation) . '_count';

        // Tier 1: Exact Match (Role + Gov + Shift) - Sorted by least jobs this month
        $driver = User::where('role', $role)
            ->where('governorate', $gov)
            ->when($shift, function ($q) use ($shift) {
                return $q->where('shift', $shift);
            })
            ->withCount([$relation => function($query) use ($now) {
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
            }])
            ->orderBy($relationCountColumn, 'asc')
            ->orderBy('id', 'asc')
            ->first();

        // Tier 2: Regional Fallback (Role + Gov + ANY Shift)
        if (!$driver && $shift) {
            $driver = User::where('role', $role)
                ->where('governorate', $gov)
                ->withCount([$relation => function($query) use ($now) {
                    $query->whereMonth('created_at', $now->month)
                          ->whereYear('created_at', $now->year);
                }])
                ->orderBy($relationCountColumn, 'asc')
                ->orderBy('id', 'asc')
                ->first();
        }

        // Tier 3: Return Null (Graceful fallback for manual assignment if no driver exists)
        return $driver;
    }
}
