<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supply;
use App\Models\SupplyOrder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SupplyCompanyDashboardController extends Controller
{
    /**
     * Display the Supply Company Dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Ensure only supply companies can access this
        if ($user->role !== 'supply_company') {
            abort(403, 'Unauthorized access.');
        }

        // Fetch orders (excluding 'cart' items).
        $recentOrders = SupplyOrder::with(['user', 'supply', 'driver'])
            ->where('status', '!=', 'cart')
            ->latest()
            ->get();

        // 1. Calculate Statistics
        $totalSupplies = Supply::count();
        $totalOrders = $recentOrders->count();
        $pendingOrders = $recentOrders->where('status', 'pending')->count();

        // 2. Financial Reports strictly based on architectural requirements
        $completedOrders = $recentOrders->whereIn('status', ['completed', 'delivered']);

        // استخدام total_amount بناءً على التعديلات السابقة اللي عملناها
        $grossSales = $completedOrders->sum('total_amount');
        $platformCommission = $completedOrders->sum('commission_amount');
        $netProfit = $completedOrders->sum('net_company_amount');

        $financials = [
            'gross' => $grossSales,
            'commission' => $platformCommission,
            'net' => $netProfit
        ];

        // 3. Driver Management
        // Fetch drivers created by this company
        $drivers = User::where('role', 'supply_driver')
            ->where('company_id', $user->id)
            ->get();

        return view('supplies.dashboard', compact(
            'recentOrders',
            'totalSupplies',
            'totalOrders',
            'pendingOrders',
            'financials',
            'drivers'
        ));
    }

    /**
     * Dispatching: Assign a driver to a specific order
     */
    public function assignDriver(Request $request, $orderId)
    {
        $user = Auth::user();

        if ($user->role !== 'supply_company') {
            abort(403);
        }

        $request->validate([
            'driver_id' => 'required|exists:users,id'
        ]);

        $order = SupplyOrder::findOrFail($orderId);

        // Ensure the driver belongs to this company
        $driver = User::findOrFail($request->driver_id);
        if ($driver->company_id !== $user->id || $driver->role !== 'supply_driver') {
            abort(403, 'Invalid driver selection.');
        }

        $order->update([
            'driver_id' => $driver->id,
            'status' => 'in_way' // Update status to dispatched/in_way
        ]);

        return back()->with('success', 'Driver assigned successfully. Order is now on the way.');
    }
}
