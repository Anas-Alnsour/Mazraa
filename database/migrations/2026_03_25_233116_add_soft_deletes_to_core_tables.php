<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) { $table->softDeletes(); });
        Schema::table('farms', function (Blueprint $table) { $table->softDeletes(); });
        Schema::table('supplies', function (Blueprint $table) { $table->softDeletes(); });
        Schema::table('transports', function (Blueprint $table) { $table->softDeletes(); });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('farms', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('supplies', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('transports', function (Blueprint $table) { $table->dropSoftDeletes(); });
    }
};
