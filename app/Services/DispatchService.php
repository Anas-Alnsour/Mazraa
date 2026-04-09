<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class DispatchService
{
    public function assignTransportDriver(string $gov, ?string $shift = null): ?User
    {
        return $this->findHungriestDriver('transport_driver', $gov, $shift);
    }

    public function assignSupplyDriver(string $gov, ?string $shift = null): ?User
    {
        return $this->findHungriestDriver('supply_driver', $gov, $shift);
    }

    private function findHungriestDriver(string $role, string $gov, ?string $shift): ?User
    {
        $now = Carbon::now();
        $relation = $role === 'transport_driver' ? 'transportDriverJobs' : 'supplyDriverJobs';

        // Tier 1: Exact Match (Role + Gov + Shift) - Sorted by least jobs this month
        $driver = User::role($role)
            ->where('governorate', $gov)
            ->when($shift, function ($q) use ($shift) {
                return $q->where('shift', $shift);
            })
            ->withCount([$relation => function($query) use ($now) {
                $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
            }])
            ->orderBy($relation . '_count', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        // Tier 2: Regional Fallback (Role + Gov + ANY Shift)
        if (!$driver && $shift) {
            $driver = User::role($role)
                ->where('governorate', $gov)
                ->withCount([$relation => function($query) use ($now) {
                    $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
                }])
                ->orderBy($relation . '_count', 'asc')
                ->orderBy('id', 'asc')
                ->first();
        }

        // Tier 3: Return Null (Graceful fallback for manual assignment)
        return $driver;
    }
}
