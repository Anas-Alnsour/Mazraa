<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class DispatchService
{
    /**
     * Assign a Transport Driver for a Round-Trip.
     * Criteria:
     * - Driver role is 'transport_driver'
     * - Driver belongs to the CUSTOMER'S ORIGIN Governorate.
     * - Fair Dispatching (Round-Robin): Driver with the lowest assigned jobs THIS MONTH.
     *
     * @param string $originGovernorate
     * @return User|null
     */
    public function assignTransportDriver(string $originGovernorate): ?User
    {
        $now = Carbon::now();

        $driver = User::role('transport_driver')
            ->where('governorate', $originGovernorate)
            ->withCount(['transportDriverJobs' => function($query) use ($now) {
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
            }])
            ->orderBy('transport_driver_jobs_count', 'asc') // Round-Robin: Least workload first
            ->orderBy('id', 'asc') // ID Fallback
            ->first();

        // Graceful handling: If no driver in specific gov, return null instead of throwing exception
        // The controller will then decide whether to flag it for admin review
        return $driver;
    }

    /**
     * Assign a Supply Driver for Delivery.
     * Criteria:
     * - Driver role is 'supply_driver'
     * - Driver belongs to the DESTINATION Governorate (Farm's location).
     * - Fair Dispatching (Round-Robin): Driver with the lowest assigned orders THIS MONTH.
     *
     * @param string $destinationGovernorate
     * @return User|null
     */
    public function assignSupplyDriver(string $destinationGovernorate): ?User
    {
        $now = Carbon::now();

        $driver = User::role('supply_driver')
            ->where('governorate', $destinationGovernorate)
            ->withCount(['supplyDriverJobs' => function($query) use ($now) {
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
            }])
            ->orderBy('supply_driver_jobs_count', 'asc') // Round-Robin: Least workload first
            ->orderBy('id', 'asc') // ID Fallback
            ->first();

        return $driver;
    }
}
