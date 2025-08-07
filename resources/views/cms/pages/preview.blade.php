@extends('layouts.admin')

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
            <a href="{{ route('admin.cms.pages.edit', $page) }}" class="bg-[#0d5c2f] text-white px-4 py-2 rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Page
            </a>
            <a href="{{ route('admin.cms.pages.index') }}" class="text-[#0d5c2f] hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to Pages
            </a>
        </div>
    </div>

    <!-- Page Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Page Preview</h2>
                <div class="flex items-center space-x-2">
                    @if($page->is_published)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Published
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>Draft
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Page Title -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
            
            <!-- Meta Information -->
            @if($page->meta_description)
                <p class="text-gray-600 italic">{{ $page->meta_description }}</p>
            @endif
            
            <!-- Page Content -->
            <div class="prose prose-lg max-w-none mt-6">
                {!! $page->content !!}
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