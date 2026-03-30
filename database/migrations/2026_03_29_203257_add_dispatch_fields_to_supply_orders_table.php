<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supply_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('supply_orders', 'driver_id')) {
                $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('supply_orders', 'destination_governorate')) {
                $table->enum('destination_governorate', [
                    'Amman', 'Irbid', 'Zarqa', 'Mafraq', 'Balqa', 'Karak',
                    'Jerash', 'Madaba', 'Maan', 'Ajloun', 'Aqaba', 'Tafilah'
                ])->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('supply_orders', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn(['driver_id', 'destination_governorate']);
        });
    }
};
