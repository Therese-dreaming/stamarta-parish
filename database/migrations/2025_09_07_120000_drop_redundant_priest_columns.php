<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('priests', function (Blueprint $table) {
            if (Schema::hasColumn('priests', 'inactive_reason')) {
                $table->dropColumn('inactive_reason');
            }
            if (Schema::hasColumn('priests', 'inactive_since')) {
                $table->dropColumn('inactive_since');
            }
            if (Schema::hasColumn('priests', 'leave_reason')) {
                $table->dropColumn('leave_reason');
            }
            if (Schema::hasColumn('priests', 'leave_start_date')) {
                $table->dropColumn('leave_start_date');
            }
            if (Schema::hasColumn('priests', 'leave_end_date')) {
                $table->dropColumn('leave_end_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('priests', function (Blueprint $table) {
            if (!Schema::hasColumn('priests', 'inactive_reason')) {
                $table->string('inactive_reason')->nullable();
            }
            if (!Schema::hasColumn('priests', 'inactive_since')) {
                $table->timestamp('inactive_since')->nullable();
            }
            if (!Schema::hasColumn('priests', 'leave_reason')) {
                $table->text('leave_reason')->nullable();
            }
            if (!Schema::hasColumn('priests', 'leave_start_date')) {
                $table->date('leave_start_date')->nullable();
            }
            if (!Schema::hasColumn('priests', 'leave_end_date')) {
                $table->date('leave_end_date')->nullable();
            }
        });
    }
};


