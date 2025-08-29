@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Upload Media')

@section('content')
@include('components.toast')
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-6 relative">
            <div class="absolute right-0 top-0 w-24 h-24 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-2xl font-bold text-white">Upload Media</h1>
                    <p class="text-white/80 mt-1 flex items-center text-sm">
                        <i class="fas fa-cloud-upload-alt mr-2"></i>Upload images, documents, and other files
                    </p>
                </div>
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.media.index') : route('admin.cms.media.index') }}" 
                   class="group px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow">
                    <i class="fas fa-arrow-left mr-2 text-sm group-hover:-translate-x-1 transition-transform duration-200"></i>
                    <span>Back to Media</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <form action="{{ isset($isStaff) && $isStaff ? route('staff.cms.media.store') : route('admin.cms.media.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-upload mr-2 text-[#0d5c2f]"></i>
                    Upload Files
                </h2>
            </div>
            
            <div class="p-4 space-y-4">
                <!-- File Upload -->
                <div>
                    <label for="files" class="block text-sm font-medium text-gray-700 mb-2">Select Files *</label>
                    <div class="mt-1">
                        <label for="files" class="block cursor-pointer">
                            <div class="flex justify-center px-6 pt-8 pb-8 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#0d5c2f] hover:bg-gray-50 transition-all duration-200 group">
                                <div class="space-y-3 text-center">
                                    <div class="w-16 h-16 mx-auto rounded-full bg-[#0d5c2f]/10 flex items-center justify-center group-hover:bg-[#0d5c2f]/20 transition-colors">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-[#0d5c2f] group-hover:scale-110 transition-transform duration-200"></i>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm font-medium text-gray-900 group-hover:text-[#0d5c2f] transition-colors">
                                            <span class="text-[#0d5c2f]">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            PNG, JPG, GIF, PDF, DOC, DOCX, TXT, ZIP, RAR up to 10MB each
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <input id="files" name="files[]" type="file" class="sr-only" multiple accept="image/*,.pdf,.doc,.docx,.txt,.zip,.rar" required>
                        </label>
                    </div>
                    @error('files.*')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Folder -->
                <div>
                    <label for="folder" class="block text-sm font-medium text-gray-700 mb-1">Folder</label>
                    <select id="folder" name="folder" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">
                        <option value="images" {{ old('folder', 'images') == 'images' ? 'selected' : '' }}>Images</option>
                        <option value="documents" {{ old('folder') == 'documents' ? 'selected' : '' }}>Documents</option>
                        <option value="media" {{ old('folder') == 'media' ? 'selected' : '' }}>Media</option>
                        <option value="uploads" {{ old('folder') == 'uploads' ? 'selected' : '' }}>Uploads</option>
                        <option value="general" {{ old('folder') == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Choose a folder to organize your files</p>
                </div>

                <!-- Selected Files Preview -->
                <div id="filePreview" class="hidden">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-medium text-gray-900 flex items-center">
                            <i class="fas fa-eye mr-2 text-[#0d5c2f]"></i>Selected Files
                        </h3>
                        <span id="fileCount" class="px-2 py-1 bg-[#0d5c2f]/10 text-[#0d5c2f] rounded-full text-xs font-medium"></span>
                    </div>
                    <div id="fileList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"></div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-4 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.media.index') : route('admin.cms.media.index') }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors text-sm">
                    <i class="fas fa-upload mr-2"></i>Upload Files
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('files').addEventListener('change', function(e) {
    const files = e.target.files;
    const fileList = document.getElementById('fileList');
    const filePreview = document.getElementById('filePreview');
    const fileCount = document.getElementById('fileCount');
    
    if (files.length > 0) {
        filePreview.classList.remove('hidden');
        fileList.innerHTML = '';
        fileCount.textContent = `${files.length} file${files.length !== 1 ? 's' : ''}`;
        
        Array.from(files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow duration-200';
            
            // File header with icon and name
            const fileHeader = document.createElement('div');
            fileHeader.className = 'flex items-start justify-between mb-2';
            
            const fileIcon = document.createElement('div');
            fileIcon.className = 'flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center';
            
            let iconClass = 'fas fa-file text-gray-500';
            let bgClass = 'bg-gray-100';
            
            if (file.type.startsWith('image/')) {
                iconClass = 'fas fa-image text-blue-500';
                bgClass = 'bg-blue-100';
            } else if (file.type.includes('pdf')) {
                iconClass = 'fas fa-file-pdf text-red-500';
                bgClass = 'bg-red-100';
            } else if (file.type.includes('word') || file.type.includes('document')) {
                iconClass = 'fas fa-file-word text-blue-600';
                bgClass = 'bg-blue-100';
            } else if (file.type.includes('zip') || file.type.includes('rar')) {
                iconClass = 'fas fa-file-archive text-yellow-500';
                bgClass = 'bg-yellow-100';
            }
            
            fileIcon.className += ` ${bgClass}`;
            const icon = document.createElement('i');
            icon.className = iconClass;
            fileIcon.appendChild(icon);
            
            const fileName = document.createElement('div');
            fileName.className = 'flex-1 ml-2 min-w-0';
            const fileNameText = document.createElement('p');
            fileNameText.className = 'text-sm font-medium text-gray-900 truncate';
            fileNameText.textContent = file.name;
            fileName.appendChild(fileNameText);
            
            const fileSize = document.createElement('p');
            fileSize.className = 'text-xs text-gray-500 mt-1';
            fileSize.textContent = formatFileSize(file.size);
            fileName.appendChild(fileSize);
            
            fileHeader.appendChild(fileIcon);
            fileHeader.appendChild(fileName);
            
            // Remove button
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'flex-shrink-0 w-6 h-6 rounded-full bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 transition-colors';
            removeBtn.innerHTML = '<i class="fas fa-times text-xs"></i>';
            removeBtn.onclick = function() {
                removeFile(index);
            };
            fileHeader.appendChild(removeBtn);
            
            fileItem.appendChild(fileHeader);
            
            // File preview (for images)
            if (file.type.startsWith('image/')) {
                const preview = document.createElement('div');
                preview.className = 'mt-2';
                
                const img = document.createElement('img');
                img.className = 'w-full h-20 object-cover rounded-lg border border-gray-200';
                img.style.maxWidth = '100%';
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
                
                preview.appendChild(img);
                fileItem.appendChild(preview);
            }
            
            fileList.appendChild(fileItem);
        });
    } else {
        filePreview.classList.add('hidden');
    }
});

function removeFile(index) {
    const input = document.getElementById('files');
    const dt = new DataTransfer();
    
    Array.from(input.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    
    // Trigger change event to update preview
    const event = new Event('change');
    input.dispatchEvent(event);
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Drag and drop functionality
const dropZone = document.querySelector('label[for="files"] div');

dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('border-[#0d5c2f]', 'bg-[#0d5c2f]/5');
});

dropZone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('border-[#0d5c2f]', 'bg-[#0d5c2f]/5');
});

dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('border-[#0d5c2f]', 'bg-[#0d5c2f]/5');
    
    const files = e.dataTransfer.files;
    document.getElementById('files').files = files;
    
    // Trigger change event
    const event = new Event('change');
    document.getElementById('files').dispatchEvent(event);
});
</script>
@endpush
@endsection 