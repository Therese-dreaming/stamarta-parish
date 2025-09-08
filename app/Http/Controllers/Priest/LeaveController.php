<?php

namespace App\Http\Controllers\Priest;

use App\Http\Controllers\Controller;
use App\Models\Priest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    public function create()
    {
        $priest = Auth::user()->priest;
        
        if (!$priest) {
            abort(404, 'Priest profile not found');
        }
        
        return view('priest.leave.create', compact('priest'));
    }

    public function getExistingLeaves()
    {
        $priest = Auth::user()->priest;
        
        if (!$priest) {
            return response()->json(['error' => 'Priest profile not found'], 404);
        }
        
        $leaves = $priest->leaves()
            ->whereIn('status', ['pending', 'approved'])
            ->select('start_date', 'end_date', 'leave_type', 'status')
            ->get()
            ->map(function ($leave) {
                return [
                    'start_date' => $leave->start_date->format('Y-m-d'),
                    'end_date' => $leave->end_date->format('Y-m-d'),
                    'leave_type' => $leave->leave_type,
                    'status' => $leave->status,
                ];
            });
        
        return response()->json($leaves);
    }
    
    public function store(Request $request)
    {
        $priest = Auth::user()->priest;
        
        if (!$priest) {
            abort(404, 'Priest profile not found');
        }
        
        $request->validate([
            'leave_type' => ['required', 'string', 'in:pilgrimage,medical,personal,other'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
            'contact_info' => ['required', 'string', 'max:500'],
            'emergency_contact' => ['required', 'string', 'max:500'],
            'confirmation' => ['required', 'accepted'],
        ]);

        // Prevent filing leave if priest is already assigned to bookings overlapping the requested range
        $start = \Carbon\Carbon::parse($request->start_date)->startOfDay();
        $end = \Carbon\Carbon::parse($request->end_date)->endOfDay();
        $hasConflictingBookings = $priest->bookings()
            ->whereIn('status', ['approved', 'completed'])
            ->whereDate('service_date', '>=', $start->toDateString())
            ->whereDate('service_date', '<=', $end->toDateString())
            ->exists();

        if ($hasConflictingBookings) {
            return back()
                ->withErrors(['start_date' => 'You have assigned bookings within the selected date range. Please resolve them before filing leave.'])
                ->withInput();
        }

        // Prevent overlapping leave applications
        $hasOverlappingLeave = $priest->leaves()
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    // New leave starts within existing leave period
                    $q->where('start_date', '<=', $start->toDateString())
                      ->where('end_date', '>=', $start->toDateString());
                })->orWhere(function ($q) use ($start, $end) {
                    // New leave ends within existing leave period
                    $q->where('start_date', '<=', $end->toDateString())
                      ->where('end_date', '>=', $end->toDateString());
                })->orWhere(function ($q) use ($start, $end) {
                    // New leave completely encompasses existing leave period
                    $q->where('start_date', '>=', $start->toDateString())
                      ->where('end_date', '<=', $end->toDateString());
                });
            })
            ->exists();

        if ($hasOverlappingLeave) {
            return back()
                ->withErrors(['start_date' => 'You already have a pending or approved leave application that overlaps with the selected date range. Please choose different dates or wait for your current application to be processed.'])
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Create leave record (pending)
            $leave = $priest->leaves()->create([
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'contact_info' => $request->contact_info,
                'emergency_contact' => $request->emergency_contact,
                'status' => 'pending',
                'submitted_at' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->route('priest.dashboard')
                ->with('success', 'Leave application submitted successfully! An admin will review your request.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => 'Failed to submit leave application. Please try again.'])
                ->withInput();
        }
    }
}
