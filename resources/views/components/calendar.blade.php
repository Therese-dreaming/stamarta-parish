@props(['activeBookings', 'selectedDate', 'service', 'parochialActivities', 'ministryActivities'])

<div class="calendar-container bg-white rounded-lg shadow-sm border border-gray-200 p-8">
    <div class="calendar-header flex items-center justify-between mb-8">
        <button type="button" id="prevMonth" class="p-3 text-gray-600 hover:text-[#0d5c2f] hover:bg-gray-100 rounded-lg transition-colors">
            <i class="fas fa-chevron-left text-lg"></i>
        </button>
        <h3 id="currentMonth" class="text-xl font-semibold text-gray-900"></h3>
        <button type="button" id="nextMonth" class="p-3 text-gray-600 hover:text-[#0d5c2f] hover:bg-gray-100 rounded-lg transition-colors">
            <i class="fas fa-chevron-right text-lg"></i>
        </button>
    </div>

    <div class="calendar-grid">
        <!-- Day headers -->
        <div class="grid grid-cols-7 gap-2 mb-4">
            <div class="text-center text-sm font-medium text-gray-500 py-3">Sun</div>
            <div class="text-center text-sm font-medium text-gray-500 py-3">Mon</div>
            <div class="text-center text-sm font-medium text-gray-500 py-3">Tue</div>
            <div class="text-center text-sm font-medium text-gray-500 py-3">Wed</div>
            <div class="text-center text-sm font-medium text-gray-500 py-3">Thu</div>
            <div class="text-center text-sm font-medium text-gray-500 py-3">Fri</div>
            <div class="text-center text-sm font-medium text-gray-500 py-3">Sat</div>
        </div>

        <!-- Calendar days -->
        <div id="calendarDays" class="grid grid-cols-7 gap-2">
            <!-- Days will be populated by JavaScript -->
        </div>
    </div>

    <!-- Legend -->
    <div class="calendar-legend mt-8 pt-6 border-t border-gray-200">
        <h4 class="text-sm font-medium text-gray-700 mb-4">Availability Legend</h4>
        <div class="flex flex-wrap gap-6 text-sm">
            <div class="flex items-center">
                <div class="w-5 h-5 bg-gray-200 rounded mr-3"></div>
                <span class="text-gray-600">Not Available</span>
            </div>
            <div class="flex items-center">
                <div class="w-5 h-5 bg-white border-2 border-gray-300 rounded mr-3"></div>
                <span class="text-gray-600">Available</span>
            </div>
            <div class="flex items-center">
                <div class="w-5 h-5 bg-red-200 border-2 border-red-400 rounded mr-3"></div>
                <span class="text-gray-600">Fully Booked</span>
            </div>
            <div class="flex items-center">
                <div class="w-5 h-5 bg-blue-200 border-2 border-blue-400 rounded mr-3"></div>
                <span class="text-gray-600">Full Day Activity</span>
            </div>
            <div class="flex items-center">
                <div class="w-5 h-5 bg-purple-200 border-2 border-purple-400 rounded mr-3"></div>
                <span class="text-gray-600">Ministry Activity</span>
            </div>
        </div>
        <div class="mt-4 text-xs text-gray-500">
            <p><i class="fas fa-info-circle mr-1"></i>Time slots may be blocked by existing bookings, ministry activities, or parochial activities. Check the information banner below the calendar for details.</p>
        </div>
    </div>
</div>

<script>
class Calendar {
    constructor(container, service, activeBookings, selectedDate, parochialActivities, ministryActivities) {
        this.container = container;
        this.service = service;
        this.activeBookings = activeBookings || [];
        this.selectedDate = selectedDate;
        this.parochialActivities = parochialActivities || [];
        this.ministryActivities = ministryActivities || [];
        
        this.currentMonth = new Date();
        if (this.selectedDate) {
            this.currentMonth = new Date(this.selectedDate);
        }
        
        this.init();
    }

    init() {
        
        // Ensure selected date is in local timezone format
        if (this.selectedDate) {
            const [year, month, day] = this.selectedDate.split('-').map(Number);
            const selectedDate = new Date(year, month - 1, day);
            const localYear = selectedDate.getFullYear();
            const localMonth = String(selectedDate.getMonth() + 1).padStart(2, '0');
            const localDay = String(selectedDate.getDate()).padStart(2, '0');
            this.selectedDate = `${localYear}-${localMonth}-${localDay}`;
        }
        
        this.renderCalendar();
        this.attachEventListeners();
    }

    renderCalendar() {
        const year = this.currentMonth.getFullYear();
        const month = this.currentMonth.getMonth();
        
        // Update header
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December'];
        const currentMonthElement = document.getElementById('currentMonth');
        if (currentMonthElement) {
            currentMonthElement.textContent = `${monthNames[month]} ${year}`;
        }

        // Get first day of month and number of days
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());

        const daysContainer = document.getElementById('calendarDays');
        if (!daysContainer) return;
        
        daysContainer.innerHTML = '';

        // Generate calendar days
        for (let i = 0; i < 42; i++) {
            const currentDate = new Date(startDate);
            currentDate.setDate(startDate.getDate() + i);
            
            const dayElement = this.createDayElement(currentDate, year, month);
            daysContainer.appendChild(dayElement);
        }
    }

    createDayElement(date, targetYear, targetMonth) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'calendar-day text-center py-4 px-2 cursor-pointer transition-colors text-lg font-medium';
        
        const dayNumber = date.getDate();
        const isCurrentMonth = date.getMonth() === targetMonth;
        
        // Create date string in local timezone to avoid timezone issues
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const dateString = `${year}-${month}-${day}`;
        
        // Set initial classes based on basic availability
        let availability = null;
        
        if (!isCurrentMonth) {
            dayDiv.className += ' text-gray-300 cursor-not-allowed';
        } else {
            // Check if date is in the past
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const selectedDate = new Date(year, date.getMonth(), date.getDate());
            if (selectedDate < today) {
                dayDiv.className += ' text-gray-400 cursor-not-allowed bg-gray-100';
            } else {
                // Check if service is offered on this day
                const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                const dayOfWeek = dayNames[selectedDate.getDay()];
                const serviceSchedules = this.service.schedules || {};
                
                if (!serviceSchedules[dayOfWeek] || serviceSchedules[dayOfWeek].length === 0) {
                    dayDiv.className += ' text-gray-400 cursor-not-allowed bg-gray-100';
                } else {
                    // Check availability for this date
                    availability = this.getDateAvailability(dateString);
                    this.updateDayClasses(dayDiv, availability);
                    
                    // Add tooltip
                    if (availability.available_slots !== undefined) {
                        if (availability.status === 'available-with-notes') {
                            dayDiv.title = `${availability.available_slots} of ${availability.total_slots} slots available. ${availability.note}`;
                        } else {
                            dayDiv.title = `${availability.available_slots} of ${availability.total_slots} slots available`;
                        }
                    }
                }
            }
        }
        
        // Add click handler for available dates (including those with ministry activity notes)
        // But don't allow clicking on dates completely blocked by ministry activities
        if (availability && (availability.status === 'available' || availability.status === 'available-with-notes')) {
            dayDiv.addEventListener('click', () => this.selectDate(dateString));
        }

        // Highlight selected date
        if (this.selectedDate === dateString) {
            dayDiv.className += ' bg-[#0d5c2f] text-white border-[#0d5c2f]';
        }

        dayDiv.textContent = dayNumber;
        dayDiv.dataset.date = dateString;
        
        return dayDiv;
    }

    getDateAvailability(dateString) {
        // Parse date string properly in local timezone
        const [year, month, day] = dateString.split('-').map(Number);
        const date = new Date(year, month - 1, day); // month is 0-indexed in JavaScript
        
        
        // Check if date is blocked by parochial activities
        if (this.isDateBlockedByParochialActivity(dateString)) {
            
            return {
                status: 'parochial-activity',
                reason: 'Date blocked by parochial activity'
            };
        }

        // Check if date has ministry activities (but don't block entire day for time-specific ones)
        const hasMinistryActivities = this.hasMinistryActivitiesOnDate(dateString);
        
        // Check if date is blocked by existing bookings (all-day events or full-day coverage)
        if (this.isDateBlockedByExistingBookings(dateString)) {
            
            return {
                status: 'fully-booked',
                reason: 'Date blocked by existing bookings'
            };
        }
        
        const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        const dayOfWeek = dayNames[date.getDay()];
        const serviceSchedules = this.service.schedules || {};
        const allTimeSlots = serviceSchedules[dayOfWeek] ?? [];
        
        
        if (allTimeSlots.length === 0) {
            
            return {
                status: 'not-available',
                reason: 'Service not offered on this day'
            };
        }

        // Calculate total slots for the day
        const totalSlots = allTimeSlots.length * this.service.max_slots;
        
        // Get booked slots for the day (only count bookings for THIS service)
        const bookedSlots = this.activeBookings.filter(booking => 
            booking.service_date === dateString && 
            booking.service_id === this.service.id &&
            ['pending', 'acknowledged', 'payment_hold', 'approved'].includes(booking.status)
        ).length;

        const availableSlots = totalSlots - bookedSlots;
        
        
        if (availableSlots <= 0) {
            
            return {
                status: 'fully-booked',
                total_slots: totalSlots,
                available_slots: 0,
                booked_slots: bookedSlots
            };
        } else {
            // Check if ALL time slots are blocked by either ministry activities OR existing bookings
            const allTimeSlotsBlocked = this.areAllTimeSlotsBlocked(dateString, allTimeSlots);
            
            if (allTimeSlotsBlocked) {
                // Determine the blocking reason
                if (hasMinistryActivities) {
                    
                    return {
                        status: 'ministry-activity',
                        reason: 'All time slots blocked by ministry activities'
                    };
                } else if (this.hasParochialActivitiesOnDate(dateString)) {
                    
                    return {
                        status: 'parochial-activity',
                        reason: 'All time slots blocked by parochial activities'
                    };
                } else {
                    
                    return {
                        status: 'fully-booked',
                        reason: 'All time slots blocked by existing bookings'
                    };
                }
            } else {
                // Some time slots are available
                if (hasMinistryActivities) {
                    
                    return {
                        status: 'available-with-notes',
                        total_slots: totalSlots,
                        available_slots: availableSlots,
                        booked_slots: bookedSlots,
                        note: 'Some time slots may be blocked by ministry activities'
                    };
                } else {
                    
                    return {
                        status: 'available',
                        total_slots: totalSlots,
                        available_slots: availableSlots,
                        booked_slots: bookedSlots
                    };
                }
            }
        }
    }

    isDateBlockedByParochialActivity(dateString) {
        if (!this.parochialActivities || this.parochialActivities.length === 0) {
            return false;
        }

        const [year, month, day] = dateString.split('-').map(Number);
        const date = new Date(year, month - 1, day);
        const dayOfWeek = date.getDay();

        return this.parochialActivities.some(activity => {
            // Only full_day activities block the entire service
            if (activity.block_type !== 'full_day') {
                return false;
            }

            if (activity.is_recurring) {
                // For recurring activities, check if the day of week matches
                const recurringPattern = activity.recurring_pattern || {};
                if (recurringPattern.type === 'weekly') {
                    return recurringPattern.day_of_week === dayOfWeek;
                }
                // Add more recurring pattern types as needed
            } else {
                // For one-time activities, check if the date matches
                const activityDate = new Date(activity.event_date);
                return activityDate.toDateString() === date.toDateString();
            }
            return false;
        });
    }

    isDateBlockedByMinistryActivity(dateString) {
        if (!this.ministryActivities || this.ministryActivities.length === 0) {
            return false;
        }

        const [year, month, day] = dateString.split('-').map(Number);
        const date = new Date(year, month - 1, day);
        const dateStringFormatted = this.formatDateToLocal(date); // Use our local timezone method instead of toISOString()

        return this.ministryActivities.some(activity => {
            // Parse dates without timezone conversion to avoid off-by-one errors
            const activityStart = new Date(activity.start_at);
            const activityStartDate = this.formatDateToLocal(activityStart);
            
            // Check if this is an all-day event that blocks the entire day
            if (activity.is_all_day) {
                // If activity starts on this date, it blocks the entire day
                if (activityStartDate === dateStringFormatted) {
                    return true;
                }
                
                // If activity has an end date, check if this date falls within the range
                if (activity.end_at) {
                    const activityEnd = new Date(activity.end_at);
                    const activityEndDate = this.formatDateToLocal(activityEnd);
                    
                    if (dateStringFormatted >= activityStartDate && dateStringFormatted <= activityEndDate) {
                        return true;
                    }
                }
            } else {
                // For time-specific activities, we don't block entire days
                // Instead, we'll handle time slot conflicts at the time slot level
                // This allows for more granular blocking (e.g., 10AM-2PM blocks 10AM, 11AM, 1PM, but not 5PM)
                return false;
            }
            
            return false;
        });
    }

    // Helper method to check if all time slots are blocked by either ministry activities OR existing bookings OR parochial activities
    areAllTimeSlotsBlocked(dateString, timeSlots) {
        
        // Check each time slot to see if it's blocked by ministry activities OR existing bookings OR parochial activities
        for (const timeSlot of timeSlots) {
            let isBlocked = false;
            
            // Check if blocked by parochial activities
            for (const activity of this.parochialActivities || []) {
                const parochialConflict = this.parochialActivityConflictsWithTimeSlot(activity, dateString, timeSlot);
                if (parochialConflict) {
                    isBlocked = true;
                    break;
                }
            }
            
            // Check if blocked by ministry activities
            if (!isBlocked) {
                for (const activity of this.ministryActivities || []) {
                    const ministryConflict = this.ministryActivityConflictsWithTimeSlot(activity, dateString, timeSlot);
                    if (ministryConflict) {
                        isBlocked = true;
                        break;
                    }
                }
            }
            
            // Check if blocked by existing bookings
            if (!isBlocked) {
                for (const booking of this.activeBookings || []) {
                    const bookingConflict = this.bookingConflictsWithTimeSlot(booking, dateString, timeSlot);
                    if (bookingConflict) {
                        isBlocked = true;
                        break;
                    }
                }
            }
            
            
            // If any time slot is available, return false (not all blocked)
            if (!isBlocked) {
                return false;
            }
        }
        
        // All time slots are blocked
        return true;
    }

    // Helper method to check if an existing booking conflicts with a specific time slot
    bookingConflictsWithTimeSlot(booking, dateString, timeSlot) {
        
        // Convert booking service_date to local date string format
        const bookingDate = new Date(booking.service_date);
        const bookingDateString = this.formatDateToLocal(bookingDate);
        
        
        if (bookingDateString !== dateString) {
            return false;
        }
        
        if (!['pending', 'acknowledged', 'payment_hold', 'approved'].includes(booking.status)) {
            return false;
        }
        
        // Only check for conflicts with OTHER services (different service_id)
        // Same service bookings are handled by checking max_slots
        if (booking.service_id === this.service.id) {
            return false;
        }
        
        // Parse the time slot to get the hour (handle "10:00 AM" format)
        const timeSlotHour = this.parseTimeSlotToHour(timeSlot);
        const timeSlotMinute = parseInt(timeSlot.split(' ')[0].split(':')[1]);
        const timeSlotTime = timeSlotHour * 60 + timeSlotMinute; // Convert to minutes
        
        // Parse the booking time - handle "10:00 AM" format
        let bookingHour, bookingMinute;
        if (booking.service_time.includes(' ')) {
            // Format: "10:00 AM"
            const [time, period] = booking.service_time.split(' ');
            const [hour, minute] = time.split(':').map(Number);
            
            if (period === 'AM') {
                bookingHour = hour === 12 ? 0 : hour;
            } else if (period === 'PM') {
                bookingHour = hour === 12 ? 12 : hour + 12;
            }
            bookingMinute = minute;
        } else {
            // Format: "10:00" (24-hour)
            [bookingHour, bookingMinute] = booking.service_time.split(':').map(Number);
        }
        
        const bookingStartTime = bookingHour * 60 + bookingMinute; // Convert to minutes
        
        // Calculate booking end time based on service duration
        const serviceDuration = booking.service?.duration_minutes || 60; // Default to 60 minutes
        const bookingEndTime = bookingStartTime + serviceDuration;
        
        
        // Check if the time slot conflicts with the booking
        // A time slot conflicts if it starts during an existing booking
        if (timeSlotTime >= bookingStartTime && timeSlotTime < bookingEndTime) {
            return true;
        }
        
        return false;
    }

    // Helper method to check if a ministry activity conflicts with a specific time slot
    ministryActivityConflictsWithTimeSlot(activity, dateString, timeSlot) {
        const [year, month, day] = dateString.split('-').map(Number);
        const date = new Date(year, month - 1, day);
        const dateStringFormatted = this.formatDateToLocal(date);
        
        const activityStart = new Date(activity.start_at);
        const activityStartDate = this.formatDateToLocal(activityStart);
        
        // Check if this is an all-day event
        if (activity.is_all_day) {
            if (activityStartDate === dateStringFormatted) {
                return true;
            }
            
            // If activity has an end date, check if this date falls within the range
            if (activity.end_at) {
                const activityEnd = new Date(activity.end_at);
                const activityEndDate = this.formatDateToLocal(activityEnd);
                
                if (dateStringFormatted >= activityStartDate && dateStringFormatted <= activityEndDate) {
                    return true;
                }
            }
        } else {
            // For time-specific activities, check if the time slot conflicts
            if (activityStartDate === dateStringFormatted) {
                // Parse the time slot to get the hour (handle "10:00 AM" format)
                const timeSlotHour = this.parseTimeSlotToHour(timeSlot);
                const activityStartHour = activityStart.getHours();
                
                if (activity.end_at) {
                    const activityEnd = new Date(activity.end_at);
                    const activityEndHour = activityEnd.getHours();
                    
                    
                    // Check if the time slot falls within the activity's time range
                    if (timeSlotHour >= activityStartHour && timeSlotHour < activityEndHour) {
                        return true;
                    }
                } else {
                    // If no end time, check if it's exactly at the start time
                    if (timeSlotHour === activityStartHour) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }

    // Helper method to check if a parochial activity conflicts with a specific time slot
    parochialActivityConflictsWithTimeSlot(activity, dateString, timeSlot) {
        const [year, month, day] = dateString.split('-').map(Number);
        const date = new Date(year, month - 1, day);
        const dayOfWeek = date.getDay();
        
        // Only full_day activities block time slots
        if (activity.block_type !== 'full_day') {
            return false;
        }
        
        if (activity.is_recurring) {
            // For recurring activities, check if the day of week matches
            const recurringPattern = activity.recurring_pattern || {};
            if (recurringPattern.type === 'weekly') {
                return recurringPattern.day_of_week === dayOfWeek;
            }
            // Add more recurring pattern types as needed
        } else {
            // For one-time activities, check if the date matches
            const activityDate = new Date(activity.event_date);
            return activityDate.toDateString() === date.toDateString();
        }
        
        return false;
    }

    // Helper method to parse time slot format "10:00 AM" to hour number
    parseTimeSlotToHour(timeSlot) {
        // Handle formats like "10:00 AM", "2:00 PM", "5:00 PM"
        const [time, period] = timeSlot.split(' ');
        const [hour, minute] = time.split(':').map(Number);
        
        if (period === 'AM') {
            return hour === 12 ? 0 : hour; // 12:00 AM = 0, 1:00 AM = 1, etc.
        } else if (period === 'PM') {
            return hour === 12 ? 12 : hour + 12; // 12:00 PM = 12, 1:00 PM = 13, etc.
        }
        
        // Fallback for 24-hour format
        return hour;
    }

    // Helper method to check if there are ministry activities on a specific date
    hasMinistryActivitiesOnDate(dateString) {
        if (!this.ministryActivities || this.ministryActivities.length === 0) {
            return false;
        }

        const [year, month, day] = dateString.split('-').map(Number);
        const date = new Date(year, month - 1, day);
        const dateStringFormatted = this.formatDateToLocal(date);

        return this.ministryActivities.some(activity => {
            const activityStart = new Date(activity.start_at);
            const activityStartDate = this.formatDateToLocal(activityStart);
            
            // Check if this is an all-day event on this date
            if (activity.is_all_day) {
                if (activityStartDate === dateStringFormatted) {
                    return true;
                }
                
                // If activity has an end date, check if this date falls within the range
                if (activity.end_at) {
                    const activityEnd = new Date(activity.end_at);
                    const activityEndDate = this.formatDateToLocal(activityEnd);
                    
                    if (dateStringFormatted >= activityStartDate && dateStringFormatted <= activityEndDate) {
                        return true;
                    }
                }
            } else {
                // For time-specific activities, check if they occur on this date
                if (activityStartDate === dateStringFormatted) {
                    return true;
                }
                
                // If activity has an end date, check if it spans to this date
                if (activity.end_at) {
                    const activityEnd = new Date(activity.end_at);
                    const activityEndDate = this.formatDateToLocal(activityEnd);
                    
                    if (dateStringFormatted >= activityStartDate && dateStringFormatted <= activityEndDate) {
                        return true;
                    }
                }
            }
            
            return false;
        });
    }

    // Helper method to check if there are parochial activities on a specific date
    hasParochialActivitiesOnDate(dateString) {
        if (!this.parochialActivities || this.parochialActivities.length === 0) {
            return false;
        }

        const [year, month, day] = dateString.split('-').map(Number);
        const date = new Date(year, month - 1, day);
        const dayOfWeek = date.getDay();

        return this.parochialActivities.some(activity => {
            // Only full_day activities block dates
            if (activity.block_type !== 'full_day') {
                return false;
            }

            if (activity.is_recurring) {
                // For recurring activities, check if the day of week matches
                const recurringPattern = activity.recurring_pattern || {};
                if (recurringPattern.type === 'weekly') {
                    return recurringPattern.day_of_week === dayOfWeek;
                }
            } else {
                // For one-time activities, check if the date matches
                const activityDate = new Date(activity.event_date);
                return activityDate.toDateString() === date.toDateString();
            }
            
            return false;
        });
    }

    // Helper method to format dates to local timezone YYYY-MM-DD format
    formatDateToLocal(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Helper method to get month name
    getMonthName(monthIndex) {
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December'];
        return monthNames[monthIndex];
    }

    isDateBlockedByExistingBookings(dateString) {
        // We don't block entire dates for existing bookings
        // Instead, we show conflicts at the time slot level
        return false;
    }

    updateDayClasses(dayElement, availability) {
        const baseClasses = 'calendar-day text-center py-4 px-2 cursor-pointer transition-colors text-lg font-medium';
        
        if (availability.status === 'not-available') {
            dayElement.className = baseClasses + ' text-gray-400 cursor-not-allowed bg-gray-100';
        } else if (availability.status === 'parochial-activity') {
            dayElement.className = baseClasses + ' text-white bg-blue-200 border-2 border-blue-400 cursor-not-allowed';
        } else if (availability.status === 'ministry-activity') {
            dayElement.className = baseClasses + ' text-white bg-purple-200 border-2 border-purple-400 cursor-not-allowed';
        } else if (availability.status === 'fully-booked') {
            dayElement.className = baseClasses + ' text-white bg-red-200 border-2 border-red-400 cursor-not-allowed';
        } else if (availability.status === 'available') {
            dayElement.className = baseClasses + ' text-gray-700 bg-white border-2 border-gray-300 hover:bg-gray-50 hover:border-[#0d5c2f]';
        } else if (availability.status === 'available-with-notes') {
            dayElement.className = baseClasses + ' text-gray-700 bg-white border-2 border-gray-300 hover:bg-gray-50 hover:border-[#0d5c2f]';
            dayElement.title = `${availability.available_slots} of ${availability.total_slots} slots available. ${availability.note}`;
        }
    }

    selectDate(dateString) {
        
        // Remove previous selection
        const previousSelected = document.querySelector('.calendar-day.bg-\\[\\#0d5c2f\\]');
        if (previousSelected) {
            const availability = this.getDateAvailability(previousSelected.dataset.date);
            this.updateDayClasses(previousSelected, availability);
        }

        // Select new date
        const newSelected = document.querySelector(`[data-date="${dateString}"]`);
        if (newSelected) {
            newSelected.className = 'calendar-day text-center py-4 px-2 cursor-pointer transition-colors text-lg font-medium bg-[#0d5c2f] text-white border-[#0d5c2f]';
        }

        this.selectedDate = dateString;
        
        // Update hidden input
        const dateInput = document.querySelector('input[name="selected_date"]');
        if (dateInput) {
            dateInput.value = dateString;
        }
        
        // Load time slots via AJAX
        this.loadTimeSlots(dateString);
    }


    loadTimeSlots(dateString) {
        const serviceId = this.service.id;
        const url = `/booking/time-slots/${serviceId}?date=${dateString}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                this.updateTimeSlotsDisplay(data);
            })
            .catch(error => {
                console.error('Error loading time slots:', error);
            });
    }

    updateTimeSlotsDisplay(data) {
        const timeSlotsContainer = document.getElementById('time-slots-container');
        if (!timeSlotsContainer) return;

        if (data.error) {
            timeSlotsContainer.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Error Loading Time Slots</h3>
                    <p class="text-gray-600">${data.error}</p>
                </div>
            `;
            return;
        }

        if (data.timeSlots.length === 0) {
            timeSlotsContainer.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-clock text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Time Slots Available</h3>
                    <p class="text-gray-600">This service is not offered on this day.</p>
                </div>
            `;
            return;
        }

        let bannerHTML = '';
        if (Array.isArray(data.blockingActivities) && data.blockingActivities.length > 0) {
            bannerHTML += '<div class="mb-4 p-4 border border-yellow-300 bg-yellow-50 rounded">';
            bannerHTML += '<div class="font-semibold text-yellow-800 mb-2"><i class="fas fa-info-circle mr-2"></i>This date has blocking activities:</div>';
            bannerHTML += '<ul class="list-disc pl-5 text-sm text-yellow-900">';
            data.blockingActivities.forEach(act => {
                let displayText = '';
                
                if (act.type === 'existing_booking') {
                    const startTime = act.start.split(' ')[1]; // Extract time part
                    const endTime = act.end.split(' ')[1]; // Extract time part
                    displayText = `Existing Booking — ${startTime} - ${endTime}`;
                } else if (act.type === 'ministry') {
                    let range = '';
                    if (act.is_all_day) {
                        range = 'All Day';
                    } else {
                        range = act.end ? `${act.start} - ${act.end}` : act.start;
                    }
                    displayText = `Ministry Activity — ${range}`;
                } else if (act.type === 'parochial') {
                    let range = act.end ? `${act.start} - ${act.end}` : act.start;
                    displayText = `Parochial Activity — ${range}`;
                }
                
                bannerHTML += `<li>${displayText}</li>`;
            });
            bannerHTML += '</ul></div>';
        }

        let timeSlotsHTML = bannerHTML + '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">';
        
        data.timeSlots.forEach(slot => {
            const isBlocked = !!slot.blocked;
            const isAvailable = !isBlocked && slot.available_slots > 0;
            const baseClasses = 'time-slot-btn p-4 text-center rounded-lg border transition-colors';
            const cls = isBlocked
                ? 'border-yellow-300 bg-yellow-50 text-gray-700 cursor-not-allowed'
                : (isAvailable ? 'border-gray-300 hover:border-[#0d5c2f] hover:bg-gray-50' : 'border-red-300 bg-red-50 text-gray-500 cursor-not-allowed');
            const title = isBlocked ? (slot.reason || 'Blocked by activity') : (isAvailable ? '' : 'Fully booked');

            let slotContent = '';
            if (isBlocked) {
                slotContent = `
                    <div class="font-semibold text-yellow-800">${slot.time}</div>
                    <div class="text-sm text-yellow-700">
                        <i class="fas fa-ban mr-1"></i>${slot.reason}
                    </div>
                `;
            } else if (isAvailable) {
                slotContent = `
                    <div class="font-semibold">${slot.time}</div>
                    <div class="text-sm text-gray-600">
                        ${slot.available_slots} of ${slot.total_slots} slots available
                    </div>
                `;
            } else {
                slotContent = `
                    <div class="font-semibold">${slot.time}</div>
                    <div class="text-sm text-gray-600">
                        Fully booked
                    </div>
                `;
            }

            timeSlotsHTML += `
                <button type="button" 
                        class="${baseClasses} ${cls}"
                        data-time="${slot.time}"
                        ${(!isAvailable) ? 'disabled' : ''}
                        ${title ? `title="${title}"` : ''}>
                    ${slotContent}
                </button>
            `;
        });
        
        timeSlotsHTML += '</div>';
        timeSlotsHTML += '<input type="hidden" name="selected_time" id="selectedTime" value="">';
        
        timeSlotsContainer.innerHTML = timeSlotsHTML;
        
        // Re-attach event listeners to new time slot buttons
        this.attachTimeSlotListeners();
    }

    attachTimeSlotListeners() {
        const timeSlotBtns = document.querySelectorAll('.time-slot-btn:not([disabled])');
        const selectedTimeInput = document.getElementById('selectedTime');
        const continueBtn = document.getElementById('continueBtn');

        timeSlotBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove previous selection
                timeSlotBtns.forEach(b => {
                    b.classList.remove('bg-[#0d5c2f]', 'text-white');
                    b.classList.add('border-gray-300', 'hover:border-[#0d5c2f]', 'hover:bg-gray-50');
                });

                // Select this time slot
                this.classList.remove('border-gray-300', 'hover:border-[#0d5c2f]', 'hover:bg-gray-50');
                this.classList.add('bg-[#0d5c2f]', 'text-white');

                // Update hidden input
                selectedTimeInput.value = this.dataset.time;
                
                // Enable continue button
                continueBtn.disabled = false;
            });
        });
    }


    attachEventListeners() {
        const prevButton = document.getElementById('prevMonth');
        const nextButton = document.getElementById('nextMonth');
        
        if (prevButton) {
            prevButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.currentMonth.setMonth(this.currentMonth.getMonth() - 1);
                this.renderCalendar();
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.currentMonth.setMonth(this.currentMonth.getMonth() + 1);
                this.renderCalendar();
            });
        }
    }
}

// Initialize calendar when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    
    const calendarContainer = document.querySelector('.calendar-container');
    
    if (calendarContainer) {
        
        const calendar = new Calendar(
            calendarContainer,
            @json($service),
            @json($activeBookings),
            @json($selectedDate),
            @json($parochialActivities ?? []),
            @json($ministryActivities ?? [])
        );
        
        // Attach time slot listeners if there are existing time slots
        calendar.attachTimeSlotListeners();
    } else {
        console.error('Calendar container not found');
    }
});

</script>