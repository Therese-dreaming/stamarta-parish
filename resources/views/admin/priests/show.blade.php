@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Priest Details')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Priest Details</h1>
                    <p class="text-white/80 mt-1">View priest information and details</p>
                </div>
                <div class="flex items-center space-x-3">
                    @if(!isset($isStaff) || !$isStaff)
                    <a href="{{ route('admin.priests.edit', $priest) }}" 
                       class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Edit Priest">
                        <i class="fas fa-edit text-lg"></i>
                    </a>
                    @endif
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.priests.index') : route('admin.priests.index') }}" 
                       class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Back to Priests">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Priest Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>
                        Basic Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>Full Name
                            </label>
                            <p class="text-lg font-medium text-gray-900">{{ $priest->name }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-envelope mr-2 text-[#0d5c2f]"></i>Email
                            </label>
                            <p class="text-lg text-gray-900">{{ $priest->email }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-phone mr-2 text-[#0d5c2f]"></i>Phone Number
                            </label>
                            <p class="text-lg text-gray-900">{{ $priest->phone ?? 'Not provided' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-toggle-on mr-2 text-[#0d5c2f]"></i>Status
                            </label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $priest->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-{{ $priest->is_active ? 'check' : 'times' }}-circle mr-1"></i>
                                {{ $priest->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-id-card mr-2 text-[#0d5c2f]"></i>
                        Personal Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($priest->birth_date)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-birthday-cake text-gray-600 mr-2"></i>
                                <span class="font-medium text-gray-900">Birth Date</span>
                            </div>
                            <p class="text-gray-700">{{ $priest->birth_date->format('F j, Y') }}</p>
                            @if($priest->age)
                                <p class="text-sm text-gray-500 mt-1">{{ $priest->age }} years old</p>
                            @endif
                        </div>
                        @endif

                        @if($priest->ordination_date)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-church text-gray-600 mr-2"></i>
                                <span class="font-medium text-gray-900">Ordination Date</span>
                            </div>
                            <p class="text-gray-700">{{ $priest->ordination_date->format('F j, Y') }}</p>
                            @if($priest->years_of_service)
                                <p class="text-sm text-gray-500 mt-1">{{ $priest->years_of_service }} years of service</p>
                            @endif
                        </div>
                        @endif

                        @if($priest->address)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 md:col-span-2">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-map-marker-alt text-gray-600 mr-2"></i>
                                <span class="font-medium text-gray-900">Address</span>
                            </div>
                            <p class="text-gray-700 whitespace-pre-line">{{ $priest->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Biography -->
            @if($priest->bio)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-book mr-2 text-[#0d5c2f]"></i>
                        Biography
                    </h3>
                </div>
                <div class="p-6">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-line">{{ $priest->bio }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Profile Picture -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-camera mr-2 text-[#0d5c2f]"></i>
                        Profile
                    </h3>
                </div>
                <div class="p-6">
                    @if($priest->photo_path)
                        <img src="{{ Storage::url($priest->photo_path) }}" alt="{{ $priest->name }}" 
                             class="w-full h-48 object-cover rounded-lg border-2 border-gray-200">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-[#0d5c2f] to-[#0d5c2f]/80 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-cross text-3xl text-white"></i>
                                </div>
                                <p class="text-white font-medium">{{ $priest->name }}</p>
                                <p class="text-white/80 text-sm">Priest</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if(!isset($isStaff) || !$isStaff)
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt mr-2 text-[#0d5c2f]"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.priests.edit', $priest) }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit Priest
                        </a>
                        
                        @if($priest->is_active)
                            <form action="{{ route('admin.priests.toggle-status', $priest) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                                    <i class="fas fa-pause mr-2"></i>Deactivate Priest
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.priests.toggle-status', $priest) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-play mr-2"></i>Activate Priest
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Specializations -->
            @if($priest->specializations && is_array($priest->specializations) && count($priest->specializations) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-star mr-2 text-[#0d5c2f]"></i>
                        Specializations
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach($priest->specializations as $spec)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#0d5c2f]/10 text-[#0d5c2f] border border-[#0d5c2f]/20">
                                <i class="fas fa-check-circle mr-1 text-xs"></i>{{ $spec }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Priest Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-[#0d5c2f]"></i>
                        Priest Stats
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-calendar-plus mr-2 text-[#0d5c2f]"></i>Member Since
                            </span>
                            <span class="text-sm font-medium text-gray-900">{{ $priest->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-clock mr-2 text-[#0d5c2f]"></i>Last Updated
                            </span>
                            <span class="text-sm font-medium text-gray-900">{{ $priest->updated_at->diffForHumans() }}</span>
                        </div>
                        @if($priest->ordination_date)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-church mr-2 text-[#0d5c2f]"></i>Years of Service
                            </span>
                            <span class="text-sm font-medium text-[#0d5c2f]">{{ $priest->years_of_service ?? 'N/A' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 