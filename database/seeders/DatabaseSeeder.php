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
        $supplyCo  = User::where('email', 'supply@mazraa.com')->first();
        $transport = User::where('email', 'transport@mazraa.com')->first();
        $drvT      = User::where('email', 'driver.t@mazraa.com')->first();
        $drvS      = User::where('email', 'driver.s@mazraa.com')->first();

        // 3. Massive Farm Generation (Core Owner Focus)
        // owner@mazraa.com gets 25 farms
        $ownerFarms = \App\Models\Farm::factory(25)->create([
            'owner_id' => $owner->id,
        ]);
        
        // Other random owners get 15 farms
        $otherFarms = \App\Models\Farm::factory(15)->create();
        $allFarms = $ownerFarms->concat($otherFarms);

        // 4. Massive Booking Generation (Core User Focus)
        // user@mazraa.com gets 120 bookings (history + future)
        $userBookings = \App\Models\FarmBooking::factory(120)->create([
            'user_id' => $user->id,
            'farm_id' => fn() => $allFarms->random()->id,
        ]);
        
        // Other random users get 80 bookings
        $otherBookings = \App\Models\FarmBooking::factory(80)->create([
            'farm_id' => fn() => $allFarms->random()->id,
        ]);
        
        $allBookings = $userBookings->concat($otherBookings);

        // 5. Supply Orders (Core Supply Co Focus)
        // supply@mazraa.com gets 120 orders
        $supplies = \App\Models\Supply::all();
        $userOrder = \App\Models\SupplyOrder::factory(120)->create([
            'user_id' => $user->id,
            'supply_id' => fn() => $supplies->random()->id,
            'booking_id' => fn() => $allBookings->random()->id,
        ]);
        
        // 6. Transport & Logistics (Core Transport Focus)
        // transport@mazraa.com manages 80 trips
        $vehicles = \App\Models\Vehicle::where('company_id', $transport->id)->get();
        $transportTrips = \App\Models\Transport::factory(80)->create([
            'company_id' => $transport->id,
            'vehicle_id' => fn() => $vehicles->random()->id,
            'driver_id'  => fn() => $drvT->id, // Binding trips to core driver
            'farm_booking_id' => fn() => $allBookings->random()->id,
        ]);

        // 7. Financial Transactions (Admin & Ledger Focus)
        // Generate 300+ transactions correlated with bookings and orders
        $allBookings->each(function ($booking) use ($admin, $owner) {
            if ($booking->payment_status === 'paid') {
                $commission = $booking->total_price * 0.10;
                $net = $booking->total_price - $commission;
                
                // Admin Commission
                \App\Models\FinancialTransaction::factory()->create([
                    'user_id' => $admin->id,
                    'reference_type' => 'farm_booking',
                    'reference_id' => $booking->id,
                    'transaction_type' => 'credit',
                    'amount' => $commission,
                    'description' => "Booking Commission #{$booking->id}",
                    'created_at' => $booking->created_at,
                ]);

                // Owner Credit
                \App\Models\FinancialTransaction::factory()->create([
                    'user_id' => $booking->farm->owner_id,
                    'reference_type' => 'farm_booking',
                    'reference_id' => $booking->id,
                    'transaction_type' => 'credit',
                    'amount' => $net,
                    'description' => "Net Booking Revenue #{$booking->id}",
                    'created_at' => $booking->created_at,
                ]);
            }
        });

        // 8. Arabic Reviews (Massive Social Proof)
        // Generate 200 reviews spread across farms
        $allFarms->each(function ($farm) use ($user) {
            \App\Models\Review::factory(5)->create([
                'reviewable_id' => $farm->id,
                'reviewable_type' => 'farm',
            ]);
        });

        // 9. System Support (Contact Us messages)
        ContactMessage::factory(35)->create();

        // 10. User Experience (Favorites)
        // user@mazraa.com favorites 15-20 farms
        $favoriteFarms = $allFarms->random(rand(15, 20));
        foreach ($favoriteFarms as $farm) {
            Favorite::create([
                'user_id' => $user->id,
                'farm_id' => $farm->id,
            ]);
        }

        // 11. Realism (Blocked Dates)
        // Block dates for some premium farms
        $premiumFarms = $allFarms->random(10);
        foreach ($premiumFarms as $farm) {
            FarmBlockedDate::factory(5)->create([
                'farm_id' => $farm->id,
            ]);
        }

        // 12. System Activity (Notifications)
        $coreAccounts = [$admin, $owner, $user, $supplyCo, $transport];
        $notificationTypes = [
            'App\Notifications\BookingConfirmed',
            'App\Notifications\PaymentReceived',
            'App\Notifications\OrderDispatched',
            'App\Notifications\NewReviewPosted',
            'App\Notifications\DriverAssigned'
        ];
        $notificationData = [
            ['title' => 'تأكيد الحجز', 'message' => 'تم تأكيد حجزك بنجاح للمزرعة المختارة.'],
            ['title' => 'تم استلام الدفعة', 'message' => 'لقد تم تسجيل مبلغ مالي جديد في محفظتك.'],
            ['title' => 'طلب قيد التوصيل', 'message' => 'يرجى العلم أن طلب التوريد الخاص بك قيد التوصيل الآن.'],
            ['title' => 'تقييم جديد', 'message' => 'قام أحد العملاء بإضافة تقييم جديد لمزرعتك.'],
            ['title' => 'تعيين سائق', 'message' => 'تم تعيين سائق لرحلتك القادمة، يمكنك التواصل معه الآن.']
        ];

        foreach ($coreAccounts as $account) {
            for ($i = 0; $i < 15; $i++) {
                $index = array_rand($notificationTypes);
                DB::table('notifications')->insert([
                    'id' => Str::uuid(),
                    'type' => $notificationTypes[$index],
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $account->id,
                    'data' => json_encode($notificationData[$index]),
                    'read_at' => rand(0, 1) ? now() : null,
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('OMNI-SEEDER COMPLETE 🟢 - 100% database coverage achieved!');
    }
}
