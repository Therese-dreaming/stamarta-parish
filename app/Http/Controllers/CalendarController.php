<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ParochialActivity;
use App\Models\MinistryActivity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = $request->get('month', now()->month);
        $currentYear = $request->get('year', now()->year);
        
        // Get events for the current month
        $events = $this->getEventsForMonth($currentYear, $currentMonth);
        
        // Get statistics
        $stats = $this->getCalendarStats();
        
        return view('calendar.index', compact('events', 'currentMonth', 'currentYear', 'stats'));
    }
    
    public function getEvents(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $events = $this->getEventsForMonth($year, $month);
        
        return response()->json([
            'success' => true,
            'events' => $events,
            'total' => $events->count()
        ]);
    }
    
    private function getEventsForMonth($year, $month)
    {
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
                    'date' => $booking->service_date->utc()->format('Y-m-d'),
                    'time' => $booking->service_time ? Carbon::parse($booking->service_time)->format('g:i A') : 'All Day',
                    'location' => 'Parish Church',
                    'type' => 'booking',
                    'status' => $booking->status,
                    'client' => $booking->user->name ?? 'Unknown',
                    'description' => 'Service booking for ' . ($booking->user->name ?? 'Unknown'),
                    'color' => 'blue'
                ]);
            }

            // Get parochial activities (active and completed)
            $parochialActivities = ParochialActivity::whereIn('status', ['active', 'completed'])
                ->whereBetween('event_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->get();

            foreach ($parochialActivities as $activity) {
                $events->push([
                    'id' => 'parochial_' . $activity->id,
                    'title' => $activity->title,
                    'date' => $activity->event_date->utc()->format('Y-m-d'),
                    'time' => $activity->start_time ? Carbon::parse($activity->start_time)->format('g:i A') : 'All Day',
                    'location' => $activity->location ?? 'Parish Grounds',
                    'type' => 'parochial',
                    'status' => $activity->status,
                    'description' => $activity->description ?? 'Parish activity',
                    'color' => 'green'
                ]);
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

            // Sort events by date and time
            $events = $events->sortBy(['date', 'time'])->values();

        } catch (\Exception $e) {
            // Return empty collection on error
            $events = collect();
        }

        return $events;
    }
    
    private function getCalendarStats()
    {
        try {
            $thisMonth = now()->startOfMonth();
            $nextMonth = now()->addMonth()->endOfMonth();
            
            $bookingsCount = Booking::whereIn('status', ['approved', 'completed'])
                ->whereBetween('service_date', [$thisMonth, $nextMonth])
                ->count();
                
            $parochialCount = ParochialActivity::whereIn('status', ['active', 'completed'])
                ->whereBetween('event_date', [$thisMonth, $nextMonth])
                ->count();
                
            $ministryCount = MinistryActivity::whereHas('currentBudgetRequest', function($query) {
                $query->whereIn('status', ['approved', 'complete']);
            })
            ->whereBetween('start_at', [$thisMonth, $nextMonth])
            ->count();
            
            return [
                'bookings' => $bookingsCount,
                'parochial' => $parochialCount,
                'ministry' => $ministryCount,
                'total' => $bookingsCount + $parochialCount + $ministryCount
            ];
        } catch (\Exception $e) {
            return [
                'bookings' => 0,
                'parochial' => 0,
                'ministry' => 0,
                'total' => 0
            ];
        }
    }
}
