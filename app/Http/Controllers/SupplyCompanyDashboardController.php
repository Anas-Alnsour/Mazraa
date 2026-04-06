<?php

namespace App\Http\Controllers;

use App\Models\SupplyOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Fetch all orders where the supply belongs to the authenticated company
        $orders = SupplyOrder::with(['supply', 'user', 'driver', 'booking.farm'])
            ->whereHas('supply', function ($query) {
                $query->where('company_id', Auth::id());
            })
            ->whereNotIn('status', ['cart']) // Don't show un-purchased cart items
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by Invoice ID to keep UI clean (Jules Logic)
        $groupedOrders = $orders->groupBy('order_id');

        // Financial Metrics for the Company (Jules Logic)
        $completedOrders = $orders->where('status', 'delivered');
        $totalRevenue = $completedOrders->sum('net_company_amount');
        $activeOrdersCount = $orders->whereIn('status', ['pending', 'waiting_driver', 'in_way'])->count();

        // 💡 بنرجع الـ view تبعك الأصلي عشان ما تضرب الراوتات أو الـ UI
        // إذا كنت بدك تستخدم view جولز غيرها لـ 'supplies.company.dashboard'
        return view('admin.supplies.dashboard', compact('groupedOrders', 'totalRevenue', 'activeOrdersCount'));
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

        $order->update([
            'driver_id' => $driver->id,
            'status' => 'in_way'
        ]);

        return back()->with('success', 'Driver assigned successfully. Order is now on the way.');
    }
}
