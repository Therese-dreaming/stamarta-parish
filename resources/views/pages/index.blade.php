@extends('layouts.user')

@section('title', 'All Pages')

@section('content')
<!-- Hero Section -->
<div class="relative h-[40vh] -mt-[80px]">
    <img src="{{ asset('images/church-bg.jpg') }}" alt="Church Background" class="absolute inset-0 w-full h-full object-cover brightness-50" />
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
        <h1 class="text-5xl md:text-6xl font-bold mb-4">Pages</h1>
        <p class="text-xl">Explore our content and learn more about our parish</p>
    </div>
</div>

<!-- Pages Directory Section -->
<div class="bg-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Browse Our Pages</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Discover information about our parish, activities, and community resources.
                </p>
            </div>

            @if($pages->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($pages as $page)
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $page->title }}</h3>
                                
                                @if($page->meta_description)
                                    <p class="text-gray-600 mb-4 line-clamp-3">{{ $page->meta_description }}</p>
                                @else
                                    <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit(strip_tags($page->content), 120) }}</p>
                                @endif

                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-500">
                                        @if($page->creator)
                                            By {{ $page->creator->name }}
                                        @endif
                                        @if($page->published_at)
                                            • {{ $page->published_at->format('M d, Y') }}
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('page.show', $page->slug) }}" 
                                       class="inline-flex items-center text-[#0d5c2f] hover:text-[#0d5c2f]/80 font-medium transition-colors">
                                        Read More
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($pages->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $pages->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-16">
                    <i class="fas fa-file-alt text-6xl text-gray-300 mb-6"></i>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">No Pages Available</h3>
                    <p class="text-lg text-gray-600">Check back later for new content!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
