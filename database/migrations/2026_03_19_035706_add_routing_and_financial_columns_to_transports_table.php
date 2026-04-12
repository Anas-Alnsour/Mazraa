<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            $table->decimal('pickup_lat', 10, 8)->nullable();
            $table->decimal('pickup_lng', 11, 8)->nullable();

            $table->unsignedBigInteger('driver_id')->nullable()->change();
            //$table->foreign('driver_id')->references('id')->on('users')->nullOnDelete();

            $table->decimal('commission_amount', 10, 2)->nullable();
            $table->decimal('net_company_amount', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn([
                'pickup_lat',
                'pickup_lng',
                'commission_amount',
                'net_company_amount',
            ]);
        });
    }
};
