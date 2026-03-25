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

        // حماية: التأكد إنو اللي فات هو شركة مواصلات فقط
        if ($user->role !== 'transport_company') {
            abort(403, 'Unauthorized access.');
        }

        // --- 1. جلب الرحلات الخاصة بهي الشركة ---
        $myTrips = Transport::with(['driver', 'vehicle', 'farm', 'user'])
            ->where('company_id', $user->id)
            ->latest()
            ->get();

        // --- 2. إحصائيات الرحلات (مدمجة) ---
        $totalTrips = $myTrips->count();
        $pendingTrips = $myTrips->where('status', 'accepted')->count(); // من كودك القديم
        $activeTrips = $myTrips->whereIn('status', ['accepted', 'assigned', 'in_progress'])->count();
        $completedTrips = $myTrips->whereIn('status', ['completed', 'delivered', 'finished'])->count();

        // --- 3. الحسابات المالية ---
        $completedTripsData = $myTrips->whereIn('status', ['completed', 'delivered', 'finished']);

        $financials = [
            'gross' => $completedTripsData->sum('price'),
            'commission' => $completedTripsData->sum('commission_amount'),
            'net' => $completedTripsData->sum('net_company_amount')
        ];

        // --- 4. إحصائيات الأسطول ---
        $drivers = User::where('role', 'transport_driver')
            ->where('company_id', $user->id)
            ->get(); // من كودك (عشان لو احتجتها كـ Collection)

        $totalDrivers = $drivers->count();

        // شيك إذا موديل Vehicle موجود عندك، إذا مش موجود امسح هدول السطرين
        $totalVehicles = Vehicle::where('company_id', $user->id)->count();
        $availableVehicles = Vehicle::where('company_id', $user->id)
            ->where('status', 'available')
            ->count();

        // --- 5. لوجيك التوزيع (Dispatch) - من كودك القديم للحماية ---
        $availableJobs = Transport::whereNull('company_id')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $myJobs = Transport::with(['driver', 'vehicle', 'farm', 'user'])
            ->where('company_id', $user->id)
            ->latest()
            ->paginate(10);

        // آخر نشاطات للواجهة الجديدة
        $recentActivity = $myTrips->take(5);

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
            'recentActivity'
        ));
    }
}
