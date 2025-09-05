@extends('layouts.admin')

@section('title', 'Add Manual Cash Inflow')

@section('content')
@include('components.toast')

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Add Manual Cash Inflow</h1>
                        <p class="text-gray-600 mt-1">Record cash received from diocese, donations, or other sources</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.manual-cash-inflows.index') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to List
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
                            Cash Inflow Details
                        </h2>
                        <p class="text-white/80 text-sm mt-1">Fill in the details below to record the cash inflow</p>
                    </div>

                    <!-- Form Content -->
                    <form action="{{ route('admin.manual-cash-inflows.store') }}" method="POST" class="p-8">
                        @csrf

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
                                           value="{{ old('amount') }}"
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
                                        <option value="diocese" {{ old('source_type') === 'diocese' ? 'selected' : '' }}>🏛️ Diocese</option>
                                        <option value="donation" {{ old('source_type') === 'donation' ? 'selected' : '' }}>💝 Donation</option>
                                        <option value="other" {{ old('source_type') === 'other' ? 'selected' : '' }}>📋 Other</option>
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
                                   value="{{ old('description') }}"
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
                                   value="{{ old('source_details') }}"
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
                        <div id="other_source_specification" class="mb-8 hidden">
                            <label for="other_source_specify" class="block text-sm font-semibold text-gray-700 mb-3">
                                Specify Other Source <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="other_source_specify" 
                                   id="other_source_specify" 
                                   value="{{ old('other_source_specify') }}"
                                   class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 @error('other_source_specify') border-red-300 focus:ring-red-200 focus:border-red-500 @enderror"
                                   placeholder="Please specify the source type (e.g., Fundraising event, Grant, Sponsorship, etc.)">
                            @error('other_source_specify')
                                <p class="text-sm text-red-600 flex items-center mt-2">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Ministry and Reference Number Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Ministry Field -->
                            <div class="space-y-2">
                                <label for="ministry_id" class="block text-sm font-semibold text-gray-700">
                                    Ministry (Optional)
                                </label>
                                <div class="relative">
                                    <select name="ministry_id" 
                                            id="ministry_id" 
                                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 appearance-none bg-white @error('ministry_id') border-red-300 focus:ring-red-200 focus:border-red-500 @enderror">
                                        <option value="">🏛️ General Parish Fund</option>
                                        @foreach($ministries as $ministry)
                                            <option value="{{ $ministry->id }}" {{ old('ministry_id') == $ministry->id ? 'selected' : '' }}>
                                                🏛️ {{ $ministry->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                @error('ministry_id')
                                    <p class="text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Reference Number Field -->
                            <div class="space-y-2">
                                <label for="reference_no" class="block text-sm font-semibold text-gray-700">
                                    Reference Number (Optional)
                                </label>
                                <input type="text" 
                                       name="reference_no" 
                                       id="reference_no" 
                                       value="{{ old('reference_no') }}"
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
                                      placeholder="Any additional information or context about this cash inflow (e.g., purpose, conditions, special instructions)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-sm text-red-600 flex items-center mt-2">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-8 border-t border-gray-200">
                            <a href="{{ route('admin.manual-cash-inflows.index') }}" 
                               class="inline-flex justify-center items-center px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all duration-200 border border-gray-300 hover:border-gray-400">
                                <i class="fas fa-times mr-2"></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex justify-center items-center px-8 py-3.5 bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] hover:from-[#0a4a26] hover:to-[#0d5c2f] text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-save mr-2"></i>
                                Create Cash Inflow
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
                            <h3 class="text-lg font-semibold text-blue-900 mb-3">Important Information</h3>
                            <div class="space-y-3 text-sm text-blue-800">
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-clock text-blue-600 mt-1 text-xs"></i>
                                    <span>All cash inflows require approval before funds are added to the budget</span>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-check-circle text-blue-600 mt-1 text-xs"></i>
                                    <span>Once approved, funds are automatically added to the selected ministry</span>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-building text-blue-600 mt-1 text-xs"></i>
                                    <span>General parish fund if no ministry is selected</span>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-edit text-blue-600 mt-1 text-xs"></i>
                                    <span>Pending inflows can be edited or deleted</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-[#0d5c2f]"></i>
                        Quick Stats
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-green-800">Approved</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">
                                {{ \App\Models\ManualCashInflow::where('status', 'approved')->count() }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-yellow-800">Pending</span>
                            </div>
                            <span class="text-lg font-bold text-yellow-600">
                                {{ \App\Models\ManualCashInflow::where('status', 'pending')->count() }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-times text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-red-800">Rejected</span>
                            </div>
                            <span class="text-lg font-bold text-red-600">
                                {{ \App\Models\ManualCashInflow::where('status', 'rejected')->count() }}
                            </span>
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
                            <p class="text-sm text-purple-800 mb-4">If you have questions about recording cash inflows, contact your system administrator.</p>
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
            otherSourceSpecifyInput.value = ''; // Clear the value when hiding
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