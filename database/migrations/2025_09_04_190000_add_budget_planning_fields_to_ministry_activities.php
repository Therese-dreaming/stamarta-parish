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
        Schema::table('ministry_activities', function (Blueprint $table) {
            // Ensure planning fields exist for activity budget planning UI
            if (!Schema::hasColumn('ministry_activities', 'has_budget_request')) {
                $table->boolean('has_budget_request')->default(false)->after('is_public');
            }
            if (!Schema::hasColumn('ministry_activities', 'estimated_budget')) {
                $table->decimal('estimated_budget', 12, 2)->nullable()->after('has_budget_request');
            }
            if (!Schema::hasColumn('ministry_activities', 'budget_breakdown')) {
                $table->text('budget_breakdown')->nullable()->after('estimated_budget');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ministry_activities', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('ministry_activities', 'budget_breakdown')) {
                $table->dropColumn('budget_breakdown');
            }
            if (Schema::hasColumn('ministry_activities', 'estimated_budget')) {
                $table->dropColumn('estimated_budget');
            }
            // Keep has_budget_request if other parts depend on it; drop only if we created it
            if (Schema::hasColumn('ministry_activities', 'has_budget_request')) {
                $table->dropColumn('has_budget_request');
            }
        });
    }
};


