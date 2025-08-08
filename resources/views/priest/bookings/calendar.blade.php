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
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-calendar-check mr-2 text-[#0d5c2f]"></i>
                        My Bookings
                    </h4>
                    <div id="bookingsList" class="space-y-3">
                        <!-- Bookings will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Activities Section -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-calendar-day mr-2 text-[#0d5c2f]"></i>
                        Parish Activities
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
let currentDate = new Date();
let bookings = @json($bookings);
let activities = @json($activities);

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    // Update header
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                       'July', 'August', 'September', 'October', 'November', 'December'];
    document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
    
    // Get first day of month and number of days
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay());
    
    const calendarDays = document.getElementById('calendarDays');
    calendarDays.innerHTML = '';
    
    // Generate calendar days
    for (let i = 0; i < 42; i++) {
        const date = new Date(startDate);
        date.setDate(startDate.getDate() + i);
        
        const dayElement = document.createElement('div');
        dayElement.className = 'min-h-[100px] p-2 border border-gray-200 bg-white hover:bg-gray-50 transition-colors cursor-pointer';
        
        // Check if date is in current month
        if (date.getMonth() !== month) {
            dayElement.classList.add('bg-gray-50', 'text-gray-400');
        }
        
        // Check if date is today
        const today = new Date();
        if (date.toDateString() === today.toDateString()) {
            dayElement.classList.add('bg-blue-50', 'border-blue-300');
        }
        
        const dayNumber = document.createElement('div');
        dayNumber.className = 'text-sm font-medium mb-1';
        dayNumber.textContent = date.getDate();
        dayElement.appendChild(dayNumber);
        
        // Add events for this date
        const eventsContainer = document.createElement('div');
        eventsContainer.className = 'space-y-1';
        
        // Add bookings
        const dateBookings = bookings.filter(booking => {
            const bookingDate = new Date(booking.service_date);
            return bookingDate.toDateString() === date.toDateString();
        });
        
        dateBookings.forEach(booking => {
            const eventElement = document.createElement('div');
            eventElement.className = 'text-xs p-1 rounded cursor-pointer';
            
            let bgColor, borderColor;
            switch(booking.status) {
                case 'pending':
                    bgColor = 'rgba(251, 191, 36, 0.25)';
                    borderColor = 'rgba(251, 191, 36, 0.6)';
                    break;
                case 'acknowledged':
                    bgColor = 'rgba(59, 130, 246, 0.25)';
                    borderColor = 'rgba(59, 130, 246, 0.6)';
                    break;
                case 'payment_hold':
                    bgColor = 'rgba(249, 115, 22, 0.25)';
                    borderColor = 'rgba(249, 115, 22, 0.6)';
                    break;
                case 'approved':
                    bgColor = 'rgba(16, 185, 129, 0.25)';
                    borderColor = 'rgba(16, 185, 129, 0.6)';
                    break;
                case 'rejected':
                    bgColor = 'rgba(239, 68, 68, 0.25)';
                    borderColor = 'rgba(239, 68, 68, 0.6)';
                    break;
                case 'completed':
                    bgColor = 'rgba(5, 150, 105, 0.25)';
                    borderColor = 'rgba(5, 150, 105, 0.6)';
                    break;
                default:
                    bgColor = 'rgba(156, 163, 175, 0.25)';
                    borderColor = 'rgba(156, 163, 175, 0.6)';
            }
            
            eventElement.style.backgroundColor = bgColor;
            eventElement.style.border = `1px solid ${borderColor}`;
            eventElement.textContent = `#${booking.id} - ${booking.service.name}`;
            eventElement.onclick = () => showEventsForDate(date);
            
            eventsContainer.appendChild(eventElement);
        });
        
        // Add activities
        const dateActivities = activities.filter(activity => {
            const activityStart = new Date(activity.start_date);
            const activityEnd = new Date(activity.end_date);
            return date >= activityStart && date <= activityEnd;
        });
        
        dateActivities.forEach(activity => {
            const eventElement = document.createElement('div');
            eventElement.className = 'text-xs p-1 rounded cursor-pointer';
            eventElement.style.backgroundColor = 'rgba(251, 191, 36, 0.25)';
            eventElement.style.border = '1px solid rgba(251, 191, 36, 0.6)';
            eventElement.textContent = activity.title;
            eventElement.onclick = () => showEventsForDate(date);
            
            eventsContainer.appendChild(eventElement);
        });
        
        dayElement.appendChild(eventsContainer);
        dayElement.onclick = () => showEventsForDate(date);
        
        calendarDays.appendChild(dayElement);
    }
}

function showEventsForDate(date) {
    const dateString = date.toDateString();
    const dateTitle = date.toLocaleDateString('en-US', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    document.getElementById('selectedDateTitle').textContent = `Events for ${dateTitle}`;
    
    // Populate bookings
    const dateBookings = bookings.filter(booking => {
        const bookingDate = new Date(booking.service_date);
        return bookingDate.toDateString() === dateString;
    });
    
    const bookingsList = document.getElementById('bookingsList');
    if (dateBookings.length > 0) {
        bookingsList.innerHTML = dateBookings.map(booking => `
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="flex items-center justify-between mb-2">
                    <h5 class="font-medium text-gray-900">#${booking.id} - ${booking.service.name}</h5>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                        ${booking.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                          booking.status === 'acknowledged' ? 'bg-blue-100 text-blue-800' :
                          booking.status === 'payment_hold' ? 'bg-orange-100 text-orange-800' :
                          booking.status === 'approved' ? 'bg-green-100 text-green-800' :
                          booking.status === 'completed' ? 'bg-purple-100 text-purple-800' :
                          'bg-red-100 text-red-800'}">
                        ${booking.status.replace('_', ' ')}
                    </span>
                </div>
                <p class="text-sm text-gray-600">${booking.user.name}</p>
                <p class="text-sm text-gray-500">${booking.formatted_time}</p>
                <a href="/priest/bookings/${booking.id}" class="text-[#0d5c2f] hover:text-[#0d5c2f]/80 text-sm font-medium">
                    View Details →
                </a>
            </div>
        `).join('');
    } else {
        bookingsList.innerHTML = '<p class="text-gray-500 text-sm">No bookings for this date</p>';
    }
    
    // Populate activities
    const dateActivities = activities.filter(activity => {
        const activityStart = new Date(activity.start_date);
        const activityEnd = new Date(activity.end_date);
        return date >= activityStart && date <= activityEnd;
    });
    
    const activitiesList = document.getElementById('activitiesList');
    if (dateActivities.length > 0) {
        activitiesList.innerHTML = dateActivities.map(activity => `
            <div class="bg-yellow-50 rounded-lg p-3">
                <h5 class="font-medium text-gray-900">${activity.title}</h5>
                <p class="text-sm text-gray-600">${activity.description || 'No description'}</p>
                <p class="text-sm text-gray-500">${activity.location || 'No location'}</p>
            </div>
        `).join('');
    } else {
        activitiesList.innerHTML = '<p class="text-gray-500 text-sm">No activities for this date</p>';
    }
    
    document.getElementById('eventsSection').classList.remove('hidden');
}

function hideEventsSection() {
    document.getElementById('eventsSection').classList.add('hidden');
}

function changeMonth(direction) {
    if (direction === 'prev') {
        currentDate.setMonth(currentDate.getMonth() - 1);
    } else {
        currentDate.setMonth(currentDate.getMonth() + 1);
    }
    renderCalendar();
}

// Event listeners
document.getElementById('prevMonth').addEventListener('click', () => changeMonth('prev'));
document.getElementById('nextMonth').addEventListener('click', () => changeMonth('next'));

// Initialize calendar
document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();
});
</script>
@endsection 