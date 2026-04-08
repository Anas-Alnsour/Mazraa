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
        // Step 1: Expand ENUM to include 'driver' without removing old roles yet
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'farm_owner', 'supply_company', 'transport_company', 'supply_driver', 'transport_driver', 'driver') NOT NULL DEFAULT 'user'");

        // Step 2: Migrate existing legacy roles to the new unified 'driver' role
        DB::table('users')->whereIn('role', ['supply_driver', 'transport_driver'])->update(['role' => 'driver']);

        // Step 3: Contract ENUM to the final desired list (removing legacy strings)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'farm_owner', 'supply_company', 'transport_company', 'driver') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Restore legacy roles to ENUM while keeping 'driver' temporarily
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'farm_owner', 'supply_company', 'transport_company', 'supply_driver', 'transport_driver', 'driver') NOT NULL DEFAULT 'user'");

        // Step 2: Revert 'driver' users to 'user' (cannot reliably determine original driver subtype)
        DB::table('users')->where('role', 'driver')->update(['role' => 'user']);

        // Step 3: Remove 'driver' from ENUM to match pure original state
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'farm_owner', 'supply_company', 'transport_company', 'supply_driver', 'transport_driver') NOT NULL DEFAULT 'user'");
    }
};
