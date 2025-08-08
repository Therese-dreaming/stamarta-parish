@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Edit Parochial Activity')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Edit Parochial Activity</h1>
                    <p class="text-white/80 mt-1 flex items-center">
                        <i class="fas fa-edit mr-2"></i>Update parish event or activity
                    </p>
                </div>
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.index') : route('admin.parochial-activities.index') }}" 
                   class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Back to Activities">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-edit mr-2 text-[#0d5c2f]"></i>
                Activity Details
            </h2>
        </div>
        
        <form action="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.update', $parochialActivity) : route('admin.parochial-activities.update', $parochialActivity) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-tag mr-2 text-[#0d5c2f]"></i>Activity Title *
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $parochialActivity->title) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-align-left mr-2 text-[#0d5c2f]"></i>Description
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">{{ old('description', $parochialActivity->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-[#0d5c2f]"></i>Location
                        </label>
                        <input type="text" name="location" id="location" value="{{ old('location', $parochialActivity->location) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <label for="organizer" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>Organizer
                        </label>
                        <input type="text" name="organizer" id="organizer" value="{{ old('organizer', $parochialActivity->organizer) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                        @error('organizer')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Date and Time -->
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-[#0d5c2f]"></i>
                            <span id="event_date_label">{{ $parochialActivity->is_recurring ? 'Day of Week *' : 'Event Date *' }}</span>
                        </label>
                        <input type="date" name="event_date" id="event_date" value="{{ old('event_date', $parochialActivity->event_date->format('Y-m-d')) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                        <p id="event_date_help" class="mt-1 text-sm text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ $parochialActivity->is_recurring ? 'Select any date that falls on the desired day of the week (e.g., any Monday for a Monday activity)' : 'Select the specific date for this activity' }}
                        </p>
                        @error('event_date')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-clock mr-2 text-[#0d5c2f]"></i>Start Time *
                            </label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $parochialActivity->start_time->format('H:i')) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-clock mr-2 text-[#0d5c2f]"></i>End Time *
                            </label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $parochialActivity->end_time->format('H:i')) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <label for="block_type" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-ban mr-2 text-[#0d5c2f]"></i>Booking Block Type *
                        </label>
                        <select name="block_type" id="block_type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                            <option value="">Select block type</option>
                            <option value="time_slot" {{ old('block_type', $parochialActivity->block_type) == 'time_slot' ? 'selected' : '' }}>
                                Time Slot Only - Block only the specific time period
                            </option>
                            <option value="full_day" {{ old('block_type', $parochialActivity->block_type) == 'full_day' ? 'selected' : '' }}>
                                Full Day - Block the entire day for bookings
                            </option>
                        </select>
                        @error('block_type')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-toggle-on mr-2 text-[#0d5c2f]"></i>Status *
                        </label>
                        <select name="status" id="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                            <option value="active" {{ old('status', $parochialActivity->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="cancelled" {{ old('status', $parochialActivity->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ old('status', $parochialActivity->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-address-book mr-2 text-[#0d5c2f]"></i>
                    Contact Information
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-user-tie mr-2 text-[#0d5c2f]"></i>Contact Person
                        </label>
                        <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $parochialActivity->contact_person) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                        @error('contact_person')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-phone mr-2 text-[#0d5c2f]"></i>Contact Phone
                        </label>
                        <input type="tel" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $parochialActivity->contact_phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                        @error('contact_phone')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-envelope mr-2 text-[#0d5c2f]"></i>Contact Email
                        </label>
                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $parochialActivity->contact_email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                        @error('contact_email')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Recurring Options -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="is_recurring" id="is_recurring" value="1" {{ old('is_recurring', $parochialActivity->is_recurring) ? 'checked' : '' }}
                               class="h-4 w-4 text-[#0d5c2f] focus:ring-[#0d5c2f] border-gray-300 rounded">
                        <label for="is_recurring" class="ml-2 block text-sm font-medium text-gray-900 flex items-center">
                            <i class="fas fa-sync-alt mr-2 text-[#0d5c2f]"></i>This is a recurring activity
                        </label>
                    </div>

                    <div id="recurring-options" class="{{ old('is_recurring', $parochialActivity->is_recurring) ? '' : 'hidden' }} space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-white rounded-lg p-4">
                                <label for="recurring_pattern_type" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-calendar-week mr-2 text-[#0d5c2f]"></i>Recurring Pattern
                                </label>
                                <select name="recurring_pattern[type]" id="recurring_pattern_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                                    <option value="weekly" {{ old('recurring_pattern.type', $parochialActivity->recurring_pattern['type'] ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ old('recurring_pattern.type', $parochialActivity->recurring_pattern['type'] ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                            </div>

                            <div class="bg-white rounded-lg p-4">
                                <label for="recurring_end_date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-calendar-times mr-2 text-[#0d5c2f]"></i>End Date
                                </label>
                                <input type="date" name="recurring_end_date" id="recurring_end_date" value="{{ old('recurring_end_date', $parochialActivity->recurring_end_date?->format('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="bg-gray-50 rounded-lg p-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-sticky-note mr-2 text-[#0d5c2f]"></i>Additional Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">{{ old('notes', $parochialActivity->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-4">
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.index') : route('admin.parochial-activities.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex items-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>Update Activity
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
        eventDateHelp.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Select any date that falls on the desired day of the week (e.g., any Monday for a Monday activity)';
    } else {
        recurringOptions.classList.add('hidden');
        eventDateLabel.textContent = 'Event Date *';
        eventDateHelp.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Select the specific date for this activity';
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