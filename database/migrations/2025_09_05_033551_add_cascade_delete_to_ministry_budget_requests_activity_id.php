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
            // Check if the foreign key constraint exists and drop it
            $foreignKeys = $this->getForeignKeyConstraints('ministry_budget_requests', 'activity_id');
            foreach ($foreignKeys as $foreignKey) {
                $table->dropForeign($foreignKey);
            }
            
            // Add the foreign key constraint with cascade delete
            $table->foreign('activity_id')->references('id')->on('ministry_activities')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ministry_budget_requests', function (Blueprint $table) {
            // Drop the cascade delete constraint
            $table->dropForeign(['activity_id']);
            
            // Restore the original foreign key constraint without cascade
            $table->foreign('activity_id')->references('id')->on('ministry_activities');
        });
    }

    /**
     * Get foreign key constraint names for a specific column
     */
    private function getForeignKeyConstraints($tableName, $columnName)
    {
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = ? 
            AND COLUMN_NAME = ? 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$tableName, $columnName]);

        return array_map(function ($constraint) {
            return $constraint->CONSTRAINT_NAME;
        }, $constraints);
    }
};
