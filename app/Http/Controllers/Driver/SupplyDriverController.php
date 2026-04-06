<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\SupplyOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplyDriverController extends Controller
{
    /**
     * Display the Supply Driver Mobile Dashboard.
     */
    public function dashboard()
    {
        if (Auth::user()->role !== 'supply_driver') {
            abort(403, 'Unauthorized access.');
        }

        // Group the driver's orders by Invoice ID to simplify their delivery route
        $orders = SupplyOrder::with(['supply.company', 'user', 'booking.farm'])
            ->where('driver_id', Auth::id())
            ->whereNotIn('status', ['cart', 'pending_payment', 'pending_verification'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $groupedOrders = $orders->groupBy('order_id');

        // 💡 التعديل هون: مسار ملفك الأصلي حسب الصورة اللي بعثتها
        return view('supplies.drivers.supply_dashboard', compact('groupedOrders'));
    }

    /**
     * Update the logistics tracking status of a supply order (Grouped by Invoice ID).
     */
    public function updateStatus(Request $request, $order_id)
    {
        if (Auth::user()->role !== 'supply_driver') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'status' => 'required|in:waiting_driver,in_way,delivered'
        ]);

        // Fetch all items under this Invoice ID assigned to this driver
        $orders = SupplyOrder::where('order_id', $order_id)
            ->where('driver_id', Auth::id())
            ->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'Orders not found or unauthorized.');
        }

        DB::beginTransaction();

        try {
            // فحص إذا الفاتورة أصلاً تسلمت من قبل عشان ما ننقص العداد أكثر من مرة
            $alreadyDelivered = $orders->first()->status === 'delivered';

            // تحديث حالة كل العناصر التابعة لنفس الفاتورة
            foreach ($orders as $order) {
                $order->update(['status' => $request->status]);
            }

            // 💡 التعديل هون: تنقيص العداد مرة واحدة فقط لكل فاتورة تكتمل
            if (!$alreadyDelivered && $request->status === 'delivered') {
                DB::table('users')
                    ->where('id', Auth::id())
                    ->where('orders_count', '>', 0) // حماية إضافية عشان ما يصير العداد بالسالب
                    ->decrement('orders_count');
            }

            DB::commit();
            return back()->with('success', 'Delivery status updated successfully to: ' . strtoupper(str_replace('_', ' ', $request->status)));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update delivery status.');
        }
    }
}
