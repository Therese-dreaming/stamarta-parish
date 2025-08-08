@extends('layouts.admin')

@section('title', 'Add Priest')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Add New Priest</h1>
                    <p class="text-white/80 mt-1 flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i>Add a new priest to the parish system
                    </p>
                </div>
                <a href="{{ route('admin.priests.index') }}" 
                   class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Back to Priests">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form action="{{ route('admin.priests.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                            <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>Basic Information
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>Full Name *
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                                @error('name')
                                    <p class="text-red-600 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-envelope mr-2 text-[#0d5c2f]"></i>Email *
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                                @error('email')
                                    <p class="text-red-600 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-phone mr-2 text-[#0d5c2f]"></i>Phone Number
                                </label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                                @error('phone')
                                    <p class="text-red-600 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#0d5c2f]"></i>Address
                                </label>
                                <textarea id="address" name="address" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="text-red-600 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                            <i class="fas fa-id-card mr-2 text-[#0d5c2f]"></i>Additional Information
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-birthday-cake mr-2 text-[#0d5c2f]"></i>Birth Date
                                </label>
                                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                                @error('birth_date')
                                    <p class="text-red-600 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="ordination_date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-church mr-2 text-[#0d5c2f]"></i>Ordination Date
                                </label>
                                <input type="date" id="ordination_date" name="ordination_date" value="{{ old('ordination_date') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                                @error('ordination_date')
                                    <p class="text-red-600 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="photo" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-camera mr-2 text-[#0d5c2f]"></i>Photo
                                </label>
                                <input type="file" id="photo" name="photo" accept="image/*"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">
                                <p class="text-sm text-gray-500 mt-2 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>Accepted formats: JPEG, PNG, JPG, GIF (max 2MB)
                                </p>
                                @error('photo')
                                    <p class="text-red-600 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Specializations -->
            <div class="mt-8">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                        <i class="fas fa-star mr-2 text-[#0d5c2f]"></i>Specializations
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $specializations = ['Baptism', 'Wedding', 'Confession', 'Anointing', 'Funeral', 'Blessing', 'Catechesis', 'Youth Ministry'];
                        @endphp
                        @foreach($specializations as $spec)
                            <label class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="specializations[]" value="{{ $spec }}" 
                                       {{ in_array($spec, old('specializations', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-[#0d5c2f] focus:ring-[#0d5c2f]">
                                <span class="ml-3 text-sm text-gray-700">{{ $spec }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('specializations')
                        <p class="text-red-600 text-sm mt-3 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Bio -->
            <div class="mt-8">
                <div class="bg-gray-50 rounded-lg p-4">
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-book mr-2 text-[#0d5c2f]"></i>Biography
                    </label>
                    <textarea id="bio" name="bio" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] bg-white">{{ old('bio') }}</textarea>
                    @error('bio')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.priests.index') }}" 
                   class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors flex items-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>Save Priest
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 