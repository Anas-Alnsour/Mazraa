<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transport;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class TransportCompanyDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $companyId = $user->id;

        // حماية: التأكد إنو اللي فات هو شركة مواصلات فقط
        if ($user->role !== 'transport_company') {
            abort(403, 'Unauthorized access.');
        }

        // --- 1. جلب الرحلات الخاصة بهي الشركة (مع منع الـ Lazy Loading وتعديل ربط المزرعة) ---
        $myTrips = Transport::with(['driver', 'vehicle', 'farmBooking.farm', 'farm', 'user'])
            ->where('company_id', $companyId)
            ->latest()
            ->get();

        // --- 2. إحصائيات الرحلات (مدمجة بين القديم وجديد جولز) ---
        $totalTrips = $myTrips->count();
        $pendingTrips = $myTrips->where('status', 'accepted')->count();
        $activeTrips = $myTrips->whereIn('status', ['accepted', 'assigned', 'in_progress', 'in_way'])->count();
        $completedTrips = $myTrips->whereIn('status', ['completed', 'delivered', 'finished'])->count();

        // متغيرات جولز
        $pendingTransportsCount = $myTrips->where('status', 'pending')->count();
        $assignedTransportsCount = $myTrips->whereIn('status', ['assigned', 'in_way'])->count();

        // --- 3. الحسابات المالية ---
        $completedTripsData = $myTrips->whereIn('status', ['completed', 'delivered', 'finished']);

        $financials = [
            'gross' => $completedTripsData->sum('price'),
            'commission' => $completedTripsData->sum('commission_amount'),
            'net' => $completedTripsData->sum('net_company_amount')
        ];

        // --- 4. إحصائيات الأسطول والسائقين ---
        $drivers = User::where('role', 'transport_driver')
            ->where('company_id', $companyId)
            ->get();

        $totalDrivers = $drivers->count();
        $driversCount = $totalDrivers; // لجولز

        $totalVehicles = Vehicle::where('company_id', $companyId)->count();
        $vehiclesCount = $totalVehicles; // لجولز
        $availableVehicles = Vehicle::where('company_id', $companyId)
            ->where('status', 'available')
            ->count();

        // --- 5. لوجيك التوزيع والواجهة (Dispatch) ---
        $availableJobs = Transport::with(['farmBooking.farm', 'farm', 'user'])
            ->whereNull('company_id')
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Pagination for my jobs
        $myJobs = Transport::with(['driver', 'vehicle', 'farmBooking.farm', 'farm', 'user'])
            ->where('company_id', $companyId)
            ->latest()
            ->paginate(10);

        // آخر نشاطات للواجهة الجديدة
        $recentActivity = $myTrips->take(5);
        $recentTrips = $recentActivity; // لجولز

        // متغيرات التوافق
        $recentJobs = $recentActivity;
        $myDrivers = $drivers;
        $pendingRequests = $availableJobs->take(5);
        $stats = [
            'total_jobs' => $totalTrips,
            'revenue' => $financials['net'],
            'active_drivers' => $totalDrivers,
            'available_vehicles' => $availableVehicles,
            'total_vehicles' => $totalVehicles,
        ];

        // إرجاع كل المتغيرات للـ View
        return view('transports.dashboard', compact(
            'totalTrips',
            'pendingTrips',
            'activeTrips',
            'completedTrips',
            'financials',
            'drivers',
            'totalDrivers',
            'totalVehicles',
            'availableVehicles',
            'availableJobs',
            'myJobs',
            'recentActivity',
            'recentJobs',
            'myDrivers',
            'pendingRequests',
            'stats',
            // متغيرات جولز
            'vehiclesCount',
            'driversCount',
            'pendingTransportsCount',
            'assignedTransportsCount',
            'recentTrips'
        ));
    }

    /**
     * Assign a driver and vehicle to a specific trip (accept a dispatch job).
     */
    public function assignDriver(Request $request, Transport $trip)
    {
        $user = Auth::user();

        if ($user->role !== 'transport_company') {
            abort(403, 'Unauthorized access.');
        }

        // A company can only accept unassigned (pending, no company) jobs
        if ($trip->company_id !== null && $trip->company_id !== $user->id) {
            return back()->with('error', 'This job has already been accepted by another company.');
        }

        $request->validate([
            'driver_id'  => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        $driver  = User::findOrFail($request->driver_id);
        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        // Security: Ensure driver and vehicle belong to this company
        if ($driver->company_id !== $user->id || $driver->role !== 'transport_driver') {
            return back()->with('error', 'Invalid driver selection. Driver must belong to your company.');
        }

        if ($vehicle->company_id !== $user->id) {
            return back()->with('error', 'Invalid vehicle selection. Vehicle must belong to your company.');
        }

        if ($vehicle->status !== 'available') {
            return back()->with('error', 'This vehicle is not available. Please select another.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Accept the job: link company, driver, vehicle and advance status
            $trip->update([
                'company_id' => $user->id,
                'driver_id'  => $driver->id,
                'vehicle_id' => $vehicle->id,
                'status'     => 'assigned',
            ]);

            // Mark vehicle as in_use
            $vehicle->update(['status' => 'in_use']);

            // Increment driver's active trip count for fair dispatching
            \Illuminate\Support\Facades\DB::table('users')
                ->where('id', $driver->id)
                ->increment('trips_count');

            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Assignment failed: ' . $e->getMessage());
        }

        return back()->with('success', "Job #TRP-{$trip->id} accepted! Driver {$driver->name} has been assigned with vehicle {$vehicle->license_plate}.");
    }
}
