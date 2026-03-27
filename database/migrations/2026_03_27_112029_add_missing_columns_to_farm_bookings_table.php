<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('farm_bookings', function (Blueprint $table) {
            // إضافة حقل صافي ربح المالك
            if (!Schema::hasColumn('farm_bookings', 'net_owner_amount')) {
                $table->decimal('net_owner_amount', 10, 2)->default(0)->after('commission_amount');
            }

            // إضافة حقول المواصلات استعداداً للميزات القادمة
            if (!Schema::hasColumn('farm_bookings', 'requires_transport')) {
                $table->boolean('requires_transport')->default(false)->after('status');
            }
            if (!Schema::hasColumn('farm_bookings', 'transport_cost')) {
                $table->decimal('transport_cost', 10, 2)->default(0)->after('requires_transport');
            }
            if (!Schema::hasColumn('farm_bookings', 'pickup_lat')) {
                $table->string('pickup_lat')->nullable()->after('transport_cost');
            }
            if (!Schema::hasColumn('farm_bookings', 'pickup_lng')) {
                $table->string('pickup_lng')->nullable()->after('pickup_lat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farm_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'net_owner_amount',
                'requires_transport',
                'transport_cost',
                'pickup_lat',
                'pickup_lng'
            ]);
        });
    }
};
