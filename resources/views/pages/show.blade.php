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
        <div class="max-w-5xl mx-auto">
            <!-- Page Meta Information -->
            <div class="mb-8 text-center">
                <div class="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <i class="fas fa-calendar mr-2"></i>
                    <span>Published {{ optional($page->published_at)->format('M d, Y') }}</span>
                    @if($page->creator)
                        <span class="mx-2">•</span>
                        <i class="fas fa-user mr-2"></i>
                        <span>By {{ $page->creator->name }}</span>
                    @endif
                </div>
            </div>

            <!-- Render by layout -->
            @php($layout = $page->layout ?? 'one_column')
            @if(in_array($layout, ['image_top_text_bottom','text_top_image_bottom','image_left_text_right','image_right_text_left']) && $page->image)
                @php($imageUrl = $page->image->url)
            @endif

            @switch($layout)
                @case('image_top_text_bottom')
                    @isset($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg mb-6 object-cover">
                    @endisset
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">{!! $page->content !!}</div>
                    @break

                @case('text_top_image_bottom')
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed mb-6">{!! $page->content !!}</div>
                    @isset($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg object-cover">
                    @endisset
                    @break

                @case('image_left_text_right')
                    <div class="grid md:grid-cols-2 gap-8 items-start">
                        <div>@isset($imageUrl)<img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg object-cover">@endisset</div>
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">{!! nl2br(e($page->content)) !!}</div>
                    </div>
                    @break

                @case('image_right_text_left')
                    <div class="grid md:grid-cols-2 gap-8 items-start">
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed md:order-1">{!! $page->content !!}</div>
                        <div class="md:order-2">@isset($imageUrl)<img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg object-cover">@endisset</div>
                    </div>
                    @break

                @default
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">{!! $page->content !!}</div>
            @endswitch

            <!-- Page Footer -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <div>
                        <span>Last updated: {{ $page->updated_at->format('M d, Y \\a\\t g:i A') }}</span>
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