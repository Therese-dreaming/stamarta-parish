<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Media;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Priest;
use App\Models\Service;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Booking statistics
        $bookingStats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'acknowledged_bookings' => Booking::where('status', 'acknowledged')->count(),
            'payment_hold_bookings' => Booking::where('status', 'payment_hold')->count(),
            'approved_bookings' => Booking::where('status', 'approved')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'rejected_bookings' => Booking::where('status', 'rejected')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            'recent_bookings' => Booking::with(['user', 'service'])->latest()->take(5)->get(),
        ];

        // Monthly booking trends (last 6 months)
        $monthlyBookings = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyBookings[] = [
                'month' => $date->format('M Y'),
                'count' => Booking::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            ];
        }

        // Service popularity
        $serviceStats = Service::withCount('bookings')->orderBy('bookings_count', 'desc')->take(5)->get();

        // Finance statistics
        $financeStats = [
            'total_revenue' => BookingPayment::where('payment_status', 'verified')->sum('total_fee'),
            'monthly_revenue' => BookingPayment::where('payment_status', 'verified')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->sum('total_fee'),
            'pending_payments' => BookingPayment::where('payment_status', 'paid')->sum('total_fee'),
            'avg_transaction' => BookingPayment::where('payment_status', 'verified')->avg('total_fee') ?? 0,
            'gcash_payments' => BookingPayment::where('payment_method', 'gcash')->count(),
            'metrobank_payments' => BookingPayment::where('payment_method', 'metrobank')->count(),
            'recent_transactions' => BookingPayment::with('booking')->latest()->take(5)->get(),
        ];

        // Monthly revenue trends (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'amount' => BookingPayment::where('payment_status', 'verified')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('total_fee')
            ];
        }

        // User statistics
        $userStats = [
            'total_users' => User::count(),
            'new_users_month' => User::whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->count(),
            'active_users' => User::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
            'recent_users' => User::latest()->take(5)->get(),
        ];

        // Monthly user registration trends (last 6 months)
        $monthlyUsers = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyUsers[] = [
                'month' => $date->format('M Y'),
                'count' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            ];
        }

        // Role distribution
        $roleDistribution = [
            'user' => User::where('role', 'user')->count(),
            'staff' => User::where('role', 'staff')->count(),
            'priest' => User::where('role', 'priest')->count(),
            'admin' => User::where('role', 'admin')->count(),
        ];

        // System statistics
        $systemStats = [
            'total_pages' => Page::count(),
            'published_pages' => Page::published()->count(),
            'draft_pages' => Page::draft()->count(),
            'total_media' => Media::count(),
            'total_priests' => Priest::count(),
            'total_services' => Service::count(),
            'recent_pages' => Page::with('creator')->latest()->take(5)->get(),
            'recent_media' => Media::with('uploader')->latest()->take(5)->get(),
        ];

        // Today's statistics
        $todayStats = [
            'new_bookings' => Booking::whereDate('created_at', Carbon::today())->count(),
            'new_users' => User::whereDate('created_at', Carbon::today())->count(),
            'new_revenue' => BookingPayment::where('payment_status', 'verified')
                ->whereDate('created_at', Carbon::today())
                ->sum('total_fee'),
        ];

        $stats = array_merge($bookingStats, $financeStats, $userStats, $systemStats, $todayStats);

        return view('admin.dashboard', compact(
            'stats', 
            'monthlyBookings', 
            'monthlyRevenue', 
            'monthlyUsers', 
            'serviceStats', 
            'roleDistribution'
        ));
    }
} 