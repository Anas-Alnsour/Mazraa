<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\FarmBooking;
use App\Models\SupplyOrder;
use App\Models\FinancialTransaction;
use App\Models\User;
use App\Notifications\BookingConfirmedNotification;
use Illuminate\Support\Facades\Log;

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
            // Invalid payload
            Log::error('Stripe Webhook Error: Invalid Payload', ['exception' => $e]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Stripe Webhook Error: Invalid Signature', ['exception' => $e]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the check.session.completed event
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
        $bookingId = $session->metadata->booking_id;
        $booking = FarmBooking::find($bookingId);

        if (!$booking) return;

        // Idempotency: Prevent double processing
        if ($booking->payment_status === 'paid') return;

        // Update booking status
        $booking->update([
            'payment_status'           => 'paid',
            'status'                   => 'confirmed',
            'stripe_session_id'        => $session->id,
            'stripe_payment_intent_id' => $session->payment_intent,
        ]);

        // Financial transactions removed from here - now triggered upon booking completion by owner

        // --- 📩 SEND NOTIFICATIONS ---
        if ($booking->user) {
            $booking->user->notify(new BookingConfirmedNotification($booking));
        }
        if ($booking->farm && $booking->farm->owner_id) {
            $owner = User::find($booking->farm->owner_id);
            if ($owner) {
                $owner->notify(new BookingConfirmedNotification($booking));
            }
        }
    }

    private function handleSupplyOrder($session)
    {
        $orderId = $session->metadata->order_id;

        // Fetch all items within that specific order batch which are pending
        $orders = SupplyOrder::with(['supply', 'user', 'driver', 'booking.farm'])
            ->where('order_id', $orderId)
            ->where('status', 'pending_payment')
            ->get();

        if ($orders->isEmpty()) return;

        foreach ($orders as $order) {
            // Confirm the order into 'pending' dispatch state
            $order->update(['status' => 'pending']);

            // Financial transactions removed from here - now triggered upon delivery completion by driver
        }
    }
}
