<?php

namespace App\Services;

use App\Models\Transport;
use App\Models\User;
use App\Notifications\DriverAssignedNotification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TransportDispatchAction
{
    public static function dispatchDriver(Transport $transport)
    {
        if (!$transport->passengers) {
            Log::warning("TransportDispatchAction: No passengers count specified for Transport #{$transport->id}");
            return false;
        }

        $originGov = $transport->destination_governorate;
        $now = Carbon::now();

        // 🚀 حماية من الـ N+1 أثناء جلب نوع الحجز
        $transport->loadMissing('farmBooking.farm');

        // 1. تحديد الشفت للذهاب والعودة بناءً على نوع حجز المزرعة
        $bookingShift = $transport->farmBooking->event_type ?? 'full_day';

        $outwardShift = 'morning';
        $returnShift  = 'evening';

        if ($bookingShift === 'morning') {
            $outwardShift = 'morning';
            $returnShift  = 'evening';
        } elseif ($bookingShift === 'evening') {
            $outwardShift = 'evening';
            $returnShift  = 'morning';
        } elseif ($bookingShift === 'full_day') {
            $outwardShift = 'morning';
            $returnShift  = 'morning'; // لأن المبيت لثاني يوم الصبح
        }

        // 2. البحث عن السائقين المناسبين (لرحلة الذهاب ورحلة العودة)
        $outwardDriver = self::findBestDriver($originGov, $transport->passengers, $outwardShift, $now);
        $returnDriver  = self::findBestDriver($originGov, $transport->passengers, $returnShift, $now);

        $updateData = [];

        // 3. تعيين سائق الذهاب
        if ($outwardDriver) {
            $updateData['company_id'] = $outwardDriver->company_id; // يتم إسناد الرحلة لشركة سائق الذهاب
            $updateData['driver_id']  = $outwardDriver->id;
            $updateData['vehicle_id'] = $outwardDriver->transport_vehicle_id;

            if ($outwardDriver->transportVehicle) {
                $outwardDriver->transportVehicle->update(['status' => 'in_use']);
            }
            $outwardDriver->notify(new DriverAssignedNotification($transport));
        }

        // 4. تعيين سائق العودة
        if ($returnDriver) {
            $updateData['return_driver_id']  = $returnDriver->id;
            $updateData['return_vehicle_id'] = $returnDriver->transport_vehicle_id;

            if ($returnDriver->transportVehicle && (!$outwardDriver || $outwardDriver->transport_vehicle_id !== $returnDriver->transport_vehicle_id)) {
                $returnDriver->transportVehicle->update(['status' => 'in_use']);
            }

            // إرسال إشعار لسائق العودة إذا كان مختلفاً عن سائق الذهاب
            if (!$outwardDriver || $outwardDriver->id !== $returnDriver->id) {
                $returnDriver->notify(new DriverAssignedNotification($transport));
            }
        }

        // 5. حفظ البيانات في الداتابيس
        if (!empty($updateData)) {
            $updateData['status'] = 'assigned';
            $transport->update($updateData);

            Log::info("TransportDispatchAction: Drivers Assigned! Outward ({$outwardShift}): " . ($outwardDriver->name ?? 'None') . " | Return ({$returnShift}): " . ($returnDriver->name ?? 'None'));
            return true;
        }

        Log::info("TransportDispatchAction: No drivers found in {$originGov} with enough capacity.");
        return false;
    }

    /**
     * دالة مساعدة لجلب أفضل سائق بناءً على السعة والشفت
     */
    private static function findBestDriver($originGov, $passengers, $shift, $now)
    {
        // 🚀 تفكيك قنبلة الـ Index Killer
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        return User::role('transport_driver')
            ->where('governorate', $originGov)
            ->where('shift', $shift)
            ->whereNotNull('transport_vehicle_id')
            ->with('transportVehicle') // 🚀 تفكيك N+1 لاحقاً في الـ update
            ->withCount(['transportDriverJobs' => function($query) use ($startOfMonth, $endOfMonth) {
                // 🚀 استخدام whereBetween لتمكين الـ Indexing
                $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            }])
            ->whereHas('transportVehicle', function($query) use ($passengers) {
                // فلترة السعة: سعة المركبة يجب أن تكون أكبر أو تساوي الركاب
                $query->where('capacity', '>=', $passengers);
            })
            ->orderBy('transport_driver_jobs_count', 'asc') // للعدالة في توزيع الطلبات
            ->inRandomOrder() // 🚀 تفكيك قنبلة التزامن (Race Condition) عند التعادل
            ->first();
    }
}
