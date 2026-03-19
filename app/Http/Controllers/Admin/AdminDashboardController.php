<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farm;
use App\Models\FarmBooking;
use App\Models\Transport;
use App\Models\SupplyOrder;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Extra safety check just in case middleware fails
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // Calculate global statistics across the platform
        $totalUsers = User::count();
        $totalFarms = Farm::count();

        $totalBookings = FarmBooking::count();
        $totalTransportTrips = Transport::count();
        $totalSupplyOrders = SupplyOrder::count();

        // Calculate Platform Commission Revenue
        $farmCommission = FarmBooking::sum('commission_amount');
        $transportCommission = Transport::sum('commission_amount');
        $supplyCommission = SupplyOrder::sum('commission_amount');

        $totalPlatformRevenue = $farmCommission + $transportCommission + $supplyCommission;

        // Get the latest general activity (e.g. 5 latest users)
        $latestUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalFarms',
            'totalBookings',
            'totalTransportTrips',
            'totalSupplyOrders',
            'totalPlatformRevenue',
            'latestUsers'
        ));
    }
}
