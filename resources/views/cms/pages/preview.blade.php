@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Page Preview')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Page Preview</h1>
            <p class="text-gray-600">Previewing: {{ $page->title }}</p>
        </div>
        <div class="flex space-x-3">
            @if(!isset($isStaff) || !$isStaff)
                            <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.edit', $page) : route('admin.cms.pages.edit', $page) }}" class="bg-[#0d5c2f] text-white px-4 py-2 rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Page
                </a>
            @endif
            <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.index') : route('admin.cms.pages.index') }}" class="text-[#0d5c2f] hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to Pages
            </a>
        </div>
    </div>

    <!-- Public-like Preview (matches user page rendering) -->
    <!-- Hero Section -->
    <div class="relative h-[40vh] -mt-[20px] rounded-xl overflow-hidden border border-gray-200">
        <img src="{{ asset('images/church-bg.jpg') }}" alt="Church Background" class="absolute inset-0 w-full h-full object-cover brightness-50" />
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-3">{{ $page->title }}</h1>
            @if($page->meta_description)
                <p class="text-lg md:text-xl max-w-3xl">{{ $page->meta_description }}</p>
            @endif
        </div>
    </div>

    <!-- Page Content Section (mirrors resources/views/pages/show.blade.php) -->
    <div class="bg-white py-10 rounded-xl border border-gray-200">
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

                @php($layout = $page->layout ?? 'one_column')
                @if(in_array($layout, ['image_top_text_bottom','text_top_image_bottom','image_left_text_right','image_right_text_left']) && $page->image)
                    @php($imageUrl = $page->image->url)
                @endif

                @switch($layout)
                    @case('image_top_text_bottom')
                        @isset($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg mb-6 object-cover">
                        @endisset
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">{!! nl2br(e($page->content)) !!}</div>
                        @break

                    @case('text_top_image_bottom')
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed mb-6">{!! nl2br(e($page->content)) !!}</div>
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
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed md:order-1">{!! nl2br(e($page->content)) !!}</div>
                            <div class="md:order-2">@isset($imageUrl)<img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg object-cover">@endisset</div>
                        </div>
                        @break

                    @default
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">{!! nl2br(e($page->content)) !!}</div>
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

    <!-- Page Information -->
    <div class="mt-8 p-4 bg-gray-50 rounded-lg">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Page Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-700">Created:</span>
                <span class="text-gray-600">{{ $page->created_at->format('M d, Y \a\t g:i A') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Last Updated:</span>
                <span class="text-gray-600">{{ $page->updated_at->format('M d, Y \a\t g:i A') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Author:</span>
                <span class="text-gray-600">{{ $page->creator->name ?? 'Unknown' }}</span>
            </div>
            @if($page->is_published && $page->published_at)
            <div>
                <span class="font-medium text-gray-700">Published:</span>
                <span class="text-gray-600">{{ $page->published_at->format('M d, Y \a\t g:i A') }}</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 