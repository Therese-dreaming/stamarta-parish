@extends('layouts.ministry')

@section('title', 'Calendar')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Ministry Calendar</h1>
                    <p class="text-white/80 mt-1">View and manage your ministry activities and church events</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('ministry.activities.create') }}" 
                       class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="New Activity">
                        <i class="fas fa-plus text-lg"></i>
                    </a>
                    <a href="{{ route('ministry.activities.index') }}" 
                       class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Back to Activities">
                        <i class="fas fa-list text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-calendar-star text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $ministryActivities->count() }}</p>
                    <p class="text-sm text-gray-500">Activities</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-eye text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Public Events</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $ministryActivities->where('is_public', true)->count() }}</p>
                    <p class="text-sm text-gray-500">This Month</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-dollar-sign text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Budget Allocated</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($ministryActivities->sum(function($activity) { return $activity->approvedBudgetRequest?->amount ?? 0; }), 2) }}</p>
                    <p class="text-sm text-gray-500">This Month</p>
                </div>
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
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(139, 92, 246, 0.25); border-color: rgba(139, 92, 246, 0.6);"></div>
                        <span class="text-sm text-gray-600">Ministry Activities</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(59, 130, 246, 0.25); border-color: rgba(59, 130, 246, 0.6);"></div>
                        <span class="text-sm text-gray-600">Parochial Activities</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2 border-2" style="background-color: rgba(16, 185, 129, 0.25); border-color: rgba(16, 185, 129, 0.6);"></div>
                        <span class="text-sm text-gray-600">Church Services</span>
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
    <div id="eventsSection" class="bg-white rounded-2xl shadow-2xl border border-gray-100 hidden overflow-hidden">
        <!-- Enhanced Header with Gradient and Animation -->
        <div class="bg-gradient-to-r from-[#0d5c2f] via-[#0d5c2f] to-[#0a4a26] relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-pulse"></div>
            <div class="relative px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-calendar-day text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 id="selectedDateTitle" class="text-2xl font-bold text-white">Events for Selected Date</h3>
                            <p class="text-white/80 text-sm mt-1 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                View all scheduled events and activities
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div id="eventsSummary" class="hidden text-white/90 text-sm bg-white/10 px-4 py-2 rounded-xl backdrop-blur-sm">
                            <span id="totalEventsCount">0</span> events
                        </div>
                        <button onclick="hideEventsSection()" class="w-10 h-10 text-white/80 hover:text-white hover:bg-white/20 rounded-xl transition-all duration-200 flex items-center justify-center group">
                            <i class="fas fa-times text-lg group-hover:rotate-90 transition-transform duration-200"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Enhanced Content Area -->
        <div class="p-8 bg-gradient-to-br from-gray-50/50 to-white">
            <!-- Quick Stats Bar -->
            <div id="quickStats" class="mb-8 grid grid-cols-3 gap-4">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-4 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Ministry</p>
                            <p id="ministryCount" class="text-2xl font-bold">0</p>
                        </div>
                        <i class="fas fa-users text-purple-200 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-4 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Parochial</p>
                            <p id="parochialCount" class="text-2xl font-bold">0</p>
                        </div>
                        <i class="fas fa-church text-blue-200 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-4 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Services</p>
                            <p id="servicesCount" class="text-2xl font-bold">0</p>
                        </div>
                        <i class="fas fa-calendar-check text-green-200 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Enhanced Tabs Navigation -->
            <div class="mb-8">
                <div class="flex space-x-1 bg-gray-100 p-1 rounded-2xl">
                    <button onclick="switchEventTab('ministry')" id="ministryTab" class="flex-1 flex items-center justify-center px-6 py-3 text-sm font-medium rounded-xl transition-all duration-200 bg-white text-gray-900 shadow-sm">
                        <i class="fas fa-users mr-2 text-purple-500"></i>
                        Ministry Activities
                    </button>
                    <button onclick="switchEventTab('parochial')" id="parochialTab" class="flex-1 flex items-center justify-center px-6 py-3 text-sm font-medium rounded-xl transition-all duration-200 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-church mr-2 text-blue-500"></i>
                        Parochial Activities
                    </button>
                    <button onclick="switchEventTab('services')" id="servicesTab" class="flex-1 flex items-center justify-center px-6 py-3 text-sm font-medium rounded-xl transition-all duration-200 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-calendar-check mr-2 text-green-500"></i>
                        Church Services
                    </button>
                </div>
            </div>

            <!-- Enhanced Content Sections -->
            <div class="min-h-[400px]">
                <!-- Ministry Activities Section -->
                <div id="ministryContent" class="event-tab-content">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Ministry Activities</h4>
                                <p class="text-gray-500">Your ministry events and programs</p>
                            </div>
                        </div>
                        <a href="{{ route('ministry.activities.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-500 text-white text-sm font-medium rounded-xl hover:bg-purple-600 transition-colors shadow-lg">
                            <i class="fas fa-plus mr-2"></i>New Activity
                        </a>
                    </div>
                    <div id="ministryActivitiesList" class="space-y-4">
                        <!-- Ministry Activities will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Parochial Activities Section -->
                <div id="parochialContent" class="event-tab-content hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-church text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Parochial Activities</h4>
                                <p class="text-gray-500">Church-wide events and celebrations</p>
                            </div>
                        </div>
                    </div>
                    <div id="parochialActivitiesList" class="space-y-4">
                        <!-- Parochial Activities will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Church Services Section -->
                <div id="servicesContent" class="event-tab-content hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-calendar-check text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Church Services</h4>
                                <p class="text-gray-500">Service bookings and appointments</p>
                            </div>
                        </div>
                    </div>
                    <div id="bookingsList" class="space-y-4">
                        <!-- Bookings will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
class MinistryCalendar {
    constructor(container, ministryActivities, parochialActivities, bookings) {
        this.container = container;
        this.ministryActivities = ministryActivities;
        this.parochialActivities = parochialActivities;
        this.bookings = bookings;
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
        
        // Add ministry activities
        const dateMinistryActivities = this.ministryActivities.filter(activity => {
            const activityStart = new Date(activity.start_at);
            const activityEnd = activity.end_at ? new Date(activity.end_at) : activityStart;
            const checkDate = new Date(dateString);
            return checkDate >= activityStart.setHours(0,0,0,0) && checkDate <= activityEnd.setHours(23,59,59,999);
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
            if (activity.is_recurring) {
                const recurringPattern = activity.recurring_pattern;
                if (recurringPattern && recurringPattern.type === 'weekly') {
                    const checkDate = new Date(dateString);
                    return checkDate.getDay() === recurringPattern.day_of_week;
                }
            } else {
                const activityDate = new Date(activity.event_date);
                return this.formatDateForComparison(activityDate) === dateString;
            }
            return false;
        });
        
        dateParochialActivities.forEach(activity => {
            events.push({
                type: 'parochial_activity',
                id: activity.id,
                title: activity.title,
                backgroundColor: 'rgba(59, 130, 246, 0.25)',
                activity: activity
            });
        });
        
        // Add bookings
        const dateBookings = this.bookings.filter(booking => {
            const bookingDate = new Date(booking.service_date);
            return this.formatDateForComparison(bookingDate) === dateString;
        });
        
        dateBookings.forEach(booking => {
            events.push({
                type: 'booking',
                id: booking.id,
                title: `${booking.service.name} - ${booking.user.name}`,
                backgroundColor: 'rgba(16, 185, 129, 0.25)',
                booking: booking
            });
        });
        
        return events;
    }

    getEventColor(events) {
        if (events.length === 0) return '#6b7280'; // Gray
        
        if (events.length === 1) {
            return events[0].backgroundColor;
        }
        
        // Multiple events - check if they're all the same type
        const uniqueTypes = [...new Set(events.map(e => e.type))];
        
        if (uniqueTypes.length === 1) {
            if (uniqueTypes[0] === 'ministry_activity') {
                return 'rgba(139, 92, 246, 0.25)'; // Purple for ministry activities
            } else if (uniqueTypes[0] === 'parochial_activity') {
                return 'rgba(59, 130, 246, 0.25)'; // Blue for parochial activities
            } else if (uniqueTypes[0] === 'booking') {
                return 'rgba(16, 185, 129, 0.25)'; // Green for bookings
            }
        }
        
        return 'rgba(107, 114, 128, 0.25)'; // Gray for mixed events
    }

    showDayEvents(dateString, events) {
        const eventsSection = document.getElementById('eventsSection');
        const selectedDateTitle = document.getElementById('selectedDateTitle');
        
        // Fix timezone issue by creating date properly
        const displayDate = new Date(dateString + 'T00:00:00');
        selectedDateTitle.textContent = `Events for ${displayDate.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        })}`;
        
        // Separate events by type
        const ministryActivities = events.filter(event => event.type === 'ministry_activity');
        const parochialActivities = events.filter(event => event.type === 'parochial_activity');
        const bookings = events.filter(event => event.type === 'booking');
        
        // Update quick stats
        document.getElementById('ministryCount').textContent = ministryActivities.length;
        document.getElementById('parochialCount').textContent = parochialActivities.length;
        document.getElementById('servicesCount').textContent = bookings.length;
        
        // Update total events count
        const totalEvents = events.length;
        document.getElementById('totalEventsCount').textContent = totalEvents;
        document.getElementById('eventsSummary').classList.toggle('hidden', totalEvents === 0);
        
        // Populate sections with enhanced cards
        this.populateEnhancedSection('ministryActivitiesList', ministryActivities, 'ministry');
        this.populateEnhancedSection('parochialActivitiesList', parochialActivities, 'parochial');
        this.populateEnhancedSection('bookingsList', bookings, 'booking');
        
        // Show the events section
        eventsSection.classList.remove('hidden');
        
        // Scroll to events section
        eventsSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    populateEnhancedSection(containerId, events, type) {
        const container = document.getElementById(containerId);
        if (events.length === 0) {
            const emptyStates = {
                ministry: {
                    icon: 'fas fa-users',
                    title: 'No Ministry Activities',
                    message: 'No ministry activities scheduled for this date',
                    color: 'purple'
                },
                parochial: {
                    icon: 'fas fa-church',
                    title: 'No Parochial Activities',
                    message: 'No church activities scheduled for this date',
                    color: 'blue'
                },
                booking: {
                    icon: 'fas fa-calendar-check',
                    title: 'No Church Services',
                    message: 'No service bookings scheduled for this date',
                    color: 'green'
                }
            };
            
            const state = emptyStates[type];
            container.innerHTML = `
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gradient-to-br from-${state.color}-100 to-${state.color}-50 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="${state.icon} text-${state.color}-400 text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">${state.title}</h4>
                    <p class="text-gray-500 max-w-sm mx-auto">${state.message}</p>
                </div>
            `;
        } else {
            let html = '';
            events.forEach(event => {
                const item = event.activity || event.booking;
                html += this.createEnhancedEventCard(event, item, type);
            });
            container.innerHTML = html;
        }
    }

    createEnhancedEventCard(event, item, type) {
        const typeConfig = {
            ministry: {
                color: 'purple',
                bgGradient: 'from-purple-500 to-purple-600',
                lightBg: 'bg-purple-50',
                textColor: 'text-purple-600',
                borderColor: 'border-purple-200',
                actionUrl: `/ministry/activities/${item.id}`,
                actionText: 'View Details'
            },
            parochial: {
                color: 'blue',
                bgGradient: 'from-blue-500 to-blue-600',
                lightBg: 'bg-blue-50',
                textColor: 'text-blue-600',
                borderColor: 'border-blue-200',
                actionUrl: '#',
                actionText: 'View Info'
            },
            booking: {
                color: 'green',
                bgGradient: 'from-green-500 to-green-600',
                lightBg: 'bg-green-50',
                textColor: 'text-green-600',
                borderColor: 'border-green-200',
                actionUrl: '#',
                actionText: 'View Booking'
            }
        };

        const config = typeConfig[type];
        const title = item.title || item.service?.name || 'Event';
        const description = item.description || item.user?.name || '';
        const time = type === 'ministry' ? this.formatDateTime(item.start_at) : (type === 'booking' ? item.service_time : '');
        const location = item.location || '';
        const isPublic = type === 'ministry' ? item.is_public : null;
        const status = type === 'booking' ? item.status : (type === 'ministry' ? item.status : null);

        return `
            <div class="group bg-white rounded-2xl shadow-sm border ${config.borderColor} overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-3 h-3 bg-gradient-to-r ${config.bgGradient} rounded-full"></div>
                                <h5 class="text-lg font-bold text-gray-900 group-hover:${config.textColor} transition-colors">${title}</h5>
                            </div>
                            ${description ? `<p class="text-gray-600 text-sm mb-3">${description}</p>` : ''}
                        </div>
                        ${status ? `
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${this.getStatusBadgeClass(status)}">
                                ${status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ')}
                            </span>
                        ` : ''}
                        ${isPublic !== null ? `
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${isPublic ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                ${isPublic ? 'Public' : 'Internal'}
                            </span>
                        ` : ''}
                    </div>
                    
                    <div class="space-y-2 mb-4">
                        ${time ? `
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock w-4 h-4 mr-3 ${config.textColor}"></i>
                                <span>${time}</span>
                            </div>
                        ` : ''}
                        ${location ? `
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt w-4 h-4 mr-3 ${config.textColor}"></i>
                                <span>${location}</span>
                            </div>
                        ` : ''}
                        ${type === 'booking' && item.user ? `
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-user w-4 h-4 mr-3 ${config.textColor}"></i>
                                <span>${item.user.name}</span>
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center text-xs text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>
                            <span>${type === 'ministry' ? 'Activity' : type === 'parochial' ? 'Church Event' : 'Booking'} #${item.id}</span>
                        </div>
                        ${config.actionUrl !== '#' ? `
                            <a href="${config.actionUrl}" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r ${config.bgGradient} text-white text-sm font-medium rounded-xl hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-eye mr-2"></i>${config.actionText}
                            </a>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
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

    formatDateTime(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
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
                this.displayedMonth.setMonth(this.displayedMonth.getMonth() - 1);
                this.renderCalendar();
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', (e) => {
                e.preventDefault();
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
        const ministryActivities = @json($ministryActivities);
        const parochialActivities = @json($parochialActivities ?? []);
        const bookings = @json($bookings ?? []);
        
        const calendar = new MinistryCalendar(
            calendarContainer,
            ministryActivities,
            parochialActivities,
            bookings
        );
    }
});

function hideEventsSection() {
    document.getElementById('eventsSection').classList.add('hidden');
}

function switchEventTab(tabName) {
    // Hide all content sections
    document.querySelectorAll('.event-tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('[id$="Tab"]').forEach(tab => {
        tab.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
        tab.classList.add('text-gray-600', 'hover:text-gray-900');
    });
    
    // Show selected content
    const contentMap = {
        'ministry': 'ministryContent',
        'parochial': 'parochialContent', 
        'services': 'servicesContent'
    };
    
    const tabMap = {
        'ministry': 'ministryTab',
        'parochial': 'parochialTab',
        'services': 'servicesTab'
    };
    
    document.getElementById(contentMap[tabName]).classList.remove('hidden');
    
    // Activate selected tab
    const activeTab = document.getElementById(tabMap[tabName]);
    activeTab.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
    activeTab.classList.remove('text-gray-600', 'hover:text-gray-900');
}
</script>
@endpush
