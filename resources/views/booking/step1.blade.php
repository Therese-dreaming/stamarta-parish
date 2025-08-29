@extends('layouts.user')

@section('title', 'Book Service - Step 1')

@section('content')
<!-- Modern Progress Indicator -->
<div class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-gray-900 mb-2 sm:mb-0">Book {{ $service->name }}</h1>
            <div class="text-sm text-gray-500">Step 1 of 3</div>
        </div>
        
        <div class="relative mt-4">
            <!-- Progress Bar Background -->
            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-[#0d5c2f] rounded-full" style="width: 33.33%"></div>
            </div>
            
            <!-- Step Indicators -->
            <div class="flex justify-between items-center mt-2">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-[#0d5c2f] text-white flex items-center justify-center text-sm font-medium shadow-md">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="text-xs font-medium text-[#0d5c2f] mt-2">Personal Info</span>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-medium">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-500 mt-2">Schedule</span>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-medium">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-500 mt-2">Requirements</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Service Information Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 sticky top-6">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900">Service Details</h2>
                            <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center">
                                <i class="fas fa-concierge-bell text-[#0d5c2f]"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $service->name }}</h3>
                        
                        @if($service->description)
                            <p class="text-gray-600 mb-6 text-sm">{{ $service->description }}</p>
                        @endif
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-3 border-t border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                        <i class="fas fa-clock text-[#0d5c2f] text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Duration</span>
                                </div>
                                <span class="text-sm text-gray-600">{{ $service->formatted_duration }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between py-3 border-t border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                        <i class="fas fa-users text-[#0d5c2f] text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Capacity</span>
                                </div>
                                <span class="text-sm text-gray-600">Max {{ $service->max_slots }} slot(s)</span>
                            </div>
                            
                            <div class="flex items-center justify-between py-3 border-t border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                        <i class="fas fa-money-bill-wave text-[#0d5c2f] text-sm"></i>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-[#0d5c2f]">{{ $service->formatted_fees }}</span>
                            </div>
                            
                            @if($service->schedules)
                                <div class="pt-3 border-t border-gray-100">
                                    <div class="flex items-center mb-3">
                                        <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                            <i class="fas fa-calendar-alt text-[#0d5c2f] text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Available Times</span>
                                    </div>
                                    
                                    <div class="text-xs text-gray-600 space-y-2 ml-11">
                                        @foreach($service->schedules as $day => $times)
                                            <div>
                                                <span class="font-medium text-gray-700">{{ ucfirst($day) }}:</span> 
                                                {{ implode(', ', $times) }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Personal Information Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Personal Information</h2>
                        <p class="text-gray-500 text-sm mt-1">Please provide your contact details and service-specific information</p>
                    </div>
                    
                    <form action="{{ route('booking.step1.store', $service) }}" method="POST" class="p-6">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Contact Information Section -->
                            <div>
                                <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-address-card text-[#0d5c2f] mr-2"></i>Contact Information
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Contact Phone -->
                                    <div>
                                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                            Contact Phone <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-phone text-gray-400"></i>
                                            </div>
                                            <input type="tel" id="contact_phone" name="contact_phone" 
                                                value="{{ old('contact_phone', Auth::user()->phone ?? '') }}" required
                                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                placeholder="+63 912 345 6789">
                                        </div>
                                        @error('contact_phone')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Contact Address -->
                                    <div class="md:col-span-2">
                                        <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-1">
                                            Contact Address <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                                            </div>
                                            <textarea id="contact_address" name="contact_address" rows="2" required
                                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                    placeholder="Enter your complete address">{{ old('contact_address') }}</textarea>
                                        </div>
                                        @error('contact_address')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic Custom Fields -->
                            @php
                                $customFields = \App\Services\ServiceConfigService::getCustomFields($service->service_type);
                            @endphp
                            
                            @if($customFields)
                                <div>
                                    <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-clipboard-list text-[#0d5c2f] mr-2"></i>Service-Specific Information
                                    </h3>

                                    @php
                                        // Group fields by category
                                        $fieldGroups = [
                                            'child' => ['child_last_name', 'child_first_name', 'child_middle_initial', 'child_birth_date', 'place_of_birth', 'nationality'],
                                            'parents' => ['father_last_name', 'father_first_name', 'father_middle_initial', 'mother_last_name', 'mother_first_name', 'mother_middle_initial'],
                                            'godparents' => ['godparents'],
                                            'groom' => ['groom_name', 'groom_birth_date', 'groom_religion'],
                                            'bride' => ['bride_name', 'bride_birth_date', 'bride_religion'],
                                            'witnesses' => ['witnesses'],
                                            'person' => ['person_last_name', 'person_first_name', 'person_middle_initial', 'blessing_type', 'blessing_details'],
                                            'other' => []
                                        ];
                                        
                                        // Categorize fields
                                        $categorizedFields = [];
                                        foreach ($customFields as $fieldKey => $fieldConfig) {
                                            $categorized = false;
                                            foreach ($fieldGroups as $groupKey => $groupFields) {
                                                if (in_array($fieldKey, $groupFields)) {
                                                    $categorizedFields[$groupKey][$fieldKey] = $fieldConfig;
                                                    $categorized = true;
                                                    break;
                                                }
                                            }
                                            if (!$categorized) {
                                                $categorizedFields['other'][$fieldKey] = $fieldConfig;
                                            }
                                        }
                                    @endphp

                                    <div class="space-y-6">
                                        @foreach($categorizedFields as $groupKey => $groupFields)
                                            @if(!empty($groupFields))
                                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                        @if($groupKey === 'child')
                                                            <i class="fas fa-baby text-[#0d5c2f] mr-2"></i>Child's Information
                                                        @elseif($groupKey === 'parents')
                                                            <i class="fas fa-users text-[#0d5c2f] mr-2"></i>Parents' Information
                                                        @elseif($groupKey === 'godparents')
                                                            <i class="fas fa-user-friends text-[#0d5c2f] mr-2"></i>Godparents
                                                        @elseif($groupKey === 'groom')
                                                            <i class="fas fa-male text-[#0d5c2f] mr-2"></i>Groom's Information
                                                        @elseif($groupKey === 'bride')
                                                            <i class="fas fa-female text-[#0d5c2f] mr-2"></i>Bride's Information
                                                        @elseif($groupKey === 'witnesses')
                                                            <i class="fas fa-user-friends text-[#0d5c2f] mr-2"></i>Witnesses
                                                        @elseif($groupKey === 'person')
                                                            <i class="fas fa-user text-[#0d5c2f] mr-2"></i>Person Information
                                                        @else
                                                            <i class="fas fa-info-circle text-[#0d5c2f] mr-2"></i>Additional Information
                                                        @endif
                                                    </h4>
                                                    
                                                    <div class="space-y-4">
                                                        @foreach($groupFields as $fieldKey => $fieldConfig)
                                                            <div>
                                                                @if($fieldConfig['type'] === 'array')
                                                                    <div id="array-{{ $fieldKey }}-list" class="space-y-2">
                                                                        @php
                                                                            $oldValues = old("custom_fields.{$fieldKey}");
                                                                            $values = is_array($oldValues) && count($oldValues) > 0 ? $oldValues : [''];
                                                                        @endphp
                                                                        @foreach($values as $idx => $value)
                                                                            <div class="flex items-center gap-2">
                                                                                <input type="text" name="custom_fields[{{ $fieldKey }}][{{ $idx }}]" value="{{ $value }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="{{ $fieldConfig['placeholder'] ?? 'Enter value' }}" @if($fieldConfig['required']) required @endif>
                                                                                <button type="button" class="remove-array-item w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-full" aria-label="Remove"><i class="fas fa-times"></i></button>
                                                                            </div>
                                                                            @error("custom_fields.$fieldKey.$idx")
                                                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                                            @enderror
                                                                        @endforeach
                                                                    </div>
                                                                    <button type="button" data-array-key="{{ $fieldKey }}" class="add-array-item mt-2 px-3 py-2 text-sm text-[#0d5c2f] border border-[#0d5c2f] rounded-lg hover:bg-[#0d5c2f]/5 flex items-center">
                                                                        <i class="fas fa-plus mr-2"></i> Add
                                                                    </button>
                                                                @elseif($fieldConfig['type'] === 'select')
                                                                    <label for="custom_fields_{{ $fieldKey }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                                        {{ $fieldConfig['label'] }}
                                                                        @if($fieldConfig['required'])
                                                                            <span class="text-red-500">*</span>
                                                                        @endif
                                                                    </label>
                                                                    <select 
                                                                        id="custom_fields_{{ $fieldKey }}" 
                                                                        name="custom_fields[{{ $fieldKey }}]"
                                                                        @if($fieldConfig['required']) required @endif
                                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                                    >
                                                                        <option value="">Select {{ $fieldConfig['label'] }}</option>
                                                                        @foreach($fieldConfig['options'] as $optionValue => $optionLabel)
                                                                            <option value="{{ $optionValue }}" @selected(old("custom_fields.{$fieldKey}") == $optionValue)>{{ $optionLabel }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @elseif($fieldConfig['type'] === 'textarea')
                                                                    <label for="custom_fields_{{ $fieldKey }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                                        {{ $fieldConfig['label'] }}
                                                                        @if($fieldConfig['required'])
                                                                            <span class="text-red-500">*</span>
                                                                        @endif
                                                                    </label>
                                                                    <textarea 
                                                                        id="custom_fields_{{ $fieldKey }}" 
                                                                        name="custom_fields[{{ $fieldKey }}]"
                                                                        rows="3"
                                                                        @if($fieldConfig['required']) required @endif
                                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                                        placeholder="{{ $fieldConfig['placeholder'] ?? '' }}"
                                                                    >{{ old("custom_fields.{$fieldKey}") }}</textarea>
                                                                @elseif($fieldConfig['type'] === 'date')
                                                                    <label for="custom_fields_{{ $fieldKey }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                                        {{ $fieldConfig['label'] }}
                                                                        @if($fieldConfig['required'])
                                                                            <span class="text-red-500">*</span>
                                                                        @endif
                                                                    </label>
                                                                    <div class="relative">
                                                                        <i class="fas fa-calendar-alt text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                                                                        <input 
                                                                            type="date" 
                                                                            id="custom_fields_{{ $fieldKey }}" 
                                                                            name="custom_fields[{{ $fieldKey }}]"
                                                                            @if($fieldConfig['required']) required @endif
                                                                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                                            value="{{ old("custom_fields.{$fieldKey}") }}"
                                                                        >
                                                                    </div>
                                                                @else
                                                                    <label for="custom_fields_{{ $fieldKey }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                                        {{ $fieldConfig['label'] }}
                                                                        @if($fieldConfig['required'])
                                                                            <span class="text-red-500">*</span>
                                                                        @endif
                                                                    </label>
                                                                    <input 
                                                                        type="{{ $fieldConfig['type'] }}" 
                                                                        id="custom_fields_{{ $fieldKey }}" 
                                                                        name="custom_fields[{{ $fieldKey }}]"
                                                                        @if($fieldConfig['required']) required @endif
                                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                                        placeholder="{{ $fieldConfig['placeholder'] ?? '' }}"
                                                                        value="{{ old("custom_fields.{$fieldKey}") }}"
                                                                    >
                                                                @endif
                                                                @error("custom_fields.{$fieldKey}")
                                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Additional Notes -->
                            <div>
                                <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-sticky-note text-[#0d5c2f] mr-2"></i>Additional Information
                                </h3>
                                
                                <div>
                                    <label for="additional_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                        Additional Notes (Optional)
                                    </label>
                                    <textarea id="additional_notes" name="additional_notes" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                            placeholder="Any special requests or additional information">{{ old('additional_notes') }}</textarea>
                                    @error('additional_notes')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                                <a href="{{ route('services.index') }}" 
                                   class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to Services
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                                    Continue to Schedule Selection
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                // Generic array-type dynamic add/remove (e.g., witnesses)
                                document.querySelectorAll('.add-array-item').forEach(addBtn => {
                                    addBtn.addEventListener('click', function () {
                                        const key = this.getAttribute('data-array-key');
                                        const list = document.getElementById(`array-${key}-list`);
                                        const idx = list.querySelectorAll('div.flex').length;
                                        const wrapper = document.createElement('div');
                                        wrapper.className = 'flex items-center gap-2';
                                        wrapper.innerHTML = `
                                            <input type="text" name="custom_fields[${key}][${idx}]" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="Enter value" required>
                                            <button type="button" class="remove-array-item w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-full" aria-label="Remove"><i class="fas fa-times"></i></button>
                                        `;
                                        list.appendChild(wrapper);
                                        bindGenericArrayRemove(list);
                                    });
                                });

                                function bindGenericArrayRemove(scope) {
                                    (scope || document).querySelectorAll('.remove-array-item').forEach(btn => {
                                        btn.onclick = function () {
                                            const row = this.closest('div.flex');
                                            if (row) row.remove();
                                        };
                                    });
                                }

                                bindGenericArrayRemove();
                            });
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection