@extends('layouts.ministry')

@section('title', 'Edit Member - ' . $ministry->name)
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
                            <i class="fas fa-user-edit mr-2"></i>
                            Edit Member
                        </h1>
                    </div>
                    <p class="text-white/80 text-sm">Update member information for {{ $ministry->name }}</p>
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
            <form action="{{ route('ministry.members.update', $member) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Member Information Section -->
                <div class="space-y-4">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-[#0d5c2f] flex items-center justify-center mr-4">
                            <i class="fas fa-user text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $member->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $member->email }}</p>
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
                                    type="text" 
                                    value="{{ $member->name }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed" 
                                    readonly 
                                    disabled
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-lock text-gray-400 text-sm"></i>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Name cannot be changed</p>
                        </div>

                        <!-- Email Field (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>Email
                            </label>
                            <div class="relative">
                                <input 
                                    type="email" 
                                    value="{{ $member->email }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed" 
                                    readonly 
                                    disabled
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-lock text-gray-400 text-sm"></i>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
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
                                value="{{ old('phone', $member->phone) }}" 
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
                                value="{{ old('position', $member->position) }}" 
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
                            value="{{ old('joined_at', optional($member->joined_at)->format('Y-m-d')) }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors duration-200"
                        />
                        @error('joined_at')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Status Toggle -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input 
                                    type="checkbox" 
                                    name="is_active" 
                                    value="1" 
                                    class="sr-only" 
                                    {{ old('is_active', $member->is_active) ? 'checked' : '' }}
                                />
                                <div class="w-10 h-6 bg-gray-300 rounded-full shadow-inner"></div>
                                <div class="dot absolute w-4 h-4 bg-white rounded-full shadow -top-1 -left-1 transition-transform duration-200 ease-in-out"></div>
                            </div>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Active Member</span>
                                <p class="text-xs text-gray-500">Enable or disable this member's access</p>
                            </div>
                        </label>
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
                        >{{ old('notes', $member->notes) }}</textarea>
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
                            class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors duration-200 shadow-sm hover:shadow">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle toggle switch
    const toggle = document.querySelector('input[name="is_active"]');
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
</script>
@endsection


