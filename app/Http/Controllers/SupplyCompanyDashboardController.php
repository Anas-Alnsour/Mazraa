<?php

namespace App\Http\Controllers;

use App\Models\SupplyOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplyCompanyDashboardController extends Controller
{
    /**
     * Display the Supply Company Dashboard. (Merged with Jules' Grouping Logic)
     */
    public function index()
    {
        if (Auth::user()->role !== 'supply_company') {
            abort(403, 'Unauthorized access.');
        }

        $companyId = Auth::id();

        // ---------------------------------------------------------------
        // 1. Fetch all non-cart orders belonging to this company's supplies
        // ---------------------------------------------------------------
        $orders = SupplyOrder::with(['supply', 'user', 'driver', 'booking.farm'])
            ->whereHas('supply', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->whereNotIn('status', ['cart', 'pending_payment'])
            ->orderBy('created_at', 'desc')
            ->get();

        // ---------------------------------------------------------------
        // 2. Financial Metrics — only count completed/delivered orders
        // ---------------------------------------------------------------
        $completedOrders = $orders->whereIn('status', ['delivered', 'completed']);

        $financials = [
            'gross'      => $completedOrders->sum('total_price'),
            'commission' => $completedOrders->sum('commission_amount'),
            'net'        => $completedOrders->sum('net_company_amount'),
        ];

        // ---------------------------------------------------------------
        // 3. Active Drivers for this company (for the dispatch dropdown)
        // ---------------------------------------------------------------
        $drivers = User::where('company_id', $companyId)
            ->where('role', 'supply_driver')
            ->get();

        // ---------------------------------------------------------------
        // 4. Recent orders (flat list for the dispatch table)
        //    and grouped orders (by invoice) — both available to the view
        // ---------------------------------------------------------------
        $recentOrders  = $orders->take(50);
        $groupedOrders = $orders->groupBy('order_id');

        // Supporting metrics (used by some view sections)
        $activeOrdersCount = $orders->whereIn('status', ['pending', 'waiting_driver', 'in_way'])->count();

        return view('admin.supplies.dashboard', compact(
            'financials',
            'drivers',
            'recentOrders',
            'groupedOrders',
            'activeOrdersCount'
        ));
    }

    /**
     * Dispatching: Assign a driver to a specific order (From your Original Code)
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

        $driver = User::findOrFail($request->driver_id);

        // Ensure the driver belongs to the same supply company
        if ($driver->company_id !== $user->id || $driver->role !== 'supply_driver') {
            abort(403, 'Invalid driver selection.');
        }

        // Resolve the invoice ID so we can update ALL line items in the same invoice
        $invoiceId = $order->order_id ?? $order->id;

        // Update all orders in this invoice atomically
        DB::beginTransaction();
        try {
            SupplyOrder::where('order_id', $invoiceId)
                ->whereHas('supply', function ($q) use ($user) {
                    $q->where('company_id', $user->id);
                })
                ->update([
                    'driver_id' => $driver->id,
                    'status'    => 'waiting_driver',
                ]);

            // Increment driver's active orders count
            DB::table('users')
                ->where('id', $driver->id)
                ->increment('orders_count');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to assign driver: ' . $e->getMessage());
        }

        return back()->with('success', 'Driver assigned successfully. Order is now waiting for driver pick-up.');
    }
}
