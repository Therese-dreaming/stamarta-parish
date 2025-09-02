@extends('layouts.admin')

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
                        <a href="{{ route('admin.ministries.members.index', $ministry) }}" class="text-white/80 hover:text-white transition-colors mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-user-cog mr-2"></i>
                            Edit Member - {{ $ministry->name }}
                        </h1>
                    </div>
                    <p class="text-white/80 text-sm">Update this member's information</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-user-edit mr-2 text-[#0d5c2f]"></i>
                Member Information
            </h2>
        </div>
        
        <form action="{{ route('admin.ministries.members.update', [$ministry, $member]) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Name and Email Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1 text-gray-500"></i>
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input name="name" value="{{ old('name', $member->name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                           placeholder="Enter full name" required />
                    @error('name')
                        <div class="text-red-600 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-1 text-gray-500"></i>
                        Email Address
                    </label>
                    <input name="email" type="email" value="{{ old('email', $member->email) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                           placeholder="Enter email address" />
                    @error('email')
                        <div class="text-red-600 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Phone and Position Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-1 text-gray-500"></i>
                        Phone Number
                    </label>
                    <input name="phone" value="{{ old('phone', $member->phone) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
                           placeholder="Enter phone number" />
                    @error('phone')
                        <div class="text-red-600 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-briefcase mr-1 text-gray-500"></i>
                        Position/Role
                    </label>
                    <input name="position" value="{{ old('position', $member->position) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error('position') border-red-500 @enderror"
                           placeholder="Enter position or role" />
                    @error('position')
                        <div class="text-red-600 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Joined Date and Active -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-plus mr-1 text-gray-500"></i>
                        Joined Date
                    </label>
                    <input name="joined_at" type="date" value="{{ old('joined_at', optional($member->joined_at)->format('Y-m-d')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error('joined_at') border-red-500 @enderror" />
                    @error('joined_at')
                        <div class="text-red-600 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="flex items-end">
                    <label class="inline-flex items-center text-sm font-medium text-gray-700">
                        <input type="checkbox" name="is_active" value="1" class="mr-2 rounded border-gray-300 text-[#0d5c2f] focus:ring-[#0d5c2f]" {{ old('is_active', $member->is_active) ? 'checked' : '' }} />
                        <i class="fas fa-toggle-on mr-1 text-gray-500"></i>
                        Active
                    </label>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sticky-note mr-1 text-gray-500"></i>
                    Notes
                </label>
                <textarea name="notes" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error('notes') border-red-500 @enderror"
                          placeholder="Enter any additional notes about this member">{{ old('notes', $member->notes) }}</textarea>
                @error('notes')
                    <div class="text-red-600 text-sm mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.ministries.members.index', $ministry) }}"
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors duration-200 flex items-center shadow-sm hover:shadow">
                    <i class="fas fa-save mr-2"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


