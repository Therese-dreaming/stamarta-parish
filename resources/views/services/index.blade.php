@extends('layouts.user')

@section('title', 'Our Services')

@section('content')
<!-- Hero Section -->
<div class="relative bg-[#0d5c2f] text-white">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-4 font-['Great_Vibes']">Our Services</h1>
            <p class="text-xl md:text-2xl mb-8">Discover the spiritual services we offer to our community</p>
        </div>
    </div>
</div>

<!-- Services Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Available Services</h2>
            <p class="text-lg text-gray-600">Choose from our range of spiritual services and ceremonies</p>
        </div>

        @if($services->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">
                @foreach($services as $service)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 h-full flex flex-col">
                    <!-- Service Header -->
                    <div class="bg-[#0d5c2f] text-white p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $service->name }}</h3>
                        <p class="text-[#0d5c2f]/80">{{ $service->formatted_duration }}</p>
                    </div>
                    
                    <!-- Service Content -->
                    <div class="p-6 flex-1 flex flex-col">
                        @if($service->description)
                            <p class="text-gray-600 mb-4 flex-1">{{ Str::limit($service->description, 120) }}</p>
                        @endif
                        
                        <!-- Rating Display -->
                        @if($service->total_ratings > 0)
                            <div class="mb-4">
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="flex items-center space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $service->average_rating)
                                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                            @elseif($i - $service->average_rating < 1)
                                                <i class="fas fa-star-half-alt text-yellow-400 text-sm"></i>
                                            @else
                                                <i class="far fa-star text-gray-300 text-sm"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $service->average_rating }}</span>
                                    <span class="text-sm text-gray-500">({{ $service->total_ratings }} {{ Str::plural('rating', $service->total_ratings) }})</span>
                                </div>
                                
                                <!-- User's Rating (if authenticated and has rated) -->
                                @auth
                                    @if($service->hasUserRating(Auth::id(), null))
                                        @php
                                            $userRating = $service->ratings()->where('user_id', Auth::id())->first();
                                        @endphp
                                        @if($userRating)
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs font-medium text-blue-800">Your Rating:</span>
                                                    <div class="flex items-center space-x-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $userRating->rating)
                                                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                                            @else
                                                                <i class="far fa-star text-gray-300 text-xs"></i>
                                                            @endif
                                                        @endfor
                                                        <span class="text-xs text-blue-800 ml-1">{{ $userRating->rating }}/5</span>
                                                    </div>
                                                </div>
                                                @if($userRating->comment)
                                                    <p class="text-xs text-blue-700 mt-1 italic">"{{ Str::limit($userRating->comment, 50) }}"</p>
                                                @endif
                                            </div>
                                        @endif
                                    @endif
                                @endauth
                            </div>
                        @endif
                        
                        <!-- Fees -->
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Fees:</h4>
                            @php
                                $fees = $service->fees ?? [];
                                $regularFee = null;
                                $otherFees = [];
                                
                                foreach ($fees as $type => $feeData) {
                                    if (is_array($feeData) && isset($feeData['amount'])) {
                                        if (strtolower($type) === 'regular') {
                                            $regularFee = $feeData;
                                        } else {
                                            $otherFees[$type] = $feeData;
                                        }
                                    } else {
                                        if (strtolower($type) === 'regular') {
                                            $regularFee = ['amount' => $feeData, 'description' => 'Regular'];
                                        } else {
                                            $otherFees[$type] = ['amount' => $feeData, 'description' => ucfirst($type)];
                                        }
                                    }
                                }
                            @endphp
                            
                            @if($regularFee)
                                <p class="text-sm text-gray-600">{{ $regularFee['description'] }}: ₱{{ number_format($regularFee['amount'], 2) }}</p>
                            @endif
                            
                            @foreach($otherFees as $type => $feeData)
                                <p class="text-sm text-gray-600 mt-1">{{ $feeData['description'] }}: ₱{{ number_format($feeData['amount'], 2) }}</p>
                            @endforeach
                            
                            @if(empty($fees))
                                <p class="text-sm text-gray-600">Contact office for pricing</p>
                            @endif
                        </div>
                        
                        <!-- Schedule Preview -->
                        @if($service->schedules)
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Available Days:</h4>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_keys($service->schedules) as $day)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                            {{ ucfirst($day) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Requirements Preview -->
                        @if($service->requirements && count($service->requirements) > 0)
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-900 mb-2">Requirements:</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    @foreach(array_slice($service->requirements, 0, 3) as $requirement)
                                        <li class="flex items-center">
                                            <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                            {{ $requirement }}
                                        </li>
                                    @endforeach
                                    @if(count($service->requirements) > 3)
                                        <li class="text-gray-500 text-xs">
                                            +{{ count($service->requirements) - 3 }} more requirements
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                        
                        <!-- Action Button -->
                        <div class="mt-auto pt-4">
                            @auth
                                @if(Auth::user()->email_verified_at)
                                    <a href="{{ route('services.book', $service) }}" 
                                       class="w-full bg-[#0d5c2f] text-white px-6 py-3 rounded-lg hover:bg-[#0d5c2f]/90 transition-colors text-center block">
                                        <i class="fas fa-calendar-plus mr-2"></i>Book Now
                                    </a>
                                @else
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                                            <div>
                                                <p class="text-sm font-medium text-yellow-800">Email Verification Required</p>
                                                <p class="text-xs text-yellow-700">Please verify your email to book services</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('verification.notice') }}" 
                                           class="mt-2 inline-block text-sm text-[#0d5c2f] hover:underline">
                                            Verify Email Address
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                        <div>
                                            <p class="text-sm font-medium text-blue-800">Login Required</p>
                                            <p class="text-xs text-blue-700">Please login to book services</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 space-x-2">
                                        <a href="{{ route('login') }}" 
                                           class="inline-block text-sm text-[#0d5c2f] hover:underline">
                                            Login
                                        </a>
                                        <span class="text-gray-400">|</span>
                                        <a href="{{ route('signup') }}" 
                                           class="inline-block text-sm text-[#0d5c2f] hover:underline">
                                            Register
                                        </a>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Services Available</h3>
                <p class="text-gray-500">Please check back later for available services.</p>
            </div>
        @endif
    </div>
</div>

<!-- Contact Section -->
<div class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Need Help?</h3>
            <p class="text-gray-600 mb-8">Contact us if you have any questions about our services or booking process.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <i class="fas fa-phone text-3xl text-[#0d5c2f] mb-4"></i>
                    <h4 class="font-semibold text-gray-900 mb-2">Call Us</h4>
                    <p class="text-gray-600">+63 2 1234 5678</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-envelope text-3xl text-[#0d5c2f] mb-4"></i>
                    <h4 class="font-semibold text-gray-900 mb-2">Email Us</h4>
                    <p class="text-gray-600">info@stamarta.com</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-map-marker-alt text-3xl text-[#0d5c2f] mb-4"></i>
                    <h4 class="font-semibold text-gray-900 mb-2">Visit Us</h4>
                    <p class="text-gray-600">B. Morcilla St., Pateros</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 