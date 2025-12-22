<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyOrder;
use App\Models\Supply;
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

    // Add to Cart
    public function addToCart(Request $request, Supply $supply)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $supply->stock,
        ]);

        $quantity = $request->quantity;
        $totalPrice = $supply->price * $quantity;

        // Check if already in cart
        $existingOrder = SupplyOrder::where('user_id', Auth::id())
            ->where('supply_id', $supply->id)
            ->where('status', 'cart')
            ->first();

        if ($existingOrder) {
            $newQuantity = $existingOrder->quantity + $quantity;
            if ($newQuantity > $supply->stock) {
                return back()->with('error', 'Not enough stock.');
            }
            $existingOrder->update([
                'quantity' => $newQuantity,
                'total_price' => $supply->price * $newQuantity,
            ]);
        } else {
            SupplyOrder::create([
                'user_id' => Auth::id(),
                'supply_id' => $supply->id,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'status' => 'cart',
            ]);
        }

        return back()->with('success', 'Added to cart successfully!');
    }

    // View Cart
    public function viewCart()
    {
        $cartOrders = SupplyOrder::with('supply')
            ->where('user_id', Auth::id())
            ->where('status', 'cart')
            ->get();

        $total = $cartOrders->sum('total_price');

        return view('supplies.cart', compact('cartOrders', 'total'));
    }

    // Update Cart Item
    public function updateCart(Request $request, SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'cart') {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $order->supply->stock,
        ]);

        $quantity = $request->quantity;
        $order->update([
            'quantity' => $quantity,
            'total_price' => $order->supply->price * $quantity,
        ]);

        return back()->with('success', 'Cart updated successfully!');
    }

    // Remove from Cart
    public function removeFromCart(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'cart') {
            abort(403);
        }

        $order->delete();

        return back()->with('success', 'Removed from cart successfully!');
    }

    // Place Order
    public function placeOrder()
    {
        $cartOrders = SupplyOrder::where('user_id', Auth::id())
            ->where('status', 'cart')
            ->get();

        if ($cartOrders->isEmpty()) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
        }

        foreach ($cartOrders as $order) {
            $order->supply->decrement('stock', $order->quantity);
            $order->status = 'in_way';
            $order->save();
        }

        return redirect()->route('orders.my_orders')->with('success', 'Order placed successfully!');
    }
}
