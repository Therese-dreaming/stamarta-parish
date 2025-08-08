@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Admin - Booking Details')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Booking #{{ $booking->id }}</h1>
                    <p class="text-white/80 mt-1">Detailed view of the booking</p>
                </div>
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.index') : route('admin.bookings.index') }}" 
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
                                @if($booking->payment && $booking->payment->payment_status && is_string($booking->payment->payment_status))
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $booking->payment->payment_status_badge }}">
                                        {{ ucfirst($booking->payment->payment_status) }}
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
                                <button onclick="openAcknowledgeModal()" 
                                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    <i class="fas fa-check mr-2"></i>Acknowledge Booking
                                </button>
                                <button onclick="openRejectModal()" 
                                        class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                    <i class="fas fa-times mr-2"></i>Reject Booking
                                </button>
                            </div>
                        @elseif($booking->status === 'acknowledged')
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <div class="flex items-start">
                                    <i class="fas fa-clock text-blue-600 mt-0.5 mr-2"></i>
                                    <div>
                                        <h4 class="text-sm font-medium text-blue-800">Awaiting Payment</h4>
                                        <p class="text-sm text-blue-700 mt-1">The booking has been acknowledged. Waiting for user to submit payment proof.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($booking->status === 'payment_hold')
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                                <div class="flex items-start">
                                    <i class="fas fa-credit-card text-orange-600 mt-0.5 mr-2"></i>
                                    <div>
                                        <h4 class="text-sm font-medium text-orange-800">Payment Verification Required</h4>
                                        <p class="text-sm text-orange-700 mt-1">Payment proof has been submitted. Please verify and approve or reject the booking.</p>
                                    </div>
                                </div>
                            </div>
                            <button onclick="openPaymentVerificationModal()" 
                                    class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                <i class="fas fa-check mr-2"></i>Verify Payment
                            </button>

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
                            <form action="{{ isset($isStaff) && $isStaff ? route('staff.bookings.complete', $booking) : route('admin.bookings.complete', $booking) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
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
                                            <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.download-document', [$booking, $field]) : route('admin.bookings.download-document', [$booking, $field]) }}" 
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
            @if($booking->payment)
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
                                        @if($booking->payment->payment_status && is_string($booking->payment->payment_status))
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $booking->payment->payment_status_badge }}">
                                                {{ ucfirst($booking->payment->payment_status) }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Unknown
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Total Fee:</span>
                                        <p class="text-gray-900 font-semibold">{{ $booking->payment->formatted_total_fee }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Payment Method:</span>
                                        <p class="text-gray-900">{{ $booking->payment->payment_method_label }}</p>
                                    </div>
                                    @if($booking->payment->payment_reference)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Reference:</span>
                                            <p class="text-gray-900">{{ $booking->payment->payment_reference }}</p>
                                        </div>
                                    @endif
                                    @if($booking->payment->payment_notes)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Notes:</span>
                                            <p class="text-gray-900">{{ $booking->payment->payment_notes }}</p>
                                        </div>
                                    @endif
                                    @if($booking->payment->payment_submitted_at)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Submitted:</span>
                                            <p class="text-gray-900">{{ $booking->payment->payment_submitted_at->format('M d, Y g:i A') }}</p>
                                        </div>
                                    @endif
                                    @if($booking->payment->payment_verified_at)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Verified:</span>
                                            <p class="text-gray-900">{{ $booking->payment->payment_verified_at->format('M d, Y g:i A') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Proof</h3>
                                @if($booking->payment->payment_proof)
                                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.download-payment-proof', $booking) : route('admin.bookings.download-payment-proof', $booking) }}" 
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

                        @foreach($booking->actions as $action)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-{{ $action->action_color }}-100 rounded-full flex items-center justify-center">
                                        <i class="{{ $action->action_icon }} text-{{ $action->action_color }}-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $action->action_type_label }}</p>
                                    <p class="text-xs text-gray-500">{{ $action->created_at->format('M d, Y g:i A') }}</p>
                                    @if($action->notes)
                                        <p class="text-xs text-gray-600 mt-1">{{ $action->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if($booking->payment && $booking->payment->payment_submitted_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-upload text-blue-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Payment Proof Submitted</p>
                                    <p class="text-xs text-gray-500">{{ $booking->payment->payment_submitted_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
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
                
                                        <form action="{{ isset($isStaff) && $isStaff ? route('staff.bookings.acknowledge', $booking) : route('admin.bookings.acknowledge', $booking) }}" method="POST">
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
                                       placeholder="0.00"
                                       value="{{ $booking->service ? ($booking->service->getFeeForDate($booking->service_date)['amount'] ?? '') : '' }}">
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
                                        <form action="{{ isset($isStaff) && $isStaff ? route('staff.bookings.reject', $booking) : route('admin.bookings.reject', $booking) }}" method="POST">
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

<!-- Payment Verification Modal -->
<div id="paymentVerificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-lg w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Verification</h3>
                                        <form action="{{ isset($isStaff) && $isStaff ? route('staff.bookings.verify-payment', $booking) : route('admin.bookings.verify-payment', $booking) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Verification Status</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="verification_status" value="approved" class="mr-2" onchange="togglePriestSelection()">
                                    <span class="text-sm text-gray-700">Approve</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="verification_status" value="rejected" class="mr-2" onchange="togglePriestSelection()">
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
                            <label for="verification_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea id="verification_notes" 
                                      name="notes" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                      placeholder="Add any notes about the verification"></textarea>
                        </div>
                        
                        <div class="flex items-center justify-end space-x-3">
                            <button type="button" 
                                    onclick="closeModal('paymentVerificationModal')"
                                    class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                Verify Payment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openAcknowledgeModal() {
    document.getElementById('acknowledgeModal').classList.remove('hidden');
}

function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function openPaymentVerificationModal() {
    document.getElementById('paymentVerificationModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function togglePriestSelection() {
    const priestSelection = document.getElementById('priest-selection');
    const approvedRadio = document.querySelector('input[name="verification_status"][value="approved"]');
    
    if (approvedRadio.checked) {
        priestSelection.classList.remove('hidden');
    } else {
        priestSelection.classList.add('hidden');
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
</script>
@endsection 