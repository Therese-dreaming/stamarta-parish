<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ParochialActivity;
use App\Models\MinistryActivity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function getEvents(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        $events = collect();

        try {
            // Get bookings (approved and completed)
            $bookings = Booking::whereIn('status', ['approved', 'completed'])
                ->whereBetween('service_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->with(['service', 'user'])
                ->get();

            foreach ($bookings as $booking) {
                $events->push([
                    'id' => 'booking_' . $booking->id,
                    'title' => $booking->service->name ?? 'Booking',
                    'date' => $booking->service_date->format('Y-m-d'), // Use local date format, not UTC
                    'time' => $booking->service_time ? Carbon::parse($booking->service_time)->format('g:i A') : 'All Day',
                    'location' => 'Parish Church',
                    'type' => 'booking',
                    'status' => $booking->status,
                    'client' => $booking->user->name ?? 'Unknown',
                    'description' => 'Service booking for ' . ($booking->user->name ?? 'Unknown'),
                    'color' => 'blue'
                ]);
            }

            // Get parochial activities (active and completed) - including recurring ones
            $parochialActivities = ParochialActivity::whereIn('status', ['active', 'completed'])
                ->where(function($query) use ($startDate, $endDate) {
                    // Non-recurring activities within date range
                    $query->where('is_recurring', false)
                          ->whereBetween('event_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
                })
                ->orWhere(function($query) use ($startDate, $endDate) {
                    // Recurring activities that could have instances in this range
                    $query->where('is_recurring', true)
                          ->whereIn('status', ['active', 'completed'])
                          ->where(function($q) use ($endDate) {
                              $q->whereNull('recurring_end_date')
                                ->orWhere('recurring_end_date', '>=', $endDate->format('Y-m-d'));
                          });
                })
                ->get();

            foreach ($parochialActivities as $activity) {
                if ($activity->is_recurring) {
                    // Generate recurring instances for this month
                    $recurringDates = $this->generateRecurringDates($activity, $startDate, $endDate);
                    
                    foreach ($recurringDates as $recurringDate) {
                        $events->push([
                            'id' => 'parochial_' . $activity->id . '_' . $recurringDate->format('Y-m-d'),
                            'title' => $activity->title . ' (Recurring)',
                            'date' => $recurringDate->format('Y-m-d'), // Use local date format
                            'time' => $activity->start_time ? Carbon::parse($activity->start_time)->format('g:i A') : 'All Day',
                            'location' => $activity->location ?? 'Parish Grounds',
                            'type' => 'parochial',
                            'status' => $activity->status,
                            'description' => $activity->description ?? 'Parish activity',
                            'color' => 'green',
                            'is_recurring' => true,
                            'recurrence_type' => $activity->recurring_pattern['type'] ?? 'weekly'
                        ]);
                    }
                } else {
                    // Non-recurring activity
                    $events->push([
                        'id' => 'parochial_' . $activity->id,
                        'title' => $activity->title,
                        'date' => $activity->event_date->format('Y-m-d'), // Use local date format, not UTC
                        'time' => $activity->start_time ? Carbon::parse($activity->start_time)->format('g:i A') : 'All Day',
                        'location' => $activity->location ?? 'Parish Grounds',
                        'type' => 'parochial',
                        'status' => $activity->status,
                        'description' => $activity->description ?? 'Parish activity',
                        'color' => 'green',
                        'is_recurring' => false
                    ]);
                }
            }

            // Get ministry activities (approved and completed)
            $ministryActivities = MinistryActivity::whereHas('currentBudgetRequest', function($query) {
                $query->whereIn('status', ['approved', 'complete']);
            })
            ->orWhereHas('approvedBudgetRequest')
            ->orWhereHas('completedBudgetRequest')
            ->whereBetween('start_at', [$startDate, $endDate])
            ->with(['ministry', 'currentBudgetRequest', 'approvedBudgetRequest', 'completedBudgetRequest'])
            ->get();

            foreach ($ministryActivities as $activity) {
                $budgetRequest = $activity->currentBudgetRequest ?? 
                               $activity->approvedBudgetRequest ?? 
                               $activity->completedBudgetRequest;

                if ($budgetRequest && in_array($budgetRequest->status, ['approved', 'complete'])) {
                    $events->push([
                        'id' => 'ministry_' . $activity->id,
                        'title' => $activity->title,
                        'date' => $activity->start_at->format('Y-m-d'),
                        'time' => $activity->is_all_day ? 'All Day' : $activity->start_at->format('g:i A'),
                        'location' => $activity->location ?? 'Parish Grounds',
                        'type' => 'ministry',
                        'status' => $budgetRequest->status,
                        'ministry' => $activity->ministry->name ?? 'Unknown Ministry',
                        'description' => $activity->description ?? 'Ministry activity',
                        'color' => 'purple'
                    ]);
                }
            }

            // Sort events by date
            $events = $events->sortBy('date')->values();

            return response()->json([
                'success' => true,
                'events' => $events,
                'total' => $events->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching calendar events',
                'error' => $e->getMessage(),
                'events' => []
            ], 500);
        }
    }

    /**
     * Generate recurring dates for a parochial activity within the given date range
     */
    private function generateRecurringDates($activity, $startDate, $endDate)
    {
        $dates = [];
        $pattern = $activity->recurring_pattern ?? [];
        $type = $pattern['type'] ?? 'weekly';
        
        // Get the original event date
        $originalDate = Carbon::parse($activity->event_date);
        
        // Determine the recurring end date
        $recurringEndDate = $activity->recurring_end_date 
            ? Carbon::parse($activity->recurring_end_date) 
            : $endDate->copy()->addYear(); // Default to 1 year if no end date
        
        // Start from the original event date or the start of the range, whichever is later
        $currentDate = $originalDate->copy();
        if ($currentDate->lt($startDate)) {
            // Fast-forward to the first occurrence within the range
            switch ($type) {
                case 'weekly':
                    $dayOfWeek = $originalDate->dayOfWeek;
                    $currentDate = $startDate->copy();
                    while ($currentDate->dayOfWeek !== $dayOfWeek) {
                        $currentDate->addDay();
                    }
                    break;
                case 'monthly':
                    $dayOfMonth = $originalDate->day;
                    $currentDate = $startDate->copy()->day(min($dayOfMonth, $startDate->daysInMonth));
                    if ($currentDate->lt($startDate)) {
                        $currentDate->addMonth()->day(min($dayOfMonth, $currentDate->daysInMonth));
                    }
                    break;
                case 'yearly':
                    $currentDate = $startDate->copy()->month($originalDate->month)->day($originalDate->day);
                    if ($currentDate->lt($startDate)) {
                        $currentDate->addYear();
                    }
                    break;
            }
        }
        
        // Generate dates within the range
        while ($currentDate->lte($endDate) && $currentDate->lte($recurringEndDate)) {
            if ($currentDate->gte($startDate)) {
                $dates[] = $currentDate->copy();
            }
            
            // Move to next occurrence
            switch ($type) {
                case 'weekly':
                    $currentDate->addWeek();
                    break;
                case 'monthly':
                    $originalDay = $originalDate->day;
                    $currentDate->addMonth();
                    $currentDate->day(min($originalDay, $currentDate->daysInMonth));
                    break;
                case 'yearly':
                    $currentDate->addYear();
                    break;
                default:
                    // Unknown type, break to avoid infinite loop
                    break 2;
            }
        }
        
        return $dates;
    }
}
