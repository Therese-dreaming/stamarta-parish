@extends('layouts.priest')

@section('title', 'Edit Profile')

@section('content')
<div class="font-[Poppins] min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-6 relative">
                <div class="absolute right-0 top-0 w-20 h-20 bg-white/10 rounded-bl-full"></div>
                <div class="absolute bottom-0 left-0 w-14 h-14 bg-white/5 rounded-tr-full"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white">Edit Profile</h1>
                        <p class="text-white/90">Update your personal and professional information</p>
                    </div>
                    <a href="{{ route('priest.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-[#0d5c2f]/10 text-[#0d5c2f] mr-3">
                        <i class="fas fa-user"></i>
                    </span>
                    Personal Information
                </h2>
                <p class="text-sm text-gray-600 mt-1">Update your profile details</p>
            </div>

            <form action="{{ route('priest.profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               value="{{ old('name', $priest->user->name) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required
                               value="{{ old('email', $priest->user->email) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <input type="tel" name="phone" id="phone"
                               value="{{ old('phone', $priest->phone) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Birth Date
                        </label>
                        <input type="date" name="birth_date" id="birth_date"
                               value="{{ old('birth_date', $priest->birth_date?->format('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors">
                        @error('birth_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Address
                        </label>
                        <textarea name="address" id="address" rows="3"
                                  placeholder="Enter your complete address..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors resize-none">{{ old('address', $priest->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="mt-12">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 -mx-6 -mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-[#0d5c2f]/10 text-[#0d5c2f] mr-3">
                                <i class="fas fa-briefcase"></i>
                            </span>
                            Professional Information
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Update your professional details</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <!-- Ordination Date -->
                        <div>
                            <label for="ordination_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Ordination Date
                            </label>
                            <input type="date" name="ordination_date" id="ordination_date"
                                   value="{{ old('ordination_date', $priest->ordination_date?->format('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors">
                            @error('ordination_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Years of Service -->
                        <div>
                            <label for="years_of_service" class="block text-sm font-medium text-gray-700 mb-2">
                                Years of Service
                            </label>
                            <input type="number" name="years_of_service" id="years_of_service" min="0" max="100"
                                   value="{{ old('years_of_service', $priest->years_of_service) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors">
                            @error('years_of_service')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Leave blank to auto-calculate from ordination date</p>
                        </div>
                    </div>
                </div>

                <!-- Password Change Section -->
                <div class="mt-12">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 -mx-6 -mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-[#0d5c2f]/10 text-[#0d5c2f] mr-3">
                                <i class="fas fa-lock"></i>
                            </span>
                            Change Password
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Leave blank to keep current password</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Current Password
                            </label>
                            <input type="password" name="current_password" id="current_password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors">
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                New Password
                            </label>
                            <input type="password" name="password" id="password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="md:col-span-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm New Password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('priest.dashboard') }}" 
                       class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors font-medium shadow-sm hover:shadow-md">
                        <i class="fas fa-save mr-2"></i>
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
