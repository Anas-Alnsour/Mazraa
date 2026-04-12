<?php

namespace App\Http\Controllers;

use App\Models\FarmBooking;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\SupplyOrder;
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\NewBookingReceivedNotification;

class PaymentController extends Controller
{
    // =========================================================================
    // 🚜 القسم الأول: دفع حجوزات المزارع (Farm Bookings)
    // =========================================================================

    public function selectMethod(FarmBooking $booking)
    {
        if ($booking->status !== 'pending_payment' || $booking->user_id !== auth()->id()) {
            return redirect()->route('explore')->with('error', 'Invalid booking or already paid.');
        }
        $farm = $booking->farm;
        return view('payment.select', compact('booking', 'farm'));
    }

    public function processCliq(Request $request, FarmBooking $booking)
    {
        if ($booking->status !== 'pending_payment' || $booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }
        return view('payment.cliq', compact('booking'));
    }

    public function confirmCliq(Request $request, FarmBooking $booking)
    {
        if ($booking->status !== 'pending_payment' || $booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'cliq_alias' => 'required|string|max:255',
        ]);

        $booking->update([
            'payment_status' => 'pending',
            'status' => 'pending_verification'
        ]);

        return redirect()->route('bookings.my_bookings')
            ->with('success', 'Payment initiated via CliQ! We are verifying your transfer from alias: ' . $request->cliq_alias);
    }

    public function checkout(Request $request, FarmBooking $booking)
    {
        if ($booking->user_id !== auth()->id() || $booking->payment_status === 'paid' || $booking->status !== 'pending_payment') {
            abort(403, 'Unauthorized or already paid.');
        }

        // 🛡️ الحماية من الحجز المزدوج (Double Booking Guard)
        $overlappingBooking = \App\Models\FarmBooking::where('farm_id', $booking->farm_id)
            ->whereIn('payment_status', ['paid', 'confirmed'])
            ->where('id', '!=', $booking->id)
            ->where(function ($query) use ($booking) {
                $query->whereBetween('start_time', [$booking->start_time, $booking->end_time])
                      ->orWhereBetween('end_time', [$booking->start_time, $booking->end_time])
                      ->orWhere(function ($q) use ($booking) {
                          $q->where('start_time', '<=', $booking->start_time)
                            ->where('end_time', '>=', $booking->end_time);
                      });
            })
            ->first();

        if ($overlappingBooking) {
            $booking->update([
                'status' => 'cancelled',
                'payment_status' => 'failed'
            ]);

            return redirect()->route('explore')
                ->with('error', 'We are sorry! This property was just booked and paid for by another user moments ago. Your reservation attempt has been cancelled.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        // ✅ تحويل العملة إلى دولار أمريكي
        $exchangeRate = 1.41;
        $amountInCents = (int) (round($booking->total_price * $exchangeRate, 2) * 100);

        $startDate = \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d H:i');
        $endDate = \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d H:i');

        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd', // 👈 تم التعديل
                    'product_data' => [
                        'name' => 'Farm Booking: ' . $booking->farm->name,
                        'description' => 'From: ' . $startDate . ' | To: ' . $endDate . ' (Testing Mode: Converted to USD)',
                    ],
                    'unit_amount' => $amountInCents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['booking' => $booking->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel', ['booking' => $booking->id]),
            'metadata' => [
                'booking_id' => $booking->id,
                'type' => 'booking'
            ]
        ]);

        return redirect($checkoutSession->url);
    }

    public function success(Request $request, FarmBooking $booking)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        try {
            $session = Session::retrieve($request->get('session_id'));
            if ($session->payment_status === 'paid') {

                if ($booking->payment_status === 'paid') {
                    return redirect()->route('bookings.show', $booking->id)
                        ->with('success', 'Your booking is already confirmed!');
                }

                $booking->update([
                    'payment_status'           => 'paid',
                    'status'                   => 'confirmed',
                    'stripe_session_id'        => $session->id,
                    'stripe_payment_intent_id' => $session->payment_intent,
                ]);

                $booking->user->notify(new BookingConfirmedNotification($booking));
                if ($booking->farm && $booking->farm->owner_id) {
                    \App\Models\User::find($booking->farm->owner_id)->notify(new NewBookingReceivedNotification($booking));
                }

                return redirect()->route('bookings.show', $booking->id)
                    ->with('success', 'Payment successful! Your booking is confirmed.');
            }
        } catch (\Exception $e) {
            return redirect()->route('explore')
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }

        return redirect()->route('explore')->with('error', 'Payment not completed.');
    }

    public function cancel(FarmBooking $booking)
    {
        return redirect()->route('explore')->with('error', 'Payment was cancelled. You can try again later.');
    }


    // =========================================================================
    // 🛒 القسم الثاني: دفع طلبات التوريد والمشتريات (Supply Orders - Cart)
    // =========================================================================

    public function selectMethodSupply($order_id)
    {
        $orders = SupplyOrder::where('order_id', $order_id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending_payment', 'pending_assignment'])
            ->get();

        if ($orders->isEmpty()) {
            return redirect()->route('cart.view')->with('error', 'Invalid order or already paid.');
        }

        $totalPrice = $orders->sum('total_price');
        return view('payment.select_supply', compact('order_id', 'totalPrice'));
    }

    public function checkoutSupply(Request $request, $order_id)
    {
        $orders = SupplyOrder::where('order_id', $order_id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending_payment', 'pending_assignment'])
            ->get();

        if ($orders->isEmpty()) {
            abort(404, 'Order not found or already paid.');
        }

        $totalPrice = $orders->sum('total_price');
        Stripe::setApiKey(config('services.stripe.secret'));

        // ✅ تحويل العملة إلى دولار أمريكي
        $exchangeRate = 1.41;
        $amountInCents = (int) (round($totalPrice * $exchangeRate, 2) * 100);

        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd', // 👈 تم التعديل
                    'product_data' => [
                        'name' => 'Supply Order Invoice: #' . $order_id,
                        'description' => 'Payment for ' . $orders->count() . ' supply items. (Testing Mode)',
                    ],
                    'unit_amount' => $amountInCents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.supply.success', ['order_id' => $order_id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cart.view'),
            'metadata' => [
                'order_id' => $order_id,
                'type' => 'supply_order'
            ]
        ]);

        return redirect($checkoutSession->url);
    }

    public function successSupply(Request $request, $order_id)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = Session::retrieve($request->get('session_id'));

            if ($session->payment_status === 'paid') {
                $orders = SupplyOrder::where('order_id', $order_id)
                    ->where('user_id', auth()->id())
                    ->whereIn('status', ['pending_payment', 'pending_assignment'])
                    ->with('supply.company')
                    ->get();

                if ($orders->isEmpty()) {
                    return redirect()->route('supplies.my_orders')
                        ->with('success', 'Your order has already been confirmed!');
                }

                foreach ($orders as $order) {
                    $order->update(['status' => 'pending']);
                }

                // ✅ مسح سلة المشتريات بعد عملية الدفع الناجحة لمنع الدفع المزدوج والتكرار
                session()->forget('cart');

                return redirect()->route('supplies.my_orders')
                    ->with('success', 'Supply payment successful! Your order is now being processed.');
            }
        } catch (\Exception $e) {
            return redirect()->route('supplies.my_orders')
                ->with('error', 'Error: ' . $e->getMessage());
        }

        return redirect()->route('supplies.my_orders')->with('error', 'Payment not completed.');
    }

    public function processCliqSupply(Request $request, $order_id)
    {
        $orders = SupplyOrder::where('order_id', $order_id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending_payment', 'pending_assignment'])
            ->get();

        if ($orders->isEmpty()) {
            abort(403, 'Unauthorized or already paid.');
        }

        $totalPrice = $orders->sum('total_price');
        return view('payment.supply_cliq', compact('order_id', 'totalPrice'));
    }

    public function confirmCliqSupply(Request $request, $order_id)
    {
        $request->validate([
            'cliq_alias' => 'required|string|max:255',
        ]);

        $orders = SupplyOrder::where('order_id', $order_id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending_payment', 'pending_assignment'])
            ->get();

        foreach ($orders as $order) {
            $order->update([
                'status' => 'pending_verification'
            ]);
        }

        // ✅ مسح سلة المشتريات للـ CliQ أيضاً
        session()->forget('cart');

        return redirect()->route('supplies.my_orders')
            ->with('success', 'Market payment initiated via CliQ! We are verifying your transfer from alias: ' . $request->cliq_alias);
    }
}
