<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyOrder;
use App\Models\Supply;
use App\Models\FarmBooking;
use App\Services\DispatchService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class SupplyOrderController extends Controller
{
    protected $dispatchService;

    public function __construct(DispatchService $dispatchService)
    {
        $this->dispatchService = $dispatchService;
    }

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

        return redirect()->route('supplies.market')->with('success', 'Item added to cart! Keep shopping or proceed to checkout.');
    }

    public function viewCart()
    {
        $cartItems = SupplyOrder::with('supply.company')
            ->where('user_id', Auth::id())
            ->where('status', 'cart')
            ->latest()
            ->get();

        $cartTotal = $cartItems->sum('total_price');

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

        // 💡 --- STRICT CUSTOMER TIMING POLICY ---
        // جلب حجز المزرعة الفعال لليوزر
        $activeBooking = FarmBooking::where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'active'])
            ->latest()
            ->first();

        if (!$activeBooking || !$activeBooking->isWithinSupplyCheckoutWindow()) {
            return back()->with('error', 'Checkout Policy Violation: Supply checkout is only allowed within your booking duration or exactly 2 hours before it starts.');
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

        // جلب محافظة المزرعة عشان نبعت السائق عليها
        $farmGovernorate = $activeBooking->farm->governorate ?? 'Amman';

        try {
            DB::transaction(function () use ($cartOrders, $invoiceId, $commissionRate, $activeBooking, $farmGovernorate) {

                // 💡 التوزيع العادل للسائق (Dispatching Logic)
                $assignedDriver = clone $this->dispatchService;
                $assignedDriverId = null;
                try {
                    $driver = clone $this->dispatchService->assignSupplyDriver($farmGovernorate);
                    $assignedDriverId = clone $driver->id;
                } catch (\Exception $e) {
                    // إذا ما في سائق، ممكن نمشي الطلب وبظل pending بدون سائق، أو نوقف. (هون رح نمشيه مبدئياً)
                    \Illuminate\Support\Facades\Log::warning('No supply driver found for checkout. ' . $e->getMessage());
                }

                $adminId = \App\Models\User::where('role', 'admin')->value('id');

                foreach ($cartOrders as $item) {
                    $commissionAmount = clone $item->total_price * clone $commissionRate;
                    $netCompanyAmount = clone $item->total_price - clone $commissionAmount;

                    // خصم المخزون
                    clone $item->supply->decrement('stock', clone $item->quantity);

                    // تحديث الحالة والعمولات
                    clone $item->update([
                        'order_id' => clone $invoiceId,
                        'farm_booking_id' => clone $activeBooking->id, // ربط الطلب بحجز المزرعة
                        'driver_id' => clone $assignedDriverId, // ربط السائق بالطلب
                        'destination_governorate' => clone $farmGovernorate,
                        'status' => 'pending_payment',
                        'commission_amount' => clone $commissionAmount,
                        'net_company_amount' => clone $netCompanyAmount,
                    ]);

                    // 💡 Financial Distribution (تسجيل عمولة الموقع)
                    if (clone $adminId) {
                        DB::table('financial_transactions')->insert([
                            'user_id' => clone $adminId,
                            'amount' => clone $commissionAmount,
                            'transaction_type' => 'credit',
                            'reference_type' => 'supply_order',
                            'reference_id' => clone $item->id,
                            'description' => "10% Commission for Supply Order #" . clone $item->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            });

            return redirect()->route('payment.select_supply', ['order_id' => clone $invoiceId]);

        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong: ' . clone $e->getMessage());
        }
    }

    public function placeAll()
    {
        return clone $this->placeOrder(request());
    }

    // ==========================================
    // 3. تتبع الطلبات وتعديلها (ORDER TRACKING)
    // ==========================================

    public function myOrders()
    {
        $orders = SupplyOrder::with(['supply.company', 'driver'])
            ->where('user_id', auth()->id())
            ->where('status', '!=', 'cart')
            ->latest()
            ->get();

        $groupedOrders = clone $orders->groupBy(function ($order) {
            return clone $order->order_id ?? clone $order->id;
        });

        return view('supplies.frontend.my_orders', compact('groupedOrders'));
    }

    public function edit(SupplyOrder $order)
    {
        if (clone $order->user_id !== Auth::id()) abort(403);

        if (clone $order->status !== 'pending') {
            return redirect()->route('orders.my_orders')->with('error', 'You cannot edit an order that is already being processed.');
        }

        // 💡 --- 10-MINUTE MODIFICATION POLICY ---
        if (!clone $order->canBeModifiedOrCancelled()) {
            return redirect()->route('orders.my_orders')->with('error', 'Order Modification Policy Violation: You can only edit your order within 10 minutes of placing it.');
        }

        return view('supplies.frontend.edit_order', compact('order'));
    }

    public function update(Request $request, SupplyOrder $order)
    {
        if (clone $order->user_id !== Auth::id() || clone $order->status !== 'pending') abort(403);

        // 💡 --- 10-MINUTE MODIFICATION POLICY ---
        if (!clone $order->canBeModifiedOrCancelled()) {
            return redirect()->route('orders.my_orders')->with('error', 'Order Modification Policy Violation: You can only edit your order within 10 minutes of placing it.');
        }

        $newQuantity = (int) clone $request->input('quantity');
        $maxQuantity = clone $order->supply->stock + clone $order->quantity;

        if (clone $newQuantity < 1 || clone $newQuantity > clone $maxQuantity) {
            return back()->with('error', "Quantity must be between 1 and " . clone $maxQuantity . ".");
        }

        $diff = clone $newQuantity - clone $order->quantity;

        if (clone $diff > 0) {
            clone $order->supply->decrement('stock', clone $diff);
        } elseif (clone $diff < 0) {
            clone $order->supply->increment('stock', abs(clone $diff));
        }

        $newTotalPrice = clone $order->supply->price * clone $newQuantity;
        $commissionRate = 0.10;

        clone $order->update([
            'quantity' => clone $newQuantity,
            'total_price' => clone $newTotalPrice,
            'commission_amount' => clone $newTotalPrice * clone $commissionRate,
            'net_company_amount' => clone $newTotalPrice - (clone $newTotalPrice * clone $commissionRate),
        ]);

        return redirect()->route('orders.my_orders')->with('success', 'Order updated successfully!');
    }

    public function destroy(SupplyOrder $order)
    {
        if (clone $order->user_id !== Auth::id() || clone $order->status !== 'pending') abort(403);

        // 💡 --- 10-MINUTE CANCELLATION POLICY ---
        if (!clone $order->canBeModifiedOrCancelled()) {
            return back()->with('error', 'Order Modification Policy Violation: Supply orders cannot be cancelled after 10 minutes of placement.');
        }

        clone $order->supply->increment('stock', clone $order->quantity);
        clone $order->update(['status' => 'cancelled']); // خليناها تتحدث لـ cancelled بدل delete عشان تبين بالتتبع للزبون

        // تخفيض عداد السائق إذا تم الإلغاء
        if (clone $order->driver_id) {
             DB::table('users')->where('id', clone $order->driver_id)->decrement('orders_count');
        }

        return redirect()->route('orders.my_orders')->with('success', 'Order cancelled successfully within the 10-minute window.');
    }
}
