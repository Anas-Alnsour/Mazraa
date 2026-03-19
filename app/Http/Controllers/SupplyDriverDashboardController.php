<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyOrder;
use Illuminate\Support\Facades\Auth;

class SupplyDriverDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ensure only supply drivers can access this
        if ($user->role !== 'supply_driver') {
            abort(403, 'Unauthorized access.');
        }

        // Fetch supply orders specifically assigned to this driver
        $myOrders = SupplyOrder::with(['user', 'supply', 'farmBooking.farm'])
            ->where('driver_id', $user->id)
            ->latest()
            ->take(15)
            ->get();

        // Calculate basic statistics for this driver
        $totalDeliveries = SupplyOrder::where('driver_id', $user->id)->count();
        $pendingDeliveries = SupplyOrder::where('driver_id', $user->id)
            ->whereIn('status', ['assigned', 'out_for_delivery'])
            ->count();
        $completedDeliveries = SupplyOrder::where('driver_id', $user->id)
            ->whereIn('status', ['completed', 'delivered'])
            ->count();

        return view('delivery.orders', compact(
            'myOrders',
            'totalDeliveries',
            'pendingDeliveries',
            'completedDeliveries'
        ));
    }
}
