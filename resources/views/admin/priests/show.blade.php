@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Priest Details')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Enhanced Header with Priest Profile Preview -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-8 relative">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/5 rounded-bl-full"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
                <div class="flex items-center">
                    <div class="mr-5 hidden md:block">
                        @if($priest->photo_path)
                            <img src="{{ Storage::url($priest->photo_path) }}" alt="{{ $priest->name }}" 
                                class="w-20 h-20 object-cover rounded-full border-4 border-white/30 shadow-lg">
                        @else
                            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center border-4 border-white/30 shadow-lg">
                                <i class="fas fa-user-tie text-white text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center">
                            <h1 class="text-3xl font-bold text-white">{{ $priest->name }}</h1>
                            <span class="ml-3 px-3 py-1 rounded-full text-xs font-medium {{ $priest->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-{{ $priest->is_active ? 'check' : 'times' }}-circle mr-1"></i>
                                {{ $priest->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <p class="text-white/80 mt-2 flex items-center">
                            <i class="fas fa-envelope mr-2"></i>{{ $priest->email }}
                            @if($priest->phone)
                                <span class="mx-3">•</span>
                                <i class="fas fa-phone mr-2"></i>{{ $priest->phone }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 mt-4 md:mt-0">
                    @if(!isset($isStaff) || !$isStaff)
                    <a href="{{ route('admin.priests.edit', $priest) }}" 
                       class="group px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow" title="Edit Priest">
                        <i class="fas fa-edit mr-2 text-sm group-hover:rotate-12 transition-transform duration-300"></i>
                        <span>Edit Priest</span>
                    </a>
                    @endif
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.priests.index') : route('admin.priests.index') }}" 
                       class="w-10 h-10 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Back to Priests">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>
                        Basic Information
                    </h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-lg p-3 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Full Name</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $priest->name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-3 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                    <i class="fas fa-envelope text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Email Address</label>
                                    <p class="text-sm text-gray-900">{{ $priest->email }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-3 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                    <i class="fas fa-phone text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Phone Number</label>
                                    <p class="text-sm text-gray-900">{{ $priest->phone ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-3 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                    <i class="fas fa-toggle-on text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Current Status</label>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-1 {{ $priest->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas fa-{{ $priest->is_active ? 'check' : 'times' }}-circle mr-1"></i>
                                        {{ $priest->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            @if($priest->birth_date || $priest->ordination_date || $priest->address)
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-id-card mr-2 text-[#0d5c2f]"></i>
                        Personal Information
                    </h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($priest->birth_date)
                        <div class="bg-white rounded-lg p-3 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm">
                            <div class="flex items-start">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3 flex-shrink-0 mt-1">
                                    <i class="fas fa-birthday-cake text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Birth Date</label>
                                    <p class="text-sm text-gray-900">{{ $priest->birth_date->format('F j, Y') }}</p>
                                    @if($priest->age)
                                        <p class="text-xs text-gray-500 mt-1">{{ $priest->age }} years old</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($priest->ordination_date)
                        <div class="bg-white rounded-lg p-3 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm">
                            <div class="flex items-start">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3 flex-shrink-0 mt-1">
                                    <i class="fas fa-church text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Ordination Date</label>
                                    <p class="text-sm text-gray-900">{{ $priest->ordination_date->format('F j, Y') }}</p>
                                    @if($priest->years_of_service)
                                        <p class="text-xs text-gray-500 mt-1">{{ $priest->years_of_service }} years of service</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($priest->address)
                        <div class="bg-white rounded-lg p-3 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm md:col-span-2">
                            <div class="flex items-start">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center mr-3 flex-shrink-0 mt-1">
                                    <i class="fas fa-map-marker-alt text-amber-600 text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Address</label>
                                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $priest->address }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Biography -->
            @if($priest->bio)
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-book mr-2 text-[#0d5c2f]"></i>
                        Biography
                    </h3>
                </div>
                <div class="p-4">
                    <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                <i class="fas fa-quote-left text-[#0d5c2f] text-sm"></i>
                            </div>
                            <h4 class="text-sm font-medium text-gray-900">About {{ $priest->name }}</h4>
                        </div>
                        <div class="prose max-w-none">
                            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $priest->bio }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Specializations -->
            @if($priest->specializations && is_array($priest->specializations) && count($priest->specializations) > 0)
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-star mr-2 text-[#0d5c2f]"></i>
                        Specializations
                    </h3>
                </div>
                <div class="p-4">
                    <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                <i class="fas fa-award text-[#0d5c2f] text-sm"></i>
                            </div>
                            <h4 class="text-sm font-medium text-gray-900">Areas of Expertise</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($priest->specializations as $spec)
                                <div class="flex items-center p-2 bg-[#0d5c2f]/5 rounded-lg border border-[#0d5c2f]/10">
                                    <i class="fas fa-check-circle text-[#0d5c2f] mr-2 text-sm"></i>
                                    <span class="text-sm text-gray-800">{{ $spec }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

            <!-- Sidebar -->
            <div class="space-y-4">
                <!-- Profile Picture Card -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-camera mr-2 text-[#0d5c2f]"></i>
                        Profile
                    </h3>
                </div>
                <div class="p-4">
                        @if($priest->photo_path)
                            <div class="relative group">
                                <img src="{{ Storage::url($priest->photo_path) }}" alt="{{ $priest->name }}" 
                                     class="w-full h-48 object-cover rounded-lg shadow-sm">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-start p-4">
                                    <div class="text-white">
                                        <h4 class="font-medium">{{ $priest->name }}</h4>
                                        <p class="text-sm text-white/80">Parish Priest</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-[#0d5c2f] to-[#0d5c2f]/80 rounded-lg flex items-center justify-center shadow-sm">
                                <div class="text-center">
                                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-user-tie text-3xl text-white"></i>
                                    </div>
                                    <p class="text-white font-medium text-lg">{{ $priest->name }}</p>
                                    <p class="text-white/80 text-sm">Parish Priest</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if(!isset($isStaff) || !$isStaff)
                <!-- Quick Actions Card -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt mr-2 text-[#0d5c2f]"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-4">
                        <div class="space-y-3">
                            <a href="{{ route('admin.priests.edit', $priest) }}" 
                               class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm hover:shadow">
                                <i class="fas fa-edit mr-2"></i>Edit Priest
                            </a>
                            
                            @if($priest->is_active)
                                <button type="button" 
                                        onclick="openStatusModal()"
                                        class="w-full flex items-center justify-center px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors shadow-sm hover:shadow">
                                    <i class="fas fa-pause mr-2"></i>Deactivate Priest
                                </button>
                            @else
                                <button type="button"
                                        onclick="openStatusModal()"
                                        class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm hover:shadow">
                                    <i class="fas fa-play mr-2"></i>Activate Priest
                                </button>
                            @endif
                            
                            <button type="button"
                                    onclick="openDeleteModal()"
                                    class="w-full flex items-center justify-center px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors border border-red-200">
                                <i class="fas fa-trash mr-2"></i>Delete Priest
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Priest Stats Card -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-[#0d5c2f]"></i>
                        Priest Stats
                    </h3>
                </div>
                <div class="p-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                <span class="text-xs text-gray-600 flex items-center">
                                    <i class="fas fa-calendar-plus mr-2 text-[#0d5c2f]"></i>Member Since
                                </span>
                                <span class="text-xs font-medium text-gray-900">{{ $priest->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                <span class="text-xs text-gray-600 flex items-center">
                                    <i class="fas fa-clock mr-2 text-[#0d5c2f]"></i>Last Updated
                                </span>
                                <span class="text-xs font-medium text-gray-900">{{ $priest->updated_at->diffForHumans() }}</span>
                            </div>
                            @if($priest->ordination_date)
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                <span class="text-xs text-gray-600 flex items-center">
                                    <i class="fas fa-church mr-2 text-[#0d5c2f]"></i>Years of Service
                                </span>
                                <span class="text-xs font-medium text-[#0d5c2f]">{{ $priest->years_of_service ?? 'N/A' }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Toggle Modal -->
<div id="status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 animate-fade-in-up">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-full bg-{{ $priest->is_active ? 'yellow' : 'green' }}-100 flex items-center justify-center mr-4">
                    <i class="fas fa-{{ $priest->is_active ? 'pause' : 'play' }} text-{{ $priest->is_active ? 'yellow' : 'green' }}-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $priest->is_active ? 'Deactivate' : 'Activate' }} Priest</h3>
                    <p class="text-sm text-gray-500">{{ $priest->is_active ? 'This will make the priest inactive in the system.' : 'This will make the priest active and available for assignments.' }}</p>
                </div>
            </div>
            
            <p class="text-gray-600 mb-6">Are you sure you want to {{ $priest->is_active ? 'deactivate' : 'activate' }} {{ $priest->name }}?</p>
            
            <div class="flex items-center justify-end space-x-3">
                <button type="button" onclick="closeModal('status-modal')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <form action="{{ route('admin.priests.toggle-status', $priest) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2 bg-{{ $priest->is_active ? 'yellow' : 'green' }}-600 text-white rounded-lg hover:bg-{{ $priest->is_active ? 'yellow' : 'green' }}-700 transition-colors">
                        {{ $priest->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 animate-fade-in-up">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Delete Priest</h3>
                    <p class="text-sm text-gray-500">This action cannot be undone</p>
                </div>
            </div>
            
            <p class="text-gray-600 mb-6">Are you sure you want to delete {{ $priest->name }}? This will permanently remove all associated data.</p>
            
            <div class="flex items-center justify-end space-x-3">
                <button type="button" onclick="closeModal('delete-modal')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <form action="{{ route('admin.priests.destroy', $priest) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Delete Priest
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out;
}
</style>

<script>
function openStatusModal() {
    const modal = document.getElementById('status-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function openDeleteModal() {
    const modal = document.getElementById('delete-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Close modals when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modals = ['status-modal', 'delete-modal'];
    
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal(modalId);
                }
            });
        }
    });
});
</script>
@endsection