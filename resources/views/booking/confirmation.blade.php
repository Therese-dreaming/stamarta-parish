@extends('layouts.user')

@section('title', 'Booking Confirmation')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Alert -->
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-check text-green-600 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-green-900">Success!</h3>
                    <p class="text-sm text-green-700">Your booking has been processed and you will receive a confirmation email shortly.</p>
                </div>
            </div>
        </div>
        <!-- Hero Success Section -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg overflow-hidden mb-8 relative">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-4 left-4 w-8 h-8 border-2 border-white rounded-full"></div>
                <div class="absolute top-8 right-8 w-4 h-4 bg-white rounded-full"></div>
                <div class="absolute bottom-6 left-8 w-6 h-6 border-2 border-white rounded-full"></div>
                <div class="absolute bottom-4 right-4 w-3 h-3 bg-white rounded-full"></div>
            </div>
            
            <div class="p-8 text-center text-white relative z-10">
                <div class="mx-auto w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mb-6 animate-pulse">
                    <i class="fas fa-check text-3xl text-white"></i>
                </div>
                <h1 class="text-4xl font-bold mb-3">Booking Confirmed!</h1>
                <p class="text-xl text-green-100 mb-4">Your service booking has been successfully submitted</p>
                <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium border border-white/30">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Booking ID: #{{ $booking->id }}
                </div>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                        <i class="fas fa-info-circle text-[#0d5c2f] text-sm"></i>
                    </div>
                    Booking Details
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Service Information -->
                    <div class="lg:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                                <i class="fas fa-church text-blue-600 text-xs"></i>
                            </div>
                            Service Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                                <div class="text-xs font-medium text-blue-700 mb-1">Service</div>
                                <div class="text-sm font-semibold text-blue-900">{{ $booking->service->name }}</div>
                            </div>
                            <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                                <div class="text-xs font-medium text-green-700 mb-1">Date</div>
                                <div class="text-sm font-semibold text-green-900">{{ $booking->formatted_date }}</div>
                            </div>
                            <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                                <div class="text-xs font-medium text-purple-700 mb-1">Time</div>
                                <div class="text-sm font-semibold text-purple-900">{{ $booking->formatted_time }}</div>
                            </div>
                            <div class="p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl">
                                <div class="text-xs font-medium text-orange-700 mb-1">Status</div>
                                <div class="text-sm font-semibold text-orange-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $booking->status_badge }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center mr-2">
                                <i class="fas fa-user text-indigo-600 text-xs"></i>
                            </div>
                            Contact Information
                        </h3>
                        <div class="space-y-3">
                            <div class="p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl">
                                <div class="text-xs font-medium text-indigo-700 mb-1">Name</div>
                                <div class="text-sm font-semibold text-indigo-900">{{ $booking->user->name }}</div>
                            </div>
                            <div class="p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl">
                                <div class="text-xs font-medium text-indigo-700 mb-1">Phone</div>
                                <div class="text-sm font-semibold text-indigo-900">{{ $booking->contact_phone }}</div>
                            </div>
                            <div class="p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl">
                                <div class="text-xs font-medium text-indigo-700 mb-1">Address</div>
                                <div class="text-sm font-semibold text-indigo-900">{{ Str::limit($booking->contact_address, 25) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 mb-8">
            <h3 class="text-xl font-bold text-blue-900 mb-6 flex items-center">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center mr-3">
                    <i class="fas fa-route text-sm"></i>
                </div>
                What Happens Next?
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-lg font-bold mx-auto mb-4">1</div>
                    <h4 class="font-semibold text-blue-900 mb-2">Booking Review</h4>
                    <p class="text-sm text-blue-700">Our parish office will review your booking within 24-48 hours.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-lg font-bold mx-auto mb-4">2</div>
                    <h4 class="font-semibold text-blue-900 mb-2">Confirmation Call</h4>
                    <p class="text-sm text-blue-700">We will contact you to confirm your booking and provide additional details.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-lg font-bold mx-auto mb-4">3</div>
                    <h4 class="font-semibold text-blue-900 mb-2">Service Day</h4>
                    <p class="text-sm text-blue-700">Arrive 30 minutes before your scheduled time with all required documents.</p>
                </div>
            </div>
        </div>

        <!-- Important Reminders -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-6 mb-8">
            <h3 class="text-xl font-bold text-yellow-900 mb-6 flex items-center">
                <div class="w-8 h-8 rounded-full bg-yellow-600 text-white flex items-center justify-center mr-3">
                    <i class="fas fa-exclamation-triangle text-sm"></i>
                </div>
                Important Reminders
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-start p-4 bg-white/50 rounded-xl">
                    <div class="w-8 h-8 rounded-full bg-yellow-600 text-white flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-file-alt text-xs"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-yellow-900 mb-1">Documents</h4>
                        <p class="text-sm text-yellow-800">Bring all required documents on the day of your service</p>
                    </div>
                </div>
                <div class="flex items-start p-4 bg-white/50 rounded-xl">
                    <div class="w-8 h-8 rounded-full bg-yellow-600 text-white flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-clock text-xs"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-yellow-900 mb-1">Arrival Time</h4>
                        <p class="text-sm text-yellow-800">Arrive 30 minutes before your scheduled time</p>
                    </div>
                </div>
                <div class="flex items-start p-4 bg-white/50 rounded-xl">
                    <div class="w-8 h-8 rounded-full bg-yellow-600 text-white flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-tshirt text-xs"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-yellow-900 mb-1">Dress Code</h4>
                        <p class="text-sm text-yellow-800">Dress appropriately for the service</p>
                    </div>
                </div>
                <div class="flex items-start p-4 bg-white/50 rounded-xl">
                    <div class="w-8 h-8 rounded-full bg-yellow-600 text-white flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-credit-card text-xs"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-yellow-900 mb-1">Payment</h4>
                        <p class="text-sm text-yellow-800">Payment should be made at the parish office</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 p-4 bg-yellow-600/10 rounded-xl border border-yellow-300">
                <div class="flex items-center">
                    <i class="fas fa-phone text-yellow-600 mr-2"></i>
                    <span class="text-sm font-medium text-yellow-900">Contact the office if you need to reschedule or cancel</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Quick Actions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('booking.my-bookings') }}" 
                   class="group p-4 bg-gradient-to-br from-[#0d5c2f] to-[#0d5c2f]/90 text-white rounded-xl hover:shadow-lg transition-all duration-200 text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-white/30 transition-colors">
                        <i class="fas fa-calendar-alt text-lg"></i>
                    </div>
                    <h4 class="font-semibold mb-1">View My Bookings</h4>
                    <p class="text-xs text-green-100">Check all your bookings</p>
                </a>
                <a href="{{ route('services.index') }}" 
                   class="group p-4 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl hover:shadow-lg transition-all duration-200 text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-white/30 transition-colors">
                        <i class="fas fa-plus text-lg"></i>
                    </div>
                    <h4 class="font-semibold mb-1">Book Another Service</h4>
                    <p class="text-xs text-blue-100">Schedule more services</p>
                </a>
                <a href="{{ route('home') }}" 
                   class="group p-4 bg-gradient-to-br from-gray-500 to-gray-600 text-white rounded-xl hover:shadow-lg transition-all duration-200 text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-white/30 transition-colors">
                        <i class="fas fa-home text-lg"></i>
                    </div>
                    <h4 class="font-semibold mb-1">Return Home</h4>
                    <p class="text-xs text-gray-100">Go back to homepage</p>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Add a subtle success animation
document.addEventListener('DOMContentLoaded', function() {
    // Animate the success checkmark
    const checkmark = document.querySelector('.animate-pulse');
    if (checkmark) {
        setTimeout(() => {
            checkmark.classList.remove('animate-pulse');
            checkmark.classList.add('animate-bounce');
        }, 1000);
    }
    
    // Add a subtle fade-in animation to cards
    const cards = document.querySelectorAll('.bg-white');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 200 + (index * 100));
    });
});
</script>
@endsection 