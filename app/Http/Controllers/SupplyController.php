<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supply;
use App\Models\SupplyOrder;
use App\Models\FarmBooking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SupplyController extends Controller
{
    // 1. تصفح المتجر (للزبائن)
    public function index()
    {
        $supplies = Supply::where('stock', '>', 0)
            ->with('company')
            ->latest()
            ->paginate(12);

        return view('supplies.frontend.index', compact('supplies'));
    }

    // 2. عرض تفاصيل منتج واحد
    public function show(Supply $supply)
    {
        return view('supplies.frontend.show', compact('supply'));
    }

    // 3. معالجة الشراء (Checkout)
    public function order(Request $request, Supply $supply)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:'.$supply->stock,
            'booking_id' => 'nullable|exists:farm_bookings,id'
        ]);

        $quantity = $request->quantity;
        $totalAmount = $supply->price * $quantity;

        // حساب العمولة (10% للمنصة)
        $commissionRate = 0.10;
        $commissionAmount = $totalAmount * $commissionRate;
        $netCompanyAmount = $totalAmount - $commissionAmount;

        // خصم الكمية من المخزون
        $supply->decrement('stock', $quantity);

        // إنشاء رقم طلب مميز
        $orderReference = 'ORD-' . strtoupper(Str::random(8));

        // حفظ الطلب
        SupplyOrder::create([
            'order_id' => $orderReference,
            'user_id' => Auth::id(),
            'supply_id' => $supply->id,
            'quantity' => $quantity,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'booking_id' => $request->booking_id,
            'commission_amount' => $commissionAmount,
            'net_company_amount' => $netCompanyAmount,
        ]);

        return redirect()->route('supplies.my_orders')
            ->with('success', "Order {$orderReference} placed successfully!");
    }

    // 4. عرض طلباتي (مشترياتي)
    public function myOrders()
    {
        $orders = SupplyOrder::with(['supply.company', 'driver'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('supplies.frontend.my_orders', compact('orders'));
    }

    // 5. تعديل الطلب (تمت إعادتها من كودك الأصلي)
    public function editOrder(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        // منع تعديل الطلب إذا تم قبوله أو إرساله
        if ($order->status !== 'pending') {
            return redirect()->route('supplies.my_orders')->with('error', 'You cannot edit an order that is already being processed.');
        }

        // تم تغيير مسار العرض ليتناسب مع المجلدات الجديدة
        return view('supplies.frontend.edit_order', compact('order'));
    }

    // 6. تحديث الطلب (تمت إعادتها من كودك الأصلي + إضافة حسابات العمولة الجديدة)
    public function updateOrder(Request $request, SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        if ($order->status !== 'pending') abort(403, 'Order cannot be updated.');

        $request->validate([
            'quantity' => 'required|integer|min:1|max:'.($order->supply->stock + $order->quantity),
        ]);

        $newQuantity = $request->quantity;
        $diff = $newQuantity - $order->quantity;

        // تعديل المخزون
        $order->supply->decrement('stock', $diff);

        // إعادة حساب المبالغ والعمولات بناءً على الكمية الجديدة
        $newTotalAmount = $order->supply->price * $newQuantity;
        $commissionRate = 0.10;
        $newCommissionAmount = $newTotalAmount * $commissionRate;
        $newNetCompanyAmount = $newTotalAmount - $newCommissionAmount;

        $order->update([
            'quantity' => $newQuantity,
            'total_amount' => $newTotalAmount,
            'commission_amount' => $newCommissionAmount,
            'net_company_amount' => $newNetCompanyAmount,
        ]);

        return redirect()->route('supplies.my_orders')->with('success', 'Order updated successfully!');
    }

    // 7. إلغاء الطلب بالكامل (تمت إعادتها من كودك الأصلي)
    public function destroyOrder(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        if ($order->status !== 'pending') {
            return back()->with('error', 'You can only cancel pending orders.');
        }

        // إعادة الكمية للمخزون
        $order->supply->increment('stock', $order->quantity);

        // حذف الطلب نهائياً كما في كودك الأصلي
        $order->delete();

        return back()->with('success', 'Order canceled and deleted successfully.');
    }
}
