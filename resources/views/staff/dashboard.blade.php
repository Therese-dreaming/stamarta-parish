@extends('layouts.staff')

@section('title', 'Staff Dashboard')

@section('content')
<div class="font-[Poppins]">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $user->name }}!</h1>
        <p class="text-gray-600">Here's your personal performance overview and recent activities</p>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button onclick="showTab('overview')" id="tab-overview" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-[#0d5c2f] text-[#0d5c2f]">
                    <i class="fas fa-chart-pie mr-2"></i>My Performance
                </button>
                <button onclick="showTab('activities')" id="tab-activities" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-history mr-2"></i>Recent Activities
                </button>
                <button onclick="showTab('bookings')" id="tab-bookings" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-bookmark mr-2"></i>My Bookings
                </button>
                <button onclick="showTab('pages')" id="tab-pages" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-file-alt mr-2"></i>My Pages
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Content -->
    <div id="tab-content">
        <!-- Overview Tab -->
        <div id="overview-tab" class="tab-content">
            <!-- Key Performance Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- This Month's Actions -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-sm border border-blue-200 p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-calendar-check text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-100">This Month</p>
                            <p class="text-2xl font-bold">{{ $staffStats['processed_this_month'] }}</p>
                            <p class="text-xs text-blue-100">Actions</p>
                        </div>
                    </div>
                </div>

                <!-- Efficiency Rating -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-sm border border-green-200 p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-star text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-100">Efficiency</p>
                            <p class="text-2xl font-bold">{{ $performanceMetrics['efficiency_rating'] }}/5</p>
                            <div class="flex mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $performanceMetrics['efficiency_rating'] ? 'text-yellow-300' : 'text-gray-300' }} text-sm"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accuracy Rate -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-sm border border-purple-200 p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-bullseye text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-purple-100">Accuracy</p>
                            <p class="text-2xl font-bold">{{ $performanceMetrics['accuracy_rate'] }}%</p>
                            <p class="text-xs text-purple-100">Rate</p>
                        </div>
                    </div>
                </div>

                <!-- Average Processing Time -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-sm border border-orange-200 p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-orange-100">Avg. Time</p>
                            <p class="text-2xl font-bold">{{ $performanceMetrics['avg_processing_time'] }}</p>
                            <p class="text-xs text-orange-100">Minutes</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Actions This Month -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Acknowledged -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-check text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Acknowledged</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $staffStats['acknowledged_this_month'] }}</p>
                            <p class="text-xs text-gray-500">This month</p>
                        </div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-thumbs-up text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Approved</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $staffStats['approved_this_month'] }}</p>
                            <p class="text-xs text-gray-500">This month</p>
                        </div>
                    </div>
                </div>

                <!-- Rejected -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <i class="fas fa-times text-red-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Rejected</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $staffStats['rejected_this_month'] }}</p>
                            <p class="text-xs text-gray-500">This month</p>
                        </div>
                    </div>
                </div>

                <!-- Completed -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i class="fas fa-flag-checkered text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Completed</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $staffStats['completed_this_month'] }}</p>
                            <p class="text-xs text-gray-500">This month</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Weekly Activity Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">My Weekly Activity</h2>
                        <p class="text-sm text-gray-600">Actions performed this week</p>
                    </div>
                    <div class="p-6">
                        <canvas id="weeklyActivityChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Action Distribution Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">My Action Distribution</h2>
                        <p class="text-sm text-gray-600">This month's breakdown</p>
                    </div>
                    <div class="p-6">
                        <canvas id="actionDistributionChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Performance Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Performance Metrics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Performance Details</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900">Total Actions (All Time)</span>
                                <span class="text-sm text-gray-600">{{ $staffStats['total_processed'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900">Last Month's Actions</span>
                                <span class="text-sm text-gray-600">{{ $staffStats['processed_last_month'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900">Today's Actions</span>
                                <span class="text-sm text-gray-600">{{ $staffStats['processed_today'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900">Success Rate</span>
                                <span class="text-sm text-gray-600">{{ $performanceMetrics['success_rate'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Performance Trend -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Monthly Performance Trend</h2>
                        <p class="text-sm text-gray-600">Your actions over the past 6 months</p>
                    </div>
                    <div class="p-6">
                        <canvas id="monthlyTrendChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Service Type Distribution -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Service Type Distribution</h2>
                        <p class="text-sm text-gray-600">Bookings you've processed by service type</p>
                    </div>
                    <div class="p-6">
                        <canvas id="serviceDistributionChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Daily Activity Progress -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Daily Activity (Last 7 Days)</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($dailyActivity as $day)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $day['date'] }}</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                        @php
                                            $maxActions = max(array_column($dailyActivity, 'actions'));
                                            $percentage = $maxActions > 0 ? ($day['actions'] / $maxActions) * 100 : 0;
                                        @endphp
                                        <div class="bg-[#0d5c2f] h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $day['actions'] }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Tab -->
        <div id="activities-tab" class="tab-content hidden">
            <!-- Recent Activities -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">My Recent Activities</h2>
                    <p class="text-sm text-gray-600">Your latest actions and decisions</p>
                </div>
                <div class="p-6">
                    @if($recentActivities->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentActivities as $activity)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 rounded-lg bg-{{ $activity->action_color }}-100">
                                        <i class="{{ $activity->action_icon }} text-{{ $activity->action_color }}-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ $activity->action_type_label }} Booking #{{ $activity->booking_id }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ $activity->booking->service->name ?? 'Unknown Service' }} - 
                                            {{ $activity->booking->user->name ?? 'Unknown User' }}
                                        </p>
                                        @if($activity->notes)
                                            <p class="text-xs text-gray-500 mt-1">{{ $activity->notes }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity->action_type_label }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-6 text-center">
                            <a href="{{ route('staff.bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                                <i class="fas fa-eye mr-2"></i>View All Bookings
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">No recent activities found</p>
                            <p class="text-sm text-gray-400 mt-2">Start processing bookings to see your activities here</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bookings Tab -->
        <div id="bookings-tab" class="tab-content hidden">
            <!-- My Booking Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $bookingStats['pending_bookings'] }}</p>
                            <p class="text-xs text-gray-500">Need attention</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-check text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Acknowledged</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $bookingStats['acknowledged_bookings'] }}</p>
                            <p class="text-xs text-gray-500">By me</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 rounded-lg">
                            <i class="fas fa-money-bill text-orange-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Payment Hold</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $bookingStats['payment_hold_bookings'] }}</p>
                            <p class="text-xs text-gray-500">Awaiting payment</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-thumbs-up text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Approved</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $bookingStats['approved_bookings'] }}</p>
                            <p class="text-xs text-gray-500">By me</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings Needing My Attention -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Bookings Needing My Attention</h2>
                    <p class="text-sm text-gray-600">Bookings that require your action</p>
                </div>
                <div class="p-6">
                    @if($recentBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBookings as $booking)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 rounded-full 
                                        {{ $booking->status === 'pending' ? 'bg-yellow-500' : 
                                           ($booking->status === 'acknowledged' ? 'bg-blue-500' : 'bg-orange-500') }}">
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">#{{ $booking->id }} - {{ $booking->service->name ?? 'Unknown Service' }}</p>
                                        <p class="text-sm text-gray-600">{{ $booking->user->name ?? 'Unknown User' }}</p>
                                        <p class="text-xs text-gray-500">{{ $booking->service_date->format('M d, Y') }} at {{ $booking->service_time }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($booking->status === 'acknowledged' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">{{ $booking->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-6 text-center">
                            <a href="{{ route('staff.bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                                <i class="fas fa-list mr-2"></i>View All Bookings
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-green-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">No bookings need attention</p>
                            <p class="text-sm text-gray-400 mt-2">Great job! All bookings are up to date</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pages Tab -->
        <div id="pages-tab" class="tab-content hidden">
            <!-- CMS Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pages Created</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $cmsStats['pages_created_by_me'] }}</p>
                            <p class="text-xs text-gray-500">Total</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-image text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Media Uploaded</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $cmsStats['media_uploaded_by_me'] }}</p>
                            <p class="text-xs text-gray-500">Total</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Activities Created</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $parochialStats['activities_created_by_me'] }}</p>
                            <p class="text-xs text-gray-500">Total</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 rounded-lg">
                            <i class="fas fa-star text-orange-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Active Activities</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $parochialStats['active_activities_by_me'] }}</p>
                            <p class="text-xs text-gray-500">Current</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Pages -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Pages Created</h2>
                        <p class="text-sm text-gray-600">Your latest page content</p>
                    </div>
                    <div class="p-6">
                        @if($cmsStats['recent_pages_created']->count() > 0)
                            <div class="space-y-3">
                                @foreach($cmsStats['recent_pages_created'] as $page)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $page->title }}</p>
                                        <p class="text-xs text-gray-600">{{ $page->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $page->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $page->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-file-alt text-gray-400 text-4xl mb-4"></i>
                                <p class="text-gray-500">No pages created yet</p>
                                <p class="text-sm text-gray-400 mt-2">Start creating content to see your pages here</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Media -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Media Uploaded</h2>
                        <p class="text-sm text-gray-600">Your latest media files</p>
                    </div>
                    <div class="p-6">
                        @if($cmsStats['recent_media_uploaded']->count() > 0)
                            <div class="space-y-3">
                                @foreach($cmsStats['recent_media_uploaded'] as $media)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-image text-gray-500 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $media->filename }}</p>
                                            <p class="text-xs text-gray-600">{{ $media->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $media->file_size }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-image text-gray-400 text-4xl mb-4"></i>
                                <p class="text-gray-500">No media uploaded yet</p>
                                <p class="text-sm text-gray-400 mt-2">Start uploading files to see your media here</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 text-center">
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('staff.pages.index') }}" class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Manage Pages
                    </a>
                    <a href="{{ route('staff.cms.media.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-image mr-2"></i>Manage Media
                    </a>
                    <a href="{{ route('staff.parochial-activities.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-calendar mr-2"></i>Manage Activities
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Chart.js configuration
const chartColors = {
    primary: '#0d5c2f',
    secondary: '#6b7280',
    success: '#10b981',
    warning: '#f59e0b',
    danger: '#ef4444',
    info: '#3b82f6',
    purple: '#8b5cf6',
    orange: '#f97316'
};

// Initialize charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    showTab('overview');
    initializeCharts();
});

function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active state from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('border-[#0d5c2f]', 'text-[#0d5c2f]');
        button.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');

    // Add active state to selected tab button
    document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-gray-500');
    document.getElementById('tab-' + tabName).classList.add('border-[#0d5c2f]', 'text-[#0d5c2f]');
}

function initializeCharts() {
    // Weekly Activity Chart
    const weeklyActivityCtx = document.getElementById('weeklyActivityChart');
    if (weeklyActivityCtx) {
        new Chart(weeklyActivityCtx, {
            type: 'bar',
            data: {
                labels: @json(array_column($weeklyActivity, 'day')),
                datasets: [{
                    label: 'Actions',
                    data: @json(array_column($weeklyActivity, 'actions')),
                    backgroundColor: chartColors.primary,
                    borderColor: chartColors.primary,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Action Distribution Chart
    const actionDistributionCtx = document.getElementById('actionDistributionChart');
    if (actionDistributionCtx) {
        new Chart(actionDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Acknowledged', 'Approved', 'Rejected', 'Completed'],
                datasets: [{
                    data: [
                        {{ $actionDistribution['acknowledged'] }},
                        {{ $actionDistribution['approved'] }},
                        {{ $actionDistribution['rejected'] }},
                        {{ $actionDistribution['completed'] }}
                    ],
                    backgroundColor: [
                        chartColors.info,
                        chartColors.success,
                        chartColors.danger,
                        chartColors.purple
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Monthly Trend Chart
    const monthlyTrendCtx = document.getElementById('monthlyTrendChart');
    if (monthlyTrendCtx) {
        new Chart(monthlyTrendCtx, {
            type: 'line',
            data: {
                labels: @json(array_column($monthlyActivity, 'month')),
                datasets: [{
                    label: 'Actions',
                    data: @json(array_column($monthlyActivity, 'actions')),
                    borderColor: chartColors.primary,
                    backgroundColor: chartColors.primary + '20',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Service Distribution Chart
    const serviceDistributionCtx = document.getElementById('serviceDistributionChart');
    if (serviceDistributionCtx) {
        new Chart(serviceDistributionCtx, {
            type: 'pie',
            data: {
                labels: @json(array_keys($serviceDistribution)),
                datasets: [{
                    data: @json(array_values($serviceDistribution)),
                    backgroundColor: [
                        chartColors.primary,
                        chartColors.info,
                        chartColors.success,
                        chartColors.warning,
                        chartColors.danger,
                        chartColors.purple
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
}
</script>
@endsection 