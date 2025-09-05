@extends('layouts.ministry')

@section('title', 'Edit Cash Inflow Request')

@section('content')
@include('components.toast')

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-edit text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Cash Inflow Request</h1>
                        <p class="text-gray-600 mt-1">{{ $ministry->name }} - Request #{{ $cashInflow->id }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('ministry.manual-cash-inflows.show', $cashInflow) }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Details
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Form Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] px-8 py-6">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-money-bill-wave mr-3"></i>
                            Edit Cash Inflow Details
                        </h2>
                        <p class="text-white/80 text-sm mt-1">Update the details below to modify your cash inflow request</p>
                    </div>

                    <!-- Form Content -->
                    <form action="{{ route('ministry.manual-cash-inflows.update', $cashInflow) }}" method="POST" class="p-8">
                        @csrf
                        @method('PUT')

                        <!-- Amount and Source Type Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Amount Field -->
                            <div class="space-y-2">
                                <label for="amount" class="block text-sm font-semibold text-gray-700">
                                    Amount <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg font-medium z-10">₱</span>
                                    <input type="number" 
                                           name="amount" 
                                           id="amount" 
                                           step="0.01" 
                                           min="0.01" 
                                           max="999999.99"
                                           value="{{ old('amount', $cashInflow->amount) }}"
                                           class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 text-lg font-medium @error('amount') border-red-300 focus:ring-red-200 focus:border-red-500 @enderror"
                                           placeholder="0.00"
                                           required>
                                </div>
                                @error('amount')
                                    <p class="text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Source Type Field -->
                            <div class="space-y-2">
                                <label for="source_type" class="block text-sm font-semibold text-gray-700">
                                    Source Type <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="source_type" 
                                            id="source_type" 
                                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 appearance-none bg-white @error('source_type') border-red-300 focus:ring-red-200 focus:border-red-500 @enderror"
                                            required>
                                        <option value="">Select Source Type</option>
                                        <option value="diocese" {{ old('source_type', $cashInflow->source_type) === 'diocese' ? 'selected' : '' }}>🏛️ Diocese</option>
                                        <option value="donation" {{ old('source_type', $cashInflow->source_type) === 'donation' ? 'selected' : '' }}>💝 Donation</option>
                                        <option value="fundraising" {{ old('source_type', $cashInflow->source_type) === 'fundraising' ? 'selected' : '' }}>🎪 Fundraising</option>
                                        <option value="event_revenue" {{ old('source_type', $cashInflow->source_type) === 'event_revenue' ? 'selected' : '' }}>🎉 Event Revenue</option>
                                        <option value="membership_fee" {{ old('source_type', $cashInflow->source_type) === 'membership_fee' ? 'selected' : '' }}>👥 Membership Fee</option>
                                        <option value="sponsorship" {{ old('source_type', $cashInflow->source_type) === 'sponsorship' ? 'selected' : '' }}>🤝 Sponsorship</option>
                                        <option value="other" {{ old('source_type', $cashInflow->source_type) === 'other' ? 'selected' : '' }}>📋 Other</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                @error('source_type')
                                    <p class="text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="mb-8">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-3">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="description" 
                                   id="description" 
                                   value="{{ old('description', $cashInflow->description) }}"
                                   class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 @error('description') border-red-300 focus:ring-red-200 focus:border-red-500 @enderror"
                                   placeholder="Brief description of the cash inflow (e.g., Monthly diocese support, Anonymous donation)"
                                   required>
                            @error('description')
                                <p class="text-sm text-red-600 flex items-center mt-2">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Source Details Field -->
                        <div class="mb-8">
                            <label for="source_details" class="block text-sm font-semibold text-gray-700 mb-3">
                                Source Details
                            </label>
                            <input type="text" 
                                   name="source_details" 
                                   id="source_details" 
                                   value="{{ old('source_details', $cashInflow->source_details) }}"
                                   class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 @error('source_details') border-red-300 focus:ring-red-200 focus:border-red-500 @enderror"
                                   placeholder="Additional details about the source (e.g., donor name, diocese contact person, event details)">
                            @error('source_details')
                                <p class="text-sm text-red-600 flex items-center mt-2">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Other Source Specification (Conditional) -->
                        <div id="other_source_specification" class="mb-8 {{ old('source_type', $cashInflow->source_type) === 'other' ? '' : 'hidden' }}">
                            <label for="other_source_specify" class="block text-sm font-semibold text-gray-700 mb-3">
                                Specify Other Source <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="other_source_specify" 
                                   id="other_source_specify" 
                                   value="{{ old('other_source_specify', $cashInflow->other_source_specify) }}"
                                   class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 @error('other_source_specify') border-red-300 focus:ring-red-200 focus:border-red-500 @enderror"
                                   placeholder="Please specify the source type (e.g., Grant, Sponsorship, etc.)">
                            @error('other_source_specify')
                                <p class="text-sm text-red-600 flex items-center mt-2">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Reference Number and Date Received Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Reference Number Field -->
                            <div class="space-y-2">
                                <label for="reference_no" class="block text-sm font-semibold text-gray-700">
                                    Reference Number (Optional)
                                </label>
                                <input type="text" 
                                       name="reference_no" 
                                       id="reference_no" 
                                       value="{{ old('reference_no', $cashInflow->reference_no) }}"
                                       class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 @error('reference_no') border-red-300 focus:ring-red-200 focus:border-red-500 @enderror"
                                       placeholder="Leave blank to auto-generate">
                                @error('reference_no')
                                    <p class="text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Auto-generated if left blank
                                </p>
                            </div>

                            <!-- Date Received Field -->
                            <div class="space-y-2">
                                <label for="date_received" class="block text-sm font-semibold text-gray-700">
                                    Date Received <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="date_received" 
                                       id="date_received" 
                                       value="{{ old('date_received', $cashInflow->date_received->format('Y-m-d')) }}" 
                                       max="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 @error('date_received') border-red-300 focus:ring-red-200 focus:border-red-500 @enderror"
                                       required>
                                @error('date_received')
                                    <p class="text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes Field -->
                        <div class="mb-8">
                            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-3">
                                Additional Notes
                            </label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="4"
                                      class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 resize-none @error('notes') border-red-300 focus:ring-red-200 focus:border-red-500 @enderror"
                                      placeholder="Any additional information or context about this cash inflow (e.g., purpose, conditions, special instructions)">{{ old('notes', $cashInflow->notes) }}</textarea>
                            @error('notes')
                                <p class="text-sm text-red-600 flex items-center mt-2">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-8 border-t border-gray-200">
                            <a href="{{ route('ministry.manual-cash-inflows.show', $cashInflow) }}" 
                               class="inline-flex justify-center items-center px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all duration-200 border border-gray-300 hover:border-gray-400">
                                <i class="fas fa-times mr-2"></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex justify-center items-center px-8 py-3.5 bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] hover:from-[#0a4a26] hover:to-[#0d5c2f] text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-save mr-2"></i>
                                Update Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                
                <!-- Information Card -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-info-circle text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-blue-900 mb-3">Edit Information</h3>
                            <div class="space-y-3 text-sm text-blue-800">
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-edit text-blue-600 mt-1 text-xs"></i>
                                    <span>You can only edit pending requests</span>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-clock text-blue-600 mt-1 text-xs"></i>
                                    <span>Approved requests cannot be modified</span>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-bell text-blue-600 mt-1 text-xs"></i>
                                    <span>Changes will be reviewed by admin</span>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-history text-blue-600 mt-1 text-xs"></i>
                                    <span>Edit history is tracked</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Request Status Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-[#0d5c2f]"></i>
                        Request Status
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-{{ $cashInflow->status === 'pending' ? 'yellow' : ($cashInflow->status === 'approved' ? 'green' : 'red') }}-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-{{ $cashInflow->status === 'pending' ? 'yellow' : ($cashInflow->status === 'approved' ? 'green' : 'red') }}-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-{{ $cashInflow->status === 'pending' ? 'clock' : ($cashInflow->status === 'approved' ? 'check' : 'times') }} text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-{{ $cashInflow->status === 'pending' ? 'yellow' : ($cashInflow->status === 'approved' ? 'green' : 'red') }}-800">Status</span>
                            </div>
                            <span class="text-lg font-bold text-{{ $cashInflow->status === 'pending' ? 'yellow' : ($cashInflow->status === 'approved' ? 'green' : 'red') }}-600 capitalize">{{ $cashInflow->status }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gray-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-800">Created</span>
                            </div>
                            <span class="text-sm font-bold text-gray-600">{{ $cashInflow->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($cashInflow->updated_at != $cashInflow->created_at)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gray-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-edit text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-800">Last Updated</span>
                            </div>
                            <span class="text-sm font-bold text-gray-600">{{ $cashInflow->updated_at->format('M d, Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Ministry Info Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-church mr-2 text-[#0d5c2f]"></i>
                        Ministry Information
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-building text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-green-800">Ministry</span>
                            </div>
                            <span class="text-sm font-bold text-green-600">{{ $ministry->name }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-blue-800">Head</span>
                            </div>
                            <span class="text-sm font-bold text-blue-600">{{ $ministry->head->name ?? 'Not Assigned' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-question-circle text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-purple-900 mb-3">Need Help?</h3>
                            <p class="text-sm text-purple-800 mb-4">If you have questions about editing cash inflow requests, contact your ministry head or admin.</p>
                            <a href="#" class="inline-flex items-center text-sm font-medium text-purple-700 hover:text-purple-800 transition-colors">
                                <i class="fas fa-envelope mr-2"></i>
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for enhanced form styling -->
<style>
    /* Custom scrollbar for select dropdowns */
    select::-webkit-scrollbar {
        width: 8px;
    }
    
    select::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    select::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    
    select::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Enhanced focus states */
    .form-input:focus {
        transform: translateY(-1px);
    }
    
    /* Smooth transitions */
    * {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
</style>

<!-- JavaScript for dynamic form behavior -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sourceTypeSelect = document.getElementById('source_type');
    const otherSourceSpecification = document.getElementById('other_source_specification');
    const otherSourceSpecifyInput = document.getElementById('other_source_specify');
    
    // Function to toggle the "other source specify" field
    function toggleOtherSourceField() {
        const selectedValue = sourceTypeSelect.value;
        
        if (selectedValue === 'other') {
            otherSourceSpecification.classList.remove('hidden');
            otherSourceSpecifyInput.setAttribute('required', 'required');
            // Add smooth animation
            otherSourceSpecification.style.opacity = '0';
            otherSourceSpecification.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                otherSourceSpecification.style.transition = 'all 0.3s ease';
                otherSourceSpecification.style.opacity = '1';
                otherSourceSpecification.style.transform = 'translateY(0)';
            }, 10);
        } else {
            otherSourceSpecification.classList.add('hidden');
            otherSourceSpecifyInput.removeAttribute('required');
            // Don't clear the value when editing existing data
        }
    }
    
    // Initial check on page load
    toggleOtherSourceField();
    
    // Add event listener for changes
    sourceTypeSelect.addEventListener('change', toggleOtherSourceField);
    
    // Enhanced form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const selectedSourceType = sourceTypeSelect.value;
        const otherSourceValue = otherSourceSpecifyInput.value.trim();
        
        if (selectedSourceType === 'other' && !otherSourceValue) {
            e.preventDefault();
            otherSourceSpecifyInput.focus();
            otherSourceSpecifyInput.classList.add('border-red-500', 'focus:ring-red-200', 'focus:border-red-500');
            
            // Show error message
            let errorMsg = otherSourceSpecification.querySelector('.error-message');
            if (!errorMsg) {
                errorMsg = document.createElement('p');
                errorMsg.className = 'text-sm text-red-600 flex items-center mt-2 error-message';
                errorMsg.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i>Please specify the other source type';
                otherSourceSpecification.appendChild(errorMsg);
            }
            
            // Remove error styling after user starts typing
            otherSourceSpecifyInput.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('border-red-500', 'focus:ring-red-200', 'focus:border-red-500');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            }, { once: true });
        }
    });
});
</script>

@endsection