<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Permanently link a transport driver to their assigned vehicle
            $table->unsignedBigInteger('transport_vehicle_id')->nullable()->after('company_id');
            $table->foreign('transport_vehicle_id')->references('id')->on('vehicles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['transport_vehicle_id']);
            $table->dropColumn('transport_vehicle_id');
        });
    }
};
