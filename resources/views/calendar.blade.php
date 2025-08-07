@extends('layouts.user')

@section('title', 'Select Date - ' . ucfirst($serviceType))

@section('content')
<div class="bg-white min-h-screen py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Step Indicator -->
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-[#0d5c2f] text-white flex items-center justify-center">1</div>
                        <div class="ml-2 text-[#0d5c2f] font-medium">Details</div>
                    </div>
                    <div class="w-16 h-1 bg-[#0d5c2f]"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-[#0d5c2f] text-white flex items-center justify-center">2</div>
                        <div class="ml-2 text-[#0d5c2f] font-medium">Calendar</div>
                    </div>
                    <div class="w-16 h-1 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center">3</div>
                        <div class="ml-2 text-gray-600 font-medium">Documents</div>
                    </div>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-[#0d5c2f] mb-8">Select Date for {{ ucfirst(str_replace('_', ' ', $serviceType)) }}</h1>

            <!-- Rest of the existing calendar code remains unchanged -->
            <div class="bg-white rounded-3xl p-8 shadow-xl">
                <!-- Calendar Navigation -->
                <div class="flex items-center justify-between mb-8">
                    <button onclick="previousMonth()" class="text-[#0d5c2f] hover:bg-[#0d5c2f]/10 p-2 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800" id="currentMonth"></h2>
                    <button onclick="nextMonth()" class="text-[#0d5c2f] hover:bg-[#0d5c2f]/10 p-2 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <div class="mb-6 grid grid-cols-2 gap-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-lg bg-yellow-100 border border-yellow-300 mr-2"></div>
                        <span class="text-gray-600">Catholic Holiday</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-lg bg-red-100 border border-red-300 mr-2"></div>
                        <span class="text-gray-600">Occupied</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-lg bg-green-100 border-2 border-green-500 mr-2"></div>
                        <span class="text-gray-600">Selected Date</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-lg bg-white border border-gray-300 mr-2"></div>
                        <span class="text-gray-600">Available Date</span>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-2 mb-4">
                    <div class="text-center font-medium text-gray-600">Sun</div>
                    <div class="text-center font-medium text-gray-600">Mon</div>
                    <div class="text-center font-medium text-gray-600">Tue</div>
                    <div class="text-center font-medium text-gray-600">Wed</div>
                    <div class="text-center font-medium text-gray-600">Thu</div>
                    <div class="text-center font-medium text-gray-600">Fri</div>
                    <div class="text-center font-medium text-gray-600">Sat</div>
                </div>
                <div id="calendarGrid" class="grid grid-cols-7 gap-2"></div>

                <!-- Time Slots (shown when date is selected) -->
                <div id="timeSlots" class="hidden mt-8 border-t pt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Available Time Slots</h3>
                    <div id="timeSlotsGrid" class="grid grid-cols-3 gap-4"></div>
                </div>

                <!-- Continue Button -->
                <div class="mt-8 flex justify-between items-center">
                    <a href="{{ route('services.book', ['service_type' => session('booking_step1.service_type')]) }}" class="text-gray-600 hover:text-gray-800 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Details
                    </a>
                    <form id="dateTimeForm" action="{{ in_array($serviceType, ['mass_intention', 'blessing', 'sick_call']) ? route('services.finalize') : route('services.store-step2') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="service_type" value="{{ $serviceType }}">
                        <input type="hidden" name="selected_date" id="selectedDate">
                        <input type="hidden" name="selected_time" id="selectedTime">
                        <input type="hidden" name="step" value="2">
                        <input type="hidden" name="understood" value="1">
                        <input type="hidden" name="from_calendar" value="1">
                        <button type="submit" disabled id="continueBtn" class="bg-[#18421F] text-white px-8 py-3 rounded-lg hover:bg-[#18421F]/90 disabled:opacity-50 disabled:cursor-not-allowed">
                            @if(in_array($serviceType, ['mass_intention', 'blessing', 'sick_call']))
                                Create Booking
                            @else
                                Continue to Requirements
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedDate = null;
    let selectedTimeSlot = null;
    const serviceType = '{{ $serviceType }}';

    // Service-specific rules
    const serviceRules = {
        baptism: {
            group: {
                days: [0], // Sunday only
                times: ['10:00']
            }
            , solo: {
                days: [2, 3, 4, 5, 6], // Tuesday to Saturday
                times: ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00']
            }
        }
        , wedding: {
            days: [0], // Sunday only
            times: ['14:00'] // 2 PM
        }
        , mass_intention: {
            days: [0, 1, 2, 3, 4, 5, 6], // All days
            times: ['06:30', '08:00', '17:00']
        }
        , blessing: {
            days: [1, 2, 3, 4, 5, 6], // Monday to Saturday
            times: ['09:00', '10:00', '14:00', '15:00']
        }
        , confirmation: {
            days: [6], // Saturday only
            times: ['10:00']
        }
        , sick_call: {
            days: [0, 1, 2, 3, 4, 5, 6], // All days (emergency service)
            times: ['08:00', '09:00', '10:00', '14:00', '15:00', '16:00']
        }
    };

    // Add these functions before the isDateAvailable function
    function getEasterSunday(year) {
        const a = year % 19;
        const b = Math.floor(year / 100);
        const c = year % 100;
        const d = Math.floor(b / 4);
        const e = b % 4;
        const f = Math.floor((b + 8) / 25);
        const g = Math.floor((b - f + 1) / 3);
        const h = (19 * a + b - d - g + 15) % 30;
        const i = Math.floor(c / 4);
        const k = c % 4;
        const l = (32 + 2 * e + 2 * i - h - k) % 7;
        const m = Math.floor((a + 11 * h + 22 * l) / 451);
        const month = Math.floor((h + l - 7 * m + 114) / 31) - 1;
        const day = ((h + l - 7 * m + 114) % 31) + 1;
        return new Date(year, month, day);
    }

    function getCatholicHolidays(year) {
        const easter = getEasterSunday(year);

        // Calculate Easter-related dates
        const ashWednesday = new Date(easter);
        ashWednesday.setDate(easter.getDate() - 46);

        const palmSunday = new Date(easter);
        palmSunday.setDate(easter.getDate() - 7);

        const holyThursday = new Date(easter);
        holyThursday.setDate(easter.getDate() - 3);

        const goodFriday = new Date(easter);
        goodFriday.setDate(easter.getDate() - 2);

        const holySaturday = new Date(easter);
        holySaturday.setDate(easter.getDate() - 1);

        return [
            // Fixed dates (recurring every year)
            new Date(year, 0, 1), // New Year's Day/Solemnity of Mary
            new Date(year, 0, 6), // Epiphany
            new Date(year, 2, 19), // St. Joseph's Day
            new Date(year, 7, 15), // Assumption of Mary
            new Date(year, 10, 1), // All Saints' Day
            new Date(year, 11, 8), // Immaculate Conception
            new Date(year, 11, 25), // Christmas

            // Movable dates (based on Easter)
            ashWednesday
            , palmSunday
            , holyThursday
            , goodFriday
            , holySaturday
            , easter
        ];
    }

    // Add this at the beginning of the script section, after the serviceType declaration
    const approvedBookings = @json($approvedBookings ?? []); // We'll pass this from the controller
    
    // Update the isDateAvailable function
    function isDateAvailable(date) {
        // Check if it's a Catholic holiday
        const holidays = getCatholicHolidays(date.getFullYear());
        const isHoliday = holidays.some(holiday =>
            holiday.getDate() === date.getDate() &&
            holiday.getMonth() === date.getMonth() &&
            holiday.getFullYear() === date.getFullYear()
        );
    
        if (isHoliday) {
            return false;
        }
    
        // Check if all time slots for this date are occupied
        const dateString = date.toISOString().split('T')[0];
        const baptismType = '{{ session("booking_step1.baptism_type") }}';
        
        // Get available time slots for this service
        const rules = serviceType === 'baptism' ?
            (baptismType === 'group' ? serviceRules.baptism.group : serviceRules.baptism.solo) :
            serviceRules[serviceType];

        // Get occupied time slots for this date
        const occupiedTimes = approvedBookings
            .filter(booking => 
                booking.preferred_date === dateString && 
                booking.status === 'approved'
            )
            .map(booking => booking.preferred_time);

        // Check if all time slots are occupied
        const availableSlots = rules.times.filter(time => !occupiedTimes.includes(time));
        if (availableSlots.length === 0) {
            return false;
        }
    
        return rules.days.includes(date.getDay());
    }

    // Update the getAvailableTimeSlots function
    function getAvailableTimeSlots(date) {
        const baptismType = '{{ session("booking_step1.baptism_type") }}'; 
        const rules = serviceType === 'baptism' ?
            (baptismType === 'group' ?
                serviceRules.baptism.group :
                serviceRules.baptism.solo) :
            serviceRules[serviceType];

        const dateString = date.toISOString().split('T')[0];
        const occupiedTimes = approvedBookings
            .filter(booking => 
                booking.preferred_date === dateString && 
                booking.status === 'approved'
            )
            .map(booking => booking.preferred_time);

        return rules.times.filter(time => !occupiedTimes.includes(time));
    }

    // Update the renderCalendar function to properly handle empty cells and selected date
    function renderCalendar(year, month) {
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startingDay = firstDay.getDay();
        const monthLength = lastDay.getDate();
        const baptismType = '{{ session("booking_step1.baptism_type") }}'; // Add this line

        const calendarGrid = document.getElementById('calendarGrid');
        calendarGrid.innerHTML = '';

        // Add empty cells for days before the first day of the month
        for (let i = 0; i < startingDay; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.className = 'h-14 rounded-lg';
            calendarGrid.appendChild(emptyCell);
        }

        // Add cells for each day of the month
        for (let day = 1; day <= monthLength; day++) {
            const date = new Date(year, month, day);
            const cell = document.createElement('div');
            const isHoliday = getCatholicHolidays(year).some(holiday =>
                holiday.getDate() === date.getDate() &&
                holiday.getMonth() === date.getMonth() &&
                holiday.getFullYear() === date.getFullYear()
            );

            // Check if all time slots are occupied for this date
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const rules = serviceType === 'baptism' ?
                (baptismType === 'group' ? serviceRules.baptism.group : serviceRules.baptism.solo) :
                serviceRules[serviceType];

            const occupiedTimes = approvedBookings
                .filter(booking => 
                    booking.preferred_date === dateString && 
                    booking.status.toLowerCase() === 'approved'
                )
                .map(booking => booking.preferred_time.split(':').slice(0, 2).join(':'));

            const allSlotsOccupied = rules.times.every(time =>
                occupiedTimes.some(occupiedTime => 
                    occupiedTime.toLowerCase() === time.toLowerCase()
                )
            );

            const isAvailable = !allSlotsOccupied && rules.days.includes(date.getDay());
            const isToday = date.toDateString() === new Date().toDateString();
            const isPast = date < new Date().setHours(0, 0, 0, 0);
            const isSelected = selectedDate && date.toDateString() === selectedDate.toDateString();

            let className = 'h-14 rounded-lg flex items-center justify-center relative ';

            if (isPast) {
                className += 'bg-gray-100 text-gray-400 cursor-not-allowed';
            } else if (isHoliday) {
                className += 'bg-yellow-100 border border-yellow-300 text-gray-600 cursor-not-allowed';
            } else if (allSlotsOccupied) {
                className += 'bg-red-100 border border-red-300 text-gray-600 cursor-not-allowed';
            } else if (!isAvailable) {
                className += 'bg-gray-100 text-gray-400 cursor-not-allowed';
            } else if (isSelected) {
                className += 'bg-green-100 border-2 border-green-500 text-green-700 font-bold cursor-pointer';
            } else {
                className += 'bg-white border border-gray-300 hover:border-green-500 cursor-pointer';
            }

            if (isToday) {
                className += ' ring-2 ring-[#0d5c2f] ring-offset-2';
            }

            cell.className = className;

            // Create a span for the date number to ensure it stays visible
            const dateSpan = document.createElement('span');
            dateSpan.textContent = day;
            dateSpan.className = 'z-10'; // Ensure the number stays on top
            cell.appendChild(dateSpan);

            // Add selected indicator
            if (isSelected) {
                const indicator = document.createElement('div');
                indicator.className = 'absolute bottom-1 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 bg-green-500 rounded-full';
                cell.appendChild(indicator);
            }

            if (isAvailable && !isPast && !isHoliday && !allSlotsOccupied) {
                cell.onclick = () => selectDate(date);
            }

            calendarGrid.appendChild(cell);
        }

        // Update month display
        document.getElementById('currentMonth').textContent =
            new Date(year, month).toLocaleDateString('en-US', {
                month: 'long'
                , year: 'numeric'
            });
    }

    // Update the selectDate function to refresh the calendar when a date is selected
    function selectDate(date) {
        selectedDate = date;

        // Re-render the calendar to update all cells
        renderCalendar(date.getFullYear(), date.getMonth());

        // Show time slots
        showTimeSlots(date);

        // Update hidden input - Use local date string format instead of ISO
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        document.getElementById('selectedDate').value = `${year}-${month}-${day}`;
    }

    function showTimeSlots(date) {
        const baptismType = '{{ session("booking_step1.baptism_type") }}'; 
        const rules = serviceType === 'baptism' ?
            (baptismType === 'group' ?
                serviceRules.baptism.group :
                serviceRules.baptism.solo) :
            serviceRules[serviceType];
    
        // Use local date string format instead of ISO
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const dateString = `${year}-${month}-${day}`;
        
        // Rest of the function remains the same
        // Debug each booking's data
        console.log('All bookings:', approvedBookings);
        approvedBookings.forEach(booking => {
            console.log('Booking date comparison:', {
                'Booking date': booking.preferred_date,
                'Selected date': dateString,
                'Dates match': booking.preferred_date === dateString,
                'Booking status': booking.status,
                'Status is approved': booking.status.toLowerCase() === 'approved',
                'Booking time': booking.preferred_time
            });
        });
    
        const occupiedTimes = approvedBookings
            .filter(booking => {
                const isMatch = booking.preferred_date === dateString && 
                              booking.status.toLowerCase() === 'approved';
                console.log('Filtering booking:', booking, 'isMatch:', isMatch);
                return isMatch;
            })
            .map(booking => booking.preferred_time.split(':').slice(0, 2).join(':'));
    
        console.log('Date selected:', dateString);
        console.log('Occupied times:', occupiedTimes);
        console.log('Available rules times:', rules.times);
    
        const timeSlotsDiv = document.getElementById('timeSlots');
        const timeSlotsGrid = document.getElementById('timeSlotsGrid');
    
        timeSlotsDiv.classList.remove('hidden');
        timeSlotsGrid.innerHTML = '';
    
        rules.times.forEach(time => {
            const slot = document.createElement('button');
            slot.type = 'button';
            // Case-insensitive comparison for time slots
            const isOccupied = occupiedTimes.some(occupiedTime => 
                occupiedTime.toLowerCase() === time.toLowerCase()
            );
            
            console.log(`Time slot ${time} - Occupied: ${isOccupied}`, {
                'Current time': time,
                'Occupied times': occupiedTimes,
                'Comparison result': isOccupied
            });
            
            if (isOccupied) {
                slot.className = 'p-3 text-center rounded-lg border bg-red-100 border-red-300 text-gray-600 cursor-not-allowed';
                slot.disabled = true;
                slot.setAttribute('data-occupied', 'true');
                slot.style.pointerEvents = 'none';
            } else {
                slot.className = 'p-3 text-center rounded-lg border hover:bg-[#0d5c2f]/10';
                slot.onclick = () => {
                    console.log('Selected available slot:', time);
                    selectTimeSlot(time, slot);
                };
            }
            
            slot.textContent = time;
            timeSlotsGrid.appendChild(slot);
        });
    }

    function selectTimeSlot(time, element) {
        selectedTimeSlot = time;

        // Update UI
        document.querySelectorAll('#timeSlotsGrid button').forEach(btn => {
            btn.classList.remove('bg-[#0d5c2f]', 'text-white');
        });
        element.classList.add('bg-[#0d5c2f]', 'text-white');

        // Update hidden input and enable continue button
        document.getElementById('selectedTime').value = time;
        document.getElementById('continueBtn').disabled = false;
    }

    function previousMonth() {
        const currentMonth = document.getElementById('currentMonth').textContent;
        const date = new Date(currentMonth);
        date.setMonth(date.getMonth() - 1);
        renderCalendar(date.getFullYear(), date.getMonth());
    }

    function nextMonth() {
        const currentMonth = document.getElementById('currentMonth').textContent;
        const date = new Date(currentMonth);
        date.setMonth(date.getMonth() + 1);
        renderCalendar(date.getFullYear(), date.getMonth());
    }

    // Initialize calendar
    document.addEventListener('DOMContentLoaded', () => {
        const today = new Date();
        renderCalendar(today.getFullYear(), today.getMonth());
    });

</script>
@endsection
