@extends('layouts.admin')

@section('title', 'Create FAQ')

@section('content')
@include('components.toast')
<div class="space-y-5">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-lg shadow-md overflow-hidden">
        <div class="px-5 py-5 relative">
            <div class="absolute right-0 top-0 w-16 h-16 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-xl font-bold text-white">Create New FAQ</h1>
                    <p class="text-white/80 mt-1.5 text-sm flex items-center">
                        <i class="fas fa-plus mr-2 text-sm"></i>Add a new frequently asked question
                    </p>
                </div>
                <a href="{{ route('admin.faqs.index') }}" 
                   class="group px-3.5 py-1.5 rounded-md bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow text-sm">
                    <i class="fas fa-arrow-left mr-1.5 text-sm group-hover:-translate-x-1 transition-transform duration-200"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.faqs.store') }}" method="POST" class="space-y-5">
        @csrf
        
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-edit mr-2.5 text-[#0d5c2f] text-base"></i>
                    FAQ Information
                </h2>
            </div>
            <div class="p-5 space-y-4">
                <div class="group">
                    <label for="question" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-question mr-1.5 text-[#0d5c2f] text-xs"></i>Question *
                    </label>
                    <input type="text" name="question" id="question" value="{{ old('question') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50"
                           placeholder="Enter the frequently asked question">
                    @error('question')
                        <p class="mt-1 text-xs text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="group">
                    <label for="answer" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-comment mr-1.5 text-[#0d5c2f] text-xs"></i>Answer *
                    </label>
                    <textarea name="answer" id="answer" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50 resize-y"
                              placeholder="Provide a clear and helpful answer">{{ old('answer') }}</textarea>
                    @error('answer')
                        <p class="mt-1 text-xs text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="group">
                        <label for="category" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-tags mr-1.5 text-[#0d5c2f] text-xs"></i>Category *
                        </label>
                        <select name="category" id="category" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50">
                            <option value="">Select a category</option>
                            <option value="booking" {{ old('category') == 'booking' ? 'selected' : '' }}>Booking</option>
                            <option value="services" {{ old('category') == 'services' ? 'selected' : '' }}>Services</option>
                            <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="payment" {{ old('category') == 'payment' ? 'selected' : '' }}>Payment</option>
                            <option value="schedule" {{ old('category') == 'schedule' ? 'selected' : '' }}>Schedule</option>
                            @foreach($categories as $category)
                                @if(!in_array($category, ['booking', 'services', 'general', 'payment', 'schedule']))
                                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="order" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-sort-numeric-up mr-1.5 text-[#0d5c2f] text-xs"></i>Display Order
                        </label>
                        <input type="number" name="order" id="order" value="{{ old('order', 0) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50"
                               placeholder="0">
                        @error('order')
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="group">
                    <label for="keywords" class="block text-xs font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-key mr-1.5 text-[#0d5c2f] text-xs"></i>Keywords
                    </label>
                    <input type="text" name="keywords" id="keywords" value="{{ old('keywords') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm transition-all duration-200 group-hover:border-[#0d5c2f]/50"
                           placeholder="book, schedule, appointment (comma separated)">
                    <p class="mt-1 text-xs text-gray-500">Enter keywords separated by commas to help users find this FAQ</p>
                    @error('keywords')
                        <p class="mt-1 text-xs text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1 text-xs"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Settings -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-cog mr-2.5 text-[#0d5c2f] text-base"></i>
                    Settings
                </h2>
            </div>
            <div class="p-5">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-[#0d5c2f] focus:ring-[#0d5c2f] border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm font-medium text-gray-900 flex items-center">
                        <i class="fas fa-toggle-on mr-1.5 text-[#0d5c2f] text-xs"></i>Active (visible in chatbot)
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.faqs.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors flex items-center text-sm">
                <i class="fas fa-times mr-1.5 text-sm"></i>Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-[#0d5c2f] text-white rounded-md hover:bg-[#0a4a26] transition-colors flex items-center text-sm group">
                <i class="fas fa-save mr-1.5 text-sm group-hover:scale-110 transition-transform duration-200"></i>Create FAQ
            </button>
        </div>
    </form>
</div>

<script>
// Add focus effects to form inputs
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input, select, textarea');
    
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