@extends('layouts.admin')

@section('title', 'Admin - Bookings')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Bookings Management</h1>
                    <p class="text-white/80 mt-1">Manage all service bookings and their status</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="text-right text-white">
                        <div class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
                        <div class="text-sm opacity-80">Total Bookings</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Acknowledged</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['acknowledged'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Payment Hold</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['payment_hold'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Approved</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times text-red-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Rejected</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-flag-checkered text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Completed</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['completed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Tabs Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="grid grid-cols-7 border-b border-gray-200">
            <button id="tab-all" class="px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-list mr-2"></i> All
            </button>
            <button id="tab-pending" class="px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-clock mr-2"></i> Pending
            </button>
            <button id="tab-acknowledged" class="px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-check mr-2"></i> Acknowledged
            </button>
            <button id="tab-payment_hold" class="px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-clock mr-2"></i> Payment Hold
            </button>
            <button id="tab-approved" class="px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-check-circle mr-2"></i> Approved
            </button>
            <button id="tab-rejected" class="px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-times mr-2"></i> Rejected
            </button>
            <button id="tab-completed" class="px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-flag-checkered mr-2"></i> Completed
            </button>
        </div>
        
        <div class="p-6">
            <!-- View Toggle -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-2">
                    <button id="table-view-btn" class="px-4 py-2 text-sm font-medium text-[#0d5c2f] bg-[#0d5c2f]/10 rounded-lg border border-[#0d5c2f]/20">
                        <i class="fas fa-table mr-2"></i> Table View
                    </button>
                    <button id="card-view-btn" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg border border-gray-200 hover:bg-gray-200 transition-colors">
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Booking
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Service
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Time
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Service Fee
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 booking-row" 
                                data-booking-id="{{ is_array($booking->id) ? 0 : ($booking->id ?? 0) }}" 
                                data-service-fee="{{ $booking->service ? ($booking->service->getFeeForDate($booking->service_date)['amount'] ?? 0) : 0 }}"
                                data-status="{{ $booking->status }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            #{{ $booking->id }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $booking->user->name ?? 'Unknown User' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->service->name ?? 'Unknown Service' }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->contact_phone ?? 'No phone' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->formatted_date ?? 'No date' }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->formatted_time ?? 'No time' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                        <div class="text-xs text-gray-500">
                                            Set: ₱{{ number_format($booking->payment->total_fee, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
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
                                        <span class="ml-2 text-sm text-gray-500">
                                            ({{ ucfirst($booking->payment->payment_status) }})
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $booking->created_at->format('M d, Y g:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" 
                                           class="w-8 h-8 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-colors" title="View">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        
                                        @if($booking->status === 'pending')
                                            <button onclick="openAcknowledgeModal({{ $booking->id }})" class="w-8 h-8 rounded-full bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-colors" title="Acknowledge">
                                                <i class="fas fa-check text-sm"></i>
                                            </button>
                                        @endif

                                        @if($booking->status === 'payment_hold')
                                            <a href="{{ route('admin.bookings.show', $booking) }}#payment-verification" 
                                               class="w-8 h-8 rounded-full bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-colors" title="Verify Payment">
                                                <i class="fas fa-check-circle text-sm"></i>
                                            </a>
                                        @endif

                                        @if($booking->status === 'approved')
                                            <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="w-8 h-8 rounded-full bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-colors" title="Mark Complete">
                                                    <i class="fas fa-flag-checkered text-sm"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if(in_array($booking->status, ['pending', 'acknowledged', 'payment_hold']))
                                            <button onclick="openRejectModal({{ $booking->id }})" class="w-8 h-8 rounded-full bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-colors" title="Reject">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Card View -->
            <div id="card-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
                @foreach($bookings as $booking)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow booking-card" data-status="{{ $booking->status }}">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">#{{ $booking->id }}</h3>
                                    <p class="text-sm text-gray-500">{{ $booking->user->name ?? 'Unknown User' }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
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
                            
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Service:</span>
                                    <p class="text-sm text-gray-900">{{ $booking->service->name ?? 'Unknown Service' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Date & Time:</span>
                                    <p class="text-sm text-gray-900">{{ $booking->formatted_date ?? 'No date' }} at {{ $booking->formatted_time ?? 'No time' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Fee:</span>
                                    <p class="text-sm text-gray-900 font-medium">
                                        @if($booking->service)
                                            @php
                                                $feeInfo = $booking->service->getFeeForDate($booking->service_date);
                                                $feeAmount = $feeInfo['amount'] ?? 0;
                                            @endphp
                                            ₱{{ number_format($feeAmount, 2) }}
                                        @else
                                            ₱0.00
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Created:</span>
                                    <p class="text-sm text-gray-900">{{ $booking->created_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.bookings.show', $booking) }}" 
                                   class="w-8 h-8 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-colors" title="View">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                
                                @if($booking->status === 'pending')
                                    <button onclick="openAcknowledgeModal({{ $booking->id }})" class="w-8 h-8 rounded-full bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-colors" title="Acknowledge">
                                        <i class="fas fa-check text-sm"></i>
                                    </button>
                                @endif

                                @if($booking->status === 'payment_hold')
                                    <a href="{{ route('admin.bookings.show', $booking) }}#payment-verification" 
                                       class="w-8 h-8 rounded-full bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-colors" title="Verify Payment">
                                        <i class="fas fa-check-circle text-sm"></i>
                                    </a>
                                @endif

                                @if($booking->status === 'approved')
                                    <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-full bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-colors" title="Mark Complete">
                                            <i class="fas fa-flag-checkered text-sm"></i>
                                        </button>
                                    </form>
                                @endif

                                @if(in_array($booking->status, ['pending', 'acknowledged', 'payment_hold']))
                                    <button onclick="openRejectModal({{ $booking->id }})" class="w-8 h-8 rounded-full bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-colors" title="Reject">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="text-center py-12 hidden">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Bookings Found</h3>
                    <p class="text-gray-600 mb-4">There are no bookings matching the selected filter.</p>
                    <button onclick="resetFilter()" class="px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                        <i class="fas fa-refresh mr-2"></i>Show All Bookings
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="mt-6 border-t border-gray-200 pt-4">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Acknowledge Modal -->
<div id="acknowledgeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-lg w-full">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Acknowledge Booking</h3>
                        <p class="text-sm text-gray-600">Set payment details and acknowledge the booking</p>
                    </div>
                </div>
                
                <form id="acknowledgeForm" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="total_fee" class="block text-sm font-medium text-gray-700 mb-2">
                                Total Fee (₱) *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                <input type="number" 
                                       id="total_fee" 
                                       name="total_fee" 
                                       step="0.01" 
                                       min="0"
                                       required
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                       placeholder="0.00">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Enter the total amount the user needs to pay</p>
                        </div>
                        
                        <div>
                            <label for="acknowledge_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea id="acknowledge_notes" 
                                      name="notes" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                      placeholder="Add any notes about the acknowledgment or payment instructions"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-3 mt-6">
                        <button type="button" 
                                onclick="closeModal('acknowledgeModal')"
                                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            <i class="fas fa-check mr-2"></i>Acknowledge Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Booking</h3>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Rejection *
                        </label>
                        <textarea id="reject_notes" 
                                  name="notes" 
                                  rows="3"
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                  placeholder="Please provide a reason for rejecting this booking"></textarea>
                    </div>
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" 
                                onclick="closeModal('rejectModal')"
                                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Reject Booking
                        </button>
                    </div>
                </form>
            </div>
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

function openAcknowledgeModal(bookingId) {
    const form = document.getElementById('acknowledgeForm');
    form.action = `/admin/bookings/${bookingId}/acknowledge`;
    
    // Get the booking data to populate the total fee
    const bookingRow = document.querySelector(`tr[data-booking-id="${bookingId}"]`);
    if (bookingRow) {
        const serviceFee = bookingRow.getAttribute('data-service-fee');
        const totalFeeInput = document.getElementById('total_fee');
        if (totalFeeInput && serviceFee) {
            totalFeeInput.value = serviceFee;
        }
    }
    
    document.getElementById('acknowledgeModal').classList.remove('hidden');
}

function openRejectModal(bookingId) {
    const form = document.getElementById('rejectForm');
    form.action = `/admin/bookings/${bookingId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    // Reset form
    const form = document.getElementById(modalId === 'acknowledgeModal' ? 'acknowledgeForm' : 'rejectForm');
    form.reset();
}

// Close modals when clicking outside
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});
</script>
@endsection 