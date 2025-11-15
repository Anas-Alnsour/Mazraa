<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyOrder;
use Illuminate\Support\Facades\Auth;

class SupplyOrderController extends Controller
{
    // عرض جميع الطلبات الخاصة بالمستخدم
    public function myOrders()
    {
        $orders = SupplyOrder::with('supply')
            ->where('user_id', Auth::id())
            ->get();

        return view('supplies.my_orders', compact('orders'));
    }

    // صفحة تعديل الطلب
    public function edit(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Access denied');
        }

        return view('supplies.edit_order', compact('order'));
    }

    // تحديث الطلب
    public function update(Request $request, SupplyOrder $order)
    {
        $newQuantity = (int) $request->input('quantity');
        $maxQuantity = $order->supply->stock + $order->quantity;

        if ($newQuantity < 1 || $newQuantity > $maxQuantity) {
            return back()->with('error', "Quantity must be between 1 and {$maxQuantity}.");
        }

        $diff = $newQuantity - $order->quantity;

        if ($diff > 0) {
            $order->supply->decrement('stock', $diff);
        } elseif ($diff < 0) {
            $order->supply->increment('stock', abs($diff));
        }

        $order->quantity = $newQuantity;
        $order->total_price = $order->supply->price * $newQuantity;
        $order->save();

        return redirect()->route('orders.my_orders')->with('success', 'Order updated successfully!');
    }

    // إلغاء الطلب
    public function destroy(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Access denied');
        }

        $order->supply->increment('stock', $order->quantity);
        $order->delete();

        return redirect()->route('orders.my_orders')->with('success', 'Order cancelled successfully!');
    }

    // Place All Orders
    public function placeAll()
    {
        $orders = SupplyOrder::where('user_id', Auth::id())->get();

        if ($orders->isEmpty()) {
            return redirect()->route('orders.my_orders')->with('error', 'You have no orders to place.');
        }

        foreach ($orders as $order) {
            $order->status = 'placed'; // تأكد من وجود عمود status
            $order->save();
        }

        return redirect()->route('orders.my_orders')->with('success', 'All orders have been placed successfully!');
    }
}
