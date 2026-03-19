<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transport;
use Illuminate\Support\Facades\Auth;

class TransportCompanyDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ensure only transport companies can access this
        if ($user->role !== 'transport_company') {
            abort(403, 'Unauthorized access.');
        }

        // Fetch recent transport trips (fetching all for now, as with supplies)
        $recentTrips = Transport::with(['driver', 'farm'])
            ->latest()
            ->take(10)
            ->get();

        // Calculate basic statistics
        $totalTrips = Transport::count();
        $pendingTrips = Transport::where('status', 'pending')->count();
        $activeTrips = Transport::whereIn('status', ['in_progress', 'assigned'])->count();

        // Sum up the net profit for the transport company
        $totalRevenue = Transport::sum('net_company_amount');

        return view('transport.dashboard', compact(
            'recentTrips',
            'totalTrips',
            'pendingTrips',
            'activeTrips',
            'totalRevenue'
        ));
    }
}
