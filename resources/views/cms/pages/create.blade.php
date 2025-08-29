@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Create New Page')

@section('content')
@include('components.toast')
<style>
    /* Prevent header scrolling issues */
    body {
        overflow-x: hidden;
    }
    
    /* Ensure proper spacing */
    .space-y-4 > * + * {
        margin-top: 1rem;
    }
    
    /* Fix any potential sticky header issues */
    .bg-gradient-to-r {
        position: relative;
        z-index: 10;
    }
    
    /* Prevent the top navigation from interfering */
    .lg\:ml-64 {
        position: relative;
    }
    
    /* Ensure the page content flows properly */
    .space-y-4 {
        position: relative;
        z-index: 1;
    }
    
    /* Fix any potential overflow issues */
    .bg-white.rounded-xl {
        overflow: visible;
    }
</style>
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-6 relative">
            <div class="absolute right-0 top-0 w-24 h-24 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-2xl font-bold text-white">Create New Page</h1>
                    <p class="text-white/80 mt-1 flex items-center text-sm">
                        <i class="fas fa-file-plus mr-2"></i>Build your page with flexible content blocks
                    </p>
                </div>
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.index') : route('admin.cms.pages.index') }}" 
                   class="group px-3 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow text-sm">
                    <i class="fas fa-arrow-left mr-2 text-sm group-hover:-translate-x-1 transition-transform duration-200"></i>
                    <span>Back to Pages</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Page Form -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <form action="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.store') : route('admin.cms.pages.store') }}" method="POST" id="pageForm">
            @csrf
            
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-edit mr-2 text-[#0d5c2f]"></i>
                    Page Information
                </h2>
            </div>
            
            <div class="p-4 space-y-4">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Page Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors text-sm"
                           placeholder="Enter page title">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">URL Slug</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 py-2 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm font-medium">
                            {{ url('/') }}/
                        </span>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors text-sm"
                               placeholder="page-url-slug">
                    </div>
                    <p class="mt-1 text-sm text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>Leave empty to auto-generate from title
                    </p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Meta Information - Two Columns -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors text-sm"
                               placeholder="SEO title for search engines">
                        @error('meta_title')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors resize-y text-sm"
                                  placeholder="Brief description for search engines">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Publishing Options -->
                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-base font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-globe mr-2 text-[#0d5c2f]"></i>
                        Publishing Options
                    </h3>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-[#0d5c2f] focus:ring-[#0d5c2f] border-gray-300 rounded">
                        <label for="is_published" class="ml-2 block text-sm text-gray-700">
                            Publish immediately
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Content Blocks Editor -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-puzzle-piece mr-2 text-[#0d5c2f]"></i>
                        Content Blocks
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Build your page with flexible content blocks</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="addBlock('text')" class="px-3 py-2 bg-[#0d5c2f] text-white rounded-lg text-sm hover:bg-[#0a4a26] transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i>Add Block
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-4">
            <!-- Block Types Menu -->
            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Available Block Types:</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <button type="button" onclick="addBlock('text')" class="p-2 text-left bg-white rounded border hover:border-[#0d5c2f] transition-colors">
                        <i class="fas fa-align-left text-[#0d5c2f]"></i>
                        <span class="block text-xs font-medium">Text Block</span>
                    </button>
                    <button type="button" onclick="addBlock('image')" class="p-2 text-left bg-white rounded border hover:border-[#0d5c2f] transition-colors">
                        <i class="fas fa-image text-[#0d5c2f]"></i>
                        <span class="block text-xs font-medium">Image Block</span>
                    </button>
                    <button type="button" onclick="addBlock('gallery')" class="p-2 text-left bg-white rounded border hover:border-[#0d5c2f] transition-colors">
                        <i class="fas fa-images text-[#0d5c2f]"></i>
                        <span class="block text-xs font-medium">Image Gallery</span>
                    </button>
                    <button type="button" onclick="addBlock('columns')" class="p-2 text-left bg-white rounded border hover:border-[#0d5c2f] transition-colors">
                        <i class="fas fa-columns text-[#0d5c2f]"></i>
                        <span class="block text-xs font-medium">Text Columns</span>
                    </button>
                    <button type="button" onclick="addBlock('image_text')" class="p-2 text-left bg-white rounded border hover:border-[#0d5c2f] transition-colors">
                        <i class="fas fa-image text-[#0d5c2f]"></i>
                        <span class="block text-xs font-medium">Image + Text</span>
                    </button>
                    <button type="button" onclick="addBlock('divider')" class="p-2 text-left bg-white rounded border hover:border-[#0d5c2f] transition-colors">
                        <i class="fas fa-minus text-[#0d5c2f]"></i>
                        <span class="block text-xs font-medium">Divider</span>
                    </button>
                    <button type="button" onclick="addBlock('spacer')" class="p-2 text-left bg-white rounded border hover:border-[#0d5c2f] transition-colors">
                        <i class="fas fa-arrows-alt-v text-[#0d5c2f]"></i>
                        <span class="block text-xs font-medium">Spacer</span>
                    </button>
                    <button type="button" onclick="addBlock('button')" class="p-2 text-left bg-white rounded border hover:border-[#0d5c2f] transition-colors">
                        <i class="fas fa-link text-[#0d5c2f]"></i>
                        <span class="block text-xs font-medium">Button</span>
                    </button>
                </div>
            </div>

            <!-- Content Blocks Container -->
            <div id="contentBlocks" class="space-y-4">
                <!-- Blocks will be added here -->
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-plus-circle text-4xl mb-3"></i>
                    <p>Click "Add Block" to start building your page</p>
                </div>
            </div>

            <!-- Hidden input for storing block data -->
            <input type="hidden" id="content_blocks_data" name="content_blocks_data" value="">
            <input type="hidden" id="content" name="content" value="">
        </div>
    </div>

    <!-- Live Preview -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="fas fa-eye mr-2 text-[#0d5c2f]"></i>
                Live Preview
            </h3>
            <p class="text-sm text-gray-600 mt-1">See how your page will look in real-time</p>
        </div>
        
        <div class="p-4">
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 min-h-[400px]">
                <h1 id="previewTitle" class="text-2xl font-bold text-gray-900 mb-4">Untitled Page</h3>
                <div id="livePreviewContainer" class="prose max-w-none">
                    <p class="text-gray-500 italic text-center py-8">Add content blocks to see the preview...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4">
        <div class="flex justify-end space-x-3">
            <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.index') : route('admin.cms.pages.index') }}" 
               class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                Cancel
            </a>
            <button type="button" onclick="savePage()" class="px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors flex items-center text-sm">
                <i class="fas fa-save mr-2"></i>Create Page
            </button>
        </div>
    </div>
</div>

<!-- Media Picker Modal -->
<div id="mediaPickerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-4xl w-full overflow-hidden shadow-2xl">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-images mr-2 text-[#0d5c2f]"></i>
                        Select Image
                    </h3>
                    <button onclick="closeMediaPicker()" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
            <div class="p-4">
                @if(isset($mediaImages) && $mediaImages->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($mediaImages as $media)
                        <button type="button" class="group border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] transition-all duration-200" 
                                onclick="selectMediaForBlock({{ $media->id }}, '{{ $media->url }}', '{{ $media->original_name }}')">
                            <img src="{{ $media->url }}" alt="{{ $media->original_name }}" class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-200">
                            <div class="p-3 text-xs text-gray-600 truncate bg-white">{{ $media->original_name }}</div>
                        </button>
                    @endforeach
                </div>
                @else
                <div class="text-center text-gray-500 py-12">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-images text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No images found</h3>
                    <p class="text-gray-600">No images found in Media Library.</p>
                </div>
                @endif
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 text-right">
                <button onclick="closeMediaPicker()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let blockCounter = 0;
let currentBlockId = null;
let blocks = [];

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

function addBlock(type) {
    blockCounter++;
    const blockId = `block_${blockCounter}`;
    const block = {
        id: blockId,
        type: type,
        data: {}
    };
    
    blocks.push(block);
    
    const blockHtml = createBlockHTML(block);
    const container = document.getElementById('contentBlocks');
    
    // Remove the placeholder if it exists
    const placeholder = container.querySelector('.text-center');
    if (placeholder) {
        placeholder.remove();
    }
    
    container.insertAdjacentHTML('beforeend', blockHtml);
    updateLivePreview();
}

function createBlockHTML(block) {
    const baseClasses = "bg-white border border-gray-200 rounded-lg overflow-hidden";
    const headerClasses = "p-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between";
    
    let content = '';
    
    switch (block.type) {
        case 'text':
            content = `
                <div class="p-4">
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors resize-y text-sm" 
                              rows="4" placeholder="Enter your text content here..." 
                              onchange="updateBlockData('${block.id}', 'content', this.value)"></textarea>
                </div>
            `;
            break;
            
        case 'image':
            content = `
                <div class="p-4">
                    <div class="flex items-center space-x-2 mb-3">
                        <button type="button" onclick="openMediaPicker('${block.id}')" 
                                class="px-3 py-2 bg-[#0d5c2f] hover:bg-[#0a4a26] text-white rounded-lg text-sm transition-colors flex items-center">
                            <i class="fas fa-image mr-2"></i>Choose Image
                        </button>
                        <button type="button" onclick="clearBlockImage('${block.id}')" 
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm transition-colors flex items-center hidden" 
                                id="clear_${block.id}">
                            <i class="fas fa-times mr-2"></i>Clear
                        </button>
                    </div>
                    <div id="preview_${block.id}" class="hidden">
                        <img id="img_${block.id}" src="" alt="Selected" class="max-w-full h-auto rounded-lg border border-gray-200 shadow-sm">
                    </div>
                    <input type="text" placeholder="Image caption (optional)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors text-sm mt-2"
                           onchange="updateBlockData('${block.id}', 'caption', this.value)">
                </div>
            `;
            break;
            
        case 'gallery':
            content = `
                <div class="p-4">
                    <div class="flex items-center space-x-2 mb-3">
                        <button type="button" onclick="openMediaPicker('${block.id}', true)" 
                                class="px-3 py-2 bg-[#0d5c2f] hover:bg-[#0a4a26] text-white rounded-lg text-sm transition-colors flex items-center">
                            <i class="fas fa-images mr-2"></i>Add Images
                        </button>
                        <span class="text-sm text-gray-500">(Select multiple images)</span>
                    </div>
                    <div id="gallery_${block.id}" class="grid grid-cols-3 gap-2">
                        <!-- Gallery images will be added here -->
                    </div>
                </div>
            `;
            break;
            
        case 'columns':
            content = `
                <div class="p-4">
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Number of Columns</label>
                        <select onchange="updateBlockData('${block.id}', 'columns', this.value)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors text-sm">
                            <option value="2">2 Columns</option>
                            <option value="3">3 Columns</option>
                            <option value="4">4 Columns</option>
                        </select>
                    </div>
                    <div id="columns_${block.id}" class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors resize-y text-sm" 
                                      rows="4" placeholder="Column 1 content..." 
                                      onchange="updateBlockData('${block.id}', 'column1', this.value)"></textarea>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors resize-y text-sm" 
                                      rows="4" placeholder="Column 2 content..." 
                                      onchange="updateBlockData('${block.id}', 'column2', this.value)"></textarea>
                        </div>
                    </div>
                </div>
            `;
            break;
            
        case 'image_text':
            content = `
                <div class="p-4">
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Layout</label>
                        <select onchange="updateBlockData('${block.id}', 'layout', this.value)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors text-sm">
                            <option value="image_left">Image Left, Text Right</option>
                            <option value="image_right">Image Right, Text Left</option>
                        </select>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                            <button type="button" onclick="openMediaPicker('${block.id}')" 
                                    class="w-full px-3 py-2 bg-[#0d5c2f] hover:bg-[#0a4a26] text-white rounded-lg text-sm transition-colors flex items-center justify-center">
                                <i class="fas fa-image mr-2"></i>Choose Image
                            </button>
                            <div id="preview_${block.id}" class="mt-2 hidden">
                                <img id="img_${block.id}" src="" alt="Selected" class="w-full h-auto rounded-lg border border-gray-200 shadow-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Text Content</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors resize-y text-sm" 
                                      rows="6" placeholder="Enter your text content here..." 
                                      onchange="updateBlockData('${block.id}', 'content', this.value)"></textarea>
                        </div>
                    </div>
                </div>
            `;
            break;
            
        case 'divider':
            content = `
                <div class="p-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Divider Style</label>
                            <select onchange="updateBlockData('${block.id}', 'style', this.value)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors text-sm">
                                <option value="line">Simple Line</option>
                                <option value="dashed">Dashed Line</option>
                                <option value="dotted">Dotted Line</option>
                                <option value="text">Text Divider</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Divider Text (optional)</label>
                            <input type="text" placeholder="e.g., Section Break" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors text-sm"
                                   onchange="updateBlockData('${block.id}', 'text', this.value)">
                        </div>
                    </div>
                </div>
            `;
            break;
            
        case 'spacer':
            content = `
                <div class="p-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spacer Height</label>
                            <input type="range" min="20" max="200" value="40" class="w-full" 
                                   onchange="updateBlockData('${block.id}', 'height', this.value)">
                        </div>
                        <div class="text-sm text-gray-600">
                            <span id="height_${block.id}">40</span>px
                        </div>
                    </div>
                </div>
            `;
            break;
            
        case 'button':
            content = `
                <div class="p-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                            <input type="text" placeholder="Click here" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors text-sm"
                                   onchange="updateBlockData('${block.id}', 'text', this.value)">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button URL</label>
                            <input type="url" placeholder="https://example.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors text-sm"
                                   onchange="updateBlockData('${block.id}', 'url', this.value)">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button Style</label>
                        <select onchange="updateBlockData('${block.id}', 'style', this.value)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors text-sm">
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="outline">Outline</option>
                        </select>
                    </div>
                </div>
            `;
            break;
    }
    
    return `
        <div class="${baseClasses}" id="${block.id}">
            <div class="${headerClasses}">
                <div class="flex items-center space-x-2">
                    <i class="fas ${getBlockIcon(block.type)} text-[#0d5c2f]"></i>
                    <span class="font-medium text-gray-900">${getBlockTitle(block.type)}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="moveBlock('${block.id}', 'up')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button" onclick="moveBlock('${block.id}', 'down')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                    <button type="button" onclick="deleteBlock('${block.id}')" class="text-red-400 hover:text-red-600 transition-colors">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            ${content}
        </div>
    `;
}

function getBlockIcon(type) {
    const icons = {
        'text': 'fa-align-left',
        'image': 'fa-image',
        'gallery': 'fa-images',
        'columns': 'fa-columns',
        'image_text': 'fa-image',
        'divider': 'fa-minus',
        'spacer': 'fa-arrows-alt-v',
        'button': 'fa-link'
    };
    return icons[type] || 'fa-puzzle-piece';
}

function getBlockTitle(type) {
    const titles = {
        'text': 'Text Block',
        'image': 'Image Block',
        'gallery': 'Image Gallery',
        'columns': 'Text Columns',
        'image_text': 'Image + Text',
        'divider': 'Divider',
        'spacer': 'Spacer',
        'button': 'Button'
    };
    return titles[type] || 'Content Block';
}

function updateBlockData(blockId, field, value) {
    const block = blocks.find(b => b.id === blockId);
    if (block) {
        block.data[field] = value;
        updateLivePreview();
    }
}

function deleteBlock(blockId) {
    blocks = blocks.filter(b => b.id !== blockId);
    document.getElementById(blockId).remove();
    
    if (blocks.length === 0) {
        const container = document.getElementById('contentBlocks');
        container.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-plus-circle text-4xl mb-3"></i>
                <p>Click "Add Block" to start building your page</p>
            </div>
        `;
    }
    
    updateLivePreview();
}

function moveBlock(blockId, direction) {
    const container = document.getElementById('contentBlocks');
    const blockElement = document.getElementById(blockId);
    
    if (direction === 'up' && blockElement.previousElementSibling) {
        container.insertBefore(blockElement, blockElement.previousElementSibling);
    } else if (direction === 'down' && blockElement.nextElementSibling) {
        container.insertBefore(blockElement.nextElementSibling, blockElement);
    }
    
    // Update blocks array order
    const blockIndex = blocks.findIndex(b => b.id === blockId);
    if (blockIndex !== -1) {
        const block = blocks.splice(blockIndex, 1)[0];
        if (direction === 'up' && blockIndex > 0) {
            blocks.splice(blockIndex - 1, 0, block);
        } else if (direction === 'down' && blockIndex < blocks.length) {
            blocks.splice(blockIndex + 1, 0, block);
        } else {
            blocks.push(block);
        }
    }
    
    updateLivePreview();
}

function openMediaPicker(blockId, multiple = false) {
    currentBlockId = blockId;
    document.getElementById('mediaPickerModal').classList.remove('hidden');
}

function closeMediaPicker() {
    document.getElementById('mediaPickerModal').classList.add('hidden');
    currentBlockId = null;
}

function selectMediaForBlock(id, url, name) {
    if (currentBlockId) {
        const block = blocks.find(b => b.id === currentBlockId);
        if (block) {
            if (block.type === 'gallery') {
                if (!block.data.images) block.data.images = [];
                block.data.images.push({ id, url, name });
                updateGalleryPreview(currentBlockId);
            } else {
                block.data.image_id = id;
                block.data.image_url = url;
                block.data.image_name = name;
                updateImagePreview(currentBlockId, url);
            }
        }
    }
    closeMediaPicker();
    updateLivePreview();
}

function updateImagePreview(blockId, url) {
    const preview = document.getElementById(`preview_${blockId}`);
    const img = document.getElementById(`img_${blockId}`);
    const clearBtn = document.getElementById(`clear_${blockId}`);
    
    if (preview && img) {
        img.src = url;
        preview.classList.remove('hidden');
        if (clearBtn) clearBtn.classList.remove('hidden');
    }
}

function updateGalleryPreview(blockId) {
    const block = blocks.find(b => b.id === blockId);
    const gallery = document.getElementById(`gallery_${blockId}`);
    
    if (block && block.data.images && gallery) {
        gallery.innerHTML = block.data.images.map((image, index) => `
            <div class="relative group">
                <img src="${image.url}" alt="${image.name}" class="w-full h-24 object-cover rounded border">
                <button onclick="removeGalleryImage('${blockId}', ${index})" 
                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `).join('');
    }
}

function removeGalleryImage(blockId, index) {
    const block = blocks.find(b => b.id === blockId);
    if (block && block.data.images) {
        block.data.images.splice(index, 1);
        updateGalleryPreview(blockId);
        updateLivePreview();
    }
}

function clearBlockImage(blockId) {
    const block = blocks.find(b => b.id === blockId);
    if (block) {
        delete block.data.image_id;
        delete block.data.image_url;
        delete block.data.image_name;
        
        const preview = document.getElementById(`preview_${blockId}`);
        const clearBtn = document.getElementById(`clear_${blockId}`);
        
        if (preview) preview.classList.add('hidden');
        if (clearBtn) clearBtn.classList.add('hidden');
        
        updateLivePreview();
    }
}

function updateLivePreview() {
    const title = document.getElementById('title').value;
    const meta = document.getElementById('meta_description').value;
    
    document.getElementById('previewTitle').textContent = title || 'Untitled Page';
    
    const container = document.getElementById('livePreviewContainer');
    let html = '';
    
    if (blocks.length === 0) {
        html = '<p class="text-gray-500 italic text-center py-8">Add content blocks to see the preview...</p>';
    } else {
        blocks.forEach(block => {
            html += renderBlockPreview(block);
        });
    }
    
    container.innerHTML = html;
}

function renderBlockPreview(block) {
    switch (block.type) {
        case 'text':
            const content = block.data.content || '';
            return content ? `<div class="prose max-w-none mb-6">${content.replace(/\n/g, '<br>')}</div>` : '';
            
        case 'image':
            const imageUrl = block.data.image_url;
            const caption = block.data.caption;
            if (imageUrl) {
                return `
                    <div class="mb-6">
                        <img src="${imageUrl}" alt="${caption || 'Image'}" class="w-full h-auto rounded-lg shadow-sm">
                        ${caption ? `<p class="text-sm text-gray-600 mt-2 text-center">${caption}</p>` : ''}
                    </div>
                `;
            }
            return '';
            
        case 'gallery':
            if (block.data.images && block.data.images.length > 0) {
                const images = block.data.images.map(img => `
                    <img src="${img.url}" alt="${img.name}" class="w-full h-32 object-cover rounded border">
                `).join('');
                return `<div class="grid grid-cols-3 gap-4 mb-6">${images}</div>`;
            }
            return '';
            
        case 'columns':
            const columns = parseInt(block.data.columns) || 2;
            const columnContent = [];
            for (let i = 1; i <= columns; i++) {
                const content = block.data[`column${i}`] || '';
                columnContent.push(`<div class="prose max-w-none">${content.replace(/\n/g, '<br>')}</div>`);
            }
            return `<div class="grid md:grid-cols-${columns} gap-6 mb-6">${columnContent.join('')}</div>`;
            
        case 'image_text':
            const imgUrl = block.data.image_url;
            const textContent = block.data.content || '';
            const layout = block.data.layout || 'image_left';
            
            if (imgUrl || textContent) {
                const imageHtml = imgUrl ? `<img src="${imgUrl}" alt="Image" class="w-full h-auto rounded-lg shadow-sm">` : '<div class="h-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">No image</div>';
                const textHtml = textContent ? `<div class="prose max-w-none">${textContent.replace(/\n/g, '<br>')}</div>` : '<p class="text-gray-500 italic">No content yet...</p>';
                
                if (layout === 'image_right') {
                    return `<div class="grid md:grid-cols-2 gap-6 mb-6"><div>${textHtml}</div><div>${imageHtml}</div></div>`;
                } else {
                    return `<div class="grid md:grid-cols-2 gap-6 mb-6"><div>${imageHtml}</div><div>${textHtml}</div></div>`;
                }
            }
            return '';
            
        case 'divider':
            const style = block.data.style || 'line';
            const text = block.data.text || '';
            
            if (style === 'text' && text) {
                return `<div class="flex items-center my-6"><div class="flex-1 border-t border-gray-300"></div><span class="px-4 text-gray-500 text-sm">${text}</span><div class="flex-1 border-t border-gray-300"></div></div>`;
            } else {
                const borderStyle = style === 'dashed' ? 'border-dashed' : style === 'dotted' ? 'border-dotted' : 'border-solid';
                return `<div class="border-t ${borderStyle} border-gray-300 my-6"></div>`;
            }
            
        case 'spacer':
            const height = block.data.height || 40;
            return `<div style="height: ${height}px;"></div>`;
            
        case 'button':
            const buttonText = block.data.text || 'Click here';
            const buttonUrl = block.data.url || '#';
            const buttonStyle = block.data.style || 'primary';
            
            const styleClasses = {
                'primary': 'bg-[#0d5c2f] text-white hover:bg-[#0a4a26]',
                'secondary': 'bg-gray-600 text-white hover:bg-gray-700',
                'outline': 'border border-[#0d5c2f] text-[#0d5c2f] hover:bg-[#0d5c2f] hover:text-white'
            };
            
            return `<div class="text-center mb-6"><a href="${buttonUrl}" class="inline-block px-6 py-3 rounded-lg transition-colors ${styleClasses[buttonStyle]}">${buttonText}</a></div>`;
            
        default:
            return '';
    }
}

function savePage() {
    // Update the hidden input with block data
    document.getElementById('content_blocks_data').value = JSON.stringify(blocks);
    
    // Submit the form
    document.getElementById('pageForm').submit();
}

// Initialize preview on load
window.addEventListener('load', updateLivePreview);

// Prevent header scrolling issues
document.addEventListener('DOMContentLoaded', function() {
    // Ensure smooth scrolling
    document.documentElement.style.scrollBehavior = 'smooth';
    
    // Prevent any potential header reset issues
    const header = document.querySelector('.bg-gradient-to-r');
    if (header) {
        header.style.position = 'relative';
        header.style.zIndex = '10';
    }
    
    // Ensure proper page layout
    const mainContent = document.querySelector('.space-y-4');
    if (mainContent) {
        mainContent.style.position = 'relative';
        mainContent.style.zIndex = '1';
    }
});
</script>
@endpush
@endsection 