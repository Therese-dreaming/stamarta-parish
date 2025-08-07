@extends('layouts.user')

@section('title', 'My Bookings')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
            <p class="text-gray-600 mt-2">Track the status of your service bookings</p>
        </div>

        @if($bookings->count() > 0)
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6">
                            <!-- Booking Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $booking->service->name }}</h3>
                                    <p class="text-sm text-gray-600">Booking ID: #{{ $booking->id }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $booking->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                    @if($booking->payment && $booking->payment->payment_status)
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $booking->payment->payment_status_badge }}">
                                            {{ ucfirst($booking->payment->payment_status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Booking Details -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Date & Time</span>
                                    <p class="text-gray-900">{{ $booking->formatted_date }} at {{ $booking->formatted_time }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Contact</span>
                                    <p class="text-gray-900">{{ $booking->contact_phone }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Total Fee</span>
                                    <p class="text-gray-900">{{ $booking->formatted_total_fee ?? 'Contact office' }}</p>
                                </div>
                            </div>

                            <!-- Status-specific Actions -->
                            @if($booking->status === 'pending')
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-clock text-yellow-600 mt-0.5 mr-2"></i>
                                        <div>
                                            <h4 class="text-sm font-medium text-yellow-800">Awaiting Acknowledgment</h4>
                                            <p class="text-sm text-yellow-700 mt-1">Your booking is being reviewed by the parish office. You will be notified once it's acknowledged.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($booking->status === 'acknowledged')
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-600 mt-0.5 mr-2"></i>
                                        <div>
                                            <h4 class="text-sm font-medium text-blue-800">Booking Acknowledged</h4>
                                            <p class="text-sm text-blue-700 mt-1">Your booking has been acknowledged. Please submit your payment proof to proceed.</p>
                                                                        <a href="{{ route('booking.payment', $booking) }}" 
                               class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm inline-block">
                                Pay Now
                            </a>
                                        </div>
                                    </div>
                                </div>

                            @elseif($booking->status === 'payment_hold')
                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-clock text-orange-600 mt-0.5 mr-2"></i>
                                        <div>
                                            <h4 class="text-sm font-medium text-orange-800">Payment Under Review</h4>
                                            <p class="text-sm text-orange-700 mt-1">Your payment proof has been submitted and is being reviewed by the parish office.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($booking->status === 'approved')
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-check-circle text-green-600 mt-0.5 mr-2"></i>
                                        <div>
                                            <h4 class="text-sm font-medium text-green-800">Booking Approved</h4>
                                            <p class="text-sm text-green-700 mt-1">Your booking has been approved! Please arrive 30 minutes before your scheduled time.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($booking->status === 'rejected')
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-times-circle text-red-600 mt-0.5 mr-2"></i>
                                        <div>
                                            <h4 class="text-sm font-medium text-red-800">Booking Rejected</h4>
                                            <p class="text-sm text-red-700 mt-1">{{ $booking->notes ?? 'Your booking has been rejected. Please contact the parish office for more information.' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($booking->status === 'completed')
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-check-circle text-green-600 mt-0.5 mr-2"></i>
                                        <div>
                                            <h4 class="text-sm font-medium text-green-800">Service Completed</h4>
                                            <p class="text-sm text-green-700 mt-1">Your service has been completed successfully.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Additional Notes -->
                            @if($booking->additional_notes)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Additional Notes</h4>
                                    <p class="text-sm text-gray-600">{{ $booking->additional_notes }}</p>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-500">Created: {{ $booking->created_at->format('M d, Y g:i A') }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($booking->status === 'acknowledged')
                                        <a href="{{ route('booking.payment', $booking) }}" 
                                           class="px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors text-sm">
                                            Pay Now
                                        </a>
                                    @endif
                                    @if(in_array($booking->status, ['pending', 'acknowledged', 'payment_hold']))
                                        <button onclick="cancelBooking({{ $booking->id }})" 
                                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                            Cancel Booking
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Bookings Found</h3>
                <p class="text-gray-600 mb-6">You haven't made any bookings yet.</p>
                <a href="{{ route('services.index') }}" 
                   class="px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                    Browse Services
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function cancelBooking(bookingId) {
    if (confirm('Are you sure you want to cancel this booking?')) {
        // Add cancel booking functionality
        window.location.href = `/booking/cancel/${bookingId}`;
    }
}
</script>
@endsection 