@extends('layouts.user')

@section('title', 'Edit Profile')

@section('content')
<main class="flex-grow">
    <!-- Hero Section -->
    <div class="relative h-[40vh] -mt-[80px]">
        <img src="{{ asset('images/church-bg.jpg') }}" alt="Church Background" class="absolute inset-0 w-full h-full object-cover brightness-50" />
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">Edit Profile</h1>
            <p class="text-xl">Update your account information</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#1a8045] px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-white flex items-center">
                                <i class="fas fa-user-edit mr-3"></i>
                                Profile Information
                            </h2>
                            <a href="{{ route('profile') }}" class="text-white/80 hover:text-white transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Profile
                            </a>
                        </div>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('profile.update') }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')

                        @if(session('success'))
                            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                    <span class="text-green-800 font-medium">{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 flex items-center">
                                    <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>
                                    Basic Information
                                </h3>
                                
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="text-red-600 text-sm mt-1 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror">
                                    @error('email')
                                        <p class="text-red-600 text-sm mt-1 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date of Birth
                                        @if(!$user->date_of_birth)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" 
                                           value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                                           max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error('date_of_birth') border-red-500 @enderror">
                                    @error('date_of_birth')
                                        <p class="text-red-600 text-sm mt-1 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                    @if($user->date_of_birth)
                                        <p class="text-sm text-gray-500 mt-1">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Current age: {{ $user->age }} years old
                                        </p>
                                    @else
                                        <p class="text-sm text-amber-600 mt-1">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Date of birth is required for booking wedding services
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Password Section -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 flex items-center">
                                    <i class="fas fa-lock mr-2 text-[#0d5c2f]"></i>
                                    Change Password
                                </h3>
                                
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Current Password
                                    </label>
                                    <input type="password" id="current_password" name="current_password"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error('current_password') border-red-500 @enderror">
                                    @error('current_password')
                                        <p class="text-red-600 text-sm mt-1 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        New Password
                                    </label>
                                    <input type="password" id="new_password" name="new_password"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error('new_password') border-red-500 @enderror">
                                    @error('new_password')
                                        <p class="text-red-600 text-sm mt-1 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Confirm New Password
                                    </label>
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200">
                                </div>

                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-info-circle text-blue-600 mr-2 mt-0.5"></i>
                                        <div class="text-sm text-blue-800">
                                            <p class="font-medium mb-1">Password Requirements:</p>
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>At least 8 characters long</li>
                                                <li>Leave blank if you don't want to change your password</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-8">
                            <a href="{{ route('profile') }}" 
                               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors font-medium">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#1a8045] transition-colors font-medium">
                                <i class="fas fa-save mr-2"></i>
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
