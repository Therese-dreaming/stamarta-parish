@extends('layouts.ministry')

@section('title', 'Create Activity - ' . $ministry->name)
@section('content')
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-6 relative">
            <div class="absolute right-0 top-0 w-20 h-20 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <div class="flex items-center mb-2">
                        <a href="{{ route('ministry.activities.index') }}" class="text-white/80 hover:text-white transition-colors mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Create Activity
                        </h1>
                    </div>
                    <p class="text-white/80 text-sm">Plan a new activity for {{ $ministry->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('ministry.activities.index') }}" 
                       class="group px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow">
                        <i class="fas fa-calendar mr-2 text-sm"></i>
                        <span>Back to Activities</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Conflict Warning -->
    @if(session('conflicts'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-400 text-lg"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-red-800">Schedule Conflicts Detected</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>The following conflicts were found:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            @if(count(session('conflicts')['parochial_activities']) > 0)
                                <li><strong>Parochial Activities:</strong>
                                    @foreach(session('conflicts')['parochial_activities'] as $conflict)
                                        <span class="block ml-4">• {{ $conflict->title }} on {{ \Carbon\Carbon::parse($conflict->event_date)->format('M d, Y') }} at {{ $conflict->start_time }} - {{ $conflict->end_time }}</span>
                                    @endforeach
                                </li>
                            @endif
                            @if(count(session('conflicts')['bookings']) > 0)
                                <li><strong>Bookings:</strong>
                                    @foreach(session('conflicts')['bookings'] as $conflict)
                                        @php
                                            $bookingStart = \Carbon\Carbon::parse($conflict->service_date . ' ' . $conflict->service_time);
                                            $bookingEnd = $bookingStart->copy()->addMinutes($conflict->service->duration_minutes ?? 60);
                                        @endphp
                                        <span class="block ml-4">• {{ $conflict->service->name }} on {{ \Carbon\Carbon::parse($conflict->service_date)->format('M d, Y') }} at {{ $bookingStart->format('g:i A') }} - {{ $bookingEnd->format('g:i A') }}</span>
                                    @endforeach
                                </li>
                            @endif
                            @if(count(session('conflicts')['ministry_activities']) > 0)
                                <li><strong>Ministry Activities:</strong>
                                    @foreach(session('conflicts')['ministry_activities'] as $conflict)
                                        <span class="block ml-4">• {{ $conflict->title }} ({{ $conflict->ministry->name }}) on {{ \Carbon\Carbon::parse($conflict->start_at)->format('M d, Y g:i A') }}</span>
                                    @endforeach
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="mt-4">
                        <button type="button" onclick="confirmProceed()" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition-colors">
                            Proceed Anyway
                        </button>
                        <button type="button" onclick="adjustSchedule()" class="ml-3 bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition-colors">
                            Adjust Schedule
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('ministry.activities.store') }}" method="POST" class="space-y-6" id="activityForm" enctype="multipart/form-data">
                @csrf
                @if(session('conflicts'))
                    <input type="hidden" name="confirm_conflicts" value="1">
                @endif
                
                <!-- Activity Information Section -->
                <div class="space-y-4">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-[#0d5c2f] flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-alt text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Activity Details</h3>
                            <p class="text-sm text-gray-500">Basic information about the activity</p>
                        </div>
                    </div>

                    <!-- Title Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading mr-2 text-gray-400"></i>Activity Title <span class="text-red-600">*</span>
                        </label>
                        <input 
                            name="title" 
                            type="text" 
                            value="{{ old('title') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors duration-200" 
                            placeholder="Enter activity title"
                            required
                        />
                        @error('title')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Date and Time Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-clock mr-2 text-gray-400"></i>Start Date & Time <span class="text-red-600">*</span>
                            </label>
                            <input 
                                name="start_at" 
                                type="datetime-local" 
                                value="{{ old('start_at') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors duration-200"
                                required
                                onchange="checkConflicts()"
                            />
                            @error('start_at')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-clock mr-2 text-gray-400"></i>End Date & Time
                            </label>
                            <input 
                                name="end_at" 
                                type="datetime-local" 
                                value="{{ old('end_at') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors duration-200"
                                onchange="checkConflicts()"
                            />
                            @error('end_at')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Conflict Indicator -->
                    <div id="conflictIndicator" class="hidden">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <div class="flex items-center">
                                <i class="fas fa-spinner fa-spin text-blue-600 mr-2"></i>
                                <span class="text-sm text-blue-800">Checking for schedule conflicts...</span>
                            </div>
                        </div>
                    </div>

                    <!-- All Day Toggle -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input 
                                    type="checkbox" 
                                    name="is_all_day" 
                                    value="1" 
                                    class="sr-only" 
                                    {{ old('is_all_day') ? 'checked' : '' }}
                                    onchange="checkConflicts()"
                                />
                                <div class="w-10 h-6 bg-gray-300 rounded-full shadow-inner"></div>
                                <div class="dot absolute w-4 h-4 bg-white rounded-full shadow -top-1 -left-1 transition-transform duration-200 ease-in-out"></div>
                            </div>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">All Day Event</span>
                                <p class="text-xs text-gray-500">Check if this activity spans the entire day</p>
                            </div>
                        </label>
                    </div>

                    <!-- Location Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>Location
                        </label>
                        <input 
                            name="location" 
                            type="text" 
                            value="{{ old('location') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors duration-200" 
                            placeholder="Enter activity location"
                            onchange="checkConflicts()"
                        />
                        @error('location')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Description Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-gray-400"></i>Description
                        </label>
                        <textarea 
                            name="description" 
                            rows="4" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors duration-200 resize-none" 
                            placeholder="Describe the activity, its objectives, and expected outcomes..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Public Visibility Toggle -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input 
                                    type="checkbox" 
                                    name="is_public" 
                                    value="1" 
                                    class="sr-only" 
                                    {{ old('is_public') ? 'checked' : '' }}
                                />
                                <div class="w-10 h-6 bg-gray-300 rounded-full shadow-inner"></div>
                                <div class="dot absolute w-4 h-4 bg-white rounded-full shadow -top-1 -left-1 transition-transform duration-200 ease-in-out"></div>
                            </div>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Public Activity</span>
                                <p class="text-xs text-gray-500">Make this activity visible to all parish members</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Budget Request Section (Always Required) -->
                <div class="space-y-4 border-t border-gray-200 pt-6">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            <i class="fas fa-coins text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Budget Request</h3>
                            <p class="text-sm text-gray-500">Required budget information for this activity</p>
                        </div>
                    </div>

                    <!-- Budget Purpose -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-bullseye mr-2 text-gray-400"></i>Budget Purpose <span class="text-red-600">*</span>
                        </label>
                        <input 
                            name="budget_purpose" 
                            type="text" 
                            value="{{ old('budget_purpose') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                            placeholder="e.g., Materials and supplies for youth retreat"
                            required
                        />
                        <p class="text-xs text-gray-500 mt-1">Brief description of what the budget will be used for</p>
                        @error('budget_purpose')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Budget Breakdown List -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-list-ul mr-2 text-gray-400"></i>Budget Breakdown <span class="text-red-600">*</span>
                        </label>
                        <div id="budgetBreakdownList" class="space-y-3">
                            <!-- Default budget item -->
                            <div class="budget-item flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex-1">
                                    <input 
                                        type="text" 
                                        name="budget_items[0][name]" 
                                        placeholder="Cost Name (e.g., Venue)" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                        required
                                    />
                                </div>
                                <div class="flex-1">
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₱</span>
                                        <input 
                                            type="number" 
                                            step="0.01" 
                                            min="0" 
                                            name="budget_items[0][amount]" 
                                            placeholder="0.00" 
                                            class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                            required
                                            onchange="calculateTotal()"
                                        />
                                    </div>
                                </div>
                                <button type="button" onclick="removeBudgetItem(this)" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" onclick="addBudgetItem()" class="mt-3 inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Add Budget Item
                        </button>
                        <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-blue-900">Total Estimated Budget:</span>
                                <span id="totalBudget" class="text-lg font-bold text-blue-900">₱0.00</span>
                            </div>
                        </div>
                        @error('budget_items')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Budget Details -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-gray-400"></i>Detailed Justification
                        </label>
                        <textarea 
                            name="budget_details" 
                            rows="3" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none" 
                            placeholder="Provide detailed justification for the budget request, including specific items and their purposes..."
                        >{{ old('budget_details') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Optional: Detailed explanation of budget requirements</p>
                        @error('budget_details')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- File Upload Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-paperclip mr-2 text-gray-400"></i>Supporting Documents
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors cursor-pointer" 
                             onclick="document.getElementById('budgetFilesInput').click()">
                            <input 
                                type="file" 
                                name="budget_files[]" 
                                multiple 
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                class="hidden" 
                                id="budgetFilesInput"
                                onchange="handleFileSelection(this)"
                            />
                            <div class="space-y-2">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                                <p class="text-sm text-gray-600">Click to upload supporting documents</p>
                                <p class="text-xs text-gray-500">PDF, DOC, XLS, or image files (max 10MB each)</p>
                            </div>
                        </div>
                        <div id="selectedFiles" class="mt-3 space-y-2"></div>
                        @error('budget_files')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('ministry.activities.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors duration-200 shadow-sm hover:shadow">
                        <i class="fas fa-save mr-2"></i>
                        Create Activity
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Custom toggle switch styling */
input:checked ~ .dot {
    transform: translateX(100%);
}

input:checked ~ div {
    background-color: #0d5c2f;
}

/* File preview styling */
.file-preview {
    transition: all 0.2s ease-in-out;
}

.file-preview:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Budget item styling */
.budget-item {
    transition: all 0.2s ease-in-out;
}

.budget-item:hover {
    background-color: #f9fafb;
}
</style>

<script>
let conflictCheckTimeout;
let budgetItemIndex = 1;

document.addEventListener('DOMContentLoaded', function() {
    // Handle toggle switches
    const toggles = document.querySelectorAll('input[type="checkbox"]');
    
    toggles.forEach(toggle => {
        const toggleContainer = toggle.nextElementSibling;
        
        toggle.addEventListener('change', function() {
            if (this.checked) {
                toggleContainer.classList.remove('bg-gray-300');
                toggleContainer.classList.add('bg-[#0d5c2f]');
            } else {
                toggleContainer.classList.remove('bg-[#0d5c2f]');
                toggleContainer.classList.add('bg-gray-300');
            }
        });
        
        // Initialize toggle state
        if (toggle.checked) {
            toggleContainer.classList.remove('bg-gray-300');
            toggleContainer.classList.add('bg-[#0d5c2f]');
        }
    });

    // Calculate initial total
    calculateTotal();
});

function addBudgetItem() {
    const budgetList = document.getElementById('budgetBreakdownList');
    const newItem = document.createElement('div');
    newItem.className = 'budget-item flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200';
    newItem.innerHTML = `
        <div class="flex-1">
            <input 
                type="text" 
                name="budget_items[${budgetItemIndex}][name]" 
                placeholder="Cost Name (e.g., Venue)" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                required
            />
        </div>
        <div class="flex-1">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₱</span>
                <input 
                    type="number" 
                    step="0.01" 
                    min="0" 
                    name="budget_items[${budgetItemIndex}][amount]" 
                    placeholder="0.00" 
                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                    required
                    onchange="calculateTotal()"
                />
            </div>
        </div>
        <button type="button" onclick="removeBudgetItem(this)" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    `;
    budgetList.appendChild(newItem);
    budgetItemIndex++;
}

function removeBudgetItem(button) {
    const budgetList = document.getElementById('budgetBreakdownList');
    if (budgetList.children.length > 1) {
        button.closest('.budget-item').remove();
        calculateTotal();
    }
}

function calculateTotal() {
    const amountInputs = document.querySelectorAll('input[name*="[amount]"]');
    let total = 0;
    
    amountInputs.forEach(input => {
        const value = parseFloat(input.value) || 0;
        total += value;
    });
    
    document.getElementById('totalBudget').textContent = `₱${total.toFixed(2)}`;
}

function handleFileSelection(input) {
    const files = Array.from(input.files);
    const selectedFilesDiv = document.getElementById('selectedFiles');
    
    selectedFilesDiv.innerHTML = '';
    
    files.forEach((file, index) => {
        const fileDiv = document.createElement('div');
        fileDiv.className = 'file-preview flex items-center justify-between bg-white rounded-lg p-3 border border-gray-200';
        
        // Get file icon based on type
        const fileIcon = getFileIcon(file.type);
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        
        fileDiv.innerHTML = `
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                    <i class="${fileIcon} text-gray-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">${file.name}</p>
                    <p class="text-xs text-gray-500">${fileSize} MB</p>
                </div>
            </div>
            <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        `;
        selectedFilesDiv.appendChild(fileDiv);
    });
}

function getFileIcon(fileType) {
    if (fileType.includes('pdf')) return 'fas fa-file-pdf text-red-500';
    if (fileType.includes('word') || fileType.includes('document')) return 'fas fa-file-word text-blue-500';
    if (fileType.includes('excel') || fileType.includes('spreadsheet')) return 'fas fa-file-excel text-green-500';
    if (fileType.includes('image')) return 'fas fa-file-image text-purple-500';
    return 'fas fa-file text-gray-500';
}

function removeFile(index) {
    const input = document.getElementById('budgetFilesInput');
    const dt = new DataTransfer();
    
    Array.from(input.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    handleFileSelection(input);
}

function checkConflicts() {
    const startAt = document.querySelector('input[name="start_at"]').value;
    const endAt = document.querySelector('input[name="end_at"]').value;
    const location = document.querySelector('input[name="location"]').value;
    const isAllDay = document.querySelector('input[name="is_all_day"]').checked;
    
    if (!startAt) return;
    
    // Clear previous timeout
    if (conflictCheckTimeout) {
        clearTimeout(conflictCheckTimeout);
    }
    
    // Show loading indicator
    const indicator = document.getElementById('conflictIndicator');
    indicator.classList.remove('hidden');
    indicator.innerHTML = `
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-center">
                <i class="fas fa-spinner fa-spin text-blue-600 mr-2"></i>
                <span class="text-sm text-blue-800">Checking for schedule conflicts...</span>
            </div>
        </div>
    `;
    
    // Prepare the data to send
    let conflictData = {
        start_at: startAt,
        end_at: endAt,
        location: location,
        is_all_day: isAllDay
    };
    
    // Debug logging
    console.log('Conflict check data:', {
        startAt,
        endAt,
        location,
        isAllDay,
        conflictData
    });
    
    // If it's an all-day event, set the end time to the end of the same day
    if (isAllDay) {
        const startDate = new Date(startAt);
        const endDate = new Date(startDate);
        endDate.setHours(23, 59, 59, 999); // Set to end of day
        
        // Format as YYYY-MM-DDTHH:mm for datetime-local input
        const year = endDate.getFullYear();
        const month = String(endDate.getMonth() + 1).padStart(2, '0');
        const day = String(endDate.getDate()).padStart(2, '0');
        const hours = String(endDate.getHours()).padStart(2, '0');
        const minutes = String(endDate.getMinutes()).padStart(2, '0');
        
        conflictData.end_at = `${year}-${month}-${day}T${hours}:${minutes}`;
        
        console.log('All-day event detected, adjusted end time:', {
            originalEnd: endAt,
            adjustedEnd: conflictData.end_at,
            startDate: startDate.toISOString(),
            endDate: endDate.toISOString(),
            formattedEnd: conflictData.end_at
        });
    }
    
    // Debounce the check
    conflictCheckTimeout = setTimeout(() => {
        fetch('{{ route("ministry.activities.check-conflicts") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(conflictData)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Conflict check response:', data); // Debug log
            
            if (data.has_conflicts) {
                let conflictDetails = '';
                
                if (data.conflicts.parochial_activities && data.conflicts.parochial_activities.length > 0) {
                    conflictDetails += '<div class="mt-2"><strong>Parochial Activities:</strong><ul class="list-disc list-inside ml-4">';
                    data.conflicts.parochial_activities.forEach(activity => {
                        conflictDetails += `<li>${activity.title} on ${new Date(activity.event_date).toLocaleDateString()}</li>`;
                    });
                    conflictDetails += '</ul></div>';
                }
                
                if (data.conflicts.bookings && data.conflicts.bookings.length > 0) {
                    conflictDetails += '<div class="mt-2"><strong>Bookings:</strong><ul class="list-disc list-inside ml-4">';
                    data.conflicts.bookings.forEach(booking => {
                        // Parse the date and time more carefully
                        let startTime, endTime;
                        
                        try {
                            // Handle different date formats
                            const serviceDate = booking.service_date;
                            const serviceTime = booking.service_time;
                            
                            // If service_date is already a date string, use it directly
                            if (serviceDate && serviceTime) {
                                // Create a proper datetime string
                                const dateTimeStr = `${serviceDate}T${serviceTime}`;
                                startTime = new Date(dateTimeStr);
                                
                                // Check if parsing was successful
                                if (isNaN(startTime.getTime())) {
                                    // Fallback: try parsing as separate date and time
                                    const datePart = new Date(serviceDate);
                                    const timePart = serviceTime;
                                    
                                    if (!isNaN(datePart.getTime())) {
                                        // Parse time like "9:00 AM" or "09:00"
                                        let hours, minutes;
                                        
                                        if (timePart.includes('AM') || timePart.includes('PM')) {
                                            // Format: "9:00 AM" or "2:30 PM"
                                            const match = timePart.match(/(\d+):(\d+)\s*(AM|PM)/i);
                                            if (match) {
                                                hours = parseInt(match[1]);
                                                minutes = parseInt(match[2]);
                                                const ampm = match[3].toUpperCase();
                                                
                                                if (ampm === 'PM' && hours !== 12) {
                                                    hours += 12;
                                                } else if (ampm === 'AM' && hours === 12) {
                                                    hours = 0;
                                                }
                                                
                                                startTime = new Date(datePart);
                                                startTime.setHours(hours, minutes, 0, 0);
                                            }
                                        } else {
                                            // Format: "09:00" or "14:30"
                                            const match = timePart.match(/(\d+):(\d+)/);
                                            if (match) {
                                                hours = parseInt(match[1]);
                                                minutes = parseInt(match[2]);
                                                
                                                startTime = new Date(datePart);
                                                startTime.setHours(hours, minutes, 0, 0);
                                            }
                                        }
                                    }
                                }
                            }
                            
                            // If we still don't have a valid start time, use fallback
                            if (!startTime || isNaN(startTime.getTime())) {
                                startTime = new Date();
                                console.warn('Could not parse booking time:', { service_date: serviceDate, service_time: serviceTime });
                            }
                            
                            // Calculate end time
                            const durationMinutes = booking.service ? (booking.service.duration_minutes || 60) : 60;
                            endTime = new Date(startTime.getTime() + durationMinutes * 60000);
                            
                            // Format the display
                            const dateStr = startTime.toLocaleDateString();
                            const startTimeStr = startTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            const endTimeStr = endTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            
                            conflictDetails += `<li>${booking.service ? booking.service.name : 'Unknown Service'} on ${dateStr} at ${startTimeStr} - ${endTimeStr}</li>`;
                            
                        } catch (error) {
                            console.error('Error parsing booking time:', error, booking);
                            // Fallback display
                            conflictDetails += `<li>${booking.service ? booking.service.name : 'Unknown Service'} on ${booking.service_date} at ${booking.service_time} (duration: ${booking.service ? booking.service.duration_minutes : 60} minutes)</li>`;
                        }
                    });
                    conflictDetails += '</ul></div>';
                }
                
                if (data.conflicts.ministry_activities && data.conflicts.ministry_activities.length > 0) {
                    conflictDetails += '<div class="mt-2"><strong>Ministry Activities:</strong><ul class="list-disc list-inside ml-4">';
                    data.conflicts.ministry_activities.forEach(activity => {
                        const startTime = new Date(activity.start_at);
                        const endTime = activity.end_at ? new Date(activity.end_at) : new Date(startTime.getTime() + 2 * 60 * 60 * 1000); // Default 2 hours
                        conflictDetails += `<li>${activity.title} (${activity.ministry.name}) on ${startTime.toLocaleDateString()} at ${startTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - ${endTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</li>`;
                    });
                    conflictDetails += '</ul></div>';
                }
                
                // Add special message for all-day events
                if (isAllDay) {
                    conflictDetails += '<div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800"><i class="fas fa-info-circle mr-1"></i><strong>Note:</strong> This is an all-day event, so it conflicts with any existing activities or bookings on this date.</div>';
                }
                
                indicator.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                            <span class="text-sm text-red-800">Conflicts detected: ${data.summary}</span>
                        </div>
                        ${conflictDetails}
                    </div>
                `;
            } else {
                indicator.innerHTML = `
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <span class="text-sm text-green-800">No schedule conflicts detected</span>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Conflict check error:', error);
            console.error('Error details:', error.response); // Debug log
            indicator.innerHTML = `
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                        <span class="text-sm text-yellow-800">Unable to check conflicts. Please try again.</span>
                    </div>
                </div>
            `;
        });
    }, 1000);
}

function confirmProceed() {
    document.getElementById('activityForm').submit();
}

function adjustSchedule() {
    // Focus on start time field for user to adjust
    document.querySelector('input[name="start_at"]').focus();
}
</script>
@endsection


