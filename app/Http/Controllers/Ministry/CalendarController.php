<?php

namespace App\Http\Controllers\Ministry;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Models\MinistryActivity;
use App\Models\ParochialActivity;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
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
        
        // Get current month or requested month
        $currentDate = $request->filled('month') 
            ? Carbon::createFromFormat('Y-m', $request->month)
            : Carbon::now();
        
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        
        // Get ministry activities for the current month
        $ministryActivities = $ministry->activities()
            ->whereBetween('start_at', [$startOfMonth, $endOfMonth])
            ->orderBy('start_at', 'asc')
            ->get();
        
        // Get parochial activities for context (read-only)
        $parochialActivities = ParochialActivity::where(function($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('event_date', [$startOfMonth, $endOfMonth])
                  ->orWhere(function($q) use ($startOfMonth, $endOfMonth) {
                      $q->where('is_recurring', true)
                        ->where('recurring_pattern->type', 'weekly');
                  });
        })->get();
        
        // Get bookings for context (to show church usage)
        $bookings = Booking::whereBetween('service_date', [$startOfMonth, $endOfMonth])
            ->whereIn('status', ['pending', 'acknowledged', 'payment_hold', 'approved'])
            ->with('service')
            ->get();
        
        return view('ministry.calendar.index', compact(
            'ministry', 
            'ministryActivities', 
            'parochialActivities', 
            'bookings', 
            'currentDate'
        ));
    }

    public function getEvents(Request $request)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);
        
        $events = [];
        
        // Get ministry activities
        $ministryActivities = $ministry->activities()
            ->whereBetween('start_at', [$start, $end])
            ->get();
        
        foreach ($ministryActivities as $activity) {
            $events[] = [
                'id' => 'ministry-' . $activity->id,
                'title' => $activity->title,
                'start' => $activity->start_at->toISOString(),
                'end' => $activity->end_at ? $activity->end_at->toISOString() : null,
                'allDay' => $activity->is_all_day,
                'backgroundColor' => '#8B5CF6', // Purple for ministry activities
                'borderColor' => '#7C3AED',
                'textColor' => '#FFFFFF',
                'extendedProps' => [
                    'type' => 'ministry',
                    'description' => $activity->description,
                    'location' => $activity->location,
                    'is_public' => $activity->is_public,
                    'status' => $activity->status,
                    'budget_amount' => $activity->approvedBudgetRequest?->amount ?? 0,
                ]
            ];
        }
        
        // Get parochial activities for context
        $parochialActivities = ParochialActivity::where(function($query) use ($start, $end) {
            $query->whereBetween('event_date', [$start, $end])
                  ->orWhere(function($q) use ($start, $end) {
                      $q->where('is_recurring', true)
                        ->where('recurring_pattern->type', 'weekly');
                  });
        })->get();
        
        foreach ($parochialActivities as $activity) {
            if ($activity->is_recurring) {
                // Handle recurring activities
                $recurringPattern = $activity->recurring_pattern;
                if ($recurringPattern['type'] === 'weekly') {
                    $dayOfWeek = $recurringPattern['day_of_week'];
                    $current = $start->copy();
                    
                    while ($current <= $end) {
                        if ($current->dayOfWeek === $dayOfWeek) {
                            $events[] = [
                                'id' => 'parochial-recurring-' . $activity->id . '-' . $current->format('Y-m-d'),
                                'title' => $activity->title . ' (Parochial)',
                                'start' => $current->format('Y-m-d'),
                                'allDay' => $activity->block_type === 'full_day',
                                'backgroundColor' => '#3B82F6', // Blue for parochial activities
                                'borderColor' => '#2563EB',
                                'textColor' => '#FFFFFF',
                                'extendedProps' => [
                                    'type' => 'parochial',
                                    'description' => $activity->description,
                                    'block_type' => $activity->block_type,
                                    'is_recurring' => true,
                                ]
                            ];
                        }
                        $current->addDay();
                    }
                }
            } else {
                $events[] = [
                    'id' => 'parochial-' . $activity->id,
                    'title' => $activity->title . ' (Parochial)',
                    'start' => $activity->event_date,
                    'allDay' => $activity->block_type === 'full_day',
                    'backgroundColor' => '#3B82F6', // Blue for parochial activities
                    'borderColor' => '#2563EB',
                    'textColor' => '#FFFFFF',
                    'extendedProps' => [
                        'type' => 'parochial',
                        'description' => $activity->description,
                        'block_type' => $activity->block_type,
                        'is_recurring' => false,
                    ]
                ];
            }
        }
        
        return response()->json($events);
    }
}
