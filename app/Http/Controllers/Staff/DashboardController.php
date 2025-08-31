<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingAction;
use App\Models\Page;
use App\Models\Media;
use App\Models\Priest;
use App\Models\Service;
use App\Models\ServiceRating;
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

        // Service popularity with ratings (for services this staff has processed)
        $serviceStats = Service::withCount('bookings')
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($service) {
                $service->average_rating = $service->ratings_avg_rating ? round($service->ratings_avg_rating, 1) : 0;
                $service->total_ratings = $service->ratings_count;
                return $service;
            });

        // Rating statistics for services this staff has processed
        $ratingStats = [
            'total_ratings' => ServiceRating::count(),
            'average_rating' => ServiceRating::avg('rating') ? round(ServiceRating::avg('rating'), 1) : 0,
            'rated_services' => Service::whereHas('ratings')->count(),
            'unrated_services' => Service::whereDoesntHave('ratings')->count(),
            'top_rated_services' => Service::withCount('ratings')
                ->withAvg('ratings', 'rating')
                ->having('ratings_count', '>', 0)
                ->orderBy('ratings_avg_rating', 'desc')
                ->take(3)
                ->get()
                ->map(function ($service) {
                    $service->average_rating = round($service->ratings_avg_rating, 1);
                    return $service;
                }),
            'recent_ratings' => ServiceRating::with(['user', 'service'])
                ->latest()
                ->take(5)
                ->get(),
            'rating_distribution' => [
                '1_star' => ServiceRating::where('rating', 1)->count(),
                '2_star' => ServiceRating::where('rating', 2)->count(),
                '3_star' => ServiceRating::where('rating', 3)->count(),
                '4_star' => ServiceRating::where('rating', 4)->count(),
                '5_star' => ServiceRating::where('rating', 5)->count(),
            ],
            'new_ratings_today' => ServiceRating::whereDate('created_at', $today)->count(),
        ];

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
            'serviceDistribution',
            'ratingStats',
            'user'
        ));
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