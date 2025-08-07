@extends('layouts.user')

@section('title', 'Submit Payment')

@section('content')
@include('components.toast')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Submit Payment</h1>
                    <p class="text-gray-600 mt-2">Complete your payment for booking #{{ $booking->id }}</p>
                </div>
                <a href="{{ route('booking.my-bookings') }}" 
                   class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to My Bookings
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Payment Information -->
            <div class="space-y-6">
                <!-- Booking Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Booking Details</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Service:</span>
                                <p class="text-gray-900 font-semibold">{{ $booking->service->name }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Date & Time:</span>
                                <p class="text-gray-900">{{ $booking->formatted_date }} at {{ $booking->formatted_time }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Total Fee:</span>
                                <p class="text-gray-900 font-semibold text-lg">{{ $booking->formatted_total_fee ?? 'Contact office' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parish Payment Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Parish Payment Details</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            <!-- GCash -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <img src="{{ asset('images/gcash-logo.png') }}" alt="GCash" class="h-8 w-auto mr-3">
                                    <h3 class="text-lg font-semibold text-gray-900">GCash</h3>
                                </div>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Account Name:</span>
                                        <p class="text-gray-900">Sta. Marta Parish</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">GCash Number:</span>
                                        <p class="text-gray-900 font-mono">0917-123-4567</p>
                                    </div>
                                    <div class="mt-3">
                                        <img src="{{ asset('images/gcash-qr.png') }}" alt="GCash QR Code" class="w-32 h-32 mx-auto border border-gray-200 rounded-lg">
                                        <p class="text-xs text-gray-500 text-center mt-2">Scan QR Code to Pay</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Metrobank -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <img src="{{ asset('images/metrobank-logo.png') }}" alt="Metrobank" class="h-8 w-auto mr-3">
                                    <h3 class="text-lg font-semibold text-gray-900">Metrobank</h3>
                                </div>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Account Name:</span>
                                        <p class="text-gray-900">Sta. Marta Parish</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Account Number:</span>
                                        <p class="text-gray-900 font-mono">123-456-7890</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Branch:</span>
                                        <p class="text-gray-900">Hagonoy Branch</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Submit Payment Proof</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('booking.submit-payment', $booking) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Payment Method *
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="payment_method" value="gcash" class="mr-3" required>
                                    <img src="{{ asset('images/gcash-logo.png') }}" alt="GCash" class="h-6 w-auto mr-3">
                                    <span class="text-sm text-gray-700">GCash</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="payment_method" value="metrobank" class="mr-3" required>
                                    <img src="{{ asset('images/metrobank-logo.png') }}" alt="Metrobank" class="h-6 w-auto mr-3">
                                    <span class="text-sm text-gray-700">Metrobank</span>
                                </label>
                            </div>
                            @error('payment_method')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Reference Number *
                            </label>
                            <input type="text" 
                                   id="payment_reference" 
                                   name="payment_reference" 
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                   placeholder="Enter your payment reference number"
                                   value="{{ old('payment_reference') }}">
                            @error('payment_reference')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Proof *
                            </label>
                            <input type="file" 
                                   id="payment_proof" 
                                   name="payment_proof" 
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#0d5c2f] file:text-white hover:file:bg-[#0d5c2f]/90">
                            <p class="text-xs text-gray-500 mt-1">Accepted: PDF, JPG, PNG (Max: 5MB)</p>
                            @error('payment_proof')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="payment_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Notes (Optional)
                            </label>
                            <textarea id="payment_notes" 
                                      name="payment_notes" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                      placeholder="Any additional information about your payment">{{ old('payment_notes') }}</textarea>
                            @error('payment_notes')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('booking.my-bookings') }}" 
                               class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                                Submit Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Important Reminders -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Important Reminders</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li class="flex items-start">
                    <i class="fas fa-info-circle mt-0.5 mr-2"></i>
                    <span>Please ensure the payment amount matches exactly with the total fee.</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-info-circle mt-0.5 mr-2"></i>
                    <span>Keep your payment reference number for verification.</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-info-circle mt-0.5 mr-2"></i>
                    <span>Upload a clear screenshot or photo of your payment confirmation.</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-info-circle mt-0.5 mr-2"></i>
                    <span>Your booking will be reviewed once payment proof is submitted.</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection 