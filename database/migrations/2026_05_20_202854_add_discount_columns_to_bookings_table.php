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
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('original_price', 12, 2)->after('duration_hours')->nullable();
            $table->decimal('discount_amount', 12, 2)->after('original_price')->default(0);
            $table->boolean('xp_awarded')->after('status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['original_price', 'discount_amount', 'xp_awarded']);
        });
    }
};
