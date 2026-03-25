<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyOrder;
use Illuminate\Support\Facades\Auth;

class SupplyDriverDashboardController extends Controller
{
    // عرض لوحة تحكم السائق والطلبات المسندة له
    public function index()
    {
        $user = Auth::user();

        // حماية: التأكد من أن المستخدم هو سائق توريد فقط
        if ($user->role !== 'supply_driver') {
            abort(403, 'Unauthorized access. Only Supply Drivers can view this page.');
        }

        // جلب الطلبات المسندة لهاد السائق
        $orders = SupplyOrder::with(['user', 'supply.company', 'booking.farm'])
            ->where('driver_id', $user->id)
            ->latest()
            ->get();

        // تجميع الطلبات حسب رقم الفاتورة (Invoice)
        $groupedOrders = $orders->groupBy('order_id');

        // حساب الإحصائيات لعرضها بالساعات الذكية للسائق
        $totalDeliveries = $groupedOrders->count();

        $pendingDeliveries = $groupedOrders->filter(function ($items) {
            return in_array($items->first()->status, ['assigned', 'in_way', 'out_for_delivery']);
        })->count();

        $completedDeliveries = $groupedOrders->filter(function ($items) {
            return in_array($items->first()->status, ['completed', 'delivered']);
        })->count();

        return view('delivery.orders', compact(
            'groupedOrders',
            'totalDeliveries',
            'pendingDeliveries',
            'completedDeliveries'
        ));
    }

    // زر إنهاء التوصيل (Mark as Delivered)
    public function markDelivered(Request $request, $orderId)
    {
        $user = Auth::user();

        if ($user->role !== 'supply_driver') {
            abort(403, 'Unauthorized access.');
        }

        // جلب كل منتجات الفاتورة المسندة لهاد السائق
        $items = SupplyOrder::where('order_id', $orderId)
            ->where('driver_id', $user->id)
            ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Invoice not found or not assigned to you.');
        }

        // تحديث حالة كل المنتجات لـ Delivered
        foreach ($items as $item) {
            $item->update(['status' => 'delivered']);
        }

        return back()->with('success', "Invoice {$orderId} has been successfully marked as Delivered!");
    }
}
