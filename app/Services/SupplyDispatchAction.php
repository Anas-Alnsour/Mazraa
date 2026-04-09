<?php

namespace App\Services;

use App\Models\SupplyOrder;
use App\Models\User;
use App\Notifications\NewSupplyOrderNotification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SupplyDispatchAction
{
    /**
     * Automatically dispatches a supply order to the best driver.
     * Criteria:
     * - Role: 'supply_driver'
     * - Location: Farm's Governorate (where delivery happens).
     * - Fair Dispatch: Lowest workload this month.
     *
     * @param SupplyOrder $order
     * @return bool
     */
    public static function dispatchDriver(SupplyOrder $order)
    {
        // Destination: The farm's governorate
        $farm = $order->booking->farm;
        if (!$farm || !$farm->governorate) {
            Log::warning("SupplyDispatchAction: Missing farm governorate for Order #{$order->id}");
            return false;
        }

        $destGov = $farm->governorate;
        $now = Carbon::now();

        // Find the best supply driver
        $bestDriver = User::role('supply_driver')
            ->where('governorate', $destGov)
            ->withCount(['supplyDriverJobs' => function($query) use ($now) {
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
            }])
            ->orderBy('supply_driver_jobs_count', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        if ($bestDriver) {
            $order->update([
                'driver_id' => $bestDriver->id,
                'status'    => 'dispatched', // Transition from 'pending'
            ]);

            // Notify
            $bestDriver->notify(new NewSupplyOrderNotification($order));

            Log::info("SupplyDispatchAction: Fair Dispatch Success for Order #{$order->id}. Driver: {$bestDriver->name} in {$destGov}.");
            return true;
        }

        Log::info("SupplyDispatchAction: No available supply_driver found in {$destGov} for Order #{$order->id}. Left as 'pending'.");
        return false;
    }
}
