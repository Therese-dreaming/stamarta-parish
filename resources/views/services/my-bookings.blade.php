@extends('layouts.user')

@section('title', 'My Bookings')

@section('content')
<main class="flex-grow">
    <!-- Hero Section -->
    <div class="relative h-[40vh] -mt-[80px]">
        <img src="{{ asset('images/church-bg.jpg') }}" alt="Church Background" class="absolute inset-0 w-full h-full object-cover brightness-50" />
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">My Bookings</h1>
            <p class="text-xl">Your scheduled services</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-[#0d5c2f] mb-8 text-center">Your Bookings</h2>
                
                <div class="bg-gray-50 p-8 rounded-lg">
                    <div class="text-center">
                        <i class="fas fa-calendar text-6xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No Bookings Yet</h3>
                        <p class="text-gray-600 mb-6">You haven't made any service bookings yet.</p>
                        <a href="{{ route('userServices') }}" class="bg-[#0d5c2f] text-white px-6 py-2 rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                            Book a Service
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection 