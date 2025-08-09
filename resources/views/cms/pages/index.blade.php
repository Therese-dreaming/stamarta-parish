@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Manage Pages')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Page Management</h1>
                    <p class="text-white/80 mt-1 flex items-center">
                        <i class="fas fa-file-alt mr-2"></i>Create and manage your website content
                    </p>
                </div>
                @if(!isset($isStaff) || !$isStaff)
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.create') : route('admin.cms.pages.create') }}" 
                   class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Create Page">
                    <i class="fas fa-plus text-lg"></i>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Pages List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-file-text mr-2 text-[#0d5c2f]"></i>
                        Website Pages
                    </h2>
                    <span class="text-sm text-gray-500">{{ $pages->total() }} page{{ $pages->total() != 1 ? 's' : '' }}</span>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pages as $page)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $page->title }}</div>
                                <div class="text-sm text-gray-500">/{{ $page->slug }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($page->is_published)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Published
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $page->updated_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $page->creator->name ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.preview', $page) : route('admin.cms.pages.preview', $page) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.edit', $page) : route('admin.cms.pages.edit', $page) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.toggle-publish', $page) : route('admin.cms.pages.toggle-publish', $page) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-{{ $page->is_published ? 'yellow' : 'green' }}-600 hover:text-{{ $page->is_published ? 'yellow' : 'green' }}-900">
                                        <i class="fas fa-{{ $page->is_published ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.destroy', $page) : route('admin.cms.pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this page?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No pages found. <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.create') : route('admin.cms.pages.create') }}" class="text-[#0d5c2f] hover:underline">Create your first page</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $pages->links() }}
    </div>
</div>
@endsection 