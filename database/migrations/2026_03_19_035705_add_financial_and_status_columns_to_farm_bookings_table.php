<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farm_bookings', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->nullable();
            $table->decimal('commission_amount', 10, 2)->nullable();
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->decimal('net_profit', 10, 2)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('farm_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'total_price',
                'commission_amount',
                'tax_amount',
                'net_profit',
                'status',
            ]);
        });
    }
};
