@extends('layouts.user')

@section('title', 'Book Service - Step 2')

@section('content')
<!-- Progress Bar -->
<div class="bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-center">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-green-600">Personal Information</span>
                </div>
                <div class="w-16 h-0.5 bg-[#0d5c2f]"></div>
                <div class="flex items-center">
                    <div class="bg-[#0d5c2f] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">2</div>
                    <span class="ml-2 text-sm font-medium text-[#0d5c2f]">Schedule Selection</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="bg-gray-300 text-gray-500 rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Requirements</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Service Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Service Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $service->name }}</h3>
                        @if($service->description)
                            <p class="text-gray-600 mb-4">{{ $service->description }}</p>
                        @endif
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-[#0d5c2f] mr-2"></i>
                                <span class="text-sm text-gray-600">{{ $service->formatted_duration }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users text-[#0d5c2f] mr-2"></i>
                                <span class="text-sm text-gray-600">Max {{ $service->max_slots }} slot(s)</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Fees:</h4>
                        <p class="text-lg font-semibold text-[#0d5c2f]">{{ $service->formatted_fees }}</p>
                        
                        @if($service->schedules)
                            <h4 class="font-semibold text-gray-900 mb-2 mt-4">Available Schedule:</h4>
                            <div class="text-sm text-gray-600">
                                @foreach($service->schedules as $day => $times)
                                    <div class="mb-1">
                                        <strong>{{ ucfirst($day) }}:</strong> 
                                        {{ implode(', ', $times) }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
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
                        <x-calendar :approvedBookings="$approvedBookings" :selectedDate="$selectedDate" :service="$service" />
                    </div>
                    
                    <!-- Hidden date input for form submission -->
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
    // Handle time slot selection
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
});
</script>
@endsection 