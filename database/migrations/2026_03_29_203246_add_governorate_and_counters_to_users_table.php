<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'governorate')) {
                $table->enum('governorate', [
                    'Amman', 'Irbid', 'Zarqa', 'Mafraq', 'Salt', 'Karak',
                    'Jerash', 'Madaba', 'Maan', 'Ajloun', 'Aqaba', 'Tafilah'
                ])->nullable();
            }
            if (!Schema::hasColumn('users', 'trips_count')) {
                $table->unsignedInteger('trips_count')->default(0);
            }
            if (!Schema::hasColumn('users', 'orders_count')) {
                $table->unsignedInteger('orders_count')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['governorate', 'trips_count', 'orders_count']);
        });
    }
};
