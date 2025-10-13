<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParochialActivity;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ParochialActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = ParochialActivity::with(['creator', 'updater'])
            ->orderBy('event_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(15);

        $upcomingActivities = ParochialActivity::active()
            ->upcoming(7)
            ->orderBy('event_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        $stats = [
            'total_activities' => ParochialActivity::count(),
            'active_activities' => ParochialActivity::active()->count(),
            'upcoming_activities' => ParochialActivity::upcoming(30)->count(),
            'past_activities' => ParochialActivity::past()->count(),
        ];

        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.parochial-activities.index', compact('activities', 'upcomingActivities', 'stats', 'isStaff'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.parochial-activities.create', compact('isStaff'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'block_type' => 'required|in:time_slot,full_day',
            'location' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'is_recurring' => 'boolean',
            'recurring_pattern' => 'nullable|array',
            'recurring_end_date' => 'nullable|date|after:event_date',
            'excluded_dates' => 'nullable|array',
            'excluded_dates.*' => 'date',
            'notes' => 'nullable|string',
        ]);

        // Check for conflicts before creating
        $conflictCheckRequest = \Illuminate\Http\Request::create('/check-conflicts', 'POST', [
            'event_date' => $validated['event_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'block_type' => $validated['block_type'],
            'is_recurring' => $request->boolean('is_recurring'),
            'recurring_pattern_type' => $request->input('recurring_pattern.type', 'weekly'),
            'recurring_end_date' => $validated['recurring_end_date'] ?? null,
        ]);
        
        $conflictResponse = $this->checkConflicts($conflictCheckRequest);
        $conflictData = json_decode($conflictResponse->getContent(), true);
        
        // Check if there are blocking conflicts (errors) that haven't been skipped
        $excludedDates = $request->input('excluded_dates', []);
        $blockingConflicts = collect($conflictData['conflicts'])
            ->where('severity', 'error')
            ->filter(function($conflict) use ($excludedDates) {
                // Only consider it blocking if the date is NOT in excluded dates
                return !in_array($conflict['date'], $excludedDates);
            });
        
        if ($blockingConflicts->count() > 0) {
            $conflictMessages = $blockingConflicts->map(function($conflict) {
                $type = $conflict['type'] === 'parochial_activity' ? 'Parochial Activity' : 'Ministry Activity';
                return "{$type}: {$conflict['title']} on {$conflict['date']}";
            })->take(5)->implode(', ');
            
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'conflict' => "Cannot create activity due to conflicts with existing activities. Please skip the conflicting dates or choose a different time. Conflicts: {$conflictMessages}"
                ]);
        }

        // Handle recurring pattern (supports weekly, monthly, yearly)
        if ($request->boolean('is_recurring')) {
            $validated['recurring_pattern'] = [
                'type' => $request->input('recurring_pattern.type'),
                'interval' => $request->input('recurring_pattern.interval', 1),
                'excluded_dates' => $request->input('excluded_dates', []), // Store excluded dates
            ];
        } else {
            $validated['recurring_pattern'] = null;
            $validated['recurring_end_date'] = null;
        }

        // Add user information
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $activity = ParochialActivity::create($validated);

        // If called by staff, notify admins
        if (auth()->user()->role === 'staff') {
            NotificationService::staffActivityCreated($activity, auth()->user()->name);
        }

        return redirect()->route('admin.parochial-activities.index')
            ->with('success', 'Parochial activity created successfully.');
    }

    /**
     * Check for conflicts with existing bookings and activities
     */
    public function checkConflicts(Request $request)
    {
        $request->validate([
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'block_type' => 'required|in:time_slot,full_day',
            'is_recurring' => 'boolean',
            'recurring_pattern_type' => 'nullable|in:weekly,monthly,yearly',
            'recurring_end_date' => 'nullable|date',
            'activity_id' => 'nullable|exists:parochial_activities,id' // For edit mode
        ]);

        $eventDate = $request->input('event_date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $blockType = $request->input('block_type');
        $isRecurring = $request->boolean('is_recurring');
        $recurringPatternType = $request->input('recurring_pattern_type', 'weekly');
        $recurringEndDate = $request->input('recurring_end_date');
        $activityId = $request->input('activity_id'); // Exclude current activity when editing

        $conflicts = [];
        
        // Generate all dates to check (for recurring activities, check all occurrences)
        $datesToCheck = [];
        
        if ($isRecurring && $recurringEndDate) {
            // Generate recurring dates
            $currentDate = \Carbon\Carbon::parse($eventDate);
            $endDate = \Carbon\Carbon::parse($recurringEndDate);
            
            while ($currentDate->lte($endDate)) {
                $datesToCheck[] = $currentDate->format('Y-m-d');
                
                // Increment based on pattern type
                switch ($recurringPatternType) {
                    case 'weekly':
                        $currentDate->addWeek();
                        break;
                    case 'monthly':
                        $currentDate->addMonth();
                        break;
                    case 'yearly':
                        $currentDate->addYear();
                        break;
                }
            }
        } else {
            // Single date
            $datesToCheck[] = $eventDate;
        }

        // Check for existing bookings on all dates (informational only - not blocking)
        foreach ($datesToCheck as $dateToCheck) {
            $bookings = \App\Models\Booking::where('service_date', $dateToCheck)
                ->whereIn('status', ['pending', 'acknowledged', 'payment_hold', 'approved'])
                ->with('service', 'user')
                ->get();

            foreach ($bookings as $booking) {
                $bookingStart = \Carbon\Carbon::parse($dateToCheck . ' ' . $booking->service_time);
                $bookingEnd = $bookingStart->copy()->addMinutes($booking->service->duration_minutes ?? 120);
                
                $activityStart = \Carbon\Carbon::parse($dateToCheck . ' ' . $startTime);
                $activityEnd = \Carbon\Carbon::parse($dateToCheck . ' ' . $endTime);

                // Check if times overlap or if it's a full day block
                if ($blockType === 'full_day' || 
                    ($activityStart < $bookingEnd && $activityEnd > $bookingStart)) {
                    $conflicts[] = [
                        'type' => 'booking',
                        'title' => $booking->service->name,
                        'user' => $booking->user->name,
                        'date' => $dateToCheck,
                        'time' => $booking->service_time,
                        'duration' => $booking->service->duration_minutes ?? 120,
                        'severity' => 'info', // Informational only - existing bookings don't block parochial activities
                        'occurrence' => $isRecurring ? $dateToCheck : null // Show which occurrence has conflict
                    ];
                }
            }
        }

        // Check for existing parochial activities on all dates (including recurring)
        foreach ($datesToCheck as $dateToCheck) {
            $checkDate = \Carbon\Carbon::parse($dateToCheck);
            $dayOfWeek = $checkDate->dayOfWeek;
            
            // Get all active parochial activities
            $allActivities = ParochialActivity::active()
                ->when($activityId, function($query) use ($activityId) {
                    return $query->where('id', '!=', $activityId);
                })
                ->get();
            
            foreach ($allActivities as $activity) {
                $shouldCheck = false;
                
                // Check if this activity occurs on the date we're checking
                if ($activity->is_recurring && $activity->recurring_pattern && $activity->recurring_end_date) {
                    // For recurring activities, check if the date falls within the recurrence pattern
                    $activityStart = \Carbon\Carbon::parse($activity->event_date);
                    $activityEnd = \Carbon\Carbon::parse($activity->recurring_end_date);
                    
                    // Check if date is within range
                    if ($checkDate->between($activityStart, $activityEnd)) {
                        $pattern = $activity->recurring_pattern;
                        $patternType = $pattern['type'] ?? 'weekly';
                        
                        // Check if this date matches the recurrence pattern
                        if ($patternType === 'weekly') {
                            // Check if same day of week
                            if ($checkDate->dayOfWeek === $activityStart->dayOfWeek) {
                                // Check if not in excluded dates
                                $excludedDates = $pattern['excluded_dates'] ?? [];
                                if (!in_array($dateToCheck, $excludedDates)) {
                                    $shouldCheck = true;
                                }
                            }
                        } elseif ($patternType === 'monthly') {
                            // Check if same day of month
                            if ($checkDate->day === $activityStart->day) {
                                $excludedDates = $pattern['excluded_dates'] ?? [];
                                if (!in_array($dateToCheck, $excludedDates)) {
                                    $shouldCheck = true;
                                }
                            }
                        } elseif ($patternType === 'yearly') {
                            // Check if same month and day
                            if ($checkDate->month === $activityStart->month && $checkDate->day === $activityStart->day) {
                                $excludedDates = $pattern['excluded_dates'] ?? [];
                                if (!in_array($dateToCheck, $excludedDates)) {
                                    $shouldCheck = true;
                                }
                            }
                        }
                    }
                } else {
                    // Non-recurring activity - check if exact date match
                    if ($activity->event_date === $dateToCheck) {
                        $shouldCheck = true;
                    }
                }
                
                if ($shouldCheck) {
                    $existingStart = \Carbon\Carbon::parse($dateToCheck . ' ' . $activity->start_time->format('H:i'));
                    $existingEnd = \Carbon\Carbon::parse($dateToCheck . ' ' . $activity->end_time->format('H:i'));
                    
                    $activityStart = \Carbon\Carbon::parse($dateToCheck . ' ' . $startTime);
                    $activityEnd = \Carbon\Carbon::parse($dateToCheck . ' ' . $endTime);

                    // Check if times overlap or if either is a full day block
                    if ($blockType === 'full_day' || $activity->block_type === 'full_day' ||
                        ($activityStart < $existingEnd && $activityEnd > $existingStart)) {
                        $conflicts[] = [
                            'type' => 'parochial_activity',
                            'title' => $activity->title,
                            'date' => $dateToCheck,
                            'start_time' => $activity->start_time->format('h:i A'),
                            'end_time' => $activity->end_time->format('h:i A'),
                            'block_type' => $activity->block_type,
                            'is_recurring' => $activity->is_recurring,
                            'severity' => 'error', // Cannot create if other parochial activities exist
                            'occurrence' => $isRecurring ? $dateToCheck : null
                        ];
                    }
                }
            }

            // Check for ministry activities
            $ministryActivities = \App\Models\MinistryActivity::query()
                ->where('is_public', true)
                ->whereHas('approvedBudgetRequest')
                ->whereDate('start_at', $dateToCheck)
                ->get();

            foreach ($ministryActivities as $mActivity) {
                $mStart = \Carbon\Carbon::parse($mActivity->start_at);
                $mEnd = $mActivity->end_at ? \Carbon\Carbon::parse($mActivity->end_at) : $mStart->copy()->addHours(2);
                
                $activityStart = \Carbon\Carbon::parse($dateToCheck . ' ' . $startTime);
                $activityEnd = \Carbon\Carbon::parse($dateToCheck . ' ' . $endTime);

                // Check if times overlap or if it's a full day block
                if ($blockType === 'full_day' || $mActivity->is_all_day ||
                    ($activityStart < $mEnd && $activityEnd > $mStart)) {
                    $conflicts[] = [
                        'type' => 'ministry_activity',
                        'title' => $mActivity->title,
                        'ministry' => optional($mActivity->ministry)->name,
                        'date' => $dateToCheck,
                        'start_time' => $mStart->format('h:i A'),
                        'end_time' => $mEnd->format('h:i A'),
                        'is_all_day' => $mActivity->is_all_day,
                        'severity' => 'error', // Cannot create if ministry activities exist
                        'occurrence' => $isRecurring ? $dateToCheck : null
                    ];
                }
            }
        }

        // Get unique dates with blocking conflicts (errors only, not info)
        $conflictingDates = collect($conflicts)
            ->where('severity', 'error')
            ->pluck('date')
            ->unique()
            ->values()
            ->toArray();

        return response()->json([
            'has_conflicts' => count($conflicts) > 0,
            'conflicts' => $conflicts,
            'conflict_count' => count($conflicts),
            'has_errors' => collect($conflicts)->where('severity', 'error')->count() > 0,
            'has_warnings' => collect($conflicts)->where('severity', 'warning')->count() > 0,
            'has_info' => collect($conflicts)->where('severity', 'info')->count() > 0,
            'conflicting_dates' => $conflictingDates,
            'total_occurrences' => count($datesToCheck),
            'available_occurrences' => count($datesToCheck) - count($conflictingDates)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ParochialActivity $parochialActivity)
    {
        // Load relationships
        $parochialActivity->load(['creator', 'updater']);
        
        // Get affected dates for recurring activities
        $affectedDates = $parochialActivity->getAffectedDates();
        
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.parochial-activities.show', compact('parochialActivity', 'affectedDates', 'isStaff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParochialActivity $parochialActivity)
    {
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.parochial-activities.edit', compact('parochialActivity', 'isStaff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParochialActivity $parochialActivity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'block_type' => 'required|in:time_slot,full_day',
            'location' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'status' => 'required|in:active,cancelled,completed',
            'is_recurring' => 'boolean',
            'recurring_pattern' => 'nullable|array',
            'recurring_end_date' => 'nullable|date|after:event_date',
            'notes' => 'nullable|string',
        ]);

        // Handle recurring pattern
        if ($request->boolean('is_recurring')) {
            $validated['recurring_pattern'] = [
                'type' => $request->input('recurring_pattern.type'),
                'interval' => $request->input('recurring_pattern.interval', 1),
            ];
        } else {
            $validated['recurring_pattern'] = null;
            $validated['recurring_end_date'] = null;
        }

        // Add updated_by information
        $validated['updated_by'] = auth()->id();

        $parochialActivity->update($validated);

        return redirect()->route('admin.parochial-activities.index')
            ->with('success', 'Parochial activity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParochialActivity $parochialActivity)
    {
        $parochialActivity->delete();

        return redirect()->route('admin.parochial-activities.index')
            ->with('success', 'Parochial activity deleted successfully.');
    }

    /**
     * Get activities for calendar view
     */
    public function calendar()
    {
        $activities = ParochialActivity::active()
            ->orderBy('event_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        $calendarData = [];
        foreach ($activities as $activity) {
            $calendarData[] = [
                'id' => $activity->id,
                'title' => $activity->title,
                'start' => $activity->event_date->format('Y-m-d') . 'T' . $activity->start_time->format('H:i:s'),
                'end' => $activity->event_date->format('Y-m-d') . 'T' . $activity->end_time->format('H:i:s'),
                'color' => $activity->calendar_color,
                'extendedProps' => [
                    'block_type' => $activity->block_type,
                    'location' => $activity->location,
                    'description' => $activity->description,
                ]
            ];
        }

        return response()->json($calendarData);
    }

    /**
     * Get activities that block bookings for a specific date
     */
    public function getBlockingActivities(Request $request)
    {
        $date = $request->input('date');
        
        if (!$date) {
            return response()->json([]);
        }

        $activities = ParochialActivity::active()
            ->onDate($date)
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json($activities);
    }
}
