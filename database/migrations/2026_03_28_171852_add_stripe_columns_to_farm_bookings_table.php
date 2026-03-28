<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('farm_bookings', function (Blueprint $table) {
            $table->string('stripe_payment_intent_id')->nullable()->after('payment_status');
            $table->string('stripe_session_id')->nullable()->after('stripe_payment_intent_id');
        });
    }

    public function down()
    {
        Schema::table('farm_bookings', function (Blueprint $table) {
            $table->dropColumn(['stripe_payment_intent_id', 'stripe_session_id']);
        });
    }
};
