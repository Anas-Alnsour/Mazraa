<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transport;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\DriverAssignedNotification;

class TransportCompanyDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $companyId = $user->id;

        if ($user->role !== 'transport_company') {
            abort(403, 'Unauthorized access.');
        }

        // 🚀 إصلاح الكارثة: تجميع كل الإحصائيات والأرباح باستعلام MySQL واحد فقط لشركة النقل
        $tripStats = Transport::where('company_id', $companyId)
            ->select(
                DB::raw('COUNT(*) as total_trips'),
                DB::raw('SUM(CASE WHEN status IN ("accepted", "assigned") THEN 1 ELSE 0 END) as pending_trips'),
                DB::raw('SUM(CASE WHEN status IN ("accepted", "assigned", "in_progress", "in_way") THEN 1 ELSE 0 END) as active_trips'),
                DB::raw('SUM(CASE WHEN status IN ("completed", "delivered", "finished") THEN 1 ELSE 0 END) as completed_trips'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_transports_count'),
                DB::raw('SUM(CASE WHEN status IN ("assigned", "in_way") THEN 1 ELSE 0 END) as assigned_transports_count'),
                DB::raw('COALESCE(SUM(CASE WHEN status IN ("completed", "delivered", "finished") THEN price ELSE 0 END), 0) as gross_revenue'),
                DB::raw('COALESCE(SUM(CASE WHEN status IN ("completed", "delivered", "finished") THEN commission_amount ELSE 0 END), 0) as total_commission'),
                DB::raw('COALESCE(SUM(CASE WHEN status IN ("completed", "delivered", "finished") THEN net_company_amount ELSE 0 END), 0) as net_revenue')
            )->first();

        // تمرير المتغيرات للـ View
        $totalTrips = $tripStats->total_trips;
        $pendingTrips = $tripStats->pending_trips;
        $activeTrips = $tripStats->active_trips;
        $completedTrips = $tripStats->completed_trips;
        $pendingTransportsCount = $tripStats->pending_transports_count;
        $assignedTransportsCount = $tripStats->assigned_transports_count;

        $financials = [
            'gross' => $tripStats->gross_revenue,
            'commission' => $tripStats->total_commission,
            'net' => $tripStats->net_revenue
        ];

        // 🚀 إصلاح جلب السائقين والمركبات بدون تحميل الرام
        $drivers = User::where('role', 'transport_driver')
            ->where('company_id', $companyId)
            ->get();
        $totalDrivers = $drivers->count();

        $totalVehicles = Vehicle::where('company_id', $companyId)->count();
        $availableVehicles = Vehicle::where('company_id', $companyId)
            ->where('status', 'available')
            ->count();

        // 🚀 حماية من انهيار الرام: وضع Pagination للطلبات المتاحة بدلاً من جلبها كلها بـ get()
        $availableJobs = Transport::with(['farmBooking.farm', 'farm', 'user'])
            ->whereNull('company_id')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10, ['*'], 'available_page');

        // جلب رحلات الشركة الحالية
        $myJobs = Transport::with(['driver', 'vehicle', 'farmBooking.farm', 'farm', 'user'])
            ->where('company_id', $companyId)
            ->latest()
            ->paginate(10, ['*'], 'my_jobs_page');

        $recentActivity = Transport::where('company_id', $companyId)
            ->latest()
            ->limit(5)
            ->get();

        $stats = [
            'total_jobs' => $totalTrips,
            'revenue' => $financials['net'],
            'active_drivers' => $totalDrivers,
            'available_vehicles' => $availableVehicles,
            'total_vehicles' => $totalVehicles,
        ];

        return view('transports.dashboard', compact(
            'totalTrips', 'pendingTrips', 'activeTrips', 'completedTrips', 'financials',
            'drivers', 'totalDrivers', 'totalVehicles', 'availableVehicles', 'availableJobs',
            'myJobs', 'recentActivity', 'stats', 'pendingTransportsCount', 'assignedTransportsCount'
        ));
    }

    /**
     * Assign a driver and vehicle to a specific trip.
     */
    public function assignDriver(Request $request, Transport $trip)
    {
        $user = Auth::user();

        if ($user->role !== 'transport_company') {
            abort(403, 'Unauthorized access.');
        }

        if ($trip->company_id !== null && $trip->company_id !== $user->id) {
            return back()->with('error', 'This job has already been accepted by another company.');
        }

        $request->validate([
            'driver_id'  => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        $driver  = User::findOrFail($request->driver_id);
        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        if ($driver->company_id !== $user->id || $driver->role !== 'transport_driver') {
            return back()->with('error', 'Invalid driver selection.');
        }

        if ($vehicle->company_id !== $user->id) {
            return back()->with('error', 'Invalid vehicle selection.');
        }

        // ---------- الإضافة الجديدة: التحقق من سعة المركبة ----------
        if ($vehicle->capacity < $trip->passengers) {
            return back()->with('error', "عذراً، سعة هذه المركبة ({$vehicle->capacity} ركاب) لا تكفي لعدد الركاب المطلوب للرحلة ({$trip->passengers} ركاب).");
        }
        // -------------------------------------------------------------

        DB::beginTransaction();
        try {
            // تحديث الرحلة: الحالة assigned لتظهر في لوحة تحكم السائق كما طلبت
            $trip->update([
                'company_id' => $user->id,
                'driver_id'  => $driver->id,
                'vehicle_id' => $vehicle->id,
                'status'     => 'assigned',
            ]);

            $vehicle->update(['status' => 'in_use']);

            DB::table('users')->where('id', $driver->id)->increment('trips_count');

            DB::commit();

            // إرسال الإشعارات الفورية
            try {
                // إشعار للسائق
                $driver->notify(new DriverAssignedNotification($trip));

                // ✅ الحماية من N+1 وتحميل المستخدم قبل إرسال الإشعار
                $trip->loadMissing('user');

                // إشعار للعميل (المستخدم)
                if ($trip->user) {
                    $trip->user->notify(new DriverAssignedNotification($trip));
                }
            } catch (\Exception $e) {
                \Log::error('Notification Error (Transport Assign): ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Assignment failed: ' . $e->getMessage());
        }

        return back()->with('success', "Job #TRP-{$trip->id} accepted! Status set to assigned.");
    }
}
