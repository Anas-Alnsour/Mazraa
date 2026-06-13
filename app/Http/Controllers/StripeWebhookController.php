<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Refund;
use Stripe\Stripe;
use App\Models\FarmBooking;
use App\Models\SupplyOrder;
use App\Models\Transport;
use App\Models\User;
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\NewBookingReceivedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe Webhook Error: Invalid Payload', ['exception' => $e]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe Webhook Error: Invalid Signature', ['exception' => $e]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the checkout.session.completed event
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            // Extract metadata mapping
            if (isset($session->metadata->type)) {
                $type = $session->metadata->type;

                if ($type === 'booking') {
                    $this->handleFarmBooking($session);
                } elseif ($type === 'supply_order') {
                    $this->handleSupplyOrder($session);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }

    private function handleFarmBooking($session)
    {
        $bookingId = $session->metadata->booking_id ?? null;
        if (!$bookingId) return;

        $booking = FarmBooking::with(['user', 'farm.owner'])->find($bookingId);
        if (!$booking) return;

        // 🛡️ Idempotency: Prevent double processing
        if ($booking->payment_status === 'paid') return;

        // =========================================================
        // 🚀 1. سد ثغرة التعارض: فحص الحجز المزدوج (Race Condition Guard)
        // =========================================================
        $overlappingBooking = FarmBooking::where('farm_id', $booking->farm_id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->where('id', '!=', $booking->id)
            ->where('start_time', '<', $booking->end_time)
            ->where('end_time', '>', $booking->start_time)
            ->first();

        if ($overlappingBooking) {
            // الحجز تعارض! نقوم بإلغائه وإرجاع الأموال تلقائياً
            Stripe::setApiKey(config('services.stripe.secret'));
            try {
                Refund::create(['payment_intent' => $session->payment_intent]);
                $booking->update([
                    'status' => 'cancelled',
                    'payment_status' => 'refunded'
                ]);
                Log::warning("Webhook Auto-Refund Issued: Double booking detected for Farm {$booking->farm_id}, Booking ID: {$booking->id}");
            } catch (\Exception $e) {
                Log::error("Webhook Refund Failed for Booking {$booking->id}: " . $e->getMessage());
            }
            return;
        }

        DB::beginTransaction();
        try {
            // Update booking status
            $booking->update([
                'payment_status'           => 'paid',
                'status'                   => 'confirmed',
                'stripe_session_id'        => $session->id,
                'stripe_payment_intent_id' => $session->payment_intent,
            ]);

            // =========================================================
            // 🚀 2. سد ثغرة المواصلات (تفعيل رحلة المواصلات المرتبطة)
            // =========================================================
            if ($booking->requires_transport) {
                Transport::where('farm_booking_id', $booking->id)
                    ->whereIn('status', ['pending_payment', 'pending'])
                    ->update(['status' => 'pending']); // تفعيلها لتبدأ رحلة البحث عن سائق
            }

            // --- 📩 SEND NOTIFICATIONS ---
            if ($booking->user) {
                $booking->user->notify(new BookingConfirmedNotification($booking));
            }

            if ($booking->farm && $booking->farm->owner) {
                $booking->farm->owner->notify(new NewBookingReceivedNotification($booking));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook Farm Booking Processing Error: ' . $e->getMessage());
        }
    }

    private function handleSupplyOrder($session)
    {
        $orderId = $session->metadata->order_id ?? null;
        if (!$orderId) return;

        // 🚀 3. تفكيك قنبلة الـ Loop: استخدام تحديث جماعي مباشر (Bulk Update)
        $updatedRows = SupplyOrder::where('order_id', $orderId)
            ->whereIn('status', ['pending_payment', 'pending_assignment'])
            ->update(['status' => 'pending']);

        if ($updatedRows > 0) {
            Log::info("Webhook Success: Supply Order {$orderId} marked as pending.");
        }
    }
}
