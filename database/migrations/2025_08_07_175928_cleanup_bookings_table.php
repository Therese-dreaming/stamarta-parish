<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Clean up bookings table by removing old columns
        Schema::table('bookings', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['acknowledged_by']);
            $table->dropForeign(['payment_hold_by']);
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropForeign(['completed_by']);
            
            // Remove old columns
            $table->dropColumn([
                'total_fee',
                'payment_status',
                'payment_reference',
                'payment_proof',
                'payment_notes',
                'payment_submitted_at',
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
                'completed_by',
                'cancelled_at',
                'notes',
                'deleted_at'
            ]);
        });
    }

    public function down()
    {
        // Restore old columns to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('total_fee', 10, 2)->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('payment_reference')->nullable();
            $table->string('payment_proof')->nullable();
            $table->text('payment_notes')->nullable();
            $table->timestamp('payment_submitted_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->foreignId('acknowledged_by')->nullable()->constrained('users');
            $table->timestamp('payment_hold_at')->nullable();
            $table->foreignId('payment_hold_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users');
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
        });
    }
};
