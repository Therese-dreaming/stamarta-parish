@extends('layouts.user')

@section('title', 'Submit Payment')

@section('content')
@include('components.toast')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Payment Methods Details - Moved Above Header -->
        <div class="mb-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Available Payment Methods</h2>
                <p class="text-lg text-gray-600">Choose your preferred payment method below</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- GCash -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-5">
                        <div class="flex items-center space-x-4">
                            <img src="{{ asset('images/gcash-logo.png') }}" alt="GCash" class="w-12 h-12 bg-white rounded-lg p-2">
                            <div>
                                <h3 class="text-xl font-bold text-white">GCash</h3>
                                <p class="text-green-100">Mobile wallet payment</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <span class="block text-xs font-medium text-gray-500 mb-1">Account Name</span>
                                <span class="text-sm font-semibold text-gray-900">Sta. Marta Parish</span>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <span class="block text-xs font-medium text-gray-500 mb-1">GCash Number</span>
                                <span class="text-sm font-semibold text-gray-900 font-mono">0917-123-4567</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <img src="{{ asset('images/gcash-qr.png') }}" alt="GCash QR Code" class="w-40 h-40 mx-auto border-2 border-gray-200 rounded-xl shadow-sm">
                            <p class="text-xs text-gray-500 mt-2">Scan QR Code to Pay</p>
                        </div>
                    </div>
                </div>

                <!-- Metrobank -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-5">
                        <div class="flex items-center space-x-4">
                            <img src="{{ asset('images/metrobank-logo.png') }}" alt="Metrobank" class="w-12 h-12 bg-white rounded-lg p-2">
                            <div>
                                <h3 class="text-xl font-bold text-white">Metrobank</h3>
                                <p class="text-blue-100">Bank transfer</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <span class="block text-xs font-medium text-gray-500 mb-1">Account Name</span>
                                <span class="text-sm font-semibold text-gray-900">Sta. Marta Parish</span>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <span class="block text-xs font-medium text-gray-500 mb-1">Account Number</span>
                                <span class="text-sm font-semibold text-gray-900 font-mono">123-456-7890</span>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <span class="block text-xs font-medium text-gray-500 mb-1">Branch</span>
                                <span class="text-sm font-semibold text-gray-900">Hagonoy Branch</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-[#0d5c2f]/10 rounded-full mb-6">
                <i class="fas fa-credit-card text-2xl text-[#0d5c2f]"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Complete Your Payment</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Submit your payment proof for booking #{{ $booking->id }} to proceed with your service
            </p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column: Payment Form -->
            <div class="xl:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 px-8 py-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-upload mr-3"></i>
                            Submit Payment Proof
                        </h2>
                        <p class="text-white/80 mt-2">Upload your payment confirmation to complete the process</p>
                    </div>
                    
                    <div class="p-8">
                        <form action="{{ route('booking.submit-payment', $booking) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            
                            <!-- Payment Method Selection -->
                            <div class="space-y-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-4">
                                    <i class="fas fa-credit-card mr-2 text-[#0d5c2f]"></i>
                                    Payment Method *
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="relative group cursor-pointer">
                                        <input type="radio" name="payment_method" value="gcash" class="sr-only" required>
                                        <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-[#0d5c2f]/30 transition-all duration-200 group-hover:shadow-lg">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <img src="{{ asset('images/gcash-logo.png') }}" alt="GCash" class="w-8 h-8">
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="font-semibold text-gray-900">GCash</h3>
                                                    <p class="text-sm text-gray-500">Mobile wallet payment</p>
                                                </div>
                                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full group-hover:border-[#0d5c2f] transition-colors"></div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label class="relative group cursor-pointer">
                                        <input type="radio" name="payment_method" value="metrobank" class="sr-only" required>
                                        <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-[#0d5c2f]/30 transition-all duration-200 group-hover:shadow-lg">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <img src="{{ asset('images/metrobank-logo.png') }}" alt="Metrobank" class="w-8 h-8">
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="font-semibold text-gray-900">Metrobank</h3>
                                                    <p class="text-sm text-gray-500">Bank transfer</p>
                                                </div>
                                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full group-hover:border-[#0d5c2f] transition-colors"></div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @error('payment_method')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Payment Reference -->
                            <div class="space-y-3">
                                <label for="payment_reference" class="block text-lg font-semibold text-gray-900">
                                    <i class="fas fa-hashtag mr-2 text-[#0d5c2f]"></i>
                                    Payment Reference Number *
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-receipt text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           id="payment_reference" 
                                           name="payment_reference" 
                                           required
                                           class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 text-lg"
                                           placeholder="Enter your payment reference number"
                                           value="{{ old('payment_reference') }}">
                                </div>
                                <p class="text-sm text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    This is the reference number from your payment confirmation
                                </p>
                                @error('payment_reference')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Payment Proof Upload -->
                            <div class="space-y-3">
                                <label for="payment_proof" class="block text-lg font-semibold text-gray-900">
                                    <i class="fas fa-file-upload mr-2 text-[#0d5c2f]"></i>
                                    Payment Proof *
                                </label>
                                <div class="relative">
                                    <input type="file" 
                                           id="payment_proof" 
                                           name="payment_proof" 
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           required
                                           class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#0d5c2f] file:text-white hover:file:bg-[#0d5c2f]/90 file:transition-colors">
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <i class="fas fa-file-pdf mr-2 text-red-500"></i>PDF
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-file-image mr-2 text-blue-500"></i>JPG/PNG
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-weight-hanging mr-2 text-gray-500"></i>Max: 5MB
                                    </span>
                                </div>
                                @error('payment_proof')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Additional Notes -->
                            <div class="space-y-3">
                                <label for="payment_notes" class="block text-lg font-semibold text-gray-900">
                                    <i class="fas fa-sticky-note mr-2 text-[#0d5c2f]"></i>
                                    Additional Notes (Optional)
                                </label>
                                <textarea id="payment_notes" 
                                          name="payment_notes" 
                                          rows="4"
                                          class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 resize-none text-lg"
                                          placeholder="Any additional information about your payment...">{{ old('payment_notes') }}</textarea>
                                @error('payment_notes')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                <a href="{{ route('booking.my-bookings') }}" 
                                   class="flex items-center px-6 py-3 text-gray-600 border-2 border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 font-medium">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Back to My Bookings
                                </a>
                                <button type="submit" 
                                        class="flex items-center px-8 py-4 bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 text-white rounded-xl hover:from-[#0a4a26] hover:to-[#0a4a26]/90 transition-all duration-200 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <i class="fas fa-paper-plane mr-3"></i>
                                    Submit Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Information Panels -->
            <div class="space-y-6">
                <!-- Booking Summary -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-calendar-check mr-3"></i>
                            Booking Summary
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">
                            <span class="text-sm font-medium text-blue-700">Service</span>
                            <span class="text-sm font-semibold text-blue-900">{{ $booking->service->name }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                            <span class="text-sm font-medium text-green-700">Date & Time</span>
                            <span class="text-sm font-semibold text-green-900">{{ $booking->formatted_date }} at {{ $booking->formatted_time }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl">
                            <span class="text-sm font-medium text-purple-700">Total Fee</span>
                            <span class="text-lg font-bold text-purple-900">{{ $booking->formatted_total_fee ?? 'Contact office' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-5">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-info-circle mr-3"></i>
                            Payment Instructions
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                </div>
                                <p class="text-sm text-gray-700">Ensure payment amount matches exactly with the total fee</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                </div>
                                <p class="text-sm text-gray-700">Keep your payment reference number for verification</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                </div>
                                <p class="text-sm text-gray-700">Upload a clear screenshot or photo of your payment confirmation</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                </div>
                                <p class="text-sm text-gray-700">Your booking will be reviewed once payment proof is submitted</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Support -->
                <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-2xl p-6 text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-white text-xl"></i>
                    </div>
                    <h4 class="text-white font-semibold mb-2">Need Help?</h4>
                    <p class="text-white/80 text-sm mb-4">If you encounter any issues with payment submission</p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-colors text-sm">
                        <i class="fas fa-envelope mr-2"></i>
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom radio button styling */
input[type="radio"]:checked + div {
    border-color: #0d5c2f;
    background-color: #f0f9ff;
}

input[type="radio"]:checked + div .w-5 {
    border-color: #0d5c2f;
    background-color: #0d5c2f;
}

input[type="radio"]:checked + div .w-5::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    background-color: white;
    border-radius: 50%;
}
</style>

<script>
// Enhanced form interactions
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
    const paymentMethodLabels = document.querySelectorAll('label[for*="payment_method"]');
    
    paymentMethodInputs.forEach((input, index) => {
        input.addEventListener('change', function() {
            // Remove active state from all labels
            paymentMethodLabels.forEach(label => {
                label.querySelector('div').classList.remove('border-[#0d5c2f]', 'bg-[#0d5c2f]/5');
                label.querySelector('div').classList.add('border-gray-200');
            });
            
            // Add active state to selected label
            if (this.checked) {
                const selectedLabel = paymentMethodLabels[index];
                selectedLabel.querySelector('div').classList.remove('border-gray-200');
                selectedLabel.querySelector('div').classList.add('border-[#0d5c2f]', 'bg-[#0d5c2f]/5');
            }
        });
    });
    
    // File input enhancement
    const fileInput = document.getElementById('payment_proof');
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const fileName = this.files[0].name;
            this.classList.add('border-green-500', 'bg-green-50');
            // You could add a preview or filename display here
        }
    });
});
</script>
@endsection 