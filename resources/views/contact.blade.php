@extends('layouts.user')

@section('title', 'Contact Us & Support')

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

                    <div class="py-8">
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
                    
                    @if (session('status'))
                        <div class="bg-green-50 text-green-700 border border-green-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ session('status') }}
                            </div>
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif
                    
                    @if (session('info'))
                        <div class="bg-blue-50 text-blue-700 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                {{ session('info') }}
                            </div>
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span class="font-medium">Please fix the following errors:</span>
                            </div>
                            <ul class="list-disc ml-5 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tickets.store') }}" class="space-y-6">
                        @csrf

                        @auth
                            <div class="flex items-start gap-4 bg-[#0d5c2f]/10 border border-[#0d5c2f]/20 rounded-lg p-4">
                                <div class="w-10 h-10 rounded-full bg-[#0d5c2f] text-white flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium text-[#0d5c2f]">{{ auth()->user()->name }}</span>
                                    </p>
                                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                                    <p class="text-xs text-gray-500 mt-1">We'll send the ticket with your account details.</p>
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors" 
                                           required />
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors" 
                                           required />
                                </div>
                            </div>
                        @endauth

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors" 
                                   required 
                                   placeholder="Brief description of your inquiry" />
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea id="message" name="message" rows="6" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors resize-none" 
                                      required 
                                      placeholder="Please describe your concern or inquiry with details. We'll respond to your email as soon as possible.">{{ old('message') }}</textarea>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                We'll respond to your email within 24-48 hours. You can also continue in the chatbot anytime.
                            </p>
                        </div>
                        
                        <div class="flex items-center justify-between pt-4">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                Response time: 24-48 hours
                            </div>
                            <button type="submit" 
                                    class="bg-[#0d5c2f] text-white px-8 py-3 rounded-lg hover:bg-[#0d5c2f]/90 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection