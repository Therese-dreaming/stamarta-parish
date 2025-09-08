@extends('layouts.priest')

@section('title', 'Priest Dashboard')

@section('content')
@include('components.toast')

<div class="space-y-6">
    <!-- Enhanced Header -->
    <div class="bg-[#0d5c2f] rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 py-8 relative">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-bl-full"></div>
            <div class="absolute left-0 bottom-0 w-24 h-24 bg-white/5 rounded-tr-full"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="mr-6 hidden md:block">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center border-2 border-white/30 shadow-lg">
                            <i class="fas fa-cross text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white flex items-center mb-2">
                            <i class="fas fa-cross mr-3"></i>
                            Priest Dashboard
                        </h1>
                        <p class="text-white/90 text-base">Welcome back, {{ auth()->user()->priest->name ?? auth()->user()->name }}</p>
                        <div class="flex items-center mt-3 text-white/80 text-sm">
                            <i class="fas fa-church mr-2"></i>
                            <span>Sacramental Services</span>
                        </div>
                    </div>
                </div>
                <div class="text-right text-white">
                    <div class="text-4xl font-bold mb-1">{{ date('M d, Y') }}</div>
                    <div class="text-sm opacity-90">{{ date('l') }}</div>
                    <div class="flex space-x-3 mt-4">
                        <a href="{{ route('priest.bookings.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                            <i class="fas fa-calendar mr-2"></i>
                            View Bookings
                        </a>
                        <a href="{{ route('priest.bookings.index', ['status' => 'pending']) }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                            <i class="fas fa-clock mr-2"></i>
                            Pending Actions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Assigned</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $bookingStats['total_assigned'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar-check text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-up text-blue-500 mr-1"></i>
                    <span>All time</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Pending Attention</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $monthlyStats['pending_attention'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-clock text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-exclamation text-yellow-500 mr-1"></i>
                    <span>Requires action</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Completed This Month</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $monthlyStats['completed_this_month'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-check-circle text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                    <span>This month</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Completion Rate</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $performanceMetrics['completion_rate'] }}%</p>
                </div>
                <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-line text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-percentage text-purple-500 mr-1"></i>
                    <span>Success rate</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Bookings Chart -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Monthly Bookings</h3>
                    <p class="text-sm text-gray-500">Bookings assigned per month</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-[#0d5c2f] rounded-full"></div>
                    <span class="text-xs text-gray-500">Bookings</span>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="monthlyBookingsChart"></canvas>
                <div id="monthlyBookingsEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-line text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">No Bookings Yet</p>
                        <p class="text-gray-400 text-xs mt-1">Monthly booking trends will appear here</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Types Chart -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Service Types</h3>
                    <p class="text-sm text-gray-500">Distribution by service type</p>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="serviceTypeChart"></canvas>
                <div id="serviceTypeEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-pie text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">No Services Yet</p>
                        <p class="text-gray-400 text-xs mt-1">Service type distribution will appear here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Analysis Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Weekly Performance Chart -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Weekly Performance</h3>
                    <p class="text-sm text-gray-500">Completed vs assigned this week</p>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="weeklyPerformanceChart"></canvas>
                <div id="weeklyPerformanceEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-bar text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">No Activity This Week</p>
                        <p class="text-gray-400 text-xs mt-1">Weekly performance will be tracked here</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completion Trend Chart -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Completion Trend</h3>
                    <p class="text-sm text-gray-500">Services completed per month</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-xs text-gray-500">Completed</span>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="completionTrendChart"></canvas>
                <div id="completionTrendEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-line text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">No Completions Yet</p>
                        <p class="text-gray-400 text-xs mt-1">Completion trends will appear here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Analysis Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Booking Status Chart -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Booking Status</h3>
                    <p class="text-sm text-gray-500">Distribution by status</p>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="bookingStatusChart"></canvas>
                <div id="bookingStatusEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-pie text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">No Bookings Yet</p>
                        <p class="text-gray-400 text-xs mt-1">Status distribution will appear here</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Frequency Chart -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Service Frequency</h3>
                    <p class="text-sm text-gray-500">Most frequently conducted services</p>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="serviceFrequencyChart"></canvas>
                <div id="serviceFrequencyEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-bar text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">No Services Yet</p>
                        <p class="text-gray-400 text-xs mt-1">Service frequency will appear here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Bookings and Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming Bookings -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg border border-gray-200">
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
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-times text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">No Upcoming Bookings</p>
                        <p class="text-gray-400 text-xs mt-1">No bookings scheduled for the next 7 days</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Performance Metrics & Recent Activities -->
        <div class="space-y-6">
            <!-- Performance Metrics -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
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
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
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
                        <div class="text-center py-4">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-history text-gray-400 text-lg"></i>
                            </div>
                            <p class="text-gray-500 text-sm">No recent activities</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Status Overview -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
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

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Dashboard Charts JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real data from backend
    const chartData = {
        monthlyBookings: @json($monthlyBookingsData ?? ['labels' => [], 'data' => []]),
        serviceType: @json($serviceTypeData ?? ['labels' => [], 'data' => [], 'colors' => []]),
        bookingStatus: @json($bookingStatusData ?? ['labels' => [], 'data' => [], 'colors' => []]),
        weeklyPerformance: @json($weeklyPerformanceData ?? ['labels' => [], 'datasets' => []]),
        completionTrend: @json($completionTrendData ?? ['labels' => [], 'data' => []]),
        serviceFrequency: @json($serviceFrequencyData ?? ['labels' => [], 'data' => [], 'colors' => []])
    };

    // Monthly Bookings Chart
    const monthlyBookingsCtx = document.getElementById('monthlyBookingsChart');
    const monthlyBookingsEmptyState = document.getElementById('monthlyBookingsEmptyState');
    if (monthlyBookingsCtx) {
        if (chartData.monthlyBookings.isEmpty) {
            monthlyBookingsCtx.style.display = 'none';
            monthlyBookingsEmptyState.classList.remove('hidden');
        } else {
            new Chart(monthlyBookingsCtx, {
                type: 'line',
                data: {
                    labels: chartData.monthlyBookings.labels,
                    datasets: [{
                        label: 'Bookings',
                        data: chartData.monthlyBookings.data,
                        borderColor: '#0d5c2f',
                        backgroundColor: 'rgba(13, 92, 47, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#0d5c2f',
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
                                color: 'rgba(0, 0, 0, 0.05)'
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

    // Service Type Chart
    const serviceTypeCtx = document.getElementById('serviceTypeChart');
    const serviceTypeEmptyState = document.getElementById('serviceTypeEmptyState');
    if (serviceTypeCtx) {
        if (chartData.serviceType.isEmpty) {
            serviceTypeCtx.style.display = 'none';
            serviceTypeEmptyState.classList.remove('hidden');
        } else {
            new Chart(serviceTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: chartData.serviceType.labels,
                    datasets: [{
                        data: chartData.serviceType.data,
                        backgroundColor: chartData.serviceType.colors,
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Weekly Performance Chart
    const weeklyPerformanceCtx = document.getElementById('weeklyPerformanceChart');
    const weeklyPerformanceEmptyState = document.getElementById('weeklyPerformanceEmptyState');
    if (weeklyPerformanceCtx) {
        if (chartData.weeklyPerformance.isEmpty) {
            weeklyPerformanceCtx.style.display = 'none';
            weeklyPerformanceEmptyState.classList.remove('hidden');
        } else {
            new Chart(weeklyPerformanceCtx, {
                type: 'bar',
                data: {
                    labels: chartData.weeklyPerformance.labels,
                    datasets: chartData.weeklyPerformance.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
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

    // Completion Trend Chart
    const completionTrendCtx = document.getElementById('completionTrendChart');
    const completionTrendEmptyState = document.getElementById('completionTrendEmptyState');
    if (completionTrendCtx) {
        if (chartData.completionTrend.isEmpty) {
            completionTrendCtx.style.display = 'none';
            completionTrendEmptyState.classList.remove('hidden');
        } else {
            new Chart(completionTrendCtx, {
                type: 'line',
                data: {
                    labels: chartData.completionTrend.labels,
                    datasets: [{
                        label: 'Completed',
                        data: chartData.completionTrend.data,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
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
                                color: 'rgba(0, 0, 0, 0.05)'
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

    // Booking Status Chart
    const bookingStatusCtx = document.getElementById('bookingStatusChart');
    const bookingStatusEmptyState = document.getElementById('bookingStatusEmptyState');
    if (bookingStatusCtx) {
        if (chartData.bookingStatus.isEmpty) {
            bookingStatusCtx.style.display = 'none';
            bookingStatusEmptyState.classList.remove('hidden');
        } else {
            new Chart(bookingStatusCtx, {
                type: 'pie',
                data: {
                    labels: chartData.bookingStatus.labels,
                    datasets: [{
                        data: chartData.bookingStatus.data,
                        backgroundColor: chartData.bookingStatus.colors,
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }
    }

    // Service Frequency Chart
    const serviceFrequencyCtx = document.getElementById('serviceFrequencyChart');
    const serviceFrequencyEmptyState = document.getElementById('serviceFrequencyEmptyState');
    if (serviceFrequencyCtx) {
        if (chartData.serviceFrequency.isEmpty) {
            serviceFrequencyCtx.style.display = 'none';
            serviceFrequencyEmptyState.classList.remove('hidden');
        } else {
            new Chart(serviceFrequencyCtx, {
                type: 'bar',
                data: {
                    labels: chartData.serviceFrequency.labels,
                    datasets: [{
                        label: 'Frequency',
                        data: chartData.serviceFrequency.data,
                        backgroundColor: chartData.serviceFrequency.colors,
                        borderWidth: 0,
                        borderRadius: 6,
                        borderSkipped: false,
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
                                color: 'rgba(0, 0, 0, 0.05)'
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
});
</script>

<!-- Custom CSS for enhanced styling -->
<style>
/* Smooth animations */
* {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Chart container styling */
canvas {
    border-radius: 8px;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

@endsection