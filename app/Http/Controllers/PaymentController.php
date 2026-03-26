<?php

namespace App\Http\Controllers;

use App\Models\FarmBooking;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Notifications\BookingConfirmedNotification;

class PaymentController extends Controller
{
    /**
     * Initialize Stripe Checkout Session for a Farm Booking.
     */
    public function checkout(Request $request, FarmBooking $booking)
    {
        // حماية: التأكد إن الحجز تابع لنفس الزبون وإنه مش مدفوع مسبقاً
        if ($booking->user_id !== auth()->id() || $booking->payment_status === 'paid') {
            abort(403, 'Unauthorized or already paid.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        // في الأردن العملة 3 خانات عشرية (فلس)، فبنضرب بـ 1000 زي ما طلب Stripe
        $amountInFils = (int) ($booking->total_price * 1000);

        // تنسيق التواريخ بناءً على العواميد الحقيقية تبعتك
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
                // تحديث حالة الحجز
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed'
                ]);

                // تسجيل المعاملة المالية في السيستم تبعنا (عشان السوبر أدمن يشوفها)
                FinancialTransaction::create([
                    'user_id' => $booking->user_id,
                    'farm_id' => $booking->farm_id ?? null,
                    'amount' => $booking->total_price,
                    'transaction_type' => 'payment_in',
                    'status' => 'completed',
                    'description' => 'Stripe Checkout Session: ' . $session->id

                ]);
// إرسال إشعار التأكيد للزبون
$booking->user->notify(new BookingConfirmedNotification($booking));
                return redirect()->route('explore')->with('success', 'Payment successful! Your booking is confirmed.');
            }
        } catch (\Exception $e) {
            return redirect()->route('explore')->with('error', 'Payment verification failed.');
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
}
