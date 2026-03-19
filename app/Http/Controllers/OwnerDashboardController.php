<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmBooking;
use Illuminate\Support\Facades\Auth;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ensure only farm owners can access this
        if ($user->role !== 'farm_owner') {
            abort(403, 'Unauthorized access.');
        }

        // Fetch farms owned by this user
        $farms = Farm::where('owner_id', $user->id)->get();
        $farmIds = $farms->pluck('id');

        // Fetch bookings related to those farms
        $recentBookings = FarmBooking::with(['user', 'farm'])
            ->whereIn('farm_id', $farmIds)
            ->latest()
            ->take(5)
            ->get();

        // Calculate basic statistics
        $totalFarms = $farms->count();
        $totalBookings = FarmBooking::whereIn('farm_id', $farmIds)->count();

        // Sum up the net profit from all bookings (assuming net_profit represents the owner's cut)
        $totalRevenue = FarmBooking::whereIn('farm_id', $farmIds)
            ->where('status', 'confirmed')
            ->sum('net_profit');

        return view('owner.dashboard', compact(
            'farms',
            'recentBookings',
            'totalFarms',
            'totalBookings',
            'totalRevenue'
        ));
    }
}
