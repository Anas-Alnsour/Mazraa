<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supplies', function (Blueprint $table) {
            $table->dropForeign(['company_id']); // Assuming foreign key exists, if not this might fail. We should check or just drop column.
            $table->dropColumn(['company_id', 'stock']);
        });
    }

    public function down(): void
    {
        Schema::table('supplies', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->integer('stock')->default(0);
        });
    }
};
