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
                                            'staff' => 'bg-blue-100 text-blue-800',
                                            'user' => 'bg-green-100 text-green-800'
                                        ];
                                        $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $roleColor }} transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-{{ $user->role === 'admin' ? 'crown' : ($user->role === 'priest' ? 'cross' : ($user->role === 'staff' ? 'user-tie' : 'user')) }} mr-1"></i>
                                        {{ ucfirst($user->role) }}
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
                                            'staff' => 'bg-blue-100 text-blue-800',
                                            'user' => 'bg-green-100 text-green-800'
                                        ];
                                        $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $roleColor }} transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-{{ $user->role === 'admin' ? 'crown' : ($user->role === 'priest' ? 'cross' : ($user->role === 'staff' ? 'user-tie' : 'user')) }} mr-1"></i>
                                        {{ ucfirst($user->role) }}
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
    @endif
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