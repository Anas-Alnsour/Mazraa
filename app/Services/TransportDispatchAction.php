<?php

namespace App\Services;

use App\Models\Transport;
use App\Models\Vehicle;
use App\Notifications\DriverAssignedNotification;
use Illuminate\Support\Facades\Log;

class TransportDispatchAction
{
    /**
     * Attempts to automatically dispatch an available driver to a transport request.
     * Criteria:
     * - Driver must be 'transport_driver'
     * - Driver must be in the same governorate as the transport DESTINATION (Farm's Governorate).
     * - Driver must have an available vehicle with sufficient capacity.
     * - Fair Dispatch: Least jobs this month first.
     *
     * @param Transport $transport
     * @return bool
     */
    public static function dispatchDriver(Transport $transport)
    {
        if (!$transport->passengers) {
            Log::warning("TransportDispatchAction: No passengers count specified for Transport #{$transport->id}");
            return false;
        }

        // Target Governorate: The destination governorate (where the farm is located)
        // because supply_drivers/transport_drivers are usually based near their service areas.
        // Actually, for transport (pickup -> farm), they should be in the ORIGIN governorate.
        // The user specified: "Geographical Binding: Each driver MUST be assigned to one of the 12 Jordanian Governorates."
        // "Orders must be dispatched to drivers within the SAME governorate."
        
        $originGov = $transport->destination_governorate; // This is the governorate of the TRIP origin/destination block

        $now = \Carbon\Carbon::now();

        // 1. Find the best driver based on Fair Dispatch logic
        $bestDriver = \App\Models\User::role('transport_driver')
            ->where('governorate', $originGov)
            ->whereNotNull('transport_vehicle_id') // Driver must have a link to a permanent vehicle
            ->withCount(['transportDriverJobs' => function($query) use ($now) {
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
            }])
            ->whereHas('transportVehicle', function($query) use ($transport) {
                $query->where('capacity', '>=', $transport->passengers)
                      ->where('status', 'available');
            })
            ->orderBy('transport_driver_jobs_count', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        if ($bestDriver) {
            $vehicle = $bestDriver->transportVehicle;

            $transport->update([
                'company_id' => $bestDriver->company_id,
                'vehicle_id' => $vehicle->id,
                'driver_id'  => $bestDriver->id,
                'status'     => 'assigned',
            ]);

            // Mark vehicle as in use
            $vehicle->update(['status' => 'in_use']);

            // Notify
            $bestDriver->notify(new DriverAssignedNotification($transport));

            Log::info("TransportDispatchAction: Fair Dispatch Success for Transport #{$transport->id}. Driver: {$bestDriver->name} in {$originGov}.");
            return true;
        }

        Log::info("TransportDispatchAction: No available transport_driver found in {$originGov} with sufficient capacity for Transport #{$transport->id}. Left as 'pending'.");
        return false;
    }
}
