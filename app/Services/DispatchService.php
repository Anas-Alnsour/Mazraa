<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class DispatchService
{
    /**
     * Assign a Transport Driver for a Round-Trip.
     * Criteria:
     * - Driver role is 'transport_driver'
     * - Driver belongs to the CUSTOMER'S ORIGIN Governorate.
     * - Fair Dispatching: Driver with the lowest 'trips_count' in that region.
     *
     * @param string $originGovernorate
     * @return User
     * @throws Exception
     */
    public function assignTransportDriver(string $originGovernorate): User
    {
        $driver = User::where('role', 'transport_driver')
            ->where('governorate', $originGovernorate)
            ->orderBy('trips_count', 'asc')
            ->first();

        if (!$driver) {
            throw new Exception("No transport drivers available in {$originGovernorate} region.");
        }

        // Increment the trip count atomically to maintain fair dispatching
        DB::transaction(function () use ($driver) {
            $driver->increment('trips_count');
        });

        return $driver;
    }

    /**
     * Assign a Supply Driver for Delivery.
     * Criteria:
     * - Driver role is 'supply_driver'
     * - Driver belongs to the DESTINATION Governorate (Farm's location).
     * - Fair Dispatching: Driver with the lowest 'orders_count' in that region.
     *
     * @param string $destinationGovernorate
     * @return User
     * @throws Exception
     */
    public function assignSupplyDriver(string $destinationGovernorate): User
    {
        $driver = User::where('role', 'supply_driver')
            ->where('governorate', $destinationGovernorate)
            ->orderBy('orders_count', 'asc')
            ->first();

        if (!$driver) {
            throw new Exception("No supply drivers available in {$destinationGovernorate} region.");
        }

        // Increment the order count atomically to maintain fair dispatching
        DB::transaction(function () use ($driver) {
            $driver->increment('orders_count');
        });

        return $driver;
    }
}
