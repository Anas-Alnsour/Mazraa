<?php

namespace Database\Seeders;

use App\Models\Transport;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\FarmBooking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransportSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Transport::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. جلب شركة المواصلات المركزية المعتمدة في الـ UserSeeder
        $company = User::where('email', 'transport@mazraa.com')->first();

        if (!$company) {
            $this->command->warn('Transport company user not found. Skipping TransportSeeder.');
            return;
        }

        // 2. جلب الحجوزات الموجودة بالسيستم لربط المواصلات بها بشكل منطقي
        // (عشان الشاشات والتقارير تطلع مترابطة 100%)
        $bookings = FarmBooking::with('farm')->get();

        // جلب جميع سائقي المواصلات المتوفرين في النظام والذين تم إنشاؤهم مسبقاً
        $drivers = User::where('role', 'transport_driver')->with('transportVehicle')->get();

        if ($bookings->isEmpty() || $drivers->isEmpty()) {
            $this->command->warn('تنبيه: يجب أن يحتوي النظام على حجوزات مزارع وسائقين لتوليد حركات المواصلات.');
            return;
        }

        $statuses = ['pending', 'assigned', 'in_progress', 'completed', 'completed', 'completed', 'cancelled'];
        $points = ['دوار السابع - عمان', 'مجمع رغدان', 'نفق الصحافة', 'دوار الثقافة - إربد', 'الزرقاء الجديدة - شارع 36', 'دوار المحافظة'];

        // 3. الدوران على الحجوزات وتوليد طلبات مواصلات مليانة بيانات (ذهاب وعودة وشفتات)
        foreach ($bookings as $index => $booking) {

            // فلترة السائقين المتواجدين في نفس محافظة المزرعة المحجوزة ليكون التوزيع منطقي
            $localDrivers = $drivers->where('governorate', $booking->farm->governorate);

            if ($localDrivers->isEmpty()) {
                $localDrivers = $drivers; // Fallback في حال عدم توفر سائق في نفس المحافظة
            }

            // تقسيم السائقين حسب الشفتات لتطبيق منطق الذهاب والعودة (التعديل رقم 9)
            $morningDrivers = $localDrivers->where('shift', 'morning');
            $eveningDrivers = $localDrivers->where('shift', 'evening');

            // تأمين سائق للذهاب وسائق للعودة بناءً على الشفت المتوفر
            $outwardDriver = $morningDrivers->isNotEmpty() ? $morningDrivers->random() : $localDrivers->random();
            $returnDriver  = $eveningDrivers->isNotEmpty() ? $eveningDrivers->random() : $localDrivers->random();

            // تحديد حالة طلب التوصيل بناءً على حالة الحجز الخاص بالمزرعة
            if ($booking->status === 'completed') {
                $status = 'completed';
            } elseif ($booking->status === 'cancelled') {
                $status = 'cancelled';
            } else {
                $status = $statuses[array_rand($statuses)];
            }

            // أوقات الرحلة مطابقة تماماً لوقت حجز المزرعة
            $arrivalTime   = Carbon::parse($booking->start_time);
            $departureTime = Carbon::parse($booking->end_time);

            // حساب تكلفة المواصلات التقديرية
            $price = rand(25, 60);

            // إنشاء سجل المواصلات المليء بالبيانات السابقة واللاحقة
            Transport::create([
                'user_id'                 => $booking->user_id,
                'farm_booking_id'         => $booking->id,
                'farm_id'                 => $booking->farm_id,
                'company_id'              => $company->id,

                // رحلة الذهاب (Outward)
                'driver_id'               => $outwardDriver->id,
                'vehicle_id'              => $outwardDriver->transport_vehicle_id,

                // رحلة العودة (Return) - التعديل رقم 9 الاحترافي
                'return_driver_id'        => $returnDriver->id,
                'return_vehicle_id'       => $returnDriver->transport_vehicle_id,

                'start_and_return_point'  => $points[array_rand($points)],
                'destination_governorate' => $booking->farm->governorate,
                'passengers'              => rand(2, $outwardDriver->transportVehicle->capacity ?? 10),
                'price'                   => $price,
                'status'                  => $status,
                'Farm_Arrival_Time'       => $arrivalTime,
                'Farm_Departure_Time'     => $departureTime,
                'created_at'              => Carbon::parse($booking->created_at),
                'updated_at'              => $status === 'completed' ? $departureTime : Carbon::parse($booking->updated_at),
            ]);

            // تحديث عداد الرحلات للسائقين لتبدو الحسابات مليئة بالإحصائيات في واجهات العرض
            if ($status === 'completed') {
                $outwardDriver->increment('trips_count');
                if ($outwardDriver->id !== $returnDriver->id) {
                    $returnDriver->increment('trips_count');
                }
            }
        }

        $this->command->info('TransportSeeder: Successfully generated ' . Transport::count() . ' detailed jobs linked with outward/return drivers and shifts.');
    }
}
