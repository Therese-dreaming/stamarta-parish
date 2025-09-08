@extends('layouts.user')

@section('title', 'Profile')

@section('content')
<main class="flex-grow">
    <!-- Hero Section -->
    <div class="relative h-[40vh] -mt-[80px]">
        <img src="{{ asset('images/church-bg.jpg') }}" alt="Church Background" class="absolute inset-0 w-full h-full object-cover brightness-50" />
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">Profile</h1>
            <p class="text-xl">Your account information</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-[#0d5c2f] mb-8 text-center">User Profile</h2>
                
                <div class="bg-gray-50 p-8 rounded-lg">
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-2xl font-bold text-[#0d5c2f]">Profile Information</h2>
                        <a href="{{ route('profile.edit') }}" 
                           class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#1a8045] transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profile
                        </a>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-[#0d5c2f] mb-4 flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                Personal Information
                            </h3>
                            <div class="space-y-3">
                                <p class="text-gray-600">
                                    <strong>Name:</strong> {{ Auth::user()->name }}
                                </p>
                                <p class="text-gray-600">
                                    <strong>Email:</strong> {{ Auth::user()->email }}
                                </p>
                                <p class="text-gray-600">
                                    <strong>Date of Birth:</strong> 
                                    @if(Auth::user()->date_of_birth)
                                        {{ Auth::user()->date_of_birth->format('M d, Y') }}
                                        <span class="text-sm text-gray-500">({{ Auth::user()->age }} years old)</span>
                                    @else
                                        <span class="text-amber-600">Not provided</span>
                                    @endif
                                </p>
                                <p class="text-gray-600">
                                    <strong>Member since:</strong> {{ Auth::user()->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-[#0d5c2f] mb-4 flex items-center">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Account Status
                            </h3>
                            <div class="space-y-3">
                                <p class="text-gray-600">
                                    <strong>Email Verified:</strong> 
                                    @if(Auth::user()->email_verified_at)
                                        <span class="text-green-600">✓ Verified</span>
                                    @else
                                        <span class="text-red-600">✗ Not verified</span>
                                    @endif
                                </p>
                                
                                @if(Auth::user()->date_of_birth)
                                    <p class="text-gray-600">
                                        <strong>Wedding Booking Eligible:</strong> 
                                        @if(Auth::user()->canBookWedding())
                                            <span class="text-green-600">✓ Yes</span>
                                        @else
                                            <span class="text-red-600">✗ No</span>
                                            @if(Auth::user()->getWeddingBooking())
                                                <span class="text-sm text-gray-500">(Existing booking: {{ Auth::user()->getWeddingBooking()->status }})</span>
                                            @endif
                                        @endif
                                    </p>
                                @else
                                    <p class="text-gray-600">
                                        <strong>Wedding Booking Eligible:</strong> 
                                        <span class="text-amber-600">✗ Please provide date of birth</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection 