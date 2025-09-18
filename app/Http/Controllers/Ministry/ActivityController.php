<?php

namespace App\Http\Controllers\Ministry;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Models\MinistryActivity;
use App\Models\MinistryBudgetRequest;
use App\Models\ParochialActivity;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityController extends Controller
{
    private function getHeadMinistryOrAbort(): Ministry
    {
        $user = auth()->user();
        $ministry = Ministry::where('head_user_id', $user->id)->first();
        if (!$ministry) {
            abort(403);
        }
        return $ministry;
    }

    public function index(Request $request)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        
        $query = $ministry->activities()
            ->with(['pendingBudgetRequest', 'approvedBudgetRequest']);

        // Filter by type (public/internal)
        if ($request->filled('type')) {
            if ($request->type === 'public') {
                $query->where('is_public', true);
            } elseif ($request->type === 'internal') {
                $query->where('is_public', false);
            }
        }

        $activities = $query->orderBy('start_at', 'desc')->paginate(20);
        
        // Get total counts for statistics
        $totalActivities = $ministry->activities()->count();
        $publicActivities = $ministry->activities()->where('is_public', true)->count();
        $internalActivities = $ministry->activities()->where('is_public', false)->count();
        
        return view('ministry.activities.index', compact('ministry', 'activities', 'totalActivities', 'publicActivities', 'internalActivities'));
    }

    public function create()
    {
        $ministry = $this->getHeadMinistryOrAbort();
        return view('ministry.activities.create', compact('ministry'));
    }

    public function store(Request $request)
    {
        $ministry = $this->getHeadMinistryOrAbort();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'location' => 'nullable|string|max:255',
            'is_public' => 'sometimes|boolean',
            'budget_purpose' => 'required|string|max:255',
            'budget_details' => 'nullable|string',
            'budget_items' => 'required|array|min:1',
            'budget_items.*.name' => 'required|string|max:255',
            'budget_items.*.amount' => 'required|numeric|min:0',
            'budget_files' => 'nullable|array',
            'budget_files.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ]);

        // Check for conflicts directly in the store method
        $conflicts = $this->checkConflictsDirectly($data['start_at'], $data['end_at'] ?? null, $data['location'] ?? null, false, null);

        // If there are conflicts and user hasn't confirmed to proceed, show warning
        if ($conflicts['has_conflicts'] && !$request->has('confirm_conflicts')) {
            return back()->withInput()->with('conflicts', $conflicts)->with('warning', 
                'Schedule conflicts detected: ' . $this->getConflictSummary($conflicts) . 
                '. Please review and confirm to proceed.');
        }

        // Calculate total budget from items
        $totalBudget = 0;
        $budgetBreakdown = [];
        foreach ($data['budget_items'] as $item) {
            $totalBudget += $item['amount'];
            $budgetBreakdown[$item['name']] = $item['amount'];
        }

        $data['is_all_day'] = (bool)($data['is_all_day'] ?? false);
        $data['is_public'] = (bool)($data['is_public'] ?? false);
        $data['estimated_budget'] = $totalBudget;
        $data['budget_breakdown'] = json_encode($budgetBreakdown);

        // Create ministry activity (always required now)
        $activity = $ministry->activities()->create($data);

        // Create budget request (always required now)
        $budgetRequest = MinistryBudgetRequest::create([
            'ministry_id' => $ministry->id,
            'activity_id' => $activity->id,
            'purpose' => $data['budget_purpose'],
            'details' => $data['budget_details'] ?? $data['budget_breakdown'],
            'status' => 'pending',
            'requested_by_user_id' => auth()->id(),
        ]);

        // Handle file uploads
        if ($request->hasFile('budget_files')) {
            foreach ($request->file('budget_files') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('budget_requests', $filename, 'public');
                
                $budgetRequest->files()->create([
                    'path' => 'budget_requests/' . $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }

        $message = 'Activity created successfully with ministry activity submitted.';
        if ($conflicts['has_conflicts']) {
            $message .= ' Note: This activity conflicts with existing schedules.';
        }

        return redirect()->route('ministry.activities.index')->with('success', $message);
    }

    public function edit(MinistryActivity $activity)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        if ($activity->ministry_id !== $ministry->id) {
            abort(403);
        }
        

        
        return view('ministry.activities.edit', compact('ministry', 'activity'));
    }

    public function show(MinistryActivity $activity)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        if ($activity->ministry_id !== $ministry->id) {
            abort(403);
        }
        return view('ministry.activities.show', compact('ministry', 'activity'));
    }

    public function update(Request $request, MinistryActivity $activity)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        if ($activity->ministry_id !== $ministry->id) {
            abort(403);
        }
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_all_day' => 'sometimes|boolean',
            'location' => 'nullable|string|max:255',
            'is_public' => 'sometimes|boolean',
            'budget_purpose' => 'nullable|string|max:255',
            'budget_details' => 'nullable|string',
            'budget_items' => 'nullable|array',
            'budget_items.*.name' => 'nullable|string|max:255',
            'budget_items.*.amount' => 'nullable|numeric|min:0',
        ]);

        // Check for conflicts (excluding current activity)
        $conflicts = $this->checkConflictsDirectly($data['start_at'], $data['end_at'] ?? null, $data['location'] ?? null, false, $activity->id);

        // If there are conflicts and user hasn't confirmed to proceed, show warning
        if ($conflicts['has_conflicts'] && !$request->has('confirm_conflicts')) {
            return back()->withInput()->with('conflicts', $conflicts)->with('warning', 
                'Schedule conflicts detected: ' . $this->getConflictSummary($conflicts) . 
                '. Please review and confirm to proceed.');
        }

        $data['is_all_day'] = (bool)($data['is_all_day'] ?? false);
        $data['is_public'] = (bool)($data['is_public'] ?? false);

        // Process budget information
        if (isset($data['budget_items']) && is_array($data['budget_items'])) {
            $totalBudget = 0;
            $budgetBreakdown = [];
            
            foreach ($data['budget_items'] as $item) {
                if (!empty($item['name']) && isset($item['amount']) && $item['amount'] > 0) {
                    $totalBudget += $item['amount'];
                    $budgetBreakdown[$item['name']] = $item['amount'];
                }
            }
            
            $data['estimated_budget'] = $totalBudget;
            $data['budget_breakdown'] = json_encode($budgetBreakdown);
        }

        $activity->update($data);

        $message = 'Activity updated successfully.';
        if ($conflicts['has_conflicts']) {
            $message .= ' Note: This activity conflicts with existing schedules.';
        }

        return redirect()->route('ministry.activities.index')->with('success', $message);
    }

    public function destroy(MinistryActivity $activity)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        if ($activity->ministry_id !== $ministry->id) {
            abort(403);
        }

        try {
            // Log the deletion for debugging
            \Log::info('Deleting ministry activity', [
                'activity_id' => $activity->id,
                'title' => $activity->title,
                'ministry_id' => $activity->ministry_id,
                'deleted_by' => auth()->id()
            ]);

            // Delete the activity (this will trigger the model events to delete budget requests and files)
            $activity->delete();

            return redirect()->route('ministry.activities.index')->with('success', 'Activity deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting ministry activity', [
                'activity_id' => $activity->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('ministry.activities.index')->with('error', 'Error deleting activity. Please try again.');
        }
    }



    public function checkConflicts(Request $request)
    {
        try {
            // Simple validation
            $data = $request->validate([
                'start_at' => 'required|date',
                'end_at' => 'nullable|date',
                'location' => 'nullable|string',
                'is_all_day' => 'nullable|boolean',
                'exclude_activity_id' => 'nullable|integer', // Add this to exclude current activity
            ]);

            // Additional validation for end_at if provided
            if ($data['end_at']) {
                $startAt = Carbon::parse($data['start_at']);
                $endAt = Carbon::parse($data['end_at']);
                
                if ($endAt->lt($startAt)) {
                    return response()->json([
                        'has_conflicts' => false,
                        'summary' => 'Invalid time range',
                        'error' => 'End time must be after start time'
                    ], 400);
                }
            }

            // Debug logging for received data
            \Log::info('Conflict check request received:', [
                'raw_request' => $request->all(),
                'validated_data' => $data,
                'is_all_day' => $data['is_all_day'] ?? false,
                'is_all_day_type' => gettype($data['is_all_day'] ?? null),
                'exclude_activity_id' => $data['exclude_activity_id'] ?? null
            ]);

            // Use the actual conflict checking logic with exclude ID
            $conflicts = $this->checkConflictsDirectly(
                $data['start_at'], 
                $data['end_at'], 
                $data['location'], 
                $data['is_all_day'] ?? false,
                $data['exclude_activity_id'] ?? null
            );

            return response()->json([
                'has_conflicts' => $conflicts['has_conflicts'],
                'summary' => $this->getConflictSummary($conflicts),
                'conflicts' => $conflicts
            ]);
        } catch (\Exception $e) {
            \Log::error('Conflict check error: ' . $e->getMessage());
            return response()->json([
                'has_conflicts' => false,
                'summary' => 'Error checking conflicts',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function testConflicts()
    {
        try {
            // Test basic database access
            $bookingCount = Booking::count();
            $serviceCount = \App\Models\Service::count();
            
            // Test specific date conflict checking
            $testDate = '2025-08-30';
            $testStartTime = '09:00';
            $testEndTime = '11:00';
            
            $startDateTime = Carbon::parse($testDate . ' ' . $testStartTime);
            $endDateTime = Carbon::parse($testDate . ' ' . $testEndTime);
            
            // Get all bookings for the test date
            $allBookings = Booking::where('service_date', $testDate)
                ->with(['service', 'user'])
                ->get();
            
            // Test conflict checking
            $conflicts = $this->checkConflictsDirectly($testDate . ' ' . $testStartTime, $testDate . ' ' . $testEndTime, null, false, null);
            
            return response()->json([
                'status' => 'success',
                'booking_count' => $bookingCount,
                'service_count' => $serviceCount,
                'test_date' => $testDate,
                'test_time_range' => $testStartTime . ' - ' . $testEndTime,
                'all_bookings_on_date' => $allBookings->map(function($booking) {
                    return [
                        'id' => $booking->id,
                        'status' => $booking->status,
                        'service_date' => $booking->service_date,
                        'service_time' => $booking->service_time,
                        'service_name' => $booking->service ? $booking->service->name : 'No service',
                        'duration_minutes' => $booking->service ? $booking->service->duration_minutes : 'No duration'
                    ];
                }),
                'conflicts_found' => $conflicts,
                'message' => 'Database connection working and conflict check completed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Check if a booking conflicts with a time range using service duration
     */
    private function bookingConflictsWithTimeRange($booking, $startDateTime, $endDateTime)
    {
        // Parse booking start time - handle different time formats
        $serviceTime = $booking->service_time;
        $serviceDate = $booking->service_date;
        
        // Additional safety check for service
        if (!$booking->service) {
            \Log::error('Booking has no service in conflict check:', [
                'booking_id' => $booking->id,
                'service_id' => $booking->service_id
            ]);
            return false;
        }
        
        // Try to parse the time in different formats
        try {
            // First try: direct parsing
            $bookingStart = Carbon::parse($serviceDate . ' ' . $serviceTime);
        } catch (\Exception $e) {
            // Second try: handle common time formats
            try {
                // If service_time is like "9:00 AM" or "9:00AM"
                if (preg_match('/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i', $serviceTime, $matches)) {
                    $hour = (int)$matches[1];
                    $minute = (int)$matches[2];
                    $ampm = strtoupper($matches[3]);
                    
                    // Convert to 24-hour format
                    if ($ampm === 'PM' && $hour !== 12) {
                        $hour += 12;
                    } elseif ($ampm === 'AM' && $hour === 12) {
                        $hour = 0;
                    }
                    
                    $bookingStart = Carbon::parse($serviceDate)->setTime($hour, $minute);
                } else {
                    // Fallback: try to parse as is
                    $bookingStart = Carbon::parse($serviceDate . ' ' . $serviceTime);
                }
            } catch (\Exception $e2) {
                \Log::error('Failed to parse booking time:', [
                    'booking_id' => $booking->id,
                    'service_date' => $serviceDate,
                    'service_time' => $serviceTime,
                    'error1' => $e->getMessage(),
                    'error2' => $e2->getMessage()
                ]);
                return false; // Can't determine conflict if we can't parse time
            }
        }
        
        // Calculate booking end time using service duration
        $durationMinutes = $booking->service->duration_minutes ?? 60;
        $bookingEnd = $bookingStart->copy()->addMinutes($durationMinutes);
        
        // Check if time ranges overlap
        // Two time ranges overlap if: start1 < end2 AND start2 < end1
        $overlaps = $bookingStart->lt($endDateTime) && $bookingEnd->gt($startDateTime);
        
        // Debug logging
        \Log::info('Booking conflict check:', [
            'booking_id' => $booking->id,
            'service_date' => $serviceDate,
            'service_time' => $serviceTime,
            'duration_minutes' => $durationMinutes,
            'booking_start' => $bookingStart->toDateTimeString(),
            'booking_end' => $bookingEnd->toDateTimeString(),
            'activity_start' => $startDateTime->toDateTimeString(),
            'activity_end' => $endDateTime->toDateTimeString(),
            'overlaps' => $overlaps,
            'overlap_check_details' => [
                'booking_start_lt_activity_end' => $bookingStart->lt($endDateTime),
                'booking_end_gt_activity_start' => $bookingEnd->gt($startDateTime),
            ],
            'time_parsing_debug' => [
                'raw_service_time' => $serviceTime,
                'parsed_start' => $bookingStart->toDateTimeString(),
                'parsed_end' => $bookingEnd->toDateTimeString(),
                'service_exists' => $booking->service ? 'yes' : 'no',
                'service_duration' => $durationMinutes
            ]
        ]);
        
        return $overlaps;
    }

    /**
     * Check if a parochial activity conflicts with a time range
     */
    private function parochialActivityConflictsWithTimeRange($activity, $startDateTime, $endDateTime)
    {
        $activityDate = Carbon::parse($activity->event_date);
        
        // Check if dates match
        if (!$activityDate->equalTo($startDateTime->toDateString())) {
            return false;
        }

        // If blocking full day, any time range on that date conflicts
        if ($activity->block_type === 'full_day') {
            return true;
        }

        // If blocking time slot, check time overlap
        if ($activity->block_type === 'time_slot') {
            $activityStart = Carbon::parse($activity->event_date . ' ' . $activity->start_time->format('H:i:s'));
            $activityEnd = Carbon::parse($activity->event_date . ' ' . $activity->end_time->format('H:i:s'));

            // Check if time ranges overlap
            return $activityStart->lt($endDateTime) && $activityEnd->gt($startDateTime);
        }

        return false;
    }

    /**
     * Check for conflicts directly (used by store method)
     */
    private function checkConflictsDirectly($startAt, $endAt = null, $location = null, $isAllDay = false, $excludeActivityId = null)
    {
        $conflicts = [
            'parochial_activities' => [],
            'bookings' => [],
            'ministry_activities' => [],
            'has_conflicts' => false
        ];

        $startDateTime = Carbon::parse($startAt);
        $endDateTime = $endAt ? Carbon::parse($endAt) : $startDateTime->copy()->addHours(2);
        
        // Check if this is an all-day event (either explicitly set or spans more than 20 hours)
        if ($endAt) {
            $duration = $startDateTime->diffInHours($endDateTime);
            $isAllDay = $isAllDay || $duration >= 20; // Consider events longer than 20 hours as all-day
        }

        // Debug logging
        \Log::info('Conflict check started:', [
            'start_at' => $startAt,
            'end_at' => $endAt,
            'start_datetime' => $startDateTime->toDateTimeString(),
            'end_datetime' => $endDateTime->toDateTimeString(),
            'is_all_day' => $isAllDay,
            'is_all_day_parameter' => $isAllDay,
            'duration_hours' => $endAt ? $startDateTime->diffInHours($endDateTime) : 'N/A',
            'location' => $location,
            'exclude_activity_id' => $excludeActivityId
        ]);

        // Check parochial activities
        $blockingActivities = ParochialActivity::active()->onDate($startDateTime->toDateString())->get();
        
        foreach ($blockingActivities as $activity) {
            if ($this->parochialActivityConflictsWithTimeRange($activity, $startDateTime, $endDateTime)) {
                $conflicts['parochial_activities'][] = $activity;
            }
        }

        // Check ALL bookings for the date (not just filtered by status) for debugging
        $allBookings = Booking::where('service_date', $startDateTime->toDateString())
            ->with(['service', 'user'])
            ->get();

        \Log::info('All bookings found for date:', [
            'date' => $startDateTime->toDateString(),
            'total_bookings' => $allBookings->count(),
            'bookings_details' => $allBookings->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'status' => $booking->status,
                    'service_date' => $booking->service_date,
                    'service_time' => $booking->service_time,
                    'service_name' => $booking->service ? $booking->service->name : 'No service',
                    'duration_minutes' => $booking->service ? $booking->service->duration_minutes : 'No duration',
                    'service_loaded' => $booking->relationLoaded('service') ? 'yes' : 'no',
                    'service_id' => $booking->service_id
                ];
            })
        ]);

        // Check bookings with proper duration calculation (filtered by status)
        $bookings = Booking::whereIn('status', ['pending', 'acknowledged', 'approved', 'payment_hold', 'confirmed'])
            ->where('service_date', $startDateTime->toDateString())
            ->with(['service', 'user'])
            ->get();

        \Log::info('Filtered bookings for conflict check:', [
            'filtered_count' => $bookings->count(),
            'filtered_bookings' => $bookings->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'status' => $booking->status,
                    'service_date' => $booking->service_date,
                    'service_time' => $booking->service_time,
                    'service_name' => $booking->service ? $booking->service->name : 'No service',
                    'duration_minutes' => $booking->service ? $booking->service->duration_minutes : 'No duration',
                    'service_loaded' => $booking->relationLoaded('service') ? 'yes' : 'no',
                    'service_id' => $booking->service_id
                ];
            })
        ]);

        foreach ($bookings as $booking) {
            // Additional safety check - reload service if not loaded
            if (!$booking->relationLoaded('service') || !$booking->service) {
                $booking->load('service');
            }
            
            // Skip if service still not found
            if (!$booking->service) {
                \Log::warning('Booking has no service:', [
                    'booking_id' => $booking->id,
                    'service_id' => $booking->service_id
                ]);
                continue;
            }
            
            // For all-day events, ANY booking on the same date is a conflict
            if ($isAllDay) {
                $conflicts['bookings'][] = $booking;
                \Log::info('All-day event conflict detected with booking:', [
                    'booking_id' => $booking->id,
                    'reason' => 'All-day event conflicts with any booking on same date',
                    'is_all_day' => $isAllDay,
                    'booking_date' => $booking->service_date,
                    'activity_date' => $startDateTime->toDateString()
                ]);
            } else {
                // For time-specific events, check time overlap
                if ($this->bookingConflictsWithTimeRange($booking, $startDateTime, $endDateTime)) {
                    $conflicts['bookings'][] = $booking;
                }
            }
        }

        // Check ministry activities
        $ministryActivities = MinistryActivity::query()
            ->whereDate('start_at', '<=', $endDateTime)
            ->where(function($q) use ($startDateTime) {
                $q->whereDate('end_at', '>=', $startDateTime)->orWhereNull('end_at');
            })
            ->when($excludeActivityId, function($q) use ($excludeActivityId) {
                $q->where('id', '!=', $excludeActivityId);
            })
            ->with('ministry')
            ->get();

        foreach ($ministryActivities as $activity) {
            $activityStart = $activity->start_at;
            $activityEnd = $activity->end_at ?: $activity->start_at->copy()->addHours(2);
            
            if ($activityStart->lt($endDateTime) && $activityEnd->gt($startDateTime)) {
                $conflicts['ministry_activities'][] = $activity;
            }
        }

        // Filter by location if provided
        if ($location) {
            $conflicts['parochial_activities'] = collect($conflicts['parochial_activities'])
                ->filter(fn($activity) => stripos($activity->location ?? '', $location) !== false)
                ->values()
                ->toArray();
            
            $conflicts['ministry_activities'] = collect($conflicts['ministry_activities'])
                ->filter(fn($activity) => stripos($activity->location ?? '', $location) !== false)
                ->values()
                ->toArray();
        }

        // Determine if there are any conflicts
        $conflicts['has_conflicts'] = count($conflicts['parochial_activities']) > 0 || 
                                    count($conflicts['bookings']) > 0 || 
                                    count($conflicts['ministry_activities']) > 0;

        \Log::info('Conflict check completed:', [
            'has_conflicts' => $conflicts['has_conflicts'],
            'parochial_conflicts' => count($conflicts['parochial_activities']),
            'booking_conflicts' => count($conflicts['bookings']),
            'ministry_conflicts' => count($conflicts['ministry_activities']),
            'is_all_day' => $isAllDay,
            'exclude_activity_id' => $excludeActivityId
        ]);

        return $conflicts;
    }

    /**
     * Get conflict summary for display
     */
    private function getConflictSummary($conflicts)
    {
        $summary = [];
        
        if (count($conflicts['parochial_activities']) > 0) {
            $summary[] = count($conflicts['parochial_activities']) . ' parochial event(s)';
        }
        
        if (count($conflicts['bookings']) > 0) {
            $summary[] = count($conflicts['bookings']) . ' booking(s)';
        }
        
        if (count($conflicts['ministry_activities']) > 0) {
            $summary[] = count($conflicts['ministry_activities']) . ' ministry activity(ies)';
        }
        
        return implode(', ', $summary);
    }

    /**
     * Upload liquidation report for an activity
     */
    public function uploadLiquidation(Request $request, MinistryActivity $activity)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        
        // Ensure the activity belongs to this ministry
        if ($activity->ministry_id !== $ministry->id) {
            abort(403);
        }
        
        // Validate the request
        $request->validate([
            'liquidation_report' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:10240', // 10MB
            'liquidation_notes' => 'nullable|string|max:1000'
        ]);
        
        // Store the file
        $file = $request->file('liquidation_report');
        $filename = time() . '_liquidation_' . $file->getClientOriginalName();
        $path = $file->storeAs('liquidation_reports', $filename, 'public');
        
        // Update the activity
        $activity->update([
            'liquidation_report_path' => $path,
            'liquidation_submitted_at' => now(),
            'liquidation_notes' => $request->liquidation_notes
        ]);
        
        return redirect()->route('ministry.activities.show', $activity)
            ->with('success', 'Liquidation report uploaded successfully!');
    }
    
    /**
     * Mark activity as complete
     */
    public function markComplete(Request $request, MinistryActivity $activity)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        
        // Ensure the activity belongs to this ministry
        if ($activity->ministry_id !== $ministry->id) {
            abort(403);
        }
        
        // Ensure liquidation report has been uploaded
        if (!$activity->liquidation_report_path) {
            return redirect()->route('ministry.activities.show', $activity)
                ->with('error', 'Cannot mark activity as complete without uploading liquidation report.');
        }
        
        // Validate the request
        $request->validate([
            'completion_notes' => 'nullable|string|max:1000'
        ]);
        
        // Update the budget request status to complete
        // Find the approved budget request (not pending)
        $budgetRequest = $activity->budgetRequests()->where('status', 'approved')->first();
        
        if ($budgetRequest) {
            $budgetRequest->update([
                'status' => 'complete',
                'completed_at' => now(),
                'completion_notes' => $request->completion_notes
            ]);
        } else {
            // Fallback: try to find any budget request for this activity
            $budgetRequest = $activity->budgetRequests()->first();
            if ($budgetRequest) {
                $budgetRequest->update([
                    'status' => 'complete',
                    'completed_at' => now(),
                    'completion_notes' => $request->completion_notes
                ]);
            }
        }
        
        return redirect()->route('ministry.activities.show', $activity)
            ->with('success', 'Activity marked as complete successfully!');
    }
}


