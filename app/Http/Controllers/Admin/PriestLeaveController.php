<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriestLeave;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PriestLeaveController extends Controller
{
    public function approve(Request $request, PriestLeave $leave)
    {
        $leave->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'notes' => $request->input('notes'),
        ]);

        // Update priest active status based on approved leave window
        if ($leave->priest) {
            $today = Carbon::today(config('app.timezone'));
            $leave->priest->update([
                'is_active' => ($leave->start_date->lte($today) && $leave->end_date->gte($today)) ? 0 : $leave->priest->is_active,
            ]);
        }

        return back()->with('success', 'Leave approved successfully.');
    }

    public function reject(Request $request, PriestLeave $leave)
    {
        $leave->update([
            'status' => 'rejected',
            'approved_at' => null,
            'approved_by' => null,
            'notes' => $request->input('notes'),
        ]);

        // Reset priest state. Keep is_active as-is unless no other active approved leave exists today.
        if ($leave->priest) {
            $today = Carbon::today(config('app.timezone'));
            $hasAnotherActiveLeaveToday = $leave->priest->leaves()
                ->where('id', '!=', $leave->id)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->exists();
            $leave->priest->update([
                'is_active' => $hasAnotherActiveLeaveToday ? 0 : 1,
            ]);
        }

        return back()->with('success', 'Leave rejected.');
    }

    public function complete(PriestLeave $leave)
    {
        $leave->update(['status' => 'completed']);

        if ($leave->priest) {
            $today = Carbon::today(config('app.timezone'));
            // Determine if any other approved leave covers today and set active accordingly
            $hasAnotherActiveLeaveToday = $leave->priest->leaves()
                ->where('id', '!=', $leave->id)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->exists();
            $leave->priest->update([
                'is_active' => $hasAnotherActiveLeaveToday ? 0 : 1,
            ]);
        }

        return back()->with('success', 'Leave marked as completed.');
    }
}


