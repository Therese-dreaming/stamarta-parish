<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParochialActivity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ParochialActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = ParochialActivity::orderBy('event_date', 'desc')
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

        return view('admin.parochial-activities.index', compact('activities', 'upcomingActivities', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.parochial-activities.create');
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

        ParochialActivity::create($validated);

        return redirect()->route('admin.parochial-activities.index')
            ->with('success', 'Parochial activity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ParochialActivity $parochialActivity)
    {
        // Get affected dates for recurring activities
        $affectedDates = $parochialActivity->getAffectedDates();
        
        return view('admin.parochial-activities.show', compact('parochialActivity', 'affectedDates'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParochialActivity $parochialActivity)
    {
        return view('admin.parochial-activities.edit', compact('parochialActivity'));
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
