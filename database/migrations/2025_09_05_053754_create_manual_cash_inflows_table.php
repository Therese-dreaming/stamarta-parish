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
        Schema::create('manual_cash_inflows', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 12, 2);
            $table->string('description');
            $table->enum('source_type', ['diocese', 'donation', 'other'])->default('other');
            $table->string('source_details')->nullable(); // Additional details about the source
            $table->unsignedBigInteger('ministry_id')->nullable(); // Optional ministry association
            $table->string('reference_no')->nullable()->unique(); // Optional reference number
            $table->text('notes')->nullable(); // Additional notes
            $table->unsignedBigInteger('entered_by_user_id'); // Who entered this inflow
            $table->unsignedBigInteger('approved_by_user_id')->nullable(); // Who approved this inflow
            $table->timestamp('approved_at')->nullable(); // When it was approved
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('ministry_id')->references('id')->on('ministries')->nullOnDelete();
            $table->foreign('entered_by_user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('approved_by_user_id')->references('id')->on('users')->nullOnDelete();

            $table->index(['status', 'created_at']);
            $table->index(['ministry_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_cash_inflows');
    }
};
