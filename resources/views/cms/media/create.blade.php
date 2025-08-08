@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Upload Media')

@section('content')
<div class="font-[Poppins]">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Upload Media</h1>
            <p class="text-gray-600 dark:text-gray-400">Upload images, documents, and other files</p>
        </div>
        <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.media.index') : route('admin.cms.media.index') }}" class="text-[#0d5c2f] hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Back to Media Library
        </a>
    </div>

    <!-- Upload Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ isset($isStaff) && $isStaff ? route('staff.cms.media.store') : route('admin.cms.media.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Upload Files</h2>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- File Upload -->
                <div>
                    <label for="files" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Files *</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-[#0d5c2f] transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="files" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-[#0d5c2f] hover:text-[#0d5c2f]/80 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#0d5c2f]">
                                    <span>Upload files</span>
                                    <input id="files" name="files[]" type="file" class="sr-only" multiple accept="image/*,.pdf,.doc,.docx,.txt,.zip,.rar" required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                PNG, JPG, GIF, PDF, DOC, DOCX, TXT, ZIP, RAR up to 10MB each
                            </p>
                        </div>
                    </div>
                    @error('files.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Folder -->
                <div>
                    <label for="folder" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Folder</label>
                    <input type="text" id="folder" name="folder" value="{{ old('folder', 'general') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] dark:bg-gray-700 dark:text-white"
                           placeholder="Enter folder name (optional)">
                    <p class="mt-1 text-sm text-gray-500">Files will be organized in this folder</p>
                </div>

                <!-- Selected Files Preview -->
                <div id="filePreview" class="hidden">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Selected Files</h3>
                    <div id="fileList" class="space-y-2"></div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end space-x-3">
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.media.index') : route('admin.cms.media.index') }}" class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
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
    
    if (files.length > 0) {
        filePreview.classList.remove('hidden');
        fileList.innerHTML = '';
        
        Array.from(files).forEach(file => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg';
            
            const fileInfo = document.createElement('div');
            fileInfo.className = 'flex items-center space-x-3';
            
            const icon = document.createElement('i');
            icon.className = file.type.startsWith('image/') ? 'fas fa-image text-blue-500' : 'fas fa-file text-gray-500';
            
            const fileName = document.createElement('span');
            fileName.className = 'text-sm font-medium text-gray-900 dark:text-white';
            fileName.textContent = file.name;
            
            const fileSize = document.createElement('span');
            fileSize.className = 'text-xs text-gray-500';
            fileSize.textContent = formatFileSize(file.size);
            
            fileInfo.appendChild(icon);
            fileInfo.appendChild(fileName);
            fileInfo.appendChild(fileSize);
            fileItem.appendChild(fileInfo);
            fileList.appendChild(fileItem);
        });
    } else {
        filePreview.classList.add('hidden');
    }
});

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Drag and drop functionality
const dropZone = document.querySelector('input[type="file"]').parentElement.parentElement.parentElement;

dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('border-[#0d5c2f]');
});

dropZone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('border-[#0d5c2f]');
});

dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('border-[#0d5c2f]');
    
    const files = e.dataTransfer.files;
    document.getElementById('files').files = files;
    
    // Trigger change event
    const event = new Event('change');
    document.getElementById('files').dispatchEvent(event);
});
</script>
@endpush
@endsection 