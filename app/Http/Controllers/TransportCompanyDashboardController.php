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

        // --- 1. جلب الرحلات الخاصة بهي الشركة (مع منع الـ Lazy Loading 🚀) ---
        $myTrips = Transport::with(['driver', 'vehicle', 'farm', 'user'])
            ->where('company_id', $companyId)
            ->latest()
            ->get();

        // --- 2. إحصائيات الرحلات (مدمجة) ---
        $totalTrips = $myTrips->count();
        $pendingTrips = $myTrips->where('status', 'accepted')->count();
        $activeTrips = $myTrips->whereIn('status', ['accepted', 'assigned', 'in_progress'])->count();
        $completedTrips = $myTrips->whereIn('status', ['completed', 'delivered', 'finished'])->count();

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

        $totalVehicles = Vehicle::where('company_id', $companyId)->count();
        $availableVehicles = Vehicle::where('company_id', $companyId)
            ->where('status', 'available')
            ->count();

        // --- 5. لوجيك التوزيع والواجهة (Dispatch) ---
        // 💡 THE FIX: Added `with(['farm', 'user'])` here as well to prevent errors
        $availableJobs = Transport::with(['farm', 'user'])
            ->whereNull('company_id')
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Pagination for my jobs
        $myJobs = Transport::with(['driver', 'vehicle', 'farm', 'user'])
            ->where('company_id', $companyId)
            ->latest()
            ->paginate(10);

        // آخر نشاطات للواجهة الجديدة
        $recentActivity = $myTrips->take(5);

        // متغيرات التوافق (عشان الكود اللي أعطيتك إياه قبل ما يضرب مع الـ View إذا كان بيستناها)
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
            // --- Compatibility vars ---
            'recentJobs',
            'myDrivers',
            'pendingRequests',
            'stats'
        ));
    }
}
