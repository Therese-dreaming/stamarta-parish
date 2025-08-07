@extends('layouts.admin')

@section('title', 'Service Management')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Service Management</h1>
                    <p class="text-white/80 mt-1">Manage parish services and schedules</p>
                </div>
            </div>
        </div>
    </div>

    <!-- View Toggle (Full width tab style) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="flex border-b border-gray-200">
            <button id="table-view-btn" class="flex-1 px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-table mr-2"></i> Table View
            </button>
            <button id="card-view-btn" class="flex-1 px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                <i class="fas fa-th-large mr-2"></i> Cards View
            </button>
        </div>
        
        <div class="p-6">
            @if($services->count() > 0)
                <!-- Table View -->
                <div id="table-view" class="overflow-x-auto hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fees</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($services as $service)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                                        @if($service->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($service->description, 60) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($service->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $service->formatted_duration }}</div>
                                    <div class="text-sm text-gray-500">Max: {{ $service->max_slots }} slots</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $service->formatted_fees }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($service->schedules && count($service->schedules) > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(array_keys($service->schedules) as $day)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                    {{ ucfirst($day) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">No schedule</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.services.edit', $service) }}" class="w-8 h-8 rounded-full bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-600 hover:text-indigo-800 transition-colors" title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.services.toggle-status', $service) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-8 h-8 rounded-full bg-{{ $service->is_active ? 'yellow' : 'green' }}-100 hover:bg-{{ $service->is_active ? 'yellow' : 'green' }}-200 flex items-center justify-center text-{{ $service->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $service->is_active ? 'yellow' : 'green' }}-800 transition-colors" 
                                                    title="{{ $service->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $service->is_active ? 'pause' : 'play' }} text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Cards View -->
                <div id="card-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
                    @foreach($services as $service)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full">
                        <!-- Service Header -->
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $service->name }}</h3>
                                @if($service->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Inactive
                                    </span>
                                @endif
                            </div>
                            <p class="text-gray-600 text-sm">{{ $service->description }}</p>
                        </div>

                        <!-- Service Details -->
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Duration</label>
                                    <p class="text-gray-900">{{ $service->formatted_duration }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Max Slots</label>
                                    <p class="text-gray-900">{{ $service->max_slots }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Fees</label>
                                <p class="text-gray-900 text-sm">{{ $service->formatted_fees }}</p>
                            </div>

                            <!-- Schedule Preview -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Schedule</label>
                                @if($service->schedules && count($service->schedules) > 0)
                                    <div class="space-y-1">
                                        @foreach($service->schedules as $day => $times)
                                            <div class="flex justify-between text-xs">
                                                <span class="text-gray-600 capitalize">{{ $day }}</span>
                                                <span class="text-gray-900">{{ implode(', ', $times) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-xs text-gray-500 italic">No schedule set</p>
                                @endif
                            </div>

                            <!-- Requirements Preview -->
                            @if($service->requirements && count($service->requirements) > 0)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Requirements</label>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($service->requirements, 0, 3) as $requirement)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#0d5c2f]/10 text-[#0d5c2f]">
                                            {{ $requirement }}
                                        </span>
                                    @endforeach
                                    @if(count($service->requirements) > 3)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            +{{ count($service->requirements) - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 mt-auto">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('admin.services.edit', $service) }}" 
                                   class="w-8 h-8 rounded-full bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-600 hover:text-indigo-800 transition-colors" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('admin.services.toggle-status', $service) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="w-8 h-8 rounded-full bg-{{ $service->is_active ? 'yellow' : 'green' }}-100 hover:bg-{{ $service->is_active ? 'yellow' : 'green' }}-200 flex items-center justify-center text-{{ $service->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $service->is_active ? 'yellow' : 'green' }}-800 transition-colors" 
                                            title="{{ $service->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $service->is_active ? 'pause' : 'play' }} text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No services found</h3>
                    <p class="text-gray-600">No services have been configured yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableViewBtn = document.getElementById('table-view-btn');
        const cardViewBtn = document.getElementById('card-view-btn');
        const tableView = document.getElementById('table-view');
        const cardView = document.getElementById('card-view');
        
        // Function to toggle views
        function showTableView() {
            if (window.innerWidth >= 768) { // md breakpoint
                tableView.classList.remove('hidden');
                cardView.classList.add('hidden');
                tableViewBtn.classList.add('text-[#0d5c2f]', 'border-[#0d5c2f]');
                tableViewBtn.classList.remove('text-gray-600', 'border-transparent');
                cardViewBtn.classList.remove('text-[#0d5c2f]', 'border-[#0d5c2f]');
                cardViewBtn.classList.add('text-gray-600', 'border-transparent');
                
                // Save preference
                localStorage.setItem('adminServicesViewPreference', 'table');
            }
        }
        
        function showCardView() {
            if (window.innerWidth >= 768) { // Only allow card view on desktop
                tableView.classList.add('hidden');
                cardView.classList.remove('hidden');
                cardViewBtn.classList.add('text-[#0d5c2f]', 'border-[#0d5c2f]');
                cardViewBtn.classList.remove('text-gray-600', 'border-transparent');
                tableViewBtn.classList.remove('text-[#0d5c2f]', 'border-[#0d5c2f]');
                tableViewBtn.classList.add('text-gray-600', 'border-transparent');
                
                // Save preference
                localStorage.setItem('adminServicesViewPreference', 'card');
            }
        }
        
        // Event listeners
        if (tableViewBtn) {
            tableViewBtn.addEventListener('click', showTableView);
        }
        if (cardViewBtn) {
            cardViewBtn.addEventListener('click', showCardView);
        }
        
        // Check for saved preference
        const savedPreference = localStorage.getItem('adminServicesViewPreference');
        
        // Initial view setup
        if (window.innerWidth < 768) {
            // Always show cards on mobile
            if (cardView) cardView.classList.remove('hidden');
            if (tableView) tableView.classList.add('hidden');
        } else {
            // On desktop, respect user preference if available
            if (savedPreference === 'card') {
                showCardView();
            } else {
                // Default to table view
                showTableView();
            }
        }
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth < 768) {
                // Force card view on mobile
                if (cardView) cardView.classList.remove('hidden');
                if (tableView) tableView.classList.add('hidden');
            } else {
                // On desktop, restore the saved preference
                const currentPreference = localStorage.getItem('adminServicesViewPreference');
                if (currentPreference === 'card') {
                    showCardView();
                } else {
                    showTableView();
                }
            }
        });
    });
</script>
@endsection 