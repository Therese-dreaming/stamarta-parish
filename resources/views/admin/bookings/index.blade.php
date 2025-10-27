@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Admin - Bookings')

@section('content')
@include('components.toast')
<div class="space-y-4">
    
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-lg shadow-md overflow-hidden">
        <div class="px-4 py-4 relative">
            <div class="absolute right-0 top-0 w-16 h-16 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-xl font-bold text-white">Bookings Management</h1>
                    <p class="text-white/80 mt-1 text-xs">Manage all service bookings and their status</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route(isset($isStaff) && $isStaff ? 'staff.bookings.export' : 'admin.bookings.export') }}" 
                           class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200 flex items-center space-x-2 text-sm font-medium border border-white/30">
                            <i class="fas fa-file-excel"></i>
                            <span>Export All</span>
                        </a>
                        <button onclick="openExportModal()" 
                           class="px-4 py-2 bg-white hover:bg-white/90 text-[#0d5c2f] rounded-lg transition-all duration-200 flex items-center space-x-2 text-sm font-medium shadow-sm">
                            <i class="fas fa-filter"></i>
                            <span>Export Filtered</span>
                        </button>
                        <button onclick="openPdfPreviewModal()" 
                           class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200 flex items-center space-x-2 text-sm font-medium border border-white/30">
                            <i class="fas fa-file-pdf"></i>
                            <span>Preview PDF</span>
                        </button>
                    </div>
                    <div class="text-right text-white">
                        <div class="text-lg font-bold">{{ $stats['total'] ?? 0 }}</div>
                        <div class="text-xs opacity-80">Total Bookings</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-yellow-100 rounded-md flex items-center justify-center mr-2">
                    <i class="fas fa-clock text-yellow-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Pending</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-blue-100 rounded-md flex items-center justify-center mr-2">
                    <i class="fas fa-check text-blue-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Acknowledged</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['acknowledged'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-orange-100 rounded-md flex items-center justify-center mr-2">
                    <i class="fas fa-clock text-orange-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Payment Hold</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['payment_hold'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center mr-2">
                    <i class="fas fa-check-circle text-green-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Approved</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-red-100 rounded-md flex items-center justify-center mr-2">
                    <i class="fas fa-times text-red-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Rejected</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center mr-2">
                    <i class="fas fa-flag-checkered text-green-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Completed</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['completed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Tabs Filter -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="grid grid-cols-7 border-b border-gray-200">
            <button id="tab-all" class="px-3 py-3 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-list mr-1"></i> All
            </button>
            <button id="tab-pending" class="px-3 py-3 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-clock mr-1"></i> Pending
            </button>
            <button id="tab-acknowledged" class="px-3 py-3 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-check mr-1"></i> Acknowledged
            </button>
            <button id="tab-payment_hold" class="px-3 py-3 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-clock mr-1"></i> Payment Hold
            </button>
            <button id="tab-approved" class="px-3 py-3 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-check-circle mr-1"></i> Approved
            </button>
            <button id="tab-rejected" class="px-3 py-3 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-times mr-1"></i> Rejected
            </button>
            <button id="tab-completed" class="px-3 py-3 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-flag-checkered mr-1"></i> Completed
            </button>
        </div>
        
        <div class="p-4">
            <!-- View Toggle -->
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center space-x-2">
                    <button id="table-view-btn" class="px-3 py-1.5 text-xs font-medium text-[#0d5c2f] bg-[#0d5c2f]/10 rounded-md border border-[#0d5c2f]/20">
                        <i class="fas fa-table mr-1.5"></i> Table View
                    </button>
                    <button id="card-view-btn" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-md border border-gray-200 hover:bg-gray-200 transition-colors">
                        <i class="fas fa-th-large mr-1.5"></i> Card View
                    </button>
                </div>
                <div class="text-xs text-gray-500">
                    <span id="filtered-count">{{ $bookings->count() }}</span> bookings found
                </div>
            </div>

            <!-- Table View -->
            <div id="table-view" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Fee</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                            @php
                                $serviceFee = 0;
                                if ($booking->service) {
                                    $feeInfo = $booking->service->getFeeForDate($booking->service_date);
                                    $serviceFee = is_array($feeInfo) ? ($feeInfo['amount'] ?? 0) : 0;
                                    $serviceFee = is_numeric($serviceFee) ? (float)$serviceFee : 0;
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 booking-row transition-colors duration-200" 
                                data-booking-id="{{ is_array($booking->id) ? 0 : ($booking->id ?? 0) }}" 
                                data-service-fee="{{ $serviceFee }}"
                                data-status="{{ $booking->status }}"
                                data-total-fee="{{ $booking->payment ? $booking->payment->total_fee : '' }}">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">#{{ $booking->id }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->user->name ?? 'Unknown User' }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->service->name ?? 'Unknown Service' }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->contact_phone ?? 'No phone' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->formatted_date ?? 'No date' }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->formatted_time ?? 'No time' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">
                                        ₱{{ number_format($serviceFee, 2) }}
                                    </div>
                                    @if($booking->payment && $booking->payment->total_fee)
                                        <div class="text-xs text-gray-500">
                                            Set: ₱{{ number_format($booking->payment->total_fee, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
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
                                        <span class="ml-1 text-xs text-gray-500">
                                            ({{ ucfirst($booking->payment->payment_status) }})
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                                    {{ $booking->created_at->format('M d, Y g:i A') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-1">
                                        <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.show', $booking) : route('admin.bookings.show', $booking) }}" 
                                           class="w-6 h-6 rounded-md bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-colors" title="View">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        
                                        @if($booking->status === 'pending')
                                            <button onclick="openAcknowledgeModal({{ $booking->id }})" class="w-6 h-6 rounded-md bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-colors" title="Acknowledge">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        @endif

                                        @if($booking->status === 'payment_hold')
                                            <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.show', $booking) : route('admin.bookings.show', $booking) }}#payment-verification" 
                                               class="w-6 h-6 rounded-md bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-colors" title="Verify Payment">
                                                <i class="fas fa-check-circle text-xs"></i>
                                            </a>
                                        @endif

                                        @if($booking->status === 'approved')
                                            <button onclick="openCompleteModal({{ $booking->id }})" class="w-6 h-6 rounded-md bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-colors" title="Mark Complete">
                                                <i class="fas fa-flag-checkered text-xs"></i>
                                            </button>
                                        @endif

                                        @if(in_array($booking->status, ['pending', 'acknowledged', 'payment_hold']))
                                            <button onclick="openRejectModal({{ $booking->id }})" class="w-6 h-6 rounded-md bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-colors" title="Cancel Booking">
                                                <i class="fas fa-times text-xs"></i>
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
            <div id="card-view" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
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
                        
                        // Calculate service fee safely
                        $serviceFee = 0;
                        if ($booking->service) {
                            $feeInfo = $booking->service->getFeeForDate($booking->service_date);
                            $serviceFee = is_array($feeInfo) ? ($feeInfo['amount'] ?? 0) : 0;
                            $serviceFee = is_numeric($serviceFee) ? (float)$serviceFee : 0;
                        }
                    @endphp
                    <div class="relative bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition transform hover:-translate-y-0.5 booking-card" data-status="{{ $booking->status }}">
                        <div class="absolute inset-x-0 top-0 h-1.5 {{ $statusColor }}"></div>
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2.5">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-sm font-semibold text-gray-900">#{{ $booking->id }}</h3>
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
                                    <p class="text-[11px] text-gray-500 mt-1">{{ $booking->user->name ?? 'Unknown User' }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 text-gray-700 text-[11px]">
                                        <i class="fas fa-clock mr-1"></i>{{ $booking->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
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
                                        ₱{{ number_format($serviceFee, 2) }}
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
                        
                        <div class="bg-gray-50 px-4 py-3 flex justify-between items-center">
                            <div class="text-[11px] text-gray-500">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-id-badge mr-1"></i>Booking ID: #{{ $booking->id }}
                                </span>
                            </div>
                            <div class="flex space-x-1">
                                <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.show', $booking) : route('admin.bookings.show', $booking) }}" 
                                   class="px-2.5 py-1.5 rounded-md bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs transition-colors" title="View">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>

                                @if($booking->status === 'pending')
                                    <button onclick="openAcknowledgeModal({{ $booking->id }})" class="px-2.5 py-1.5 rounded-md bg-green-100 hover:bg-green-200 text-green-700 text-xs transition-colors" title="Acknowledge">
                                        <i class="fas fa-check mr-1"></i> Ack
                                    </button>
                                @endif

                                @if($booking->status === 'payment_hold')
                                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.show', $booking) : route('admin.bookings.show', $booking) }}#payment-verification" 
                                       class="px-2.5 py-1.5 rounded-md bg-green-100 hover:bg-green-200 text-green-700 text-xs transition-colors" title="Verify Payment">
                                        <i class="fas fa-check-circle mr-1"></i> Verify
                                    </a>
                                @endif

                                @if($booking->status === 'approved')
                                    <button onclick="openCompleteModal({{ $booking->id }})" class="px-2.5 py-1.5 rounded-md bg-green-100 hover:bg-green-200 text-green-700 text-xs transition-colors" title="Mark Complete">
                                        <i class="fas fa-flag-checkered mr-1"></i> Complete
                                    </button>
                                @endif

                                @if(in_array($booking->status, ['pending', 'acknowledged', 'payment_hold']))
                                    <button onclick="openRejectModal({{ $booking->id }})" class="px-2.5 py-1.5 rounded-md bg-red-100 hover:bg-red-200 text-red-700 text-xs transition-colors" title="Reject">
                                        <i class="fas fa-times mr-1"></i> Reject
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="text-center py-8 hidden">
                <div class="max-w-md mx-auto">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-calendar-times text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-2">No Bookings Found</h3>
                    <p class="text-sm text-gray-600 mb-3">There are no bookings matching the selected filter.</p>
                    <button onclick="resetFilter()" class="px-3 py-1.5 bg-[#0d5c2f] text-white rounded-md hover:bg-[#0d5c2f]/90 transition-colors text-xs">
                        <i class="fas fa-refresh mr-1.5"></i>Show All Bookings
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="mt-4 border-t border-gray-200 pt-3">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Acknowledge Modal -->
<div id="acknowledgeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
            <div class="p-4">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-check text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Acknowledge Booking</h3>
                        <p class="text-xs text-gray-600">Set payment details and acknowledge the booking</p>
                    </div>
                </div>
                
                <form id="acknowledgeForm" method="POST">
                    @csrf
                    
                    <div class="space-y-3">
                        <div>
                            <label for="total_fee" class="block text-xs font-medium text-gray-700 mb-1">
                                Total Fee (₱) *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">₱</span>
                                <input type="number" 
                                       id="total_fee" 
                                       name="total_fee" 
                                       step="0.01" 
                                       min="0"
                                       required
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm"
                                       placeholder="0.00">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Enter the total amount the user needs to pay</p>
                        </div>
                        
                        <div>
                            <label for="acknowledge_notes" class="block text-xs font-medium text-gray-700 mb-1">
                                Notes (Optional)
                            </label>
                            <textarea id="acknowledge_notes" 
                                      name="notes" 
                                      rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm"
                                      placeholder="Add any notes about the acknowledgment or payment instructions"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-2 mt-4">
                        <button type="button" 
                                onclick="closeModal('acknowledgeModal')"
                                class="px-3 py-1.5 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors text-xs">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-medium text-xs">
                            <i class="fas fa-check mr-1.5"></i>Acknowledge Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Booking Modal (copied design from show page) -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-ban text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Cancel Booking</h3>
                        <p class="text-sm text-gray-600">Are you sure you want to cancel this booking?</p>
                    </div>
                </div>
                
                <div class="bg-yellow-50 rounded-lg p-4 mb-6 border border-yellow-200">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
                        <p class="text-sm text-yellow-700">
                            This action will cancel the booking and notify the user. This cannot be undone.
                        </p>
                    </div>
                </div>
                
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Cancellation *
                        </label>
                        <textarea id="reject_notes" 
                                  name="notes" 
                                  rows="3"
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                  placeholder="Please provide a reason for cancelling this booking"></textarea>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" 
                                onclick="closeModal('rejectModal')"
                                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Go Back
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                            <i class="fas fa-ban mr-2"></i>Cancel Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Complete Modal -->
<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
            <div class="p-4">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-flag-checkered text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Complete Booking</h3>
                        <p class="text-xs text-gray-600">Mark this booking as completed</p>
                    </div>
                </div>
                
                <form id="completeForm" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="complete_notes" class="block text-xs font-medium text-gray-700 mb-1">
                            Completion Notes (Optional)
                        </label>
                        <textarea id="complete_notes" 
                                  name="notes" 
                                  rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm"
                                  placeholder="Add any notes about the completion"></textarea>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-2">
                        <button type="button" 
                                onclick="closeModal('completeModal')"
                                class="px-3 py-1.5 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors text-xs">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-1.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors font-medium text-xs">
                            <i class="fas fa-flag-checkered mr-1.5"></i>Complete Booking
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
    @if(isset($isStaff) && $isStaff)
        form.action = `/staff/bookings/${bookingId}/acknowledge`;
    @else
        form.action = `/admin/bookings/${bookingId}/acknowledge`;
    @endif
    
    // Get the booking data to populate the total fee
    const bookingRow = document.querySelector(`tr[data-booking-id="${bookingId}"]`);
    if (bookingRow) {
        const serviceFee = bookingRow.getAttribute('data-service-fee');
        const existingTotalFee = bookingRow.getAttribute('data-total-fee');
        const totalFeeInput = document.getElementById('total_fee');
        if (totalFeeInput) {
            // Use existing total fee if available, otherwise use service fee
            totalFeeInput.value = existingTotalFee || serviceFee || '';
        }
    }
    
    document.getElementById('acknowledgeModal').classList.remove('hidden');
}

function openRejectModal(bookingId) {
    const form = document.getElementById('rejectForm');
    @if(isset($isStaff) && $isStaff)
        form.action = `/staff/bookings/${bookingId}/reject`;
    @else
        form.action = `/admin/bookings/${bookingId}/reject`;
    @endif
    document.getElementById('rejectModal').classList.remove('hidden');
}

function openCompleteModal(bookingId) {
    const form = document.getElementById('completeForm');
    @if(isset($isStaff) && $isStaff)
        form.action = `/staff/bookings/${bookingId}/complete`;
    @else
        form.action = `/admin/bookings/${bookingId}/complete`;
    @endif
    document.getElementById('completeModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    // Reset form
    const form = document.getElementById(modalId === 'acknowledgeModal' ? 'acknowledgeForm' : 
                                     modalId === 'rejectModal' ? 'rejectForm' : 
                                     modalId === 'completeModal' ? 'completeForm' : null);
    if (form) {
        form.reset();
    }
}

// Close modals when clicking outside
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});

// Export Modal Functions
function openExportModal() {
    document.getElementById('exportModal').classList.remove('hidden');
}

function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}

// PDF Preview Modal Functions
function openPdfPreviewModal() {
    document.getElementById('pdfPreviewModal').classList.remove('hidden');
}

function closePdfPreviewModal() {
    document.getElementById('pdfPreviewModal').classList.add('hidden');
}

function previewPdf() {
    const dateFrom = document.getElementById('pdf_date_from').value;
    const dateTo = document.getElementById('pdf_date_to').value;
    
    // Get selected statuses
    const selectedStatuses = Array.from(document.querySelectorAll('.pdf-status-checkbox:checked'))
        .map(cb => cb.value);
    
    // Get selected services
    const selectedServices = Array.from(document.querySelectorAll('.pdf-service-checkbox:checked'))
        .map(cb => cb.value);
    
    // Build query string
    const params = new URLSearchParams();
    if (dateFrom) params.append('date_from', dateFrom);
    if (dateTo) params.append('date_to', dateTo);
    
    // Add multiple statuses
    selectedStatuses.forEach(status => {
        params.append('status[]', status);
    });
    
    // Add multiple services
    selectedServices.forEach(service => {
        params.append('service[]', service);
    });
    
    // Open PDF in new tab
    const pdfUrl = '{{ route(isset($isStaff) && $isStaff ? "staff.bookings.pdf" : "admin.bookings.pdf") }}?' + params.toString();
    window.open(pdfUrl, '_blank');
    
    // Close modal
    closePdfPreviewModal();
}

function exportBookings() {
    const form = document.getElementById('exportForm');
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    
    // Get selected statuses
    const selectedStatuses = Array.from(document.querySelectorAll('.status-checkbox:checked'))
        .map(cb => cb.value);
    
    // Get selected services
    const selectedServices = Array.from(document.querySelectorAll('.service-checkbox:checked'))
        .map(cb => cb.value);
    
    // Build query string
    const params = new URLSearchParams();
    if (dateFrom) params.append('date_from', dateFrom);
    if (dateTo) params.append('date_to', dateTo);
    
    // Add multiple statuses
    selectedStatuses.forEach(status => {
        params.append('status[]', status);
    });
    
    // Add multiple services
    selectedServices.forEach(service => {
        params.append('service[]', service);
    });
    
    // Redirect to export URL with filters
    const exportUrl = '{{ route(isset($isStaff) && $isStaff ? "staff.bookings.export" : "admin.bookings.export") }}?' + params.toString();
    window.location.href = exportUrl;
    
    closeExportModal();
}
</script>

<!-- Export Modal -->
<div id="exportModal" class="hidden fixed inset-0 bg-black bg-opacity-60 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto w-full max-w-3xl">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <i class="fas fa-file-excel text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Export to Excel</h3>
                            <p class="text-white/80 text-sm">Configure filters and export bookings data</p>
                        </div>
                    </div>
                    <button onclick="closeExportModal()" class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-6 space-y-5">
                <!-- Date Range Section -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center space-x-2 mb-3">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                        <h4 class="font-semibold text-gray-800">Date Range</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">From Date</label>
                            <input type="date" id="date_from" name="date_from" 
                                   class="w-full px-3 py-2 border-2 border-blue-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">To Date</label>
                            <input type="date" id="date_to" name="date_to" 
                                   class="w-full px-3 py-2 border-2 border-blue-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>
                
                <!-- Status Filter Section -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center space-x-2 mb-3">
                        <i class="fas fa-tasks text-green-600"></i>
                        <h4 class="font-semibold text-gray-800">Status Filter</h4>
                    </div>
                    <div class="max-h-40 overflow-y-auto border-2 border-green-200 rounded-lg p-3 bg-white">
                    <label class="flex items-center space-x-2 mb-2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                        <input type="checkbox" name="status[]" value="pending" class="status-checkbox rounded text-[#0d5c2f] focus:ring-[#0d5c2f]">
                        <span class="text-sm">Pending</span>
                    </label>
                    <label class="flex items-center space-x-2 mb-2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                        <input type="checkbox" name="status[]" value="acknowledged" class="status-checkbox rounded text-[#0d5c2f] focus:ring-[#0d5c2f]">
                        <span class="text-sm">Acknowledged</span>
                    </label>
                    <label class="flex items-center space-x-2 mb-2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                        <input type="checkbox" name="status[]" value="payment_hold" class="status-checkbox rounded text-[#0d5c2f] focus:ring-[#0d5c2f]">
                        <span class="text-sm">Payment Hold</span>
                    </label>
                    <label class="flex items-center space-x-2 mb-2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                        <input type="checkbox" name="status[]" value="approved" class="status-checkbox rounded text-[#0d5c2f] focus:ring-[#0d5c2f]">
                        <span class="text-sm">Approved</span>
                    </label>
                    <label class="flex items-center space-x-2 mb-2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                        <input type="checkbox" name="status[]" value="completed" class="status-checkbox rounded text-[#0d5c2f] focus:ring-[#0d5c2f]">
                        <span class="text-sm">Completed</span>
                    </label>
                    <label class="flex items-center space-x-2 mb-2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                        <input type="checkbox" name="status[]" value="rejected" class="status-checkbox rounded text-[#0d5c2f] focus:ring-[#0d5c2f]">
                        <span class="text-sm">Rejected</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                        <input type="checkbox" name="status[]" value="cancelled" class="status-checkbox rounded text-[#0d5c2f] focus:ring-[#0d5c2f]">
                        <span class="text-sm">Cancelled</span>
                    </label>
                </div>
            </div>
            
                <!-- Service Filter Section -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-200">
                    <div class="flex items-center space-x-2 mb-3">
                        <i class="fas fa-church text-purple-600"></i>
                        <h4 class="font-semibold text-gray-800">Service Filter</h4>
                    </div>
                    <div class="max-h-40 overflow-y-auto border-2 border-purple-200 rounded-lg p-3 bg-white">
                        @foreach(\App\Models\Service::orderBy('name')->get() as $serviceItem)
                            <label class="flex items-center space-x-2 mb-2 cursor-pointer hover:bg-purple-50 p-2 rounded transition-colors">
                                <input type="checkbox" name="service[]" value="{{ $serviceItem->id }}" class="service-checkbox rounded text-purple-600 focus:ring-purple-500">
                                <span class="text-sm">{{ $serviceItem->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-2 border-t border-gray-200">
                    <button type="button" onclick="closeExportModal()" 
                            class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition-all duration-200 flex items-center space-x-2">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </button>
                    <button type="button" onclick="exportBookings()" 
                            class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:shadow-lg text-sm font-medium transition-all duration-200 flex items-center space-x-2 transform hover:scale-105">
                        <i class="fas fa-file-excel"></i>
                        <span>Export to Excel</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PDF Preview Modal -->
<div id="pdfPreviewModal" class="hidden fixed inset-0 bg-black bg-opacity-60 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto w-full max-w-3xl">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <i class="fas fa-file-pdf text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">PDF Preview</h3>
                            <p class="text-white/80 text-sm">Configure filters and preview bookings report</p>
                        </div>
                    </div>
                    <button onclick="closePdfPreviewModal()" class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-6 space-y-5">
                <!-- Date Range Section -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center space-x-2 mb-3">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                        <h4 class="font-semibold text-gray-800">Date Range</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">From Date</label>
                            <input type="date" id="pdf_date_from" 
                                   class="w-full px-3 py-2 border-2 border-blue-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">To Date</label>
                            <input type="date" id="pdf_date_to" 
                                   class="w-full px-3 py-2 border-2 border-blue-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>
                
                <!-- Status Filter Section -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center space-x-2 mb-3">
                        <i class="fas fa-tasks text-green-600"></i>
                        <h4 class="font-semibold text-gray-800">Status Filter</h4>
                    </div>
                    <div class="max-h-40 overflow-y-auto border-2 border-green-200 rounded-lg p-3 bg-white">
                        <label class="flex items-center space-x-1 mb-1 cursor-pointer">
                            <input type="checkbox" value="pending" class="pdf-status-checkbox rounded text-[#0d5c2f]">
                            <span>Pending</span>
                        </label>
                        <label class="flex items-center space-x-1 mb-1 cursor-pointer">
                            <input type="checkbox" value="acknowledged" class="pdf-status-checkbox rounded text-[#0d5c2f]">
                            <span>Acknowledged</span>
                        </label>
                        <label class="flex items-center space-x-1 mb-1 cursor-pointer">
                            <input type="checkbox" value="payment_hold" class="pdf-status-checkbox rounded text-[#0d5c2f]">
                            <span>Payment Hold</span>
                        </label>
                        <label class="flex items-center space-x-1 mb-1 cursor-pointer">
                            <input type="checkbox" value="approved" class="pdf-status-checkbox rounded text-[#0d5c2f]">
                            <span>Approved</span>
                        </label>
                        <label class="flex items-center space-x-1 mb-1 cursor-pointer">
                            <input type="checkbox" value="completed" class="pdf-status-checkbox rounded text-[#0d5c2f]">
                            <span>Completed</span>
                        </label>
                        <label class="flex items-center space-x-1 mb-1 cursor-pointer">
                            <input type="checkbox" value="rejected" class="pdf-status-checkbox rounded text-[#0d5c2f]">
                            <span>Rejected</span>
                        </label>
                        <label class="flex items-center space-x-1 cursor-pointer">
                            <input type="checkbox" value="cancelled" class="pdf-status-checkbox rounded text-[#0d5c2f]">
                            <span>Cancelled</span>
                        </label>
                    </div>
                </div>
                
                <!-- Service Filter Section -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-200">
                    <div class="flex items-center space-x-2 mb-3">
                        <i class="fas fa-church text-purple-600"></i>
                        <h4 class="font-semibold text-gray-800">Service Filter</h4>
                    </div>
                    <div class="max-h-40 overflow-y-auto border-2 border-purple-200 rounded-lg p-3 bg-white">
                        @foreach(\App\Models\Service::orderBy('name')->get() as $serviceItem)
                            <label class="flex items-center space-x-2 mb-2 cursor-pointer hover:bg-purple-50 p-2 rounded transition-colors">
                                <input type="checkbox" value="{{ $serviceItem->id }}" class="pdf-service-checkbox rounded text-purple-600 focus:ring-purple-500">
                                <span class="text-sm">{{ $serviceItem->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-2 border-t border-gray-200">
                    <button type="button" onclick="closePdfPreviewModal()" 
                            class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition-all duration-200 flex items-center space-x-2">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </button>
                    <button type="button" onclick="previewPdf()" 
                            class="px-6 py-2.5 bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] text-white rounded-lg hover:shadow-lg text-sm font-medium transition-all duration-200 flex items-center space-x-2 transform hover:scale-105">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Open Preview</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 