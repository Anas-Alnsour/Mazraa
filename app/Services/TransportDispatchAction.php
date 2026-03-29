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
     *
     * @param Transport $transport
     * @return bool
     */
    public static function dispatchDriver(Transport $transport)
    {
        // Require passengers count to find an appropriate vehicle
        if (!$transport->passengers) {
            Log::warning("TransportDispatchAction: No passengers count specified for Transport #{$transport->id}");
            return false;
        }

        // Find an available vehicle with a driver assigned, where the company is active,
        // and capacity is greater than or equal to the requested passengers.
        $availableVehicle = Vehicle::with(['driver', 'company'])
            ->where('status', 'available')
            ->where('capacity', '>=', $transport->passengers)
            ->whereNotNull('driver_id')
            ->whereNotNull('company_id')
            ->first();

        if ($availableVehicle && $availableVehicle->driver) {
            // Assign the fleet to the transport request
            $transport->update([
                'company_id' => $availableVehicle->company_id,
                'vehicle_id' => $availableVehicle->id,
                'driver_id'  => $availableVehicle->driver_id,
                'status'     => 'assigned',
            ]);

            // Temporarily mark the vehicle as in_use (optional based on your business logic,
            // but good practice to prevent double-booking immediately)
            $availableVehicle->update(['status' => 'in_use']);

            // Notify the assigned driver
            $availableVehicle->driver->notify(new DriverAssignedNotification($transport));

            Log::info("TransportDispatchAction: Successfully dispatched Driver #{$availableVehicle->driver_id} (Vehicle #{$availableVehicle->id}) to Transport #{$transport->id}.");
            return true;
        }

        Log::info("TransportDispatchAction: No available driver/vehicle found for Transport #{$transport->id} with capacity >= {$transport->passengers}. Left as pending.");
        return false;
    }
}
