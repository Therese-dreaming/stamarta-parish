@extends('layouts.priest')

@section('title', 'My Bookings')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">My Bookings</h1>
                    <p class="text-white/80 mt-1 text-sm">View your assigned bookings and services</p>
                </div>
                <a href="{{ route('priest.bookings.calendar') }}" 
                   class="w-11 h-11 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Calendar View">
                    <i class="fas fa-calendar-alt text-base"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Upcoming Bookings -->
    @php
        $today = \Carbon\Carbon::today();
        $upcoming = $bookings->filter(function($b) use ($today) {
            try {
                $date = \Carbon\Carbon::parse($b->service_date);
            } catch (\Throwable $e) {
                return false;
            }
            return in_array($b->status, ['pending','acknowledged','payment_hold','approved']) && $date->gte($today);
        })->sortBy('service_date')->take(5);
    @endphp
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-lg bg-[#0d5c2f]/10 flex items-center justify-center">
                    <i class="fas fa-calendar-day text-[#0d5c2f]"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Upcoming Bookings</h2>
                    <p class="text-xs text-gray-500">Next scheduled items for quick access</p>
                </div>
            </div>
            <a href="{{ route('priest.bookings.index') }}" class="text-xs text-[#0d5c2f] hover:text-[#0d5c2f]/80">View all</a>
        </div>
        <div class="p-4">
            @if($upcoming->count())
                <div class="space-y-3">
                    @foreach($upcoming as $u)
                        <div class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2.5 border border-gray-200">
                            <div class="min-w-0">
                                <div class="flex items-center space-x-2">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $u->service->name ?? 'Unknown Service' }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium 
                                        @if($u->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($u->status === 'acknowledged') bg-blue-100 text-blue-800
                                        @elseif($u->status === 'payment_hold') bg-orange-100 text-orange-800
                                        @elseif($u->status === 'approved') bg-green-100 text-green-800
                                        @elseif($u->status === 'rejected') bg-red-100 text-red-800
                                        @elseif($u->status === 'completed') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $u->status)) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 mt-0.5">{{ $u->formatted_date ?? \Carbon\Carbon::parse($u->service_date)->format('M d, Y') }} @if(!empty($u->formatted_time))<span class="text-gray-400">•</span> {{ $u->formatted_time }}@endif</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('priest.bookings.show', $u) }}" class="px-3 py-1.5 rounded-md bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs transition-colors">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-calendar-xmark text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1">No Upcoming Bookings</h3>
                    <p class="text-sm text-gray-600 mb-4">You're all caught up. Check the calendar for availability.</p>
                    <a href="{{ route('priest.bookings.calendar') }}" class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors text-sm">
                        <i class="fas fa-calendar-alt mr-2"></i>Go to Calendar
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 transition duration-200 hover:shadow-md hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-7 h-7 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Pending</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 transition duration-200 hover:shadow-md hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check text-blue-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Acknowledged</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['acknowledged'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 transition duration-200 hover:shadow-md hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-7 h-7 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Payment Hold</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['payment_hold'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 transition duration-200 hover:shadow-md hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-7 h-7 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Approved</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['approved'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 transition duration-200 hover:shadow-md hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-7 h-7 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times text-red-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Rejected</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['rejected'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 transition duration-200 hover:shadow-md hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-7 h-7 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-flag-checkered text-green-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Completed</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['completed'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Tabs Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="grid grid-cols-7 border-b border-gray-200">
            <button id="tab-all" class="px-5 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent rounded-t">
                <i class="fas fa-list mr-2"></i> All
            </button>
            <button id="tab-pending" class="px-5 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent rounded-t">
                <i class="fas fa-clock mr-2"></i> Pending
            </button>
            <button id="tab-acknowledged" class="px-5 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent rounded-t">
                <i class="fas fa-check mr-2"></i> Acknowledged
            </button>
            <button id="tab-payment_hold" class="px-5 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent rounded-t">
                <i class="fas fa-clock mr-2"></i> Payment Hold
            </button>
            <button id="tab-approved" class="px-5 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent rounded-t">
                <i class="fas fa-check-circle mr-2"></i> Approved
            </button>
            <button id="tab-rejected" class="px-5 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent rounded-t">
                <i class="fas fa-times mr-2"></i> Rejected
            </button>
            <button id="tab-completed" class="px-5 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent rounded-t">
                <i class="fas fa-flag-checkered mr-2"></i> Completed
            </button>
        </div>
        
        <div class="p-6">
            <!-- View Toggle -->
            <div class="flex justify-between items-center mb-5">
                <div class="flex items-center space-x-2">
                    <button id="table-view-btn" class="px-3 py-2 text-sm font-medium text-[#0d5c2f] bg-[#0d5c2f]/10 rounded-lg border border-[#0d5c2f]/20">
                        <i class="fas fa-table mr-2"></i> Table View
                    </button>
                    <button id="card-view-btn" class="px-3 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg border border-gray-200 hover:bg-gray-200 transition-colors">
                        <i class="fas fa-th-large mr-2"></i> Card View
                    </button>
                </div>
                <div class="text-sm text-gray-500">
                    <span id="filtered-count">{{ $bookings->count() }}</span> bookings found
                </div>
            </div>
            <!-- Table View -->
            <div id="table-view" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                Booking
                            </th>
                            <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                Service
                            </th>
                            <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                Date & Time
                            </th>
                            <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                Service Fee
                            </th>
                            <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 transition-colors booking-row" data-status="{{ $booking->status }}">
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            #{{ $booking->id }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $booking->user->name ?? 'Unknown User' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->service->name ?? 'Unknown Service' }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->contact_phone ?? 'No phone' }}</div>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->formatted_date ?? 'No date' }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->formatted_time ?? 'No time' }}</div>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">
                                        @if($booking->service)
                                            @php
                                                $feeInfo = $booking->service->getFeeForDate($booking->service_date);
                                                $feeAmount = $feeInfo['amount'] ?? 0;
                                            @endphp
                                            ₱{{ number_format($feeAmount, 2) }}
                                        @else
                                            ₱0.00
                                        @endif
                                    </div>
                                    @if($booking->payment && $booking->payment->total_fee)
                                        <div class="text-[11px] text-gray-500">
                                            Set: ₱{{ number_format($booking->payment->total_fee, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium 
                                        @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status === 'acknowledged') bg-blue-100 text-blue-800
                                        @elseif($booking->status === 'payment_hold') bg-orange-100 text-orange-800
                                        @elseif($booking->status === 'approved') bg-green-100 text-green-800
                                        @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                                        @elseif($booking->status === 'completed') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                    @if($booking->payment && $booking->payment->payment_status)
                                        <span class="ml-2 text-xs text-gray-500">
                                            ({{ ucfirst($booking->payment->payment_status) }})
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-xs text-gray-500">
                                    {{ $booking->created_at->format('M d, Y g:i A') }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('priest.bookings.show', $booking) }}" 
                                           class="w-8 h-8 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-colors" title="View">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Card View -->
            <div id="card-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 hidden">
                @foreach($bookings as $booking)
                    @php
                        $statusColor = match($booking->status) {
                            'pending' => 'bg-yellow-500',
                            'acknowledged' => 'bg-blue-500',
                            'payment_hold' => 'bg-orange-500',
                            'approved' => 'bg-green-500',
                            'rejected' => 'bg-red-500',
                            'completed' => 'bg-green-600',
                            default => 'bg-gray-400'
                        };
                        $paymentStatus = $booking->payment->payment_status ?? null;
                    @endphp
                    <div class="relative bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition transform hover:-translate-y-0.5 booking-card" data-status="{{ $booking->status }}">
                        <div class="absolute inset-x-0 top-0 h-1.5 {{ $statusColor }}"></div>
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-base font-semibold text-gray-900">#{{ $booking->id }}</h3>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium 
                                            @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status === 'acknowledged') bg-blue-100 text-blue-800
                                            @elseif($booking->status === 'payment_hold') bg-orange-100 text-orange-800
                                            @elseif($booking->status === 'approved') bg-green-100 text-green-800
                                            @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                                            @elseif($booking->status === 'completed') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $booking->user->name ?? 'Unknown User' }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 text-gray-700 text-[11px]">
                                        <i class="fas fa-clock mr-1"></i>{{ $booking->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2.5">
                                <div class="flex items-center text-sm text-gray-900">
                                    <i class="fas fa-concierge-bell text-gray-400 mr-2"></i>
                                    <span class="font-medium">{{ $booking->service->name ?? 'Unknown Service' }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-900">
                                    <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                    <span>{{ $booking->formatted_date ?? 'No date' }} <span class="text-gray-500">at</span> {{ $booking->formatted_time ?? 'No time' }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-900">
                                    <i class="fas fa-phone text-gray-400 mr-2"></i>
                                    <span>{{ $booking->contact_phone ?? 'No phone' }}</span>
                                </div>

                                <div class="flex items-center flex-wrap gap-2 pt-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <i class="fas fa-money-bill-wave mr-1"></i>
                                        @php
                                            $feeInfo = $booking->service ? $booking->service->getFeeForDate($booking->service_date) : ['amount' => 0];
                                            $feeAmount = $feeInfo['amount'] ?? 0;
                                        @endphp
                                        ₱{{ number_format($feeAmount, 2) }}
                                    </span>
                                    @if($booking->payment && $booking->payment->total_fee)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] bg-gray-50 text-gray-700 border border-gray-200">
                                            Set: ₱{{ number_format($booking->payment->total_fee, 2) }}
                                        </span>
                                    @endif
                                    @if($paymentStatus)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] border 
                                            @class([
                                                'bg-blue-50 text-blue-700 border-blue-200' => $paymentStatus === 'pending',
                                                'bg-green-50 text-green-700 border-green-200' => $paymentStatus === 'verified',
                                                'bg-red-50 text-red-700 border-red-200' => $paymentStatus === 'rejected',
                                                'bg-gray-50 text-gray-700 border-gray-200' => !in_array($paymentStatus, ['pending','verified','rejected'])
                                            ])>
                                            <i class="fas fa-receipt mr-1"></i>{{ ucfirst($paymentStatus) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-5 py-3.5 flex justify-between items-center">
                            <div class="text-xs text-gray-500">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-id-badge mr-1"></i>Booking ID: #{{ $booking->id }}
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('priest.bookings.show', $booking) }}" 
                                   class="px-3 py-2 rounded-md bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm transition-colors" title="View">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="text-center py-10 hidden">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-calendar-times text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1">No Bookings Found</h3>
                    <p class="text-gray-600 mb-4 text-sm">There are no bookings matching the selected filter.</p>
                    <button onclick="resetFilter()" class="px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors text-sm">
                        <i class="fas fa-refresh mr-2"></i>Show All Bookings
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="mt-5 border-t border-gray-200 pt-4">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Tab filtering functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('[id^="tab-"]');
    const bookingRows = document.querySelectorAll('.booking-row');
    const bookingCards = document.querySelectorAll('.booking-card');
    const emptyState = document.getElementById('empty-state');
    const tableView = document.getElementById('table-view');
    const cardView = document.getElementById('card-view');
    const filteredCount = document.getElementById('filtered-count');
    
    function filterBookings(status) {
        let visibleCount = 0;
        
        // Filter table rows
        bookingRows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            if (status === 'all' || rowStatus === status) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Filter card elements
        bookingCards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            if (status === 'all' || cardStatus === status) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Update count
        filteredCount.textContent = visibleCount;
        
        // Show/hide empty state
        if (visibleCount === 0) {
            emptyState.classList.remove('hidden');
            tableView.classList.add('hidden');
            cardView.classList.add('hidden');
        } else {
            emptyState.classList.add('hidden');
            if (currentView === 'table') {
                tableView.classList.remove('hidden');
                cardView.classList.add('hidden');
            } else {
                tableView.classList.add('hidden');
                cardView.classList.remove('hidden');
            }
        }
    }
    
    function setActiveTab(activeTab) {
        tabs.forEach(tab => {
            tab.classList.remove('text-[#0d5c2f]', 'border-[#0d5c2f]');
            tab.classList.add('text-gray-600', 'border-transparent');
        });
        activeTab.classList.remove('text-gray-600', 'border-transparent');
        activeTab.classList.add('text-[#0d5c2f]', 'border-[#0d5c2f]');
    }
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const status = this.id.replace('tab-', '');
            filterBookings(status);
            setActiveTab(this);
        });
    });
    
    // View toggle functionality
    const tableViewBtn = document.getElementById('table-view-btn');
    const cardViewBtn = document.getElementById('card-view-btn');
    let currentView = 'table';
    
    function showTableView() {
        tableView.classList.remove('hidden');
        cardView.classList.add('hidden');
        tableViewBtn.classList.add('text-[#0d5c2f]', 'bg-[#0d5c2f]/10', 'border-[#0d5c2f]/20');
        tableViewBtn.classList.remove('text-gray-600', 'bg-gray-100', 'border-gray-200');
        cardViewBtn.classList.remove('text-[#0d5c2f]', 'bg-[#0d5c2f]/10', 'border-[#0d5c2f]/20');
        cardViewBtn.classList.add('text-gray-600', 'bg-gray-100', 'border-gray-200');
        currentView = 'table';
    }
    
    function showCardView() {
        tableView.classList.add('hidden');
        cardView.classList.remove('hidden');
        cardViewBtn.classList.add('text-[#0d5c2f]', 'bg-[#0d5c2f]/10', 'border-[#0d5c2f]/20');
        cardViewBtn.classList.remove('text-gray-600', 'bg-gray-100', 'border-gray-200');
        tableViewBtn.classList.remove('text-[#0d5c2f]', 'bg-[#0d5c2f]/10', 'border-[#0d5c2f]/20');
        tableViewBtn.classList.add('text-gray-600', 'bg-gray-100', 'border-gray-200');
        currentView = 'card';
    }
    
    tableViewBtn.addEventListener('click', showTableView);
    cardViewBtn.addEventListener('click', showCardView);
    
    function resetFilter() {
        setActiveTab(document.getElementById('tab-all'));
        filterBookings('all');
    }
    
    // Make resetFilter globally available
    window.resetFilter = resetFilter;
    
    // Set initial active tab
    setActiveTab(document.getElementById('tab-all'));
});
</script>
@endsection 