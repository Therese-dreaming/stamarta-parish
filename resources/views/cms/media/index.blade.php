@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Media Library')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Media Library</h1>
                    <p class="text-white/80 mt-1 flex items-center">
                        <i class="fas fa-images mr-2"></i>Manage images, documents, and other media files
                    </p>
                </div>
                @if(!isset($isStaff) || !$isStaff)
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.media.create') : route('admin.cms.media.create') }}" 
                   class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Upload Media">
                    <i class="fas fa-plus text-lg"></i>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-filter mr-2 text-[#0d5c2f]"></i>
                Filter Media Files
            </h2>
        </div>
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">File Type</label>
                    <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        <option value="">All Types</option>
                        <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images</option>
                        <option value="document" {{ request('type') === 'document' ? 'selected' : '' }}>Documents</option>
                        <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>Videos</option>
                        <option value="audio" {{ request('type') === 'audio' ? 'selected' : '' }}>Audio</option>
                    </select>
                </div>
                <div>
                    <label for="folder" class="block text-sm font-medium text-gray-700 mb-1">Folder</label>
                    <select id="folder" name="folder" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        <option value="">All Folders</option>
                        @foreach($folders as $folder)
                            <option value="{{ $folder }}" {{ request('folder') === $folder ? 'selected' : '' }}>{{ $folder }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search files..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-[#0d5c2f] text-white px-4 py-2 rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-folder-open mr-2 text-[#0d5c2f]"></i>
                        Media Library
                    </h2>
                    <span class="text-sm text-gray-500">{{ $media->total() }} file{{ $media->total() != 1 ? 's' : '' }}</span>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($media->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($media as $file)
                    <div class="group relative bg-gray-50 rounded-lg p-4 hover:shadow-lg transition-all duration-200">
                        <div class="relative">
                            @if($file->is_image)
                                <img src="{{ $file->url }}" alt="{{ $file->alt_text }}" 
                                     class="w-full h-32 bg-gray-200 rounded-lg mb-3 object-cover">
                            @else
                                <div class="w-full h-32 bg-gray-200 rounded-lg mb-3 flex items-center justify-center">
                                    <i class="fas fa-file text-2xl text-gray-400"></i>
                                </div>
                            @endif
                            
                            <!-- Overlay Actions -->
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center space-x-2">
                                <button onclick="editMedia({{ $file->id }}, '{{ $file->original_name }}', '{{ $file->alt_text }}', '{{ $file->description }}', '{{ $file->folder }}')" 
                                        class="text-white hover:text-[#0d5c2f] transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ isset($isStaff) && $isStaff ? route('staff.cms.media.destroy', $file) : route('admin.cms.media.destroy', $file) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-white hover:text-red-400 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <p class="text-sm font-medium text-gray-900 truncate" title="{{ $file->original_name }}">
                            {{ $file->original_name }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $file->formatted_size }}</p>
                        <p class="text-xs text-gray-500">{{ $file->created_at->format('M d, Y') }}</p>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $media->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No media files found</h3>
                    <p class="text-gray-500 mb-4">Upload your first media file to get started</p>
                    @if(!isset($isStaff) || !$isStaff)
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.media.create') : route('admin.cms.media.create') }}" class="bg-[#0d5c2f] text-white px-6 py-2 rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Upload First File
                    </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-md w-full">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Edit Media File</h3>
            </div>
            <form id="editForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="alt_text" class="block text-sm font-medium text-gray-700 mb-1">Alt Text</label>
                    <input type="text" id="alt_text" name="alt_text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"></textarea>
                </div>
                
                <div>
                    <label for="folder" class="block text-sm font-medium text-gray-700 mb-1">Folder</label>
                    <input type="text" id="folder" name="folder" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editMedia(id, name, altText, description, folder) {
    const isStaff = {{ isset($isStaff) && $isStaff ? 'true' : 'false' }};
    const baseUrl = isStaff ? '/staff/cms/media' : '/admin/cms/media';
    document.getElementById('editForm').action = `${baseUrl}/${id}`;
    document.getElementById('alt_text').value = altText || '';
    document.getElementById('description').value = description || '';
    document.getElementById('folder').value = folder || '';
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection 