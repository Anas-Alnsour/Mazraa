<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // إضافة العمود إذا لم يكن موجود
        if (!Schema::hasColumn('transports', 'user_id')) {
            Schema::table('transports', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            });
        }

        // إضافة المفتاح الأجنبي إذا لم يكن موجود
        try {
            Schema::table('transports', function (Blueprint $table) {
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // تجاهل الخطأ إذا كان المفتاح موجود مسبقًا
        }
    }

    public function down(): void
    {
        // حذف المفتاح الأجنبي إذا موجود
        Schema::table('transports', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // حذف العمود إذا موجود
        if (Schema::hasColumn('transports', 'user_id')) {
            Schema::table('transports', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
};
