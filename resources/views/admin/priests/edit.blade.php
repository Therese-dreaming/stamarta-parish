@extends('layouts.admin')

@section('title', 'Edit Priest')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Priest</h1>
            <p class="text-gray-600 mt-1">Update priest information</p>
        </div>
        <a href="{{ route('admin.priests.index') }}" class="text-[#0d5c2f] hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Back to Priests
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form action="{{ route('admin.priests.update', $priest) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Basic Information</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $priest->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $priest->email) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $priest->phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        @error('phone')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">{{ old('address', $priest->address) }}</textarea>
                        @error('address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Additional Information</h3>
                    
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
                        <input type="date" id="birth_date" name="birth_date" 
                               value="{{ old('birth_date', $priest->birth_date ? $priest->birth_date->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        @error('birth_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ordination_date" class="block text-sm font-medium text-gray-700 mb-1">Ordination Date</label>
                        <input type="date" id="ordination_date" name="ordination_date" 
                               value="{{ old('ordination_date', $priest->ordination_date ? $priest->ordination_date->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        @error('ordination_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                        @if($priest->photo_path)
                            <div class="mb-2">
                                <img src="{{ Storage::url($priest->photo_path) }}" alt="{{ $priest->name }}" 
                                     class="h-20 w-20 rounded-lg object-cover">
                                <p class="text-sm text-gray-500 mt-1">Current photo</p>
                            </div>
                        @endif
                        <input type="file" id="photo" name="photo" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                        <p class="text-sm text-gray-500 mt-1">Accepted formats: JPEG, PNG, JPG, GIF (max 2MB)</p>
                        @error('photo')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Specializations -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Specializations</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php
                        $specializations = ['Baptism', 'Wedding', 'Confession', 'Anointing', 'Funeral', 'Blessing', 'Catechesis', 'Youth Ministry'];
                        $currentSpecs = $priest->specializations ?? [];
                    @endphp
                    @foreach($specializations as $spec)
                        <label class="flex items-center">
                            <input type="checkbox" name="specializations[]" value="{{ $spec }}" 
                                   {{ in_array($spec, old('specializations', $currentSpecs)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#0d5c2f] focus:ring-[#0d5c2f]">
                            <span class="ml-2 text-sm text-gray-700">{{ $spec }}</span>
                        </label>
                    @endforeach
                </div>
                @error('specializations')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bio -->
            <div class="mt-6">
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Biography</label>
                <textarea id="bio" name="bio" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">{{ old('bio', $priest->bio) }}</textarea>
                @error('bio')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.priests.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                    <i class="fas fa-save mr-2"></i>Update Priest
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 