<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyOrder;
use App\Models\FarmBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SupplyController extends Controller
{
    /**
     * 1. Display the Supply Marketplace for customers (With Cart Logic & Filters)
     */
    public function index(Request $request)
    {
        $query = Supply::query();

        // 👈 فلترة حسب القسم إذا اليوزر كبس على واحد من الأزرار (من كودك الأصلي)
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Fetch supplies with available stock
        $supplies = $query->with('company')
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(12);

        $eligibleBookings = [];

        // Fetch user's confirmed/paid bookings to show in the modal (Cart Logic)
        if (Auth::check() && Auth::user()->role === 'user') {
            $userBookings = FarmBooking::with('farm')
                ->where('user_id', Auth::id())
                ->whereIn('status', ['confirmed', 'completed'])
                ->get();

            // Filter strictly by the 2-hour window rule using the Model method
            foreach ($userBookings as $booking) {
                if ($booking->isWithinSupplyCheckoutWindow()) {
                    $eligibleBookings[] = $booking;
                }
            }
        }

        // 💡 التعديل هون: رجعنا الـ view تبعك إنت بدل market تبع جولز
        return view('supplies.frontend.index', compact('supplies', 'eligibleBookings'));
    }

    /**
     * 2. عرض تفاصيل منتج واحد (من كودك الأصلي)
     */
    public function show(Supply $supply)
    {
        $supply->load(['company.receivedReviews.user']);
        return view('supplies.frontend.show', compact('supply'));
    }

    /**
     * 4. عرض طلباتي / مشترياتي (من كودك الأصلي - مدمج مع السلة)
     */
    public function myOrders()
    {
        // استثناء الطلبات اللي لسا بالـ Cart (عشان ما تظهر بصفحة المشتريات)
        $orders = SupplyOrder::with(['supply.company', 'booking.farm', 'driver'])
            ->where('user_id', auth()->id())
            ->where('status', '!=', 'cart')
            ->latest()
            ->get();

        // تجميع الطلبات حسب رقم الفاتورة (Invoice)
        $groupedOrders = $orders->groupBy(function ($order) {
            return $order->order_id ?? $order->id;
        });

        return view('supplies.frontend.my_orders', compact('groupedOrders'));
    }

    /**
     * 5. تعديل الطلب (من كودك الأصلي)
     */
    public function editOrder(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        if ($order->status !== 'pending') {
            return redirect()->route('supplies.my_orders')->with('error', 'You cannot edit an order that is already being processed.');
        }

        return view('supplies.frontend.edit_order', compact('order'));
    }

    /**
     * 6. تحديث الطلب (من كودك الأصلي)
     */
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
            'total_price' => $newTotalAmount,
            'commission_amount' => $newCommissionAmount,
            'net_company_amount' => $newNetCompanyAmount,
        ]);

        return redirect()->route('supplies.my_orders')->with('success', 'Order updated successfully!');
    }

    /**
     * 7. إلغاء الطلب بالكامل (من كودك الأصلي)
     */
    public function destroyOrder(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        if ($order->status !== 'pending' && $order->status !== 'cart') {
            return back()->with('error', 'You can only cancel pending or cart orders.');
        }

        // إذا كان الطلب تم تأكيده (مش كارت)، نرجع الكمية للمخزون
        if ($order->status === 'pending') {
            $order->supply->increment('stock', $order->quantity);
        }

        $order->delete();

        return back()->with('success', 'Order canceled and deleted successfully.');
    }
}
