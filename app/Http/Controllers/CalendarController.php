<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ParochialActivity;
use App\Models\MinistryActivity;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $start = Carbon::parse($request->query('start', Carbon::now()->startOfMonth()->startOfDay()));
        $end = Carbon::parse($request->query('end', Carbon::now()->endOfMonth()->endOfDay()));

        // Aggregate events
        $events = [];

        // Approved bookings
        $approvedBookings = Booking::query()
            ->where('status', 'approved')
            ->whereBetween('service_date', [$start->toDateString(), $end->toDateString()])
            ->with(['service'])
            ->get();

        foreach ($approvedBookings as $b) {
            $events[] = [
                'type' => 'booking',
                'title' => ($b->service->name ?? 'Service') . ' Booking',
                'date' => $b->service_date->format('Y-m-d'),
                'start_time' => $b->service_time,
                'color' => '#2563eb',
            ];
        }

        // Public parochial activities (status active)
        $parochialActivities = ParochialActivity::query()
            ->where('status', 'active')
            ->whereBetween('event_date', [$start->toDateString(), $end->toDateString()])
            ->get();

        foreach ($parochialActivities as $a) {
            $events[] = [
                'type' => 'parochial',
                'title' => $a->title,
                'date' => $a->event_date->format('Y-m-d'),
                'start_time' => $a->start_time->format('H:i'),
                'end_time' => $a->end_time->format('H:i'),
                'color' => '#f59e0b',
            ];
        }

        // Approved ministry activities (public only)
        $ministryActivities = MinistryActivity::query()
            ->where('is_public', true)
            ->whereHas('budgetRequests', function ($q) {
                $q->where('status', 'approved');
            })
            ->where(function ($q) use ($start, $end) {
                $q->whereDate('start_at', '<=', $end->toDateString())
                  ->where(function ($sub) use ($start) {
                      $sub->whereDate('end_at', '>=', $start->toDateString())
                          ->orWhereNull('end_at');
                  });
            })
            ->get();

        foreach ($ministryActivities as $m) {
            $events[] = [
                'type' => 'ministry',
                'title' => $m->title,
                'date' => Carbon::parse($m->start_at)->format('Y-m-d'),
                'start_time' => Carbon::parse($m->start_at)->format('H:i'),
                'end_time' => $m->end_at ? Carbon::parse($m->end_at)->format('H:i') : null,
                'color' => '#10b981',
            ];
        }


        return view('calendar.index', [
            'events' => $events,
            'start' => $start,
            'end' => $end,
        ]);
    }
}


