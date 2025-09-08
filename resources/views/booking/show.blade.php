@extends('layouts.user')

@section('title', 'Booking Details')

@section('content')
@include('components.toast')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-[#0d5c2f]/10 rounded-full mb-4">
                <i class="fas fa-calendar-check text-2xl text-[#0d5c2f]"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Details</h1>
            <p class="text-lg text-gray-600">Complete information about your service booking</p>
        </div>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('booking.my-bookings') }}" 
               class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to My Bookings
            </a>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Booking Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Main Booking Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <!-- Status Header Bar -->
                    <div class="h-3 {{ $booking->status === 'pending' ? 'bg-yellow-400' : 
                                    ($booking->status === 'acknowledged' ? 'bg-blue-400' : 
                                    ($booking->status === 'payment_hold' ? 'bg-orange-400' : 
                                    ($booking->status === 'approved' ? 'bg-green-400' : 
                                    ($booking->status === 'rejected' ? 'bg-red-400' : 
                                    ($booking->status === 'completed' ? 'bg-emerald-400' : 'bg-gray-400'))))) }}">
                    </div>
                    
                    <div class="p-8">
                        <!-- Booking Header -->
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-3">{{ $booking->service->name }}</h2>
                            <div class="flex items-center justify-center space-x-4">
                                <span class="px-4 py-2 rounded-full text-sm font-medium {{ $booking->status_badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                                <span class="text-gray-500">•</span>
                                <span class="text-gray-600">Booking ID: #{{ $booking->id }}</span>
                            </div>
                        </div>

                        <!-- Service Information -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-info-circle text-[#0d5c2f] mr-2"></i>
                                Service Information
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white rounded-lg p-3 border border-gray-200">
                                    <span class="block text-xs font-medium text-gray-500 mb-1">Service Name</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $booking->service->name }}</span>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-gray-200">
                                    <span class="block text-xs font-medium text-gray-500 mb-1">Duration</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $booking->service->formatted_duration }}</span>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-gray-200">
                                    <span class="block text-xs font-medium text-gray-500 mb-1">Service Date</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $booking->formatted_date }}</span>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-gray-200">
                                    <span class="block text-xs font-medium text-gray-500 mb-1">Service Time</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $booking->formatted_time }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-user text-blue-600 mr-2"></i>
                                Personal Information
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                    <span class="block text-xs font-medium text-blue-600 mb-1">Full Name</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        @if($booking->user)
                                            {{ $booking->user->name }}
                                        @elseif(isset($booking->custom_data['full_name']))
                                            {{ $booking->custom_data['full_name'] }}
                                        @elseif(isset($booking->custom_data['name']))
                                            {{ $booking->custom_data['name'] }}
                                        @else
                                            Not provided
                                        @endif
                                    </span>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                    <span class="block text-xs font-medium text-blue-600 mb-1">Contact Phone</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $booking->contact_phone ?? 'Not provided' }}</span>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                    <span class="block text-xs font-medium text-blue-600 mb-1">Email Address</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        @if($booking->user)
                                            {{ $booking->user->email }}
                                        @elseif(isset($booking->custom_data['email']))
                                            {{ $booking->custom_data['email'] }}
                                        @else
                                            Not provided
                                        @endif
                                    </span>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                    <span class="block text-xs font-medium text-blue-600 mb-1">Date of Birth</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        @if(isset($booking->custom_data['birth_date']))
                                            {{ \Carbon\Carbon::parse($booking->custom_data['birth_date'])->format('F d, Y') }}
                                        @elseif(isset($booking->custom_data['date_of_birth']))
                                            {{ \Carbon\Carbon::parse($booking->custom_data['date_of_birth'])->format('F d, Y') }}
                                        @else
                                            Not provided
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Service-Specific Information - Categorized -->
                        @if($booking->custom_data && count($booking->custom_data) > 0)
                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4 mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-clipboard-list text-purple-600 mr-2"></i>
                                    Service-Specific Information
                                </h3>
                                
                                @php
                                    // Categorize custom fields based on common patterns
                                    $categories = [
                                        'child_info' => ['child_name', 'child_birth_date', 'child_birth_place', 'child_gender', 'child_age'],
                                        'parent_info' => ['father_name', 'mother_name', 'father_birth_date', 'mother_birth_date', 'parent_address'],
                                        'godparent_info' => ['godfather_name', 'godmother_name', 'godparents', 'sponsors'],
                                        'marriage_info' => ['groom_name', 'bride_name', 'marriage_date', 'marriage_place'],
                                        'document_info' => ['birth_certificate', 'marriage_certificate', 'baptismal_certificate', 'documents'],
                                        'other_info' => []
                                    ];
                                    
                                    $categorizedData = [];
                                    $usedFields = [];
                                    
                                    foreach ($categories as $category => $fieldPatterns) {
                                        $categorizedData[$category] = [];
                                        foreach ($fieldPatterns as $pattern) {
                                            foreach ($booking->custom_data as $field => $value) {
                                                if (stripos($field, $pattern) !== false && !in_array($field, $usedFields) && $value && $value !== '') {
                                                    $categorizedData[$category][$field] = $value;
                                                    $usedFields[] = $field;
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Add remaining fields to 'other_info'
                                    foreach ($booking->custom_data as $field => $value) {
                                        if (!in_array($field, $usedFields) && $value && $value !== '') {
                                            $categorizedData['other_info'][$field] = $value;
                                        }
                                    }
                                    
                                    // Remove empty categories
                                    $categorizedData = array_filter($categorizedData, function($data) {
                                        return !empty($data);
                                    });
                                @endphp
                                
                                <div class="space-y-3">
                                    @foreach($categorizedData as $category => $fields)
                                        @if(count($fields) > 0)
                                            <div class="bg-white rounded-lg p-3 border border-purple-200">
                                                <h4 class="text-sm font-semibold text-purple-800 mb-2 flex items-center">
                                                    @switch($category)
                                                        @case('child_info')
                                                            <i class="fas fa-baby text-purple-600 mr-2"></i>
                                                            Child's Information
                                                            @break
                                                        @case('parent_info')
                                                            <i class="fas fa-users text-purple-600 mr-2"></i>
                                                            Parents' Information
                                                            @break
                                                        @case('godparent_info')
                                                            <i class="fas fa-hands-helping text-purple-600 mr-2"></i>
                                                            Godparents/Sponsors
                                                            @break
                                                        @case('marriage_info')
                                                            <i class="fas fa-heart text-purple-600 mr-2"></i>
                                                            Marriage Information
                                                            @break
                                                        @case('document_info')
                                                            <i class="fas fa-file-alt text-purple-600 mr-2"></i>
                                                            Required Documents
                                                            @break
                                                        @default
                                                            <i class="fas fa-info-circle text-purple-600 mr-2"></i>
                                                            Additional Information
                                                    @endswitch
                                                </h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    @foreach($fields as $field => $value)
                                                        <div>
                                                            <span class="block text-xs font-medium text-gray-600 mb-1">
                                                                {{ ucwords(str_replace('_', ' ', $field)) }}
                                                            </span>
                                                            <span class="text-sm text-gray-900 font-medium">
                                                                @if(is_array($value))
                                                                    @if(count($value) === 1)
                                                                        {{ $value[0] }}
                                                                    @else
                                                                        <ul class="list-disc list-inside space-y-1 text-xs">
                                                                            @foreach($value as $item)
                                                                                <li>{{ $item }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                @else
                                                                    {{ $value }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Additional Notes -->
                        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl p-4 mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
                                Additional Notes
                            </h3>
                            @if($booking->additional_notes && trim($booking->additional_notes) !== '')
                                <div class="bg-white rounded-lg p-3 border border-yellow-200">
                                    <p class="text-gray-700 text-sm">{{ $booking->additional_notes }}</p>
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 text-center">
                                    <i class="fas fa-sticky-note text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-gray-500 text-sm">No additional notes provided</p>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-center space-x-4 pt-6 border-t border-gray-200">
                            @if($booking->status === 'acknowledged')
                                <a href="{{ route('booking.payment', $booking) }}" 
                                   class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                                    <i class="fas fa-credit-card mr-2"></i>
                                    Proceed to Payment
                                </a>
                            @endif
                            
                            @if(!empty($booking->certificate_path))
                                <a href="{{ Storage::url($booking->certificate_path) }}" target="_blank" 
                                   class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                                    <i class="fas fa-certificate mr-2"></i>
                                    View Certificate
                                </a>
                            @endif
                            
                            @if(!$booking->service->hasUserRating(Auth::id(), $booking->id) && $booking->status === 'completed')
                                <button onclick="openRatingModal({{ $booking->id }}, {{ $booking->service_id }}, '{{ $booking->service->name }}')" 
                                        class="inline-flex items-center px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                                    <i class="fas fa-star mr-2"></i>
                                    Rate Service
                                </button>
                            @endif
                            
                            @if(in_array($booking->status, ['pending', 'acknowledged', 'payment_hold']))
                                <button onclick="openCancelModal({{ $booking->id }}, '{{ $booking->service->name }}', '{{ $booking->formatted_date }}')" 
                                        class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancel Booking
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar Information -->
            <div class="space-y-6 lg:sticky lg:top-4 self-start">
                <!-- Booking Timeline -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-clock text-[#0d5c2f] mr-3"></i>
                        Booking Timeline
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="w-4 h-4 bg-green-500 rounded-full mt-1 flex-shrink-0"></div>
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Booking Created</span>
                                <p class="text-sm text-gray-600">{{ $booking->created_at->format('F d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->created_at->format('g:i A') }}</p>
                            </div>
                        </div>
                        @if($booking->acknowledged_at)
                            <div class="flex items-start space-x-4">
                                <div class="w-4 h-4 bg-blue-500 rounded-full mt-1 flex-shrink-0"></div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-900">Acknowledged</span>
                                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($booking->acknowledged_at)->format('F d, Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->acknowledged_at)->format('g:i A') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($booking->payment && $booking->payment->payment_verified_at)
                            <div class="flex items-start space-x-4">
                                <div class="w-4 h-4 bg-green-500 rounded-full mt-1 flex-shrink-0"></div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-900">Payment Verified</span>
                                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($booking->payment->payment_verified_at)->format('F d, Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->payment->payment_verified_at)->format('g:i A') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($booking->completed_at)
                            <div class="flex items-start space-x-4">
                                <div class="w-4 h-4 bg-emerald-500 rounded-full mt-1 flex-shrink-0"></div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-900">Completed</span>
                                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($booking->completed_at)->format('F d, Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->completed_at)->format('g:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>


                <!-- Payment Information -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-credit-card text-green-600 mr-3"></i>
                        Payment Information
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                                <span class="block text-xs font-medium text-green-600 mb-1">Service Fee</span>
                                <span class="text-sm font-semibold text-gray-900">
                                    @if($booking->service && $booking->service->fees)
                                        @php
                                            $feeInfo = $booking->service->getFeeForDate($booking->service_date);
                                            $feeAmount = is_array($feeInfo) ? ($feeInfo['amount'] ?? 0) : 0;
                                            $feeAmount = is_numeric($feeAmount) ? (float)$feeAmount : 0;
                                        @endphp
                                        ₱{{ number_format($feeAmount, 2) }}
                                    @else
                                        Contact office
                                    @endif
                                </span>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                                <span class="block text-xs font-medium text-green-600 mb-1">Total Fee</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $booking->formatted_total_fee ?? 'Contact office' }}</span>
                            </div>
                        </div>
                        
                        @if($booking->payment)
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                <h4 class="text-sm font-medium text-green-800 mb-3">Payment Details</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="block text-xs font-medium text-green-600 mb-1">Payment Status</span>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $booking->payment->payment_status_badge }}">
                                            {{ ucfirst($booking->payment->payment_status) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="block text-xs font-medium text-green-600 mb-1">Payment Method</span>
                                        <span class="text-sm text-gray-900">{{ ucfirst($booking->payment->payment_method ?? 'Not specified') }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-info-circle text-yellow-600"></i>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-800">No Payment Submitted</p>
                                        <p class="text-xs text-yellow-700">Payment information will appear here once submitted</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Service Requirements Preview -->
                @if($booking->service->requirements && count($booking->service->requirements) > 0)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-list-check text-[#0d5c2f] mr-3"></i>
                            Service Requirements
                        </h3>
                        <div class="space-y-3">
                            @foreach(array_slice($booking->service->requirements, 0, 5) as $requirement)
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-check text-green-500 text-sm"></i>
                                    <span class="text-sm text-gray-700">{{ $requirement }}</span>
                                </div>
                            @endforeach
                            @if(count($booking->service->requirements) > 5)
                                <div class="text-sm text-gray-500 text-center pt-2">
                                    +{{ count($booking->service->requirements) - 5 }} more requirements
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- User's Rating -->
                @if($booking->service->hasUserRating(Auth::id(), $booking->id))
                    @php
                        $userRating = $booking->service->ratings()->where('user_id', Auth::id())->where('booking_id', $booking->id)->first();
                    @endphp
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-star text-blue-600 mr-3"></i>
                            Your Rating
                        </h3>
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="text-center mb-4">
                                <div class="flex items-center justify-center space-x-2 mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $userRating->rating)
                                            <i class="fas fa-star text-yellow-400 text-3xl"></i>
                                        @else
                                            <i class="far fa-star text-gray-300 text-3xl"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-3xl font-bold text-gray-900">{{ $userRating->rating }}/5</span>
                            </div>
                            
                            @if($userRating->comment)
                                <div class="bg-white rounded-lg p-3 border border-blue-200 mb-4">
                                    <p class="text-gray-700 italic text-sm text-center">"{{ $userRating->comment }}"</p>
                                </div>
                            @endif
                            
                            <div class="text-center">
                                <button onclick="editRating({{ $userRating->id }}, {{ $userRating->rating }}, '{{ $userRating->comment }}')" 
                                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                    <i class="fas fa-edit mr-2"></i>Edit Rating
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-star text-gray-400 mr-3"></i>
                            Your Rating
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 text-center">
                            <i class="fas fa-star text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500 text-sm mb-4">You haven't rated this service yet</p>
                            @if($booking->status === 'completed')
                                <button onclick="openRatingModal({{ $booking->id }}, {{ $booking->service_id }}, '{{ $booking->service->name }}')" 
                                        class="px-6 py-2.5 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm font-medium">
                                    <i class="fas fa-star mr-2"></i>Rate Service
                                </button>
                            @else
                                <p class="text-xs text-gray-400">Rate this service after completion</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full animate-slide-up">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Cancel Booking</h3>
                        <p class="text-sm text-gray-600">Are you sure you want to cancel?</p>
                    </div>
                </div>
                
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-red-600 mt-0.5"></i>
                        <div class="text-sm text-red-800">
                            <p class="font-medium mb-1">This action cannot be undone.</p>
                            <p>Your booking will be permanently cancelled and you'll need to create a new one if needed.</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Service:</span>
                        <span class="font-medium text-gray-900" id="cancelServiceName"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Date:</span>
                        <span class="font-medium text-gray-900" id="cancelServiceDate"></span>
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3">
                    <button onclick="closeCancelModal()" 
                            class="px-6 py-2.5 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Keep Booking
                    </button>
                    <button onclick="confirmCancel()" 
                            class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        <i class="fas fa-times mr-2"></i>
                        Cancel Booking
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rate Service Modal -->
<div id="ratingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full animate-slide-up">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-star text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900" id="ratingModalTitle">Rate Service</h3>
                        <p class="text-sm text-gray-600" id="ratingModalSubtitle">How was your experience?</p>
                    </div>
                </div>
                
                <div class="mb-6">
                    <div class="flex justify-between text-sm mb-3">
                        <span class="text-gray-600">Service:</span>
                        <span class="font-medium text-gray-900" id="ratingServiceName"></span>
                    </div>
                </div>
                
                <!-- Star Rating -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Your Rating:</label>
                    <div class="flex items-center space-x-2" id="starRating">
                        <button type="button" class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="1">
                            <i class="far fa-star"></i>
                        </button>
                        <button type="button" class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="2">
                            <i class="far fa-star"></i>
                        </button>
                        <button type="button" class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="3">
                            <i class="far fa-star"></i>
                        </button>
                        <button type="button" class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="4">
                            <i class="far fa-star"></i>
                        </button>
                        <button type="button" class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="5">
                            <i class="far fa-star"></i>
                        </button>
                    </div>
                    <div class="text-sm text-gray-500 mt-2" id="ratingText">Click to rate</div>
                </div>
                
                <!-- Comment -->
                <div class="mb-6">
                    <label for="ratingComment" class="block text-sm font-medium text-gray-700 mb-2">Comment (Optional):</label>
                    <textarea id="ratingComment" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] resize-none" placeholder="Share your experience..."></textarea>
                </div>
                
                <div class="flex items-center justify-end space-x-3">
                    <button onclick="closeRatingModal()" 
                            class="px-6 py-2.5 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </button>
                    <button onclick="submitRating()" 
                            class="px-6 py-2.5 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-medium">
                        <i class="fas fa-star mr-2"></i>
                        <span id="submitButtonText">Submit Rating</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.animate-slide-up {
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
let currentBookingId = null;
let currentServiceId = null;
let currentRatingId = null;
let selectedRating = 0;
let isEditMode = false;

function openCancelModal(bookingId, serviceName, serviceDate) {
    currentBookingId = bookingId;
    document.getElementById('cancelServiceName').textContent = serviceName;
    document.getElementById('cancelServiceDate').textContent = serviceDate;
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    currentBookingId = null;
}

function confirmCancel() {
    if (currentBookingId) {
        window.location.href = `/booking/cancel/${currentBookingId}`;
    }
}

function openRatingModal(bookingId, serviceId, serviceName) {
    currentBookingId = bookingId;
    currentServiceId = serviceId;
    currentRatingId = null;
    selectedRating = 0;
    isEditMode = false;
    
    document.getElementById('ratingModalTitle').textContent = 'Rate Service';
    document.getElementById('ratingModalSubtitle').textContent = 'How was your experience?';
    document.getElementById('submitButtonText').textContent = 'Submit Rating';
    document.getElementById('ratingServiceName').textContent = serviceName;
    document.getElementById('ratingComment').value = '';
    
    document.getElementById('ratingModal').classList.remove('hidden');
    resetStars();
}

function editRating(ratingId, rating, comment) {
    currentRatingId = ratingId;
    currentBookingId = null;
    currentServiceId = null;
    selectedRating = rating;
    isEditMode = true;
    
    document.getElementById('ratingModalTitle').textContent = 'Edit Rating';
    document.getElementById('ratingModalSubtitle').textContent = 'Update your rating';
    document.getElementById('submitButtonText').textContent = 'Update Rating';
    document.getElementById('ratingServiceName').textContent = 'Your Service';
    document.getElementById('ratingComment').value = comment || '';
    
    document.getElementById('ratingModal').classList.remove('hidden');
    setRating(rating);
}

function closeRatingModal() {
    document.getElementById('ratingModal').classList.add('hidden');
    currentBookingId = null;
    currentServiceId = null;
    currentRatingId = null;
    selectedRating = 0;
    isEditMode = false;
    resetStars();
}

function resetStars() {
    const stars = document.querySelectorAll('#starRating button');
    stars.forEach((star, index) => {
        const icon = star.querySelector('i');
        icon.className = 'far fa-star';
        star.classList.remove('text-yellow-400');
        star.classList.add('text-gray-300');
    });
    document.getElementById('ratingText').textContent = 'Click to rate';
}

function setRating(rating) {
    selectedRating = rating;
    const stars = document.querySelectorAll('#starRating button');
    const ratingTexts = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
    
    stars.forEach((star, index) => {
        const icon = star.querySelector('i');
        if (index < rating) {
            icon.className = 'fas fa-star';
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            icon.className = 'far fa-star';
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
    
    document.getElementById('ratingText').textContent = ratingTexts[rating];
}

function submitRating() {
    if (selectedRating === 0) {
        alert('Please select a rating before submitting.');
        return;
    }
    
    const comment = document.getElementById('ratingComment').value;
    
    if (isEditMode) {
        // Update existing rating
        fetch(`/service-rating/${currentRatingId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                rating: selectedRating,
                comment: comment
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Rating updated successfully!', 'success');
                closeRatingModal();
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showToast(data.message || 'Error updating rating', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error updating rating', 'error');
        });
    } else {
        // Create new rating
        fetch('/service-rating', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                booking_id: currentBookingId,
                service_id: currentServiceId,
                rating: selectedRating,
                comment: comment
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Rating submitted successfully!', 'success');
                closeRatingModal();
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showToast(data.message || 'Error submitting rating', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error submitting rating', 'error');
        });
    }
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium shadow-lg transition-all duration-300 transform translate-x-full`;
    
    if (type === 'success') {
        toast.classList.add('bg-green-600');
    } else if (type === 'error') {
        toast.classList.add('bg-red-600');
    } else {
        toast.classList.add('bg-blue-600');
    }
    
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Initialize star rating functionality
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('#starRating button');
    stars.forEach((star, index) => {
        star.addEventListener('click', () => setRating(index + 1));
        star.addEventListener('mouseenter', () => {
            if (selectedRating === 0) {
                const stars = document.querySelectorAll('#starRating button');
                stars.forEach((s, i) => {
                    const icon = s.querySelector('i');
                    if (i <= index) {
                        icon.className = 'fas fa-star';
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    }
                });
            }
        });
        star.addEventListener('mouseleave', () => {
            if (selectedRating === 0) {
                resetStars();
            }
        });
    });
});

// Close modals when clicking outside
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCancelModal();
    }
});

document.getElementById('ratingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRatingModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCancelModal();
        closeRatingModal();
    }
});
</script>
@endsection 