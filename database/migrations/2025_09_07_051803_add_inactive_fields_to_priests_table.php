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
        Schema::table('priests', function (Blueprint $table) {
            $table->string('inactive_reason')->nullable()->after('is_active');
            $table->timestamp('inactive_since')->nullable()->after('inactive_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('priests', function (Blueprint $table) {
            $table->dropColumn(['inactive_reason', 'inactive_since']);
        });
    }
};
