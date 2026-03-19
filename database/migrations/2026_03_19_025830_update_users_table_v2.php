<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void {
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['admin', 'user', 'farm_owner', 'supply_company', 'transport_company', 'supply_driver', 'transport_driver'])->default('user')->change();
        $table->unsignedBigInteger('company_id')->nullable()->after('password');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
