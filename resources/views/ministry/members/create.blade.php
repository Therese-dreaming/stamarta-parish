@extends('layouts.ministry')

@section('title', 'Add Member - ' . $ministry->name)
@section('content')
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-6 relative">
            <div class="absolute right-0 top-0 w-20 h-20 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <div class="flex items-center mb-2">
                        <a href="{{ route('ministry.members.index') }}" class="text-white/80 hover:text-white transition-colors mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Add Member
                        </h1>
                    </div>
                    <p class="text-white/80 text-sm">Add a new member to {{ $ministry->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('ministry.members.index') }}" 
                       class="group px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow">
                        <i class="fas fa-users mr-2 text-sm"></i>
                        <span>Back to Members</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('ministry.members.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- User Search Section -->
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-search text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-blue-900">Link Existing User</h3>
                                <p class="text-xs text-blue-700">Search and select an existing user to add as a member</p>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-gray-400"></i>Search User <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    id="userSearch" 
                                    type="text" 
                                    placeholder="Type name or email to search for a user..." 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors duration-200 pr-10" 
                                    autocomplete="off"
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                            <input type="hidden" name="user_id" id="selectedUserId" />
                            <div id="userResults" class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-auto"></div>
                            @error('user_id')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                You must link an existing user as a member. Start typing to search.
                            </p>
                        </div>
                    </div>

                    <!-- Selected User Display -->
                    <div id="selectedUserDisplay" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-green-900" id="selectedUserName"></h4>
                                <p class="text-xs text-green-700" id="selectedUserEmail"></p>
                            </div>
                            <button type="button" id="clearSelection" class="text-green-600 hover:text-green-800 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Member Information Section -->
                <div class="space-y-4">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-[#0d5c2f] flex items-center justify-center mr-4">
                            <i class="fas fa-user text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Member Information</h3>
                            <p class="text-sm text-gray-500">Fill in the member's details</p>
                        </div>
                    </div>

                    <!-- Name Field (Read-only) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-gray-400"></i>Name
                            </label>
                            <div class="relative">
                                <input 
                                    name="name" 
                                    type="text" 
                                    value="{{ old('name') }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed" 
                                    readonly 
                                    disabled
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-lock text-gray-400 text-sm"></i>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Auto-filled from selected user</p>
                        </div>

                        <!-- Email Field (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>Email
                            </label>
                            <div class="relative">
                                <input 
                                    name="email" 
                                    type="email" 
                                    value="{{ old('email') }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed" 
                                    readonly 
                                    disabled
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-lock text-gray-400 text-sm"></i>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Auto-filled from selected user</p>
                        </div>
                    </div>

                    <!-- Editable Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Phone Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>Phone Number
                            </label>
                            <input 
                                name="phone" 
                                type="tel" 
                                value="{{ old('phone') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors duration-200" 
                                placeholder="Enter phone number"
                            />
                            @error('phone')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Position Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-briefcase mr-2 text-gray-400"></i>Position/Role
                            </label>
                            <input 
                                name="position" 
                                type="text" 
                                value="{{ old('position') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors duration-200" 
                                placeholder="Enter position or role"
                            />
                            @error('position')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Joined Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>Date Joined
                        </label>
                        <input 
                            name="joined_at" 
                            type="date" 
                            value="{{ old('joined_at') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors duration-200"
                        />
                        @error('joined_at')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Notes Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-2 text-gray-400"></i>Notes
                        </label>
                        <textarea 
                            name="notes" 
                            rows="4" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors duration-200 resize-none" 
                            placeholder="Add any additional notes about this member..."
                        >{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('ministry.members.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" 
                            id="submitBtn"
                            disabled
                            class="inline-flex items-center px-6 py-3 bg-gray-400 text-white rounded-lg transition-colors duration-200 shadow-sm hover:shadow disabled:cursor-not-allowed disabled:opacity-50">
                        <i class="fas fa-save mr-2"></i>
                        Add Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('userSearch');
    const results = document.getElementById('userResults');
    const selectedUserId = document.getElementById('selectedUserId');
    const selectedUserDisplay = document.getElementById('selectedUserDisplay');
    const selectedUserName = document.getElementById('selectedUserName');
    const selectedUserEmail = document.getElementById('selectedUserEmail');
    const clearSelection = document.getElementById('clearSelection');
    const submitBtn = document.getElementById('submitBtn');

    let debounce;

    // Search functionality
    searchInput.addEventListener('input', () => {
        clearTimeout(debounce);
        const q = searchInput.value.trim();
        
        if (q.length < 2) {
            results.classList.add('hidden');
            results.innerHTML = '';
            return;
        }

        debounce = setTimeout(() => {
            fetch(`{{ route('ministry.members.search-users') }}?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(list => {
                    if (!Array.isArray(list) || list.length === 0) {
                        results.classList.add('hidden');
                        results.innerHTML = '';
                        return;
                    }

                    results.classList.remove('hidden');
                    results.innerHTML = list.map((u, idx) => `
                        <button type="button" class="user-option w-full text-left px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition-colors duration-200 ${idx === 0 ? 'bg-gray-50' : ''}" data-id="${u.id}" data-name="${u.name}" data-email="${u.email}">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-[#0d5c2f] flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-white text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-sm text-gray-900">${u.name}</div>
                                    <div class="text-xs text-gray-600">${u.email}</div>
                                </div>
                            </div>
                        </button>
                    `).join('');

                    // Add event listeners to user options
                    Array.from(results.querySelectorAll('button.user-option')).forEach(btn => {
                        btn.addEventListener('click', () => {
                            selectUser(btn.dataset.id, btn.dataset.name, btn.dataset.email);
                        });
                    });
                })
                .catch(error => {
                    console.error('Search error:', error);
                    results.classList.add('hidden');
                });
        }, 250);
    });

    // Select user function
    function selectUser(id, name, email) {
        selectedUserId.value = id;
        document.querySelector('input[name="name"]').value = name;
        document.querySelector('input[name="email"]').value = email;
        
        // Update display
        selectedUserName.textContent = name;
        selectedUserEmail.textContent = email;
        selectedUserDisplay.classList.remove('hidden');
        
        // Hide results and update search input
        results.classList.add('hidden');
        results.innerHTML = '';
        searchInput.value = `${name} <${email}>`;
        
        // Enable submit button
        submitBtn.disabled = false;
        submitBtn.classList.remove('bg-gray-400');
        submitBtn.classList.add('bg-[#0d5c2f]', 'hover:bg-[#0a4a26]');
    }

    // Clear selection
    clearSelection.addEventListener('click', () => {
        selectedUserId.value = '';
        document.querySelector('input[name="name"]').value = '';
        document.querySelector('input[name="email"]').value = '';
        selectedUserDisplay.classList.add('hidden');
        searchInput.value = '';
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.classList.remove('bg-[#0d5c2f]', 'hover:bg-[#0a4a26]');
        submitBtn.classList.add('bg-gray-400');
    });

    // Close dropdown on outside click
    document.addEventListener('click', (e) => {
        if (!results.contains(e.target) && e.target !== searchInput) {
            results.classList.add('hidden');
        }
    });

    // Handle keyboard navigation
    searchInput.addEventListener('keydown', (e) => {
        const visibleOptions = results.querySelectorAll('button.user-option:not([style*="display: none"])');
        const currentIndex = Array.from(visibleOptions).findIndex(option => option.classList.contains('bg-gray-50'));
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const nextIndex = (currentIndex + 1) % visibleOptions.length;
            visibleOptions.forEach((option, index) => {
                option.classList.toggle('bg-gray-50', index === nextIndex);
            });
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const prevIndex = currentIndex <= 0 ? visibleOptions.length - 1 : currentIndex - 1;
            visibleOptions.forEach((option, index) => {
                option.classList.toggle('bg-gray-50', index === prevIndex);
            });
        } else if (e.key === 'Enter') {
            e.preventDefault();
            const selectedOption = results.querySelector('button.user-option.bg-gray-50');
            if (selectedOption) {
                selectUser(selectedOption.dataset.id, selectedOption.dataset.name, selectedOption.dataset.email);
            }
        }
    });
});
</script>
@endpush
@endsection


