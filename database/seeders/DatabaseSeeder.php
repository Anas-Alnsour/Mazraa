<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Farm;
use App\Models\FarmBooking;
use App\Models\Transport;
use App\Models\ContactMessage;
use App\Models\Favorite;
use App\Models\FarmBlockedDate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Fundamental Seeders
        $this->call(UserSeeder::class);
        $this->call(SupplySeeder::class);
        $this->call(TransportSeeder::class);

        // 2. Core Accounts
        $admin     = User::where('email', 'admin@mazraa.com')->first();
        $owner     = User::where('email', 'owner@mazraa.com')->first();
        $user      = User::where('email', 'user@mazraa.com')->first();
        $transport = User::where('email', 'transport@mazraa.com')->first();

        // 💡 استخدام إيميل السائق الذكي اللي إنت عملته
        // $drvT      = User::where('email', 'driver.t.amman.morning@mazraa.com')->first();

        // 💡 جلب جميع شركات وسائقي التوريد وسائقي النقل دفعة واحدة
        $supplyCompanies  = User::where('role', 'supply_company')->get();
        // $supplyDrivers    = User::where('role', 'supply_driver')->get();
        // $transportDrivers = User::where('role', 'transport_driver')->get();

        // 3. Massive Farm Generation (Core Owner Focus)
        // $ownerFarms = \App\Models\Farm::factory(25)->create([
        //     'owner_id' => $owner->id,
        // ]);

        // $otherFarms = \App\Models\Farm::factory(15)->create();
        // $allFarms = $ownerFarms->concat($otherFarms);

        // 4. Massive Booking Generation (Core User Focus)
        // $userBookings = \App\Models\FarmBooking::factory(120)->create([
        //     'user_id' => $user->id,
        //     'farm_id' => fn() => $allFarms->random()->id,
        // ]);

        // $otherBookings = \App\Models\FarmBooking::factory(80)->create([
        //     'farm_id' => fn() => $allFarms->random()->id,
        // ]);

        // $allBookings = $userBookings->concat($otherBookings);

        // 5. Supply Orders (Core Supply Co Focus)
        // $supplies = \App\Models\Supply::all();
        // \App\Models\SupplyOrder::factory(120)->create([
        //     'user_id' => $user->id,
        //     'supply_id' => fn() => $supplies->random()->id,
        //     'booking_id' => fn() => $allBookings->random()->id,
        // ]);

        // 6. Transport & Logistics (Core Transport Focus)
        // $vehicles = \App\Models\Vehicle::where('company_id', $transport->id)->get();

        // حماية إضافية عشان ما يضرب إيرور إذا ما لقى سيارات أو سواق
        // if ($vehicles->isNotEmpty() && $drvT) {
        //     \App\Models\Transport::factory(80)->create([
        //         'company_id' => $transport->id,
        //         'vehicle_id' => fn() => $vehicles->random()->id,
        //         'driver_id'  => fn() => $drvT->id,
        //         'farm_booking_id' => fn() => $allBookings->random()->id,
        //     ]);
        // }

        // 7. Financial Transactions (Admin & Ledger Focus)
        // $allBookings->each(function ($booking) use ($admin) {
        //     if ($booking->payment_status === 'paid') {
        //         $commission = $booking->total_price * 0.10;
        //         $net = $booking->total_price - $commission;

                // Admin Commission
                // \App\Models\FinancialTransaction::factory()->create([
                //     'user_id' => $admin->id,
                //     'reference_type' => 'farm_booking',
                //     'reference_id' => $booking->id,
                //     'transaction_type' => 'credit',
                //     'amount' => $commission,
                //     'description' => "Booking Commission #{$booking->id}",
                //     'created_at' => $booking->created_at,
                // ]);

                // Owner Credit
        //         \App\Models\FinancialTransaction::factory()->create([
        //             'user_id' => $booking->farm->owner_id,
        //             'reference_type' => 'farm_booking',
        //             'reference_id' => $booking->id,
        //             'transaction_type' => 'credit',
        //             'amount' => $net,
        //             'description' => "Net Booking Revenue #{$booking->id}",
        //             'created_at' => $booking->created_at,
        //         ]);
        //     }
        // });

        // 8. Arabic Reviews
        // $allFarms->each(function ($farm) {
        //     \App\Models\Review::factory(5)->create([
        //         'reviewable_id' => $farm->id,
        //         'reviewable_type' => 'farm',
        //     ]);
        // });

        // 9. System Support
        // ContactMessage::factory(35)->create();

        // 10. Favorites
        // $favoriteFarms = $allFarms->random(rand(15, 20));
        // foreach ($favoriteFarms as $farm) {
        //     Favorite::create([
        //         'user_id' => $user->id,
        //         'farm_id' => $farm->id,
        //     ]);
        // }

        // 11. Realism (Blocked Dates)
        // Block dates for some premium farms safely without unique constraint crashes
        // $premiumFarms = $allFarms->random(10);
        // foreach ($premiumFarms as $farm) {
        //     for ($i = 1; $i <= 5; $i++) {
        //         try {
        //             FarmBlockedDate::factory()->create([
        //                 'farm_id' => $farm->id,
        //                 // 💡 توليد تواريخ متسلسلة للأمام لمنع التكرار نهائياً
        //                 'date' => now()->addDays($i * rand(2, 5))->format('Y-m-d'),
        //             ]);
        //         } catch (\Exception $e) {
        //             // Ignore rare accidental duplicates to keep the seeder running
        //             continue;
        //         }
        //     }
        // }

        // 12. System Activity (Notifications)
        // $coreAccounts = collect([$admin, $owner, $user, $transport, $drvT])
        //     ->filter()
        //     ->merge($supplyCompanies)
        //     ->merge($supplyDrivers)
        //     ->merge($transportDrivers);

        // $notificationTypes = [
        //     'App\Notifications\BookingConfirmed',
        //     'App\Notifications\PaymentReceived',
        //     'App\Notifications\OrderDispatched',
        //     'App\Notifications\NewReviewPosted',
        //     'App\Notifications\DriverAssigned'
        // ];

        // $notificationData = [
        //     ['title' => 'تأكيد الحجز', 'message' => 'تم تأكيد حجزك بنجاح للمزرعة المختارة.'],
        //     ['title' => 'تم استلام الدفعة', 'message' => 'لقد تم تسجيل مبلغ مالي جديد في محفظتك.'],
        //     ['title' => 'طلب قيد التوصيل', 'message' => 'يرجى العلم أن طلب التوريد الخاص بك قيد التوصيل الآن.'],
        //     ['title' => 'تقييم جديد', 'message' => 'قام أحد العملاء بإضافة تقييم جديد لمزرعتك.'],
        //     ['title' => 'تعيين سائق', 'message' => 'تم تعيين سائق لرحلتك القادمة، يمكنك التواصل معه الآن.']
        // ];

        // foreach ($coreAccounts as $account) {
        //     if ($account) {
        //         for ($i = 0; $i < 5; $i++) {
        //             $index = array_rand($notificationTypes);
        //             DB::table('notifications')->insert([
        //                 'id' => Str::uuid(),
        //                 'type' => $notificationTypes[$index],
        //                 'notifiable_type' => 'App\Models\User',
        //                 'notifiable_id' => $account->id,
        //                 'data' => json_encode($notificationData[$index]),
        //                 'read_at' => rand(0, 1) ? now() : null,
        //                 'created_at' => now()->subDays(rand(1, 30)),
        //                 'updated_at' => now(),
        //             ]);
        //         }
        //     }
        // }

        // $this->command->info('OMNI-SEEDER COMPLETE 🟢 - 100% database coverage achieved!');
    }
}
