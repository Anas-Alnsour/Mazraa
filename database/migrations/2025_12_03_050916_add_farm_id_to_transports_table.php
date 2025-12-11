<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            // أضف العمود (nullable لو عندك بيانات قديمة؛ لو جدولك فاضي ممكن تشيل nullable)
            $table->unsignedBigInteger('farm_id')->nullable()->after('driver_id');

            // اربط بالمزارع
            $table->foreign('farm_id')
                  ->references('id')->on('farms')
                  ->cascadeOnDelete(); // أو ->nullOnDelete() حسب ما تفضّل
        });
    }

    public function down(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            $table->dropForeign(['farm_id']);
            $table->dropColumn('farm_id');
        });
    }
};