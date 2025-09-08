@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Service Management')

@section('content')
@include('components.toast')
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-lg shadow-md overflow-hidden">
        <div class="px-4 py-4 relative">
            <div class="absolute right-0 top-0 w-16 h-16 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-xl font-bold text-white">Service Management</h1>
                    <p class="text-white/80 mt-1 text-xs">Manage parish services and schedules</p>
                </div>
            </div>
        </div>
    </div>

    <!-- View Toggle (Full width tab style) -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="flex border-b border-gray-200">
            <button id="table-view-btn" class="flex-1 px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 border-b-2 border-transparent">
                <i class="fas fa-table mr-2"></i> Table View
            </button>
            <button id="card-view-btn" class="flex-1 px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 border-b-2 border-transparent">
                <i class="fas fa-th-large mr-2"></i> Cards View
            </button>
        </div>
        
        <div class="p-4">
            @if($services->count() > 0)
                <!-- Table View -->
                <div id="table-view" class="overflow-x-auto hidden animate-fadeIn">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fees</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($services as $service)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 animate-slideInUp" style="animation-delay: {{ $loop->index * 50 }}ms">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                                        @if($service->description)
                                            <div class="text-xs text-gray-500">{{ Str::limit($service->description, 50) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($service->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-check-circle mr-1 text-xs"></i>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-times-circle mr-1 text-xs"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $service->formatted_duration }}</div>
                                    <div class="text-xs text-gray-500">Max: {{ $service->max_slots }} slots</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $service->formatted_fees }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($service->schedules && count($service->schedules) > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(array_keys($service->schedules) as $day)
                                                <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                    {{ ucfirst($day) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">No schedule</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ isset($isStaff) && $isStaff ? route('staff.services.show', $service) : route('admin.services.show', $service) }}" class="w-7 h-7 rounded-lg bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-all duration-200 hover:scale-110" title="View">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        @if(!isset($isStaff) || !$isStaff)
                                        <a href="{{ route('admin.services.edit', $service) }}" class="w-7 h-7 rounded-lg bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-600 hover:text-indigo-800 transition-all duration-200 hover:scale-110" title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <button type="button" onclick="openModal('toggle-status-modal-{{ $service->id }}')"
                                                class="w-7 h-7 rounded-lg bg-{{ $service->is_active ? 'yellow' : 'green' }}-100 hover:bg-{{ $service->is_active ? 'yellow' : 'green' }}-200 flex items-center justify-center text-{{ $service->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $service->is_active ? 'yellow' : 'green' }}-800 transition-all duration-200 hover:scale-110" 
                                                title="{{ $service->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $service->is_active ? 'pause' : 'play' }} text-xs"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Cards View -->
                <div id="card-view" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden animate-fadeIn">
                    @foreach($services as $service)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden flex flex-col h-full hover:shadow-lg transition-all duration-300 animate-slideInUp hover:-translate-y-1" style="animation-delay: {{ $loop->index * 100 }}ms">
                        <!-- Service Header -->
                        <div class="p-4 border-b border-gray-200 bg-gray-50">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-base font-semibold text-gray-900">{{ $service->name }}</h3>
                                @if($service->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-check-circle mr-1 text-xs"></i>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-times-circle mr-1 text-xs"></i>Inactive
                                    </span>
                                @endif
                            </div>
                            @if($service->description)
                                <p class="text-gray-600 text-xs">{{ Str::limit($service->description, 80) }}</p>
                            @endif
                        </div>

                        <!-- Service Details -->
                        <div class="p-4 space-y-3">
                            <div class="grid grid-cols-2 gap-3 text-xs">
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
                                @php
                                    $fees = $service->fees ?? [];
                                    $regularFee = null;
                                    $otherFees = [];
                                    
                                    foreach ($fees as $type => $feeData) {
                                        if (is_array($feeData) && isset($feeData['amount'])) {
                                            if (strtolower($type) === 'regular') {
                                                $regularFee = $feeData;
                                            } else {
                                                $otherFees[$type] = $feeData;
                                            }
                                        } else {
                                            if (strtolower($type) === 'regular') {
                                                $regularFee = ['amount' => $feeData, 'description' => 'Regular'];
                                            } else {
                                                $otherFees[$type] = ['amount' => $feeData, 'description' => ucfirst($type)];
                                            }
                                        }
                                    }
                                @endphp
                                
                                @if($regularFee)
                                    <p class="text-gray-900 text-xs">{{ $regularFee['description'] }}: ₱{{ number_format($regularFee['amount'], 2) }}</p>
                                @endif
                                
                                @foreach($otherFees as $type => $feeData)
                                    <p class="text-gray-900 text-xs mt-1">{{ $feeData['description'] }}: ₱{{ number_format($feeData['amount'], 2) }}</p>
                                @endforeach
                                
                                @if(empty($fees))
                                    <p class="text-gray-600 text-xs">Contact office for pricing</p>
                                @endif
                            </div>

                            <!-- Schedule Preview -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Schedule</label>
                                @if($service->schedules && count($service->schedules) > 0)
                                    <div class="space-y-1">
                                        @foreach(array_slice($service->schedules, 0, 3) as $day => $times)
                                            <div class="flex justify-between text-xs">
                                                <span class="text-gray-600 capitalize">{{ $day }}</span>
                                                <span class="text-gray-900">{{ implode(', ', $times) }}</span>
                                            </div>
                                        @endforeach
                                        @if(count($service->schedules) > 3)
                                            <div class="text-xs text-gray-500 italic">+{{ count($service->schedules) - 3 }} more days</div>
                                        @endif
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
                                    @foreach(array_slice($service->requirements, 0, 2) as $requirement)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#0d5c2f]/10 text-[#0d5c2f]">
                                            {{ $requirement }}
                                        </span>
                                    @endforeach
                                    @if(count($service->requirements) > 2)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            +{{ count($service->requirements) - 2 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 mt-auto">
                            <div class="flex items-center justify-between">
                                <a href="{{ isset($isStaff) && $isStaff ? route('staff.services.show', $service) : route('admin.services.show', $service) }}" 
                                   class="w-7 h-7 rounded-lg bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-all duration-200 hover:scale-110" title="View">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                @if(!isset($isStaff) || !$isStaff)
                                <a href="{{ route('admin.services.edit', $service) }}" 
                                   class="w-7 h-7 rounded-lg bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-600 hover:text-indigo-800 transition-all duration-200 hover:scale-110" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <button type="button" onclick="openModal('toggle-status-modal-{{ $service->id }}')"
                                        class="w-7 h-7 rounded-lg bg-{{ $service->is_active ? 'yellow' : 'green' }}-100 hover:bg-{{ $service->is_active ? 'yellow' : 'green' }}-200 flex items-center justify-center text-{{ $service->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $service->is_active ? 'yellow' : 'green' }}-800 transition-all duration-200 hover:scale-110" 
                                        title="{{ $service->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-{{ $service->is_active ? 'pause' : 'play' }} text-xs"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 animate-fadeIn">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gradient-to-br from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center shadow-sm">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No services found</h3>
                    <p class="text-gray-600 text-sm">No services have been configured yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals for each service -->
@foreach($services as $service)
    <!-- Toggle Status Modal -->
    <x-modal 
        id="toggle-status-modal-{{ $service->id }}"
        title="{{ $service->is_active ? 'Deactivate' : 'Activate' }} Service"
        message="Are you sure you want to {{ $service->is_active ? 'deactivate' : 'activate' }} the service '{{ $service->name }}'? {{ $service->is_active ? 'This will make the service unavailable for bookings.' : 'This will make the service available for bookings.' }}"
        confirmText="{{ $service->is_active ? 'Deactivate' : 'Activate' }}"
        confirmClass="{{ $service->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}">
        @if(!isset($isStaff) || !$isStaff)
        <form action="{{ route('admin.services.toggle-status', $service) }}" method="POST">
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
        
        // Function to toggle views with animation
        function showTableView() {
            if (window.innerWidth >= 768) { // md breakpoint
                cardView.style.opacity = '0';
                cardView.style.transform = 'translateY(10px)';
                
                setTimeout(() => {
                    cardView.classList.add('hidden');
                    tableView.classList.remove('hidden');
                    
                    // Trigger animation
                    tableView.style.opacity = '0';
                    tableView.style.transform = 'translateY(10px)';
                    
                    requestAnimationFrame(() => {
                        tableView.style.transition = 'all 0.3s ease-out';
                        tableView.style.opacity = '1';
                        tableView.style.transform = 'translateY(0)';
                    });
                }, 150);
                
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
                tableView.style.opacity = '0';
                tableView.style.transform = 'translateY(10px)';
                
                setTimeout(() => {
                    tableView.classList.add('hidden');
                    cardView.classList.remove('hidden');
                    
                    // Trigger animation
                    cardView.style.opacity = '0';
                    cardView.style.transform = 'translateY(10px)';
                    
                    requestAnimationFrame(() => {
                        cardView.style.transition = 'all 0.3s ease-out';
                        cardView.style.opacity = '1';
                        cardView.style.transform = 'translateY(0)';
                    });
                }, 150);
                
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