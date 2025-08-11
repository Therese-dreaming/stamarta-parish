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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'admin_staff' or 'user'
            $table->string('action'); // 'booking_created', 'booking_acknowledged', 'booking_approved', 'booking_rejected', etc.
            $table->text('message');
            $table->json('data')->nullable(); // Additional data like booking_id, user_id, etc.
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Target user
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // Who triggered the action
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('cascade'); // Related booking
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'is_read']);
            $table->index(['type', 'created_at']);
            $table->index('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
