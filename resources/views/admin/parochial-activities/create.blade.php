@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Create Parochial Activity')

@section('content')
@include('components.toast')
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-lg shadow-md overflow-hidden">
        <div class="px-4 py-4 relative">
            <div class="absolute right-0 top-0 w-16 h-16 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-xl font-bold text-white">Create Parochial Activity</h1>
                    <p class="text-white/80 mt-1 text-xs flex items-center">
                        <i class="fas fa-plus-circle mr-1.5 text-xs"></i>Add a new parish event or activity
                    </p>
                </div>
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.index') : route('admin.parochial-activities.index') }}" 
                   class="group px-3 py-1.5 rounded-md bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow text-xs">
                    <i class="fas fa-arrow-left mr-1.5 text-xs group-hover:-translate-x-1 transition-transform duration-200"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.store') : route('admin.parochial-activities.store') }}" method="POST" class="space-y-4">
        @csrf
        
        <!-- Basic Information Container -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-edit mr-2 text-[#0d5c2f] text-sm"></i>
                    Basic Information
                </h2>
            </div>
            <div class="p-4 space-y-4">
                <div class="group">
                    <label for="title" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-tag mr-1.5 text-[#0d5c2f] text-xs"></i>Activity Title *
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="group">
                    <label for="description" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-align-left mr-1.5 text-[#0d5c2f] text-xs"></i>Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50 resize-y">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="group">
                        <label for="location" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-map-marker-alt mr-1.5 text-[#0d5c2f] text-xs"></i>Location
                        </label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        @error('location')
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="organizer" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-user mr-1.5 text-[#0d5c2f] text-xs"></i>Organizer
                        </label>
                        <input type="text" name="organizer" id="organizer" value="{{ old('organizer') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        @error('organizer')
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Date and Time Container -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-[#0d5c2f] text-sm"></i>
                    Date and Time
                </h2>
            </div>
            <div class="p-4 space-y-4">
                <div class="group">
                    <label for="event_date" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-calendar-alt mr-1.5 text-[#0d5c2f] text-xs"></i>
                        <span id="event_date_label">Event Date *</span>
                    </label>
                    <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}" required
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                    <p id="event_date_help" class="mt-1 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-1 text-xs"></i>Select any date that falls on the desired day of the week
                    </p>
                    @error('event_date')
                        <p class="mt-1 text-xs text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="group">
                        <label for="start_time" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-clock mr-1.5 text-[#0d5c2f] text-xs"></i>Start Time *
                        </label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        @error('start_time')
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="end_time" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-clock mr-1.5 text-[#0d5c2f] text-xs"></i>End Time *
                        </label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        @error('end_time')
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="group">
                    <label for="block_type" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-ban mr-1.5 text-[#0d5c2f] text-xs"></i>Booking Block Type *
                    </label>
                    <select name="block_type" id="block_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        <option value="">Select block type</option>
                        <option value="time_slot" {{ old('block_type') == 'time_slot' ? 'selected' : '' }}>
                            Time Slot Only - Block only the specific time period
                        </option>
                        <option value="full_day" {{ old('block_type') == 'full_day' ? 'selected' : '' }}>
                            Full Day - Block the entire day for bookings
                        </option>
                    </select>
                    @error('block_type')
                        <p class="mt-1 text-xs text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact Information Container -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-address-book mr-2 text-[#0d5c2f] text-sm"></i>
                    Contact Information
                </h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="group">
                        <label for="contact_person" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-user-tie mr-1.5 text-[#0d5c2f] text-xs"></i>Contact Person
                        </label>
                        <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        @error('contact_person')
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="contact_phone" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-phone mr-1.5 text-[#0d5c2f] text-xs"></i>Contact Phone
                        </label>
                        <input type="tel" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        @error('contact_phone')
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="contact_email" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-envelope mr-1.5 text-[#0d5c2f] text-xs"></i>Contact Email
                        </label>
                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        @error('contact_email')
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Recurring Options Container -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-sync-alt mr-2 text-[#0d5c2f] text-sm"></i>
                    Recurring Options
                </h2>
            </div>
            <div class="p-4">
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="is_recurring" id="is_recurring" value="1" {{ old('is_recurring') ? 'checked' : '' }}
                           class="h-4 w-4 text-[#0d5c2f] focus:ring-[#0d5c2f] border-gray-300 rounded">
                    <label for="is_recurring" class="ml-2 block text-sm font-medium text-gray-900 flex items-center">
                        <i class="fas fa-sync-alt mr-1.5 text-[#0d5c2f] text-xs"></i>This is a recurring activity
                    </label>
                </div>

                <div id="recurring-options" class="{{ old('is_recurring') ? '' : 'hidden' }} space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="group">
                            <label for="recurring_pattern_type" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-calendar-week mr-1.5 text-[#0d5c2f] text-xs"></i>Recurring Pattern
                            </label>
                            <select name="recurring_pattern[type]" id="recurring_pattern_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                                <option value="weekly" {{ old('recurring_pattern.type') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ old('recurring_pattern.type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ old('recurring_pattern.type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>

                        <div class="group">
                            <label for="recurring_end_date" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-calendar-times mr-1.5 text-[#0d5c2f] text-xs"></i>End Date
                            </label>
                            <input type="date" name="recurring_end_date" id="recurring_end_date" value="{{ old('recurring_end_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Container -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-sticky-note mr-2 text-[#0d5c2f] text-sm"></i>
                    Additional Notes
                </h2>
            </div>
            <div class="p-4">
                <div class="group">
                    <label for="notes" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-sticky-note mr-1.5 text-[#0d5c2f] text-xs"></i>Notes
                    </label>
                    <textarea name="notes" id="notes" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50 resize-y">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-xs text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.index') : route('admin.parochial-activities.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors flex items-center text-sm">
                <i class="fas fa-times mr-1.5 text-sm"></i>Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-[#0d5c2f] text-white rounded-md hover:bg-[#0a4a26] transition-colors flex items-center text-sm group">
                <i class="fas fa-save mr-1.5 text-sm group-hover:scale-110 transition-transform duration-200"></i>Create Activity
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('is_recurring').addEventListener('change', function() {
    const recurringOptions = document.getElementById('recurring-options');
    const eventDateLabel = document.getElementById('event_date_label');
    const eventDateHelp = document.getElementById('event_date_help');
    
    if (this.checked) {
        recurringOptions.classList.remove('hidden');
        eventDateLabel.textContent = 'Day of Week *';
        eventDateHelp.innerHTML = '<i class="fas fa-info-circle mr-1 text-xs"></i>Select any date that falls on the desired day of the week (e.g., any Monday for a Monday activity)';
    } else {
        recurringOptions.classList.add('hidden');
        eventDateLabel.textContent = 'Event Date *';
        eventDateHelp.innerHTML = '<i class="fas fa-info-circle mr-1 text-xs"></i>Select the specific date for this activity';
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const isRecurringCheckbox = document.getElementById('is_recurring');
    if (isRecurringCheckbox.checked) {
        isRecurringCheckbox.dispatchEvent(new Event('change'));
    }
});

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
});
</script>
@endsection 