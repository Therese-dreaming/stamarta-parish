@extends('layouts.user')

@section('content')
<!-- Hero Section -->
<div class="relative bg-[#0d5c2f] text-white">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-4 font-['Great_Vibes']">Page Unavailable</h1>
            <p class="text-xl md:text-2xl mb-8">This page is currently under maintenance</p>
        </div>
    </div>
</div>

<!-- Content Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <!-- Icon -->
            <div class="mb-8">
                <i class="fas fa-tools text-6xl text-[#0d5c2f]"></i>
            </div>
            
            <!-- Message -->
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Page Temporarily Unavailable</h2>
            <p class="text-lg text-gray-600 mb-8">
                The page "<strong>{{ $unpublishedPage->title }}</strong>" is currently being updated and is temporarily unavailable.
            </p>
            
            <!-- Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Page Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Page Title:</span>
                        <span class="text-gray-600">{{ $unpublishedPage->title }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Last Updated:</span>
                        <span class="text-gray-600">{{ $unpublishedPage->updated_at->format('M d, Y \a\t g:i A') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>Under Maintenance
                        </span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Author:</span>
                        <span class="text-gray-600">{{ $unpublishedPage->creator->name ?? 'Unknown' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="space-y-4">
                <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Return to Home
                </a>
                
                <div class="text-sm text-gray-500">
                    <p>If you need immediate assistance, please contact the parish office.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Section -->
<div class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Need Help?</h3>
            <p class="text-gray-600 mb-8">Contact us if you have any questions or need assistance.</p>
            
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