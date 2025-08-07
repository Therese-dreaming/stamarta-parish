@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="font-[Poppins]">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600">Welcome to your parish CMS</p>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button onclick="showTab('bookings')" id="tab-bookings" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-[#0d5c2f] text-[#0d5c2f]">
                    <i class="fas fa-bookmark mr-2"></i>Bookings
                </button>
                <button onclick="showTab('finance')" id="tab-finance" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-chart-line mr-2"></i>Finance
                </button>
                <button onclick="showTab('trends')" id="tab-trends" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-users mr-2"></i>User Trends
                </button>
                <button onclick="showTab('actions')" id="tab-actions" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-bolt mr-2"></i>Quick Actions
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Content -->
    <div id="tab-content">
        <!-- Bookings Tab -->
        <div id="bookings-tab" class="tab-content">
            <!-- Today's Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-sm border border-blue-200 p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-calendar-day text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-100">Today's Bookings</p>
                            <p class="text-2xl font-bold">{{ $stats['new_bookings'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-sm border border-green-200 p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-100">Completed Today</p>
                            <p class="text-2xl font-bold">{{ $stats['completed_bookings'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-sm border border-orange-200 p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-orange-100">Pending Review</p>
                            <p class="text-2xl font-bold">{{ $stats['payment_hold_bookings'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Bookings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-bookmark text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_bookings'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Bookings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_bookings'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Acknowledged Bookings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-check text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Acknowledged</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['acknowledged_bookings'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Approved Bookings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-thumbs-up text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Approved</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['approved_bookings'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Monthly Booking Trends -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Monthly Booking Trends</h2>
                    </div>
                    <div class="p-6">
                        <canvas id="bookingTrendsChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Service Popularity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Most Popular Services</h2>
                    </div>
                    <div class="p-6">
                        @if($serviceStats->count() > 0)
                            <div class="space-y-4">
                                @foreach($serviceStats as $service)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full bg-[#0d5c2f]"></div>
                                        <span class="text-sm font-medium text-gray-900">{{ $service->name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            @php
                                                $maxBookings = $serviceStats->max('bookings_count');
                                                $percentage = $maxBookings > 0 ? ($service->bookings_count / $maxBookings) * 100 : 0;
                                            @endphp
                                            <div class="bg-[#0d5c2f] h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">{{ $service->bookings_count }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No service data available</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Bookings</h2>
                </div>
                <div class="p-6">
                    @if(isset($stats['recent_bookings']) && $stats['recent_bookings']->count() > 0)
                        <div class="space-y-4">
                            @foreach($stats['recent_bookings'] as $booking)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 rounded-full 
                                        {{ $booking->status === 'pending' ? 'bg-yellow-500' : 
                                           ($booking->status === 'acknowledged' ? 'bg-blue-500' : 
                                           ($booking->status === 'payment_hold' ? 'bg-orange-500' : 
                                           ($booking->status === 'approved' ? 'bg-green-500' : 'bg-gray-500'))) }}">
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">#{{ $booking->id }} - {{ $booking->service->name ?? 'Unknown Service' }}</p>
                                        <p class="text-sm text-gray-600">{{ $booking->user->name ?? 'Unknown User' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ ucfirst($booking->status) }}</p>
                                    <p class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.bookings.index') }}" class="text-[#0d5c2f] hover:underline text-sm">View all bookings →</a>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No bookings found</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Finance Tab -->
        <div id="finance-tab" class="tab-content hidden">
            <!-- Today's Financial Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-sm border border-green-200 p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-money-bill-wave text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-100">Today's Revenue</p>
                            <p class="text-2xl font-bold">₱{{ number_format($stats['new_revenue'] ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-sm border border-blue-200 p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-100">This Month</p>
                            <p class="text-2xl font-bold">₱{{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-sm border border-purple-200 p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-purple-100">Avg. Transaction</p>
                            <p class="text-2xl font-bold">₱{{ number_format($stats['avg_transaction'] ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Revenue -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-900">₱{{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 rounded-lg">
                            <i class="fas fa-clock text-orange-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Payments</p>
                            <p class="text-2xl font-bold text-gray-900">₱{{ number_format($stats['pending_payments'] ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- GCash Payments -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <img src="{{ asset('images/gcash-logo.png') }}" alt="GCash" class="h-6 w-auto">
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">GCash Payments</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['gcash_payments'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Metrobank Payments -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <img src="{{ asset('images/metrobank-logo.png') }}" alt="Metrobank" class="h-6 w-auto">
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Metrobank Payments</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['metrobank_payments'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Monthly Revenue Trends -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Monthly Revenue Trends</h2>
                    </div>
                    <div class="p-6">
                        <canvas id="revenueTrendsChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Payment Method Distribution -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Payment Method Distribution</h2>
                    </div>
                    <div class="p-6">
                        <canvas id="paymentMethodsChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Chart -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Payment Methods</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ asset('images/gcash-logo.png') }}" alt="GCash" class="h-6 w-auto mr-3">
                                    <span class="text-sm font-medium text-gray-900">GCash</span>
                                </div>
                                <span class="text-sm text-gray-600">{{ $stats['gcash_payments'] ?? 0 }} payments</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ asset('images/metrobank-logo.png') }}" alt="Metrobank" class="h-6 w-auto mr-3">
                                    <span class="text-sm font-medium text-gray-900">Metrobank</span>
                                </div>
                                <span class="text-sm text-gray-600">{{ $stats['metrobank_payments'] ?? 0 }} payments</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Transactions</h2>
                    </div>
                    <div class="p-6">
                        @if(isset($stats['recent_transactions']) && $stats['recent_transactions']->count() > 0)
                            <div class="space-y-3">
                                @foreach($stats['recent_transactions'] as $transaction)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Booking #{{ $transaction->booking_id }}</p>
                                        <p class="text-xs text-gray-600">{{ $transaction->payment_method_label }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">₱{{ number_format($transaction->total_fee, 2) }}</p>
                                        <p class="text-xs text-gray-500">{{ $transaction->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No recent transactions</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- User Trends Tab -->
        <div id="trends-tab" class="tab-content hidden">
            <!-- Today's User Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-sm border border-blue-200 p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-user-plus text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-100">New Users Today</p>
                            <p class="text-2xl font-bold">{{ $stats['new_users'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-sm border border-green-200 p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-user-shield text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-100">Verified Users</p>
                            <p class="text-2xl font-bold">{{ $stats['verified_users'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-sm border border-purple-200 p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-user-check text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-purple-100">Active Users</p>
                            <p class="text-2xl font-bold">{{ $stats['active_users'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- New Users This Month -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-user-plus text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">New This Month</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['new_users_month'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Unverified Users -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-user-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Unverified</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['unverified_users'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- User Growth Rate -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Growth Rate</p>
                            <p class="text-2xl font-bold text-gray-900">
                                @php
                                    $totalUsers = $stats['total_users'] ?? 0;
                                    $newUsers = $stats['new_users_month'] ?? 0;
                                    $growthRate = $totalUsers > 0 ? round(($newUsers / $totalUsers) * 100, 1) : 0;
                                @endphp
                                {{ $growthRate }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Monthly User Registration Trends -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Monthly User Registration Trends</h2>
                    </div>
                    <div class="p-6">
                        <canvas id="userTrendsChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Role Distribution -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">User Role Distribution</h2>
                    </div>
                    <div class="p-6">
                        <canvas id="roleDistributionChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- User Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">User Registration Trend</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @for($i = 5; $i >= 0; $i--)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ now()->subDays($i)->format('M d') }}</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-32 bg-gray-200 rounded-full h-2">
                                        <div class="bg-[#0d5c2f] h-2 rounded-full" style="width: {{ rand(20, 80) }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ rand(1, 15) }}</span>
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Recent User Activity</h2>
                    </div>
                    <div class="p-6">
                        @if(isset($stats['recent_users']) && $stats['recent_users']->count() > 0)
                            <div class="space-y-3">
                                @foreach($stats['recent_users'] as $user)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-8 w-8 rounded-full bg-[#0d5c2f] flex items-center justify-center">
                                            <i class="fas fa-user text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-600">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No recent user activity</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Tab -->
        <div id="actions-tab" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Content Management -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Content Management</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('admin.cms.pages.create') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-plus text-[#0d5c2f] mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Create Page</span>
                            </a>
                            <a href="{{ route('admin.cms.media.create') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-upload text-[#0d5c2f] mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Upload Media</span>
                            </a>
                            <a href="{{ route('admin.cms.pages.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-list text-[#0d5c2f] mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Manage Pages</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Booking Management -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Booking Management</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('admin.bookings.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-bookmark text-[#0d5c2f] mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">View All Bookings</span>
                            </a>
                            <a href="{{ route('admin.services.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-calendar-alt text-[#0d5c2f] mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Manage Services</span>
                            </a>
                            <a href="{{ route('admin.priests.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-cross text-[#0d5c2f] mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Manage Priests</span>
                            </a>
                            <a href="{{ route('admin.parochial-activities.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-church text-[#0d5c2f] mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Manage Activities</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Management -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">User Management</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-users text-[#0d5c2f] mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Manage Users</span>
                            </a>
                            <a href="{{ route('admin.users.create') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-user-plus text-[#0d5c2f] mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Add New User</span>
                            </a>
                            <a href="{{ route('home') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-eye text-[#0d5c2f] mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">View Public Site</span>
                            </a>
                        </div>
                    </div>
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
    showTab('bookings');
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
    // Booking Trends Chart
    const bookingTrendsCtx = document.getElementById('bookingTrendsChart');
    if (bookingTrendsCtx) {
        new Chart(bookingTrendsCtx, {
            type: 'line',
            data: {
                labels: @json(array_column($monthlyBookings, 'month')),
                datasets: [{
                    label: 'Bookings',
                    data: @json(array_column($monthlyBookings, 'count')),
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

    // Revenue Trends Chart
    const revenueTrendsCtx = document.getElementById('revenueTrendsChart');
    if (revenueTrendsCtx) {
        new Chart(revenueTrendsCtx, {
            type: 'bar',
            data: {
                labels: @json(array_column($monthlyRevenue, 'month')),
                datasets: [{
                    label: 'Revenue (₱)',
                    data: @json(array_column($monthlyRevenue, 'amount')),
                    backgroundColor: chartColors.success,
                    borderColor: chartColors.success,
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
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
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

    // Payment Methods Chart
    const paymentMethodsCtx = document.getElementById('paymentMethodsChart');
    if (paymentMethodsCtx) {
        new Chart(paymentMethodsCtx, {
            type: 'doughnut',
            data: {
                labels: ['GCash', 'Metrobank'],
                datasets: [{
                    data: [{{ $stats['gcash_payments'] ?? 0 }}, {{ $stats['metrobank_payments'] ?? 0 }}],
                    backgroundColor: [chartColors.info, chartColors.danger],
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

    // User Trends Chart
    const userTrendsCtx = document.getElementById('userTrendsChart');
    if (userTrendsCtx) {
        new Chart(userTrendsCtx, {
            type: 'line',
            data: {
                labels: @json(array_column($monthlyUsers, 'month')),
                datasets: [{
                    label: 'New Users',
                    data: @json(array_column($monthlyUsers, 'count')),
                    borderColor: chartColors.info,
                    backgroundColor: chartColors.info + '20',
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

    // Role Distribution Chart
    const roleDistributionCtx = document.getElementById('roleDistributionChart');
    if (roleDistributionCtx) {
        new Chart(roleDistributionCtx, {
            type: 'pie',
            data: {
                labels: ['Users', 'Staff', 'Priests', 'Admins'],
                datasets: [{
                    data: [
                        {{ $roleDistribution['user'] ?? 0 }},
                        {{ $roleDistribution['staff'] ?? 0 }},
                        {{ $roleDistribution['priest'] ?? 0 }},
                        {{ $roleDistribution['admin'] ?? 0 }}
                    ],
                    backgroundColor: [
                        chartColors.success,
                        chartColors.info,
                        chartColors.purple,
                        chartColors.warning
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