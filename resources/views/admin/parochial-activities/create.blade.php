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

    <!-- Conflict Error Alert -->
    @if($errors->has('conflict'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-red-800">Cannot Create Activity</h3>
                    <p class="mt-1 text-sm text-red-700">{{ $errors->first('conflict') }}</p>
                </div>
            </div>
        </div>
    @endif

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

                <!-- Conflict Warning Container -->
                <div id="conflict-warning" class="hidden mt-4"></div>
                
                <!-- Container for excluded dates hidden inputs (dynamically added) -->
                <div id="excluded-dates-container"></div>
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
let conflictCheckTimeout = null;
let hasBlockingConflicts = false;
let skipConflictingDates = false;
let conflictingDatesArray = [];

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

// Real-time conflict checking
function checkConflicts() {
    const eventDate = document.getElementById('event_date').value;
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    const blockType = document.getElementById('block_type').value;
    const isRecurring = document.getElementById('is_recurring').checked;
    const recurringPatternType = document.getElementById('recurring_pattern_type')?.value || 'weekly';
    const recurringEndDate = document.getElementById('recurring_end_date')?.value;
    
    // Only check if all required fields are filled
    if (!eventDate || !startTime || !endTime || !blockType) {
        document.getElementById('conflict-warning').classList.add('hidden');
        return;
    }
    
    // If recurring, require end date
    if (isRecurring && !recurringEndDate) {
        document.getElementById('conflict-warning').classList.add('hidden');
        return;
    }
    
    // Show loading state
    const warningDiv = document.getElementById('conflict-warning');
    warningDiv.classList.remove('hidden');
    warningDiv.innerHTML = `
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-spinner fa-spin text-blue-600 mr-3"></i>
                <span class="text-sm text-blue-800">Checking for conflicts...</span>
            </div>
        </div>
    `;
    
    // Make AJAX request
    fetch('{{ route("admin.parochial-activities.check-conflicts") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            event_date: eventDate,
            start_time: startTime,
            end_time: endTime,
            block_type: blockType,
            is_recurring: isRecurring,
            recurring_pattern_type: recurringPatternType,
            recurring_end_date: recurringEndDate
        })
    })
    .then(response => response.json())
    .then(data => {
        hasBlockingConflicts = data.has_errors && !skipConflictingDates;
        conflictingDatesArray = data.conflicting_dates || [];
        
        if (!data.has_conflicts) {
            // No conflicts
            warningDiv.innerHTML = `
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-3 text-lg"></i>
                        <div>
                            <p class="text-sm font-medium text-green-800">No conflicts detected</p>
                            <p class="text-xs text-green-700 mt-1">This time slot is available</p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            // Has conflicts
            let conflictHTML = '';
            const errorCount = data.conflicts.filter(c => c.severity === 'error').length;
            const warningCount = data.conflicts.filter(c => c.severity === 'warning').length;
            const infoCount = data.conflicts.filter(c => c.severity === 'info').length;
            
            if (errorCount > 0) {
                const isRecurringConflict = data.conflicts.some(c => c.severity === 'error' && c.occurrence);
                const hasParochialConflicts = data.conflicts.some(c => c.severity === 'error' && c.type === 'parochial_activity');
                const hasMinistryConflicts = data.conflicts.some(c => c.severity === 'error' && c.type === 'ministry_activity');
                
                let errorTitle = '⚠️ Cannot Create Activity - Conflicts Found';
                let errorMessage = '';
                
                if (hasParochialConflicts || hasMinistryConflicts) {
                    errorMessage = `There are ${errorCount} conflicting activities. You cannot create overlapping parochial activities or ministry activities.`;
                }
                
                conflictHTML += `
                    <div class="p-4 bg-red-50 border border-red-300 rounded-lg mb-3">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-3 text-lg mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-red-800">${errorTitle}</p>
                                <p class="text-xs text-red-700 mt-1">${errorMessage}</p>
                                ${isRecurringConflict ? '<p class="text-xs text-red-700 mt-2 font-semibold">💡 Tip: Check the specific dates below to see which occurrences have conflicts.</p>' : ''}
                                <ul class="mt-2 space-y-2">
                `;
                
                data.conflicts.filter(c => c.severity === 'error').forEach(conflict => {
                    const occurrenceInfo = conflict.occurrence ? `<br><span class="text-red-700 font-semibold">📅 Date: ${conflict.occurrence}</span>` : '';
                    
                    if (conflict.type === 'parochial_activity') {
                        const recurringBadge = conflict.is_recurring ? '<span class="ml-1 px-1.5 py-0.5 bg-purple-200 text-purple-800 rounded text-xs">Recurring</span>' : '';
                        conflictHTML += `
                            <li class="text-xs text-red-800 bg-red-100 p-2 rounded">
                                <strong>Parochial Activity:</strong> ${conflict.title} ${recurringBadge}
                                <br>${conflict.start_time} - ${conflict.end_time} (${conflict.block_type === 'full_day' ? 'Full Day Block' : 'Time Slot'})
                                ${occurrenceInfo}
                            </li>
                        `;
                    } else if (conflict.type === 'ministry_activity') {
                        conflictHTML += `
                            <li class="text-xs text-red-800 bg-red-100 p-2 rounded">
                                <strong>Ministry Activity:</strong> ${conflict.title} ${conflict.ministry ? '(' + conflict.ministry + ')' : ''}
                                <br>${conflict.is_all_day ? 'All Day' : conflict.start_time + ' - ' + conflict.end_time}
                                ${occurrenceInfo}
                            </li>
                        `;
                    }
                });
                
                conflictHTML += `
                                </ul>
                                ${isRecurringConflict && data.available_occurrences > 0 ? `
                                    <div class="mt-4 p-3 bg-blue-50 border border-blue-300 rounded">
                                        <p class="text-xs font-semibold text-blue-800 mb-2">
                                            <i class="fas fa-lightbulb mr-1"></i>Smart Solution Available
                                        </p>
                                        <p class="text-xs text-blue-700 mb-3">
                                            You can create this recurring activity for <strong>${data.available_occurrences} out of ${data.total_occurrences} occurrences</strong> by skipping the conflicting dates.
                                        </p>
                                        <label class="flex items-start cursor-pointer">
                                            <input type="checkbox" id="skip-conflicts-checkbox" class="mt-1 h-4 w-4 text-[#0d5c2f] focus:ring-[#0d5c2f] border-gray-300 rounded">
                                            <span class="ml-2 text-xs text-blue-800">
                                                <strong>Skip conflicting dates and create activity for available dates only</strong>
                                                <br><span class="text-blue-600">The activity will be created for all dates except those with existing bookings.</span>
                                            </span>
                                        </label>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }
            
            if (warningCount > 0) {
                conflictHTML += `
                    <div class="p-4 bg-yellow-50 border border-yellow-300 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-yellow-600 mr-3 text-lg mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-yellow-800">⚠️ Warning - Other Activities Found</p>
                                <p class="text-xs text-yellow-700 mt-1">There are ${warningCount} other activity/activities scheduled at this time. You can still create this activity, but be aware of potential overlaps.</p>
                                <ul class="mt-2 space-y-2">
                `;
                
                data.conflicts.filter(c => c.severity === 'warning').forEach(conflict => {
                    if (conflict.type === 'parochial_activity') {
                        const recurringBadge = conflict.is_recurring ? '<span class="ml-1 px-1.5 py-0.5 bg-purple-200 text-purple-800 rounded text-xs">Recurring</span>' : '';
                        const occurrenceInfo = conflict.occurrence ? `<br><span class="text-yellow-700 font-semibold">📅 Date: ${conflict.occurrence}</span>` : '';
                        conflictHTML += `
                            <li class="text-xs text-yellow-800 bg-yellow-100 p-2 rounded">
                                <strong>Parochial Activity:</strong> ${conflict.title} ${recurringBadge}
                                <br>${conflict.start_time} - ${conflict.end_time} (${conflict.block_type === 'full_day' ? 'Full Day Block' : 'Time Slot'})
                                ${occurrenceInfo}
                            </li>
                        `;
                    } else if (conflict.type === 'ministry_activity') {
                        const occurrenceInfo = conflict.occurrence ? `<br><span class="text-yellow-700 font-semibold">📅 Date: ${conflict.occurrence}</span>` : '';
                        conflictHTML += `
                            <li class="text-xs text-yellow-800 bg-yellow-100 p-2 rounded">
                                <strong>Ministry Activity:</strong> ${conflict.title} ${conflict.ministry ? '(' + conflict.ministry + ')' : ''}
                                <br>${conflict.is_all_day ? 'All Day' : conflict.start_time + ' - ' + conflict.end_time}
                                ${occurrenceInfo}
                            </li>
                        `;
                    }
                });
                
                conflictHTML += `
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Info level - existing bookings (informational only, not blocking)
            if (infoCount > 0) {
                conflictHTML += `
                    <div class="p-4 bg-blue-50 border border-blue-300 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mr-3 text-lg mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-blue-800">ℹ️ Existing Bookings Detected</p>
                                <p class="text-xs text-blue-700 mt-1">There are ${infoCount} existing booking(s) on the selected date(s). You can still create this activity. Future bookings will be blocked, but existing bookings will remain valid.</p>
                                <ul class="mt-2 space-y-2">
                `;
                
                data.conflicts.filter(c => c.severity === 'info').forEach(conflict => {
                    const occurrenceInfo = conflict.occurrence ? `<br><span class="text-blue-700 font-semibold">📅 Date: ${conflict.occurrence}</span>` : '';
                    conflictHTML += `
                        <li class="text-xs text-blue-800 bg-blue-100 p-2 rounded">
                            <strong>${conflict.title}</strong> - ${conflict.time} (${conflict.duration} min)
                            <br><span class="text-blue-600">Booked by: ${conflict.user}</span>
                            ${occurrenceInfo}
                        </li>
                    `;
                });
                
                conflictHTML += `
                                </ul>
                                <p class="text-xs text-blue-700 mt-3 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>These existing bookings will NOT be affected. You can proceed with creating the activity.
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            warningDiv.innerHTML = conflictHTML;
            
            // Attach event listener to skip conflicts checkbox
            const skipCheckbox = document.getElementById('skip-conflicts-checkbox');
            if (skipCheckbox) {
                skipCheckbox.addEventListener('change', function() {
                    skipConflictingDates = this.checked;
                    hasBlockingConflicts = data.has_errors && !skipConflictingDates;
                    
                    const container = document.getElementById('excluded-dates-container');
                    
                    if (this.checked && conflictingDatesArray.length > 0) {
                        // Clear container
                        container.innerHTML = '';
                        
                        // Add hidden inputs for each excluded date
                        conflictingDatesArray.forEach(date => {
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'excluded_dates[]';
                            hiddenInput.value = date;
                            hiddenInput.className = 'excluded-date-input';
                            container.appendChild(hiddenInput);
                        });
                        
                        console.log('Added excluded dates:', conflictingDatesArray);
                        
                        // Show success message
                        const existingMsg = skipCheckbox.closest('.p-3')?.querySelector('.bg-green-50');
                        if (!existingMsg) {
                            const successMsg = document.createElement('div');
                            successMsg.className = 'mt-3 p-2 bg-green-50 border border-green-300 rounded text-xs text-green-800';
                            successMsg.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Ready to create! The activity will skip ' + conflictingDatesArray.length + ' conflicting date(s).';
                            skipCheckbox.closest('.p-3').appendChild(successMsg);
                        }
                    } else {
                        // Clear container
                        container.innerHTML = '';
                        console.log('Cleared excluded dates');
                        
                        // Remove success message
                        skipCheckbox.closest('.p-3')?.querySelector('.bg-green-50')?.remove();
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Error checking conflicts:', error);
        warningDiv.innerHTML = `
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                    <span class="text-sm text-red-800">Error checking conflicts. Please try again.</span>
                </div>
            </div>
        `;
    });
}

// Attach event listeners for real-time checking
document.addEventListener('DOMContentLoaded', function() {
    const eventDate = document.getElementById('event_date');
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    const blockType = document.getElementById('block_type');
    const isRecurring = document.getElementById('is_recurring');
    const recurringPatternType = document.getElementById('recurring_pattern_type');
    const recurringEndDate = document.getElementById('recurring_end_date');
    
    [eventDate, startTime, endTime, blockType, isRecurring, recurringPatternType, recurringEndDate].forEach(field => {
        if (field) {
            field.addEventListener('change', function() {
                // Debounce the conflict check
                clearTimeout(conflictCheckTimeout);
                conflictCheckTimeout = setTimeout(checkConflicts, 500);
            });
        }
    });
    
    // Prevent form submission if there are blocking conflicts
    document.querySelector('form').addEventListener('submit', function(e) {
        if (hasBlockingConflicts) {
            e.preventDefault();
            alert('Cannot create activity: There are conflicting parochial activities or ministry activities.\n\nOptions:\n1. Check the "Skip conflicting dates" option to create the activity for available dates only\n2. Choose a different date/time\n3. Coordinate with other activities to avoid conflicts');
            return false;
        }
        
        // Show confirmation if skipping dates
        if (skipConflictingDates && conflictingDatesArray.length > 0) {
            const confirmed = confirm(`You are about to create a recurring activity that will skip ${conflictingDatesArray.length} date(s) with existing bookings.\n\nThe activity will be created for the remaining available dates.\n\nContinue?`);
            if (!confirmed) {
                e.preventDefault();
                return false;
            }
        }
    });
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