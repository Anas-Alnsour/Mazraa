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
    Schema::create('transports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id'); // <- هنا
        $table->string('transport_type');
        $table->integer('passengers');
        $table->foreignId('driver_id');
        $table->string('start_point');
        $table->string('destination');
        $table->float('distance');
        $table->float('price');
        $table->dateTime('departure_time');
        $table->dateTime('arrival_time')->nullable();
        $table->string('status');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transports');
    }
};
