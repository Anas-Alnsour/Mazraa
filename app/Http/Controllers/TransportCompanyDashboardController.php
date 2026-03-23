<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TransportCompanyDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'transport_company') {
            abort(403, 'Unauthorized access.');
        }

        // --- 1. الإحصائيات (الكود القديم تبعك) ---
        $recentTrips = Transport::with(['driver', 'farm', 'user'])
            ->where('company_id', $user->id)
            ->latest()
            ->get();

        $totalTrips = $recentTrips->count();
        $pendingTrips = $recentTrips->where('status', 'accepted')->count(); // صار اسمه accepted بعد القبول
        $activeTrips = $recentTrips->whereIn('status', ['assigned', 'in_progress'])->count();
        $completedTrips = $recentTrips->whereIn('status', ['completed', 'finished']);

        $financials = [
            'gross' => $completedTrips->sum('price'),
            'commission' => $completedTrips->sum('commission_amount'),
            'net' => $completedTrips->sum('net_company_amount')
        ];

        $drivers = User::where('role', 'transport_driver')
            ->where('company_id', $user->id)
            ->get();

        // --- 2. لوجيك التوزيع (Dispatch) ---
        // الطلبات المتاحة بالسوق (لسا ما حدا أخذها)
        $availableJobs = Transport::whereNull('company_id')
            ->where('status', 'pending')
            ->latest()
            ->get();

        // طلبات شركتي أنا (اللي قبلتها) عشان أعرضها بالجدول تحت
        $myJobs = Transport::where('company_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('transports.dashboard', compact(
            'totalTrips',
            'pendingTrips',
            'activeTrips',
            'financials',
            'drivers',
            'availableJobs',
            'myJobs'
        ));
    }
}
