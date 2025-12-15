<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            // إزالة الأعمدة القديمة إذا موجودة
            if (Schema::hasColumn('transports', 'start_point')) {
                $table->dropColumn('start_point');
            }
            if (Schema::hasColumn('transports', 'destination')) {
                $table->dropColumn('destination');
            }
            if (Schema::hasColumn('transports', 'departure_time')) {
                $table->dropColumn('departure_time');
            }
            if (Schema::hasColumn('transports', 'arrival_time')) {
                $table->dropColumn('arrival_time');
            }

            // التأكد من الأعمدة المطلوبة وتهيئتها
            if (!Schema::hasColumn('transports', 'Farm_Arrival_Time')) {
                $table->dateTime('Farm_Arrival_Time')->after('price');
            }
            if (!Schema::hasColumn('transports', 'Farm_Departure_Time')) {
                $table->dateTime('Farm_Departure_Time')->nullable()->after('Farm_Arrival_Time');
            }

            // ترتيب الأعمدة: MySQL لا يسمح فعليًا بإعادة ترتيب كل الأعمدة بسهولة، لكن يمكنك التأكد من وجود الأعمدة
            if (!Schema::hasColumn('transports', 'start_and_return_point')) {
                $table->string('start_and_return_point')->after('farm_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            // إعادة الأعمدة القديمة
            if (!Schema::hasColumn('transports', 'start_point')) {
                $table->string('start_point')->nullable();
            }
            if (!Schema::hasColumn('transports', 'destination')) {
                $table->string('destination')->nullable();
            }
            if (!Schema::hasColumn('transports', 'departure_time')) {
                $table->dateTime('departure_time')->nullable();
            }
            if (!Schema::hasColumn('transports', 'arrival_time')) {
                $table->dateTime('arrival_time')->nullable();
            }
        });
    }
};
