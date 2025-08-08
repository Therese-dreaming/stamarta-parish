@extends('layouts.priest')

@section('title', 'Booking Details')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Booking #{{ $booking->id }}</h1>
                    <p class="text-white/80 mt-1">{{ $booking->service->name }} - {{ $booking->user->name }}</p>
                </div>
                <a href="{{ route('priest.bookings.index') }}" 
                   class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Back to Bookings">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-[#0d5c2f]"></i>
                        Booking Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Service</label>
                            <p class="text-lg font-medium text-gray-900">{{ $booking->service->name }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status === 'acknowledged') bg-blue-100 text-blue-800
                                @elseif($booking->status === 'payment_hold') bg-orange-100 text-orange-800
                                @elseif($booking->status === 'approved') bg-green-100 text-green-800
                                @elseif($booking->status === 'completed') bg-purple-100 text-purple-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Service Date</label>
                            <p class="text-lg text-gray-900">{{ $booking->formatted_date }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Service Time</label>
                            <p class="text-lg text-gray-900">{{ $booking->formatted_time }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fee</label>
                            <p class="text-lg font-medium text-gray-900">{{ $booking->service->formatted_fees }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Created</label>
                            <p class="text-lg text-gray-900">{{ $booking->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Client Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>
                        Client Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <p class="text-lg font-medium text-gray-900">{{ $booking->user->name }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <p class="text-lg text-gray-900">{{ $booking->user->email }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <p class="text-lg text-gray-900">{{ $booking->user->phone ?? 'Not provided' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <p class="text-lg text-gray-900">{{ $booking->user->address ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Required Documents -->
            @if($booking->service->requirements && count($booking->service->requirements) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-file-alt mr-2 text-[#0d5c2f]"></i>
                        Required Documents
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($booking->service->requirements as $requirement)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-file mr-3 text-[#0d5c2f]"></i>
                                <span class="font-medium text-gray-900">{{ $requirement }}</span>
                            </div>
                            @if(isset($booking->custom_data[$requirement]))
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-green-600 flex items-center">
                                    <i class="fas fa-check-circle mr-1"></i>Uploaded
                                </span>
                                <a href="{{ route('priest.bookings.download-document', [$booking, $requirement]) }}" 
                                   class="px-3 py-1 bg-[#0d5c2f] text-white rounded text-sm hover:bg-[#0d5c2f]/90 transition-colors">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                            </div>
                            @else
                            <span class="text-sm text-red-600 flex items-center">
                                <i class="fas fa-times-circle mr-1"></i>Not uploaded
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Payment Information -->
            @if($booking->payment)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-credit-card mr-2 text-[#0d5c2f]"></i>
                        Payment Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <p class="text-lg font-medium text-gray-900">{{ ucfirst($booking->payment->payment_method) }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                            <p class="text-lg font-medium text-gray-900">₱{{ number_format($booking->payment->amount, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($booking->payment->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->payment->status === 'verified') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($booking->payment->status) }}
                            </span>
                        </div>
                        @if($booking->payment->payment_proof)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Proof</label>
                            <a href="{{ route('priest.bookings.download-payment-proof', $booking) }}" 
                               class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                                <i class="fas fa-download mr-2"></i>Download Proof
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif


        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Booking Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Booking Timeline</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($booking->actions as $action)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center">
                                    <i class="fas fa-{{ $action->action === 'completed' ? 'check' : ($action->action === 'acknowledged' ? 'eye' : 'edit') }} text-[#0d5c2f] text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ ucfirst($action->action) }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $action->created_at->format('M d, Y g:i A') }}</p>
                                @if($action->notes)
                                <p class="text-xs text-gray-600 mt-1">{{ $action->notes }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Service Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Service Details</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Duration</span>
                        <span class="text-sm font-medium text-gray-900">{{ $booking->service->formatted_duration }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Max Slots</span>
                        <span class="text-sm font-medium text-gray-900">{{ $booking->service->max_slots }}</span>
                    </div>
                    @if($booking->service->description)
                    <div>
                        <span class="text-sm text-gray-600">Description</span>
                        <p class="text-sm text-gray-900 mt-1">{{ $booking->service->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 