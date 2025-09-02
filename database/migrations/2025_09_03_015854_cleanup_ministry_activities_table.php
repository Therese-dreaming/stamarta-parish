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
        // Remove the redundant budget columns from ministry_activities
        // Keep only the basic budget planning fields (estimated_budget, budget_breakdown, has_budget_request)
        Schema::table('ministry_activities', function (Blueprint $table) {
            // Drop the consolidated budget request columns that duplicate the normalized tables
            $table->dropForeign(['budget_requested_by_user_id']);
            $table->dropForeign(['budget_approved_by_user_id']);
            $table->dropColumn([
                'budget_request_amount',
                'budget_request_details', 
                'budget_request_status',
                'budget_requested_by_user_id',
                'budget_approved_by_user_id',
                'budget_approved_at',
                'budget_request_files'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the consolidated columns if needed
        Schema::table('ministry_activities', function (Blueprint $table) {
            $table->decimal('budget_request_amount', 12, 2)->nullable()->after('has_budget_request');
            $table->text('budget_request_details')->nullable()->after('budget_request_amount');
            $table->enum('budget_request_status', ['pending', 'approved', 'rejected'])->default('pending')->after('budget_request_details');
            $table->unsignedBigInteger('budget_requested_by_user_id')->nullable()->after('budget_request_status');
            $table->unsignedBigInteger('budget_approved_by_user_id')->nullable()->after('budget_requested_by_user_id');
            $table->timestamp('budget_approved_at')->nullable()->after('budget_approved_by_user_id');
            $table->json('budget_request_files')->nullable()->after('budget_approved_at');
            
            $table->foreign('budget_requested_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('budget_approved_by_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }
};
