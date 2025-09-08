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
            $table->integer('years_of_service')->nullable()->after('ordination_date');
            $table->enum('leave_status', ['active', 'on_leave', 'pilgrimage', 'sabbatical', 'retired'])->default('active')->after('is_active');
            $table->text('leave_reason')->nullable()->after('leave_status');
            $table->date('leave_start_date')->nullable()->after('leave_reason');
            $table->date('leave_end_date')->nullable()->after('leave_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('priests', function (Blueprint $table) {
            $table->dropColumn([
                'years_of_service',
                'leave_status',
                'leave_reason',
                'leave_start_date',
                'leave_end_date'
            ]);
        });
    }
};
