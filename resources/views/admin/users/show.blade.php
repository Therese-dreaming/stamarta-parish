@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
@include('components.toast')
<div class="space-y-3">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-lg shadow-md overflow-hidden">
        <div class="px-4 py-4 relative">
            <div class="absolute right-0 top-0 w-16 h-16 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-xl font-bold text-white">User Details</h1>
                    <p class="text-white/80 mt-1 text-xs">View user information and account details</p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="group px-3 py-1.5 rounded-md bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow text-xs">
                        <i class="fas fa-edit mr-1.5 text-xs group-hover:scale-110 transition-transform duration-200"></i>
                        <span>Edit</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="group px-3 py-1.5 rounded-md bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow text-xs">
                        <i class="fas fa-arrow-left mr-1.5 text-xs group-hover:-translate-x-1 transition-transform duration-200"></i>
                        <span>Back</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-3">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden animate-slideInUp">
                <div class="p-3 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user mr-2 text-[#0d5c2f] text-sm"></i>
                        Basic Information
                    </h3>
                </div>
                <div class="p-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Full Name</label>
                            <p class="text-sm font-medium text-gray-900 group-hover:text-[#0d5c2f] transition-colors duration-200">{{ $user->name }}</p>
                        </div>
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-sm text-gray-900 group-hover:text-[#0d5c2f] transition-colors duration-200">{{ $user->email }}</p>
                        </div>
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Role</label>
                            @php
                                $roleColors = [
                                    'admin' => 'bg-red-100 text-red-800',
                                    'priest' => 'bg-purple-100 text-purple-800',
                                    'staff' => 'bg-blue-100 text-blue-800',
                                    'user' => 'bg-green-100 text-green-800'
                                ];
                                $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $roleColor }} transition-all duration-200 hover:scale-105">
                                <i class="fas fa-{{ $user->role === 'admin' ? 'crown' : ($user->role === 'priest' ? 'cross' : ($user->role === 'staff' ? 'user-tie' : 'user')) }} mr-1 text-xs"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 transition-all duration-200 hover:scale-105">
                                    <i class="fas fa-check-circle mr-1 text-xs"></i>Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 transition-all duration-200 hover:scale-105">
                                    <i class="fas fa-clock mr-1 text-xs"></i>Pending
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden animate-slideInUp" style="animation-delay: 100ms">
                <div class="p-3 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-[#0d5c2f] text-sm"></i>
                        Account Information
                    </h3>
                </div>
                <div class="p-3">
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
                        @if($user->email_verified_at)
                        <div class="bg-green-50 border border-green-200 rounded-md p-2.5 hover:shadow-md transition-all duration-200 group">
                            <div class="flex items-center mb-1.5">
                                <i class="fas fa-check-circle text-green-600 mr-1.5 text-xs group-hover:scale-110 transition-transform duration-200"></i>
                                <span class="font-medium text-green-900 text-xs">Email Verified</span>
                            </div>
                            <p class="text-xs text-green-700">{{ $user->email_verified_at->format('M j, Y g:i A') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-3">
            <!-- Profile Picture -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden animate-slideInUp" style="animation-delay: 200ms">
                <div class="p-3 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-id-card mr-2 text-[#0d5c2f] text-sm"></i>
                        Profile
                    </h3>
                </div>
                <div class="p-3">
                    <div class="w-full h-28 bg-gradient-to-br from-[#0d5c2f] to-[#0d5c2f]/80 rounded-md flex items-center justify-center group hover:shadow-lg transition-all duration-300">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-1.5 group-hover:scale-110 transition-transform duration-200">
                                <i class="fas fa-user text-lg text-white"></i>
                            </div>
                            <p class="text-white font-medium text-xs">{{ $user->name }}</p>
                            <p class="text-white/80 text-xs">{{ ucfirst($user->role) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden animate-slideInUp" style="animation-delay: 300ms">
                <div class="p-3 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt mr-2 text-[#0d5c2f] text-sm"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-3">
                    <div class="space-y-2">
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="w-full flex items-center justify-center px-3 py-1.5 bg-[#0d5c2f] text-white rounded-md hover:bg-[#0a4a26] transition-all duration-200 hover:shadow-md hover:scale-105 text-xs">
                            <i class="fas fa-edit mr-1.5 text-xs"></i>Edit User
                        </a>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.promote-ministry-head', $user) }}" method="POST" class="space-y-2">
                                @csrf
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Assign as Ministry Head</label>
                                    <select name="ministry_id" class="w-full border rounded px-2 py-1.5 text-xs">
                                        @foreach($ministries as $min)
                                            <option value="{{ $min->id }}">{{ $min->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="w-full flex items-center justify-center px-3 py-1.5 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-all duration-200 hover:shadow-md hover:scale-105 text-xs" type="submit">
                                    <i class="fas fa-user-shield mr-1.5 text-xs"></i>Promote to Ministry Head
                                </button>
                            </form>
                        @endif
                        @if($user->id !== auth()->id())
                            <button onclick="openDeleteModal()" 
                                    class="w-full flex items-center justify-center px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-all duration-200 hover:shadow-md hover:scale-105 text-xs">
                                <i class="fas fa-trash mr-1.5 text-xs"></i>Delete User
                            </button>
                        @else
                            <div class="w-full flex items-center justify-center px-3 py-1.5 bg-gray-100 text-gray-500 rounded-md text-xs">
                                <i class="fas fa-lock mr-1.5 text-xs"></i>Cannot delete own account
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Account Stats -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden animate-slideInUp" style="animation-delay: 400ms">
                <div class="p-3 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-[#0d5c2f] text-sm"></i>
                        Account Stats
                    </h3>
                </div>
                <div class="p-3">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-1.5 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors duration-200">
                            <span class="text-xs text-gray-600">Account Age</span>
                            <span class="text-xs font-medium text-gray-900">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center justify-between p-1.5 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors duration-200">
                            <span class="text-xs text-gray-600">Last Activity</span>
                            <span class="text-xs font-medium text-gray-900">{{ $user->updated_at->diffForHumans() }}</span>
                        </div>
                        @if($user->email_verified_at)
                        <div class="flex items-center justify-between p-1.5 bg-green-50 rounded-md hover:bg-green-100 transition-colors duration-200">
                            <span class="text-xs text-gray-600">Verified</span>
                            <span class="text-xs font-medium text-green-600">{{ $user->email_verified_at->diffForHumans() }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
@if($user->id !== auth()->id())
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-sm w-full shadow-2xl animate-modalSlideIn">
            <div class="p-3 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-trash mr-2 text-red-600 text-sm"></i>
                    Delete User
                </h3>
            </div>
            <div class="p-3">
                <p class="text-gray-600 mb-3 text-sm">Are you sure you want to delete <strong>{{ $user->name }}</strong>? This action cannot be undone and will permanently remove all associated data including bookings and account information.</p>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeDeleteModal()" class="px-3 py-1.5 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors text-xs">
                            Cancel
                        </button>
                        <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-xs">
                            Delete User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

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

@keyframes modalSlideIn {
    from { 
        opacity: 0; 
        transform: translateY(-20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

.animate-slideInUp {
    animation: slideInUp 0.6s ease-out forwards;
}

.animate-modalSlideIn {
    animation: modalSlideIn 0.3s ease-out forwards;
}
</style>

<script>
function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('deleteModal');
    if (e.target === modal) {
        closeDeleteModal();
    }
});
</script>
@endsection 