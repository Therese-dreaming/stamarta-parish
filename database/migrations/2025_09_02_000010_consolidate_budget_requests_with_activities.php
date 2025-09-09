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
        // Check if the required columns exist before trying to migrate data
        if (!Schema::hasColumn('ministry_activities', 'budget_request_amount')) {
            // Skip data migration if columns don't exist yet
            return;
        }

        // Since all columns already exist, just migrate the data
        // Update existing budget requests to link to activities
        DB::statement('
            UPDATE ministry_activities ma 
            JOIN ministry_budget_requests mbr ON ma.id = mbr.activity_id 
            SET 
                ma.budget_request_amount = mbr.amount,
                ma.budget_request_details = mbr.details,
                ma.budget_request_status = mbr.status,
                ma.budget_requested_by_user_id = mbr.requested_by_user_id,
                ma.budget_approved_by_user_id = mbr.approved_by_user_id,
                ma.budget_approved_at = mbr.approved_at,
                ma.has_budget_request = 1
        ');

        // Migrate budget request files using PHP instead of MySQL JSON functions
        $activities = DB::table('ministry_activities')
            ->join('ministry_budget_requests', 'ministry_activities.id', '=', 'ministry_budget_requests.activity_id')
            ->select('ministry_activities.id as activity_id', 'ministry_budget_requests.id as budget_request_id')
            ->get();

        foreach ($activities as $activity) {
            $files = DB::table('ministry_budget_request_files')
                ->where('budget_request_id', $activity->budget_request_id)
                ->get()
                ->map(function ($file) {
                    return [
                        'id' => $file->id,
                        'path' => $file->path,
                        'original_name' => $file->original_name,
                        'uploaded_by' => $file->uploaded_by,
                        'created_at' => $file->created_at
                    ];
                })
                ->toArray();

            if (!empty($files)) {
                DB::table('ministry_activities')
                    ->where('id', $activity->activity_id)
                    ->update(['budget_request_files' => json_encode($files)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear the consolidated data
        DB::table('ministry_activities')->update([
            'budget_request_amount' => null,
            'budget_request_details' => null,
            'budget_request_status' => 'pending',
            'budget_requested_by_user_id' => null,
            'budget_approved_by_user_id' => null,
            'budget_approved_at' => null,
            'budget_request_files' => null,
            'has_budget_request' => false
        ]);
    }
}; 