<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            if (!Schema::hasColumn('transports', 'driver_id')) {
                $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('transports', 'origin_governorate')) {
                $table->enum('origin_governorate', [
                    'Amman', 'Irbid', 'Zarqa', 'Mafraq', 'Balqa', 'Karak',
                    'Jerash', 'Madaba', 'Maan', 'Ajloun', 'Aqaba', 'Tafilah'
                ])->nullable();
            }
            if (!Schema::hasColumn('transports', 'destination_governorate')) {
                $table->enum('destination_governorate', [
                    'Amman', 'Irbid', 'Zarqa', 'Mafraq', 'Balqa', 'Karak',
                    'Jerash', 'Madaba', 'Maan', 'Ajloun', 'Aqaba', 'Tafilah'
                ])->nullable();
            }
            if (!Schema::hasColumn('transports', 'return_scheduled_at')) {
                $table->dateTime('return_scheduled_at')->nullable(); // لوقت رحلة العودة من المزرعة
            }
        });
    }

    public function down(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn(['driver_id', 'origin_governorate', 'destination_governorate', 'return_scheduled_at']);
        });
    }
};
