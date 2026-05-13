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

       $myTrips = Transport::where('driver_id', Auth::id())
    ->whereIn('status', ['assigned', 'accepted', 'in_progress', 'in_way']) // 👈 تأكد من إضافة assigned هنا
    ->latest()
    ->get();

        $totalTrips = $myTrips->count();
        // تم التحديث ليشمل الحالة الجديدة assigned في الإحصائيات
        $pendingTrips = $myTrips->whereIn('status', ['accepted', 'assigned'])->count();
        $activeTrips = $myTrips->whereIn('status', ['accepted', 'assigned', 'in_progress', 'in_way'])->count();
        $completedTrips = $myTrips->whereIn('status', ['completed', 'delivered', 'finished'])->count();

        $pendingTransportsCount = $myTrips->where('status', 'pending')->count();
        $assignedTransportsCount = $myTrips->whereIn('status', ['assigned', 'in_way'])->count();

        $completedTripsData = $myTrips->whereIn('status', ['completed', 'delivered', 'finished']);

        $financials = [
            'gross' => $completedTripsData->sum('price'),
            'commission' => $completedTripsData->sum('commission_amount'),
            'net' => $completedTripsData->sum('net_company_amount')
        ];

        $drivers = User::where('role', 'transport_driver')
            ->where('company_id', $companyId)
            ->get();

        $totalDrivers = $drivers->count();
        $totalVehicles = Vehicle::where('company_id', $companyId)->count();
        $availableVehicles = Vehicle::where('company_id', $companyId)
            ->where('status', 'available')
            ->count();

        $availableJobs = Transport::with(['farmBooking.farm', 'farm', 'user'])
            ->whereNull('company_id')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $myJobs = Transport::with(['driver', 'vehicle', 'farmBooking.farm', 'farm', 'user'])
            ->where('company_id', $companyId)
            ->latest()
            ->paginate(10);

        $recentActivity = $myTrips->take(5);
        
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