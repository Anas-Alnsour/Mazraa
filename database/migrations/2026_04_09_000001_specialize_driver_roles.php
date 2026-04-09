<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Expand ENUM to include 'supply_driver' and 'transport_driver'
        // Note: keeping 'driver' temporarily for the data migration
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'farm_owner', 'supply_company', 'transport_company', 'supply_driver', 'transport_driver', 'driver') NOT NULL DEFAULT 'user'");

        // 2. Data Migration: Determine subtype based on parent company role
        $drivers = DB::table('users')->where('role', 'driver')->get();

        foreach ($drivers as $driver) {
            $newRole = 'supply_driver'; // Default

            if ($driver->company_id) {
                $company = DB::table('users')->where('id', $driver->company_id)->first();
                if ($company && $company->role === 'transport_company') {
                    $newRole = 'transport_driver';
                }
            }

            DB::table('users')->where('id', $driver->id)->update(['role' => $newRole]);
        }

        // 3. Contract ENUM to final desired list (removing 'driver')
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'farm_owner', 'supply_company', 'transport_company', 'supply_driver', 'transport_driver') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore 'driver' role temporarily
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'farm_owner', 'supply_company', 'transport_company', 'supply_driver', 'transport_driver', 'driver') NOT NULL DEFAULT 'user'");

        // Revert specialized drivers back to generic 'driver'
        DB::table('users')->whereIn('role', ['supply_driver', 'transport_driver'])->update(['role' => 'driver']);

        // Remove specialized roles from ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'farm_owner', 'supply_company', 'transport_company', 'driver') NOT NULL DEFAULT 'user'");
    }
};
