@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">User Details</h1>
                    <p class="text-white/80 mt-1">View user information and account details</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Edit User">
                        <i class="fas fa-edit text-lg"></i>
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Back to Users">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                            <p class="text-lg font-medium text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-lg text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Role</label>
                            @php
                                $roleColors = [
                                    'admin' => 'bg-red-100 text-red-800',
                                    'priest' => 'bg-purple-100 text-purple-800',
                                    'staff' => 'bg-blue-100 text-blue-800',
                                    'user' => 'bg-green-100 text-green-800'
                                ];
                                $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColor }}">
                                <i class="fas fa-{{ $user->role === 'admin' ? 'crown' : ($user->role === 'priest' ? 'cross' : ($user->role === 'staff' ? 'user-tie' : 'user')) }} mr-1"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Pending Verification
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Account Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-calendar-plus text-gray-600 mr-2"></i>
                                <span class="font-medium text-gray-900">Member Since</span>
                            </div>
                            <p class="text-gray-700">{{ $user->created_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-clock text-gray-600 mr-2"></i>
                                <span class="font-medium text-gray-900">Last Updated</span>
                            </div>
                            <p class="text-gray-700">{{ $user->updated_at->format('F j, Y g:i A') }}</p>
                        </div>
                        @if($user->email_verified_at)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                <span class="font-medium text-green-900">Email Verified</span>
                            </div>
                            <p class="text-green-700">{{ $user->email_verified_at->format('F j, Y g:i A') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Profile Picture -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Profile</h3>
                </div>
                <div class="p-6">
                    <div class="w-full h-48 bg-gradient-to-br from-[#0d5c2f] to-[#0d5c2f]/80 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-user text-3xl text-white"></i>
                            </div>
                            <p class="text-white font-medium">{{ $user->name }}</p>
                            <p class="text-white/80 text-sm">{{ ucfirst($user->role) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit User
                        </a>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-3 bg-{{ $user->is_active ? 'yellow' : 'green' }}-600 text-white rounded-lg hover:bg-{{ $user->is_active ? 'yellow' : 'green' }}-700 transition-colors">
                                    <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }} mr-2"></i>
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                                </button>
                            </form>
                        @else
                            <div class="w-full flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-500 rounded-lg">
                                <i class="fas fa-lock mr-2"></i>Cannot modify own account
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Account Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Account Stats</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Account Age</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Last Activity</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->updated_at->diffForHumans() }}</span>
                        </div>
                        @if($user->email_verified_at)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Verified</span>
                            <span class="text-sm font-medium text-green-600">{{ $user->email_verified_at->diffForHumans() }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 