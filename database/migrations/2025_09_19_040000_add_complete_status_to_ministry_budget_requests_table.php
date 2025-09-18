<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ministry_budget_requests', function (Blueprint $table) {
            // Add completion fields
            $table->timestamp('completed_at')->nullable()->after('approved_at');
            $table->text('completion_notes')->nullable()->after('completed_at');
        });

        // Modify the enum to include 'complete' status
        DB::statement("ALTER TABLE ministry_budget_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'complete') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ministry_budget_requests', function (Blueprint $table) {
            // Drop completion fields
            $table->dropColumn(['completed_at', 'completion_notes']);
        });

        // Revert the enum to original values
        DB::statement("ALTER TABLE ministry_budget_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
