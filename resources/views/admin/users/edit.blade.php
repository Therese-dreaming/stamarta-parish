@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
@include('components.toast')
<div class="space-y-3">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-lg shadow-md overflow-hidden">
        <div class="px-4 py-4 relative">
            <div class="absolute right-0 top-0 w-16 h-16 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-xl font-bold text-white">Edit User</h1>
                    <p class="text-white/80 mt-1 text-xs">Update user information and role</p>
                </div>
                <a href="{{ route('admin.users.index') }}" 
                   class="group px-3 py-1.5 rounded-md bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow text-xs">
                    <i class="fas fa-arrow-left mr-1.5 text-xs group-hover:-translate-x-1 transition-transform duration-200"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden animate-slideInUp">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Basic Information -->
                <div class="space-y-3">
                    <h3 class="text-base font-semibold text-gray-900 border-b border-gray-200 pb-2 flex items-center">
                        <i class="fas fa-user mr-2 text-[#0d5c2f] text-sm"></i>
                        Basic Information
                    </h3>
                    
                    <div class="group">
                        <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        @error('name')
                            <p class="text-red-600 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        @error('email')
                            <p class="text-red-600 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="contact_number" class="block text-xs font-medium text-gray-700 mb-1">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                        @error('contact_number')
                            <p class="text-red-600 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="address" class="block text-xs font-medium text-gray-700 mb-1">Address</label>
                        <textarea id="address" name="address" rows="3"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="text-red-600 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Role and Status -->
                <div class="space-y-3">
                    <h3 class="text-base font-semibold text-gray-900 border-b border-gray-200 pb-2 flex items-center">
                        <i class="fas fa-shield-alt mr-2 text-[#0d5c2f] text-sm"></i>
                        Role & Status
                    </h3>
                    
                    <div class="group">
                        <label for="role" class="block text-xs font-medium text-gray-700 mb-1">Role *</label>
                        <select id="role" name="role" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                            <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="priest" {{ old('role', $user->role) === 'priest' ? 'selected' : '' }}>Priest</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="text-red-600 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Email Verification</label>
                        <div class="flex items-center">
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 transition-all duration-200 hover:scale-105">
                                    <i class="fas fa-check-circle mr-1 text-xs"></i>Verified
                                </span>
                                <span class="text-xs text-gray-500 ml-2">{{ $user->email_verified_at->format('M j, Y g:i A') }}</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 transition-all duration-200 hover:scale-105">
                                    <i class="fas fa-clock mr-1 text-xs"></i>Pending
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Account Status</label>
                        <div class="flex items-center">
                            @if($user->id === auth()->id())
                                <span class="text-xs text-gray-500">You cannot modify your own account status</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} transition-all duration-200 hover:scale-105">
                                    <i class="fas fa-{{ $user->is_active ? 'check' : 'times' }}-circle mr-1 text-xs"></i>
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="mt-4">
                <h3 class="text-base font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-3 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-[#0d5c2f] text-sm"></i>
                    Account Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-2.5 hover:shadow-md transition-all duration-200 group">
                        <div class="flex items-center mb-1.5">
                            <i class="fas fa-calendar-plus text-[#0d5c2f] mr-1.5 text-xs group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="font-medium text-gray-900 text-xs">Member Since</span>
                        </div>
                        <p class="text-xs text-gray-700">{{ $user->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-2.5 hover:shadow-md transition-all duration-200 group">
                        <div class="flex items-center mb-1.5">
                            <i class="fas fa-clock text-[#0d5c2f] mr-1.5 text-xs group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="font-medium text-gray-900 text-xs">Last Updated</span>
                        </div>
                        <p class="text-xs text-gray-700">{{ $user->updated_at->format('M j, Y g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-3 py-1.5 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-all duration-200 hover:shadow-sm text-xs">
                    <i class="fas fa-times mr-1.5 text-xs"></i>Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-1.5 bg-[#0d5c2f] text-white rounded-md hover:bg-[#0a4a26] transition-all duration-200 hover:shadow-sm text-xs group">
                    <i class="fas fa-save mr-1.5 text-xs group-hover:scale-110 transition-transform duration-200"></i>Update User
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes slideInUp {
    from { 
        opacity: 0; 
        transform: translateY(30px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

.animate-slideInUp {
    animation: slideInUp 0.6s ease-out forwards;
}
</style>

<script>
// Add focus effects to form inputs
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-[#0d5c2f]/20', 'ring-offset-1');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-[#0d5c2f]/20', 'ring-offset-1');
        });
    });
});
</script>
@endsection 