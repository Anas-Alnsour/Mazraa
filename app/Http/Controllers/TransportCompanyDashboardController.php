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

        $recentTrips = Transport::with(['driver', 'farm', 'user'])
            ->latest()
            ->get();

        $totalTrips = $recentTrips->count();
        $pendingTrips = $recentTrips->where('status', 'pending')->count();
        $activeTrips = $recentTrips->whereIn('status', ['assigned', 'in_progress', 'started'])->count();

        $completedTrips = $recentTrips->whereIn('status', ['completed', 'finished', 'delivered']);

        // استخدام price بناءً على الداتا بيس تبعتك
        $grossRevenue = $completedTrips->sum('price');
        $platformCommission = $completedTrips->sum('commission_amount');
        $netProfit = $completedTrips->sum('net_company_amount');

        $financials = [
            'gross' => $grossRevenue,
            'commission' => $platformCommission,
            'net' => $netProfit
        ];

        $drivers = User::where('role', 'transport_driver')
            ->where('company_id', $user->id)
            ->get();

        return view('transports.dashboard', compact(
            'recentTrips',
            'totalTrips',
            'pendingTrips',
            'activeTrips',
            'financials',
            'drivers'
        ));
    }

    public function assignDriver(Request $request, $tripId)
    {
        $user = Auth::user();

        if ($user->role !== 'transport_company') {
            abort(403);
        }

        $request->validate([
            'driver_id' => 'required|exists:users,id'
        ]);

        $trip = Transport::findOrFail($tripId);

        $driver = User::findOrFail($request->driver_id);
        if ($driver->company_id !== $user->id || $driver->role !== 'transport_driver') {
            abort(403, 'Invalid driver selection.');
        }

        $trip->update([
            'driver_id' => $driver->id,
            'status' => 'assigned'
        ]);

        return back()->with('success', 'Driver assigned successfully. The trip is now scheduled.');
    }
}
