@extends('layouts.priest')

@section('title', 'Calendar View')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Calendar View</h1>
                    <p class="text-white/80 mt-1">View your assigned bookings in calendar format</p>
                </div>
                <a href="{{ route('priest.bookings.index') }}" 
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
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(139, 92, 246, 0.25); border-color: rgba(139, 92, 246, 0.6);"></div>
                        <span class="text-sm text-gray-600">Ministry Activities</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(251, 191, 36, 0.25); border-color: rgba(251, 191, 36, 0.6);"></div>
                        <span class="text-sm text-gray-600">Parochial Activities</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(107, 114, 128, 0.25); border-color: rgba(107, 114, 128, 0.6);"></div>
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
                            <h4 class="text-lg font-semibold text-gray-900">My Bookings</h4>
                            <p class="text-sm text-gray-500">Service appointments</p>
                        </div>
                    </div>
                    <div id="bookingsList" class="space-y-4">
                        <!-- Bookings will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Ministry Activities Section -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center shadow-sm">
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

                <!-- Parochial Activities Section -->
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
                    <div id="parochialActivitiesList" class="space-y-4">
                        <!-- Parochial Activities will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
class PriestCalendar {
    constructor(container, bookings, ministryActivities, parochialActivities) {
        this.container = container;
        this.bookings = bookings;
        this.ministryActivities = ministryActivities;
        this.parochialActivities = parochialActivities;
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
            dayDiv.className += ' text-gray-700 bg-white border-2 border-gray-300 hover:bg-gray-50 hover:border-[#0d5c2f]';
            
            // Check if date is today
            const today = new Date();
            if (date.toDateString() === today.toDateString()) {
                dayDiv.className += ' bg-blue-50 border-blue-300';
            }
            
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
            
            // Add click handler for all current month days (with or without events)
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
        const events = [];
        
        // Add bookings
        const dateBookings = this.bookings.filter(booking => {
            const bookingDate = new Date(booking.service_date);
            return this.formatDateForComparison(bookingDate) === dateString;
        });
        
        dateBookings.forEach(booking => {
            events.push({
                type: 'booking',
                id: booking.id,
                title: `#${booking.id} - ${booking.service.name}`,
                status: booking.status,
                backgroundColor: this.getStatusColor(booking.status),
                booking: booking
            });
        });
        
        // Add ministry activities
        const dateMinistryActivities = this.ministryActivities.filter(activity => {
            const activityStart = new Date(activity.start_date);
            const activityEnd = new Date(activity.end_date);
            const checkDate = new Date(dateString);
            return checkDate >= activityStart && checkDate <= activityEnd;
        });
        
        dateMinistryActivities.forEach(activity => {
            events.push({
                type: 'ministry_activity',
                id: activity.id,
                title: activity.title,
                backgroundColor: 'rgba(139, 92, 246, 0.25)',
                activity: activity
            });
        });
        
        // Add parochial activities
        const dateParochialActivities = this.parochialActivities.filter(activity => {
            const activityStart = new Date(activity.start_date);
            const activityEnd = new Date(activity.end_date);
            const checkDate = new Date(dateString);
            return checkDate >= activityStart && checkDate <= activityEnd;
        });
        
        dateParochialActivities.forEach(activity => {
            events.push({
                type: 'parochial_activity',
                id: activity.id,
                title: activity.title,
                backgroundColor: 'rgba(251, 191, 36, 0.25)',
                activity: activity
            });
        });
        
        return events;
    }

    getEventColor(events) {
        if (events.length === 0) return '#6b7280'; // Gray
        
        if (events.length === 1) {
            const event = events[0];
            return event.backgroundColor || this.getStatusColor(event.status);
        }
        
        // Multiple events - check if they're all the same type/status
        const uniqueTypes = [...new Set(events.map(e => e.type))];
        const uniqueStatuses = [...new Set(events.filter(e => e.type === 'booking').map(e => e.status))];
        
        if (uniqueTypes.length === 1 && uniqueTypes[0] === 'ministry_activity') {
            return 'rgba(139, 92, 246, 0.25)'; // All ministry activities
        } else if (uniqueTypes.length === 1 && uniqueTypes[0] === 'parochial_activity') {
            return 'rgba(251, 191, 36, 0.25)'; // All parochial activities
        } else if (uniqueTypes.length === 1 && uniqueTypes[0] === 'booking' && uniqueStatuses.length === 1) {
            return this.getStatusColor(uniqueStatuses[0]); // All same status bookings
        } else {
            return 'rgba(107, 114, 128, 0.25)'; // Gray for mixed events
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
        const ministryActivitiesList = document.getElementById('ministryActivitiesList');
        const parochialActivitiesList = document.getElementById('parochialActivitiesList');
        
        // Fix timezone issue by creating date properly
        const displayDate = new Date(dateString + 'T00:00:00');
        selectedDateTitle.textContent = `Events for ${displayDate.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        })}`;
        
        // Separate events by type
        const bookings = events.filter(event => event.type === 'booking');
        const ministryActivities = events.filter(event => event.type === 'ministry_activity');
        const parochialActivities = events.filter(event => event.type === 'parochial_activity');
        
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
            bookings.forEach(bookingEvent => {
                const booking = bookingEvent.booking;
                bookingsHTML += `
                    <div class="group bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h5 class="text-lg font-semibold text-gray-900 group-hover:text-[#0d5c2f] transition-colors mb-1">#${booking.id} - ${booking.service.name}</h5>
                                    <p class="text-sm text-gray-500">Service Appointment</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${this.getStatusBadgeClass(booking.status)}">
                                    ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1).replace('_', ' ')}
                                </span>
                            </div>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-user w-4 h-4 mr-3 text-gray-400"></i>
                                    <span>${booking.user.name}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-phone w-4 h-4 mr-3 text-gray-400"></i>
                                    <span>${booking.contact_phone || 'N/A'}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock w-4 h-4 mr-3 text-gray-400"></i>
                                    <span>${booking.service_time || 'N/A'}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    <span>Booking ID: ${booking.id}</span>
                                </div>
                                <a href="/priest/bookings/${booking.id}" 
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
        
        // Populate ministry activities section
        if (ministryActivities.length === 0) {
            ministryActivitiesList.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-100 to-purple-50 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-users text-purple-400 text-2xl"></i>
                    </div>
                    <h4 class="text-base font-semibold text-gray-900 mb-2">No Ministry Activities</h4>
                    <p class="text-sm text-gray-500">No ministry activities scheduled for this date</p>
                </div>
            `;
        } else {
            let ministryActivitiesHTML = '';
            ministryActivities.forEach(activityEvent => {
                const activity = activityEvent.activity;
                ministryActivitiesHTML += `
                    <div class="group bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h5 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors mb-1">${activity.title}</h5>
                                    <p class="text-sm text-gray-500">Ministry Activity</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    Ministry Activity
                                </span>
                            </div>
                            
                            <div class="space-y-3 mb-4">
                                ${activity.description ? `
                                    <div class="flex items-start text-sm text-gray-600">
                                        <i class="fas fa-align-left w-4 h-4 mr-3 text-gray-400 mt-0.5"></i>
                                        <span>${activity.description}</span>
                                    </div>
                                ` : ''}
                                ${activity.location ? `
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt w-4 h-4 mr-3 text-gray-400"></i>
                                        <span>${activity.location}</span>
                                    </div>
                                ` : ''}
                                ${activity.organizer ? `
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-user-tie w-4 h-4 mr-3 text-gray-400"></i>
                                        <span>${activity.organizer}</span>
                                    </div>
                                ` : ''}
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock w-4 h-4 mr-3 text-gray-400"></i>
                                    <span>${this.formatDate(activity.start_date)} - ${this.formatDate(activity.end_date)}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    <span>Activity ID: ${activity.id}</span>
                                </div>
                                <a href="/priest/ministry-activities/${activity.id}" 
                                   class="inline-flex items-center px-4 py-2 bg-purple-500 text-white text-sm font-medium rounded-xl hover:bg-purple-600 transition-all duration-200 transform hover:scale-105 shadow-sm">
                                    <i class="fas fa-eye mr-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });
            ministryActivitiesList.innerHTML = ministryActivitiesHTML;
        }
        
        // Populate parochial activities section
        if (parochialActivities.length === 0) {
            parochialActivitiesList.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-church text-yellow-400 text-2xl"></i>
                    </div>
                    <h4 class="text-base font-semibold text-gray-900 mb-2">No Parochial Activities</h4>
                    <p class="text-sm text-gray-500">No parochial activities scheduled for this date</p>
                </div>
            `;
        } else {
            let parochialActivitiesHTML = '';
            parochialActivities.forEach(activityEvent => {
                const activity = activityEvent.activity;
                parochialActivitiesHTML += `
                    <div class="group bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h5 class="text-lg font-semibold text-gray-900 group-hover:text-yellow-600 transition-colors mb-1">${activity.title}</h5>
                                    <p class="text-sm text-gray-500">Parochial Activity</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Parochial Activity
                                </span>
                            </div>
                            
                            <div class="space-y-3 mb-4">
                                ${activity.description ? `
                                    <div class="flex items-start text-sm text-gray-600">
                                        <i class="fas fa-align-left w-4 h-4 mr-3 text-gray-400 mt-0.5"></i>
                                        <span>${activity.description}</span>
                                    </div>
                                ` : ''}
                                ${activity.location ? `
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt w-4 h-4 mr-3 text-gray-400"></i>
                                        <span>${activity.location}</span>
                                    </div>
                                ` : ''}
                                ${activity.organizer ? `
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-user-tie w-4 h-4 mr-3 text-gray-400"></i>
                                        <span>${activity.organizer}</span>
                                    </div>
                                ` : ''}
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock w-4 h-4 mr-3 text-gray-400"></i>
                                    <span>${this.formatDate(activity.start_date)} - ${this.formatDate(activity.end_date)}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    <span>Activity ID: ${activity.id}</span>
                                </div>
                                <a href="/priest/parochial-activities/${activity.id}" 
                                   class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-xl hover:bg-yellow-600 transition-all duration-200 transform hover:scale-105 shadow-sm">
                                    <i class="fas fa-eye mr-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });
            parochialActivitiesList.innerHTML = parochialActivitiesHTML;
        }
        
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

    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
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
        const bookings = @json($bookingsData);
        const ministryActivities = @json($ministryActivities ?? []);
        const parochialActivities = @json($parochialActivities ?? []);
        
        const calendar = new PriestCalendar(
            calendarContainer,
            bookings,
            ministryActivities,
            parochialActivities
        );
    }
});

function hideEventsSection() {
    document.getElementById('eventsSection').classList.add('hidden');
}
</script>
@endsection 