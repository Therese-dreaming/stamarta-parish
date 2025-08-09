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
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->decimal('total_fee', 10, 2)->nullable();
            $table->string('payment_method')->nullable(); // 'gcash', 'metrobank'
            $table->string('payment_reference')->nullable();
            $table->text('payment_proof')->nullable(); // File path to uploaded proof
            $table->text('payment_notes')->nullable();
            $table->string('payment_status')->default('pending'); // 'pending', 'paid', 'verified', 'rejected'
            $table->timestamp('payment_submitted_at')->nullable();
            $table->timestamp('payment_verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
    }
};
