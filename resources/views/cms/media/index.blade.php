@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Media Library')

@section('content')
@include('components.toast')
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-6 relative">
            <div class="absolute right-0 top-0 w-24 h-24 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-2xl font-bold text-white">Media Library</h1>
                    <p class="text-white/80 mt-1 flex items-center text-sm">
                        <i class="fas fa-images mr-2"></i>Manage images, documents, and other media files
                    </p>
                </div>
                @if(!isset($isStaff) || !$isStaff)
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.media.create') : route('admin.cms.media.create') }}" 
                   class="group px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow">
                    <i class="fas fa-plus mr-2 text-sm group-hover:rotate-90 transition-transform duration-300"></i>
                    <span>Upload Media</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-filter mr-2 text-[#0d5c2f]"></i>
                Filter Media Files
            </h2>
        </div>
        <div class="p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">File Type</label>
                    <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">
                        <option value="">All Types</option>
                        <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images</option>
                        <option value="document" {{ request('type') === 'document' ? 'selected' : '' }}>Documents</option>
                        <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>Videos</option>
                        <option value="audio" {{ request('type') === 'audio' ? 'selected' : '' }}>Audio</option>
                    </select>
                </div>
                <div>
                    <label for="folder" class="block text-sm font-medium text-gray-700 mb-1">Folder</label>
                    <select id="folder" name="folder" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">
                        <option value="">All Folders</option>
                        @foreach($folders as $folder)
                            <option value="{{ $folder }}" {{ request('folder') === $folder ? 'selected' : '' }}>{{ $folder }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search files..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-[#0d5c2f] text-white px-4 py-2 rounded-lg hover:bg-[#0a4a26] transition-colors text-sm">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-folder-open mr-2 text-[#0d5c2f]"></i>
                        Media Library
                    </h2>
                    <span class="px-3 py-1 bg-[#0d5c2f]/10 text-[#0d5c2f] rounded-full text-sm font-medium">
                        {{ $media->total() }} file{{ $media->total() != 1 ? 's' : '' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="p-4">
            @if($media->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($media as $file)
                    <div class="group relative bg-gray-50 rounded-lg p-3 hover:shadow-lg transition-all duration-200 border border-gray-100 hover:border-gray-200">
                        <div class="relative">
                            @if($file->is_image)
                                <img src="{{ $file->url }}" alt="{{ $file->alt_text }}" 
                                     class="w-full h-24 bg-gray-200 rounded-lg mb-2 object-cover">
                            @else
                                <div class="w-full h-24 bg-gray-200 rounded-lg mb-2 flex items-center justify-center">
                                    <i class="fas fa-file text-xl text-gray-400"></i>
                                </div>
                            @endif
                            
                            <!-- Overlay Actions -->
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center space-x-2">
                                <button onclick="editMedia({{ $file->id }}, '{{ $file->original_name }}', '{{ $file->alt_text }}', '{{ $file->description }}', '{{ $file->folder }}')" 
                                        class="w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <button onclick="openDeleteModal({{ $file->id }}, '{{ $file->original_name }}')"
                                        class="w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Delete">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                        
                        <p class="text-xs font-medium text-gray-900 truncate mb-1" title="{{ $file->original_name }}">
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
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center shadow-sm">
                        <i class="fas fa-images text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No media files found</h3>
                    <p class="text-gray-600 mb-4">Upload your first media file to get started</p>
                    @if(!isset($isStaff) || !$isStaff)
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.media.create') : route('admin.cms.media.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors">
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
        <div class="bg-white rounded-xl max-w-md w-full shadow-2xl">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-edit mr-2 text-[#0d5c2f]"></i>
                    Edit Media File
                </h3>
            </div>
            <form id="editForm" method="POST" class="p-4 space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="alt_text" class="block text-sm font-medium text-gray-700 mb-1">Alt Text</label>
                    <input type="text" id="alt_text" name="alt_text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm resize-y"></textarea>
                </div>
                
                <div>
                    <label for="folder" class="block text-sm font-medium text-gray-700 mb-1">Folder</label>
                    <input type="text" id="folder" name="folder" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors text-sm">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-md w-full shadow-2xl">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-trash mr-2 text-red-600"></i>
                    Delete Media File
                </h3>
            </div>
            <div class="p-4">
                <p class="text-gray-600 mb-4">Are you sure you want to delete "<span id="deleteFileName" class="font-medium"></span>"? This action cannot be undone.</p>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                            Delete File
                        </button>
                    </div>
                </form>
            </div>
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

function openDeleteModal(id, fileName) {
    const isStaff = {{ isset($isStaff) && $isStaff ? 'true' : 'false' }};
    const baseUrl = isStaff ? '/staff/cms/media' : '/admin/cms/media';
    document.getElementById('deleteForm').action = `${baseUrl}/${id}`;
    document.getElementById('deleteFileName').textContent = fileName;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection 