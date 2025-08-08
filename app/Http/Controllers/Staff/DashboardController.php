<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingAction;
use App\Models\Page;
use App\Models\Media;
use App\Models\Priest;
use App\Models\Service;
use App\Models\ParochialActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'staff']);
    }

    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth();

        // Staff-specific statistics (bookings they've processed)
        $staffStats = [
            'total_processed' => BookingAction::where('performed_by', $user->id)->count(),
            'acknowledged_by_me' => BookingAction::where('performed_by', $user->id)
                ->where('action_type', 'acknowledged')->count(),
            'approved_by_me' => BookingAction::where('performed_by', $user->id)
                ->where('action_type', 'approved')->count(),
            'rejected_by_me' => BookingAction::where('performed_by', $user->id)
                ->where('action_type', 'rejected')->count(),
            'completed_by_me' => BookingAction::where('performed_by', $user->id)
                ->where('action_type', 'completed')->count(),
            'processed_today' => BookingAction::where('performed_by', $user->id)
                ->whereDate('created_at', $today)->count(),
            'processed_this_month' => BookingAction::where('performed_by', $user->id)
                ->whereMonth('created_at', $thisMonth->month)
                ->whereYear('created_at', $thisMonth->year)->count(),
            'processed_last_month' => BookingAction::where('performed_by', $user->id)
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)->count(),
        ];

        // Current booking statistics (what they can see)
        $bookingStats = [
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'acknowledged_bookings' => Booking::where('status', 'acknowledged')->count(),
            'payment_hold_bookings' => Booking::where('status', 'payment_hold')->count(),
            'approved_bookings' => Booking::where('status', 'approved')->count(),
            'rejected_bookings' => Booking::where('status', 'rejected')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'total_bookings' => Booking::count(),
        ];

        // Recent activities performed by this staff member
        $recentActivities = BookingAction::with(['booking.user', 'booking.service'])
            ->where('performed_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent bookings that need attention
        $recentBookings = Booking::with(['user', 'service'])
            ->whereIn('status', ['pending', 'acknowledged', 'payment_hold'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // CMS Statistics (content created by this staff member)
        $cmsStats = [
            'pages_created_by_me' => Page::where('created_by', $user->id)->count(),
            'media_uploaded_by_me' => Media::where('uploaded_by', $user->id)->count(),
            'recent_pages_created' => Page::where('created_by', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),
            'recent_media_uploaded' => Media::where('uploaded_by', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),
        ];

        // Parochial Activities (created by this staff member)
        $parochialStats = [
            'activities_created_by_me' => ParochialActivity::where('created_by', $user->id)->count(),
            'active_activities_by_me' => ParochialActivity::where('created_by', $user->id)
                ->where('status', 'active')->count(),
            'upcoming_activities_by_me' => ParochialActivity::where('created_by', $user->id)
                ->where('event_date', '>=', $today)->count(),
            'recent_activities_created' => ParochialActivity::where('created_by', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),
        ];

        // Service popularity (for reference)
        $serviceStats = Service::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        // Monthly activity trends for this staff member (last 6 months)
        $monthlyActivity = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyActivity[] = [
                'month' => $date->format('M Y'),
                'actions' => BookingAction::where('performed_by', $user->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }

        // Daily activity for the last 7 days
        $dailyActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyActivity[] = [
                'date' => $date->format('M d'),
                'actions' => BookingAction::where('performed_by', $user->id)
                    ->whereDate('created_at', $date)
                    ->count(),
            ];
        }

        // Action type distribution for this staff member
        $actionDistribution = [
            'acknowledged' => BookingAction::where('performed_by', $user->id)
                ->where('action_type', 'acknowledged')->count(),
            'approved' => BookingAction::where('performed_by', $user->id)
                ->where('action_type', 'approved')->count(),
            'rejected' => BookingAction::where('performed_by', $user->id)
                ->where('action_type', 'rejected')->count(),
            'completed' => BookingAction::where('performed_by', $user->id)
                ->where('action_type', 'completed')->count(),
        ];

        // Performance metrics
        $performanceMetrics = [
            'avg_processing_time' => $this->calculateAverageProcessingTime($user->id),
            'efficiency_rating' => $this->calculateEfficiencyRating($user->id),
            'accuracy_rate' => $this->calculateAccuracyRate($user->id),
        ];

        return view('staff.dashboard', compact(
            'staffStats',
            'bookingStats',
            'recentActivities',
            'recentBookings',
            'cmsStats',
            'parochialStats',
            'serviceStats',
            'monthlyActivity',
            'dailyActivity',
            'actionDistribution',
            'performanceMetrics',
            'user'
        ));
    }

    private function calculateAverageProcessingTime($userId)
    {
        // Calculate average time between booking creation and first action by this staff member
        $actions = BookingAction::where('performed_by', $userId)
            ->with('booking')
            ->get();

        if ($actions->isEmpty()) {
            return 0;
        }

        $totalTime = 0;
        $count = 0;

        foreach ($actions as $action) {
            if ($action->booking) {
                $timeDiff = $action->created_at->diffInMinutes($action->booking->created_at);
                $totalTime += $timeDiff;
                $count++;
            }
        }

        return $count > 0 ? round($totalTime / $count, 1) : 0;
    }

    private function calculateEfficiencyRating($userId)
    {
        // Calculate efficiency based on actions per day
        $actionsThisMonth = BookingAction::where('performed_by', $userId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $daysInMonth = Carbon::now()->daysInMonth;
        $actionsPerDay = $actionsThisMonth / $daysInMonth;

        // Simple rating system: 0-5 actions/day = 1-5 stars
        if ($actionsPerDay >= 5) return 5;
        if ($actionsPerDay >= 4) return 4;
        if ($actionsPerDay >= 3) return 3;
        if ($actionsPerDay >= 2) return 2;
        if ($actionsPerDay >= 1) return 1;
        return 0;
    }

    private function calculateAccuracyRate($userId)
    {
        // Calculate accuracy based on ratio of approved vs rejected bookings
        $approved = BookingAction::where('performed_by', $userId)
            ->where('action_type', 'approved')->count();
        $rejected = BookingAction::where('performed_by', $userId)
            ->where('action_type', 'rejected')->count();

        $total = $approved + $rejected;
        
        if ($total === 0) {
            return 100; // No decisions made yet
        }

        // Higher approval rate might indicate better accuracy (assuming most bookings should be approved)
        return round(($approved / $total) * 100, 1);
    }
} 