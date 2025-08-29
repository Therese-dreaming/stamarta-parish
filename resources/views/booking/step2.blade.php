@extends('layouts.user')

@section('title', 'Book Service - Step 2')

@section('content')
<!-- Modern Progress Indicator -->
<div class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-gray-900 mb-2 sm:mb-0">Book {{ $service->name }}</h1>
            <div class="text-sm text-gray-500">Step 2 of 3</div>
        </div>
        
        <div class="relative mt-4">
            <!-- Progress Bar Background -->
            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-[#0d5c2f] rounded-full" style="width: 66.66%"></div>
            </div>
            
            <!-- Step Indicators -->
            <div class="flex justify-between items-center mt-2">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-[#0d5c2f] text-white flex items-center justify-center text-sm font-medium shadow-md">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="text-xs font-medium text-[#0d5c2f] mt-2">Personal Info</span>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-[#0d5c2f] text-white flex items-center justify-center text-sm font-medium">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span class="text-xs font-medium text-[#0d5c2f] mt-2">Schedule</span>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-medium">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-500 mt-2">Requirements</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Service Information (single column, icon rows) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Service Details</h2>
                    <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center">
                        <i class="fas fa-concierge-bell text-[#0d5c2f]"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $service->name }}</h3>
                @if($service->description)
                    <p class="text-gray-600 mb-6 text-sm">{{ $service->description }}</p>
                @endif
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-t border-gray-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-[#0d5c2f] text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Duration</span>
                        </div>
                        <span class="text-sm text-gray-600">{{ $service->formatted_duration }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-3 border-t border-gray-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                <i class="fas fa-users text-[#0d5c2f] text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Capacity</span>
                        </div>
                        <span class="text-sm text-gray-600">Max {{ $service->max_slots }} slot(s)</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-3 border-t border-gray-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                <i class="fas fa-money-bill-wave text-[#0d5c2f] text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Fees</span>
                        </div>
                        <span class="text-sm font-semibold text-[#0d5c2f]">{{ $service->formatted_fees }}</span>
                    </div>
                    
                    @if($service->schedules)
                        <div class="pt-3 border-t border-gray-100">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar-alt text-[#0d5c2f] text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Available Times</span>
                            </div>
                            <div class="text-xs text-gray-600 space-y-2 ml-11">
                                @foreach($service->schedules as $day => $times)
                                    <div>
                                        <span class="font-medium text-gray-700">{{ ucfirst($day) }}:</span>
                                        {{ implode(', ', $times) }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Schedule Selection Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Schedule Selection</h2>
                <p class="text-gray-600 mt-1">Choose your preferred date and time from the available slots</p>
            </div>
            
            <form action="{{ route('booking.step2.store', $service) }}" method="POST" class="p-6">
                @csrf
                
                <!-- Calendar -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Date</h3>
                    <div class="max-w-4xl mx-auto">
                        <x-calendar :activeBookings="$activeBookings" :selectedDate="$selectedDate" :service="$service" :parochialActivities="$parochialActivities" />
                    </div>
                    <input type="hidden" name="selected_date" value="{{ $selectedDate }}">
                </div>

                <!-- Time Slots (loaded via AJAX) -->
                <div class="mt-8 border-t pt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Time Slots</h3>
                    <div id="time-slots-container">
                        <div class="text-center py-8">
                            <i class="fas fa-calendar text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Select a Date</h3>
                            <p class="text-gray-600">Please select a date from the calendar above to view available time slots.</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-8 flex justify-between items-center">
                    <a href="{{ route('booking.step1', $service) }}" 
                       class="text-gray-600 hover:text-gray-800 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Details
                    </a>
                    
                    <button type="submit" 
                            id="continueBtn"
                            class="bg-[#0d5c2f] text-white px-8 py-3 rounded-lg hover:bg-[#0d5c2f]/90 disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ !$selectedDate ? 'disabled' : '' }}>
                        Continue to Requirements
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const timeSlotBtns = document.querySelectorAll('.time-slot-btn:not([disabled])');
    const selectedTimeInput = document.getElementById('selectedTime');
    const continueBtn = document.getElementById('continueBtn');

    timeSlotBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            timeSlotBtns.forEach(b => {
                b.classList.remove('bg-[#0d5c2f]', 'text-white');
                b.classList.add('border-gray-300', 'hover:border-[#0d5c2f]', 'hover:bg-gray-50');
            });

            this.classList.remove('border-gray-300', 'hover:border-[#0d5c2f]', 'hover:bg-gray-50');
            this.classList.add('bg-[#0d5c2f]', 'text-white');

            selectedTimeInput.value = this.dataset.time;
            continueBtn.disabled = false;
        });
    });
});
</script>
@endsection 