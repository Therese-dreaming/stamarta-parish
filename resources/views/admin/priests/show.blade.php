@extends('layouts.admin')

@section('title', 'Priest Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Priest Details</h1>
            <p class="text-gray-600 mt-1">View priest information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.priests.edit', $priest) }}" class="bg-[#0d5c2f] text-white px-4 py-2 rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.priests.index') }}" class="text-[#0d5c2f] hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to Priests
            </a>
        </div>
    </div>

    <!-- Priest Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Full Name</label>
                        <p class="text-lg font-medium text-gray-900">{{ $priest->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="text-lg text-gray-900">{{ $priest->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Phone Number</label>
                        <p class="text-lg text-gray-900">{{ $priest->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priest->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-{{ $priest->is_active ? 'check' : 'times' }}-circle mr-1"></i>
                            {{ $priest->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Address -->
            @if($priest->address)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Address</h3>
                <p class="text-gray-900 whitespace-pre-line">{{ $priest->address }}</p>
            </div>
            @endif

            <!-- Biography -->
            @if($priest->bio)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Biography</h3>
                <p class="text-gray-900 whitespace-pre-line">{{ $priest->bio }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Photo -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Photo</h3>
                @if($priest->photo_path)
                    <img src="{{ Storage::url($priest->photo_path) }}" alt="{{ $priest->name }}" 
                         class="w-full h-64 object-cover rounded-lg">
                @else
                    <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cross text-6xl text-gray-400"></i>
                    </div>
                @endif
            </div>

            <!-- Personal Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Details</h3>
                <div class="space-y-3">
                    @if($priest->birth_date)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Birth Date</label>
                        <p class="text-gray-900">{{ $priest->birth_date->format('F j, Y') }}</p>
                        @if($priest->age)
                            <p class="text-sm text-gray-500">{{ $priest->age }} years old</p>
                        @endif
                    </div>
                    @endif

                    @if($priest->ordination_date)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Ordination Date</label>
                        <p class="text-gray-900">{{ $priest->ordination_date->format('F j, Y') }}</p>
                        @if($priest->years_of_service)
                            <p class="text-sm text-gray-500">{{ $priest->years_of_service }} years of service</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Specializations -->
            @if($priest->specializations && count($priest->specializations) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Specializations</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($priest->specializations as $spec)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#0d5c2f]/10 text-[#0d5c2f]">
                            {{ $spec }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 