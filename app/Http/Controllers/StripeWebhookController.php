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

                if ($type === 'farm_booking') {
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

        $adminId = User::where('role', 'admin')->value('id');

        // --- FINANCIAL SPLIT: ADMIN COMMISSION ---
        FinancialTransaction::create([
            'user_id'          => $adminId,
            'reference_type'   => 'farm_booking',
            'reference_id'     => $booking->id,
            'amount'           => $booking->commission_amount,
            'transaction_type' => 'credit',
            'description'      => "Platform commission (Booking #{$booking->id})",
        ]);

        // --- FINANCIAL SPLIT: OWNER NET PROFIT ---
        FinancialTransaction::create([
            'user_id'          => $booking->farm->owner_id,
            'reference_type'   => 'farm_booking',
            'reference_id'     => $booking->id,
            'amount'           => $booking->net_owner_amount,
            'transaction_type' => 'credit',
            'description'      => "Net payout for farm booking #{$booking->id}",
        ]);

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
        $orders = SupplyOrder::where('order_id', $orderId)
            ->where('status', 'pending_payment')
            ->with('supply.company')
            ->get();

        if ($orders->isEmpty()) return;

        $adminId = User::where('role', 'admin')->value('id');

        foreach ($orders as $order) {
            // Confirm the order into 'pending' dispatch state
            $order->update(['status' => 'pending']);

            // --- FINANCIAL SPLIT: ADMIN COMMISSION ---
            FinancialTransaction::create([
                'user_id'          => $adminId,
                'reference_type'   => 'supply_order',
                'reference_id'     => $order->id,
                'amount'           => $order->commission_amount ?? ($order->total_price * 0.05), // fallback if commission missing
                'transaction_type' => 'credit',
                'description'      => "Platform commission (Supply Order #{$order->id})",
            ]);

            // --- FINANCIAL SPLIT: SUPPLY COMPANY NET PROFIT ---
            $companyId = $order->supply->company_id ?? $order->supply->user_id; // Support both structures depending on model
            
            FinancialTransaction::create([
                'user_id'          => $companyId,
                'reference_type'   => 'supply_order',
                'reference_id'     => $order->id,
                'amount'           => $order->net_company_amount ?? ($order->total_price * 0.95), // fallback
                'transaction_type' => 'credit',
                'description'      => "Net payout for Supply Order #{$order->id}",
            ]);
        }
    }
}
