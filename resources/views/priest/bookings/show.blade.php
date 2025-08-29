@extends('layouts.priest')

@section('title', 'Booking Details #' . $booking->id)

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Hero Header with Pattern Background -->
    <div class="relative bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/80 rounded-2xl shadow-lg overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="relative px-6 py-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-11 h-11 rounded-lg bg-white/20 mr-3">
                            <i class="fas fa-calendar-check text-white text-base"></i>
                        </span>
                        <h1 class="text-2xl font-bold text-white">Booking #{{ $booking->id }}</h1>
                    </div>
                    <p class="text-white/80 mt-2 ml-14 text-sm">{{ $booking->service->name }} — {{ $booking->user->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($booking->status === 'acknowledged') bg-blue-100 text-blue-800
                        @elseif($booking->status === 'payment_hold') bg-orange-100 text-orange-800
                        @elseif($booking->status === 'approved') bg-green-100 text-green-800
                        @elseif($booking->status === 'completed') bg-purple-100 text-purple-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                    <a href="{{ route('priest.bookings.index') }}" 
                       class="w-11 h-11 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Back to Bookings">
                        <i class="fas fa-arrow-left text-base"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ tab: 'info' }" class="space-y-4">
        <!-- Tabs Nav -->
        <div class="bg-white border border-gray-200 rounded-xl p-2 sticky top-0 z-10 shadow-sm">
            <div class="flex flex-wrap gap-2">
                <button @click="tab='info'" :class="tab==='info' ? 'bg-[#0d5c2f] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-info-circle mr-1.5"></i>Booking Info
                </button>
                <button @click="tab='docs'" :class="tab==='docs' ? 'bg-[#0d5c2f] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-file-alt mr-1.5"></i>Documents
                </button>
                <button @click="tab='payment'" :class="tab==='payment' ? 'bg-[#0d5c2f] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-money-bill-wave mr-1.5"></i>Payment
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Booking Information Card -->
                <div x-show="tab==='info'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-[#0d5c2f]"></i>
                            Booking Information
                        </h2>
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">Created: {{ $booking->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-concierge-bell text-[#0d5c2f] mr-2"></i>
                                    Service Details
                                </h3>
                                <div class="space-y-3">
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Service:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->service->name }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Date & Time:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->formatted_date }} at {{ $booking->formatted_time }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Duration:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->service->formatted_duration }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Fees:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->service->formatted_fees }}</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-user text-[#0d5c2f] mr-2"></i>
                                    Contact Information
                                </h3>
                                <div class="space-y-3">
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Name:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->user->name }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Phone:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->contact_phone ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Address:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->contact_address ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Email:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->user->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($booking->additional_notes)
                            <div class="mt-5 pt-4 border-t border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-sticky-note text-[#0d5c2f] mr-2"></i>
                                    Additional Notes
                                </h3>
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                    <p class="text-gray-700 text-sm">{{ $booking->additional_notes }}</p>
                                </div>
                            </div>
                        @endif

                        @if($booking->custom_data && count($booking->custom_data) > 0)
                            <div class="mt-5 pt-4 border-t border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-clipboard-list text-[#0d5c2f] mr-2"></i>
                                    Service-Specific Information
                                </h3>
                                <div class="space-y-4">
                                    @php
                                        $serviceType = $booking->service->service_type ?? 'general';
                                    @endphp
                                    @foreach($booking->custom_data as $fieldKey => $fieldValue)
                                        @if(is_string($fieldValue) || is_numeric($fieldValue))
                                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                                                <span class="text-xs font-medium text-gray-500">{{ ucwords(str_replace('_', ' ', $fieldKey)) }}:</span>
                                                <p class="text-sm font-medium text-gray-900 mt-1">{{ $fieldValue }}</p>
                                            </div>
                                        @elseif(is_array($fieldValue))
                                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                                                <span class="text-xs font-medium text-gray-500">{{ ucwords(str_replace('_', ' ', $fieldKey)) }}:</span>
                                                <div class="mt-2 space-y-1">
                                                    @foreach($fieldValue as $index => $item)
                                                        <p class="text-sm font-medium text-gray-900">{{ $index + 1 }}. {{ $item }}</p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Documents -->
                <div x-show="tab==='docs'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-file-alt mr-2 text-[#0d5c2f]"></i>
                            Submitted Documents
                        </h2>
                        @if($booking->requirements_submitted && count($booking->requirements_submitted) > 0)
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                {{ count($booking->requirements_submitted) - (isset($booking->requirements_submitted['conditional_answers']) ? 1 : 0) }} documents
                            </span>
                        @endif
                    </div>
                    <div class="p-4">
                        @if($booking->requirements_submitted && count($booking->requirements_submitted) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($booking->requirements_submitted as $field => $path)
                                    @if($field !== 'conditional_answers')
                                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-[#0d5c2f]/30 transition-all duration-300">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <span class="w-10 h-10 rounded-lg bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                                        <i class="fas fa-file-pdf text-[#0d5c2f] text-sm"></i>
                                                    </span>
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 capitalize">
                                                            {{ str_replace('_', ' ', $field) }}
                                                        </h4>
                                                        <p class="text-xs text-gray-500 mt-0.5">Document uploaded</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ Storage::url($path) }}" target="_blank" title="View" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                                        <i class="fas fa-eye text-xs"></i>
                                                    </a>
                                                    <a href="{{ route('priest.bookings.download-document', [$booking, $field]) }}" 
                                                    title="Download" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-[#0d5c2f] text-white hover:bg-[#0d5c2f]/90 transition-colors">
                                                        <i class="fas fa-download text-xs"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="py-10">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-base font-medium text-gray-900 mb-2">No Documents Submitted</h3>
                                    <p class="text-sm text-gray-500 max-w-md">
                                        No documents have been submitted for this booking yet.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Information -->
                <div x-show="tab==='payment'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-money-bill-wave mr-2 text-[#0d5c2f]"></i>
                            Payment Information
                        </h2>
                        @if($booking->payment && $booking->payment->payment_status && is_string($booking->payment->payment_status))
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($booking->payment->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->payment->payment_status === 'verified') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($booking->payment->payment_status) }}
                            </span>
                        @endif
                    </div>
                    <div class="p-4">
                        @if($booking->payment)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                        <i class="fas fa-receipt text-[#0d5c2f] mr-2"></i>
                                        Payment Details
                                    </h3>
                                    <div class="space-y-3">
                                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 group hover:border-[#0d5c2f]/30 transition-all">
                                            <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Total Fee:</span>
                                            <p class="text-gray-900 text-sm font-semibold">{{ $booking->payment->formatted_total_fee }}</p>
                                        </div>
                                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 group hover:border-[#0d5c2f]/30 transition-all">
                                            <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Payment Method:</span>
                                            <p class="text-gray-900 text-sm">{{ $booking->payment->payment_method_label }}</p>
                                        </div>
                                        @if($booking->payment->payment_reference)
                                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 group hover:border-[#0d5c2f]/30 transition-all">
                                                <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Reference:</span>
                                                <p class="text-gray-900 text-sm">{{ $booking->payment->payment_reference }}</p>
                                            </div>
                                        @endif
                                        @if($booking->payment->payment_notes)
                                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 group hover:border-[#0d5c2f]/30 transition-all">
                                                <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Notes:</span>
                                                <p class="text-gray-900 text-sm">{{ $booking->payment->payment_notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                        <i class="fas fa-file-invoice text-[#0d5c2f] mr-2"></i>
                                        Payment Proof
                                    </h3>
                                    @if($booking->payment->payment_proof)
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col items-center justify-center">
                                            <div class="w-14 h-14 bg-[#0d5c2f]/10 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-file-image text-[#0d5c2f] text-xl"></i>
                                            </div>
                                            <p class="text-gray-700 mb-3 text-center text-sm">Payment proof has been uploaded</p>
                                            <a href="{{ route('priest.bookings.download-payment-proof', $booking) }}" 
                                            class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors text-sm">
                                                <i class="fas fa-download mr-2"></i>Download Proof
                                            </a>
                                        </div>
                                        <div class="mt-3 space-y-2">
                                            @if($booking->payment->payment_submitted_at)
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-gray-500">Submitted:</span>
                                                    <span class="text-gray-700">{{ $booking->payment->payment_submitted_at->format('M d, Y g:i A') }}</span>
                                                </div>
                                            @endif
                                            @if($booking->payment->payment_verified_at)
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-gray-500">Verified:</span>
                                                    <span class="text-gray-700">{{ $booking->payment->payment_verified_at->format('M d, Y g:i A') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col items-center justify-center">
                                            <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-file-upload text-gray-400 text-xl"></i>
                                            </div>
                                            <p class="text-gray-500 text-center text-sm">No payment proof has been uploaded yet</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Empty Payment State -->
                            <div class="py-10">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-file-invoice-dollar text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-base font-medium text-gray-900 mb-2">No Payment Information</h3>
                                    <p class="text-sm text-gray-500 max-w-md">
                                        This booking doesn't have any payment details yet. Payment information will appear here once the booking is acknowledged.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Booking Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-chart-simple mr-2 text-[#0d5c2f]"></i>
                            Booking Summary
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4 mb-5">
                            <div class="bg-[#0d5c2f]/5 rounded-xl p-4 text-center">
                                <span class="text-2xl font-bold text-[#0d5c2f]">{{ $booking->id }}</span>
                                <p class="text-xs text-gray-600 mt-1">Booking ID</p>
                            </div>
                            <div class="bg-[#0d5c2f]/5 rounded-xl p-4 text-center">
                                <span class="text-2xl font-bold text-[#0d5c2f]">
                                    @if($booking->payment)
                                        {{ str_replace('₱', '', $booking->payment->formatted_total_fee) }}
                                    @else
                                        0
                                    @endif
                                </span>
                                <p class="text-xs text-gray-600 mt-1">Total Fee (₱)</p>
                            </div>
                        </div>
                         
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-plus text-[#0d5c2f] mr-2"></i>
                                    <span class="text-sm text-gray-700">Created</span>
                                </div>
                                <span class="text-sm font-medium">{{ $booking->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-day text-[#0d5c2f] mr-2"></i>
                                    <span class="text-sm text-gray-700">Service Date</span>
                                </div>
                                <span class="text-sm font-medium">{{ $booking->formatted_date }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-[#0d5c2f] mr-2"></i>
                                    <span class="text-sm text-gray-700">Service Time</span>
                                </div>
                                <span class="text-sm font-medium">{{ $booking->formatted_time }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <div class="flex items-center">
                                    <i class="fas fa-user text-[#0d5c2f] mr-2"></i>
                                    <span class="text-sm text-gray-700">Booked By</span>
                                </div>
                                <span class="text-sm font-medium">{{ $booking->user->name }}</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('priest.bookings.print', $booking) }}" class="w-full px-4 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg transition-colors flex items-center justify-center">
                                <i class="fas fa-file-pdf mr-2"></i>
                                <span>Print Booking Details (PDF)</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Booking Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-history mr-2 text-[#0d5c2f]"></i>
                            Booking Timeline
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            <div class="timeline-item flex">
                                <div class="timeline-left">
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-plus text-green-600 text-xs"></i>
                                    </div>
                                    <div class="timeline-line"></div>
                                </div>
                                <div class="ml-3 pb-6">
                                    <p class="text-xs font-medium text-gray-900">Booking Created</p>
                                    <p class="text-[10px] text-gray-500">{{ $booking->created_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                            @foreach($booking->actions as $index => $action)
                                <div class="timeline-item flex">
                                    <div class="timeline-left">
                                        <div class="w-6 h-6 bg-{{ $action->action_color }}-100 rounded-full flex items-center justify-center">
                                            <i class="{{ $action->action_icon }} text-{{ $action->action_color }}-600 text-xs"></i>
                                        </div>
                                        @if(!$loop->last || ($booking->payment && $booking->payment->payment_submitted_at))
                                            <div class="timeline-line"></div>
                                        @endif
                                    </div>
                                    <div class="ml-3 pb-6">
                                        <p class="text-xs font-medium text-gray-900">{{ $action->action_type_label }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $action->created_at->format('M d, Y g:i A') }}</p>
                                        @if($action->notes)
                                            <p class="text-[10px] text-gray-600 mt-1 bg-gray-50 p-2 rounded border border-gray-100">{{ $action->notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if($booking->payment && $booking->payment->payment_submitted_at)
                                <div class="timeline-item flex">
                                    <div class="timeline-left">
                                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-upload text-blue-600 text-xs"></i>
                                        </div>
                                        @if($booking->payment && $booking->payment->payment_verified_at)
                                            <div class="timeline-line"></div>
                                        @endif
                                    </div>
                                    <div class="ml-3 pb-6">
                                        <p class="text-xs font-medium text-gray-900">Payment Proof Submitted</p>
                                        <p class="text-[10px] text-gray-500">{{ $booking->payment->payment_submitted_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                            @if($booking->payment && $booking->payment->payment_verified_at)
                                <div class="timeline-item flex">
                                    <div class="timeline-left">
                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check-double text-green-600 text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs font-medium text-gray-900">Payment Verified</p>
                                        <p class="text-[10px] text-gray-500">{{ $booking->payment->payment_verified_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline-left { display: flex; flex-direction: column; align-items: center; }
    .timeline-line { width: 2px; height: 100%; background-color: #e5e7eb; margin-top: 8px; }
    .bg-pattern { background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
</style>
@endsection 