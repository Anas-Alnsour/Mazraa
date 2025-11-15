<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('farm_bookings', function (Blueprint $table) {
            $table->string('event_type')->nullable()->after('end_time'); // Birthday, Wedding, etc.
        });
    }

    public function down(): void
    {
        Schema::table('farm_bookings', function (Blueprint $table) {
            $table->dropColumn('event_type');
        });
    }
};
