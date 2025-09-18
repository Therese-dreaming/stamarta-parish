@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Admin - Bookings Calendar')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Bookings Calendar</h1>
                    <p class="text-white/80 mt-1">View all bookings and events in calendar format</p>
                </div>
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.index') : route('admin.bookings.index') }}" 
                   class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Back to Bookings">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <!-- Legend -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Legend</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(251, 191, 36, 0.25); border-color: rgba(251, 191, 36, 0.6);"></div>
                        <span class="text-sm text-gray-600">Pending</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(59, 130, 246, 0.25); border-color: rgba(59, 130, 246, 0.6);"></div>
                        <span class="text-sm text-gray-600">Acknowledged</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(249, 115, 22, 0.25); border-color: rgba(249, 115, 22, 0.6);"></div>
                        <span class="text-sm text-gray-600">Payment Hold</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(16, 185, 129, 0.25); border-color: rgba(16, 185, 129, 0.6);"></div>
                        <span class="text-sm text-gray-600">Approved</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(239, 68, 68, 0.25); border-color: rgba(239, 68, 68, 0.6);"></div>
                        <span class="text-sm text-gray-600">Rejected</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(5, 150, 105, 0.25); border-color: rgba(5, 150, 105, 0.6);"></div>
                        <span class="text-sm text-gray-600">Completed</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(251, 191, 36, 0.25); border-color: rgba(251, 191, 36, 0.6);"></div>
                        <span class="text-sm text-gray-600">Activities</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(96, 165, 250, 0.25); border-color: rgba(96, 165, 250, 0.6);"></div>
                        <span class="text-sm text-gray-600">Ministry Activities</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(139, 92, 246, 0.25); border-color: rgba(139, 92, 246, 0.6);"></div>
                        <span class="text-sm text-gray-600">Multiple Events</span>
                    </div>
                </div>
            </div>

            <!-- Calendar Header -->
            <div class="calendar-header flex items-center justify-between mb-8">
                <button type="button" id="prevMonth" class="p-3 text-gray-600 hover:text-[#0d5c2f] hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-chevron-left text-lg"></i>
                </button>
                <h3 id="currentMonth" class="text-xl font-semibold text-gray-900"></h3>
                <button type="button" id="nextMonth" class="p-3 text-gray-600 hover:text-[#0d5c2f] hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-chevron-right text-lg"></i>
                </button>
            </div>

            <!-- Calendar Grid -->
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
        </div>
    </div>

    <!-- Events Display Section -->
    <div id="eventsSection" class="bg-white rounded-xl shadow-lg border border-gray-200 hidden">
        <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-t-xl">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 id="selectedDateTitle" class="text-xl font-semibold text-white">Events for Selected Date</h3>
                        <p class="text-white/80 text-sm mt-1">View all scheduled events and activities</p>
                    </div>
                    <button onclick="hideEventsSection()" class="text-white/80 hover:text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Bookings Section -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-[#0d5c2f] rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-calendar-check text-white text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">Bookings</h4>
                            <p class="text-sm text-gray-500">Service appointments</p>
                        </div>
                    </div>
                    <div id="bookingsList" class="space-y-4">
                        <!-- Bookings will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Activities Section -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-yellow-500 rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-church text-white text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">Parochial Activities</h4>
                            <p class="text-sm text-gray-500">Church events</p>
                        </div>
                    </div>
                    <div id="activitiesList" class="space-y-4">
                        <!-- Activities will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Ministry Activities Section -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-users text-white text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">Ministry Activities</h4>
                            <p class="text-sm text-gray-500">Ministry events</p>
                        </div>
                    </div>
                    <div id="ministryActivitiesList" class="space-y-4">
                        <!-- Ministry Activities will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
class AdminCalendar {
    constructor(container, events) {
        this.container = container;
        this.events = events;
        this.currentDate = new Date();
        this.displayedMonth = new Date();
        this.selectedDate = null;
        
        this.init();
    }

    init() {
        this.renderCalendar();
        this.attachEventListeners();
    }

    renderCalendar() {
        const year = this.displayedMonth.getFullYear();
        const month = this.displayedMonth.getMonth();
        
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
        const dateString = this.formatDateForComparison(date);
        
        // Set initial classes
        if (!isCurrentMonth) {
            dayDiv.className += ' text-gray-300 cursor-not-allowed';
        } else {
            dayDiv.className += ' text-gray-700 bg-white border-2 border-gray-300 hover:bg-gray-50 hover:border-[#0d5c2f] hover:shadow-md transform hover:scale-105 transition-all duration-200';
            
            // Check for events on this date
            const dayEvents = this.getEventsForDate(dateString);
            if (dayEvents.length > 0) {
                dayDiv.className += ' relative';
                
                // Determine the color based on events
                const eventColor = this.getEventColor(dayEvents);
                
                // Color the entire day background
                dayDiv.style.backgroundColor = eventColor;
                dayDiv.style.color = '#374151'; // Dark gray text for contrast
                dayDiv.style.fontWeight = 'bold';
                dayDiv.style.borderColor = eventColor.replace('0.25)', '0.6)'); // Darker border
                dayDiv.style.borderWidth = '2px';
                dayDiv.style.borderStyle = 'solid';
            }
            
            // Add click handler for all dates in current month
            dayDiv.addEventListener('click', () => this.showDayEvents(dateString, dayEvents));
        }

        dayDiv.textContent = dayNumber;
        dayDiv.dataset.date = dateString;
        
        return dayDiv;
    }

    formatDateForComparison(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    getEventsForDate(dateString) {
        const events = this.events.filter(event => {
            // Extract date part from the start time (handle timezone issues)
            let eventDate = event.start.split('T')[0];
            
            // If the event date still contains time (like "2025-08-25 00:00:00"), extract just the date
            if (eventDate.includes(' ')) {
                eventDate = eventDate.split(' ')[0];
            }
            
            const matches = eventDate === dateString;
            return matches;
        });
        
        return events;
    }

    getEventColor(events) {
        if (events.length === 0) return '#6b7280'; // Gray
        
        if (events.length === 1) {
            const event = events[0];
            // Use the backgroundColor from the server data
            const color = event.backgroundColor || this.getStatusColor(event.status);
            return color;
        }
        
        // Multiple events - check if they're all the same type/status
        const uniqueTypes = [...new Set(events.map(e => e.type))];
        const uniqueStatuses = [...new Set(events.filter(e => e.type === 'booking').map(e => e.status))];
        
        if (uniqueTypes.length === 1 && uniqueTypes[0] === 'activity') {
            return '#fbbf24'; // All activities
        } else if (uniqueTypes.length === 1 && uniqueTypes[0] === 'ministry_activity') {
            return 'rgba(96, 165, 250, 0.25)'; // All ministry activities
        } else if (uniqueTypes.length === 1 && uniqueTypes[0] === 'booking' && uniqueStatuses.length === 1) {
            return this.getStatusColor(uniqueStatuses[0]); // All same status bookings
        } else {
            return '#8b5cf6'; // Purple for mixed
        }
    }

    getStatusColor(status) {
        const colors = {
            'pending': 'rgba(251, 191, 36, 0.25)', // Yellow with 25% opacity
            'acknowledged': 'rgba(59, 130, 246, 0.25)', // Blue with 25% opacity
            'payment_hold': 'rgba(249, 115, 22, 0.25)', // Orange with 25% opacity
            'approved': 'rgba(16, 185, 129, 0.25)', // Green with 25% opacity
            'rejected': 'rgba(239, 68, 68, 0.25)', // Red with 25% opacity
            'completed': 'rgba(5, 150, 105, 0.25)', // Dark Green with 25% opacity
            'cancelled': 'rgba(107, 114, 128, 0.25)', // Gray with 25% opacity
        };
        return colors[status] || 'rgba(107, 114, 128, 0.25)'; // Default gray with 25% opacity
    }

    showDayEvents(dateString, events) {
        const eventsSection = document.getElementById('eventsSection');
        const selectedDateTitle = document.getElementById('selectedDateTitle');
        const bookingsList = document.getElementById('bookingsList');
        const activitiesList = document.getElementById('activitiesList');
        const ministryActivitiesList = document.getElementById('ministryActivitiesList');
        
        // Fix timezone issue by creating date properly
        const displayDate = new Date(dateString + 'T00:00:00');
        
        // Check if date is valid
        if (isNaN(displayDate.getTime())) {
            selectedDateTitle.textContent = `Events for ${dateString}`;
        } else {
            selectedDateTitle.textContent = `Events for ${displayDate.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            })}`;
        }
        
        // Check if there are any events at all for this date
        const hasAnyEvents = events.length > 0;
        
        // If no events at all, show a special empty state
        if (!hasAnyEvents) {
            this.showEmptyDateDesign(dateString);
            return;
        }
        
        // Separate bookings, activities, and ministry activities
        const bookings = events.filter(event => event.type === 'booking');
        const activities = events.filter(event => event.type === 'activity');
        const ministryActivities = events.filter(event => event.type === 'ministry_activity');
        
        // Populate bookings section
        if (bookings.length === 0) {
            bookingsList.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-calendar-check text-gray-400 text-2xl"></i>
                    </div>
                    <h4 class="text-base font-semibold text-gray-900 mb-2">No Bookings</h4>
                    <p class="text-sm text-gray-500">No bookings scheduled for this date</p>
                </div>
            `;
        } else {
            let bookingsHTML = '';
            bookings.forEach(booking => {
                bookingsHTML += `
                    <div class="group bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h5 class="text-lg font-semibold text-gray-900 group-hover:text-[#0d5c2f] transition-colors mb-1">${booking.title}</h5>
                                    <p class="text-sm text-gray-500">${booking.extendedProps.service_name}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${this.getStatusBadgeClass(booking.status)}">
                                    ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1).replace('_', ' ')}
                                </span>
                            </div>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-user w-4 h-4 mr-3 text-gray-400"></i>
                                    <span>${booking.extendedProps.user_name}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-phone w-4 h-4 mr-3 text-gray-400"></i>
                                    <span>${booking.extendedProps.contact_phone}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock w-4 h-4 mr-3 text-gray-400"></i>
                                    <span>${this.formatTime(booking.start)}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    <span>Booking ID: ${booking.booking_id}</span>
                                </div>
                                <a href="/admin/bookings/${booking.booking_id}" 
                                   class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white text-sm font-medium rounded-xl hover:bg-[#0d5c2f]/90 transition-all duration-200 transform hover:scale-105 shadow-sm">
                                    <i class="fas fa-eye mr-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });
            bookingsList.innerHTML = bookingsHTML;
        }
        
        // Populate activities section
        if (activities.length === 0) {
            activitiesList.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-church text-yellow-400 text-2xl"></i>
                    </div>
                    <h4 class="text-base font-semibold text-gray-900 mb-2">No Activities</h4>
                    <p class="text-sm text-gray-500">No parochial activities scheduled for this date</p>
                </div>
            `;
        } else {
            let activitiesHTML = '';
            activities.forEach(activity => {
                activitiesHTML += `
                    <div class="group bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h5 class="text-lg font-semibold text-gray-900 group-hover:text-yellow-600 transition-colors mb-1">${activity.title}</h5>
                                    <p class="text-sm text-gray-500">Parochial Activity</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Activity
                                </span>
                            </div>
                            
                            <div class="space-y-3 mb-4">
                                ${activity.extendedProps.description ? `
                                    <div class="flex items-start text-sm text-gray-600">
                                        <i class="fas fa-align-left w-4 h-4 mr-3 text-gray-400 mt-0.5"></i>
                                        <span>${activity.extendedProps.description}</span>
                                    </div>
                                ` : ''}
                                ${activity.extendedProps.location ? `
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt w-4 h-4 mr-3 text-gray-400"></i>
                                        <span>${activity.extendedProps.location}</span>
                                    </div>
                                ` : ''}
                                ${activity.extendedProps.organizer ? `
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-user-tie w-4 h-4 mr-3 text-gray-400"></i>
                                        <span>${activity.extendedProps.organizer}</span>
                                    </div>
                                ` : ''}
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock w-4 h-4 mr-3 text-gray-400"></i>
                                    <span>${this.formatTime(activity.start)} - ${this.formatTime(activity.end)}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    <span>Activity ID: ${activity.activity_id}</span>
                                </div>
                                <a href="/admin/parochial-activities/${activity.activity_id}" 
                                   class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-xl hover:bg-yellow-600 transition-all duration-200 transform hover:scale-105 shadow-sm">
                                    <i class="fas fa-eye mr-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });
            activitiesList.innerHTML = activitiesHTML;
        }
        
        // Populate ministry activities section
        if (ministryActivities.length === 0) {
            ministryActivitiesList.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-users text-blue-400 text-2xl"></i>
                    </div>
                    <h4 class="text-base font-semibold text-gray-900 mb-2">No Ministry Activities</h4>
                    <p class="text-sm text-gray-500">No ministry activities scheduled for this date</p>
                </div>
            `;
        } else {
            let ministryActivitiesHTML = '';
            ministryActivities.forEach(activity => {
                const isPublic = activity.extendedProps.is_public !== false; // Default to true if not specified
                const ministryName = activity.extendedProps.ministry || 'Unknown Ministry';
                const isAllDay = activity.extendedProps.is_all_day || false;
                
                ministryActivitiesHTML += `
                    <div class="group bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h5 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors mb-1">${activity.title}</h5>
                                    <p class="text-sm text-gray-500">${ministryName}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Ministry
                                    </span>
                                    ${isPublic ? 
                                        '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Public</span>' :
                                        '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Internal</span>'
                                    }
                                </div>
                            </div>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-users w-4 h-4 mr-3 text-gray-400"></i>
                                    <span>${ministryName}</span>
                                </div>
                                ${activity.extendedProps.description ? `
                                    <div class="flex items-start text-sm text-gray-600">
                                        <i class="fas fa-align-left w-4 h-4 mr-3 text-gray-400 mt-0.5"></i>
                                        <span>${activity.extendedProps.description}</span>
                                    </div>
                                ` : ''}
                                ${activity.extendedProps.location ? `
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt w-4 h-4 mr-3 text-gray-400"></i>
                                        <span>${activity.extendedProps.location}</span>
                                    </div>
                                ` : ''}
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock w-4 h-4 mr-3 text-gray-400"></i>
                                    <span>${isAllDay ? 'All Day' : (this.formatTime(activity.start) + (activity.end ? ' - ' + this.formatTime(activity.end) : ''))}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    <span>Activity ID: ${activity.activity_id}</span>
                                </div>
                                <a href="/admin/ministries/ministry-activities/${activity.activity_id}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-xl hover:bg-blue-600 transition-all duration-200 transform hover:scale-105 shadow-sm">
                                    <i class="fas fa-eye mr-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });
            ministryActivitiesList.innerHTML = ministryActivitiesHTML;
        }
        
        // Show the events section
        eventsSection.classList.remove('hidden');
        
        // Scroll to events section
        eventsSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    
    showEmptyDateDesign(dateString) {
        const eventsSection = document.getElementById('eventsSection');
        const selectedDateTitle = document.getElementById('selectedDateTitle');
        const bookingsList = document.getElementById('bookingsList');
        const activitiesList = document.getElementById('activitiesList');
        const ministryActivitiesList = document.getElementById('ministryActivitiesList');
        
        // Fix timezone issue by creating date properly
        const displayDate = new Date(dateString + 'T00:00:00');
        
        // Update title
        if (isNaN(displayDate.getTime())) {
            selectedDateTitle.textContent = `Events for ${dateString}`;
        } else {
            selectedDateTitle.textContent = `Events for ${displayDate.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            })}`;
        }
        
        // Show empty state for all sections
        bookingsList.innerHTML = `
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-calendar-plus text-gray-400 text-3xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 mb-3">No Bookings Scheduled</h4>
                <p class="text-sm text-gray-500">This date is available for new bookings</p>
            </div>
        `;
        
        activitiesList.innerHTML = `
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-church text-yellow-400 text-3xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 mb-3">No Parochial Activities</h4>
                <p class="text-sm text-gray-500">No church activities planned for this date</p>
            </div>
        `;
        
        ministryActivitiesList.innerHTML = `
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-users text-blue-400 text-3xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 mb-3">No Ministry Activities</h4>
                <p class="text-sm text-gray-500">No ministry events scheduled for this date</p>
            </div>
        `;
        
        // Show the events section
        eventsSection.classList.remove('hidden');
        
        // Scroll to events section
        eventsSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    getStatusBadgeClass(status) {
        const classes = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'acknowledged': 'bg-blue-100 text-blue-800',
            'payment_hold': 'bg-orange-100 text-orange-800',
            'approved': 'bg-green-100 text-green-800',
            'rejected': 'bg-red-100 text-red-800',
            'completed': 'bg-green-100 text-green-800',
            'cancelled': 'bg-gray-100 text-gray-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    }

    formatTime(dateString) {
        // Handle both string and Date object inputs
        let date;
        if (typeof dateString === 'string') {
            date = new Date(dateString);
        } else {
            date = dateString;
        }
        
        // Check if date is valid
        if (isNaN(date.getTime())) {
            return 'Invalid time';
        }
        
        return date.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    attachEventListeners() {
        const prevButton = document.getElementById('prevMonth');
        const nextButton = document.getElementById('nextMonth');
        
        if (prevButton) {
            prevButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.displayedMonth.setMonth(this.displayedMonth.getMonth() - 1);
                this.renderCalendar();
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.displayedMonth.setMonth(this.displayedMonth.getMonth() + 1);
                this.renderCalendar();
            });
        }
    }
}

// Initialize calendar when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const calendarContainer = document.querySelector('.calendar-grid');
    
    if (calendarContainer) {
        const calendar = new AdminCalendar(
            calendarContainer,
            @json($calendarEvents)
        );
    }
});

function hideEventsSection() {
    document.getElementById('eventsSection').classList.add('hidden');
}
</script>
@endsection 