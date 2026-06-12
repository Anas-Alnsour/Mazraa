<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            // إضافة سائق ومركبة لرحلة العودة
            $table->foreignId('return_driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('return_vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            $table->dropForeign(['return_driver_id']);
            $table->dropForeign(['return_vehicle_id']);
            $table->dropColumn(['return_driver_id', 'return_vehicle_id']);
        });
    }
};
