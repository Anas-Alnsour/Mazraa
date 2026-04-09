<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Farm;
use App\Models\FarmBooking;
use App\Models\Transport;
use Illuminate\Database\Seeder;

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
                    'farm_id' => $booking->farm_id,
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
                    'farm_id' => $booking->farm_id,
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

        $this->command->info('MASSIVE SCALE Demo Data Ready 🟢 - 6 months of historical data injected!');
    }
}
