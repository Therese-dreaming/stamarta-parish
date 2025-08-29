@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Page Preview')

@section('content')
@include('components.toast')
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-6 relative">
            <div class="absolute right-0 top-0 w-24 h-24 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-2xl font-bold text-white">Page Preview</h1>
                    <p class="text-white/80 mt-1 flex items-center text-sm">
                        <i class="fas fa-eye mr-2"></i>Previewing: {{ $page->title }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    @if(!isset($isStaff) || !$isStaff)
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.edit', $page) : route('admin.cms.pages.edit', $page) }}" 
                       class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-colors flex items-center text-sm">
                        <i class="fas fa-edit mr-2"></i>Edit Page
                    </a>
                    @endif
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.index') : route('admin.cms.pages.index') }}" 
                       class="group px-3 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow text-sm">
                        <i class="fas fa-arrow-left mr-2 text-sm group-hover:-translate-x-1 transition-transform duration-200"></i>
                        <span>Back to Pages</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Public-like Preview (matches user page rendering) -->
    <!-- Hero Section -->
    <div class="relative h-[35vh] rounded-xl overflow-hidden border border-gray-200 shadow-md">
        <img src="{{ asset('images/church-bg.jpg') }}" alt="Church Background" class="absolute inset-0 w-full h-full object-cover brightness-50" />
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ $page->title }}</h1>
            @if($page->meta_description)
                <p class="text-base md:text-lg max-w-3xl opacity-90">{{ $page->meta_description }}</p>
            @endif
        </div>
    </div>

    <!-- Page Content Section -->
    <div class="bg-white py-8 rounded-xl border border-gray-200 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Page Meta Information -->
                <div class="mb-6 text-center">
                    <div class="flex items-center justify-center text-sm text-gray-500 mb-3">
                        <i class="fas fa-calendar mr-2 text-[#0d5c2f]"></i>
                        <span>Published {{ optional($page->published_at)->format('M d, Y') }}</span>
                        @if($page->creator)
                            <span class="mx-2">•</span>
                            <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>
                            <span>By {{ $page->creator->name }}</span>
                        @endif
                    </div>
                </div>

                @php($layout = $page->layout ?? 'one_column')
                @if(in_array($layout, ['image_top_text_bottom','text_top_image_bottom','image_left_text_right','image_right_text_left']) && $page->image)
                    @php($imageUrl = $page->image->url)
                @endif

                @switch($layout)
                    @case('image_top_text_bottom')
                        @isset($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg mb-6 object-cover shadow-sm">
                        @endisset
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">{!! nl2br(e($page->content)) !!}</div>
                        @break

                    @case('text_top_image_bottom')
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed mb-6">{!! nl2br(e($page->content)) !!}</div>
                        @isset($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg object-cover shadow-sm">
                        @endisset
                        @break

                    @case('image_left_text_right')
                        <div class="grid md:grid-cols-2 gap-6 items-start">
                            <div>@isset($imageUrl)<img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg object-cover shadow-sm">@endisset</div>
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">{!! nl2br(e($page->content)) !!}</div>
                        </div>
                        @break

                    @case('image_right_text_left')
                        <div class="grid md:grid-cols-2 gap-6 items-start">
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed md:order-1">{!! nl2br(e($page->content)) !!}</div>
                            <div class="md:order-2">@isset($imageUrl)<img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg object-cover shadow-sm">@endisset</div>
                        </div>
                        @break

                    @default
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">{!! nl2br(e($page->content)) !!}</div>
                @endswitch

                <!-- Page Footer -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <div>
                            <span>Last updated: {{ $page->updated_at->format('M d, Y \\a\\t g:i A') }}</span>
                        </div>
                        <div class="flex space-x-4">
                            <a href="#" class="hover:text-[#0d5c2f] transition-colors p-2 rounded-lg hover:bg-gray-100">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="hover:text-[#0d5c2f] transition-colors p-2 rounded-lg hover:bg-gray-100">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="hover:text-[#0d5c2f] transition-colors p-2 rounded-lg hover:bg-gray-100">
                                <i class="fas fa-share"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Information Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-sm font-medium text-gray-900 flex items-center">
                <i class="fas fa-info-circle mr-2 text-[#0d5c2f]"></i>
                Page Information
            </h3>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="flex items-center">
                    <i class="fas fa-calendar-plus mr-3 text-[#0d5c2f] w-4"></i>
                    <div>
                        <span class="font-medium text-gray-700">Created:</span>
                        <span class="text-gray-600 ml-2">{{ $page->created_at->format('M d, Y \a\t g:i A') }}</span>
                    </div>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-calendar-check mr-3 text-[#0d5c2f] w-4"></i>
                    <div>
                        <span class="font-medium text-gray-700">Last Updated:</span>
                        <span class="text-gray-600 ml-2">{{ $page->updated_at->format('M d, Y \a\t g:i A') }}</span>
                    </div>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-user mr-3 text-[#0d5c2f] w-4"></i>
                    <div>
                        <span class="font-medium text-gray-700">Author:</span>
                        <span class="text-gray-600 ml-2">{{ $page->creator->name ?? 'Unknown' }}</span>
                    </div>
                </div>
                @if($page->is_published && $page->published_at)
                <div class="flex items-center">
                    <i class="fas fa-globe mr-3 text-[#0d5c2f] w-4"></i>
                    <div>
                        <span class="font-medium text-gray-700">Published:</span>
                        <span class="text-gray-600 ml-2">{{ $page->published_at->format('M d, Y \a\t g:i A') }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 