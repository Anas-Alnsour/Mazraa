<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // لو العمود driver_id مش موجود أضِفه، غير هيك لا تعمل شيء
        if (!Schema::hasColumn('transports', 'driver_id')) {
            Schema::table('transports', function (Blueprint $table) {
                $table->foreignId('driver_id')
                      ->constrained('drivers')
                      ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        // بما إنه العمود موجود أصلاً قبل هذا الميجريشن، مش هنحذفه هنا
        // ولو بدك تكون حذر:
        if (Schema::hasColumn('transports', 'driver_id')) {
            Schema::table('transports', function (Blueprint $table) {
                // لو كنت أضفته هنا فقط، اسقط الـ FK (اختياري)
                // $table->dropForeign(['driver_id']);
            });
        }
    }
};