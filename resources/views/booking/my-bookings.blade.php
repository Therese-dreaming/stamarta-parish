@extends('layouts.user')

@section('title', 'My Bookings')

@section('content')
@include('components.toast')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-[#0d5c2f]/10 rounded-full mb-6">
                <i class="fas fa-calendar-check text-2xl text-[#0d5c2f]"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-3">My Bookings</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Track the status and manage all your service bookings in one place
            </p>
        </div>

        @if($bookings->count() > 0)
            <!-- Stats Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                @php
                    $pendingCount = $bookings->where('status', 'pending')->count();
                    $acknowledgedCount = $bookings->where('status', 'acknowledged')->count();
                    $approvedCount = $bookings->where('status', 'approved')->count();
                    $completedCount = $bookings->where('status', 'completed')->count();
                @endphp
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 text-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 mb-1">{{ $pendingCount }}</div>
                    <div class="text-sm text-gray-600">Pending</div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 mb-1">{{ $acknowledgedCount }}</div>
                    <div class="text-sm text-gray-600">Acknowledged</div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-thumbs-up text-green-600 text-xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 mb-1">{{ $approvedCount }}</div>
                    <div class="text-sm text-gray-600">Approved</div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 text-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-flag-checkered text-emerald-600 text-xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 mb-1">{{ $completedCount }}</div>
                    <div class="text-sm text-gray-600">Completed</div>
                </div>
            </div>

            <!-- User Rating Summary -->
            @php
                $userRatings = collect();
                foreach($bookings as $booking) {
                    if($booking->service->hasUserRating(Auth::id(), $booking->id)) {
                        $rating = $booking->service->ratings()->where('user_id', Auth::id())->where('booking_id', $booking->id)->first();
                        if($rating) {
                            $userRatings->push($rating);
                        }
                    }
                }
                $averageUserRating = $userRatings->count() > 0 ? round($userRatings->avg('rating'), 1) : 0;
                $totalUserRatings = $userRatings->count();
            @endphp
            
            @if($totalUserRatings > 0)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                            <i class="fas fa-star text-yellow-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Your Rating Summary</h3>
                        <div class="flex items-center justify-center space-x-4 mb-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-gray-900">{{ $averageUserRating }}</div>
                                <div class="text-sm text-gray-600">Average Rating</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-gray-900">{{ $totalUserRatings }}</div>
                                <div class="text-sm text-gray-600">Services Rated</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-center space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $averageUserRating)
                                    <i class="fas fa-star text-yellow-400 text-lg"></i>
                                @elseif($i - $averageUserRating < 1)
                                    <i class="fas fa-star-half-alt text-yellow-400 text-lg"></i>
                                @else
                                    <i class="far fa-star text-gray-300 text-lg"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            @endif

            <!-- Bookings List -->
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
                        <!-- Status Header Bar -->
                        <div class="h-2 {{ $booking->status === 'pending' ? 'bg-yellow-400' : 
                                        ($booking->status === 'acknowledged' ? 'bg-blue-400' : 
                                        ($booking->status === 'payment_hold' ? 'bg-orange-400' : 
                                        ($booking->status === 'approved' ? 'bg-green-400' : 
                                        ($booking->status === 'rejected' ? 'bg-red-400' : 
                                        ($booking->status === 'completed' ? 'bg-emerald-400' : 'bg-gray-400'))))) }}">
                        </div>
                        
                        <div class="p-6">
                            <!-- Booking Header -->
                            <div class="flex items-start justify-between mb-6">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $booking->service->name }}</h3>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $booking->status_badge }}">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">Booking ID: #{{ $booking->id }}</p>
                                </div>
                                
                                @if($booking->payment && $booking->payment->payment_status)
                                    <div class="text-right">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $booking->payment->payment_status_badge }}">
                                            {{ ucfirst($booking->payment->payment_status) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Booking Details Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-calendar text-blue-600"></i>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-medium text-gray-500">Date & Time</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $booking->formatted_date }}</span>
                                            <span class="block text-xs text-gray-600">{{ $booking->formatted_time }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-phone text-green-600"></i>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-medium text-gray-500">Contact</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $booking->contact_phone }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-money-bill-wave text-purple-600"></i>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-medium text-gray-500">Total Fee</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $booking->formatted_total_fee ?? 'Contact office' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-clock text-orange-600"></i>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-medium text-gray-500">Created</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $booking->created_at->format('M d, Y') }}</span>
                                            <span class="block text-xs text-gray-600">{{ $booking->created_at->format('g:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status-specific Information -->
                            @if($booking->status === 'pending')
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-6">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-clock text-yellow-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-base font-semibold text-yellow-800 mb-2">Awaiting Acknowledgment</h4>
                                            <p class="text-sm text-yellow-700 mb-3">Your booking is being reviewed by the parish office. You will be notified once it's acknowledged.</p>
                                            <div class="flex items-center space-x-2 text-xs text-yellow-600">
                                                <i class="fas fa-info-circle"></i>
                                                <span>Estimated review time: 1-2 business days</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($booking->status === 'acknowledged')
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-check-circle text-blue-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-base font-semibold text-blue-800 mb-2">Booking Acknowledged</h4>
                                            <p class="text-sm text-blue-700 mb-3">Your booking has been acknowledged. Please submit your payment proof to proceed.</p>
                                            <a href="{{ route('booking.payment', $booking) }}" 
                                               class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                                                <i class="fas fa-credit-card mr-2"></i>
                                                Pay Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($booking->status === 'payment_hold')
                                <div class="bg-orange-50 border border-orange-200 rounded-xl p-5 mb-6">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-clock text-orange-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-base font-semibold text-orange-800 mb-2">Payment Under Review</h4>
                                            <p class="text-sm text-orange-700 mb-3">Your payment proof has been submitted and is being reviewed by the parish office.</p>
                                            <div class="flex items-center space-x-2 text-xs text-orange-600">
                                                <i class="fas fa-info-circle"></i>
                                                <span>Review typically takes 24-48 hours</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($booking->status === 'approved')
                                <div class="bg-green-50 border border-green-200 rounded-xl p-5 mb-6">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-base font-semibold text-green-800 mb-2">Booking Approved</h4>
                                            <p class="text-sm text-green-700 mb-3">Your booking has been approved! Please arrive 30 minutes before your scheduled time.</p>
                                            <div class="flex items-center space-x-2 text-xs text-green-600">
                                                <i class="fas fa-info-circle"></i>
                                                <span>Service date: {{ $booking->formatted_date }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($booking->status === 'rejected')
                                <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-6">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-times-circle text-red-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-base font-semibold text-red-800 mb-2">Booking Rejected</h4>
                                            <p class="text-sm text-red-700 mb-3">{{ $booking->notes ?? 'Your booking has been rejected. Please contact the parish office for more information.' }}</p>
                                            <a href="{{ route('contact') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                                <i class="fas fa-envelope mr-2"></i>
                                                Contact Office
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($booking->status === 'completed')
                                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 mb-6">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-flag-checkered text-emerald-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-base font-semibold text-emerald-800 mb-2">Service Completed</h4>
                                            <p class="text-sm text-emerald-700 mb-3">Your service has been completed successfully. Thank you for choosing our parish.</p>
                                            
                                            <!-- User's Rating Display -->
                                            @if($booking->service->hasUserRating(Auth::id(), $booking->id))
                                                @php
                                                    $userRating = $booking->service->ratings()->where('user_id', Auth::id())->where('booking_id', $booking->id)->first();
                                                @endphp
                                                <div class="bg-white rounded-lg p-4 mb-4 border border-emerald-200">
                                                    <div class="flex items-center justify-between mb-3">
                                                        <h5 class="text-sm font-medium text-emerald-800">Your Rating:</h5>
                                                        <button onclick="editRating({{ $userRating->id }}, {{ $userRating->rating }}, '{{ $userRating->comment }}')" 
                                                                class="text-xs text-emerald-600 hover:text-emerald-800 transition-colors">
                                                            <i class="fas fa-edit mr-1"></i>Edit
                                                        </button>
                                                    </div>
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <div class="flex items-center space-x-1">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $userRating->rating)
                                                                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                                                                @else
                                                                    <i class="far fa-star text-gray-300 text-sm"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <span class="text-sm font-medium text-gray-900">{{ $userRating->rating }}/5</span>
                                                    </div>
                                                    @if($userRating->comment)
                                                        <p class="text-sm text-gray-600 italic">"{{ $userRating->comment }}"</p>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            <div class="flex items-center space-x-3">
                                                @if(!empty($booking->certificate_path))
                                                    <a href="{{ Storage::url($booking->certificate_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm">
                                                        <i class="fas fa-certificate mr-2"></i>
                                                        View Certificate
                                                    </a>
                                                @endif
                                                
                                                @if(!$booking->service->hasUserRating(Auth::id(), $booking->id))
                                                    <button onclick="openRatingModal({{ $booking->id }}, {{ $booking->service_id }}, '{{ $booking->service->name }}')" 
                                                            class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                                                        <i class="fas fa-star mr-2"></i>
                                                        Rate Service
                                                    </button>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                                        <i class="fas fa-check mr-1"></i>
                                                        Rated
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Additional Notes -->
                            @if($booking->additional_notes)
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mb-6">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-sticky-note text-gray-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 mb-2">Additional Notes</h4>
                                            <p class="text-sm text-gray-600">{{ $booking->additional_notes }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- User's Rating Display (for all statuses) -->
                            @if($booking->service->hasUserRating(Auth::id(), $booking->id))
                                @php
                                    $userRating = $booking->service->ratings()->where('user_id', Auth::id())->where('booking_id', $booking->id)->first();
                                @endphp
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-star text-blue-600 text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="text-sm font-medium text-blue-800">Your Rating</h4>
                                                <button onclick="editRating({{ $userRating->id }}, {{ $userRating->rating }}, '{{ $userRating->comment }}')" 
                                                        class="text-xs text-blue-600 hover:text-blue-800 transition-colors">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </button>
                                            </div>
                                            <div class="flex items-center space-x-2 mb-2">
                                                <div class="flex items-center space-x-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $userRating->rating)
                                                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                                                        @else
                                                            <i class="far fa-star text-gray-300 text-sm"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">{{ $userRating->rating }}/5</span>
                                            </div>
                                            @if($userRating->comment)
                                                <p class="text-sm text-blue-700 italic">"{{ $userRating->comment }}"</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('booking.show', $booking) }}" 
                                       class="inline-flex items-center px-4 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                                        <i class="fas fa-eye mr-2"></i>
                                        View Details
                                    </a>
                                    
                                    @if($booking->status === 'acknowledged')
                                        <a href="{{ route('booking.payment', $booking) }}" 
                                           class="inline-flex items-center px-5 py-2.5 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                                            <i class="fas fa-credit-card mr-2"></i>
                                            Pay Now
                                        </a>
                                    @endif
                                    
                                    @if(!empty($booking->certificate_path))
                                        <a href="{{ Storage::url($booking->certificate_path) }}" target="_blank" 
                                           class="inline-flex items-center px-4 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                                            <i class="fas fa-certificate mr-2"></i>
                                            View Certificate
                                        </a>
                                    @endif
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    @if(in_array($booking->status, ['pending', 'acknowledged', 'payment_hold']))
                                        <button onclick="openCancelModal({{ $booking->id }}, '{{ $booking->service->name }}', '{{ $booking->formatted_date }}')" 
                                                class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                                            <i class="fas fa-times mr-2"></i>
                                            Cancel Booking
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 px-6 py-4">
                        {{ $bookings->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-calendar-times text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">No Bookings Found</h3>
                <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
                    You haven't made any bookings yet. Start by exploring our available services.
                </p>
                <a href="{{ route('services.index') }}" 
                   class="inline-flex items-center px-8 py-4 bg-[#0d5c2f] text-white rounded-xl hover:bg-[#0d5c2f]/90 transition-all duration-200 text-lg font-medium shadow-lg hover:shadow-xl">
                    <i class="fas fa-search mr-3"></i>
                    Browse Services
                </a>
            </div>
        @endif
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
        // Redirect to cancel booking route
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
                // Reload page to show updated rating
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
                // Reload page to show updated rating status
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
    // Create toast element
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
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
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

// Close modal when clicking outside
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

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCancelModal();
        closeRatingModal();
    }
});
</script>
@endsection 