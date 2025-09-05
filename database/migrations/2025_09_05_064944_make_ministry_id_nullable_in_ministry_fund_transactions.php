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
        Schema::table('ministry_fund_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('ministry_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ministry_fund_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('ministry_id')->nullable(false)->change();
        });
    }
};
