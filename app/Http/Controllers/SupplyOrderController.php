<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyOrder;
use App\Models\Supply;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SupplyOrderController extends Controller
{
    // ==========================================
    // 1. نظام السلة (CART SYSTEM)
    // ==========================================

    public function addToCart(Request $request, Supply $supply)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $supply->stock,
        ]);

        $quantity = $request->quantity;
        $totalPrice = $supply->price * $quantity;

        // فحص إذا المنتج موجود أصلاً بالسلة
        $existingOrder = SupplyOrder::where('user_id', Auth::id())
            ->where('supply_id', $supply->id)
            ->where('status', 'cart')
            ->first();

        if ($existingOrder) {
            $newQuantity = $existingOrder->quantity + $quantity;
            if ($newQuantity > $supply->stock) {
                return back()->with('error', 'Not enough stock available.');
            }
            $existingOrder->update([
                'quantity' => $newQuantity,
                'total_price' => $supply->price * $newQuantity,
            ]);
        } else {
            // إضافة للسلة بدون خصم مخزون أو عمولات
            SupplyOrder::create([
                'user_id' => Auth::id(),
                'supply_id' => $supply->id,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'status' => 'cart',
            ]);
        }

        return back()->with('success', 'Item added to cart! Keep shopping or proceed to checkout.');
    }

    public function viewCart()
    {
        $cartItems = SupplyOrder::with('supply.company')
            ->where('user_id', Auth::id())
            ->where('status', 'cart')
            ->latest()
            ->get();

        $cartTotal = $cartItems->sum('total_price');

        // التوجيه لواجهة السلة الفخمة الجديدة
        return view('supplies.frontend.cart', compact('cartItems', 'cartTotal'));
    }

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

    public function removeFromCart(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'cart') {
            abort(403);
        }

        $order->delete();
        return back()->with('success', 'Removed from cart successfully!');
    }

    // ==========================================
    // 2. الدفع وتأكيد الطلب (CHECKOUT)
    // ==========================================

    public function placeOrder(Request $request)
    {
        $cartOrders = SupplyOrder::with('supply')
            ->where('user_id', Auth::id())
            ->where('status', 'cart')
            ->get();

        if ($cartOrders->isEmpty()) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
        }

        // 1. حماية: فحص المخزون قبل أي عملية خصم
        foreach ($cartOrders as $item) {
            if ($item->quantity > $item->supply->stock) {
                return back()->with('error', "Sorry, {$item->supply->name} no longer has enough stock. Please adjust your cart.");
            }
        }

        // 2. إنشاء رقم فاتورة موحد
        $invoiceId = 'INV-' . strtoupper(Str::random(8));
        $commissionRate = 0.10;

        // 3. تنفيذ العمليات بـ Transaction
        DB::transaction(function () use ($cartOrders, $invoiceId, $commissionRate) {
            foreach ($cartOrders as $item) {
                $commissionAmount = $item->total_price * $commissionRate;
                $netCompanyAmount = $item->total_price - $commissionAmount;

                // خصم المخزون
                $item->supply->decrement('stock', $item->quantity);

                // تحديث الحالة والعمولات
                $item->update([
                    'order_id' => $invoiceId,
                    'status' => 'pending',
                    'commission_amount' => $commissionAmount,
                    'net_company_amount' => $netCompanyAmount,
                ]);
            }
        });

        return redirect()->route('orders.my_orders')->with('success', "Order placed successfully! Reference: {$invoiceId}");
    }

    public function placeAll()
    {
        // هذه الدالة تم استبدالها بـ placeOrder، لكن تركناها لحماية راوتك القديم
        return $this->placeOrder(request());
    }

    // ==========================================
    // 3. تتبع الطلبات وتعديلها (ORDER TRACKING)
    // ==========================================

    public function myOrders()
    {
        $orders = SupplyOrder::with(['supply.company', 'driver'])
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'cart')
            ->latest()
            ->get();

        $groupedOrders = $orders->groupBy(function ($order) {
            return $order->order_id ?? $order->id;
        });

        // التوجيه لواجهة التتبع الجديدة (Talabat Style)
        return view('supplies.frontend.my_orders', compact('groupedOrders'));
    }

    public function edit(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        if ($order->status !== 'pending') {
            return redirect()->route('orders.my_orders')->with('error', 'You cannot edit an order that is already being processed.');
        }

        // التوجيه لواجهة التعديل الجديدة
        return view('supplies.frontend.edit_order', compact('order'));
    }

    public function update(Request $request, SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'pending') abort(403);

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

        $newTotalPrice = $order->supply->price * $newQuantity;
        $commissionRate = 0.10;

        $order->update([
            'quantity' => $newQuantity,
            'total_price' => $newTotalPrice,
            'commission_amount' => $newTotalPrice * $commissionRate,
            'net_company_amount' => $newTotalPrice - ($newTotalPrice * $commissionRate),
        ]);

        return redirect()->route('orders.my_orders')->with('success', 'Order updated successfully!');
    }

    public function destroy(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'pending') abort(403);

        $order->supply->increment('stock', $order->quantity);
        $order->delete();

        return redirect()->route('orders.my_orders')->with('success', 'Order cancelled successfully!');
    }
}
