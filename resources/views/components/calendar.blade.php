@props(['approvedBookings', 'selectedDate', 'service'])

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
        </div>
    </div>
</div>

<script>
class Calendar {
    constructor(container, approvedBookings, selectedDate, service) {
        console.log('Calendar constructor called');
        console.log('Container:', container);
        console.log('Approved bookings:', approvedBookings);
        console.log('Selected date:', selectedDate);
        console.log('Service:', service);
        
        this.container = container;
        this.approvedBookings = approvedBookings;
        this.selectedDate = selectedDate;
        this.service = service;
        this.currentDate = new Date();
        this.displayedMonth = new Date();
        
        this.init();
    }

    init() {
        console.log('Calendar init called');
        this.renderCalendar();
        this.attachEventListeners();
    }

    renderCalendar() {
        console.log('renderCalendar called');
        const year = this.displayedMonth.getFullYear();
        const month = this.displayedMonth.getMonth();
        
        console.log('Year:', year, 'Month:', month);
        
        // Update header
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December'];
        const currentMonthElement = document.getElementById('currentMonth');
        if (currentMonthElement) {
            currentMonthElement.textContent = `${monthNames[month]} ${year}`;
        } else {
            console.error('currentMonth element not found');
        }

        // Get first day of month and number of days
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());

        const daysContainer = document.getElementById('calendarDays');
        if (!daysContainer) {
            console.error('calendarDays element not found');
            return;
        }
        
        daysContainer.innerHTML = '';
        console.log('Generating calendar days...');

        // Generate calendar days
        for (let i = 0; i < 42; i++) {
            const currentDate = new Date(startDate);
            currentDate.setDate(startDate.getDate() + i);
            
            const dayElement = this.createDayElement(currentDate, year, month);
            daysContainer.appendChild(dayElement);
        }
        
        console.log('Calendar rendering complete');
    }

    createDayElement(date, targetYear, targetMonth) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'calendar-day text-center py-4 px-2 cursor-pointer transition-colors text-lg font-medium';
        
        const dayNumber = date.getDate();
        const isCurrentMonth = date.getMonth() === targetMonth;
        const dateString = date.toISOString().split('T')[0];
        
        // Set initial classes based on basic availability
        if (!isCurrentMonth) {
            dayDiv.className += ' text-gray-300 cursor-not-allowed';
        } else {
            // Check if date is in the past
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const selectedDate = new Date(dateString);
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
                    const availability = this.getDateAvailability(dateString);
                    this.updateDayClasses(dayDiv, availability);
                    
                    // Add click handler for available dates
                    if (availability.status === 'available') {
                        dayDiv.addEventListener('click', () => this.selectDate(dateString));
                    }
                    
                    // Add tooltip
                    if (availability.available_slots !== undefined) {
                        dayDiv.title = `${availability.available_slots} of ${availability.total_slots} slots available`;
                    }
                }
            }
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
        const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        const date = new Date(dateString);
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
        
        // Get booked slots for the day (count per time slot)
        const bookedSlots = this.approvedBookings.filter(booking => 
            booking.service_date === dateString && 
            ['pending', 'confirmed'].includes(booking.status)
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
            return {
                status: 'available',
                total_slots: totalSlots,
                available_slots: availableSlots,
                booked_slots: bookedSlots
            };
        }
    }

    updateDayClasses(dayElement, availability) {
        const baseClasses = 'calendar-day text-center py-4 px-2 cursor-pointer transition-colors text-lg font-medium';
        
        if (availability.status === 'not-available') {
            dayElement.className = baseClasses + ' text-gray-400 cursor-not-allowed bg-gray-100';
        } else if (availability.status === 'fully-booked') {
            dayElement.className = baseClasses + ' text-white bg-red-200 border-2 border-red-400 cursor-not-allowed';
        } else if (availability.status === 'available') {
            dayElement.className = baseClasses + ' text-gray-700 bg-white border-2 border-gray-300 hover:bg-gray-50 hover:border-[#0d5c2f]';
        }
    }

    selectDate(dateString) {
        console.log('Selecting date:', dateString);
        
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

        let timeSlotsHTML = '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">';
        
        data.timeSlots.forEach(slot => {
            const isAvailable = slot.available_slots > 0;
            timeSlotsHTML += `
                <button type="button" 
                        class="time-slot-btn p-4 text-center rounded-lg border transition-colors ${isAvailable ? 'border-gray-300 hover:border-[#0d5c2f] hover:bg-gray-50' : 'border-red-300 bg-red-50 text-gray-500 cursor-not-allowed'}"
                        data-time="${slot.time}"
                        ${!isAvailable ? 'disabled' : ''}>
                    <div class="font-semibold">${slot.time}</div>
                    <div class="text-sm text-gray-600">
                        ${isAvailable ? `${slot.available_slots} of ${slot.total_slots} slots available` : 'Fully booked'}
                    </div>
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
    console.log('Calendar initialization started');
    const calendarContainer = document.querySelector('.calendar-container');
    console.log('Calendar container:', calendarContainer);
    
    if (calendarContainer) {
        console.log('Approved bookings:', @json($approvedBookings));
        console.log('Selected date:', @json($selectedDate));
        console.log('Service:', @json($service));
        
        const calendar = new Calendar(
            calendarContainer,
            @json($approvedBookings),
            @json($selectedDate),
            @json($service)
        );
        
        // Attach time slot listeners if there are existing time slots
        calendar.attachTimeSlotListeners();
    } else {
        console.error('Calendar container not found');
    }
});
</script> 