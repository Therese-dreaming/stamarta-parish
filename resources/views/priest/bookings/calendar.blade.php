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
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(251, 191, 36, 0.25); border-color: rgba(251, 191, 36, 0.6);"></div>
                        <span class="text-sm text-gray-600">Activities</span>
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
    <div id="eventsSection" class="bg-white rounded-xl shadow-sm border border-gray-200 hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 id="selectedDateTitle" class="text-xl font-semibold text-gray-900">Events for Selected Date</h3>
                <button onclick="hideEventsSection()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Bookings Section -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-calendar-check mr-2 text-[#0d5c2f]"></i>
                        My Bookings
                    </h4>
                    <div id="bookingsList" class="space-y-3">
                        <!-- Bookings will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Activities Section -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-church mr-2 text-yellow-500"></i>
                        Parochial Activities
                    </h4>
                    <div id="activitiesList" class="space-y-3">
                        <!-- Activities will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
class PriestCalendar {
    constructor(container, bookings, activities) {
        this.container = container;
        this.bookings = bookings;
        this.activities = activities;
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
                
                // Add click handler
                dayDiv.addEventListener('click', () => this.showDayEvents(dateString, dayEvents));
            }
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
        
        // Add activities
        const dateActivities = this.activities.filter(activity => {
            const activityStart = new Date(activity.start_date);
            const activityEnd = new Date(activity.end_date);
            const checkDate = new Date(dateString);
            return checkDate >= activityStart && checkDate <= activityEnd;
        });
        
        dateActivities.forEach(activity => {
            events.push({
                type: 'activity',
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
        
        if (uniqueTypes.length === 1 && uniqueTypes[0] === 'activity') {
            return 'rgba(251, 191, 36, 0.25)'; // All activities
        } else if (uniqueTypes.length === 1 && uniqueTypes[0] === 'booking' && uniqueStatuses.length === 1) {
            return this.getStatusColor(uniqueStatuses[0]); // All same status bookings
        } else {
            return 'rgba(139, 92, 246, 0.25)'; // Purple for mixed
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
        
        // Fix timezone issue by creating date properly
        const displayDate = new Date(dateString + 'T00:00:00');
        selectedDateTitle.textContent = `Events for ${displayDate.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        })}`;
        
        // Separate bookings and activities
        const bookings = events.filter(event => event.type === 'booking');
        const activities = events.filter(event => event.type === 'activity');
        
        // Populate bookings section
        if (bookings.length === 0) {
            bookingsList.innerHTML = '<p class="text-gray-500 italic">No bookings scheduled for this date.</p>';
        } else {
            let bookingsHTML = '';
            bookings.forEach(bookingEvent => {
                const booking = bookingEvent.booking;
                bookingsHTML += `
                    <div class="bg-gray-50 rounded-lg p-4 border-l-4" style="border-left-color: ${bookingEvent.backgroundColor}">
                        <div class="flex items-center justify-between mb-2">
                            <h5 class="font-semibold text-gray-900">#${booking.id} - ${booking.service.name}</h5>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                ${this.getStatusBadgeClass(booking.status)}">
                                ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1).replace('_', ' ')}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>Customer:</strong> ${booking.user.name}</p>
                            <p><strong>Service:</strong> ${booking.service.name}</p>
                            <p><strong>Phone:</strong> ${booking.contact_phone || 'N/A'}</p>
                            <p><strong>Time:</strong> ${booking.formatted_time || 'N/A'}</p>
                        </div>
                        <div class="mt-3">
                            <a href="/priest/bookings/${booking.id}" 
                               class="inline-flex items-center px-3 py-1 bg-[#0d5c2f] text-white text-xs rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                                <i class="fas fa-eye mr-1"></i>View Details
                            </a>
                        </div>
                    </div>
                `;
            });
            bookingsList.innerHTML = bookingsHTML;
        }
        
        // Populate activities section
        if (activities.length === 0) {
            activitiesList.innerHTML = '<p class="text-gray-500 italic">No parochial activities scheduled for this date.</p>';
        } else {
            let activitiesHTML = '';
            activities.forEach(activityEvent => {
                const activity = activityEvent.activity;
                activitiesHTML += `
                    <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-400">
                        <div class="flex items-center justify-between mb-2">
                            <h5 class="font-semibold text-gray-900">${activity.title}</h5>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Activity
                            </span>
                        </div>
                        <div class="text-sm text-gray-600 space-y-1">
                            ${activity.description ? `<p><strong>Description:</strong> ${activity.description}</p>` : ''}
                            ${activity.location ? `<p><strong>Location:</strong> ${activity.location}</p>` : ''}
                            ${activity.organizer ? `<p><strong>Organizer:</strong> ${activity.organizer}</p>` : ''}
                            <p><strong>Duration:</strong> ${this.formatDate(activity.start_date)} - ${this.formatDate(activity.end_date)}</p>
                        </div>
                    </div>
                `;
            });
            activitiesList.innerHTML = activitiesHTML;
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
        const calendar = new PriestCalendar(
            calendarContainer,
            @json($bookings),
            @json($activities)
        );
    }
});

function hideEventsSection() {
    document.getElementById('eventsSection').classList.add('hidden');
}
</script>
@endsection 