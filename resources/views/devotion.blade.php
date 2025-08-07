@extends('layouts.user')

@section('title', 'Devotion')

@section('content')
<main class="flex-grow">
    <!-- Hero Section -->
    <div class="relative h-[40vh] -mt-[80px]">
        <img src="{{ asset('images/church-bg.jpg') }}" alt="Church Background" class="absolute inset-0 w-full h-full object-cover brightness-50" />
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">Devotion</h1>
            <p class="text-xl">Spiritual practices and devotions</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-[#0d5c2f] mb-8">Devotional Practices</h2>
                <p class="text-lg text-gray-600 mb-8">
                    Discover the various devotional practices and spiritual traditions of our parish.
                </p>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-xl font-semibold text-[#0d5c2f] mb-4">Prayer</h3>
                        <p class="text-gray-600">Daily prayers, novenas, and special prayer intentions.</p>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-xl font-semibold text-[#0d5c2f] mb-4">Meditation</h3>
                        <p class="text-gray-600">Guided meditation sessions and spiritual reflection.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection 