<?php

namespace App\Http\Controllers;

use App\Models\FarmBooking;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
// use App\Notifications\BookingConfirmedNotification; // 👈 عطلنا استدعاء الكلاس مؤقتاً
use App\Models\SupplyOrder;

class PaymentController extends Controller
{
    /**
     * عرض صفحة اختيار طريقة الدفع (Visa or CliQ)
     */
    public function selectMethod(FarmBooking $booking)
    {
        // التأكد إن الحجز لسا بانتظار الدفع وإن المستخدم هو صاحب الحجز
        if ($booking->status !== 'pending_payment' || $booking->user_id !== auth()->id()) {
            return redirect()->route('explore')->with('error', 'Invalid booking or already paid.');
        }

        // جلب بيانات المزرعة لعرض السعر والصورة في صفحة الدفع
        $farm = $booking->farm;

        return view('payment.select', compact('booking', 'farm'));
    }

    // ==========================================
    // القسم الجديد: الدفع عن طريق محفظة كليك
    // ==========================================

    /**
     * 1. توجيه المستخدم لصفحة دفع كليك المخصصة
     */
    public function processCliq(Request $request, FarmBooking $booking)
    {
        if ($booking->status !== 'pending_payment' || $booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        // توجيه لصفحة الكليك اللي صممناها
        return view('payment.cliq', compact('booking'));
    }

    /**
     * 2. استقبال بيانات الدفع من صفحة كليك وتأكيد الحجز
     */
    public function confirmCliq(Request $request, FarmBooking $booking)
    {
        if ($booking->status !== 'pending_payment' || $booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        // الفاليديشن للتأكد إن الزبون دخل الـ Alias تبعه
        $request->validate([
            'cliq_alias' => 'required|string|max:255',
        ]);

        // تحديث حالة الحجز (بانتظار التأكيد من الإدارة)
        $booking->update([
            'payment_status' => 'pending',
            'status' => 'pending_verification' // بتستنى الأدمن يشيك الحوالة
        ]);

        return redirect()->route('bookings.my_bookings')
            ->with('success', 'Payment initiated via CliQ! We are verifying your transfer from alias: ' . $request->cliq_alias);
    }

    // ==========================================
    // القسم الخاص بـ Stripe (الفيزا)
    // ==========================================

    /**
     * Initialize Stripe Checkout Session for a Farm Booking (Visa/Mastercard).
     */
    public function checkout(Request $request, FarmBooking $booking)
    {
        // تم تغيير الفحص ليقبل 'pending_payment'
        if ($booking->user_id !== auth()->id() || $booking->payment_status === 'paid' || $booking->status !== 'pending_payment') {
            abort(403, 'Unauthorized or already paid.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $amountInFils = (int) (round($booking->total_price, 2) * 1000);

        $startDate = \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d H:i');
        $endDate = \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d H:i');

        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jod',
                    'product_data' => [
                        'name' => 'Farm Booking: ' . $booking->farm->name,
                        'description' => 'From: ' . $startDate . ' | To: ' . $endDate,
                    ],
                    'unit_amount' => $amountInFils,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['booking' => $booking->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel', ['booking' => $booking->id]),
            'metadata' => [
                'booking_id' => $booking->id,
                'type' => 'farm_booking'
            ]
        ]);

        return redirect($checkoutSession->url);
    }

    /**
     * Handle Successful Payment.
     */
    public function success(Request $request, FarmBooking $booking)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = Session::retrieve($request->get('session_id'));

            if ($session->payment_status === 'paid') {
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed'
                ]);

                FinancialTransaction::create([
                    'user_id'        => $booking->user_id,
                    'reference_type' => 'farm_booking', // 👈 نوع العملية
                    'reference_id'   => $booking->id,   // 👈 رقم الحجز
                    'amount'         => $booking->total_price,
                    'transaction_type' => 'credit',     // 👈 حسب تصميم مودلك (credit/debit)
                    'description'    => 'Stripe Checkout Session: ' . $session->id,
                    // 'status'      => 'completed', // إذا جدولك ما فيه عمود status شيل هاد السطر
                ]);

                // 👈 هون عملنا التعليق (Comment) عشان يتجاوز الإشعار وما يعطيك Error
                // $booking->user->notify(new BookingConfirmedNotification($booking));

                return redirect()->route('explore')->with('success', 'Payment successful! Your booking is confirmed.');
            }
        } catch (\Exception $e) {
            // شلنا ה-dd عشان يرجعك بشكل طبيعي لو صار مشكلة حقيقية مستقبلاً
            return redirect()->route('explore')->with('error', 'Payment verification failed: ' . $e->getMessage());
        }

        return redirect()->route('explore')->with('error', 'Payment not completed.');
    }

    /**
     * Handle Cancelled Payment.
     */
    public function cancel(FarmBooking $booking)
    {
        return redirect()->route('explore')->with('error', 'Payment was cancelled. You can try again later.');
    }

    // ==========================================
    // القسم الخاص بدفع طلبات التوريد (Supply)
    // ==========================================

    /**
     * Initialize Stripe Checkout Session for a Grouped Supply Order (Invoice).
     */
    public function checkoutSupply(Request $request, $order_id)
    {
        $orders = SupplyOrder::where('order_id', $order_id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->get();

        if ($orders->isEmpty()) {
            abort(404, 'Order not found or already paid.');
        }

        $totalPrice = $orders->sum('total_price');

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $amountInFils = (int) ($totalPrice * 1000);

        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jod',
                    'product_data' => [
                        'name' => 'Supply Order Invoice: #' . $order_id,
                        'description' => 'Payment for ' . $orders->count() . ' supply items.',
                    ],
                    'unit_amount' => $amountInFils,
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

    /**
     * Handle Successful Payment for a Grouped Supply Order.
     */
    public function successSupply(Request $request, $order_id)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));

            if ($session->payment_status === 'paid') {

                $orders = SupplyOrder::where('order_id', $order_id)
                    ->where('user_id', auth()->id())
                    ->get();

                if ($orders->isEmpty()) {
                    abort(404, 'Orders not found.');
                }

                foreach ($orders as $order) {
                    $order->update([
                        'status' => 'processing',
                    ]);
                }

                $totalPrice = $orders->sum('total_price');

                \App\Models\FinancialTransaction::create([
                    'user_id' => auth()->id(),
                    'farm_id' => null,
                    'amount' => $totalPrice,
                    'transaction_type' => 'payment_in',
                    'status' => 'completed',
                    'description' => 'Stripe Supply Payment (Invoice #' . $order_id . ') - Session: ' . $session->id
                ]);

                return redirect()->route('orders.my_orders')->with('success', 'Supply payment successful! Your order is now being processed.');
            }
        } catch (\Exception $e) {
            return redirect()->route('orders.my_orders')->with('error', 'Payment verification failed.');
        }

        return redirect()->route('orders.my_orders')->with('error', 'Payment not completed.');
    }
}
