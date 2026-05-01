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

        // State machine guards
        if ($newStatus === 'in_progress' && $trip->status !== 'assigned') {
            return back()->with('error', 'Cannot start this trip. It is not in assigned status.');
        }

        if ($newStatus === 'completed' && !in_array($trip->status, ['in_progress', 'in_way'])) {
            return back()->with('error', 'Cannot complete this trip. It is not currently in progress.');
        }

        // 🔒 Idempotency guard: prevent duplicate ledger entries if submitted twice
        if ($newStatus === 'completed' && $trip->status === 'completed') {
            return redirect()->route('transport.driver.dashboard')
                ->with('success', 'This trip is already marked as completed.');
        }
        // 🛡️ يمنع السائق من بدء الرحلة إذا كان متبقي أكثر من 8 ساعات لموعد الوصول
        if ($newStatus === 'in_progress' && $trip->Farm_Arrival_Time) {
            if (now()->addHours(8)->lessThan($trip->Farm_Arrival_Time)) {
                return back()->with('error', 'You cannot start this trip yet. Trips can only be started within 8 hours of the scheduled arrival time.');
            }
        }

        DB::beginTransaction();

        try {
            // Update Trip Status
            $trip->update(['status' => $newStatus]);

            // Specific actions when a trip is completed
            if ($newStatus === 'completed') {

                // 1. Decrement the driver's active trips_count
                if ($driver->trips_count > 0) {
                    $driver->decrement('trips_count');
                }

                // 2. Release vehicle back to fleet
                if ($trip->vehicle) {
                    $trip->vehicle->update(['status' => 'available']);
                }

                // 3. Financial Distribution — pull pre-calculated amounts (set during booking)
                //    with safe fallback to 10/90 split if not pre-calculated
                $commissionAmount = $trip->commission_amount ?? ($trip->price * 0.10);
                $netCompanyAmount = $trip->net_company_amount ?? ($trip->price * 0.90);

                $adminId = User::where('role', 'admin')->value('id');

                // Entry A: Credit Transport Company's wallet (Net Revenue)
                if ($trip->company_id) {
                    FinancialTransaction::create([
                        'user_id'          => $trip->company_id,
                        'amount'           => $netCompanyAmount,
                        'transaction_type' => 'credit',
                        'reference_type'   => 'transport',
                        'reference_id'     => $trip->id,
                        'description'      => "Net revenue for transport trip #{$trip->id}",
                    ]);
                }

                // Entry B: Credit Admin's wallet (Platform Commission)
                if ($adminId) {
                    FinancialTransaction::create([
                        'user_id'          => $adminId,
                        'amount'           => $commissionAmount,
                        'transaction_type' => 'credit',
                        'reference_type'   => 'transport',
                        'reference_id'     => $trip->id,
                        'description'      => "Platform commission for transport trip #{$trip->id}",
                    ]);
                }
            }

            DB::commit();

            $message = $newStatus === 'in_progress'
                ? 'Trip started successfully. Drive safely!'
                : 'Trip marked as completed. Great job! Funds have been distributed.';

            return redirect()->route('transport.driver.dashboard')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while updating the status: ' . $e->getMessage());
        }
    }

    /**
     * Display the transport driver trip history.
     */
    public function history()
    {
        $driverId = Auth::id();

        $trips = Transport::where('driver_id', $driverId)
            ->where('status', 'completed')
            ->with(['user', 'farmBooking.farm', 'farm', 'vehicle'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('transports.drivers.history', compact('trips'));
    }
}
