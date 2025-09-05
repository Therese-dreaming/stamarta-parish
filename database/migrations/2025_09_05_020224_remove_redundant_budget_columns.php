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
        // Remove the amount column from ministry_budget_requests table
        // since we'll use estimated_budget from the activity instead
        Schema::table('ministry_budget_requests', function (Blueprint $table) {
            $table->dropColumn('amount');
        });

        // Remove the has_budget_request column from ministry_activities table
        // since all activities should have budget requests now
        Schema::table('ministry_activities', function (Blueprint $table) {
            $table->dropColumn('has_budget_request');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the amount column to ministry_budget_requests
        Schema::table('ministry_budget_requests', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->after('activity_id');
        });

        // Add back the has_budget_request column to ministry_activities
        Schema::table('ministry_activities', function (Blueprint $table) {
            $table->boolean('has_budget_request')->default(false)->after('budget_breakdown');
        });
    }
};
