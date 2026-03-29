<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       \Illuminate\Support\Facades\DB::statement("ALTER TABLE financial_transactions MODIFY COLUMN reference_type ENUM('farm_booking', 'supply_order') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE financial_transactions MODIFY COLUMN reference_type ENUM('farm_booking') NOT NULL");
    }
};
