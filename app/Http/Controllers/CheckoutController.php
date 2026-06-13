<?php

namespace App\Http\Controllers;

use App\Models\Farm;
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

            // جلب المزرعة مرة واحدة لمنع استعلامات N+1 ولحساب النسبة الدقيقة
            $farm = Farm::findOrFail($validated['farm_id']);

            $adminId = User::where('role', 'admin')->value('id');
            if (!$adminId) {
                throw new Exception("Admin account not found for financial distribution.");
            }

            // 1. حسابات المزرعة وتقسيم الأرباح
            $adminCommissionRate = $farm->commission_rate ? ($farm->commission_rate / 100) : 0.15;
            $adminCommission = $validated['farm_price'] * $adminCommissionRate;
            $ownerNetProfit = $validated['farm_price'] - $adminCommission;

            // 2. إنشاء الحجز مع كافة الحقول لتغذية لوحات التحكم بشكل صحيح
            $booking = FarmBooking::create([
                'user_id' => $user->id,
                'farm_id' => $farm->id,
                'start_time' => $validated['start_date'], // متوافقة مع المايجريشن
                'end_time' => $validated['end_date'],
                'total_price' => $validated['farm_price'],
                'commission_amount' => $adminCommission,
                'net_owner_amount' => $ownerNetProfit,
                'status' => 'confirmed',
                'requires_transport' => $validated['requires_transport'] ?? false,
                'transport_cost' => $validated['requires_transport'] ? ($validated['transport_price'] ?? 0) : 0,
            ]);

            // 3. إدخال المعاملات المالية للمزرعة باستعلام واحد (Bulk Insert)
            DB::table('financial_transactions')->insert([
                [
                    'user_id' => $adminId,
                    'amount' => $adminCommission,
                    'transaction_type' => 'credit',
                    'reference_type' => 'farm_booking',
                    'reference_id' => $booking->id,
                    'description' => "Commission for Farm Booking #{$booking->id}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'user_id' => $farm->owner_id,
                    'amount' => $ownerNetProfit,
                    'transaction_type' => 'credit',
                    'reference_type' => 'farm_booking',
                    'reference_id' => $booking->id,
                    'description' => "Net Profit for Farm Booking #{$booking->id}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            // 4. مسار المواصلات (إصلاح مشكلة الـ 100% للآدمن وتوزيع الحقوق)
            if (!empty($validated['requires_transport'])) {
                $transportPrice = $validated['transport_price'] ?? 0;

                // التوزيع العادل (10% للمنصة، 90% لشركة النقل)
                $transportAdminCommission = $transportPrice * 0.10;
                $transportNetCompany = $transportPrice - $transportAdminCommission;

                $assignedDriver = $this->dispatchService->assignTransportDriver($validated['customer_governorate']);

                $transport = Transport::create([
                    'user_id' => $user->id,
                    'driver_id' => $assignedDriver->id ?? null,
                    'company_id' => $assignedDriver->company_id ?? null,
                    'farm_booking_id' => $booking->id,
                    'origin_governorate' => $validated['customer_governorate'],
                    'destination_governorate' => $validated['farm_governorate'],
                    'status' => $assignedDriver ? 'pending' : 'pending_assignment',
                    'price' => $transportPrice,
                    'commission_amount' => $transportAdminCommission,
                    'net_company_amount' => $transportNetCompany,
                    'Farm_Arrival_Time' => $validated['start_date'],
                    'Farm_Departure_Time' => $validated['end_date'],
                ]);

                // تجهيز مصفوفة الإدخال المالي للمواصلات
                $transportFinancials = [
                    [
                        'user_id' => $adminId,
                        'amount' => $transportAdminCommission,
                        'transaction_type' => 'credit',
                        'reference_type' => 'transport',
                        'reference_id' => $transport->id,
                        'description' => "10% Platform Commission for Transport #{$transport->id}",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ];

                // إذا تم تعيين شركة نقل، نعطيها أرباحها (90%)
                if (isset($assignedDriver->company_id)) {
                    $transportFinancials[] = [
                        'user_id' => $assignedDriver->company_id,
                        'amount' => $transportNetCompany,
                        'transaction_type' => 'credit',
                        'reference_type' => 'transport',
                        'reference_id' => $transport->id,
                        'description' => "90% Net Profit for Transport #{$transport->id}",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // إدخال المعاملات المالية للمواصلات باستعلام واحد
                DB::table('financial_transactions')->insert($transportFinancials);
            }

            DB::commit();

            return redirect()->route('bookings.my_bookings')->with('success', 'Booking confirmed and dispatched successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
