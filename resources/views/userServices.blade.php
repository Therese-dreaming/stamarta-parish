@extends('layouts.user')

@section('title', 'User Services')

@section('content')
<main class="flex-grow">
    <!-- Hero Section -->
    <div class="relative h-[40vh] -mt-[80px]">
        <img src="{{ asset('images/church-bg.jpg') }}" alt="Church Background" class="absolute inset-0 w-full h-full object-cover brightness-50" />
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">User Services</h1>
            <p class="text-xl">Parish services and sacraments</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-[#0d5c2f] mb-8">Parish Services</h2>
                <p class="text-lg text-gray-600 mb-8">
                    Welcome to our parish services page. Here you can learn about the various sacraments and services we offer.
                </p>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-xl font-semibold text-[#0d5c2f] mb-4">Sacraments</h3>
                        <p class="text-gray-600">Information about baptism, confirmation, marriage, and other sacraments.</p>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-xl font-semibold text-[#0d5c2f] mb-4">Bookings</h3>
                        <p class="text-gray-600">Schedule appointments for sacraments and other parish services.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection 