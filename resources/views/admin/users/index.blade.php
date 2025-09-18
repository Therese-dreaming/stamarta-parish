@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
@include('components.toast')
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-6 relative">
            <div class="absolute right-0 top-0 w-20 h-20 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-2xl font-bold text-white">User Management</h1>
                    <p class="text-white/80 mt-1 text-sm">Manage parish users and their roles</p>
                </div>
                <a href="{{ route('admin.users.create') }}" 
                   class="group px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow">
                    <i class="fas fa-plus mr-2 text-sm group-hover:rotate-90 transition-transform duration-300"></i>
                    <span>Create User</span>
                </a>
            </div>
        </div>
    </div>

    <!-- View Toggle (Full width tab style) -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="flex border-b border-gray-200">
            <button id="table-view-btn" class="flex-1 px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 border-b-2 border-transparent">
                <i class="fas fa-table mr-2"></i> Table View
            </button>
            <button id="card-view-btn" class="flex-1 px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 border-b-2 border-transparent">
                <i class="fas fa-th-large mr-2"></i> Cards View
            </button>
        </div>
        
        <div class="p-4">
            @if($users->count() > 0)
                <!-- Table View -->
                <div id="table-view" class="overflow-x-auto hidden animate-fadeIn">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 animate-slideInUp" style="animation-delay: {{ $loop->index * 50 }}ms">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-[#0d5c2f] flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                            <i class="fas fa-user text-white text-sm"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $roleColors = [
                                            'admin' => 'bg-red-100 text-red-800',
                                            'priest' => 'bg-purple-100 text-purple-800',
                                            'ministry_head' => 'bg-orange-100 text-orange-800',
                                            'staff' => 'bg-blue-100 text-blue-800',
                                            'user' => 'bg-green-100 text-green-800'
                                        ];
                                        $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                                        
                                        $roleIcons = [
                                            'admin' => 'crown',
                                            'priest' => 'cross',
                                            'ministry_head' => 'users-cog',
                                            'staff' => 'user-tie',
                                            'user' => 'user'
                                        ];
                                        $roleIcon = $roleIcons[$user->role] ?? 'user';
                                        
                                        $roleDisplayName = $user->role === 'ministry_head' ? 'Ministry Head' : ucfirst($user->role);
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $roleColor }} transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-{{ $roleIcon }} mr-1"></i>
                                        {{ $roleDisplayName }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($user->email_verified_at)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-check-circle mr-1"></i>Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.users.show', $user) }}" class="w-7 h-7 rounded-lg bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-all duration-200 hover:scale-110" title="View">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="w-7 h-7 rounded-lg bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-600 hover:text-indigo-800 transition-all duration-200 hover:scale-110" title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        @if($user->id !== auth()->id() && $user->role === 'user' && !$user->isMinistryHead())
                                            <button type="button" onclick="openModal('promote-modal-{{ $user->id }}')" class="w-7 h-7 rounded-lg bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-all duration-200 hover:scale-110" title="Promote to Ministry Head">
                                                <i class="fas fa-arrow-up text-xs"></i>
                                            </button>
                                        @endif
                                        @if($user->id !== auth()->id())
                                            <button type="button" onclick="openModal('delete-modal-{{ $user->id }}')" class="w-7 h-7 rounded-lg bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-all duration-200 hover:scale-110" title="Delete">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        @else
                                            <span class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400" title="Cannot modify your own account">
                                                <i class="fas fa-lock text-xs"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Cards View -->
                <div id="card-view" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 hidden animate-fadeIn">
                    @foreach($users as $user)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 animate-slideInUp hover:-translate-y-1" style="animation-delay: {{ $loop->index * 100 }}ms">
                        <div class="p-4">
                            <div class="flex items-center mb-3">
                                <div class="h-10 w-10 rounded-full bg-[#0d5c2f] flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-base font-medium text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Role:</span>
                                    @php
                                        $roleColors = [
                                            'admin' => 'bg-red-100 text-red-800',
                                            'priest' => 'bg-purple-100 text-purple-800',
                                            'ministry_head' => 'bg-orange-100 text-orange-800',
                                            'staff' => 'bg-blue-100 text-blue-800',
                                            'user' => 'bg-green-100 text-green-800'
                                        ];
                                        $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                                        
                                        $roleIcons = [
                                            'admin' => 'crown',
                                            'priest' => 'cross',
                                            'ministry_head' => 'users-cog',
                                            'staff' => 'user-tie',
                                            'user' => 'user'
                                        ];
                                        $roleIcon = $roleIcons[$user->role] ?? 'user';
                                        
                                        $roleDisplayName = $user->role === 'ministry_head' ? 'Ministry Head' : ucfirst($user->role);
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $roleColor }} transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-{{ $roleIcon }} mr-1"></i>
                                        {{ $roleDisplayName }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Status:</span>
                                    @if($user->email_verified_at)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-check-circle mr-1"></i>Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Joined:</span>
                                    <span class="text-xs text-gray-900">{{ $user->created_at->format('M j, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 flex justify-end space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="w-7 h-7 rounded-lg bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-all duration-200 hover:scale-110" title="View">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="w-7 h-7 rounded-lg bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-600 hover:text-indigo-800 transition-all duration-200 hover:scale-110" title="Edit">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            @if($user->id !== auth()->id() && $user->role === 'user' && !$user->isMinistryHead())
                                <button type="button" onclick="openModal('promote-modal-{{ $user->id }}')" class="w-7 h-7 rounded-lg bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-all duration-200 hover:scale-110" title="Promote to Ministry Head">
                                    <i class="fas fa-arrow-up text-xs"></i>
                                </button>
                            @endif
                            @if($user->id !== auth()->id())
                                <button type="button" onclick="openModal('delete-modal-{{ $user->id }}')" class="w-7 h-7 rounded-lg bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-all duration-200 hover:scale-110" title="Delete">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            @else
                                <span class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400" title="Cannot modify your own account">
                                    <i class="fas fa-lock text-xs"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-8 animate-fadeIn">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gradient-to-br from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center shadow-sm">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                    <p class="text-gray-600 text-sm">No users have been registered yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals for each user -->
@foreach($users as $user)
    @if($user->id !== auth()->id())
        <!-- Delete Modal -->
        <x-modal 
            id="delete-modal-{{ $user->id }}"
            title="Delete User"
            message="Are you sure you want to delete {{ $user->name }}? This action cannot be undone and will permanently remove all associated data including bookings and account information."
            confirmText="Delete User"
            confirmClass="bg-red-600 hover:bg-red-700">
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                @csrf
                @method('DELETE')
            </form>
        </x-modal>
        
        @if($user->role === 'user' && !$user->isMinistryHead())
            <!-- Promote to Ministry Head Modal -->
            <div id="promote-modal-{{ $user->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                    <i class="fas fa-arrow-up text-green-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Promote to Ministry Head</h3>
                                    <p class="text-sm text-gray-500">{{ $user->name }}</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeModal('promote-modal-{{ $user->id }}')" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <form action="{{ route('admin.users.promote-ministry-head', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            <div class="mb-4">
                                <label for="ministry-{{ $user->id }}" class="block text-sm font-medium text-gray-700 mb-2">Select Ministry</label>
                                <select id="ministry-{{ $user->id }}" name="ministry_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">
                                    <option value="">Choose a ministry...</option>
                                    @if(isset($ministries))
                                        @foreach($ministries as $ministry)
                                            <option value="{{ $ministry->id }}">{{ $ministry->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="notes-{{ $user->id }}" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                                <textarea id="notes-{{ $user->id }}" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm" placeholder="Add any notes about this promotion..."></textarea>
                            </div>
                            
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 mb-4">
                                <div class="flex">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
                                    <div class="text-sm text-yellow-800">
                                        <p class="font-medium mb-1">This action will:</p>
                                        <ul class="list-disc list-inside space-y-1 text-xs">
                                            <li>Change {{ $user->name }}'s role to Ministry Head</li>
                                            <li>Assign them to the selected ministry</li>
                                            <li>Grant ministry management permissions</li>
                                            <li>Send a notification email to the user</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeModal('promote-modal-{{ $user->id }}')" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors text-sm">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm">
                                    <i class="fas fa-arrow-up mr-2"></i>Promote User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endforeach

<script>
    // Modal functions
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.opacity = '0';
            modal.style.transform = 'scale(0.95)';
            
            requestAnimationFrame(() => {
                modal.style.transition = 'all 0.3s ease-out';
                modal.style.opacity = '1';
                modal.style.transform = 'scale(1)';
            });
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.opacity = '0';
            modal.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }
    }
    
    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('bg-gray-600') && e.target.classList.contains('bg-opacity-50')) {
            const modalId = e.target.id;
            if (modalId) {
                closeModal(modalId);
            }
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('[id*="modal"]:not(.hidden)');
            openModals.forEach(modal => {
                closeModal(modal.id);
            });
        }
    });
    
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
                localStorage.setItem('userViewPreference', 'table');
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
                localStorage.setItem('userViewPreference', 'card');
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
        const savedPreference = localStorage.getItem('userViewPreference');
        
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
                const currentPreference = localStorage.getItem('userViewPreference');
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