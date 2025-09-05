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
        // First, update the enum to allow more source types
        DB::statement("ALTER TABLE manual_cash_inflows MODIFY COLUMN source_type ENUM('diocese', 'donation', 'fundraising', 'event_revenue', 'membership_fee', 'sponsorship', 'other') DEFAULT 'other'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE manual_cash_inflows MODIFY COLUMN source_type ENUM('diocese', 'donation', 'other') DEFAULT 'other'");
    }
};