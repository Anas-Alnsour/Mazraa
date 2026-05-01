<?php

namespace App\Http\Controllers;

use App\Models\SupplyOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\SupplyDriverAssignedNotification;

class SupplyCompanyDashboardController extends Controller
{
    /**
     * Display the Supply Company Dashboard. (Merged with Jules' Grouping Logic)
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'supply_company') {
            abort(403, 'Unauthorized access.');
        }

        $user = Auth::user();
        $companyId = $user->id;

        // ---------------------------------------------------------------
        // MASTER HQ INTERFACE LOGIC
        // ---------------------------------------------------------------
        if ($user->is_hq) {
            return redirect()->route('supplies.hq.overview');
        }

        // ---------------------------------------------------------------
        // REGIONAL BRANCH INTERFACE LOGIC
        // ---------------------------------------------------------------
        // 1. Fetch all non-cart orders belonging to this company's supplies AND routed to their governorate
        $orders = SupplyOrder::with(['supply', 'user', 'driver', 'booking.farm'])
            ->where(function ($query) use ($user) {
                // strict routing: either the farm booking governorate or standalone destination_governorate
                $query->whereHas('booking.farm', function ($farmQ) use ($user) {
                    $farmQ->where('governorate', $user->governorate);
                })
                ->orWhere('destination_governorate', $user->governorate);
            })
            ->whereHas('supply.inventories', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->whereNotIn('status', ['cart', 'pending_payment'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Financial Metrics — only count completed/delivered orders
        $completedOrders = $orders->whereIn('status', ['delivered', 'completed']);

        $financials = [
            'gross'      => $completedOrders->sum('total_price'),
            'commission' => $completedOrders->sum('commission_amount'),
            'net'        => $completedOrders->sum('net_company_amount'),
        ];

        // 3. Active Drivers for this company (for the dispatch dropdown)
        $drivers = User::where('company_id', $companyId)
            ->where('role', 'supply_driver')
            ->get();

        // 4. Recent orders (flat list for the dispatch table)
        $recentOrders  = $orders->take(50);
        $groupedOrders = $orders->groupBy('order_id');

        // Supporting metrics (used by some view sections)
        $activeOrdersCount = $orders->whereIn('status', ['pending', 'waiting_driver', 'in_way'])->count();

        // 5. Global Catalog & Local Inventory with Pagination & Filters
        $query = \App\Models\Supply::with(['inventories' => function($q) use ($companyId) {
            $q->where('company_id', $companyId);
        }]);

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $globalSupplies = $query->paginate(12)->withQueryString();

        return view('admin.supplies.dashboard', compact(
            'financials',
            'drivers',
            'recentOrders',
            'groupedOrders',
            'activeOrdersCount',
            'globalSupplies'
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

        $order = SupplyOrder::where('id', $orderId)
            ->whereHas('supply.inventories', function($invQ) use ($user) {
                $invQ->where('company_id', $user->id);
            })->firstOrFail();

        $driver = User::findOrFail($request->driver_id);

        if ($driver->company_id !== $user->id || $driver->role !== 'supply_driver') {
            abort(403, 'Invalid driver selection.');
        }

        $invoiceId = $order->order_id ?? $order->id;

        DB::beginTransaction();
        try {
            SupplyOrder::where('order_id', $invoiceId)
                ->whereHas('supply.inventories', function($invQ) use ($user) {
                    $invQ->where('company_id', $user->id);
                })
                ->whereIn('status', ['pending', 'waiting_driver'])
                ->update([
                    'driver_id' => $driver->id,
                    'status'    => 'waiting_driver',
                ]);

            DB::table('users')
                ->where('id', $driver->id)
                ->increment('orders_count');

            DB::commit();

            // =========================================================
            // 🚀 إطلاق الإشعارات (للسائق وللعميل)
            // =========================================================
            try {
                // إشعار للسائق
                $driver->notify(new SupplyDriverAssignedNotification($invoiceId));

                // إشعار للعميل صاحب الطلب
                if ($order->user) {
                    $order->user->notify(new SupplyDriverAssignedNotification($invoiceId));
                }
            } catch (\Exception $e) {
                \Log::error('Notification Error (Supply Assign): ' . $e->getMessage());
            }
            // =========================================================

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to assign driver: ' . $e->getMessage());
        }

        return back()->with('success', 'Driver assigned successfully. Order is now waiting for driver pick-up.');
    }

    /**
     * Update specific stock for Centralized Global Supply Catalog (Regional Branch)
     */
    public function updateStock(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'supply_company') {
            abort(403);
        }

        $request->validate([
            'supply_id' => 'required|exists:supplies,id',
            'stock' => 'required|integer|min:0',
        ]);

        \App\Models\SupplyInventory::updateOrCreate(
            ['supply_id' => $request->supply_id, 'company_id' => $user->id],
            ['stock' => $request->stock]
        );

        return back()->with('success', 'Local inventory updated successfully.');
    }

    /**
     * Fetch branch specific inventory dynamically via AJAX for HQ drill-down.
     */
    public function branchInventory(Request $request, $branchId)
    {
        if (Auth::user()->role !== 'supply_company' || !Auth::user()->is_hq) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $branch = User::where('id', $branchId)->where('role', 'supply_company')->firstOrFail();

        // Fetch supplies with their respective stock in this branch, order by ASC stock for load balancing
        $inventory = \App\Models\Supply::select('supplies.*', 'supply_inventories.stock')
            ->leftJoin('supply_inventories', function($join) use ($branchId) {
                $join->on('supplies.id', '=', 'supply_inventories.supply_id')
                     ->where('supply_inventories.company_id', '=', $branchId);
            })
            ->orderByRaw('COALESCE(supply_inventories.stock, 0) ASC')
            ->get();

        return response()->json([
            'branch' => $branch->name,
            'inventory' => $inventory
        ]);
    }

    /**
     * Master HQ: Store New Global Product
     */
    public function storeGlobalProduct(Request $request)
    {
        if (!Auth::user()->is_hq) { abort(403); }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        // Upload Logic placeholder if needed (assumes normal image logic).
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('supplies', 'public');
            $validated['image'] = $path;
        } else {
            $validated['image'] = 'assets/img/default-supply.png';
        }

        \App\Models\Supply::create($validated);
        return back()->with('success', 'Global Product Added Successfully.');
    }

    /**
     * Master HQ: Update Global Product
     */
    public function updateGlobalProduct(Request $request, $id)
    {
        if (!Auth::user()->is_hq) { abort(403); }

        $supply = \App\Models\Supply::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('supplies', 'public');
            $validated['image'] = $path;
        }

        $supply->update($validated);
        return back()->with('success', 'Global Product Updated Successfully.');
    }

    /**
     * Master HQ: Delete Global Product
     */
    public function destroyGlobalProduct($id)
    {
        if (!Auth::user()->is_hq) { abort(403); }

        $supply = \App\Models\Supply::findOrFail($id);
        $supply->delete();

        return back()->with('success', 'Global Product Deleted Successfully.');
    }
    // ==========================================================
    // Master HQ Specific Views & Actions
    // ==========================================================

    public function hqOverview()
    {
        if (!Auth::user()->is_hq) { abort(403); }
        $branches = User::where('role', 'supply_company')->where('is_hq', false)->get();
        $globalSuppliesCount = \App\Models\Supply::count();
        return view('admin.supplies.hq_overview', compact('branches', 'globalSuppliesCount'));
    }

    public function hqCatalog(Request $request)
    {
        if (!Auth::user()->is_hq) { abort(403); }

        // 💡 التعديل هنا: تحديد المسار الكامل للموديل \App\Models\Supply
        $query = \App\Models\Supply::orderBy('created_at', 'desc');

        // تطبيق الفلتر إذا قام الآدمن باختيار قسم معين
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $globalSupplies = $query->paginate(12)->withQueryString();

        return view('admin.supplies.hq_catalog', compact('globalSupplies'));
    }

    public function hqTelemetry(Request $request)
    {
        if (!Auth::user()->is_hq) { abort(403); }

        $branches = User::where('role', 'supply_company')->where('is_hq', false)->get();
        $selectedBranch = $request->branch_id ? User::findOrFail($request->branch_id) : $branches->first();

        if (!$selectedBranch) {
            return view('admin.supplies.hq_telemetry', [
                'branches' => $branches,
                'inventory' => collect(),
                'selectedBranch' => null
            ]);
        }

        $query = \App\Models\Supply::select('supplies.*', 'supply_inventories.stock')
            ->leftJoin('supply_inventories', function($join) use ($selectedBranch) {
                $join->on('supplies.id', '=', 'supply_inventories.supply_id')
                     ->where('supply_inventories.company_id', '=', $selectedBranch->id);
            });

        if ($request->has('category') && $request->category != '') {
            $query->where('supplies.category', $request->category);
        }

        // Load Balancing Sort: Lowest stock first
        $inventory = $query->orderByRaw('COALESCE(supply_inventories.stock, 0) ASC')
                           ->paginate(12)->withQueryString();

        return view('admin.supplies.hq_telemetry', compact('branches', 'selectedBranch', 'inventory'));
    }

    public function replenishStock(Request $request)
    {
        if (!Auth::user()->is_hq) { abort(403); }

        $request->validate([
            'supply_id' => 'required|exists:supplies,id',
            'company_id' => 'required|exists:users,id',
            'qty' => 'required|integer|min:1',
        ]);

        $inventory = \App\Models\SupplyInventory::firstOrCreate(
            ['supply_id' => $request->supply_id, 'company_id' => $request->company_id],
            ['stock' => 0]
        );

        $inventory->increment('stock', $request->qty);

        return back()->with('success', 'Replenishment successful. Stock properly incremented.');
    }
}