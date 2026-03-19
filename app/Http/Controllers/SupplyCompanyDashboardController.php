<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supply;
use App\Models\SupplyOrder;
use Illuminate\Support\Facades\Auth;

class SupplyCompanyDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ensure only supply companies can access this
        if ($user->role !== 'supply_company') {
            abort(403, 'Unauthorized access.');
        }

        // Fetch recent supply orders (we will fetch all for now, assuming the company fulfills all orders)
        $recentOrders = SupplyOrder::with(['user', 'supply', 'driver'])
            ->latest()
            ->take(10)
            ->get();

        // Calculate basic statistics
        $totalSupplies = Supply::count();
        $totalOrders = SupplyOrder::count();
        $pendingOrders = SupplyOrder::where('status', 'pending')->count();

        // Sum up the net profit for the company from completed/confirmed orders
        // (Assuming 'status' uses 'completed' or 'confirmed' - we will sum all for now if no specific status exists)
        $totalRevenue = SupplyOrder::sum('net_company_amount');

        return view('supplies.dashboard', compact(
            'recentOrders',
            'totalSupplies',
            'totalOrders',
            'pendingOrders',
            'totalRevenue'
        ));
    }
}
