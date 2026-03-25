<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // الزبون اللي كتب التقييم
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // الأعمدة السحرية للـ Polymorphic
            $table->morphs('reviewable');

            // التقييم (من 1 لـ 5)
            $table->tinyInteger('rating')->unsigned();

            // نص التقييم
            $table->text('comment')->nullable();

            // منع اليوزر يقيم نفس الإشي مرتين
            $table->unique(['user_id', 'reviewable_id', 'reviewable_type']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
