@extends('layouts.admin')

@section('title', 'Edit Service')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Edit Service</h1>
                    <p class="text-white/80 mt-1">Update service information and schedules</p>
                </div>
                <a href="{{ route('admin.services.index') }}" 
                   class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Back to Services">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form action="{{ route('admin.services.update', $service) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Basic Information</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Service Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $service->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes) *</label>
                            <input type="number" id="duration_minutes" name="duration_minutes" 
                                   value="{{ old('duration_minutes', $service->duration_minutes) }}" 
                                   min="15" max="480" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                            @error('duration_minutes')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_slots" class="block text-sm font-medium text-gray-700 mb-1">Max Slots *</label>
                            <input type="number" id="max_slots" name="max_slots" 
                                   value="{{ old('max_slots', $service->max_slots) }}" 
                                   min="1" max="100" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                            @error('max_slots')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Booking Restrictions -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Booking Restrictions</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="minimum_days" class="block text-sm font-medium text-gray-700 mb-1">Minimum Days in Advance *</label>
                            <input type="number" id="minimum_days" name="booking_restrictions[minimum_days]" 
                                   value="{{ old('booking_restrictions.minimum_days', $service->booking_restrictions['minimum_days'] ?? 1) }}" 
                                   min="1" max="365" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                            <p class="text-xs text-gray-500 mt-1">Days before service date</p>
                        </div>

                        <div>
                            <label for="maximum_days" class="block text-sm font-medium text-gray-700 mb-1">Maximum Days in Advance *</label>
                            <input type="number" id="maximum_days" name="booking_restrictions[maximum_days]" 
                                   value="{{ old('booking_restrictions.maximum_days', $service->booking_restrictions['maximum_days'] ?? 365) }}" 
                                   min="1" max="365" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                            <p class="text-xs text-gray-500 mt-1">Days before service date</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2"></i>
                            <div>
                                <p class="text-sm text-blue-800 font-medium mb-1">Booking Restriction Examples:</p>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• <strong>Baptism:</strong> Min 4 days, Max 90 days</li>
                                    <li>• <strong>Wedding:</strong> Min 30 days, Max 365 days</li>
                                    <li>• <strong>Blessing:</strong> Min 1 day, Max 60 days</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Requirements and Fees -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Requirements & Fees</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Requirements</label>
                        <div id="requirements-container" class="space-y-2">
                            @if($service->requirements)
                                @foreach($service->requirements as $index => $requirement)
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="requirements[]" value="{{ $requirement }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                           placeholder="Enter requirement">
                                    <button type="button" onclick="removeRequirement(this)" 
                                            class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addRequirement()" 
                                class="mt-2 text-[#0d5c2f] hover:text-[#0d5c2f]/80 text-sm">
                            <i class="fas fa-plus mr-1"></i>Add Requirement
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fee Structure</label>
                        <div id="fees-container" class="space-y-4">
                            @if($service->fees)
                                @foreach($service->fees as $feeType => $feeData)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Fee Type</label>
                                            <input type="text" name="fee_types[]" value="{{ $feeType }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                   placeholder="e.g., regular, rush">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Description</label>
                                            <input type="text" name="fee_descriptions[]" 
                                                   value="{{ is_array($feeData) ? ($feeData['description'] ?? '') : '' }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                   placeholder="e.g., Regular (10+ days advance)">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Amount (₱)</label>
                                            <input type="number" name="fee_amounts[]" 
                                                   value="{{ is_array($feeData) ? $feeData['amount'] : $feeData }}" 
                                                   step="0.01" min="0"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                   placeholder="0.00">
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Min Days</label>
                                                <input type="number" name="fee_min_days[]" 
                                                       value="{{ is_array($feeData) && isset($feeData['condition']['min_days']) ? $feeData['condition']['min_days'] : '' }}"
                                                       min="0" max="365"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                       placeholder="0">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Max Days</label>
                                                <input type="number" name="fee_max_days[]" 
                                                       value="{{ is_array($feeData) && isset($feeData['condition']['max_days']) ? $feeData['condition']['max_days'] : '' }}"
                                                       min="0" max="365"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                       placeholder="365">
                                            </div>
                                        </div>
                                        <button type="button" onclick="removeFee(this)" 
                                                class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Remove Fee
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addFee()" 
                                class="mt-2 text-[#0d5c2f] hover:text-[#0d5c2f]/80 text-sm">
                            <i class="fas fa-plus mr-1"></i>Add Fee Structure
                        </button>
                    </div>
                </div>
            </div>

            <!-- Schedule Management -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Schedule Management</h3>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2"></i>
                        <div>
                            <p class="text-sm text-blue-800 font-medium mb-1">How to set schedules:</p>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• <strong>Available days:</strong> Add time slots for days when the service is offered</li>
                                <li>• <strong>Unavailable days:</strong> Leave the time fields empty (no slots will be shown)</li>
                                <li>• <strong>Example:</strong> If service is only available Monday (10AM, 2PM) and Tuesday (10AM), leave all other days empty</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    @endphp
                    
                    @foreach($days as $index => $day)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-3">{{ $dayNames[$index] }}</h4>
                        <div id="schedule-{{ $day }}" class="space-y-2">
                            @if($service->schedules && isset($service->schedules[$day]))
                                @foreach($service->schedules[$day] as $time)
                                <div class="flex items-center space-x-2">
                                    <input type="time" name="schedules[{{ $day }}][]" value="{{ date('H:i', strtotime($time)) }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                                    <select name="schedule_ampm[{{ $day }}][]" 
                                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                                        <option value="AM" {{ strpos($time, 'AM') !== false ? 'selected' : '' }}>AM</option>
                                        <option value="PM" {{ strpos($time, 'PM') !== false ? 'selected' : '' }}>PM</option>
                                    </select>
                                    <button type="button" onclick="removeTimeSlot(this)" 
                                            class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addTimeSlot('{{ $day }}')" 
                                class="mt-2 text-[#0d5c2f] hover:text-[#0d5c2f]/80 text-sm">
                            <i class="fas fa-plus mr-1"></i>Add Time
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-8">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea id="notes" name="notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">{{ old('notes', $service->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.services.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                    <i class="fas fa-save mr-2"></i>Update Service
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function addRequirement() {
    const container = document.getElementById('requirements-container');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2';
    div.innerHTML = `
        <input type="text" name="requirements[]" 
               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
               placeholder="Enter requirement">
        <button type="button" onclick="removeRequirement(this)" 
                class="text-red-600 hover:text-red-800">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeRequirement(button) {
    button.parentElement.remove();
}

function addFee() {
    const container = document.getElementById('fees-container');
    const div = document.createElement('div');
    div.className = 'border border-gray-200 rounded-lg p-4';
    div.innerHTML = `
        <div class="grid grid-cols-1 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Fee Type</label>
                <input type="text" name="fee_types[]" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                       placeholder="e.g., regular, rush">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Description</label>
                <input type="text" name="fee_descriptions[]" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                       placeholder="e.g., Regular (10+ days advance)">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Amount (₱)</label>
                <input type="number" name="fee_amounts[]" step="0.01" min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                       placeholder="0.00">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Min Days</label>
                    <input type="number" name="fee_min_days[]" min="0" max="365"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                           placeholder="0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Max Days</label>
                    <input type="number" name="fee_max_days[]" min="0" max="365"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                           placeholder="365">
                </div>
            </div>
            <button type="button" onclick="removeFee(this)" 
                    class="text-red-600 hover:text-red-800 text-sm">
                <i class="fas fa-trash mr-1"></i>Remove Fee
            </button>
        </div>
    `;
    container.appendChild(div);
}

function removeFee(button) {
    button.parentElement.remove();
}

function addTimeSlot(day) {
    const container = document.getElementById(`schedule-${day}`);
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2';
    div.innerHTML = `
        <input type="time" name="schedules[${day}][]" 
               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
        <select name="schedule_ampm[${day}][]" 
                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
            <option value="AM">AM</option>
            <option value="PM">PM</option>
        </select>
        <button type="button" onclick="removeTimeSlot(this)" 
                class="text-red-600 hover:text-red-800">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeTimeSlot(button) {
    button.parentElement.remove();
}
</script>
@endsection 