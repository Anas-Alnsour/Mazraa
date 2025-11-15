<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supply;
use App\Models\SupplyOrder;
use Illuminate\Support\Facades\Auth;

class SupplyController extends Controller
{
    // عرض كل المستلزمات المتوفرة للطلب
    public function index()
    {
        $supplies = Supply::all();
        return view('supplies.index', compact('supplies'));
    }

    // صفحة طلب عنصر محدد
    public function show(Supply $supply)
    {
        return view('supplies.show', compact('supply'));
    }

    // حفظ الطلب
    public function order(Request $request, Supply $supply)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:'.$supply->stock,
        ]);

        $quantity = $request->quantity;
        $totalPrice = $supply->price * $quantity;

        $supply->decrement('stock', $quantity);

        SupplyOrder::create([
            'user_id' => Auth::id(),
            'supply_id' => $supply->id,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
        ]);

        return back()->with('success', "You ordered {$quantity} {$supply->name}(s) successfully!");
    }

    // عرض الطلبات الخاصة بالمستخدم
    public function myOrders()
    {
        $orders = SupplyOrder::with('supply')
            ->where('user_id', Auth::id())
            ->get();

        return view('supplies.my_orders', compact('orders'));
    }

    // تعديل كمية الطلب
    public function editOrder(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        return view('supplies.edit_order', compact('order'));
    }

    public function updateOrder(Request $request, SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:'.$order->supply->stock + $order->quantity,
        ]);

        $newQuantity = $request->quantity;
        $diff = $newQuantity - $order->quantity;

        // تعديل المخزون
        $order->supply->decrement('stock', $diff);

        $order->update([
            'quantity' => $newQuantity,
            'total_price' => $order->supply->price * $newQuantity,
        ]);

        return redirect()->route('orders.my_orders')->with('success', 'Order updated successfully!');
    }

    // إلغاء الطلب
    public function destroyOrder(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        // إعادة الكمية للمخزون
        $order->supply->increment('stock', $order->quantity);

        $order->delete();

        return back()->with('success', 'Order canceled successfully.');
    }

    
}
