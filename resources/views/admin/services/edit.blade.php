@extends('layouts.admin')

@section('title', 'Edit Service')

@section('content')
@include('components.toast')

@if ($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-red-600 mt-0.5 mr-3"></i>
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-red-800 mb-2">Please fix the following errors:</h3>
                <ul class="text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-lg shadow-md overflow-hidden">
        <div class="px-4 py-4 relative">
            <div class="absolute right-0 top-0 w-16 h-16 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-xl font-bold text-white">Edit Service</h1>
                    <p class="text-white/80 mt-1 text-xs">Update service information and schedules</p>
                </div>
                <a href="{{ route('admin.services.index') }}" 
                   class="group px-3 py-1.5 rounded-md bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow text-xs">
                    <i class="fas fa-arrow-left mr-1.5 text-xs group-hover:-translate-x-1 transition-transform duration-200"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.services.update', $service) }}" method="POST" class="space-y-4" id="serviceForm">
        @csrf
        @method('PUT')
        
        <!-- Basic Information Container -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden animate-slideInUp">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-[#0d5c2f] text-sm"></i>
                    Basic Information
                </h3>
            </div>
            <div class="p-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="group">
                        <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Service Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $service->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        @error('name')
                            <p class="text-red-600 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="duration_minutes" class="block text-xs font-medium text-gray-700 mb-1">Duration (minutes) *</label>
                        <input type="number" id="duration_minutes" name="duration_minutes" 
                               value="{{ old('duration_minutes', $service->duration_minutes) }}" 
                               min="15" max="480" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        @error('duration_minutes')
                            <p class="text-red-600 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="group">
                    <label for="description" class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50 resize-y">{{ old('description', $service->description) }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-xs mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="group">
                    <label for="max_slots" class="block text-xs font-medium text-gray-700 mb-1">Max Slots *</label>
                    <input type="number" id="max_slots" name="max_slots" 
                           value="{{ old('max_slots', $service->max_slots) }}" 
                           min="1" max="100" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                    @error('max_slots')
                        <p class="text-red-600 text-xs mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Booking Restrictions Container -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden animate-slideInUp" style="animation-delay: 100ms">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-[#0d5c2f] text-sm"></i>
                    Booking Restrictions
                </h3>
            </div>
            <div class="p-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="group">
                        <label for="minimum_days" class="block text-xs font-medium text-gray-700 mb-1">Minimum Days in Advance *</label>
                        <input type="number" id="minimum_days" name="booking_restrictions[minimum_days]" 
                               value="{{ old('booking_restrictions.minimum_days', $service->booking_restrictions['minimum_days'] ?? 1) }}" 
                               min="1" max="365" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        <p class="text-xs text-gray-500 mt-1">Days before service date</p>
                    </div>

                    <div class="group">
                        <label for="maximum_days" class="block text-xs font-medium text-gray-700 mb-1">Maximum Days in Advance *</label>
                        <input type="number" id="maximum_days" name="booking_restrictions[maximum_days]" 
                               value="{{ old('booking_restrictions.maximum_days', $service->booking_restrictions['maximum_days'] ?? 365) }}" 
                               min="1" max="365" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        <p class="text-xs text-gray-500 mt-1">Days before service date</p>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2 text-sm"></i>
                        <div>
                            <p class="text-xs text-blue-800 font-medium mb-1">Booking Restriction Examples:</p>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li>• <strong>Baptism:</strong> Min 4 days, Max 90 days</li>
                                <li>• <strong>Wedding:</strong> Min 30 days, Max 365 days</li>
                                <li>• <strong>Blessing:</strong> Min 1 day, Max 60 days</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requirements Container -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden animate-slideInUp" style="animation-delay: 200ms">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-list-check mr-2 text-[#0d5c2f] text-sm"></i>
                    Requirements
                </h3>
            </div>
            <div class="p-4">
                <div id="requirements-container" class="space-y-2">
                    @if($service->requirements)
                        @foreach($service->requirements as $index => $requirement)
                        <div class="flex items-center space-x-2">
                            <input type="text" name="requirements[]" value="{{ $requirement }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm"
                                   placeholder="Enter requirement">
                            <button type="button" onclick="removeRequirement(this)" 
                                    class="w-7 h-7 rounded-md bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-all duration-200 hover:scale-110">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" onclick="addRequirement()" 
                        class="mt-3 px-3 py-1.5 bg-[#0d5c2f]/10 text-[#0d5c2f] rounded-md hover:bg-[#0d5c2f]/20 transition-all duration-200 text-xs">
                    <i class="fas fa-plus mr-1.5 text-xs"></i>Add Requirement
                </button>
            </div>
        </div>

        <!-- Fee Structure Container -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden animate-slideInUp" style="animation-delay: 300ms">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-money-bill-wave mr-2 text-[#0d5c2f] text-sm"></i>
                    Fee Structure
                </h3>
            </div>
            <div class="p-4">
                <div class="border border-gray-200 rounded-md p-4 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Fee Type</label>
                            <input type="text" value="Regular Fee" readonly
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 text-sm cursor-not-allowed">
                            <input type="hidden" name="fee_types[]" value="regular">
                        </div>
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Amount (₱) *</label>
                            <input type="number" name="fee_amounts[]" 
                                   value="{{ old('fee_amounts.0', is_array($service->fees) && isset($service->fees['regular']) ? (is_array($service->fees['regular']) ? $service->fees['regular']['amount'] : $service->fees['regular']) : 0) }}" 
                                   step="0.01" min="0" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50"
                                   placeholder="0.00">
                            @error('fee_amounts.0')
                                <p class="text-red-600 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Description</label>
                        <input type="text" name="fee_descriptions[]" 
                               value="{{ old('fee_descriptions.0', 'Standard service fee') }}" readonly
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 text-sm cursor-not-allowed">
                    </div>
                    <!-- Hidden fields for compatibility -->
                    <input type="hidden" name="fee_min_days[]" value="">
                    <input type="hidden" name="fee_max_days[]" value="">
                </div>
                
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-md p-3">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2 text-sm"></i>
                        <div>
                            <p class="text-xs text-blue-800 font-medium mb-1">Fee Structure Information:</p>
                            <p class="text-xs text-blue-700">This service uses a simplified fee structure with only a regular fee. The amount can be adjusted as needed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Management Container -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden animate-slideInUp" style="animation-delay: 400ms">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-clock mr-2 text-[#0d5c2f] text-sm"></i>
                    Schedule Management
                </h3>
            </div>
            <div class="p-4">
                <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2 text-sm"></i>
                        <div>
                            <p class="text-xs text-blue-800 font-medium mb-1">How to set schedules:</p>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li>• <strong>Available days:</strong> Add time slots for days when the service is offered</li>
                                <li>• <strong>Unavailable days:</strong> Leave the time fields empty (no slots will be shown)</li>
                                <li>• <strong>Example:</strong> If service is only available Monday (10AM, 2PM) and Tuesday (10AM), leave all other days empty</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    @endphp
                    
                    @foreach($days as $index => $day)
                    <div class="border border-gray-200 rounded-md p-3 bg-gray-50">
                        <h4 class="font-medium text-gray-900 mb-3 text-sm">{{ $dayNames[$index] }}</h4>
                        <div id="schedule-{{ $day }}" class="space-y-2">
                            @if($service->schedules && isset($service->schedules[$day]))
                                @foreach($service->schedules[$day] as $time)
                                <div class="flex items-center space-x-2">
                                    <input type="time" name="schedules[{{ $day }}][]" 
                                           value="{{ \Carbon\Carbon::parse($time)->format('H:i') }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">
                                    <button type="button" onclick="removeTimeSlot(this)" 
                                            class="w-7 h-7 rounded-md bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-all duration-200 hover:scale-110">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addTimeSlot('{{ $day }}')" 
                                class="mt-2 px-2 py-1 bg-[#0d5c2f]/10 text-[#0d5c2f] rounded-md hover:bg-[#0d5c2f]/20 transition-all duration-200 text-xs">
                            <i class="fas fa-plus mr-1 text-xs"></i>Add Time
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Notes Container -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden animate-slideInUp" style="animation-delay: 500ms">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-sticky-note mr-2 text-[#0d5c2f] text-sm"></i>
                    Additional Notes
                </h3>
            </div>
            <div class="p-4">
                <div class="group">
                    <label for="notes" class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="notes" name="notes" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50 resize-y">{{ old('notes', $service->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-600 text-xs mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Debug Information (remove in production) -->
        @if(config('app.debug'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-yellow-800 mb-2">Debug Information:</h4>
            <div class="text-xs text-yellow-700 space-y-1">
                <div><strong>Form Action:</strong> {{ route('admin.services.update', $service) }}</div>
                <div><strong>Service ID:</strong> {{ $service->id }}</div>
                <div><strong>CSRF Token:</strong> {{ csrf_token() }}</div>
                <div><strong>Method:</strong> PUT (via method spoofing)</div>
            </div>
        </div>
        @endif
        
        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('admin.services.index') }}" 
               class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-all duration-200 hover:shadow-sm text-sm">
                <i class="fas fa-times mr-1.5 text-sm"></i>Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-[#0d5c2f] text-white rounded-md hover:bg-[#0a4a26] transition-all duration-200 hover:shadow-sm text-sm group">
                <i class="fas fa-save mr-1.5 text-sm group-hover:scale-110 transition-transform duration-200"></i>Update Service
            </button>
        </div>
    </form>
</div>

<style>
@keyframes slideInUp {
    from { 
        opacity: 0; 
        transform: translateY(30px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

.animate-slideInUp {
    animation: slideInUp 0.6s ease-out forwards;
}
</style>

<script>
function addRequirement() {
    const container = document.getElementById('requirements-container');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2';
    div.innerHTML = `
        <input type="text" name="requirements[]" 
               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm"
               placeholder="Enter requirement">
        <button type="button" onclick="removeRequirement(this)" 
                class="w-7 h-7 rounded-md bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-all duration-200 hover:scale-110">
            <i class="fas fa-times text-xs"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeRequirement(button) {
    button.parentElement.remove();
}

// Fee structure functions removed - now using simplified single fee structure

function addTimeSlot(day) {
    const container = document.getElementById(`schedule-${day}`);
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2';
    div.innerHTML = `
        <input type="time" name="schedules[${day}][]" 
               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">
        <button type="button" onclick="removeTimeSlot(this)" 
                class="w-7 h-7 rounded-md bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-all duration-200 hover:scale-110">
            <i class="fas fa-times text-xs"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeTimeSlot(button) {
    button.parentElement.remove();
}

// Add focus effects to form inputs
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-[#0d5c2f]/20', 'ring-offset-1');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-[#0d5c2f]/20', 'ring-offset-1');
        });
    });
    
    // Add form submission handler
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submission started');
            
            // Basic client-side validation
            const name = form.querySelector('#name').value.trim();
            const duration = form.querySelector('#duration_minutes').value;
            const maxSlots = form.querySelector('#max_slots').value;
            const minDays = form.querySelector('#minimum_days').value;
            const maxDays = form.querySelector('#maximum_days').value;
            
            if (!name) {
                alert('Service name is required');
                e.preventDefault();
                return false;
            }
            
            if (!duration || duration < 15 || duration > 480) {
                alert('Duration must be between 15 and 480 minutes');
                e.preventDefault();
                return false;
            }
            
            if (!maxSlots || maxSlots < 1 || maxSlots > 100) {
                alert('Max slots must be between 1 and 100');
                e.preventDefault();
                return false;
            }
            
            if (!minDays || minDays < 1 || minDays > 365) {
                alert('Minimum days must be between 1 and 365');
                e.preventDefault();
                return false;
            }
            
            if (!maxDays || maxDays < 1 || maxDays > 365) {
                alert('Maximum days must be between 1 and 365');
                e.preventDefault();
                return false;
            }
            
            if (parseInt(minDays) > parseInt(maxDays)) {
                alert('Minimum days cannot be greater than maximum days');
                e.preventDefault();
                return false;
            }
            
            // Disable submit button to prevent double submission
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5 text-sm"></i>Updating...';
            }
            
            // Re-enable after 10 seconds in case of issues
            setTimeout(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save mr-1.5 text-sm group-hover:scale-110 transition-transform duration-200"></i>Update Service';
                }
            }, 10000);
        });
    }
});
</script>
@endsection 