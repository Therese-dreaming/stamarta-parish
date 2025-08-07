@extends('layouts.user')

@section('title', 'Book Service - Step 1')

@section('content')
<!-- Progress Bar -->
<div class="bg-white border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-center">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="bg-[#0d5c2f] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">1</div>
                    <span class="ml-2 text-sm font-medium text-[#0d5c2f]">Personal Information</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="bg-gray-300 text-gray-500 rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">2</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Schedule Selection</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="bg-gray-300 text-gray-500 rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Requirements</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Service Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Service Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $service->name }}</h3>
                        @if($service->description)
                            <p class="text-gray-600 mb-4">{{ $service->description }}</p>
                        @endif
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-[#0d5c2f] mr-2"></i>
                                <span class="text-sm text-gray-600">{{ $service->formatted_duration }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users text-[#0d5c2f] mr-2"></i>
                                <span class="text-sm text-gray-600">Max {{ $service->max_slots }} slot(s)</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Fees:</h4>
                        <p class="text-lg font-semibold text-[#0d5c2f]">{{ $service->formatted_fees }}</p>
                        
                        @if($service->schedules)
                            <h4 class="font-semibold text-gray-900 mb-2 mt-4">Available Schedule:</h4>
                            <div class="text-sm text-gray-600">
                                @foreach($service->schedules as $day => $times)
                                    <div class="mb-1">
                                        <strong>{{ ucfirst($day) }}:</strong> 
                                        {{ implode(', ', $times) }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Personal Information</h2>
                <p class="text-gray-600 mt-1">Please provide your contact details and service-specific information</p>
            </div>
            
            <form action="{{ route('booking.step1.store', $service) }}" method="POST" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Contact Phone -->
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Phone *
                        </label>
                        <input type="tel" id="contact_phone" name="contact_phone" 
                               value="{{ old('contact_phone', Auth::user()->phone ?? '') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                               placeholder="+63 912 345 6789">
                        @error('contact_phone')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Address -->
                    <div class="md:col-span-2">
                        <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Address *
                        </label>
                        <textarea id="contact_address" name="contact_address" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                  placeholder="Enter your complete address">{{ old('contact_address') }}</textarea>
                        @error('contact_address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dynamic Custom Fields -->
                    @php
                        $customFields = \App\Services\ServiceConfigService::getCustomFields($service->service_type);
                    @endphp
                    
                    @if($customFields)
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                                Service-Specific Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($customFields as $fieldKey => $fieldConfig)
                                    <div class="{{ $fieldConfig['type'] === 'textarea' ? 'md:col-span-2' : '' }}">
                                        <label for="custom_fields_{{ $fieldKey }}" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $fieldConfig['label'] }}
                                            @if($fieldConfig['required'])
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </label>
                                        
                                        @if($fieldConfig['type'] === 'textarea')
                                            <textarea 
                                                id="custom_fields_{{ $fieldKey }}" 
                                                name="custom_fields[{{ $fieldKey }}]"
                                                rows="3"
                                                @if($fieldConfig['required']) required @endif
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                placeholder="{{ $fieldConfig['placeholder'] ?? '' }}"
                                            >{{ old("custom_fields.{$fieldKey}") }}</textarea>
                                        @elseif($fieldConfig['type'] === 'select')
                                            <select 
                                                id="custom_fields_{{ $fieldKey }}" 
                                                name="custom_fields[{{ $fieldKey }}]"
                                                @if($fieldConfig['required']) required @endif
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                            >
                                                <option value="">Select {{ $fieldConfig['label'] }}</option>
                                                @foreach($fieldConfig['options'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old("custom_fields.{$fieldKey}") == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
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

                    <!-- Additional Notes -->
                    <div class="md:col-span-2">
                        <label for="additional_notes" class="block text-sm font-medium text-gray-700 mb-2">
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
            </form>
        </div>
    </div>
</div>
@endsection 