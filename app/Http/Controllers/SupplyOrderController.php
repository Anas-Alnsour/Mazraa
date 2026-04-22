<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supply;
use App\Models\SupplyOrder;
use App\Models\FarmBooking;
use App\Models\User;
use App\Models\SupplyInventory;
use App\Services\DispatchService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
        // 1. العثور على الحجز الفعال الصحيح (نفس اللوجيك الموجود في واجهة السوق)
        $userBookings = FarmBooking::with('farm')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'paid'])
            ->orderBy('start_time', 'asc')
            ->get();

        $activeBooking = null;
        foreach ($userBookings as $booking) {
            // نأخذ أول حجز مستوفٍ لشرط الوقت (خلال التواجد أو قبل ساعتين)
            if ($booking->isWithinSupplyCheckoutWindow()) {
                $activeBooking = $booking;
                break;
            }
        }

        $stockAvailable = 0;

        if ($activeBooking && $activeBooking->farm) {
            // 2. إيجاد شركة التوريد في نفس محافظة المزرعة
            $localCompany = User::where('role', 'supply_company')
                ->where('governorate', $activeBooking->farm->governorate)
                ->first();

            if ($localCompany) {
                // 3. جلب المخزون من جدول inventories
                $localInventory = SupplyInventory::where('supply_id', $supply->id)
                    ->where('company_id', $localCompany->id)
                    ->first();

                $stockAvailable = $localInventory ? $localInventory->stock : 0;
            }
        }

        // إذا لم يكن هناك مخزون متاح (أو لا يوجد حجز)، نمنع الإضافة
        if ($stockAvailable <= 0) {
             return back()->with('error', 'This item is not available in the region of your booked farm.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $stockAvailable,
        ]);

        $quantity = $request->quantity;
        $totalPrice = $supply->price * $quantity;

        // التحقق مما إذا كان المنتج موجوداً مسبقاً في السلة
        $existingOrder = SupplyOrder::where('user_id', Auth::id())
            ->where('supply_id', $supply->id)
            ->whereNull('booking_id') // Link to generic cart
            ->where('status', 'cart')
            ->first();

        if ($existingOrder) {
            $newQuantity = $existingOrder->quantity + $quantity;
            if ($newQuantity > $stockAvailable) {
                return back()->with('error', 'Cannot add more. Not enough local stock available.');
            }
            $existingOrder->update([
                'quantity' => $newQuantity,
                'total_price' => $supply->price * $newQuantity,
            ]);
        } else {
            SupplyOrder::create([
                'user_id' => Auth::id(),
                'supply_id' => $supply->id,
                'booking_id' => null,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'status' => 'cart',
            ]);
        }

        return redirect()->route('supplies.market')->with('success', 'Item added to cart! Keep shopping or proceed to checkout.');
    }

    public function viewCart()
    {
        if (!Auth::check() || Auth::user()->role !== 'user') abort(403);

        // 💡 التعديل هنا: جلب عناصر السلة مع المنتج فقط، الفرع سنحدده من الحجز
        $cartItems = SupplyOrder::with(['supply'])
            ->where('user_id', Auth::id())
            ->where('status', 'cart')
            ->get();

        $cartTotal = $cartItems->sum('total_price');

        $eligibleBookings = [];
        $localCompany = null; // 💡 تحديد الفرع المحلي

        $userBookings = FarmBooking::with('farm')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'paid'])
            ->get();

        foreach ($userBookings as $booking) {
            if ($booking->isWithinSupplyCheckoutWindow()) {
                $eligibleBookings[] = $booking;
            }
        }

        // 💡 تمرير اسم الفرع لصفحة السلة إذا كان هناك حجز صالح
        if (count($eligibleBookings) > 0) {
            $activeBooking = collect($eligibleBookings)->first();
            if ($activeBooking && $activeBooking->farm) {
                $localCompany = User::where('role', 'supply_company')
                    ->where('governorate', $activeBooking->farm->governorate)
                    ->first();
            }
        }

        return view('supplies.frontend.cart', compact('cartItems', 'cartTotal', 'eligibleBookings', 'localCompany'));
    }

    public function updateCart(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'user') abort(403);

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = SupplyOrder::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'cart')
            ->firstOrFail();

        $supply = $cartItem->supply;

        // 1. العثور على الحجز الفعال الصحيح (نفس اللوجيك الموحد)
        $userBookings = FarmBooking::with('farm')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'paid'])
            ->orderBy('start_time', 'asc')
            ->get();

        $activeBooking = null;
        foreach ($userBookings as $booking) {
            if ($booking->isWithinSupplyCheckoutWindow()) {
                $activeBooking = $booking;
                break;
            }
        }

        $stockAvailable = 0;

        // 2. فحص مخزون الفرع المرتبط بهذا الحجز تحديداً
        if ($activeBooking && $activeBooking->farm) {
            $localCompany = User::where('role', 'supply_company')
                ->where('governorate', $activeBooking->farm->governorate)
                ->first();

            if ($localCompany) {
                $localInventory = SupplyInventory::where('supply_id', $supply->id)
                    ->where('company_id', $localCompany->id)
                    ->first();
                $stockAvailable = $localInventory ? $localInventory->stock : 0;
            }
        }

        // 3. منع التحديث إذا كانت الكمية المطلوبة أكبر من المخزون المحلي الفعلي
        if ($stockAvailable < $request->quantity) {
            return back()->with('error', 'Sorry, only ' . $stockAvailable . ' units of ' . $supply->name . ' are available in your region.');
        }

        $cartItem->update([
            'quantity' => $request->quantity,
            'total_price' => $request->quantity * $supply->price,
        ]);

        return back()->with('success', 'Cart updated successfully.');
    }

    public function removeFromCart($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'user') abort(403);

        $cartItem = SupplyOrder::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'cart')
            ->firstOrFail();

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    // ==========================================
    // 2. الدفع وتأكيد الطلب (CHECKOUT)
    // ==========================================

    public function placeOrder(Request $request)
    {
        $cartOrders = SupplyOrder::with(['supply'])
            ->where('user_id', Auth::id())
            ->where('status', 'cart')
            ->get();

        if ($cartOrders->isEmpty()) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'booking_id' => 'required|exists:farm_bookings,id'
        ]);

        $activeBooking = FarmBooking::with('farm')->findOrFail($request->booking_id);

        if ($activeBooking->user_id !== Auth::id() || !$activeBooking->isWithinSupplyCheckoutWindow()) {
            return back()->with('error', 'Checkout Policy Violation: Supply checkout is only allowed within your booking duration or exactly 2 hours before it starts.');
        }

        $farmGovernorate = $activeBooking->farm->governorate ?? 'Amman';
        $bookingShift = $activeBooking->event_type;
        $dispatchShift = ($bookingShift === 'full_day') ? 'morning' : $bookingShift;

        $invoiceId = 'INV-' . strtoupper(Str::random(8));
        $commissionRate = 0.10;

        try {
            DB::transaction(function () use ($cartOrders, $invoiceId, $commissionRate, $activeBooking, $farmGovernorate, $dispatchShift) {

                // [FAIR DISPATCH] Invoke Algorithm
                $driver = $this->dispatchService->assignSupplyDriver($farmGovernorate, $dispatchShift);
                $assignedDriverId = $driver->id ?? null;

                // [STRICT ROUTING] Find Local Company
                $localCompany = User::where('role', 'supply_company')
                    ->where('governorate', $farmGovernorate)
                    ->first();

                if (!$localCompany) {
                    throw new \Exception("Operational Error: No supply branch found for [{$farmGovernorate}].");
                }

                foreach ($cartOrders as $item) {
                    // [INVENTORY ROUTING] Check Local Stock
                    $localInventory = SupplyInventory::where('company_id', $localCompany->id)
                        ->where('supply_id', $item->supply_id)
                        ->first();

                    if (!$localInventory || $localInventory->stock < $item->quantity) {
                        throw new \Exception("Insufficient stock in {$farmGovernorate} branch for [" . $item->supply->name . "].");
                    }

                    $commissionAmount = $item->total_price * $commissionRate;
                    $netCompanyAmount = $item->total_price - $commissionAmount;

                    // Decrement local stock
                    $localInventory->decrement('stock', $item->quantity);

                    $item->update([
                        'order_id'                => $invoiceId,
                        'booking_id'              => $activeBooking->id,
                        'driver_id'               => $assignedDriverId,
                        'destination_governorate' => $farmGovernorate,
                        'status'                  => $assignedDriverId ? 'pending_payment' : 'pending_assignment',
                        'commission_amount'       => $commissionAmount,
                        'net_company_amount'      => $netCompanyAmount,
                    ]);
                }
            });

            return redirect()->route('payment.select_supply', ['order_id' => $invoiceId]);

        } catch (\Exception $e) {
            return back()->with('error', 'Checkout Error: ' . $e->getMessage());
        }
    }

    public function placeAll()
    {
        return $this->placeOrder(request());
    }

    // ==========================================
    // 3. تتبع الطلبات (ORDER TRACKING)
    // ==========================================

    public function myOrders()
    {
        // 💡 التعديل هنا: جلب الشركة من خلال الـ inventories بدلاً من الـ company مباشرة
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
    public function edit(SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        if ($order->status !== 'pending') {
            return redirect()->route('orders.my_orders')->with('error', 'You cannot edit an order that is already being processed.');
        }

        if (!$order->canBeModifiedOrCancelled()) {
            return redirect()->route('orders.my_orders')->with('error', 'Order Modification Policy Violation: You can only edit your order within 10 minutes of placing it.');
        }

        return view('supplies.frontend.edit_order', compact('order'));
    }

    public function update(Request $request, SupplyOrder $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'pending') abort(403);

        if (!$order->canBeModifiedOrCancelled()) {
            return redirect()->route('orders.my_orders')->with('error', 'Order Modification Policy Violation: You can only edit your order within 10 minutes of placing it.');
        }

        $localCompany = \App\Models\User::where('role', 'supply_company')
            ->where('governorate', $order->destination_governorate ?? ($order->booking->farm->governorate ?? 'Amman'))
            ->first();

        $localInventory = null;
        if ($localCompany) {
            $localInventory = \App\Models\SupplyInventory::where('company_id', $localCompany->id)
                ->where('supply_id', $order->supply_id)
                ->first();
        }

        $stockAvailable = $localInventory ? $localInventory->stock : 0;

        $newQuantity = (int) $request->input('quantity');
        $maxQuantity = $stockAvailable + $order->quantity;

        if ($newQuantity < 1 || $newQuantity > $maxQuantity) {
            return back()->with('error', "Quantity must be between 1 and " . $maxQuantity . ".");
        }

        $diff = $newQuantity - $order->quantity;

        if ($diff > 0) {
            if ($localInventory) $localInventory->decrement('stock', $diff);
        } elseif ($diff < 0) {
            if ($localInventory) $localInventory->increment('stock', abs($diff));
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

        if (!$order->canBeModifiedOrCancelled()) {
            return back()->with('error', 'Order Modification Policy Violation: Supply orders cannot be cancelled after 10 minutes of placement.');
        }

        $localCompany = \App\Models\User::where('role', 'supply_company')
            ->where('governorate', $order->destination_governorate ?? ($order->booking->farm->governorate ?? 'Amman'))
            ->first();

        if ($localCompany) {
            $localInventory = \App\Models\SupplyInventory::where('company_id', $localCompany->id)
                ->where('supply_id', $order->supply_id)
                ->first();
            if ($localInventory) {
                $localInventory->increment('stock', $order->quantity);
            }
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.my_orders')->with('success', 'Order cancelled successfully within the 10-minute window.');
    }
}
