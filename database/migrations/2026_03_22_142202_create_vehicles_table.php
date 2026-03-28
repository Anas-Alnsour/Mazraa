<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->constrained('users')->onDelete('cascade'); // 👈 هي حقل الشركة
        $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null'); // 👈 وهي حقل السائق
        $table->string('type');
        $table->string('license_plate');
        $table->integer('capacity');
        $table->enum('status', ['available', 'maintenance', 'in_use'])->default('available');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
