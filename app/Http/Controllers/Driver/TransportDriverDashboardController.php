<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use App\Models\FinancialTransaction;
use App\Models\User;
use App\Models\Vehicle;
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
        // 🚀 تفكيك قنبلة الواجهة: وضع limit(20) لمنع استنزاف موارد الهاتف
        $assignedTrips = Transport::where('driver_id', $driverId)
            ->where('status', 'assigned')
            ->with(['user', 'farmBooking.farm', 'farm', 'vehicle'])
            ->orderBy('Farm_Arrival_Time', 'asc')
            ->limit(20)
            ->get();

        // Recently Completed Trips (for history/stats)
        $completedTrips = Transport::where('driver_id', $driverId)
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
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

        // 🛡️ يمنع السائق من بدء الرحلة إذا كان متبقي أكثر من 8 ساعات لموعد الوصول
        if ($newStatus === 'in_progress' && $trip->Farm_Arrival_Time) {
            if (now()->addHours(8)->lessThan($trip->Farm_Arrival_Time)) {
                return back()->with('error', 'You cannot start this trip yet. Trips can only be started within 8 hours of the scheduled arrival time.');
            }
        }

        DB::beginTransaction();

        try {
            // 🚀 التحديث الآمن للحالة لمنع الـ Race Conditions
            $updated = Transport::where('id', $trip->id)
                ->where('status', $trip->status) // التأكد أن الحالة لم تتغير في هذه اللحظة
                ->update(['status' => $newStatus]);

            if (!$updated) {
                DB::rollBack();
                return redirect()->route('transport.driver.dashboard')
                    ->with('error', 'Trip status was already updated by another process.');
            }

            // Specific actions when a trip is completed
            if ($newStatus === 'completed') {

                // 1. Decrement the driver's active trips_count securely
                User::where('id', $driver->id)
                    ->where('trips_count', '>', 0)
                    ->decrement('trips_count');

                // 2. Release vehicle back to fleet securely
                if ($trip->vehicle_id) {
                    Vehicle::where('id', $trip->vehicle_id)->update(['status' => 'available']);
                }

                // =========================================================
                // 🚀 3. تفكيك ثغرة الأرباح المزدوجة (Idempotency Guard)
                // =========================================================
                $alreadyPaid = FinancialTransaction::where('reference_type', 'transport')
                    ->where('reference_id', $trip->id)
                    ->exists();

                // التأكد من عدم توزيع الأرباح إذا تم توزيعها مسبقاً (سواء في الكاشير أو هنا)
                if (!$alreadyPaid) {
                    $commissionAmount = $trip->commission_amount ?? ($trip->price * 0.10);
                    $netCompanyAmount = $trip->net_company_amount ?? ($trip->price * 0.90);
                    $adminId = User::where('role', 'admin')->value('id');

                    $financialsToInsert = [];

                    // Entry A: Credit Transport Company's wallet (Net Revenue)
                    if ($trip->company_id) {
                        $financialsToInsert[] = [
                            'user_id'          => $trip->company_id,
                            'amount'           => $netCompanyAmount,
                            'transaction_type' => 'credit',
                            'reference_type'   => 'transport',
                            'reference_id'     => $trip->id,
                            'description'      => "Net revenue for completed transport trip #{$trip->id}",
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ];
                    }

                    // Entry B: Credit Admin's wallet (Platform Commission)
                    if ($adminId) {
                        $financialsToInsert[] = [
                            'user_id'          => $adminId,
                            'amount'           => $commissionAmount,
                            'transaction_type' => 'credit',
                            'reference_type'   => 'transport',
                            'reference_id'     => $trip->id,
                            'description'      => "Platform commission for transport trip #{$trip->id}",
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ];
                    }

                    if (!empty($financialsToInsert)) {
                        DB::table('financial_transactions')->insert($financialsToInsert);
                    }
                }
                // =========================================================
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

        // 🚀 تفكيك قنبلة الرام: استخدام paginate بدلاً من get لتاريخ السائق
        $trips = Transport::where('driver_id', $driverId)
            ->where('status', 'completed')
            ->with(['user', 'farmBooking.farm', 'farm', 'vehicle'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('transports.drivers.history', compact('trips'));
    }
}
