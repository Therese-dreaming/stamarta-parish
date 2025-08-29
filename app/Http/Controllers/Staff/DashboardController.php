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
            // Monthly-specific statistics
            'acknowledged_this_month' => BookingAction::where('performed_by', $user->id)
                ->where('action_type', 'acknowledged')
                ->whereMonth('created_at', $thisMonth->month)
                ->whereYear('created_at', $thisMonth->year)->count(),
            'approved_this_month' => BookingAction::where('performed_by', $user->id)
                ->where('action_type', 'approved')
                ->whereMonth('created_at', $thisMonth->month)
                ->whereYear('created_at', $thisMonth->year)->count(),
            'rejected_this_month' => BookingAction::where('performed_by', $user->id)
                ->where('action_type', 'rejected')
                ->whereMonth('created_at', $thisMonth->month)
                ->whereYear('created_at', $thisMonth->year)->count(),
            'completed_this_month' => BookingAction::where('performed_by', $user->id)
                ->where('action_type', 'completed')
                ->whereMonth('created_at', $thisMonth->month)
                ->whereYear('created_at', $thisMonth->year)->count(),
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
            // Additional CMS data for My Pages tab
            'pages_created' => Page::where('created_by', $user->id)
                ->whereMonth('created_at', $thisMonth->month)
                ->whereYear('created_at', $thisMonth->year)->count(),
            'posts_published' => Page::where('created_by', $user->id)
                ->where('is_published', true)
                ->whereMonth('created_at', $thisMonth->month)
                ->whereYear('created_at', $thisMonth->year)->count(),
            'comments_approved' => 0, // Placeholder - add if you have a comments system
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
            'success_rate' => $this->calculateSuccessRate($user->id),
            'fastest_processing' => $this->calculateFastestProcessingTime($user->id),
            'monthly_avg_time' => $this->calculateMonthlyAverageTime($user->id),
            'time_improvement' => $this->calculateTimeImprovement($user->id),
            'speed_score' => $this->calculateSpeedScore($user->id),
            'quality_score' => $this->calculateQualityScore($user->id),
            'consistency_score' => $this->calculateConsistencyScore($user->id),
        ];

        // Weekly activity for the last 7 days
        $weeklyActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklyActivity[] = [
                'day' => $date->format('D'),
                'actions' => BookingAction::where('performed_by', $user->id)
                    ->whereDate('created_at', $date)
                    ->count(),
            ];
        }

        // Service distribution for this staff member
        $serviceDistribution = $this->calculateServiceDistribution($user->id);

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
            'weeklyActivity',
            'actionDistribution',
            'performanceMetrics',
            'serviceDistribution',
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

    private function calculateSuccessRate($userId)
    {
        // Calculate success rate based on completed vs total processed
        $completed = BookingAction::where('performed_by', $userId)
            ->where('action_type', 'completed')->count();
        $total = BookingAction::where('performed_by', $userId)->count();
        
        if ($total === 0) {
            return 100;
        }

        return round(($completed / $total) * 100, 1);
    }

    private function calculateFastestProcessingTime($userId)
    {
        $actions = BookingAction::where('performed_by', $userId)
            ->with('booking')
            ->get();

        if ($actions->isEmpty()) {
            return 0;
        }

        $fastestTime = PHP_INT_MAX;
        foreach ($actions as $action) {
            if ($action->booking) {
                $timeDiff = $action->created_at->diffInMinutes($action->booking->created_at);
                if ($timeDiff < $fastestTime) {
                    $fastestTime = $timeDiff;
                }
            }
        }

        return $fastestTime === PHP_INT_MAX ? 0 : $fastestTime;
    }

    private function calculateMonthlyAverageTime($userId)
    {
        $thisMonth = Carbon::now()->startOfMonth();
        
        $actions = BookingAction::where('performed_by', $userId)
            ->whereMonth('created_at', $thisMonth->month)
            ->whereYear('created_at', $thisMonth->year)
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

    private function calculateTimeImprovement($userId)
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth();
        
        $thisMonthAvg = $this->calculateMonthlyAverageTime($userId);
        
        // Calculate last month's average
        $lastMonthActions = BookingAction::where('performed_by', $userId)
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->with('booking')
            ->get();

        if ($lastMonthActions->isEmpty()) {
            return 0;
        }

        $lastMonthTotalTime = 0;
        $lastMonthCount = 0;

        foreach ($lastMonthActions as $action) {
            if ($action->booking) {
                $timeDiff = $action->created_at->diffInMinutes($action->booking->created_at);
                $lastMonthTotalTime += $timeDiff;
                $lastMonthCount++;
            }
        }

        $lastMonthAvg = $lastMonthCount > 0 ? $lastMonthTotalTime / $lastMonthCount : 0;
        
        if ($lastMonthAvg === 0) {
            return 0;
        }

        return round((($lastMonthAvg - $thisMonthAvg) / $lastMonthAvg) * 100, 1);
    }

    private function calculateSpeedScore($userId)
    {
        $avgTime = $this->calculateAverageProcessingTime($userId);
        
        // Score based on processing time (lower is better)
        if ($avgTime <= 30) return 100;
        if ($avgTime <= 60) return 85;
        if ($avgTime <= 120) return 70;
        if ($avgTime <= 240) return 55;
        if ($avgTime <= 480) return 40;
        return 25;
    }

    private function calculateQualityScore($userId)
    {
        // Quality based on low rejection rate and high completion rate
        $total = BookingAction::where('performed_by', $userId)->count();
        $rejected = BookingAction::where('performed_by', $userId)
            ->where('action_type', 'rejected')->count();
        $completed = BookingAction::where('performed_by', $userId)
            ->where('action_type', 'completed')->count();
        
        if ($total === 0) {
            return 100;
        }

        $rejectionRate = ($rejected / $total) * 100;
        $completionRate = ($completed / $total) * 100;
        
        // Quality score: high completion rate and low rejection rate
        $qualityScore = ($completionRate * 0.7) + ((100 - $rejectionRate) * 0.3);
        
        return round($qualityScore, 1);
    }

    private function calculateConsistencyScore($userId)
    {
        // Consistency based on daily activity patterns
        $dailyActivity = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyActivity[] = BookingAction::where('performed_by', $userId)
                ->whereDate('created_at', $date)
                ->count();
        }

        if (empty($dailyActivity)) {
            return 100;
        }

        $avgActions = array_sum($dailyActivity) / count($dailyActivity);
        $variance = 0;
        
        foreach ($dailyActivity as $actions) {
            $variance += pow($actions - $avgActions, 2);
        }
        
        $variance = $variance / count($dailyActivity);
        $stdDev = sqrt($variance);
        
        // Consistency score: lower standard deviation = higher consistency
        if ($avgActions === 0) {
            return 100;
        }
        
        $coefficientOfVariation = ($stdDev / $avgActions) * 100;
        
        if ($coefficientOfVariation <= 20) return 100;
        if ($coefficientOfVariation <= 40) return 85;
        if ($coefficientOfVariation <= 60) return 70;
        if ($coefficientOfVariation <= 80) return 55;
        if ($coefficientOfVariation <= 100) return 40;
        return 25;
    }

    private function calculateServiceDistribution($userId)
    {
        $actions = BookingAction::where('performed_by', $userId)
            ->with('booking.service')
            ->get();

        $serviceCounts = [];
        
        foreach ($actions as $action) {
            if ($action->booking && $action->booking->service) {
                $serviceName = $action->booking->service->name;
                if (!isset($serviceCounts[$serviceName])) {
                    $serviceCounts[$serviceName] = 0;
                }
                $serviceCounts[$serviceName]++;
            }
        }

        // Sort by count descending and limit to top 6
        arsort($serviceCounts);
        return array_slice($serviceCounts, 0, 6, true);
    }
} 