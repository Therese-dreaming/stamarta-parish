@extends('layouts.user')

@section('title', 'Contact Us')

@section('content')
<main class="flex-grow">
    <!-- Hero Section -->
    <div class="relative h-[40vh] -mt-[80px]">
        <img src="{{ asset('images/church-bg.jpg') }}" alt="Church Background" class="absolute inset-0 w-full h-full object-cover brightness-50" />
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">Contact Us</h1>
            <p class="text-xl">Get in touch with our parish community</p>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div class="space-y-8">
                    <div>
                        <h2 class="text-3xl font-bold text-[#0d5c2f] mb-6">Parish Information</h2>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 flex items-center justify-center bg-[#0d5c2f]/10 rounded-lg">
                                    <i class="fas fa-location-dot text-[#0d5c2f] text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Address</h3>
                                    <p class="text-gray-600">{!! nl2br(e(\App\Services\ContentService::getSetting('contact_address', 'B. Morcilla St.,<br>Pateros, Metro Manila'))) !!}</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 flex items-center justify-center bg-[#0d5c2f]/10 rounded-lg">
                                    <i class="fas fa-phone text-[#0d5c2f] text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Phone</h3>
                                    <p class="text-gray-600">{{ \App\Services\ContentService::getSetting('contact_phone', '0917-366-4359') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 flex items-center justify-center bg-[#0d5c2f]/10 rounded-lg">
                                    <i class="fas fa-envelope text-[#0d5c2f] text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Email</h3>
                                    <p class="text-gray-600">{{ \App\Services\ContentService::getSetting('contact_email', 'diocesansaintmartha@gmail.com') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class = "py-8">
                        <h2 class="text-3xl font-bold text-[#0d5c2f] mb-6">Follow Us</h2>
                        <div class="flex space-x-4">
                            @if(\App\Services\ContentService::getSetting('facebook_url'))
                            <a href="{{ \App\Services\ContentService::getSetting('facebook_url') }}" target="_blank" class="w-12 h-12 flex items-center justify-center bg-[#0d5c2f]/10 rounded-lg hover:bg-[#0d5c2f] group transition-colors">
                                <i class="fab fa-facebook-f text-[#0d5c2f] group-hover:text-white text-2xl transition-colors"></i>
                            </a>
                            @endif
                            @if(\App\Services\ContentService::getSetting('youtube_url'))
                            <a href="{{ \App\Services\ContentService::getSetting('youtube_url') }}" target="_blank" class="w-12 h-12 flex items-center justify-center bg-[#0d5c2f]/10 rounded-lg hover:bg-[#0d5c2f] group transition-colors">
                                <i class="fab fa-youtube text-[#0d5c2f] group-hover:text-white text-2xl transition-colors"></i>
                            </a>
                            @endif
                            @if(\App\Services\ContentService::getSetting('instagram_url'))
                            <a href="{{ \App\Services\ContentService::getSetting('instagram_url') }}" target="_blank" class="w-12 h-12 flex items-center justify-center bg-[#0d5c2f]/10 rounded-lg hover:bg-[#0d5c2f] group transition-colors">
                                <i class="fab fa-instagram text-[#0d5c2f] group-hover:text-white text-2xl transition-colors"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-gray-50 p-8 rounded-xl">
                    <h2 class="text-3xl font-bold text-[#0d5c2f] mb-6">Send us a Message</h2>
                    <form class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-[#0d5c2f] text-white py-2 px-4 rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection