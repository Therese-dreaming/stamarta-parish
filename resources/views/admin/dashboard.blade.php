@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="font-[Poppins]">
    <!-- Hero Header -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm mb-8">
        <div class="px-6 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Dashboard</h1>
                    <p class="text-white/80 mt-1">Welcome to your parish CMS - Manage bookings, finances, and users</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right text-white/90">
                        <p class="text-sm">Last Updated</p>
                        <p class="font-semibold">{{ now()->format('M d, Y g:i A') }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-2">
            <nav class="flex space-x-2" aria-label="Tabs">
                <button onclick="showTab('bookings')" id="tab-bookings" class="tab-button flex-1 py-3 px-4 rounded-lg font-medium text-sm transition-all duration-200 bg-[#0d5c2f] text-white shadow-sm">
                    <i class="fas fa-bookmark mr-2"></i>Bookings
                </button>
                <button onclick="showTab('finance')" id="tab-finance" class="tab-button flex-1 py-3 px-4 rounded-lg font-medium text-sm transition-all duration-200 bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-800">
                    <i class="fas fa-chart-line mr-2"></i>Finance
                </button>
                <button onclick="showTab('trends')" id="tab-trends" class="tab-button flex-1 py-3 px-4 rounded-lg font-medium text-sm transition-all duration-200 bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-800">
                    <i class="fas fa-users mr-2"></i>User Trends
                </button>
                <button onclick="showTab('ratings')" id="tab-ratings" class="tab-button flex-1 py-3 px-4 rounded-lg font-medium text-sm transition-all duration-200 bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-800">
                    <i class="fas fa-star mr-2"></i>Ratings
                </button>
                <button onclick="showTab('actions')" id="tab-actions" class="tab-button flex-1 py-3 px-4 rounded-lg font-medium text-sm transition-all duration-200 bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-800">
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-500/30 rounded-xl shadow-sm border-2 border-blue-500 p-4">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-blue-500 rounded-lg">
                            <i class="fas fa-calendar-day text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-800">Today's Bookings</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $stats['new_bookings'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-500/30 rounded-xl shadow-sm border-2 border-green-500 p-4">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-green-500 rounded-lg">
                            <i class="fas fa-check-circle text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-800">Completed Today</p>
                            <p class="text-2xl font-bold text-green-900">{{ $stats['completed_bookings'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-orange-500/30 rounded-xl shadow-sm border-2 border-orange-500 p-4">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-orange-500 rounded-lg">
                            <i class="fas fa-clock text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-orange-800">Pending Review</p>
                            <p class="text-2xl font-bold text-orange-900">{{ $stats['payment_hold_bookings'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-500/30 rounded-xl shadow-sm border-2 border-yellow-500 p-4">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-yellow-500 rounded-lg">
                            <i class="fas fa-star text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-yellow-800">New Ratings</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $stats['new_ratings'] ?? 0 }}</p>
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

            <!-- Service Ratings Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Service Ratings Overview</h2>
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                Overall: {{ $stats['average_rating'] ?? 0 }}/5
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-thumbs-up text-green-500 mr-1"></i>
                                {{ $stats['rated_services'] ?? 0 }} rated
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock text-orange-500 mr-1"></i>
                                {{ $stats['unrated_services'] ?? 0 }} unrated
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
                        
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="text-center">
                                <a href="#" onclick="showTab('ratings')" class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                                    <i class="fas fa-star mr-2"></i>
                                    View Detailed Rating Analytics
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No service data available</p>
                    @endif
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-base font-semibold text-gray-900">Recent Bookings</h2>
                </div>
                <div class="p-4">
                    @if(isset($stats['recent_bookings']) && $stats['recent_bookings']->count() > 0)
                        <div class="hidden md:grid grid-cols-12 text-[11px] font-medium text-gray-500 px-2 pb-2">
                            <div class="col-span-5">Booking</div>
                            <div class="col-span-3">Service Date & Time</div>
                            <div class="col-span-2">Status</div>
                            <div class="col-span-1 text-right">Amount</div>
                            <div class="col-span-1 text-right">Action</div>
                        </div>
                        <div class="space-y-2">
                            @foreach($stats['recent_bookings'] as $booking)
                            @php
                                $serviceDate = $booking->service_date ? ($booking->service_date instanceof \Carbon\Carbon ? $booking->service_date->format('M d, Y') : \Carbon\Carbon::parse($booking->service_date)->format('M d, Y')) : null;
                                $serviceTime = $booking->service_time ? ($booking->service_time instanceof \Carbon\Carbon ? $booking->service_time->format('h:i A') : \Carbon\Carbon::parse($booking->service_time)->format('h:i A')) : null;
                                $amount = optional($booking->payment)->total_fee;
                                $paymentStatus = optional($booking->payment)->payment_status;
                            @endphp
                            <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="hidden md:grid md:grid-cols-12 md:items-center md:gap-2">
                                    <div class="col-span-5 flex items-center space-x-3">
                                        <div class="w-1.5 h-1.5 rounded-full 
                                            {{ $booking->status === 'pending' ? 'bg-yellow-500' : 
                                               ($booking->status === 'acknowledged' ? 'bg-blue-500' : 
                                               ($booking->status === 'payment_hold' ? 'bg-orange-500' : 
                                               ($booking->status === 'approved' ? 'bg-green-500' : 
                                               ($booking->status === 'completed' ? 'bg-emerald-600' : 'bg-gray-500')))) }}">
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">#{{ $booking->id }} — {{ $booking->service->name ?? 'Unknown Service' }}</p>
                                            <p class="text-xs text-gray-600">{{ $booking->user->name ?? 'Unknown User' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-span-3 text-[12px] text-gray-700">
                                        @if($serviceDate || $serviceTime)
                                            <span>{{ $serviceDate ?? 'TBD' }}</span>
                                            <span class="text-gray-400">•</span>
                                            <span>{{ $serviceTime ?? '—' }}</span>
                                        @else
                                            <span class="text-gray-400">Schedule not set</span>
                                        @endif
                                    </div>
                                    <div class="col-span-2">
                                        <span class="text-[10px] font-medium px-2 py-0.5 rounded-full 
                                            {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($booking->status === 'acknowledged' ? 'bg-blue-100 text-blue-800' : 
                                               ($booking->status === 'payment_hold' ? 'bg-orange-100 text-orange-800' : 
                                               ($booking->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                               ($booking->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800')))) }}">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </div>
                                    <div class="col-span-1 text-right">
                                        @if(!is_null($amount))
                                            <span class="text-[12px] font-semibold text-gray-900">₱{{ number_format($amount, 2) }}</span>
                                        @else
                                            <span class="text-[12px] text-gray-400">—</span>
                                        @endif
                                    </div>
                                    <div class="col-span-1 text-right">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center p-2 rounded-md text-gray-500 hover:text-[#0d5c2f] hover:bg-white focus:outline-none focus:ring-2 focus:ring-[#0d5c2f]/20" title="View booking" aria-label="View booking">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="md:hidden space-y-1">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-1.5 h-1.5 mt-1 rounded-full 
                                                {{ $booking->status === 'pending' ? 'bg-yellow-500' : 
                                                   ($booking->status === 'acknowledged' ? 'bg-blue-500' : 
                                                   ($booking->status === 'payment_hold' ? 'bg-orange-500' : 
                                                   ($booking->status === 'approved' ? 'bg-green-500' : 
                                                   ($booking->status === 'completed' ? 'bg-emerald-600' : 'bg-gray-500')))) }}">
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">#{{ $booking->id }} — {{ $booking->service->name ?? 'Unknown Service' }}</p>
                                                <p class="text-xs text-gray-600">{{ $booking->user->name ?? 'Unknown User' }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center p-2 rounded-md text-gray-500 hover:text-[#0d5c2f] hover:bg-white focus:outline-none focus:ring-2 focus:ring-[#0d5c2f]/20" title="View booking" aria-label="View booking">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-between text-[12px]">
                                        <div class="text-gray-700">
                                            @if($serviceDate || $serviceTime)
                                                <span>{{ $serviceDate ?? 'TBD' }}</span>
                                                <span class="text-gray-400">•</span>
                                                <span>{{ $serviceTime ?? '—' }}</span>
                                            @else
                                                <span class="text-gray-400">Schedule not set</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-[10px] font-medium px-2 py-0.5 rounded-full 
                                                {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($booking->status === 'acknowledged' ? 'bg-blue-100 text-blue-800' : 
                                                   ($booking->status === 'payment_hold' ? 'bg-orange-100 text-orange-800' : 
                                                   ($booking->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                                   ($booking->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800')))) }}">
                                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                            </span>
                                            @if(!is_null($amount))
                                                <span class="text-[12px] font-semibold text-gray-900">₱{{ number_format($amount, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-[11px] text-gray-500">Created {{ $booking->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.bookings.index') }}" class="text-[#0d5c2f] hover:underline text-xs">View all bookings →</a>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-3 text-sm">No bookings found</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Finance Tab -->
        <div id="finance-tab" class="tab-content hidden">
            <!-- Today's Financial Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-green-500/30 rounded-xl shadow-sm border-2 border-green-500 p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-500 rounded-lg">
                            <i class="fas fa-money-bill-wave text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-800">Today's Revenue</p>
                            <p class="text-2xl font-bold text-green-900">₱{{ number_format($stats['new_revenue'] ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-500/30 rounded-xl shadow-sm border-2 border-blue-500 p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-500 rounded-lg">
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-800">This Month</p>
                            <p class="text-2xl font-bold text-blue-900">₱{{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-500/30 rounded-xl shadow-sm border-2 border-purple-500 p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-500 rounded-lg">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-purple-800">Avg. Transaction</p>
                            <p class="text-2xl font-bold text-purple-900">₱{{ number_format($stats['avg_transaction'] ?? 0, 2) }}</p>
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
                        @php
                            $hasRevenueData = collect($monthlyRevenue ?? [])->sum('amount') > 0;
                        @endphp
                        @if($hasRevenueData)
                            <canvas id="revenueTrendsChart" width="400" height="200"></canvas>
                        @else
                            <div class="flex flex-col items-center justify-center text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                <div class="p-3 bg-gray-200 rounded-full mb-3">
                                    <i class="fas fa-chart-line text-gray-600"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-700">No revenue data yet</p>
                                <p class="text-xs text-gray-500 mt-1">Verified revenue will appear here once transactions are recorded.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Method Distribution -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Payment Method Distribution</h2>
                    </div>
                    <div class="p-6">
                        @php
                            $totalPaymentsCount = intval($stats['gcash_payments'] ?? 0) + intval($stats['metrobank_payments'] ?? 0);
                        @endphp
                        @if($totalPaymentsCount > 0)
                            <canvas id="paymentMethodsChart" width="400" height="200"></canvas>
                        @else
                            <div class="flex flex-col items-center justify-center text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                <div class="p-3 bg-gray-200 rounded-full mb-3">
                                    <i class="fas fa-wallet text-gray-600"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-700">No payment data yet</p>
                                <p class="text-xs text-gray-500 mt-1">Once payments are made, their method breakdown will show here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Transactions</h2>
                </div>
                <div class="p-6">
                    @if(isset($stats['recent_transactions']) && $stats['recent_transactions']->count() > 0)
                        <div class="hidden md:grid grid-cols-12 text-[11px] font-medium text-gray-500 px-2 pb-2">
                            <div class="col-span-5">Booking</div>
                            <div class="col-span-3">Method & Status</div>
                            <div class="col-span-2 text-right">Amount</div>
                            <div class="col-span-1 text-right">Time</div>
                            <div class="col-span-1 text-right">Action</div>
                        </div>
                        <div class="space-y-2">
                            @foreach($stats['recent_transactions'] as $transaction)
                            @php
                                $booking = $transaction->booking;
                                $method = $transaction->payment_method_label ?? ($transaction->payment_method ? ucfirst($transaction->payment_method) : 'Unknown');
                                $status = $transaction->payment_status ?? 'unknown';
                            @endphp
                            <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="hidden md:grid md:grid-cols-12 md:items-center md:gap-2">
                                    <div class="col-span-5">
                                        <p class="text-sm font-medium text-gray-900">#{{ $booking ? $booking->id : $transaction->booking_id }} — {{ ($booking && $booking->service) ? $booking->service->name : 'Unknown Service' }}</p>
                                        <p class="text-xs text-gray-600">{{ ($booking && $booking->user) ? $booking->user->name : 'Unknown User' }}</p>
                                    </div>
                                    <div class="col-span-3 flex items-center space-x-2">
                                        <span class="text-[12px] text-gray-700">{{ $method }}</span>
                                        <span class="text-[10px] font-medium px-2 py-0.5 rounded-full 
                                            {{ $status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($status === 'paid' ? 'bg-blue-100 text-blue-800' : 
                                               ($status === 'verified' ? 'bg-green-100 text-green-800' : 
                                               ($status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </div>
                                    <div class="col-span-2 text-right">
                                        <span class="text-[12px] font-semibold text-gray-900">₱{{ number_format($transaction->total_fee, 2) }}</span>
                                    </div>
                                    <div class="col-span-1 text-right">
                                        <span class="text-[11px] text-gray-500">{{ $transaction->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="col-span-1 text-right">
                                        @if($booking)
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center p-2 rounded-md text-gray-500 hover:text-[#0d5c2f] hover:bg-white focus:outline-none focus:ring-2 focus:ring-[#0d5c2f]/20" title="View booking" aria-label="View booking">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="md:hidden space-y-1">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">#{{ $booking ? $booking->id : $transaction->booking_id }} — {{ ($booking && $booking->service) ? $booking->service->name : 'Unknown Service' }}</p>
                                            <p class="text-xs text-gray-600">{{ ($booking && $booking->user) ? $booking->user->name : 'Unknown User' }}</p>
                                        </div>
                                        @if($booking)
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center p-2 rounded-md text-gray-500 hover:text-[#0d5c2f] hover:bg-white focus:outline-none focus:ring-2 focus:ring-[#0d5c2f]/20" title="View booking" aria-label="View booking">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between text-[12px]">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-gray-700">{{ $method }}</span>
                                            <span class="text-[10px] font-medium px-2 py-0.5 rounded-full 
                                                {{ $status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($status === 'paid' ? 'bg-blue-100 text-blue-800' : 
                                                   ($status === 'verified' ? 'bg-green-100 text-green-800' : 
                                                   ($status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </div>
                                        <span class="text-[12px] font-semibold text-gray-900">₱{{ number_format($transaction->total_fee, 2) }}</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">{{ $transaction->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                            <div class="p-3 bg-gray-200 rounded-full mb-3">
                                <i class="fas fa-receipt text-gray-600"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-700">No recent transactions</p>
                            <p class="text-xs text-gray-500 mt-1">When payments are recorded, they will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Trends Tab -->
        <div id="trends-tab" class="tab-content hidden">
            <!-- Today's User Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-500/30 rounded-xl shadow-sm border-2 border-blue-500 p-4">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-blue-500 rounded-lg">
                            <i class="fas fa-user-plus text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-800">New Users Today</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $stats['new_users'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-500/30 rounded-xl shadow-sm border-2 border-green-500 p-4">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-green-500 rounded-lg">
                            <i class="fas fa-user-shield text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-800">Verified Users</p>
                            <p class="text-2xl font-bold text-green-900">{{ $stats['verified_users'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-500/30 rounded-xl shadow-sm border-2 border-purple-500 p-4">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-purple-500 rounded-lg">
                            <i class="fas fa-user-check text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-purple-800">Active Users</p>
                            <p class="text-2xl font-bold text-purple-900">{{ $stats['active_users'] ?? 0 }}</p>
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
                        <div class="space-y-3">
                            @php
                                $monthlyData = isset($monthlyUsers) ? $monthlyUsers : [];
                                $counts = array_map(fn($m) => $m['count'], $monthlyData);
                                $maxCount = count($counts) ? max($counts) : 0;
                            @endphp
                            @if(count($monthlyData) > 0)
                                @foreach($monthlyData as $idx => $m)
                                @break($idx >= 6)
                                @php $pct = $maxCount > 0 ? ($m['count'] / $maxCount) * 100 : 0; @endphp
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">{{ $m['month'] }}</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-28 bg-gray-200 rounded-full h-2">
                                            <div class="bg-[#0d5c2f] h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-gray-900">{{ $m['count'] }}</span>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                @for($i = 5; $i >= 0; $i--)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">{{ now()->subMonths($i)->format('M Y') }}</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-28 bg-gray-200 rounded-full h-2">
                                            <div class="bg-[#0d5c2f] h-2 rounded-full" style="width: 0%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-gray-900">0</span>
                                    </div>
                                </div>
                                @endfor
                            @endif
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
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-8 w-8 rounded-full bg-[#0d5c2f] flex items-center justify-center">
                                            <i class="fas fa-user-plus text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs text-gray-600">{{ $user->email }}</span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    New Registration
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                                        <p class="text-xs text-gray-400">Account Created</p>
                                    </div>
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

        <!-- Ratings Tab -->
        <div id="ratings-tab" class="tab-content hidden">
            <!-- Today's Rating Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-yellow-500/30 rounded-xl shadow-sm border-2 border-yellow-500 p-4">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-yellow-500 rounded-lg">
                            <i class="fas fa-star text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-yellow-800">New Ratings Today</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $stats['new_ratings'] ?? 0 }}</p>
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
                            <p class="text-2xl font-bold text-blue-900">{{ $stats['rated_services'] ?? 0 }}</p>
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
                            <p class="text-2xl font-bold text-orange-900">{{ $stats['unrated_services'] ?? 0 }}</p>
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
                            <p class="text-2xl font-bold text-green-900">{{ $stats['average_rating'] ?? 0 }}/5</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rating Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Ratings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-star text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Ratings</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_ratings'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Average Rating -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-chart-line text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Average Rating</p>
                            <div class="flex items-center space-x-1">
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['average_rating'] ?? 0 }}</p>
                                <span class="text-sm text-gray-600">/5</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rated Services -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-thumbs-up text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Rated Services</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['rated_services'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Unrated Services -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 rounded-lg">
                            <i class="fas fa-clock text-orange-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Unrated Services</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['unrated_services'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Rating Distribution Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Rating Distribution</h2>
                    </div>
                    <div class="p-6">
                        <canvas id="ratingDistributionChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Service Rating Comparison -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Top Rated Services</h2>
                    </div>
                    <div class="p-6">
                        @if(isset($stats['top_rated_services']) && $stats['top_rated_services']->count() > 0)
                            <div class="space-y-4">
                                @foreach($stats['top_rated_services'] as $topService)
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
                            <p class="text-gray-500 text-center py-4">No rating data available</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Ratings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Ratings</h2>
                </div>
                <div class="p-6">
                    @if(isset($stats['recent_ratings']) && $stats['recent_ratings']->count() > 0)
                        <div class="space-y-4">
                            @foreach($stats['recent_ratings'] as $rating)
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

        <!-- Quick Actions Tab -->
        <div id="actions-tab" class="tab-content hidden">
            <!-- Quick Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-lg p-4 text-white text-center">
                    <i class="fas fa-bookmark text-2xl mb-2"></i>
                    <p class="text-sm opacity-90">Total Bookings</p>
                    <p class="text-xl font-bold">{{ $stats['total_bookings'] ?? 0 }}</p>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white text-center">
                    <i class="fas fa-users text-2xl mb-2"></i>
                    <p class="text-sm opacity-90">Total Users</p>
                    <p class="text-xl font-bold">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white text-center">
                    <i class="fas fa-money-bill-wave text-2xl mb-2"></i>
                    <p class="text-sm opacity-90">Revenue</p>
                    <p class="text-xl font-bold">₱{{ number_format($stats['total_revenue'] ?? 0, 0) }}</p>
                </div>
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white text-center">
                    <i class="fas fa-chart-line text-2xl mb-2"></i>
                    <p class="text-sm opacity-90">Growth</p>
                    <p class="text-xl font-bold">{{ $stats['new_users_month'] ?? 0 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Content Management -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                        <div class="flex items-center">
                            <div class="p-3 bg-[#0d5c2f] rounded-lg mr-3">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Content Management</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('admin.cms.pages.create') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-[#0d5c2f]/10 hover:border-l-4 hover:border-l-[#0d5c2f] transition-all duration-200">
                                <i class="fas fa-plus text-[#0d5c2f] mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Create Page</span>
                                <i class="fas fa-arrow-right ml-auto text-gray-400"></i>
                            </a>
                            <a href="{{ route('admin.cms.media.create') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-[#0d5c2f]/10 hover:border-l-4 hover:border-l-[#0d5c2f] transition-all duration-200">
                                <i class="fas fa-upload text-[#0d5c2f] mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Upload Media</span>
                                <i class="fas fa-arrow-right ml-auto text-gray-400"></i>
                            </a>
                            <a href="{{ route('admin.cms.pages.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-[#0d5c2f]/10 hover:border-l-4 hover:border-l-[#0d5c2f] transition-all duration-200">
                                <i class="fas fa-list text-[#0d5c2f] mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Manage Pages</span>
                                <i class="fas fa-arrow-right ml-auto text-gray-400"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Booking Management -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-500 rounded-lg mr-3">
                                <i class="fas fa-calendar-check text-white"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Booking Management</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('admin.bookings.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-blue-50 hover:border-l-4 hover:border-l-blue-500 transition-all duration-200">
                                <i class="fas fa-bookmark text-blue-500 mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">View All Bookings</span>
                                <i class="fas fa-arrow-right ml-auto text-gray-400"></i>
                            </a>
                            <a href="{{ route('admin.services.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-blue-50 hover:border-l-4 hover:border-l-blue-500 transition-all duration-200">
                                <i class="fas fa-calendar-alt text-blue-500 mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Manage Services</span>
                                <i class="fas fa-arrow-right ml-auto text-gray-400"></i>
                            </a>
                            <a href="{{ route('admin.priests.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-blue-50 hover:border-l-4 hover:border-l-blue-500 transition-all duration-200">
                                <i class="fas fa-user-tie text-blue-500 mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Manage Priests</span>
                                <i class="fas fa-arrow-right ml-auto text-gray-400"></i>
                            </a>
                            <a href="{{ route('admin.parochial-activities.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-blue-50 hover:border-l-4 hover:border-l-blue-500 transition-all duration-200">
                                <i class="fas fa-church text-blue-500 mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Manage Activities</span>
                                <i class="fas fa-arrow-right ml-auto text-gray-400"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Management -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-500 rounded-lg mr-3">
                                <i class="fas fa-users-cog text-white"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">User Management</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-green-50 hover:border-l-4 hover:border-l-green-500 transition-all duration-200">
                                <i class="fas fa-users text-green-500 mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Manage Users</span>
                                <i class="fas fa-arrow-right ml-auto text-gray-400"></i>
                            </a>
                            <a href="{{ route('admin.users.create') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-green-50 hover:border-l-4 hover:border-l-green-500 transition-all duration-200">
                                <i class="fas fa-user-plus text-green-500 mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Add New User</span>
                                <i class="fas fa-arrow-right ml-auto text-gray-400"></i>
                            </a>
                            <a href="{{ route('home') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-green-50 hover:border-l-4 hover:border-l-green-500 transition-all duration-200">
                                <i class="fas fa-eye text-green-500 mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">View Public Site</span>
                                <i class="fas fa-arrow-right ml-auto text-gray-400"></i>
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
        button.classList.remove('bg-[#0d5c2f]', 'text-white', 'shadow-sm');
        button.classList.add('bg-gray-100', 'text-gray-600');
    });

    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');

    // Add active state to selected tab button
    document.getElementById('tab-' + tabName).classList.remove('bg-gray-100', 'text-gray-600');
    document.getElementById('tab-' + tabName).classList.add('bg-[#0d5c2f]', 'text-white', 'shadow-sm');
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
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: chartColors.primary,
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false
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
                            padding: 8
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
                    point: {
                        backgroundColor: chartColors.primary,
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        radius: 6,
                        hoverRadius: 8
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
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: chartColors.success,
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toLocaleString();
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
                                return '₱' + value.toLocaleString();
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
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: chartColors.info,
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                elements: {
                    arc: {
                        borderWidth: 2,
                        borderColor: '#ffffff'
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
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: chartColors.info,
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false
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
                            padding: 8
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
                    point: {
                        backgroundColor: chartColors.info,
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        radius: 6,
                        hoverRadius: 8
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
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: chartColors.purple,
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                elements: {
                    arc: {
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }
                }
            }
        });
    }

    // Rating Distribution Chart
    const ratingDistributionCtx = document.getElementById('ratingDistributionChart');
    if (ratingDistributionCtx) {
        // Get rating distribution data from the controller
        const ratingData = {
            '1_star': {{ $stats['rating_distribution']['1_star'] ?? 0 }},
            '2_star': {{ $stats['rating_distribution']['2_star'] ?? 0 }},
            '3_star': {{ $stats['rating_distribution']['3_star'] ?? 0 }},
            '4_star': {{ $stats['rating_distribution']['4_star'] ?? 0 }},
            '5_star': {{ $stats['rating_distribution']['5_star'] ?? 0 }}
        };

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
</script>
@endsection