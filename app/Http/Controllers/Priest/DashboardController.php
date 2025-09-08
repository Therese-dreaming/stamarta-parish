<?php

namespace App\Http\Controllers\Priest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingAction;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        
        // Get current year and month for filtering
        $currentYear = now()->year;
        $currentMonth = now()->month;
        
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

        // Chart data
        $monthlyBookingsData = $this->getMonthlyBookingsChartData($priestRecord->id, $currentYear);
        $serviceTypeData = $this->getServiceTypeChartData($priestRecord->id);
        $bookingStatusData = $this->getBookingStatusChartData($priestRecord->id);
        $weeklyPerformanceData = $this->getWeeklyPerformanceChartData($priestRecord->id);
        $completionTrendData = $this->getCompletionTrendChartData($priestRecord->id, $currentYear);
        $serviceFrequencyData = $this->getServiceFrequencyChartData($priestRecord->id);

        return view('priest.dashboard', compact(
            'bookingStats',
            'recentActivities', 
            'recentBookings',
            'monthlyStats',
            'performanceMetrics',
            'upcomingBookings',
            'monthlyBookingsData',
            'serviceTypeData',
            'bookingStatusData',
            'weeklyPerformanceData',
            'completionTrendData',
            'serviceFrequencyData'
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
                ->where('action_type', BookingAction::ACTION_ACKNOWLEDGED)
                ->where('performed_by', auth()->id())
                ->first();

            if ($acknowledgedAction) {
                $completedAction = $booking->actions()
                    ->where('action_type', BookingAction::ACTION_COMPLETED)
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

    private function getMonthlyBookingsChartData($priestId, $year)
    {
        $bookings = Booking::where('priest_id', $priestId)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = $bookings[$i] ?? 0;
        }

        return [
            'labels' => $months,
            'data' => $data,
            'isEmpty' => array_sum($data) === 0
        ];
    }

    private function getServiceTypeChartData($priestId)
    {
        $services = Booking::where('priest_id', $priestId)
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->selectRaw('services.service_type, COUNT(*) as count')
            ->groupBy('services.service_type')
            ->pluck('count', 'service_type')
            ->toArray();

        $labels = ['Baptism', 'Wedding', 'Blessing', 'Funeral', 'Confession', 'Other'];
        $data = [];
        $colors = ['#0d5c2f', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444'];

        $serviceMapping = [
            'baptism' => 'Baptism',
            'wedding' => 'Wedding',
            'blessing' => 'Blessing',
            'funeral' => 'Funeral',
            'confession' => 'Confession',
            'other' => 'Other'
        ];

        foreach ($labels as $label) {
            $serviceKey = array_search($label, $serviceMapping);
            $data[] = $services[$serviceKey] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
            'isEmpty' => array_sum($data) === 0
        ];
    }

    private function getBookingStatusChartData($priestId)
    {
        $statuses = Booking::where('priest_id', $priestId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = ['Pending', 'Acknowledged', 'Payment Hold', 'Approved', 'Completed', 'Rejected'];
        $data = [];
        $colors = ['#f59e0b', '#3b82f6', '#f97316', '#10b981', '#8b5cf6', '#ef4444'];

        $statusMapping = [
            'pending' => 'Pending',
            'acknowledged' => 'Acknowledged',
            'payment_hold' => 'Payment Hold',
            'approved' => 'Approved',
            'completed' => 'Completed',
            'rejected' => 'Rejected'
        ];

        foreach ($labels as $label) {
            $statusKey = array_search($label, $statusMapping);
            $data[] = $statuses[$statusKey] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
            'isEmpty' => array_sum($data) === 0
        ];
    }

    private function getWeeklyPerformanceChartData($priestId)
    {
        $weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $completedData = [];
        $assignedData = [];

        for ($i = 0; $i < 7; $i++) {
            $startOfWeek = Carbon::now()->startOfWeek()->addDays($i);
            $endOfWeek = $startOfWeek->copy()->endOfDay();

            $completed = Booking::where('priest_id', $priestId)
                ->where('status', 'completed')
                ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                ->count();

            $assigned = Booking::where('priest_id', $priestId)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();

            $completedData[] = $completed;
            $assignedData[] = $assigned;
        }

        return [
            'labels' => $weekDays,
            'datasets' => [
                [
                    'label' => 'Completed',
                    'data' => $completedData,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                    'borderColor' => '#10b981'
                ],
                [
                    'label' => 'Assigned',
                    'data' => $assignedData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => '#3b82f6'
                ]
            ],
            'isEmpty' => (array_sum($completedData) + array_sum($assignedData)) === 0
        ];
    }

    private function getCompletionTrendChartData($priestId, $year)
    {
        $completions = Booking::where('priest_id', $priestId)
            ->where('status', 'completed')
            ->selectRaw('MONTH(updated_at) as month, COUNT(*) as count')
            ->whereYear('updated_at', $year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = $completions[$i] ?? 0;
        }

        return [
            'labels' => $months,
            'data' => $data,
            'isEmpty' => array_sum($data) === 0
        ];
    }

    private function getServiceFrequencyChartData($priestId)
    {
        $frequencies = Booking::where('priest_id', $priestId)
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->selectRaw('services.name, COUNT(*) as count')
            ->groupBy('services.id', 'services.name')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->pluck('count', 'name')
            ->toArray();

        $labels = array_keys($frequencies);
        $data = array_values($frequencies);
        $colors = ['#0d5c2f', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'];

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
            'isEmpty' => array_sum($data) === 0
        ];
    }
} 