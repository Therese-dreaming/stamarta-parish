@extends('layouts.user')

@section('title', 'Parish Calendar')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-[#0d5c2f] via-[#0f6b35] to-[#0d5c2f] relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full transform translate-x-32 -translate-y-32"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full transform -translate-x-16 translate-y-16"></div>
        
        <div class="relative z-10 container mx-auto px-6 py-16">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl mb-6 shadow-2xl">
                    <i class="fas fa-calendar-alt text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Parish Calendar</h1>
                <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                    Stay connected with all parish activities, events, and services in one beautiful calendar view
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-2xl font-bold text-white">{{ $stats['total'] }}</div>
                        <div class="text-sm text-white/80">Total Events</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-2xl font-bold text-white">{{ $stats['bookings'] }}</div>
                        <div class="text-sm text-white/80">Bookings</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-2xl font-bold text-white">{{ $stats['parochial'] }}</div>
                        <div class="text-sm text-white/80">Parish Events</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-2xl font-bold text-white">{{ $stats['ministry'] }}</div>
                        <div class="text-sm text-white/80">Ministry Events</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
            
            <!-- Calendar Grid -->
            <div class="xl:col-span-3">
                <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                    <!-- Calendar Header -->
                    <div class="bg-gradient-to-r from-gray-50 to-white px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-[#0d5c2f] rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-calendar text-white text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900" id="current-month-year">
                                        {{ \Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
                                    </h2>
                                    <p class="text-gray-600">Parish Events & Activities</p>
                                </div>
                            </div>
                            
                            <!-- Navigation -->
                            <div class="flex items-center space-x-2">
                                <button onclick="changeMonth(-1)" 
                                        class="w-12 h-12 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-sm">
                                    <i class="fas fa-chevron-left text-gray-600"></i>
                                </button>
                                <button onclick="goToToday()" 
                                        class="px-4 py-2 bg-[#0d5c2f] text-white rounded-xl hover:bg-[#0d5c2f]/90 transition-colors font-medium shadow-sm">
                                    Today
                                </button>
                                <button onclick="changeMonth(1)" 
                                        class="w-12 h-12 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-sm">
                                    <i class="fas fa-chevron-right text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="p-8">
                        <!-- Days of Week -->
                        <div class="grid grid-cols-7 gap-2 mb-4">
                            @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            <div class="text-center py-3 text-sm font-semibold text-gray-600 bg-gray-50 rounded-lg">
                                {{ substr($day, 0, 3) }}
                            </div>
                            @endforeach
                        </div>

                        <!-- Calendar Days -->
                        <div id="calendar-grid" class="grid grid-cols-7 gap-2">
                            <!-- Calendar days will be generated by JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="mt-6 bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-[#0d5c2f]"></i>
                        Event Types
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center p-3 bg-blue-50 rounded-xl border border-blue-200">
                            <div class="w-4 h-4 bg-blue-600 rounded-full mr-3 shadow-sm"></div>
                            <div>
                                <div class="font-medium text-blue-900">Service Bookings</div>
                                <div class="text-sm text-blue-700">Weddings, Baptisms, etc.</div>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-green-50 rounded-xl border border-green-200">
                            <div class="w-4 h-4 bg-green-600 rounded-full mr-3 shadow-sm"></div>
                            <div>
                                <div class="font-medium text-green-900">Parish Activities</div>
                                <div class="text-sm text-green-700">Community events</div>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-purple-50 rounded-xl border border-purple-200">
                            <div class="w-4 h-4 bg-purple-600 rounded-full mr-3 shadow-sm"></div>
                            <div>
                                <div class="font-medium text-purple-900">Ministry Activities</div>
                                <div class="text-sm text-purple-700">Ministry programs</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Details Sidebar -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-6">
                    <!-- Sidebar Header -->
                    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0f6b35] px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-calendar-day mr-2"></i>
                            Event Details
                        </h3>
                    </div>

                    <!-- Selected Date Info -->
                    <div id="selected-date-info" class="p-6 border-b border-gray-200 hidden">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-[#0d5c2f] rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                                <span id="selected-day" class="text-2xl font-bold text-white"></span>
                            </div>
                            <div id="selected-date-text" class="text-lg font-semibold text-gray-900"></div>
                            <div id="selected-month-year" class="text-sm text-gray-600"></div>
                        </div>
                    </div>

                    <!-- Events List -->
                    <div id="events-container" class="max-h-96 overflow-y-auto">
                        <!-- Default State -->
                        <div id="no-date-selected" class="p-8 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-plus text-gray-400 text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Select a Date</h4>
                            <p class="text-gray-600 text-sm">Click on any date to view events and activities</p>
                        </div>

                        <!-- Events will be populated here -->
                        <div id="events-list" class="hidden"></div>
                        
                        <!-- No Events State -->
                        <div id="no-events" class="p-8 text-center hidden">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">No Events</h4>
                            <p class="text-gray-600 text-sm">No activities scheduled for this date</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                @auth
                <div class="mt-6 bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-bolt mr-2 text-[#0d5c2f]"></i>
                        Quick Actions
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('userServices') }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-[#0d5c2f] text-white rounded-xl hover:bg-[#0d5c2f]/90 transition-colors font-medium shadow-sm">
                            <i class="fas fa-plus mr-2"></i>
                            Book a Service
                        </a>
                        <a href="{{ route('booking.my-bookings') }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium shadow-sm">
                            <i class="fas fa-list mr-2"></i>
                            My Bookings
                        </a>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<script>
let currentMonth = {{ $currentMonth }};
let currentYear = {{ $currentYear }};
let events = @json($events);
let selectedDate = null;

// Helper function to normalize dates - Enhanced to handle timezone issues
function normalizeDate(dateString) {
    if (!dateString) return null;
    
    // Handle different date formats
    let dateToProcess = dateString;
    
    // If it's a full datetime string, we need to be careful about timezone
    if (dateString.includes('T')) {
        // Extract just the date part to avoid timezone conversion
        return dateString.split('T')[0];
    }
    
    // Handle space-separated datetime
    if (dateString.includes(' ')) {
        return dateString.split(' ')[0];
    }
    
    // For date-only strings, return as-is
    if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
        return dateString;
    }
    
    // Try to parse and format consistently
    try {
        const date = new Date(dateString);
        if (!isNaN(date.getTime())) {
            // Use UTC methods to avoid timezone shifts
            const year = date.getUTCFullYear();
            const month = String(date.getUTCMonth() + 1).padStart(2, '0');
            const day = String(date.getUTCDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
    } catch (e) {
        console.error('Error parsing date:', dateString, e);
    }
    
    return dateString;
}

// Helper function to create date string in local timezone
function createLocalDateString(date) {
    // Use local timezone to avoid UTC conversion issues
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Function to generate recurring event instances
function generateRecurringEvents(events) {
    const expandedEvents = [];
    const currentDate = new Date();
    const endDate = new Date();
    endDate.setMonth(endDate.getMonth() + 12); // Generate events for next 12 months
    
    events.forEach(event => {
        // Add the original event
        expandedEvents.push(event);
        
        // Check if it's a recurring event
        if (event.is_recurring && event.recurrence_type) {
            const startDate = new Date(normalizeDate(event.date) + 'T00:00:00');
            let nextDate = new Date(startDate);
            
            // Generate recurring instances
            while (nextDate <= endDate) {
                switch (event.recurrence_type) {
                    case 'weekly':
                        nextDate.setDate(nextDate.getDate() + 7);
                        break;
                    case 'monthly':
                        nextDate.setMonth(nextDate.getMonth() + 1);
                        break;
                    case 'yearly':
                        nextDate.setFullYear(nextDate.getFullYear() + 1);
                        break;
                    default:
                        // Unknown recurrence type, break the loop
                        nextDate = new Date(endDate.getTime() + 1);
                        break;
                }
                
                if (nextDate <= endDate) {
                    // Create a new event instance
                    const recurringEvent = {
                        ...event,
                        date: createLocalDateString(nextDate),
                        id: `${event.id}_recurring_${nextDate.getTime()}`, // Unique ID for recurring instance
                        title: `${event.title} (Recurring)`
                    };
                    expandedEvents.push(recurringEvent);
                }
            }
        }
    });
    
    return expandedEvents;
}

// Initialize calendar
document.addEventListener('DOMContentLoaded', function() {
    // Debug: Log events to verify backend fixes
    console.log('=== CALENDAR DEBUG INFO ===');
    console.log('Calendar Events from backend:', events);
    console.log('Total events count:', events.length);
    
    // Focus on parochial events to verify date and recurring fixes
    const parochialEvents = events.filter(e => e.type === 'parochial');
    console.log('=== PAROCHIAL EVENTS ANALYSIS ===');
    console.log('Parochial events count:', parochialEvents.length);
    
    parochialEvents.forEach((event, index) => {
        console.log(`Parochial Event ${index + 1}:`, {
            title: event.title,
            date: event.date,
            isRecurring: event.is_recurring,
            recurrenceType: event.recurrence_type
        });
    });
    
    // No need to generate recurring events on frontend anymore - backend handles it
    generateCalendar();
});

function changeMonth(direction) {
    currentMonth += direction;
    if (currentMonth > 12) {
        currentMonth = 1;
        currentYear++;
    } else if (currentMonth < 1) {
        currentMonth = 12;
        currentYear--;
    }
    
    updateCalendar();
}

function goToToday() {
    const today = new Date();
    currentMonth = today.getMonth() + 1;
    currentYear = today.getFullYear();
    updateCalendar();
}

function updateCalendar() {
    // Update header
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'];
    document.getElementById('current-month-year').textContent = `${monthNames[currentMonth - 1]} ${currentYear}`;
    
    // Load events for the new month
    loadEventsForMonth();
}

function loadEventsForMonth() {
    fetch(`/api/calendar-events?month=${currentMonth}&year=${currentYear}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Backend now handles recurring events, so just use the events directly
                events = data.events || [];
                console.log(`Events loaded for ${currentMonth}/${currentYear}:`, events);
                
                // Debug parochial events for this month
                const parochialEvents = events.filter(e => e.type === 'parochial');
                console.log(`Parochial events for ${currentMonth}/${currentYear}:`, parochialEvents.map(e => ({
                    title: e.title,
                    date: e.date,
                    isRecurring: e.is_recurring
                })));
            } else {
                console.error('Error loading events:', data.message);
                events = [];
            }
            generateCalendar();
        })
        .catch(error => {
            console.error('Error loading events:', error);
            events = [];
            generateCalendar();
        });
}

function generateCalendar() {
    const firstDay = new Date(currentYear, currentMonth - 1, 1);
    const lastDay = new Date(currentYear, currentMonth, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay());
    
    const calendarGrid = document.getElementById('calendar-grid');
    calendarGrid.innerHTML = '';
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    for (let i = 0; i < 42; i++) {
        const date = new Date(startDate);
        date.setDate(startDate.getDate() + i);
        
        const isCurrentMonth = date.getMonth() === currentMonth - 1;
        const isToday = date.getTime() === today.getTime();
        const dateStr = createLocalDateString(date);
        
        // Get events for this date - fix date comparison to handle timezone issues
        const dayEvents = events.filter(event => {
            const normalizedEventDate = normalizeDate(event.date);
            const match = normalizedEventDate === dateStr;
            
            // Debug logging for parochial activities - show both matches and near misses
            if (event.type === 'parochial') {
                const eventDateObj = new Date(event.date);
                const calendarDateObj = new Date(dateStr + 'T00:00:00');
                const dayDifference = Math.abs(eventDateObj.getTime() - calendarDateObj.getTime()) / (1000 * 60 * 60 * 24);
                
                if (match) {
                    console.log(`✅ Parochial MATCH: ${event.title} - Event: ${event.date} -> Normalized: ${normalizedEventDate} | Calendar: ${dateStr}`);
                } else if (dayDifference <= 1) {
                    console.log(`❌ Parochial NEAR MISS: ${event.title} - Event: ${event.date} -> Normalized: ${normalizedEventDate} | Calendar: ${dateStr} | Diff: ${dayDifference} days`);
                }
            }
            
            return match;
        });
        
        // Determine background color based on event types
        let backgroundColor = '';
        let textColor = '';
        let borderColor = '';
        
        if (isCurrentMonth) {
            if (isToday) {
                backgroundColor = 'bg-[#0d5c2f]';
                textColor = 'text-white';
                borderColor = 'border-[#0d5c2f]';
            } else if (dayEvents.length > 0) {
                // Check event types to determine color
                const hasBooking = dayEvents.some(e => e.type === 'booking');
                const hasParochial = dayEvents.some(e => e.type === 'parochial');
                const hasMinistry = dayEvents.some(e => e.type === 'ministry');
                
                if (hasBooking && hasParochial && hasMinistry) {
                    // Mixed events - gradient background
                    backgroundColor = 'bg-gradient-to-br from-blue-100 via-green-100 to-purple-100';
                    textColor = 'text-gray-900';
                    borderColor = 'border-blue-300';
                } else if (hasBooking && hasParochial) {
                    backgroundColor = 'bg-gradient-to-br from-blue-100 to-green-100';
                    textColor = 'text-gray-900';
                    borderColor = 'border-blue-300';
                } else if (hasBooking && hasMinistry) {
                    backgroundColor = 'bg-gradient-to-br from-blue-100 to-purple-100';
                    textColor = 'text-gray-900';
                    borderColor = 'border-blue-300';
                } else if (hasParochial && hasMinistry) {
                    backgroundColor = 'bg-gradient-to-br from-green-100 to-purple-100';
                    textColor = 'text-gray-900';
                    borderColor = 'border-green-300';
                } else if (hasBooking) {
                    backgroundColor = 'bg-blue-100';
                    textColor = 'text-blue-900';
                    borderColor = 'border-blue-300';
                } else if (hasParochial) {
                    backgroundColor = 'bg-green-100';
                    textColor = 'text-green-900';
                    borderColor = 'border-green-300';
                } else if (hasMinistry) {
                    backgroundColor = 'bg-purple-100';
                    textColor = 'text-purple-900';
                    borderColor = 'border-purple-300';
                } else {
                    backgroundColor = 'bg-white';
                    textColor = 'text-gray-900';
                    borderColor = 'border-gray-200';
                }
            } else {
                backgroundColor = 'bg-white';
                textColor = 'text-gray-900';
                borderColor = 'border-gray-200';
            }
        } else {
            backgroundColor = 'bg-gray-50';
            textColor = 'text-gray-400';
            borderColor = 'border-gray-100';
        }
        
        // Create day element
        const dayElement = document.createElement('div');
        dayElement.className = `relative p-4 text-center cursor-pointer transition-all duration-200 rounded-xl border-2 min-h-[80px] flex flex-col justify-between hover:shadow-md ${backgroundColor} ${textColor} ${borderColor} ${
            isCurrentMonth && !isToday ? 'hover:bg-opacity-80' : ''
        }`;
        
        dayElement.onclick = () => selectDate(dateStr, date);
        
        // Day number
        const dayNumber = document.createElement('div');
        dayNumber.className = `text-lg font-semibold ${isToday ? 'text-white' : ''}`;
        dayNumber.textContent = date.getDate();
        
        // Events indicators
        const eventsContainer = document.createElement('div');
        eventsContainer.className = 'flex flex-wrap gap-1 justify-center mt-1';
        
        const eventTypes = [...new Set(dayEvents.map(e => e.type))];
        eventTypes.slice(0, 3).forEach(type => {
            const indicator = document.createElement('div');
            indicator.className = `w-3 h-3 rounded-full shadow-sm ${
                type === 'booking' ? 'bg-blue-600' :
                type === 'parochial' ? 'bg-green-600' : 'bg-purple-600'
            }`;
            eventsContainer.appendChild(indicator);
        });
        
        if (dayEvents.length > 3) {
            const moreIndicator = document.createElement('div');
            moreIndicator.className = `text-xs font-medium px-1 py-0.5 rounded ${
                isToday ? 'text-white bg-white/20' : 'text-gray-600 bg-gray-200'
            }`;
            moreIndicator.textContent = `+${dayEvents.length - 3}`;
            eventsContainer.appendChild(moreIndicator);
        }
        
        dayElement.appendChild(dayNumber);
        dayElement.appendChild(eventsContainer);
        calendarGrid.appendChild(dayElement);
    }
}

function selectDate(dateStr, dateObj) {
    selectedDate = dateStr;
    
    // Update selected date info
    const selectedDateInfo = document.getElementById('selected-date-info');
    const noDateSelected = document.getElementById('no-date-selected');
    const selectedDay = document.getElementById('selected-day');
    const selectedDateText = document.getElementById('selected-date-text');
    const selectedMonthYear = document.getElementById('selected-month-year');
    
    selectedDay.textContent = dateObj.getDate();
    selectedDateText.textContent = dateObj.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });
    selectedMonthYear.textContent = dateObj.getFullYear();
    
    selectedDateInfo.classList.remove('hidden');
    noDateSelected.classList.add('hidden');
    
    // Show events for selected date
    showEventsForDate(dateStr);
}

function showEventsForDate(dateStr) {
    const dayEvents = events.filter(event => {
        const normalizedEventDate = normalizeDate(event.date);
        return normalizedEventDate === dateStr;
    });
    const eventsList = document.getElementById('events-list');
    const noEvents = document.getElementById('no-events');
    
    if (dayEvents.length === 0) {
        eventsList.classList.add('hidden');
        noEvents.classList.remove('hidden');
        return;
    }
    
    noEvents.classList.add('hidden');
    eventsList.classList.remove('hidden');
    
    eventsList.innerHTML = '';
    
    dayEvents.forEach(event => {
        const eventElement = document.createElement('div');
        eventElement.className = 'p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors';
        
        const typeColors = {
            booking: { bg: 'bg-blue-100', text: 'text-blue-800', border: 'border-blue-200', dot: 'bg-blue-600' },
            parochial: { bg: 'bg-green-100', text: 'text-green-800', border: 'border-green-200', dot: 'bg-green-600' },
            ministry: { bg: 'bg-purple-100', text: 'text-purple-800', border: 'border-purple-200', dot: 'bg-purple-600' }
        };
        
        const colors = typeColors[event.type] || typeColors.booking;
        
        eventElement.innerHTML = `
            <div class="flex items-start space-x-3">
                <div class="w-3 h-3 rounded-full mt-2 ${colors.dot}"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="font-semibold text-gray-900 text-sm">${event.title}</h4>
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${colors.bg} ${colors.text} ${colors.border} border">
                            ${event.type.charAt(0).toUpperCase() + event.type.slice(1)}
                        </span>
                    </div>
                    <div class="space-y-1 text-xs text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2 text-gray-400"></i>
                            ${event.time}
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                            ${event.location}
                        </div>
                        ${event.description ? `<div class="mt-2 text-gray-700">${event.description}</div>` : ''}
                    </div>
                </div>
            </div>
        `;
        
        eventsList.appendChild(eventElement);
    });
}
</script>
@endsection
