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
        // 🚀 الحماية من الـ N+1 أثناء جلب العلاقات
        $order->loadMissing('booking.farm');

        // Destination: The farm's governorate
        $farm = $order->booking->farm ?? null;
        if (!$farm || !$farm->governorate) {
            Log::warning("SupplyDispatchAction: Missing farm governorate for Order #{$order->id}");
            return false;
        }

        $destGov = $farm->governorate;
        $now = Carbon::now();

        // 🚀 تفكيك قنبلة الـ Index Killer
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Find the best supply driver
        $bestDriver = User::role('supply_driver')
            ->where('governorate', $destGov)
            ->withCount(['supplyDriverJobs' => function($query) use ($startOfMonth, $endOfMonth) {
                // 🚀 استخدام whereBetween لتمكين الـ Indexing السريع
                $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            }])
            ->orderBy('supply_driver_jobs_count', 'asc')
            ->inRandomOrder() // 🚀 تفكيك قنبلة التزامن (Race Condition) عند التعادل
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
