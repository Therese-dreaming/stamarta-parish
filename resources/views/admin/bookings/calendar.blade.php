@extends('layouts.admin')

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
                <a href="{{ route('admin.bookings.index') }}" 
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
                        <div class="w-4 h-4 bg-yellow-400 rounded mr-2"></div>
                        <span class="text-sm text-gray-600">Pending</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-600">Acknowledged</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-orange-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-600">Payment Hold</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-600">Approved</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-600">Rejected</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-700 rounded mr-2"></div>
                        <span class="text-sm text-gray-600">Completed</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-400 rounded mr-2"></div>
                        <span class="text-sm text-gray-600">Activities</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-purple-500 rounded mr-2"></div>
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
</div>

<!-- Event Details Modal -->
<div id="eventModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-lg w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Event Details</h3>
                    <button onclick="closeEventModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div id="modalContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
                
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button onclick="closeEventModal()" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Close
                    </button>
                    <a id="viewBookingBtn" href="#" 
                       class="px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors hidden">
                        <i class="fas fa-eye mr-2"></i>View Booking
                    </a>
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
            dayDiv.className += ' text-gray-700 bg-white border-2 border-gray-300 hover:bg-gray-50 hover:border-[#0d5c2f]';
            
            // Check for events on this date
            const dayEvents = this.getEventsForDate(dateString);
            if (dayEvents.length > 0) {
                dayDiv.className += ' relative';
                
                // Determine the color based on events
                const eventColor = this.getEventColor(dayEvents);
                
                // Add event indicators
                if (dayEvents.length === 1) {
                    // Single event - use its color
                    const indicator = document.createElement('div');
                    indicator.className = 'absolute w-3 h-3 rounded-full';
                    indicator.style.backgroundColor = eventColor;
                    indicator.style.top = '8px';
                    indicator.style.right = '8px';
                    dayDiv.appendChild(indicator);
                } else {
                    // Multiple events - show multiple indicators or use purple for mixed
                    const uniqueTypes = [...new Set(dayEvents.map(e => e.type))];
                    const uniqueStatuses = [...new Set(dayEvents.filter(e => e.type === 'booking').map(e => e.status))];
                    
                    if (uniqueTypes.length > 1 || uniqueStatuses.length > 1) {
                        // Mixed events - use purple
                        const indicator = document.createElement('div');
                        indicator.className = 'absolute w-3 h-3 rounded-full';
                        indicator.style.backgroundColor = '#8b5cf6'; // Purple
                        indicator.style.top = '8px';
                        indicator.style.right = '8px';
                        dayDiv.appendChild(indicator);
                    } else {
                        // Same type/status - use the color
                        const indicator = document.createElement('div');
                        indicator.className = 'absolute w-3 h-3 rounded-full';
                        indicator.style.backgroundColor = eventColor;
                        indicator.style.top = '8px';
                        indicator.style.right = '8px';
                        dayDiv.appendChild(indicator);
                    }
                }
                
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
        return this.events.filter(event => {
            // Extract date part from the start time (handle timezone issues)
            const eventDate = event.start.split('T')[0];
            return eventDate === dateString;
        });
    }

    getEventColor(events) {
        if (events.length === 0) return '#6b7280'; // Gray
        
        if (events.length === 1) {
            const event = events[0];
            // Use the backgroundColor from the server data
            return event.backgroundColor || this.getStatusColor(event.status);
        }
        
        // Multiple events - check if they're all the same type/status
        const uniqueTypes = [...new Set(events.map(e => e.type))];
        const uniqueStatuses = [...new Set(events.filter(e => e.type === 'booking').map(e => e.status))];
        
        if (uniqueTypes.length === 1 && uniqueTypes[0] === 'activity') {
            return '#fbbf24'; // All activities
        } else if (uniqueTypes.length === 1 && uniqueTypes[0] === 'booking' && uniqueStatuses.length === 1) {
            return this.getStatusColor(uniqueStatuses[0]); // All same status bookings
        } else {
            return '#8b5cf6'; // Purple for mixed
        }
    }

    getStatusColor(status) {
        const colors = {
            'pending': '#fbbf24', // Yellow
            'acknowledged': '#3b82f6', // Blue
            'payment_hold': '#f97316', // Orange
            'approved': '#10b981', // Green
            'rejected': '#ef4444', // Red
            'completed': '#059669', // Dark Green
            'cancelled': '#6b7280', // Gray
        };
        return colors[status] || '#6b7280'; // Default gray
    }

    showDayEvents(dateString, events) {
        const modal = document.getElementById('eventModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        const viewBookingBtn = document.getElementById('viewBookingBtn');
        
        // Fix timezone issue by creating date properly
        const displayDate = new Date(dateString + 'T00:00:00');
        modalTitle.textContent = `Events for ${displayDate.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        })}`;
        
        let contentHTML = '';
        
        if (events.length === 0) {
            contentHTML = '<p class="text-gray-500">No events scheduled for this date.</p>';
        } else {
            contentHTML = '<div class="space-y-4">';
            events.forEach(event => {
                if (event.type === 'booking') {
                    contentHTML += `
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-900">${event.title}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    ${this.getStatusBadgeClass(event.status)}">
                                    ${event.status.charAt(0).toUpperCase() + event.status.slice(1).replace('_', ' ')}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><strong>Customer:</strong> ${event.extendedProps.user_name}</p>
                                <p><strong>Service:</strong> ${event.extendedProps.service_name}</p>
                                <p><strong>Phone:</strong> ${event.extendedProps.contact_phone}</p>
                                <p><strong>Time:</strong> ${this.formatTime(event.start)}</p>
                            </div>
                        </div>
                    `;
                } else {
                    contentHTML += `
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-900">${event.title}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Activity
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 space-y-1">
                                ${event.extendedProps.description ? `<p><strong>Description:</strong> ${event.extendedProps.description}</p>` : ''}
                                ${event.extendedProps.location ? `<p><strong>Location:</strong> ${event.extendedProps.location}</p>` : ''}
                                ${event.extendedProps.organizer ? `<p><strong>Organizer:</strong> ${event.extendedProps.organizer}</p>` : ''}
                                <p><strong>Time:</strong> ${this.formatTime(event.start)} - ${this.formatTime(event.end)}</p>
                            </div>
                        </div>
                    `;
                }
            });
            contentHTML += '</div>';
        }
        
        modalContent.innerHTML = contentHTML;
        viewBookingBtn.classList.add('hidden');
        modal.classList.remove('hidden');
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
        return new Date(dateString).toLocaleTimeString('en-US', {
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

function closeEventModal() {
    document.getElementById('eventModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('eventModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEventModal();
    }
});
</script>
@endsection 