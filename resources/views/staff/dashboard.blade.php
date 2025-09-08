@extends('layouts.staff')

@section('title', 'Staff Dashboard')

@section('content')
<div class="font-[Poppins]">
    <!-- Hero Header -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-8 relative">
            <div class="absolute right-0 top-0 w-24 h-24 bg-white/10 rounded-bl-full"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-tr-full"></div>
            <div class="relative z-10">
                <h1 class="text-4xl font-bold text-white mb-2">Welcome back, {{ $user->name }}!</h1>
                <p class="text-white/90 text-lg">Here's your personal performance overview and recent activities</p>
                <div class="mt-4 flex items-center space-x-6 text-white/80">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-check mr-2 text-lg"></i>
                        <span class="text-sm font-medium">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2 text-lg"></i>
                        <span class="text-sm font-medium">{{ now()->format('g:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <nav class="flex space-x-0" aria-label="Tabs">
                <button onclick="showTab('overview')" id="tab-overview" class="tab-button flex-1 py-4 px-6 text-center font-medium text-sm border-b-2 border-[#0d5c2f] text-[#0d5c2f] bg-[#0d5c2f]/5 transition-all duration-200">
                    <i class="fas fa-chart-pie mr-2"></i>My Performance
                </button>
                <button onclick="showTab('activities')" id="tab-activities" class="tab-button flex-1 py-4 px-6 text-center font-medium text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-history mr-2"></i>Recent Activities
                </button>
                <button onclick="showTab('bookings')" id="tab-bookings" class="tab-button flex-1 py-4 px-6 text-center font-medium text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-bookmark mr-2"></i>My Bookings
                </button>
                <button onclick="showTab('ratings')" id="tab-ratings" class="tab-button flex-1 py-4 px-6 text-center font-medium text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-star mr-2"></i>Service Ratings
                </button>
                <button onclick="showTab('pages')" id="tab-pages" class="tab-button flex-1 py-4 px-6 text-center font-medium text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6 mb-8">
                <!-- This Month's Actions -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg border border-blue-200 p-8 text-white relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-bl-full"></div>
                    <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/5 rounded-tr-full"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-4 bg-white/20 rounded-xl">
                                    <i class="fas fa-calendar-check text-white text-2xl"></i>
                                </div>
                                <div class="ml-6">
                                    <p class="text-lg font-medium text-blue-100">This Month's Performance</p>
                                    <p class="text-4xl font-bold">{{ $staffStats['processed_this_month'] }}</p>
                                    <p class="text-sm text-blue-100">Total Actions Processed</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-6xl font-bold text-white/20">{{ $staffStats['processed_this_month'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Actions This Month -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Acknowledged -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-4 bg-blue-100 rounded-xl group-hover:bg-blue-200 transition-colors duration-200">
                            <i class="fas fa-check text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Acknowledged</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $staffStats['acknowledged_this_month'] }}</p>
                            <p class="text-xs text-gray-500">This month</p>
                        </div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-4 bg-green-100 rounded-xl group-hover:bg-green-200 transition-colors duration-200">
                            <i class="fas fa-thumbs-up text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Approved</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $staffStats['approved_this_month'] }}</p>
                            <p class="text-xs text-gray-500">This month</p>
                        </div>
                    </div>
                </div>

                <!-- Rejected -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-4 bg-red-100 rounded-xl group-hover:bg-red-200 transition-colors duration-200">
                            <i class="fas fa-times text-red-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Rejected</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $staffStats['rejected_this_month'] }}</p>
                            <p class="text-xs text-gray-500">This month</p>
                        </div>
                    </div>
                </div>

                <!-- Completed -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-4 bg-purple-100 rounded-xl group-hover:bg-purple-200 transition-colors duration-200">
                            <i class="fas fa-flag-checkered text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Completed</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $staffStats['completed_this_month'] }}</p>
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
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-bar text-blue-600 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">My Weekly Activity</h2>
                                <p class="text-sm text-gray-600">Actions performed this week</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $hasWeeklyData = collect($weeklyActivity)->sum('actions') > 0;
                        @endphp
                        @if($hasWeeklyData)
                            <canvas id="weeklyActivityChart" width="400" height="200"></canvas>
                        @else
                            <div class="flex flex-col items-center justify-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-chart-bar text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Activity This Week</h3>
                                <p class="text-gray-500 text-center max-w-sm">You haven't performed any actions this week yet. Start processing bookings to see your activity here.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Distribution Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-pie text-green-600 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">My Action Distribution</h2>
                                <p class="text-sm text-gray-600">This month's breakdown</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $hasActionData = $actionDistribution['acknowledged'] + $actionDistribution['approved'] + $actionDistribution['rejected'] + $actionDistribution['completed'] > 0;
                        @endphp
                        @if($hasActionData)
                            <canvas id="actionDistributionChart" width="400" height="200"></canvas>
                        @else
                            <div class="flex flex-col items-center justify-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-chart-pie text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Actions This Month</h3>
                                <p class="text-gray-500 text-center max-w-sm">You haven't performed any actions this month yet. Start processing bookings to see your distribution here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Performance Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Monthly Performance Trend -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line text-purple-600 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Monthly Performance Trend</h2>
                                <p class="text-sm text-gray-600">Your actions over the past 6 months</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $hasMonthlyData = collect($monthlyActivity)->sum('actions') > 0;
                        @endphp
                        @if($hasMonthlyData)
                            <canvas id="monthlyTrendChart" width="400" height="200"></canvas>
                        @else
                            <div class="flex flex-col items-center justify-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-chart-line text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Monthly Data</h3>
                                <p class="text-gray-500 text-center max-w-sm">You haven't performed any actions in the past 6 months. Start processing bookings to see your trend here.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Service Type Distribution -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-church text-orange-600 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Service Type Distribution</h2>
                                <p class="text-sm text-gray-600">Bookings you've processed by service type</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $hasServiceData = collect($serviceDistribution)->sum() > 0;
                        @endphp
                        @if($hasServiceData)
                            <canvas id="serviceDistributionChart" width="400" height="200"></canvas>
                        @else
                            <div class="flex flex-col items-center justify-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-church text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Service Data</h3>
                                <p class="text-gray-500 text-center max-w-sm">You haven't processed any bookings by service type yet. Start processing bookings to see your distribution here.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Daily Activity Progress -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 lg:col-span-2">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-day text-indigo-600 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Daily Activity (Last 7 Days)</h2>
                                <p class="text-sm text-gray-600">Your daily action breakdown</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $hasDailyData = collect($dailyActivity)->sum('actions') > 0;
                        @endphp
                        @if($hasDailyData)
                            <div class="space-y-4">
                                @foreach($dailyActivity as $day)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-[#0d5c2f] rounded-lg flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ $day['actions'] }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $day['date'] }}</p>
                                            <p class="text-sm text-gray-600">{{ $day['actions'] }} {{ Str::plural('action', $day['actions']) }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-32 bg-gray-200 rounded-full h-3">
                                            @php
                                                $maxActions = max(array_column($dailyActivity, 'actions'));
                                                $percentage = $maxActions > 0 ? ($day['actions'] / $maxActions) * 100 : 0;
                                            @endphp
                                            <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/80 h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 w-8 text-right">{{ $day['actions'] }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-calendar-day text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Daily Activity</h3>
                                <p class="text-gray-500 text-center max-w-sm">You haven't performed any actions in the last 7 days. Start processing bookings to see your daily activity here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Tab -->
        <div id="activities-tab" class="tab-content hidden">
            <!-- Recent Activities -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-history text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">My Recent Activities</h2>
                            <p class="text-sm text-gray-600">Your latest actions and decisions</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentActivities->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentActivities as $activity)
                            <div class="flex items-center justify-between p-5 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl hover:from-gray-100 hover:to-gray-200 transition-all duration-200 border border-gray-200 hover:border-gray-300">
                                <div class="flex items-center space-x-4">
                                    <div class="p-3 rounded-xl bg-{{ $activity->action_color }}-100 border border-{{ $activity->action_color }}-200">
                                        <i class="{{ $activity->action_icon }} text-{{ $activity->action_color }}-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-lg">
                                            {{ $activity->action_type_label }} Booking #{{ $activity->booking_id }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-church mr-1"></i>{{ $activity->booking->service->name ?? 'Unknown Service' }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-user mr-1"></i>{{ $activity->booking->user->name ?? 'Unknown User' }}
                                        </p>
                                        @if($activity->notes)
                                            <p class="text-xs text-gray-500 mt-2 italic bg-white/50 p-2 rounded-lg">{{ $activity->notes }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $activity->action_color }}-100 text-{{ $activity->action_color }}-800 border border-{{ $activity->action_color }}-200">
                                        {{ $activity->action_type_label }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-8 text-center">
                            <a href="{{ route('staff.bookings.index') }}" class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] text-white rounded-xl hover:bg-[#0d5c2f]/90 transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105">
                                <i class="fas fa-eye mr-2"></i>View All Bookings
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-16">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Recent Activities</h3>
                            <p class="text-gray-500 text-center max-w-md mb-6">You haven't performed any actions yet. Start processing bookings to see your activities here.</p>
                            <a href="{{ route('staff.bookings.index') }}" class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] text-white rounded-xl hover:bg-[#0d5c2f]/90 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-plus mr-2"></i>Start Processing Bookings
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bookings Tab -->
        <div id="bookings-tab" class="tab-content hidden">
            <!-- My Booking Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-4 bg-yellow-100 rounded-xl group-hover:bg-yellow-200 transition-colors duration-200">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $bookingStats['pending_bookings'] }}</p>
                            <p class="text-xs text-gray-500">Need attention</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-4 bg-blue-100 rounded-xl group-hover:bg-blue-200 transition-colors duration-200">
                            <i class="fas fa-check text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Acknowledged</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $bookingStats['acknowledged_bookings'] }}</p>
                            <p class="text-xs text-gray-500">By me</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-4 bg-orange-100 rounded-xl group-hover:bg-orange-200 transition-colors duration-200">
                            <i class="fas fa-money-bill text-orange-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Payment Hold</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $bookingStats['payment_hold_bookings'] }}</p>
                            <p class="text-xs text-gray-500">Awaiting payment</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-4 bg-green-100 rounded-xl group-hover:bg-green-200 transition-colors duration-200">
                            <i class="fas fa-thumbs-up text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Approved</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $bookingStats['approved_bookings'] }}</p>
                            <p class="text-xs text-gray-500">By me</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings Needing My Attention -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bookmark text-yellow-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Bookings Needing My Attention</h2>
                            <p class="text-sm text-gray-600">Bookings that require your action</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBookings as $booking)
                            <div class="flex items-center justify-between p-5 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl hover:from-gray-100 hover:to-gray-200 transition-all duration-200 border border-gray-200 hover:border-gray-300">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 border-2 border-yellow-200' : 
                                           ($booking->status === 'acknowledged' ? 'bg-blue-100 border-2 border-blue-200' : 'bg-orange-100 border-2 border-orange-200') }}">
                                        <i class="fas fa-bookmark text-lg
                                            {{ $booking->status === 'pending' ? 'text-yellow-600' : 
                                               ($booking->status === 'acknowledged' ? 'text-blue-600' : 'text-orange-600') }}"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-lg">#{{ $booking->id }} - {{ $booking->service->name ?? 'Unknown Service' }}</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-user mr-1"></i>{{ $booking->user->name ?? 'Unknown User' }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-calendar mr-1"></i>{{ $booking->service_date->format('M d, Y') }} at {{ $booking->service_time }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border
                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 
                                           ($booking->status === 'acknowledged' ? 'bg-blue-100 text-blue-800 border-blue-200' : 'bg-orange-100 text-orange-800 border-orange-200') }}">
                                        @php
                                            $statusDisplay = str_replace('_', ' ', $booking->status);
                                            $statusDisplay = ucwords($statusDisplay);
                                        @endphp
                                        {{ $statusDisplay }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">{{ $booking->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-8 text-center">
                            <a href="{{ route('staff.bookings.index') }}" class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] text-white rounded-xl hover:bg-[#0d5c2f]/90 transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105">
                                <i class="fas fa-list mr-2"></i>View All Bookings
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-16">
                            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-6">
                                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">All Caught Up!</h3>
                            <p class="text-gray-500 text-center max-w-md mb-6">Great job! No bookings need your attention right now. All bookings are up to date.</p>
                            <a href="{{ route('staff.bookings.index') }}" class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] text-white rounded-xl hover:bg-[#0d5c2f]/90 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-eye mr-2"></i>View All Bookings
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ratings Tab -->
        <div id="ratings-tab" class="tab-content hidden">
            <!-- Rating Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-yellow-500/30 rounded-xl shadow-sm border-2 border-yellow-500 p-4">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-yellow-500 rounded-lg">
                            <i class="fas fa-star text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-yellow-800">New Ratings Today</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $ratingStats['new_ratings_today'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-500/30 rounded-xl shadow-sm border-2 border-blue-500 p-4">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-blue-500 rounded-lg">
                            <i class="fas fa-thumbs-up text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-800">Rated Services</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $ratingStats['rated_services'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-orange-500/30 rounded-xl shadow-sm border-2 border-orange-500 p-4">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-orange-500 rounded-lg">
                            <i class="fas fa-clock text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-orange-800">Unrated Services</p>
                            <p class="text-2xl font-bold text-orange-900">{{ $ratingStats['unrated_services'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-500/30 rounded-xl shadow-sm border-2 border-green-500 p-4">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-green-500 rounded-lg">
                            <i class="fas fa-chart-line text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-800">Overall Average</p>
                            <p class="text-2xl font-bold text-green-900">{{ $ratingStats['average_rating'] ?? 0 }}/5</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Ratings Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Service Ratings Overview</h2>
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                Overall: {{ $ratingStats['average_rating'] ?? 0 }}/5
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-thumbs-up text-green-500 mr-1"></i>
                                {{ $ratingStats['rated_services'] ?? 0 }} rated
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock text-orange-500 mr-1"></i>
                                {{ $ratingStats['unrated_services'] ?? 0 }} unrated
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($serviceStats->count() > 0)
                        <div class="space-y-4">
                            @foreach($serviceStats as $service)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-full bg-[#0d5c2f] flex items-center justify-center">
                                        <i class="fas fa-church text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $service->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $service->bookings_count }} bookings</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    @if($service->total_ratings > 0)
                                        <!-- Rated Service -->
                                        <div class="text-center">
                                            <div class="flex items-center justify-center space-x-1 mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $service->average_rating)
                                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                                    @elseif($i - $service->average_rating < 1)
                                                        <i class="fas fa-star-half-alt text-yellow-400 text-sm"></i>
                                                    @else
                                                        <i class="far fa-star text-gray-300 text-sm"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-lg font-bold text-gray-900">{{ $service->average_rating }}</span>
                                                <span class="text-sm text-gray-600">/5</span>
                                                <span class="text-xs text-gray-500">({{ $service->total_ratings }})</span>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Unrated Service -->
                                        <div class="text-center">
                                            <div class="flex items-center justify-center space-x-1 mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="far fa-star text-gray-300 text-sm"></i>
                                                @endfor
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-lg font-bold text-gray-400">--</span>
                                                <span class="text-sm text-gray-400">/5</span>
                                                <span class="text-xs text-gray-400">(0)</span>
                                            </div>
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    No ratings yet
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No service data available</p>
                    @endif
                </div>
            </div>

            <!-- Charts and Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Rating Distribution Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Rating Distribution</h2>
                        <p class="text-sm text-gray-600">Overall rating distribution across all services</p>
                    </div>
                    <div class="p-6">
                        @php
                            $hasRatingDistData = (
                                ($ratingStats['rating_distribution']['1_star'] ?? 0) +
                                ($ratingStats['rating_distribution']['2_star'] ?? 0) +
                                ($ratingStats['rating_distribution']['3_star'] ?? 0) +
                                ($ratingStats['rating_distribution']['4_star'] ?? 0) +
                                ($ratingStats['rating_distribution']['5_star'] ?? 0)
                            ) > 0;
                        @endphp
                        @if($hasRatingDistData)
                            <canvas id="ratingDistributionChart" width="400" height="200"></canvas>
                        @else
                            <div class="flex flex-col items-center justify-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-chart-bar text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Rating Data</h3>
                                <p class="text-gray-500 text-center max-w-sm">There are no ratings yet to display a distribution. Ratings will appear here once users start rating services.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Top Rated Services -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Top Rated Services</h2>
                        <p class="text-sm text-gray-600">Highest rated services in the system</p>
                    </div>
                    <div class="p-6">
                        @if(isset($ratingStats['top_rated_services']) && $ratingStats['top_rated_services']->count() > 0)
                            <div class="space-y-4">
                                @foreach($ratingStats['top_rated_services'] as $topService)
                                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center">
                                            <i class="fas fa-trophy text-white text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $topService->name }}</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-center justify-center space-x-1 mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $topService->average_rating)
                                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                                @else
                                                    <i class="far fa-star text-gray-300 text-xs"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-lg font-bold text-yellow-600">{{ $topService->average_rating }}/5</span>
                                        <p class="text-xs text-gray-600">{{ $topService->ratings_count }} ratings</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-trophy text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Top Rated Services</h3>
                                <p class="text-gray-500 text-center max-w-sm">There are no rated services yet. Once services receive ratings, the highest rated ones will appear here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Ratings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Ratings</h2>
                    <p class="text-sm text-gray-600">Latest ratings submitted by users</p>
                </div>
                <div class="p-6">
                    @if(isset($ratingStats['recent_ratings']) && $ratingStats['recent_ratings']->count() > 0)
                        <div class="space-y-4">
                            @foreach($ratingStats['recent_ratings'] as $rating)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $rating->user->name ?? 'Unknown User' }}</h3>
                                        <p class="text-sm text-gray-600">{{ $rating->service->name ?? 'Unknown Service' }}</p>
                                        @if($rating->comment)
                                            <p class="text-xs text-gray-500 mt-1 italic">"{{ Str::limit($rating->comment, 100) }}"</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center justify-center space-x-1 mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating->rating)
                                                <i class="fas fa-star text-yellow-400 text-lg"></i>
                                            @else
                                                <i class="far fa-star text-gray-300 text-lg"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-xl font-bold text-gray-900">{{ $rating->rating }}/5</span>
                                    <p class="text-xs text-gray-500">{{ $rating->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No recent ratings found</p>
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
        button.classList.remove('border-[#0d5c2f]', 'text-[#0d5c2f]', 'bg-[#0d5c2f]/5');
        button.classList.add('border-transparent', 'text-gray-500', 'hover:bg-gray-50');
    });

    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');

    // Add active state to selected tab button
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:bg-gray-50');
    activeButton.classList.add('border-[#0d5c2f]', 'text-[#0d5c2f]', 'bg-[#0d5c2f]/5');
}

function initializeCharts() {
    // Weekly Activity Chart
    const weeklyActivityCtx = document.getElementById('weeklyActivityChart');
    if (weeklyActivityCtx) {
        const weeklyData = @json($weeklyActivity);
        const hasData = weeklyData.some(day => day.actions > 0);
        
        if (hasData) {
            new Chart(weeklyActivityCtx, {
                type: 'bar',
                data: {
                    labels: weeklyData.map(day => day.day),
                    datasets: [{
                        label: 'Actions',
                        data: weeklyData.map(day => day.actions),
                        backgroundColor: chartColors.primary,
                        borderColor: chartColors.primary,
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
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
    }

    // Action Distribution Chart
    const actionDistributionCtx = document.getElementById('actionDistributionChart');
    if (actionDistributionCtx) {
        const actionData = {
            acknowledged: {{ $actionDistribution['acknowledged'] }},
            approved: {{ $actionDistribution['approved'] }},
            rejected: {{ $actionDistribution['rejected'] }},
            completed: {{ $actionDistribution['completed'] }}
        };
        
        const hasData = Object.values(actionData).some(value => value > 0);
        
        if (hasData) {
            new Chart(actionDistributionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Acknowledged', 'Approved', 'Rejected', 'Completed'],
                    datasets: [{
                        data: [
                            actionData.acknowledged,
                            actionData.approved,
                            actionData.rejected,
                            actionData.completed
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
    }

    // Monthly Trend Chart
    const monthlyTrendCtx = document.getElementById('monthlyTrendChart');
    if (monthlyTrendCtx) {
        const monthlyData = @json($monthlyActivity);
        const hasData = monthlyData.some(month => month.actions > 0);
        
        if (hasData) {
            new Chart(monthlyTrendCtx, {
                type: 'line',
                data: {
                    labels: monthlyData.map(month => month.month),
                    datasets: [{
                        label: 'Actions',
                        data: monthlyData.map(month => month.actions),
                        borderColor: chartColors.primary,
                        backgroundColor: chartColors.primary + '20',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: chartColors.primary,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6
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
    }

    // Service Distribution Chart
    const serviceDistributionCtx = document.getElementById('serviceDistributionChart');
    if (serviceDistributionCtx) {
        const serviceData = @json($serviceDistribution);
        const hasData = Object.values(serviceData).some(value => value > 0);
        
        if (hasData) {
            new Chart(serviceDistributionCtx, {
                type: 'pie',
                data: {
                    labels: Object.keys(serviceData),
                    datasets: [{
                        data: Object.values(serviceData),
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

    // Rating Distribution Chart
    const ratingDistributionCtx = document.getElementById('ratingDistributionChart');
    if (ratingDistributionCtx) {
        // Get rating distribution data from the controller
        const ratingData = {
            '1_star': {{ $ratingStats['rating_distribution']['1_star'] ?? 0 }},
            '2_star': {{ $ratingStats['rating_distribution']['2_star'] ?? 0 }},
            '3_star': {{ $ratingStats['rating_distribution']['3_star'] ?? 0 }},
            '4_star': {{ $ratingStats['rating_distribution']['4_star'] ?? 0 }},
            '5_star': {{ $ratingStats['rating_distribution']['5_star'] ?? 0 }}
        };

        const hasData = Object.values(ratingData).some(value => value > 0);

        if (hasData) {
            new Chart(ratingDistributionCtx, {
                type: 'bar',
                data: {
                    labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                    datasets: [{
                        label: 'Number of Ratings',
                        data: [
                            ratingData['1_star'],
                            ratingData['2_star'],
                            ratingData['3_star'],
                            ratingData['4_star'],
                            ratingData['5_star']
                        ],
                        backgroundColor: chartColors.warning,
                        borderColor: chartColors.warning,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: chartColors.warning,
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' ratings';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6',
                                lineWidth: 1
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 12
                                },
                                padding: 8,
                                callback: function(value) {
                                    return value + ' ratings';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 12
                                },
                                padding: 8
                            }
                        }
                    },
                    elements: {
                        bar: {
                            borderRadius: 6,
                            borderSkipped: false
                        }
                    }
                }
            });
        }
    }
}
</script>
@endsection 