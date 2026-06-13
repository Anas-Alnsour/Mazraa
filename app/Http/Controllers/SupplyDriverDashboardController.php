<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // 🚀 1. تفكيك قنبلة الرام: حساب الإحصائيات داخل قاعدة البيانات مباشرة
        // نستخدم COUNT DISTINCT لأن الفاتورة الواحدة (order_id) قد تحتوي على عدة منتجات
        $stats = SupplyOrder::where('driver_id', $user->id)
            ->select(
                DB::raw('COUNT(DISTINCT order_id) as totalDeliveries'),
                DB::raw('COUNT(DISTINCT CASE WHEN status IN ("waiting_driver", "assigned", "in_way", "out_for_delivery") THEN order_id END) as pendingDeliveries'),
                DB::raw('COUNT(DISTINCT CASE WHEN status IN ("completed", "delivered") THEN order_id END) as completedDeliveries')
            )->first();

        // 🚀 2. تفكيك قنبلة الواجهة: استخدام paginate لمنع انهيار متصفح هاتف السائق
        $orders = SupplyOrder::with(['user', 'supply.company', 'booking.farm'])
            ->where('driver_id', $user->id)
            ->latest()
            ->paginate(50); // سحب أحدث المنتجات فقط لتخفيف الحمل

        // تجميع الطلبات حسب رقم الفاتورة (Invoice) لصفحة العرض
        $groupedOrders = $orders->groupBy('order_id');

        return view('delivery.orders', [
            'groupedOrders'       => $groupedOrders,
            'orders_pagination'   => $orders, // 👈 تم تمريرها لاستخدام أزرار التقليب $orders_pagination->links()
            'totalDeliveries'     => $stats->totalDeliveries,
            'pendingDeliveries'   => $stats->pendingDeliveries,
            'completedDeliveries' => $stats->completedDeliveries
        ]);
    }

    // زر إنهاء التوصيل (Mark as Delivered)
    public function markDelivered(Request $request, $orderId)
    {
        $user = Auth::user();

        if ($user->role !== 'supply_driver') {
            abort(403, 'Unauthorized access.');
        }

        // 🚀 3. تفكيك قنبلة الـ N+1 Updates: تحديث كل منتجات الفاتورة باستعلام Bulk واحد فقط!
        $updatedRows = SupplyOrder::where('order_id', $orderId)
            ->where('driver_id', $user->id)
            ->whereNotIn('status', ['delivered', 'completed', 'cancelled']) // حماية من التحديث المزدوج
            ->update(['status' => 'delivered']);

        if ($updatedRows === 0) {
            return back()->with('error', 'Invoice not found, already delivered, or not assigned to you.');
        }

        // إشعار ذكي (اختياري)
        // إذا أردت إرسال إشعار للشركة الموردة أو الزبون بأن البضاعة وصلت، يمكنك إضافته هنا.

        return back()->with('success', "Invoice {$orderId} has been successfully marked as Delivered!");
    }
}
