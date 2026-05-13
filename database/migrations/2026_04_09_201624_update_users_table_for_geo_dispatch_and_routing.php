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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'shift')) {
                $table->enum('shift', ['morning', 'evening'])->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('shift');
            }
            if (!Schema::hasColumn('users', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            
            // Standardize governorate ENUM if it exists
            $table->enum('governorate', [
                'Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Mafraq', 'Jerash', 
                'Ajloun', 'Salt', 'Madaba', 'Karak', 'Tafilah', 'Maan'
            ])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['shift', 'latitude', 'longitude']);
        });
    }
};
