<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            // شركة المواصلات اللي استلمت الطلب
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
            $table->foreign('company_id')->references('id')->on('users')->nullOnDelete();

            // المركبة اللي رح تطلع بالرحلة
            $table->unsignedBigInteger('vehicle_id')->nullable()->after('driver_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn(['company_id', 'vehicle_id']);
        });
    }
};
