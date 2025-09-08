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
        Schema::create('priest_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('priest_id')->constrained()->onDelete('cascade');
            $table->enum('leave_type', ['pilgrimage', 'medical', 'personal', 'other']);
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason');
            $table->text('contact_info');
            $table->text('emergency_contact');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->timestamp('submitted_at');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('priest_leaves');
    }
};
