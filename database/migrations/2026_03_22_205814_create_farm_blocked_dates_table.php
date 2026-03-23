<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('farm_blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('shift', ['morning', 'evening', 'full_day'])->default('full_day');
            $table->string('reason')->nullable();
            $table->timestamps();

            // Prevent duplicate blockings for the same date and shift on a single farm
            $table->unique(['farm_id', 'date', 'shift']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_blocked_dates');
    }
};
