<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('farm_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');

            // 1. الأعمدة الخاصة بتفاصيل الحجز (اللي كانت ناقصة)
            $table->string('event_type')->nullable();

            // 2. الأعمدة المالية (استخدمت 10,3 عشان الأردن بيتعامل بـ 3 خانات عشرية - فلس)
            $table->decimal('total_price', 10, 3)->default(0);
            $table->decimal('tax_amount', 10, 3)->default(0);
            $table->decimal('commission_amount', 10, 3)->default(0);
            $table->decimal('net_owner_amount', 10, 3)->default(0);

            // 3. حالة الدفع (عشان نميز إذا اندفع بالفيزا أو لسا)
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');

            // 4. عمود حالة الحجز اللي عدلناه (السبب الرئيسي للمشكلة)
            $table->enum('status', [
                'pending_payment',       // بانتظار الدفع (الفيزا أو كليك)
                'pending_verification',  // بانتظار تأكيد الأدمن (لحوالات الكليك)
                'pending',               // معلق بشكل عام
                'confirmed',             // مؤكد (بعد الدفع)
                'cancelled',             // ملغي
                'completed'              // منتهي (بعد ما يخلص الحجز)
            ])->default('pending_payment');

            // 5. في حال كنت مفعل الـ SoftDeletes في المودل
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farm_bookings');
    }
};
