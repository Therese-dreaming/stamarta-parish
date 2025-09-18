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
            $table->string('liquidation_report_path')->nullable()->after('budget_breakdown');
            $table->timestamp('liquidation_submitted_at')->nullable()->after('liquidation_report_path');
            $table->text('liquidation_notes')->nullable()->after('liquidation_submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ministry_activities', function (Blueprint $table) {
            $table->dropColumn(['liquidation_report_path', 'liquidation_submitted_at', 'liquidation_notes']);
        });
    }
};
