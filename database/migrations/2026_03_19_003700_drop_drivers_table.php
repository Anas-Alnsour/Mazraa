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
        Schema::dropIfExists('drivers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('transport_type', ['Car (Max 4 passengers)', 'Bus (Max 20 passengers)', 'Large Bus (Max 50 passengers)']);
            $table->timestamps();
        });
    }
};
