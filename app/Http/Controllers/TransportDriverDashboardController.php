<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transport;
use Illuminate\Support\Facades\Auth;

class TransportDriverDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ensure only transport drivers can access this
        if ($user->role !== 'transport_driver') {
            abort(403, 'Unauthorized access.');
        }

        // Fetch trips specifically assigned to this driver
        $myTrips = Transport::with(['user', 'farm'])
            ->where('driver_id', $user->id)
            ->latest('Farm_Arrival_Time') // Order by upcoming arrival time
            ->take(15)
            ->get();

        // Calculate basic statistics for this driver
        $totalTrips = Transport::where('driver_id', $user->id)->count();
        $upcomingTrips = Transport::where('driver_id', $user->id)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->count();
        $completedTrips = Transport::where('driver_id', $user->id)
            ->whereIn('status', ['completed', 'finished'])
            ->count();

        return view('shuttle.trips', compact(
            'myTrips',
            'totalTrips',
            'upcomingTrips',
            'completedTrips'
        ));
    }
}
