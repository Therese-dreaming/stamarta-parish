@extends('layouts.admin')

@section('title', 'Admin - Booking Details')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Booking #{{ $booking->id }}</h1>
                    <p class="text-gray-600 mt-2">Detailed view of the booking</p>
                </div>
                <a href="{{ route('admin.bookings.index') }}" 
                   class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Bookings
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Booking Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Booking Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Details</h3>
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Service:</span>
                                        <p class="text-gray-900">{{ $booking->service->name }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Date & Time:</span>
                                        <p class="text-gray-900">{{ $booking->formatted_date }} at {{ $booking->formatted_time }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Duration:</span>
                                        <p class="text-gray-900">{{ $booking->service->formatted_duration }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Fees:</span>
                                        <p class="text-gray-900">{{ $booking->service->formatted_fees }}</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Name:</span>
                                        <p class="text-gray-900">{{ $booking->user->name }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Phone:</span>
                                        <p class="text-gray-900">{{ $booking->contact_phone }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Address:</span>
                                        <p class="text-gray-900">{{ $booking->contact_address }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Email:</span>
                                        <p class="text-gray-900">{{ $booking->user->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($booking->additional_notes)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Additional Notes</h3>
                                <p class="text-gray-700">{{ $booking->additional_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status and Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Status & Actions</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Current Status:</span>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $booking->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                    @if($booking->payment_status)
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $booking->payment_status_badge }}">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                Created: {{ $booking->created_at->format('M d, Y g:i A') }}
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            @if($booking->status === 'pending')
                                <div class="flex items-center space-x-4">
                                    <form action="{{ route('admin.bookings.acknowledge', $booking) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-check mr-2"></i>Acknowledge Booking
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.bookings.payment-hold', $booking) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                            <i class="fas fa-credit-card mr-2"></i>Put on Payment Hold
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                                                onclick="return confirm('Are you sure you want to reject this booking?')">
                                            <i class="fas fa-times mr-2"></i>Reject Booking
                                        </button>
                                    </form>
                                </div>
                            @elseif($booking->status === 'acknowledged')
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-credit-card text-blue-600 mt-0.5 mr-2"></i>
                                        <div>
                                            <h4 class="text-sm font-medium text-blue-800">Payment Verification Required</h4>
                                            <p class="text-sm text-blue-700 mt-1">Please verify the payment proof and approve or reject the booking.</p>
                                        </div>
                                    </div>
                                </div>
                                <div id="payment-verification">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Verification</h3>
                                    <form action="{{ route('admin.bookings.verify-payment', $booking) }}" method="POST">
                                        @csrf
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Verification Status</label>
                                                <div class="flex space-x-4">
                                                    <label class="flex items-center">
                                                        <input type="radio" name="verification_status" value="approved" class="mr-2">
                                                        <span class="text-sm text-gray-700">Approve</span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="verification_status" value="rejected" class="mr-2">
                                                        <span class="text-sm text-gray-700">Reject</span>
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <div id="priest-selection" class="hidden">
                                                <label for="priest_id" class="block text-sm font-medium text-gray-700 mb-2">Assign Priest</label>
                                                <select name="priest_id" id="priest_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                                                    <option value="">Select a priest</option>
                                                    @foreach($priests as $priest)
                                                        <option value="{{ $priest->id }}">{{ $priest->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div>
                                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                                                <textarea name="notes" id="notes" rows="3" 
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                          placeholder="Add any notes about the verification"></textarea>
                                            </div>
                                            
                                            <div class="flex space-x-3">
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                                    <i class="fas fa-check mr-2"></i>Verify Payment
                                                </button>
                                                <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                                                            onclick="return confirm('Are you sure you want to reject this booking?')">
                                                        <i class="fas fa-times mr-2"></i>Reject Booking
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </form>

                            @elseif($booking->status === 'approved')
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-check-circle text-green-600 mt-0.5 mr-2"></i>
                                        <div>
                                            <h4 class="text-sm font-medium text-green-800">Booking Approved</h4>
                                            <p class="text-sm text-green-700 mt-1">The booking has been approved and is ready for service.</p>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-flag-checkered mr-2"></i>Mark as Completed
                                    </button>
                                </form>
                            @elseif($booking->status === 'completed')
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-flag-checkered text-green-600 mt-0.5 mr-2"></i>
                                        <div>
                                            <h4 class="text-sm font-medium text-green-800">Service Completed</h4>
                                            <p class="text-sm text-green-700 mt-1">This booking has been completed successfully.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($booking->status === 'rejected')
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-times-circle text-red-600 mt-0.5 mr-2"></i>
                                        <div>
                                            <h4 class="text-sm font-medium text-red-800">Booking Rejected</h4>
                                            <p class="text-sm text-red-700 mt-1">{{ $booking->notes ?? 'This booking has been rejected.' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                @if($booking->requirements_submitted && count($booking->requirements_submitted) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Submitted Documents</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($booking->requirements_submitted as $field => $path)
                                    @if($field !== 'conditional_answers')
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-900 capitalize">
                                                        {{ str_replace('_', ' ', $field) }}
                                                    </h4>
                                                    <p class="text-xs text-gray-500 mt-1">Document uploaded</p>
                                                </div>
                                                <a href="{{ route('admin.bookings.download-document', [$booking, $field]) }}" 
                                                   class="px-3 py-1 bg-[#0d5c2f] text-white rounded text-sm hover:bg-[#0d5c2f]/90 transition-colors">
                                                    <i class="fas fa-download mr-1"></i>Download
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Payment Information -->
                @if($booking->payment_status && $booking->payment_status !== 'pending')
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Payment Information</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Details</h3>
                                    <div class="space-y-3">
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Status:</span>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $booking->payment_status_badge }}">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </div>
                                        @if($booking->payment_reference)
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Reference:</span>
                                                <p class="text-gray-900">{{ $booking->payment_reference }}</p>
                                            </div>
                                        @endif
                                        @if($booking->payment_notes)
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Notes:</span>
                                                <p class="text-gray-900">{{ $booking->payment_notes }}</p>
                                            </div>
                                        @endif
                                        @if($booking->payment_submitted_at)
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Submitted:</span>
                                                <p class="text-gray-900">{{ $booking->payment_submitted_at->format('M d, Y g:i A') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Proof</h3>
                                    @if($booking->payment_proof)
                                        <a href="{{ route('admin.bookings.download-payment-proof', $booking) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                                            <i class="fas fa-download mr-2"></i>Download Payment Proof
                                        </a>
                                    @else
                                        <p class="text-gray-500 text-sm">No payment proof uploaded</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Notes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Admin Notes</h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.bookings.update-notes', $booking) }}" method="POST">
                            @csrf
                            <textarea name="notes" rows="6" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                      placeholder="Add admin notes here...">{{ $booking->notes }}</textarea>
                            <button type="submit" 
                                    class="mt-3 px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                                Update Notes
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Booking Timeline</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-plus text-green-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Booking Created</p>
                                    <p class="text-xs text-gray-500">{{ $booking->created_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>

                            @if($booking->acknowledged_at)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-blue-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Booking Acknowledged</p>
                                        <p class="text-xs text-gray-500">{{ $booking->acknowledged_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($booking->payment_hold_at)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-credit-card text-orange-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Put on Payment Hold</p>
                                        <p class="text-xs text-gray-500">{{ $booking->payment_hold_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($booking->payment_submitted_at)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-upload text-blue-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Payment Proof Submitted</p>
                                        <p class="text-xs text-gray-500">{{ $booking->payment_submitted_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($booking->verified_at)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check-circle text-green-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">
                                            Payment {{ $booking->payment_status === 'verified' ? 'Verified' : 'Rejected' }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $booking->verified_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($booking->completed_at)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-flag-checkered text-green-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Service Completed</p>
                                        <p class="text-xs text-gray-500">{{ $booking->completed_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($booking->cancelled_at)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-times text-red-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Booking Cancelled</p>
                                        <p class="text-xs text-gray-500">{{ $booking->cancelled_at->format('M d, Y g:i A') }}</p>
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

<script>
// Show/hide priest selection based on verification status
document.querySelectorAll('input[name="verification_status"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const priestSelection = document.getElementById('priest-selection');
        if (this.value === 'approved') {
            priestSelection.classList.remove('hidden');
        } else {
            priestSelection.classList.add('hidden');
        }
    });
});
</script>
@endsection 