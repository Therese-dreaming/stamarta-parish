@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Edit Page')

@section('content')
<div class="font-[Poppins]">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Page</h1>
            <p class="text-gray-600 dark:text-gray-400">Update your page content</p>
        </div>
        <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.index') : route('admin.cms.pages.index') }}" class="text-[#0d5c2f] hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Back to Pages
        </a>
    </div>

    <!-- Page Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.update', $page) : route('admin.cms.pages.update', $page) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Page Information</h2>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Page Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] dark:bg-gray-700 dark:text-white">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">URL Slug</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-400 text-sm">
                            {{ url('/') }}/
                        </span>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}"
                               class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-r-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] dark:bg-gray-700 dark:text-white"
                               placeholder="page-url-slug">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from title</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Page Content *</label>
                    <textarea id="content" name="content" rows="12" required
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] dark:bg-gray-700 dark:text-white"
                              placeholder="Enter your page content here...">{{ old('content', $page->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Layout & Image -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="layout" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Layout</label>
                        <select id="layout" name="layout" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] dark:bg-gray-700 dark:text-white">
                            @php($currentLayout = old('layout', $page->layout ?? 'one_column'))
                            <option value="one_column" {{ $currentLayout === 'one_column' ? 'selected' : '' }}>One column (text only)</option>
                            <option value="image_top_text_bottom" {{ $currentLayout === 'image_top_text_bottom' ? 'selected' : '' }}>Image on top, text below</option>
                            <option value="text_top_image_bottom" {{ $currentLayout === 'text_top_image_bottom' ? 'selected' : '' }}>Text on top, image below</option>
                            <option value="image_left_text_right" {{ $currentLayout === 'image_left_text_right' ? 'selected' : '' }}>Two columns: image left, text right</option>
                            <option value="image_right_text_left" {{ $currentLayout === 'image_right_text_left' ? 'selected' : '' }}>Two columns: image right, text left</option>
                        </select>
                        @error('layout')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Featured Image</label>
                        <input type="hidden" id="image_media_id" name="image_media_id" value="{{ old('image_media_id', $page->image_media_id) }}">
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="openMediaPicker()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">
                                <i class="fas fa-image mr-2"></i>Choose from Media Library
                            </button>
                            <button type="button" id="clearImageBtn" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm {{ $page->image ? '' : 'hidden' }}" onclick="clearSelectedImage()">
                                <i class="fas fa-times mr-2"></i>Clear
                            </button>
                        </div>
                        <div id="selectedImagePreview" class="mt-3 {{ $page->image ? '' : 'hidden' }}">
                            @if($page->image)
                                <img id="selectedImage" src="{{ $page->image->url }}" alt="Selected" class="h-32 rounded-lg object-cover border">
                            @else
                                <img id="selectedImage" src="" alt="Selected" class="h-32 rounded-lg object-cover border hidden">
                            @endif
                        </div>
                        @error('image_media_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Meta Information -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] dark:bg-gray-700 dark:text-white"
                               placeholder="SEO title for search engines">
                        @error('meta_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] dark:bg-gray-700 dark:text-white"
                                  placeholder="Brief description for search engines">{{ old('meta_description', $page->meta_description) }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Publishing Options -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Publishing Options</h3>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $page->is_published) ? 'checked' : '' }}
                               class="h-4 w-4 text-[#0d5c2f] focus:ring-[#0d5c2f] border-gray-300 rounded">
                        <label for="is_published" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Publish immediately
                        </label>
                    </div>
                </div>

                <!-- Live Preview -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-eye mr-2 text-[#0d5c2f]"></i>
                        Live Preview
                    </h3>
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <h1 id="previewTitle" class="text-2xl font-bold text-gray-900 dark:text-white mb-2"></h1>
                        <p id="previewMeta" class="text-gray-600 dark:text-gray-300 mb-4"></p>
                        <div id="livePreviewContainer"></div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end space-x-3">
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.index') : route('admin.cms.pages.index') }}" class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                    <i class="fas fa-save mr-2"></i>Update Page
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openMediaPicker() {
    document.getElementById('mediaPickerModal').classList.remove('hidden');
}

function closeMediaPicker() {
    document.getElementById('mediaPickerModal').classList.add('hidden');
}

function selectMedia(id, url) {
    document.getElementById('image_media_id').value = id;
    const preview = document.getElementById('selectedImagePreview');
    const img = document.getElementById('selectedImage');
    img.src = url;
    img.classList.remove('hidden');
    preview.classList.remove('hidden');
    document.getElementById('clearImageBtn').classList.remove('hidden');
    closeMediaPicker();
}

function clearSelectedImage() {
    document.getElementById('image_media_id').value = '';
    const preview = document.getElementById('selectedImagePreview');
    const img = document.getElementById('selectedImage');
    img.src = '';
    preview.classList.add('hidden');
    document.getElementById('clearImageBtn').classList.add('hidden');
}

    // Auto-generate slug from title
    document.getElementById('title').addEventListener('input', function() {
        const title = this.value;
        const slug = title.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        
        document.getElementById('slug').value = slug;
        updateLivePreview();
    });

document.getElementById('meta_description').addEventListener('input', updateLivePreview);
document.getElementById('content').addEventListener('input', updateLivePreview);
document.getElementById('layout').addEventListener('change', updateLivePreview);

function updateLivePreview() {
    const title = document.getElementById('title').value;
    const meta = document.getElementById('meta_description') ? document.getElementById('meta_description').value : '';
    const content = document.getElementById('content').value;
    const layout = document.getElementById('layout').value;
    const imgSrc = document.getElementById('selectedImage') && !document.getElementById('selectedImage').classList.contains('hidden') ? document.getElementById('selectedImage').src : '';

    document.getElementById('previewTitle').textContent = title || 'Untitled Page';
    document.getElementById('previewMeta').textContent = meta || '';

    const container = document.getElementById('livePreviewContainer');
    let html = '';
    const contentHtml = content ? content.replace(/\n/g, '<br>') : '';

    switch (layout) {
        case 'image_top_text_bottom':
            if (imgSrc) html += `<img src="${imgSrc}" class="w-full rounded-lg mb-4 object-cover" />`;
            html += `<div class="prose max-w-none">${contentHtml}</div>`;
            break;
        case 'text_top_image_bottom':
            html += `<div class="prose max-w-none mb-4">${contentHtml}</div>`;
            if (imgSrc) html += `<img src="${imgSrc}" class="w-full rounded-lg object-cover" />`;
            break;
        case 'image_left_text_right':
            html += `<div class="grid md:grid-cols-2 gap-6">`+
                    `<div>${imgSrc ? `<img src=\"${imgSrc}\" class=\"w-full rounded-lg object-cover\">` : ''}</div>`+
                    `<div class=\"prose max-w-none\">${contentHtml}</div>`+
                    `</div>`;
            break;
        case 'image_right_text_left':
            html += `<div class="grid md:grid-cols-2 gap-6">`+
                    `<div class=\"prose max-w-none md:order-1\">${contentHtml}</div>`+
                    `<div class=\"md:order-2\">${imgSrc ? `<img src=\"${imgSrc}\" class=\"w-full rounded-lg object-cover\">` : ''}</div>`+
                    `</div>`;
            break;
        default:
            html += `<div class="prose max-w-none">${contentHtml}</div>`;
    }

    container.innerHTML = html;
}

// Initialize preview on load
window.addEventListener('load', updateLivePreview);
</script>
<!-- Media Picker Modal -->
<div id="mediaPickerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-4xl w-full overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Select Image</h3>
                <button onclick="closeMediaPicker()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-4">
                @if(isset($mediaImages) && $mediaImages->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($mediaImages as $media)
                        <button type="button" class="group border rounded-lg overflow-hidden hover:shadow focus:outline-none" onclick="selectMedia({{ $media->id }}, '{{ $media->url }}')">
                            <img src="{{ $media->url }}" alt="{{ $media->original_name }}" class="w-full h-32 object-cover">
                            <div class="p-2 text-xs text-gray-600 truncate">{{ $media->original_name }}</div>
                        </button>
                    @endforeach
                </div>
                @else
                <div class="text-center text-gray-500 py-12">
                    No images found in Media Library.
                </div>
                @endif
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 text-right">
                <button onclick="closeMediaPicker()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">Close</button>
            </div>
        </div>
    </div>
</div>
@endpush
@endsection 