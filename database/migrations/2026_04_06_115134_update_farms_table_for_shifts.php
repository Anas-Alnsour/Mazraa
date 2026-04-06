<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            $table->string('governorate')->nullable()->after('location');
            $table->string('location_link')->nullable()->after('governorate');
            $table->decimal('price_per_morning_shift', 8, 2)->default(0)->after('capacity');
            $table->decimal('price_per_evening_shift', 8, 2)->default(0)->after('price_per_morning_shift');
            $table->decimal('price_per_full_day', 8, 2)->default(0)->after('price_per_evening_shift');

            if (Schema::hasColumn('farms', 'price_per_night')) {
                $table->dropColumn('price_per_night');
            }
        });
    }

    public function down(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            $table->decimal('price_per_night', 8, 2)->default(0);
            $table->dropColumn([
                'governorate',
                'location_link',
                'price_per_morning_shift',
                'price_per_evening_shift',
                'price_per_full_day'
            ]);
        });
    }
};
