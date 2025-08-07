@extends('layouts.admin')

@section('title', 'Edit Parochial Activity')

@section('content')
@include('components.toast')
<div class="font-[Poppins]">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Parochial Activity</h1>
                <p class="text-gray-600">Update parish event or activity</p>
            </div>
            <a href="{{ route('admin.parochial-activities.index') }}" class="text-[#0d5c2f] hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to Activities
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Activity Details</h2>
        </div>
        
        <form action="{{ route('admin.parochial-activities.update', $parochialActivity) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Activity Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $parochialActivity->title) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">{{ old('description', $parochialActivity->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $parochialActivity->location) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="organizer" class="block text-sm font-medium text-gray-700 mb-2">Organizer</label>
                        <input type="text" name="organizer" id="organizer" value="{{ old('organizer', $parochialActivity->organizer) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        @error('organizer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date and Time -->
                <div class="space-y-6">
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <span id="event_date_label">{{ $parochialActivity->is_recurring ? 'Day of Week *' : 'Event Date *' }}</span>
                        </label>
                        <input type="date" name="event_date" id="event_date" value="{{ old('event_date', $parochialActivity->event_date->format('Y-m-d')) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        <p id="event_date_help" class="mt-1 text-sm text-gray-500">
                            {{ $parochialActivity->is_recurring ? 'Select any date that falls on the desired day of the week (e.g., any Monday for a Monday activity)' : 'Select the specific date for this activity' }}
                        </p>
                        @error('event_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $parochialActivity->start_time->format('H:i')) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $parochialActivity->end_time->format('H:i')) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="block_type" class="block text-sm font-medium text-gray-700 mb-2">Booking Block Type *</label>
                        <select name="block_type" id="block_type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                            <option value="">Select block type</option>
                            <option value="time_slot" {{ old('block_type', $parochialActivity->block_type) == 'time_slot' ? 'selected' : '' }}>
                                Time Slot Only - Block only the specific time period
                            </option>
                            <option value="full_day" {{ old('block_type', $parochialActivity->block_type) == 'full_day' ? 'selected' : '' }}>
                                Full Day - Block the entire day for bookings
                            </option>
                        </select>
                        @error('block_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" id="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                            <option value="active" {{ old('status', $parochialActivity->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="cancelled" {{ old('status', $parochialActivity->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ old('status', $parochialActivity->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                        <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $parochialActivity->contact_person) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        @error('contact_person')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                        <input type="tel" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $parochialActivity->contact_phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        @error('contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $parochialActivity->contact_email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        @error('contact_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Recurring Options -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="is_recurring" id="is_recurring" value="1" {{ old('is_recurring', $parochialActivity->is_recurring) ? 'checked' : '' }}
                           class="h-4 w-4 text-[#0d5c2f] focus:ring-[#0d5c2f] border-gray-300 rounded">
                    <label for="is_recurring" class="ml-2 block text-sm font-medium text-gray-900">This is a recurring activity</label>
                </div>

                <div id="recurring-options" class="{{ old('is_recurring', $parochialActivity->is_recurring) ? '' : 'hidden' }} space-y-4">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="recurring_pattern_type" class="block text-sm font-medium text-gray-700 mb-2">Recurring Pattern</label>
                            <select name="recurring_pattern[type]" id="recurring_pattern_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                                <option value="weekly" {{ old('recurring_pattern.type', $parochialActivity->recurring_pattern['type'] ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ old('recurring_pattern.type', $parochialActivity->recurring_pattern['type'] ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                        </div>

                        <div>
                            <label for="recurring_end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                            <input type="date" name="recurring_end_date" id="recurring_end_date" value="{{ old('recurring_end_date', $parochialActivity->recurring_end_date?->format('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">{{ old('notes', $parochialActivity->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-4">
                <a href="{{ route('admin.parochial-activities.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors">
                    Update Activity
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('is_recurring').addEventListener('change', function() {
    const recurringOptions = document.getElementById('recurring-options');
    const eventDateLabel = document.getElementById('event_date_label');
    const eventDateHelp = document.getElementById('event_date_help');
    
    if (this.checked) {
        recurringOptions.classList.remove('hidden');
        eventDateLabel.textContent = 'Day of Week *';
        eventDateHelp.textContent = 'Select any date that falls on the desired day of the week (e.g., any Monday for a Monday activity)';
    } else {
        recurringOptions.classList.add('hidden');
        eventDateLabel.textContent = 'Event Date *';
        eventDateHelp.textContent = 'Select the specific date for this activity';
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const isRecurringCheckbox = document.getElementById('is_recurring');
    if (isRecurringCheckbox.checked) {
        isRecurringCheckbox.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection 