@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Priest Management')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Priest Management</h1>
                    <p class="text-white/80 mt-1 flex items-center">
                        <i class="fas fa-cross mr-2"></i>Manage parish priests and their information
                    </p>
                </div>
                @if(!isset($isStaff) || !$isStaff)
                <a href="{{ route('admin.priests.create') }}" 
                   class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Add Priest">
                    <i class="fas fa-plus text-lg"></i>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-users mr-2 text-[#0d5c2f]"></i>
                        Parish Priests
                    </h2>
                    <span class="text-sm text-gray-500">{{ $priests->count() }} priest{{ $priests->count() != 1 ? 's' : '' }}</span>
                </div>
                <div class="flex items-center bg-gray-100 rounded-lg p-1">
                    <button id="table-view-btn" class="px-4 py-2 rounded-md text-sm font-medium transition-colors view-btn active">
                        <i class="fas fa-table mr-2"></i>Table View
                    </button>
                    <button id="card-view-btn" class="px-4 py-2 rounded-md text-sm font-medium transition-colors view-btn">
                        <i class="fas fa-th-large mr-2"></i>Cards View
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if($priests->count() > 0)
                <!-- Table View -->
                <div id="table-view" class="view-content">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priest</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Years of Service</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($priests as $priest)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($priest->photo_path)
                                                <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-200" src="{{ Storage::url($priest->photo_path) }}" alt="{{ $priest->name }}">
                                            @else
                                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center border-2 border-gray-200">
                                                    <i class="fas fa-cross text-white text-lg"></i>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $priest->name }}</div>
                                                <div class="text-sm text-gray-500 flex items-center">
                                                    <i class="fas fa-envelope mr-1 text-xs"></i>{{ $priest->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 flex items-center">
                                            <i class="fas fa-phone mr-2 text-[#0d5c2f]"></i>{{ $priest->phone ?? 'No phone' }}
                                        </div>
                                        <div class="text-sm text-gray-500 flex items-center mt-1">
                                            <i class="fas fa-map-marker-alt mr-2 text-[#0d5c2f]"></i>{{ $priest->address ?? 'No address' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($priest->is_active)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($priest->years_of_service)
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar-alt mr-2 text-[#0d5c2f]"></i>
                                                {{ $priest->years_of_service }} years
                                            </div>
                                        @else
                                            <div class="flex items-center text-gray-500">
                                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                                N/A
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-3">
                                            <a href="{{ isset($isStaff) && $isStaff ? route('staff.priests.show', $priest) : route('admin.priests.show', $priest) }}" 
                                               class="w-8 h-8 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 transition-colors" title="View">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            @if(!isset($isStaff) || !$isStaff)
                                            <a href="{{ route('admin.priests.edit', $priest) }}" 
                                               class="w-8 h-8 rounded-full bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 transition-colors" title="Edit">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            <button type="button" onclick="openModal('toggle-status-modal-{{ $priest->id }}')"
                                                    class="w-8 h-8 rounded-full bg-{{ $priest->is_active ? 'yellow' : 'green' }}-100 hover:bg-{{ $priest->is_active ? 'yellow' : 'green' }}-200 flex items-center justify-center text-{{ $priest->is_active ? 'yellow' : 'green' }}-600 transition-colors" 
                                                    title="{{ $priest->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $priest->is_active ? 'pause' : 'play' }} text-sm"></i>
                                            </button>
                                            <button type="button" onclick="openModal('delete-modal-{{ $priest->id }}')"
                                                    class="w-8 h-8 rounded-full bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 transition-colors" title="Delete">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card View -->
                <div id="card-view" class="view-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($priests as $priest)
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    @if($priest->photo_path)
                                        <img class="h-16 w-16 rounded-full object-cover border-2 border-gray-200" src="{{ Storage::url($priest->photo_path) }}" alt="{{ $priest->name }}">
                                    @else
                                        <div class="h-16 w-16 rounded-full bg-gradient-to-br from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center border-2 border-gray-200">
                                            <i class="fas fa-cross text-white text-xl"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $priest->name }}</h3>
                                        <p class="text-sm text-gray-500 flex items-center">
                                            <i class="fas fa-envelope mr-1"></i>{{ $priest->email }}
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-3 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-phone mr-2 text-[#0d5c2f] w-4"></i>
                                        <span>{{ $priest->phone ?? 'No phone' }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-2 text-[#0d5c2f] w-4"></i>
                                        <span>{{ $priest->address ?? 'No address' }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-calendar-alt mr-2 text-[#0d5c2f] w-4"></i>
                                        <span>{{ $priest->years_of_service ? $priest->years_of_service . ' years' : 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    @if($priest->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Inactive
                                        </span>
                                    @endif

                                    <div class="flex items-center space-x-2">
                                        <a href="{{ isset($isStaff) && $isStaff ? route('staff.priests.show', $priest) : route('admin.priests.show', $priest) }}" 
                                           class="w-8 h-8 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 transition-colors" title="View">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        @if(!isset($isStaff) || !$isStaff)
                                        <a href="{{ route('admin.priests.edit', $priest) }}" 
                                           class="w-8 h-8 rounded-full bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 transition-colors" title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <button type="button" onclick="openModal('toggle-status-modal-{{ $priest->id }}')"
                                                class="w-8 h-8 rounded-full bg-{{ $priest->is_active ? 'yellow' : 'green' }}-100 hover:bg-{{ $priest->is_active ? 'yellow' : 'green' }}-200 flex items-center justify-center text-{{ $priest->is_active ? 'yellow' : 'green' }}-600 transition-colors" 
                                                title="{{ $priest->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $priest->is_active ? 'pause' : 'play' }} text-sm"></i>
                                        </button>
                                        <button type="button" onclick="openModal('delete-modal-{{ $priest->id }}')"
                                                class="w-8 h-8 rounded-full bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 transition-colors" title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $priests->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center">
                        <i class="fas fa-cross text-white text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No priests found</h3>
                    <p class="text-gray-600 mb-6">Get started by adding your first priest to the system.</p>
                    @if(!isset($isStaff) || !$isStaff)
                    <a href="{{ route('admin.priests.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add First Priest
                    </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals for each priest -->
@foreach($priests as $priest)
    <!-- Delete Modal -->
    <x-modal 
        id="delete-modal-{{ $priest->id }}"
        title="Delete Priest"
        message="Are you sure you want to delete {{ $priest->name }}? This action cannot be undone and will permanently remove all associated data."
        confirmText="Delete Priest"
        confirmClass="bg-red-600 hover:bg-red-700">
        @if(!isset($isStaff) || !$isStaff)
        <form action="{{ route('admin.priests.destroy', $priest) }}" method="POST">
            @csrf
            @method('DELETE')
        </form>
        @endif
    </x-modal>

    <!-- Toggle Status Modal -->
    <x-modal 
        id="toggle-status-modal-{{ $priest->id }}"
        title="{{ $priest->is_active ? 'Deactivate' : 'Activate' }} Priest"
        message="Are you sure you want to {{ $priest->is_active ? 'deactivate' : 'activate' }} {{ $priest->name }}? {{ $priest->is_active ? 'This will make the priest inactive in the system.' : 'This will make the priest active and available for assignments.' }}"
        confirmText="{{ $priest->is_active ? 'Deactivate' : 'Activate' }}"
        confirmClass="{{ $priest->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}">
        @if(!isset($isStaff) || !$isStaff)
        <form action="{{ route('admin.priests.toggle-status', $priest) }}" method="POST">
            @csrf
        </form>
        @endif
    </x-modal>
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableViewBtn = document.getElementById('table-view-btn');
    const cardViewBtn = document.getElementById('card-view-btn');
    const tableView = document.getElementById('table-view');
    const cardView = document.getElementById('card-view');
    const viewBtns = document.querySelectorAll('.view-btn');

    // Load saved preference
    const savedView = localStorage.getItem('priest-view-preference') || 'table';
    setActiveView(savedView);

    tableViewBtn.addEventListener('click', function() {
        setActiveView('table');
        localStorage.setItem('priest-view-preference', 'table');
    });

    cardViewBtn.addEventListener('click', function() {
        setActiveView('card');
        localStorage.setItem('priest-view-preference', 'card');
    });

    function setActiveView(view) {
        // Update button states
        viewBtns.forEach(btn => {
            btn.classList.remove('active', 'bg-white', 'text-gray-900');
            btn.classList.add('text-gray-600');
        });

        if (view === 'table') {
            tableViewBtn.classList.add('active', 'bg-white', 'text-gray-900');
            tableView.classList.remove('hidden');
            cardView.classList.add('hidden');
        } else {
            cardViewBtn.classList.add('active', 'bg-white', 'text-gray-900');
            cardView.classList.remove('hidden');
            tableView.classList.add('hidden');
        }
    }
});
</script>

<style>
.view-btn.active {
    background-color: white;
    color: #111827;
}
</style>
@endsection 