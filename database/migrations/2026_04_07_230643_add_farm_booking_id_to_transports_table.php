<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add farm_booking_id to the transports table.
     * This establishes the direct link between a transport trip and the
     * FarmBooking that triggered it (used by farmBooking() relationship).
     */
    public function up(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            if (!Schema::hasColumn('transports', 'farm_booking_id')) {
                $table->foreignId('farm_booking_id')
                      ->nullable()
                      ->after('farm_id')
                      ->constrained('farm_bookings')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            if (Schema::hasColumn('transports', 'farm_booking_id')) {
                $table->dropForeign(['farm_booking_id']);
                $table->dropColumn('farm_booking_id');
            }
        });
    }
};
