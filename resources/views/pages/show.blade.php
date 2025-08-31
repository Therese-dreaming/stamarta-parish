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

            @if($page->content_blocks && count($page->content_blocks) > 0)
                <!-- Render content blocks -->
                @foreach($page->content_blocks as $block)
                    @switch($block['type'])
                        @case('text')
                            @if(!empty($block['data']['content']))
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed mb-6">
                                {!! nl2br(e($block['data']['content'])) !!}
                            </div>
                            @endif
                            @break
                            
                        @case('image')
                            @if(!empty($block['data']['image_url']))
                            <div class="mb-6">
                                <img src="{{ $block['data']['image_url'] }}" 
                                     alt="{{ $block['data']['caption'] ?? 'Image' }}" 
                                     class="w-full h-auto rounded-lg shadow-sm">
                                @if(!empty($block['data']['caption']))
                                    <p class="text-sm text-gray-600 mt-2 text-center">{{ $block['data']['caption'] }}</p>
                                @endif
                            </div>
                            @endif
                            @break
                            
                        @case('gallery')
                            @if(!empty($block['data']['images']) && count($block['data']['images']) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                @foreach($block['data']['images'] as $image)
                                    <div class="relative group">
                                        <img src="{{ $image['url'] }}" 
                                             alt="{{ $image['name'] }}" 
                                             class="w-full h-48 object-cover rounded-lg border shadow-sm">
                                    </div>
                                @endforeach
                            </div>
                            @endif
                            @break
                            
                        @case('columns')
                            @php($columns = intval($block['data']['columns'] ?? 2))
                            <div class="grid md:grid-cols-{{ $columns }} gap-6 mb-6">
                                @for($i = 1; $i <= $columns; $i++)
                                    @if(!empty($block['data']["column{$i}"]))
                                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                        {!! nl2br(e($block['data']["column{$i}"])) !!}
                                    </div>
                                    @endif
                                @endfor
                            </div>
                            @break
                            
                        @case('image_text')
                            @php($layout = $block['data']['layout'] ?? 'image_left')
                            <div class="grid md:grid-cols-2 gap-6 mb-6">
                                @if($layout === 'image_right')
                                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                        {!! nl2br(e($block['data']['content'] ?? '')) !!}
                                    </div>
                                    <div>
                                        @if(!empty($block['data']['image_url']))
                                            <img src="{{ $block['data']['image_url'] }}" alt="Image" class="w-full h-auto rounded-lg shadow-sm">
                                        @else
                                            <div class="h-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">No image</div>
                                        @endif
                                    </div>
                                @else
                                    <div>
                                        @if(!empty($block['data']['image_url']))
                                            <img src="{{ $block['data']['image_url'] }}" alt="Image" class="w-full h-auto rounded-lg shadow-sm">
                                        @else
                                            <div class="h-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">No image</div>
                                        @endif
                                    </div>
                                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                        {!! nl2br(e($block['data']['content'] ?? '')) !!}
                                    </div>
                                @endif
                            </div>
                            @break
                            
                        @case('divider')
                            @php($style = $block['data']['style'] ?? 'line')
                            @php($text = $block['data']['text'] ?? '')
                            @if($style === 'text' && $text)
                                <div class="flex items-center my-6">
                                    <div class="flex-1 border-t border-gray-300"></div>
                                    <span class="px-4 text-gray-500 text-sm">{{ $text }}</span>
                                    <div class="flex-1 border-t border-gray-300"></div>
                                </div>
                            @else
                                @php($borderStyle = $style === 'dashed' ? 'border-dashed' : ($style === 'dotted' ? 'border-dotted' : 'border-solid'))
                                <div class="border-t {{ $borderStyle }} border-gray-300 my-6"></div>
                            @endif
                            @break
                            
                        @case('spacer')
                            @php($height = intval($block['data']['height'] ?? 40))
                            <div style="height: {{ $height }}px;"></div>
                            @break
                            
                        @case('button')
                            @php($buttonText = $block['data']['text'] ?? 'Click here')
                            @php($buttonUrl = $block['data']['url'] ?? '#')
                            @php($buttonStyle = $block['data']['style'] ?? 'primary')
                            @php($styleClasses = [
                                'primary' => 'bg-[#0d5c2f] text-white hover:bg-[#0a4a26]',
                                'secondary' => 'bg-gray-600 text-white hover:bg-gray-700',
                                'outline' => 'border border-[#0d5c2f] text-[#0d5c2f] hover:bg-[#0d5c2f] hover:text-white'
                            ])
                            <div class="text-center mb-6">
                                <a href="{{ $buttonUrl }}" class="inline-block px-6 py-3 rounded-lg transition-colors {{ $styleClasses[$buttonStyle] ?? $styleClasses['primary'] }}">
                                    {{ $buttonText }}
                                </a>
                            </div>
                            @break
                            
                        @default
                            <!-- Fallback for unknown block types -->
                            <div class="mb-6 p-4 bg-gray-100 rounded-lg">
                                <p class="text-gray-600 text-sm">Unknown content block type: {{ $block['type'] ?? 'undefined' }}</p>
                            </div>
                    @endswitch
                @endforeach
            @else
                <!-- Fallback to old layout-based rendering -->
                @switch($layout)
                    @case('image_top_text_bottom')
                        @isset($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg mb-6 object-cover">
                        @endisset
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed whitespace-pre-line">{!! $page->content !!}</div>
                        @break

                    @case('text_top_image_bottom')
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed mb-6 whitespace-pre-line">{!! $page->content !!}</div>
                        @isset($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg object-cover">
                        @endisset
                        @break

                    @case('image_left_text_right')
                        <div class="grid md:grid-cols-2 gap-8 items-start">
                            <div>@isset($imageUrl)<img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg object-cover">@endisset</div>
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed whitespace-pre-line">{!! $page->content !!}</div>
                        </div>
                        @break

                    @case('image_right_text_left')
                        <div class="grid md:grid-cols-2 gap-8 items-start">
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed md:order-1 whitespace-pre-line">{!! $page->content !!}</div>
                            <div class="md:order-2">@isset($imageUrl)<img src="{{ $imageUrl }}" alt="{{ $page->title }}" class="w-full rounded-lg object-cover">@endisset</div>
                        </div>
                        @break

                    @default
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed whitespace-pre-line">{!! $page->content !!}</div>
                @endswitch
            @endif

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