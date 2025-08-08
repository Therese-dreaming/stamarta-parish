<?php

namespace App\Http\Controllers\Priest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingAction;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $priest = auth()->user();
        
        // Get the priest record associated with this user
        $priestRecord = $priest->priest;
        
        if (!$priestRecord) {
            abort(403, 'No priest record found for this user.');
        }
        
        // Get bookings assigned to this priest
        $assignedBookings = Booking::where('priest_id', $priestRecord->id)
            ->with(['user', 'service'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistics for assigned bookings
        $bookingStats = [
            'total_assigned' => $assignedBookings->count(),
            'pending' => $assignedBookings->where('status', 'pending')->count(),
            'acknowledged' => $assignedBookings->where('status', 'acknowledged')->count(),
            'payment_hold' => $assignedBookings->where('status', 'payment_hold')->count(),
            'approved' => $assignedBookings->where('status', 'approved')->count(),
            'completed' => $assignedBookings->where('status', 'completed')->count(),
            'rejected' => $assignedBookings->where('status', 'rejected')->count(),
        ];

        // Recent activities (actions performed by this priest)
        $recentActivities = BookingAction::where('performed_by', $priest->id)
            ->with(['booking.user', 'booking.service'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Recent assigned bookings
        $recentBookings = $assignedBookings->take(5);

        // Monthly statistics
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $monthlyStats = [
            'assigned_this_month' => $assignedBookings->where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            'completed_this_month' => $assignedBookings->where('status', 'completed')
                ->where('updated_at', '>=', Carbon::now()->startOfMonth())->count(),
            'pending_attention' => $assignedBookings->whereIn('status', ['pending', 'acknowledged', 'payment_hold'])->count(),
        ];

        // Performance metrics
        $performanceMetrics = [
            'completion_rate' => $assignedBookings->count() > 0 
                ? round(($assignedBookings->where('status', 'completed')->count() / $assignedBookings->count()) * 100, 1)
                : 0,
            'average_processing_time' => $this->calculateAverageProcessingTime($assignedBookings),
            'total_services_conducted' => $assignedBookings->where('status', 'completed')->count(),
        ];

        // Upcoming bookings (next 7 days)
        $upcomingBookings = $assignedBookings
            ->where('service_date', '>=', Carbon::now()->toDateString())
            ->where('service_date', '<=', Carbon::now()->addDays(7)->toDateString())
            ->whereIn('status', ['approved', 'acknowledged'])
            ->sortBy('service_date')
            ->take(5);

        return view('priest.dashboard', compact(
            'bookingStats',
            'recentActivities', 
            'recentBookings',
            'monthlyStats',
            'performanceMetrics',
            'upcomingBookings'
        ));
    }

    private function calculateAverageProcessingTime($bookings)
    {
        $completedBookings = $bookings->where('status', 'completed');
        
        if ($completedBookings->count() === 0) {
            return 0;
        }

        $totalHours = 0;
        $count = 0;

        foreach ($completedBookings as $booking) {
            $acknowledgedAction = $booking->actions()
                ->where('action', 'acknowledged')
                ->where('performed_by', auth()->id())
                ->first();

            if ($acknowledgedAction) {
                $completedAction = $booking->actions()
                    ->where('action', 'completed')
                    ->where('performed_by', auth()->id())
                    ->first();

                if ($completedAction) {
                    $hours = $acknowledgedAction->created_at->diffInHours($completedAction->created_at);
                    $totalHours += $hours;
                    $count++;
                }
            }
        }

        return $count > 0 ? round($totalHours / $count, 1) : 0;
    }
} 