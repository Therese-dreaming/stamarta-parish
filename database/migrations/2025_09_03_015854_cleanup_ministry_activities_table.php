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
            // Check if foreign keys exist before trying to drop them
            if (Schema::hasColumn('ministry_activities', 'budget_requested_by_user_id')) {
                try {
                    $table->dropForeign(['budget_requested_by_user_id']);
                } catch (Exception $e) {
                    // Foreign key doesn't exist, continue
                }
            }
            
            if (Schema::hasColumn('ministry_activities', 'budget_approved_by_user_id')) {
                try {
                    $table->dropForeign(['budget_approved_by_user_id']);
                } catch (Exception $e) {
                    // Foreign key doesn't exist, continue
                }
            }
            
            // Drop columns if they exist
            $columnsToDrop = [];
            if (Schema::hasColumn('ministry_activities', 'budget_request_amount')) {
                $columnsToDrop[] = 'budget_request_amount';
            }
            if (Schema::hasColumn('ministry_activities', 'budget_request_details')) {
                $columnsToDrop[] = 'budget_request_details';
            }
            if (Schema::hasColumn('ministry_activities', 'budget_request_status')) {
                $columnsToDrop[] = 'budget_request_status';
            }
            if (Schema::hasColumn('ministry_activities', 'budget_requested_by_user_id')) {
                $columnsToDrop[] = 'budget_requested_by_user_id';
            }
            if (Schema::hasColumn('ministry_activities', 'budget_approved_by_user_id')) {
                $columnsToDrop[] = 'budget_approved_by_user_id';
            }
            if (Schema::hasColumn('ministry_activities', 'budget_approved_at')) {
                $columnsToDrop[] = 'budget_approved_at';
            }
            if (Schema::hasColumn('ministry_activities', 'budget_request_files')) {
                $columnsToDrop[] = 'budget_request_files';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
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
