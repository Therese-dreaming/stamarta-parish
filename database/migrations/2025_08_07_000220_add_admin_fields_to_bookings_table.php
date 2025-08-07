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
            $table->timestamp('acknowledged_at')->nullable()->after('payment_submitted_at');
            $table->foreignId('acknowledged_by')->nullable()->constrained('users')->after('acknowledged_at');
            $table->timestamp('payment_hold_at')->nullable()->after('acknowledged_by');
            $table->foreignId('payment_hold_by')->nullable()->constrained('users')->after('payment_hold_at');
            $table->timestamp('verified_at')->nullable()->after('payment_hold_by');
            $table->foreignId('verified_by')->nullable()->constrained('users')->after('verified_at');
            $table->timestamp('approved_at')->nullable()->after('verified_by');
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('approved_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_by');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->after('rejected_at');
            $table->timestamp('completed_at')->nullable()->after('rejected_by');
            $table->foreignId('completed_by')->nullable()->constrained('users')->after('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['acknowledged_by', 'payment_hold_by', 'verified_by', 'approved_by', 'rejected_by', 'completed_by']);
            $table->dropColumn([
                'acknowledged_at',
                'acknowledged_by',
                'payment_hold_at',
                'payment_hold_by',
                'verified_at',
                'verified_by',
                'approved_at',
                'approved_by',
                'rejected_at',
                'rejected_by',
                'completed_at',
                'completed_by'
            ]);
        });
    }
};
