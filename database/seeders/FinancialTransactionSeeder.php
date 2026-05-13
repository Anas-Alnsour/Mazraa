<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\FarmBooking;
use App\Models\SupplyOrder;
use Carbon\Carbon;

class FinancialTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('financial_transactions')->truncate(); // clear old dummy data

        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            echo "No admin found to seed financials.\n";
            return;
        }

        $owners = User::where('role', 'farm_owner')->get();
        $supplyCompanies = User::where('role', 'supply_company')->get();

        if ($owners->isEmpty() || $supplyCompanies->isEmpty()) {
            echo "Please ensure you have at least one farm_owner and one supply_company.\n";
            return;
        }

        $now = Carbon::now();
        $transactions = [];

        // 1. Seed Farm Booking Revenues (Credits to Admin for Commission, Credits to Owners for Net Profit)
        // for ($i = 1; $i <= 10; $i++) {
        //     $owner = $owners->random();
        //     $grossAmount = rand(200, 1500);
        //     $commission = $grossAmount * 0.10; // 10%
        //     $netAmount = $grossAmount - $commission;
            
            // Admin commission (Credit)
            // $transactions[] = [
            //     'user_id' => $admin->id,
            //     'reference_type' => 'farm_booking', // Enum constraint
            //     'reference_id' => $i,
            //     'transaction_type' => 'credit',
            //     'amount' => $commission,
            //     'description' => "Platform commission (Booking #{$i})",
            //     'created_at' => $now->copy()->subDays(rand(1, 30)),
            //     'updated_at' => $now,
            // ];

            // Owner profit (Credit)
        //     $transactions[] = [
        //         'user_id' => $owner->id,
        //         'reference_type' => 'farm_booking', // Enum constraint
        //         'reference_id' => $i,
        //         'transaction_type' => 'credit',
        //         'amount' => $netAmount,
        //         'description' => "Net payout for farm booking #{$i}",
        //         'created_at' => end($transactions)['created_at'],
        //         'updated_at' => $now,
        //     ];
        // }

        // 2. Seed Supply Order Revenues (Credits to Admin, Credits to Supply Company)
        // for ($j = 101; $j <= 110; $j++) {
        //     $company = $supplyCompanies->random();
        //     $grossAmount = rand(50, 500);
        //     $commission = $grossAmount * 0.05; // 5%
        //     $netAmount = $grossAmount - $commission;
            
            // Admin commission
            // $transactions[] = [
            //     'user_id' => $admin->id,
            //     'reference_type' => 'supply_order', // Enum constraint
            //     'reference_id' => $j,
            //     'transaction_type' => 'credit',
            //     'amount' => $commission,
            //     'description' => "Platform commission (Supply Order #{$j})",
            //     'created_at' => $now->copy()->subDays(rand(1, 20)),
            //     'updated_at' => $now,
            // ];

            // Supply company
        //     $transactions[] = [
        //         'user_id' => $company->id,
        //         'reference_type' => 'supply_order', // Enum constraint
        //         'reference_id' => $j,
        //         'transaction_type' => 'credit',
        //         'amount' => $netAmount,
        //         'description' => "Net payout for Supply Order #{$j}",
        //         'created_at' => end($transactions)['created_at'],
        //         'updated_at' => $now,
        //     ];
        // }

        // 3. Seed Manual Payouts from Admin to Vendors (Debit against vendor balance)
        // Note: The schema might strictly enforce ENUM for reference_type, but let's try 'manual_payout' 
        // as SuperAdminController uses it, or fallback to 'supply_order' if 'manual_payout' isn't registered in ENUM yet.
        // We will just use 'farm_booking' reference_type but word it as a payout to bypass strict schema limits temporarily.
    //     foreach ($owners->take(2) as $owner) {
    //         $payoutAmount = rand(500, 1000);
    //         $transactions[] = [
    //             'user_id' => $owner->id,
    //             'reference_type' => 'farm_booking', 
    //             'reference_id' => rand(999, 9999), // arbitrary ID for bank transfer
    //             'transaction_type' => 'debit',
    //             'amount' => $payoutAmount,
    //             'description' => "Bank Transfer Ref: TR-" . rand(10000, 99999),
    //             'created_at' => $now->copy()->subDays(rand(1, 5)),
    //             'updated_at' => $now,
    //         ];
    //     }

    //     foreach ($supplyCompanies->take(1) as $company) {
    //         $payoutAmount = rand(200, 500);
    //         $transactions[] = [
    //             'user_id' => $company->id,
    //             'reference_type' => 'supply_order', 
    //             'reference_id' => rand(999, 9999),
    //             'transaction_type' => 'debit',
    //             'amount' => $payoutAmount,
    //             'description' => "Bank Transfer Ref: TR-" . rand(10000, 99999),
    //             'created_at' => $now->copy()->subDays(rand(1, 5)),
    //             'updated_at' => $now,
    //         ];
    //     }

    //     DB::table('financial_transactions')->insert($transactions);
    //     echo "Successfully seeded " . count($transactions) . " financial transactions.\n";
     }
}
