<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farm_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('farm_bookings', 'requires_transport')) {
                $table->boolean('requires_transport')->default(false)->after('status');
                $table->decimal('transport_cost', 10, 3)->default(0)->after('requires_transport');
                $table->string('pickup_lat')->nullable()->after('transport_cost');
                $table->string('pickup_lng')->nullable()->after('pickup_lat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('farm_bookings', function (Blueprint $table) {
            $table->dropColumn(['requires_transport', 'transport_cost', 'pickup_lat', 'pickup_lng']);
        });
    }
};
