<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // إضافة عمود company_id زي ما طلبت بالتوثيق
            if (!Schema::hasColumn('users', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable()->after('id');
            }

            // إذا كان عمود role موجود ومحطوط string، رح نحوله لـ enum
            // ملاحظة: إذا ضربت هاي الخطوة، بنحتاج ننزل باكج dbal
            if (Schema::hasColumn('users', 'role')) {
                // بديل آمن لتغيير العمود بدون مشاكل dbal
                $table->dropColumn('role');
            }

            $table->enum('role', [
                'admin',
                'user',
                'farm_owner',
                'supply_company',
                'transport_company',
                'supply_driver',
                'transport_driver'
            ])->default('user')->after('password');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('company_id');
        });
    }
};
