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
            $table->string('payment_reference')->nullable()->after('payment_status');
            $table->string('payment_proof')->nullable()->after('payment_reference');
            $table->text('payment_notes')->nullable()->after('payment_proof');
            $table->timestamp('payment_submitted_at')->nullable()->after('payment_notes');
            $table->timestamp('cancelled_at')->nullable()->after('payment_submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_reference',
                'payment_proof',
                'payment_notes',
                'payment_submitted_at',
                'cancelled_at'
            ]);
        });
    }
};
