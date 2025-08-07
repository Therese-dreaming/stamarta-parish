@extends('layouts.user')

@section('title', 'Booking Confirmation')

@section('content')
<div class="py-16 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-check text-2xl text-green-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Confirmed!</h1>
            <p class="text-lg text-gray-600">Your service booking has been successfully submitted.</p>
        </div>

        <!-- Booking Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Booking Details</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booking ID:</span>
                                <span class="font-medium">#{{ $booking->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Service:</span>
                                <span class="font-medium">{{ $booking->service->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date:</span>
                                <span class="font-medium">{{ $booking->formatted_date }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Time:</span>
                                <span class="font-medium">{{ $booking->formatted_time }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status_badge }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium">{{ $booking->user->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-medium">{{ $booking->contact_phone }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Address:</span>
                                <span class="font-medium text-sm">{{ Str::limit($booking->contact_address, 30) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">What Happens Next?</h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3 mt-0.5">1</div>
                    <div>
                        <p class="font-medium text-blue-900">Booking Review</p>
                        <p class="text-sm text-blue-700">Our parish office will review your booking within 24-48 hours.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3 mt-0.5">2</div>
                    <div>
                        <p class="font-medium text-blue-900">Confirmation Call</p>
                        <p class="text-sm text-blue-700">We will contact you to confirm your booking and provide additional details.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3 mt-0.5">3</div>
                    <div>
                        <p class="font-medium text-blue-900">Service Day</p>
                        <p class="text-sm text-blue-700">Arrive 30 minutes before your scheduled time with all required documents.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Reminders -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-yellow-900 mb-4">Important Reminders</h3>
            <ul class="space-y-2 text-sm text-yellow-800">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-600 mr-2 mt-0.5"></i>
                    Bring all required documents on the day of your service
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-600 mr-2 mt-0.5"></i>
                    Arrive 30 minutes before your scheduled time
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-600 mr-2 mt-0.5"></i>
                    Dress appropriately for the service
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-600 mr-2 mt-0.5"></i>
                    Payment should be made at the parish office
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-600 mr-2 mt-0.5"></i>
                    Contact the office if you need to reschedule or cancel
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('services.my-bookings') }}" 
               class="px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors text-center">
                <i class="fas fa-calendar-alt mr-2"></i>View My Bookings
            </a>
            <a href="{{ route('services.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-center">
                <i class="fas fa-plus mr-2"></i>Book Another Service
            </a>
            <a href="{{ route('home') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-center">
                <i class="fas fa-home mr-2"></i>Return Home
            </a>
        </div>
    </div>
</div>
@endsection 