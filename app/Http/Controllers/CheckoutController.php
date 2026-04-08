<?php

namespace App\Http\Controllers;

use App\Models\FarmBooking;
use App\Models\Transport;
use App\Models\User;
use App\Services\DispatchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutController extends Controller
{
    protected $dispatchService;

    public function __construct(DispatchService $dispatchService)
    {
        $this->dispatchService = $dispatchService;
    }

    /**
     * Process a Farm Booking checkout, assign transport (if requested),
     * and split the profit into the financial_transactions table.
     */
    public function processFarmBooking(Request $request)
    {
        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'requires_transport' => 'boolean',
            'customer_governorate' => 'required_if:requires_transport,true|string',
            'farm_governorate' => 'required|string',
            'farm_price' => 'required|numeric|min:0',
            'transport_price' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $user = auth()->user();

            // 1. Create Farm Booking
            $booking = FarmBooking::create([
                'user_id' => $user->id,
                'farm_id' => $validated['farm_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'total_price' => $validated['farm_price'],
                'status' => 'confirmed', // Assuming immediate confirmation for this example
            ]);

            // 2. Profit Splitting for Farm Booking
            $adminCommissionRate = 0.15; // Example: 15% commission
            $adminCommission = $validated['farm_price'] * $adminCommissionRate;
            $ownerNetProfit = $validated['farm_price'] - $adminCommission;

            $farm = $booking->farm;
            $adminId = User::where('role', 'admin')->value('id'); // Get super admin ID

            if (!$adminId) {
                throw new Exception("Admin account not found for financial distribution.");
            }

            // Insert Admin Commission (Credit)
            DB::table('financial_transactions')->insert([
                'user_id' => $adminId,
                'amount' => $adminCommission,
                'transaction_type' => 'credit',
                'reference_type' => 'farm_booking',
                'reference_id' => $booking->id,
                'description' => "Commission for Farm Booking #{$booking->id}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert Owner Net Profit (Credit)
            DB::table('financial_transactions')->insert([
                'user_id' => $farm->owner_id,
                'amount' => $ownerNetProfit,
                'transaction_type' => 'credit',
                'reference_type' => 'farm_booking',
                'reference_id' => $booking->id,
                'description' => "Net Profit for Farm Booking #{$booking->id}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Transport Logic (Round-Trip Dispatching)
            if (isset($validated['requires_transport']) && $validated['requires_transport']) {
                $transportPrice = $validated['transport_price'] ?? 0;

                // Dispatch Driver based on CUSTOMER ORIGIN
                $assignedDriver = $this->dispatchService->assignTransportDriver($validated['customer_governorate']);

                $transport = Transport::create([
                    'user_id' => $user->id,
                    'driver_id' => $assignedDriver->id ?? null, // Graceful: Null if no driver found
                    'company_id' => $assignedDriver->company_id ?? null,
                    'farm_booking_id' => $booking->id,
                    'origin_governorate' => $validated['customer_governorate'],
                    'destination_governorate' => $validated['farm_governorate'],
                    'status' => $assignedDriver ? 'pending' : 'pending_assignment', // New status for admin attention
                    'total_price' => $transportPrice,
                    'scheduled_at' => $validated['start_date'],
                    'return_scheduled_at' => $validated['end_date'],
                ]);

                // Transport Profit goes 100% to Admin
                DB::table('financial_transactions')->insert([
                    'user_id' => $adminId,
                    'amount' => $transportPrice,
                    'transaction_type' => 'credit',
                    'reference_type' => 'transport',
                    'reference_id' => $transport->id,
                    'description' => "100% Transport Profit for Booking #{$booking->id}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('bookings.my_bookings')->with('success', 'Booking confirmed and dispatched successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
