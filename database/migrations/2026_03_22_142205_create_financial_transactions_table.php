<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
    {
        // هذا السطر رح يمسح الجدول القديم الناقص إذا كان موجود
        Schema::dropIfExists('financial_transactions');

        // وهون رح نبني الجدول بالهيكلية المعمارية الصح 100%
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('reference_type', ['farm_booking', 'transport', 'supply']);
            $table->unsignedBigInteger('reference_id');
            $table->enum('transaction_type', ['credit', 'debit']);
            $table->decimal('amount', 10, 2);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
