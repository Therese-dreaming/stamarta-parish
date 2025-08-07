@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="font-[Poppins]">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600">Welcome to your parish CMS</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Pages -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pages</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_pages'] }}</p>
                </div>
            </div>
        </div>

        <!-- Published Pages -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Published</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['published_pages'] }}</p>
                </div>
            </div>
        </div>

        <!-- Draft Pages -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Drafts</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['draft_pages'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Media -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-images text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Media Files</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_media'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('admin.cms.pages.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-plus text-[#0d5c2f] text-xl mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">Create Page</p>
                            <p class="text-sm text-gray-600">Add new content</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.cms.media.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-upload text-[#0d5c2f] text-xl mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">Upload Media</p>
                            <p class="text-sm text-gray-600">Add files</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.cms.pages.index') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-list text-[#0d5c2f] text-xl mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">Manage Pages</p>
                            <p class="text-sm text-gray-600">View all pages</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.cms.media.index') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-folder text-[#0d5c2f] text-xl mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">Media Library</p>
                            <p class="text-sm text-gray-600">Manage files</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Recent Pages</h2>
            </div>
            <div class="p-6">
                @if($stats['recent_pages']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['recent_pages'] as $page)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 rounded-full {{ $page->is_published ? 'bg-green-500' : 'bg-yellow-500' }}"></div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $page->title }}</p>
                                    <p class="text-sm text-gray-600">by {{ $page->creator->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">{{ $page->created_at->diffForHumans() }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.cms.pages.index') }}" class="text-[#0d5c2f] hover:underline text-sm">View all pages →</a>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No pages created yet</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Media -->
    @if($stats['recent_media']->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Recent Media</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($stats['recent_media'] as $media)
                <div class="text-center">
                    @if($media->is_image)
                        <img src="{{ $media->url }}" alt="{{ $media->alt_text }}" class="w-full h-20 object-cover rounded-lg mb-2">
                    @else
                        <div class="w-full h-20 bg-gray-200 rounded-lg mb-2 flex items-center justify-center">
                            <i class="fas fa-file text-2xl text-gray-400"></i>
                        </div>
                    @endif
                    <p class="text-xs text-gray-600 truncate" title="{{ $media->original_name }}">
                        {{ $media->original_name }}
                    </p>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.cms.media.index') }}" class="text-[#0d5c2f] hover:underline text-sm">View all media →</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 