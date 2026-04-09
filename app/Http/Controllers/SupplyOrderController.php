<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supply;
use App\Models\SupplyOrder;
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
    // 1. نظام السلة (CART SYSTEM) - (Jules + Your Logic)
    // ==========================================

    public function addToCart(Request $request, Supply $supply)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $supply->stock,
        ]);

        $bookingId = null;
        $booking = null;

        $quantity = $request->quantity;
        $totalPrice = $supply->price * $quantity;

        $existingOrder = SupplyOrder::where('user_id', Auth::id())
            ->where('supply_id', $supply->id)
            ->whereNull('booking_id') // Link to generic cart
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

        $cartItems = SupplyOrder::with(['supply.company', 'booking.farm'])
            ->where('user_id', Auth::id())
            ->where('status', 'cart')
            ->get();

        $cartTotal = $cartItems->sum('total_price');

        $eligibleBookings = [];
        if (Auth::user()->role === 'user') {
            $userBookings = FarmBooking::with('farm')
                ->where('user_id', Auth::id())
                ->whereIn('status', ['confirmed', 'completed'])
                ->get();

            foreach ($userBookings as $booking) {
                if ($booking->isWithinSupplyCheckoutWindow()) {
                    $eligibleBookings[] = $booking;
                }
            }
        }

        return view('supplies.frontend.cart', compact('cartItems', 'cartTotal', 'eligibleBookings'));
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

        if ($supply->stock < $request->quantity) {
            return back()->with('error', 'Sorry, only ' . $supply->stock . ' units of ' . $supply->name . ' are available.');
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
    // 2. الدفع وتأكيد الطلب (CHECKOUT) - (Your Complex Logic)
    // ==========================================

    public function placeOrder(Request $request)
    {
        $cartOrders = SupplyOrder::with(['supply', 'booking.farm'])
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

        foreach ($cartOrders as $item) {
            if ($item->quantity > $item->supply->stock) {
                return back()->with('error', "Sorry, {$item->supply->name} no longer has enough stock. Please adjust your cart.");
            }
        }

        $farmGovernorate = $activeBooking->farm->governorate ?? 'Amman';
        $invoiceId = 'INV-' . strtoupper(Str::random(8));
        $commissionRate = 0.10;

        try {
            DB::transaction(function () use ($cartOrders, $invoiceId, $commissionRate, $activeBooking, $farmGovernorate) {

                $driver = $this->dispatchService->assignSupplyDriver($farmGovernorate);
                $assignedDriverId = $driver->id ?? null;

                $localCompany = \App\Models\User::where('role', 'supply_company')
                    ->where('governorate', $farmGovernorate)
                    ->first();

                if (!$localCompany) {
                    throw new \Exception("Operational Error: No supply branch found for {$farmGovernorate}. Please contact support.");
                }

                foreach ($cartOrders as $item) {
                    // [STRICT ROUTING] Swap to local branch inventory
                    $localSupply = Supply::where('company_id', $localCompany->id)
                        ->where('name', $item->supply->name)
                        ->first();

                    $finalSupply = $localSupply ?: $item->supply;

                    if ($finalSupply->stock < $item->quantity) {
                        throw new \Exception("Stock issue in {$farmGovernorate} branch for " . $finalSupply->name);
                    }

                    $commissionAmount = $item->total_price * $commissionRate;
                    $netCompanyAmount = $item->total_price - $commissionAmount;

                    // Decrement stock in the LOCAL branch
                    $finalSupply->decrement('stock', $item->quantity);

                    $item->update([
                        'order_id'                => $invoiceId,
                        'supply_id'               => $finalSupply->id, // [ROUTING] Swap to local branch item
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
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function placeAll()
    {
        return $this->placeOrder(request());
    }

    // ==========================================
    // 3. تتبع الطلبات وتعديلها (ORDER TRACKING) - (Your 10-Minute Rules)
    // ==========================================

    public function myOrders()
    {
        $orders = SupplyOrder::with(['supply.company', 'booking.farm', 'driver'])
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

        $newQuantity = (int) $request->input('quantity');
        $maxQuantity = $order->supply->stock + $order->quantity;

        if ($newQuantity < 1 || $newQuantity > $maxQuantity) {
            return back()->with('error', "Quantity must be between 1 and " . $maxQuantity . ".");
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

        if (!$order->canBeModifiedOrCancelled()) {
            return back()->with('error', 'Order Modification Policy Violation: Supply orders cannot be cancelled after 10 minutes of placement.');
        }

        $order->supply->increment('stock', $order->quantity);
        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.my_orders')->with('success', 'Order cancelled successfully within the 10-minute window.');
    }
}
