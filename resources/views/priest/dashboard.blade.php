@extends('layouts.priest')

@section('title', 'Priest Dashboard')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Welcome, {{ auth()->user()->priest->name ?? auth()->user()->name }}</h1>
                    <p class="text-white/80 mt-1">View your assigned bookings and services</p>
                </div>
                <div class="p-3 bg-white/20 rounded-lg">
                    <i class="fas fa-cross text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Assigned</p>
                    <p class="text-3xl font-bold">{{ $bookingStats['total_assigned'] }}</p>
                </div>
                <div class="p-3 bg-white/20 rounded-lg">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Pending Attention</p>
                    <p class="text-3xl font-bold">{{ $monthlyStats['pending_attention'] }}</p>
                </div>
                <div class="p-3 bg-white/20 rounded-lg">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Completed This Month</p>
                    <p class="text-3xl font-bold">{{ $monthlyStats['completed_this_month'] }}</p>
                </div>
                <div class="p-3 bg-white/20 rounded-lg">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Completion Rate</p>
                    <p class="text-3xl font-bold">{{ $performanceMetrics['completion_rate'] }}%</p>
                </div>
                <div class="p-3 bg-white/20 rounded-lg">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming Bookings -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-calendar-day mr-2 text-[#0d5c2f]"></i>
                        Upcoming Bookings (Next 7 Days)
                    </h2>
                </div>
                <div class="p-6">
                    @if($upcomingBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($upcomingBookings as $booking)
                            <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">#{{ $booking->id }} - {{ $booking->service->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $booking->user->name }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($booking->status === 'approved') bg-green-100 text-green-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Date:</span>
                                        <p class="font-medium">{{ $booking->formatted_date }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Time:</span>
                                        <p class="font-medium">{{ $booking->formatted_time }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <a href="{{ route('priest.bookings.show', $booking) }}" 
                                       class="text-[#0d5c2f] hover:text-[#0d5c2f]/80 text-sm font-medium">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No upcoming bookings for the next 7 days</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-[#0d5c2f]"></i>
                        Performance Metrics
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Total Services Conducted</span>
                        <span class="text-lg font-semibold text-gray-900">{{ $performanceMetrics['total_services_conducted'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Avg. Processing Time</span>
                        <span class="text-lg font-semibold text-gray-900">{{ $performanceMetrics['average_processing_time'] }}h</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Assigned This Month</span>
                        <span class="text-lg font-semibold text-gray-900">{{ $monthlyStats['assigned_this_month'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-history mr-2 text-[#0d5c2f]"></i>
                        Recent Activities
                    </h3>
                </div>
                <div class="p-6">
                    @if($recentActivities->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentActivities->take(5) as $activity)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center">
                                        <i class="fas fa-{{ $activity->action === 'completed' ? 'check' : ($activity->action === 'acknowledged' ? 'eye' : 'edit') }} text-[#0d5c2f] text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ ucfirst($activity->action) }} booking #{{ $activity->booking->id }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No recent activities</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Status Overview -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-list-alt mr-2 text-[#0d5c2f]"></i>
                Booking Status Overview
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600">{{ $bookingStats['pending'] }}</div>
                    <div class="text-sm text-yellow-700">Pending</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $bookingStats['acknowledged'] }}</div>
                    <div class="text-sm text-blue-700">Acknowledged</div>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600">{{ $bookingStats['payment_hold'] }}</div>
                    <div class="text-sm text-orange-700">Payment Hold</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ $bookingStats['approved'] }}</div>
                    <div class="text-sm text-green-700">Approved</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ $bookingStats['completed'] }}</div>
                    <div class="text-sm text-purple-700">Completed</div>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <div class="text-2xl font-bold text-red-600">{{ $bookingStats['rejected'] }}</div>
                    <div class="text-sm text-red-700">Rejected</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 