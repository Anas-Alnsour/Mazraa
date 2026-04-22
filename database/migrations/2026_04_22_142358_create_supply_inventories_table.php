<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supply_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_id')->constrained('supplies')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->timestamps();

            $table->unique(['supply_id', 'company_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supply_inventories');
    }
};
