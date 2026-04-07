<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransportDriverDashboardController extends Controller
{
    /**
     * Display the transport driver dashboard.
     */
    public function dashboard()
    {
        $driverId = Auth::id();

        // Active Trip: Only one trip should technically be in_progress or in_way at a time
        $activeTrip = Transport::where('driver_id', $driverId)
            ->whereIn('status', ['in_progress', 'in_way'])
            ->with(['user', 'farmBooking.farm', 'farm', 'vehicle'])
            ->first();

        // Upcoming/Assigned Trips
        $assignedTrips = Transport::where('driver_id', $driverId)
            ->where('status', 'assigned')
            ->with(['user', 'farmBooking.farm', 'farm', 'vehicle'])
            ->orderBy('Farm_Arrival_Time', 'asc')
            ->get();

        // Recently Completed Trips (for history/stats)
        $completedTrips = Transport::where('driver_id', $driverId)
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('transports.drivers.transport_dashboard', compact('activeTrip', 'assignedTrips', 'completedTrips'));
    }

    /**
     * Update the status of a trip and handle financials on completion.
     */
    public function updateStatus(Request $request, $id)
    {
        $trip = Transport::findOrFail($id);
        $driver = Auth::user();

        // Security: Ensure this trip belongs to the authenticated driver
        if ($trip->driver_id !== $driver->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:in_progress,completed',
        ]);

        $newStatus = $request->status;

        // Validation based on state machine
        if ($newStatus === 'in_progress' && $trip->status !== 'assigned') {
            return back()->with('error', 'Cannot start this trip. It is not in assigned status.');
        }

        if ($newStatus === 'completed' && !in_array($trip->status, ['in_progress', 'in_way'])) {
            return back()->with('error', 'Cannot complete this trip. It is not currently in progress.');
        }

        DB::beginTransaction();

        try {
            // Update Trip Status
            $trip->update(['status' => $newStatus]);

            // Specific actions when a trip is completed
            if ($newStatus === 'completed') {

                // 1. Decrement the driver's active orders_count
                if ($driver->orders_count > 0) {
                    $driver->decrement('orders_count');
                }

                // 2. Update vehicle status to available
                if ($trip->vehicle) {
                    $trip->vehicle->update(['status' => 'available']);
                }

                // 3. Financial Distribution 💰
                // Ensure the trip has calculated commission/net amounts (fallback if not set)
                $commissionAmount = $trip->commission_amount ?? ($trip->price * 0.10);
                $netCompanyAmount = $trip->net_company_amount ?? ($trip->price * 0.90);

                // Entry A: Credit Transport Company's wallet (Net Profit)
                if ($trip->company_id) {
                    FinancialTransaction::create([
                        'user_id' => $trip->company_id,
                        'amount' => $netCompanyAmount,
                        'transaction_type' => 'credit',
                        'reference_type' => 'Transport',
                        'reference_id' => $trip->id,
                        'description' => 'Transport Revenue for trip #' . $trip->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Entry B: Credit Admin's wallet (Platform Commission)
                // Find the primary super admin (assuming the first admin created, or specifically user ID 1)
                $admin = User::where('role', 'admin')->first();
                if ($admin) {
                    FinancialTransaction::create([
                        'user_id' => $admin->id,
                        'amount' => $commissionAmount,
                        'transaction_type' => 'credit',
                        'reference_type' => 'Transport_Commission',
                        'reference_id' => $trip->id,
                        'description' => 'Platform commission for transport trip #' . $trip->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            $message = $newStatus === 'in_progress' ? 'Trip started successfully. Drive safely!' : 'Trip marked as completed. Great job! Funds have been distributed.';

            return redirect()->route('transport.driver.dashboard')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while updating the status: ' . $e->getMessage());
        }
    }
}
