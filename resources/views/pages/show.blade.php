@extends('layouts.user')

@section('title', $page->meta_title ?? $page->title)

@section('content')
<!-- Hero Section -->
<div class="relative h-[40vh] -mt-[80px]">
    <img src="{{ asset('images/church-bg.jpg') }}" alt="Church Background" class="absolute inset-0 w-full h-full object-cover brightness-50" />
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
        <h1 class="text-5xl md:text-6xl font-bold mb-4">{{ $page->title }}</h1>
        @if($page->meta_description)
            <p class="text-xl">{{ $page->meta_description }}</p>
        @endif
    </div>
</div>

<!-- Page Content Section -->
<div class="bg-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Page Meta Information -->
            <div class="mb-8 text-center">
                <div class="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <i class="fas fa-calendar mr-2"></i>
                    <span>Published {{ $page->published_at->format('M d, Y') }}</span>
                    @if($page->creator)
                        <span class="mx-2">•</span>
                        <i class="fas fa-user mr-2"></i>
                        <span>By {{ $page->creator->name }}</span>
                    @endif
                </div>
            </div>

            <!-- Page Content -->
            <div class="prose prose-lg max-w-none">
                <div class="text-gray-700 leading-relaxed">
                    {!! nl2br(e($page->content)) !!}
                </div>
            </div>

            <!-- Page Footer -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <div>
                        <span>Last updated: {{ $page->updated_at->format('M d, Y \a\t g:i A') }}</span>
                    </div>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-[#0d5c2f] transition-colors">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="hover:text-[#0d5c2f] transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="hover:text-[#0d5c2f] transition-colors">
                            <i class="fas fa-share"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 