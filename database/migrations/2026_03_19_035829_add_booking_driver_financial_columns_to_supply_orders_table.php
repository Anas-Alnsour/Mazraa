<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supply_orders', function (Blueprint $table) {
            $table->foreignId('booking_id')->nullable()->constrained('farm_bookings')->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('commission_amount', 10, 2)->nullable();
            $table->decimal('net_company_amount', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('supply_orders', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['driver_id']);
            $table->dropColumn([
                'booking_id',
                'driver_id',
                'commission_amount',
                'net_company_amount',
            ]);
        });
    }
};
