<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyOrder;
use App\Models\FarmBooking;
use App\Models\User;
use App\Models\SupplyInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplyController extends Controller
{
    /**
     * 1. Display the Supply Marketplace for customers (With Cart Logic & Filters)
     */
    public function index(Request $request)
    {
        $eligibleBookings = [];
        $activeBooking = null;
        $localCompany = null;
        $isRestricted = true; // نفترض المنع افتراضياً (View-Only)

        // 1. التحقق من حجوزات المستخدم
        if (Auth::check() && Auth::user()->role === 'user') {
            $userBookings = FarmBooking::with('farm')
                ->where('user_id', Auth::id())
                ->whereIn('status', ['confirmed', 'paid'])
                ->orderBy('start_time', 'asc')
                ->get();

            foreach ($userBookings as $booking) {
                // 💡 التحقق من شرط الوقت: (قبل ساعتين أو خلال التواجد)
                if ($booking->isWithinSupplyCheckoutWindow()) {
                    $eligibleBookings[] = $booking;
                }
            }

            // إذا كان لديه حجز في الوقت المسموح
            if (count($eligibleBookings) > 0) {
                $isRestricted = false;
                $activeBooking = $request->has('booking_id')
                    ? collect($eligibleBookings)->firstWhere('id', $request->booking_id)
                    : collect($eligibleBookings)->first();

                // تحديد فرع التوريد بناءً على محافظة المزرعة المحجوزة
                if ($activeBooking && $activeBooking->farm) {
                    $localCompany = User::where('role', 'supply_company')
                        ->where('governorate', $activeBooking->farm->governorate)
                        ->first();
                }
            }
        }

        // 2. جلب جميع المنتجات (الكتالوج العالمي)
        $query = Supply::query();

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // 3. دمج مخزون الفرع المحلي فقط إذا تم تحديد الفرع
        if ($localCompany) {
            $query->with(['inventories' => function($q) use ($localCompany) {
                $q->where('company_id', $localCompany->id);
            }]);
        }

        $supplies = $query->latest()->paginate(12);

        return view('supplies.frontend.index', compact('supplies', 'eligibleBookings', 'activeBooking', 'localCompany', 'isRestricted'));
    }

    /**
     * 2. عرض تفاصيل منتج واحد
     */
    public function show(Supply $supply)
    {
        // Removed company relation because it's now Centralized
        $supply->load(['inventories.company']);
        return view('supplies.frontend.show', compact('supply'));
    }

    /**
     * 4. عرض طلباتي / مشترياتي
     */
    public function myOrders()
    {
        $orders = SupplyOrder::with(['supply.inventories.company', 'booking.farm', 'driver'])
            ->where('user_id', auth()->id())
            ->where('status', '!=', 'cart')
            ->latest()
            ->get();

        $groupedOrders = $orders->groupBy(function ($order) {
            return $order->order_id ?? $order->id;
        });

        return view('supplies.frontend.my_orders', compact('groupedOrders'));
    }

    /**
     * 5. تعديل الطلب
     */
    public function editOrder(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        if ($order->status !== 'pending') {
            return redirect()->route('orders.my_orders')->with('error', 'You cannot edit an order that is already being processed.');
        }

        return view('supplies.frontend.edit_order', compact('order'));
    }

    /**
     * 6. تحديث الطلب (تم تحديثه ليعمل مع المعمارية الجديدة)
     */
    public function updateOrder(Request $request, SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        if ($order->status !== 'pending') abort(403, 'Order cannot be updated.');

        $localCompany = User::where('role', 'supply_company')
            ->where('governorate', $order->destination_governorate ?? ($order->booking->farm->governorate ?? 'Amman'))
            ->first();

        $localInventory = null;
        if ($localCompany) {
            $localInventory = SupplyInventory::where('company_id', $localCompany->id)
                ->where('supply_id', $order->supply_id)
                ->first();
        }

        $stockAvailable = $localInventory ? $localInventory->stock : 0;

        $request->validate([
            'quantity' => 'required|integer|min:1|max:'.($stockAvailable + $order->quantity),
        ]);

        $newQuantity = $request->quantity;
        $diff = $newQuantity - $order->quantity;

        // تعديل المخزون المحلي
        if ($localInventory) {
            $localInventory->decrement('stock', $diff);
        }

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

        return redirect()->route('orders.my_orders')->with('success', 'Order updated successfully!');
    }

    /**
     * 7. إلغاء الطلب بالكامل
     */
    public function destroyOrder(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        if ($order->status !== 'pending' && $order->status !== 'cart') {
            return back()->with('error', 'You can only cancel pending or cart orders.');
        }

        if ($order->status === 'pending') {
            $localCompany = User::where('role', 'supply_company')
                ->where('governorate', $order->destination_governorate ?? ($order->booking->farm->governorate ?? 'Amman'))
                ->first();

            if ($localCompany) {
                $localInventory = SupplyInventory::where('company_id', $localCompany->id)
                    ->where('supply_id', $order->supply_id)
                    ->first();
                if ($localInventory) {
                    $localInventory->increment('stock', $order->quantity);
                }
            }
        }

        $order->delete();

        return back()->with('success', 'Order canceled and deleted successfully.');
    }
}
