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
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-[#0d5c2f] mb-2">Personal Information</h3>
                            <p class="text-gray-600"><strong>Name:</strong> {{ Auth::user()->name }}</p>
                            <p class="text-gray-600"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            <p class="text-gray-600"><strong>Member since:</strong> {{ Auth::user()->created_at->format('M d, Y') }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-[#0d5c2f] mb-2">Account Status</h3>
                            <p class="text-gray-600">
                                <strong>Email Verified:</strong> 
                                @if(Auth::user()->email_verified_at)
                                    <span class="text-green-600">✓ Verified</span>
                                @else
                                    <span class="text-red-600">✗ Not verified</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection 