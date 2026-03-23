<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // e.g., Bus, Van, SUV
            $table->string('license_plate')->unique();
            $table->integer('capacity'); // Number of passengers
            $table->string('status')->default('available'); // available, maintenance, booked
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
