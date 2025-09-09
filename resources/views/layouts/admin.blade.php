<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Parish CMS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.2/css/all.css">

    <!-- Scripts -->
    @vite('resources/css/app.css')
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 font-['Poppins'] min-h-full flex flex-col text-sm">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out overflow-y-auto pb-4" id="sidebar">
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                <div class="flex items-center">
                    <img src="{{ asset('images/church-logo.png') }}" alt="Logo" class="h-8 w-8">
                    <h1 class="ml-3 text-lg font-bold text-[#0d5c2f]">Parish CMS</h1>
                </div>
                <button id="closeSidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="mt-6 px-4">
                <div class="space-y-2">
                    <!-- Admin Actions Summary -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                <span class="text-sm font-medium text-red-800">Admin Actions</span>
                            </div>
                            <span id="total-admin-actions" class="bg-red-500 text-white text-xs rounded-full px-2 py-1 font-bold">0</span>
                        </div>
                        <div class="mt-2 text-xs text-red-600">
                            <div id="admin-actions-breakdown" class="space-y-1">
                                <!-- Breakdown will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <i class="fas fa-tachometer-alt w-4 h-4 mr-2"></i>
                        Dashboard
                    </a>
                    
                    <div class="pt-4">
                        <h3 class="px-4 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Content Management</h3>
                    </div>
                    
                    <a href="{{ route('admin.cms.pages.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.cms.pages.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <i class="fas fa-file-alt w-4 h-4 mr-2"></i>
                        Pages
                    </a>
                    
                    <a href="{{ route('admin.cms.media.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.cms.media.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <i class="fas fa-images w-4 h-4 mr-2"></i>
                        Media Library
                    </a>
                    
                    <a href="{{ route('admin.faqs.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.faqs.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        FAQ Management
                    </a>
                </div>

                <div class="pt-4">
                    <h3 class="px-4 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Financial Management</h3>
                </div>
                
                <a href="{{ route('admin.budget-management.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.budget-management.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-chart-line w-4 h-4 mr-2"></i>
                    Budget Management
                    <span id="pending-budget-requests-count" class="ml-auto bg-red-500 text-white text-[10px] rounded-full px-1.5 py-0.5 hidden" data-count="0">0</span>
                </a>
                <a href="{{ route('admin.manual-cash-inflows.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.manual-cash-inflows.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-money-bill-wave w-4 h-4 mr-2"></i>
                    Manual Cash Inflows
                    <span id="pending-cash-inflows-count" class="ml-auto bg-red-500 text-white text-[10px] rounded-full px-1.5 py-0.5 hidden" data-count="0">0</span>
                </a>

                <!-- Priest Management -->
                <div class="pt-4">
                    <h3 class="px-4 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Priest Management</h3>
                </div>
                
                <a href="{{ route('admin.priests.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.priests.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-cross w-4 h-4 mr-2"></i>
                    Priests
                    <span id="pending-priest-leaves-count" class="ml-auto bg-red-500 text-white text-[10px] rounded-full px-1.5 py-0.5 hidden" data-count="0">0</span>
                </a>

                <!-- User Management -->
                <div class="pt-4">
                    <h3 class="px-4 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">User Management</h3>
                </div>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-users w-4 h-4 mr-2"></i>
                    Users
                    <span id="pending-users-count" class="ml-auto bg-red-500 text-white text-[10px] rounded-full px-1.5 py-0.5 hidden" data-count="0">0</span>
                </a>

                <!-- Ministries -->
                <div class="pt-4">
                    <h3 class="px-4 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Ministries</h3>
                </div>
                <a href="{{ route('admin.ministries.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.ministries.index') || request()->routeIs('admin.ministries.create') || request()->routeIs('admin.ministries.edit') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-hand-holding-heart w-4 h-4 mr-2"></i>
                    Ministries
                </a>
                <a href="{{ route('admin.ministries.ministry-activities.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.ministries.ministry-activities.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-file-invoice-dollar w-4 h-4 mr-2"></i>
                    Ministry Activities
                </a>

                <!-- Service Management -->
                <div class="pt-4">
                    <h3 class="px-4 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Service Management</h3>
                </div>
                
                <a href="{{ route('admin.services.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.services.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-calendar-alt w-4 h-4 mr-2"></i>
                    Services
                </a>

                <!-- Booking Management -->
                <div class="pt-4">
                    <h3 class="px-4 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Booking Management</h3>
                </div>
                <div class="relative group">
                    <button class="w-full flex items-center justify-between px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.bookings.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-bookmark w-4 h-4 mr-2"></i>
                            Bookings
                            <span id="pending-bookings-count" class="ml-2 bg-red-500 text-white text-[10px] rounded-full px-1.5 py-0.5 hidden" data-count="0">0</span>
                        </div>
                        <i class="fas fa-chevron-down text-[10px] transition-transform group-hover:rotate-180"></i>
                    </button>
                    <div class="absolute left-0 right-0 top-full bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <a href="{{ route('admin.bookings.index') }}" class="block px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-list mr-2"></i>
                            View Bookings
                        </a>
                        <a href="{{ route('admin.bookings.calendar') }}" class="block px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Calendar
                        </a>
                        <a href="{{ route('admin.bookings.index') }}?status=payment_hold" class="block px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-credit-card mr-2"></i>
                            Payment Verification
                            <span id="payment-verification-count" class="ml-auto bg-red-500 text-white text-[8px] rounded-full px-1 py-0.5 hidden" data-count="0">0</span>
                        </a>
                    </div>
                </div>

                <!-- Parochial Activities -->
                <div class="pt-4">
                    <h3 class="px-4 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Parochial Activities</h3>
                </div>
                
                <a href="{{ route('admin.parochial-activities.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.parochial-activities.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-church w-4 h-4 mr-2"></i>
                    Activities
                    <span id="pending-activities-count" class="ml-auto bg-red-500 text-white text-[10px] rounded-full px-1.5 py-0.5 hidden" data-count="0">0</span>
                </a>

                <!-- Notifications -->
                <div class="pt-4">
                    <h3 class="px-4 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Notifications</h3>
                </div>
                
                <a href="{{ route('admin.admin.notifications.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.admin.notifications.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-bell w-4 h-4 mr-2"></i>
                    Notifications
                    <span id="notification-count" class="ml-auto bg-red-500 text-white text-[10px] rounded-full px-1.5 py-0.5 hidden" data-notification-count="0">0</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="lg:ml-64 h-screen flex flex-col">
            <!-- Top Navigation -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-6">
                    <div class="flex items-center">
                        <button id="openSidebar" class="lg:hidden text-gray-500 hover:text-gray-700 mr-4">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h2 class="text-lg font-semibold text-gray-900">@yield('title')</h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notification Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="relative flex items-center text-gray-600 hover:text-[#0d5c2f] transition-colors p-2 rounded-lg hover:bg-gray-100">
                                <i class="fas fa-bell text-xl mr-0"></i>
                                <span id="header-notification-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-2 py-1 hidden" data-notification-count="0">0</span>
                            </button>
                            
                            <!-- Notification Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                                 x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                                 class="absolute right-0 mt-3 w-[420px] bg-white rounded-xl shadow-2xl border border-gray-200 z-50">
                                
                                <!-- Header -->
                                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-[#0d5c2f] rounded-lg flex items-center justify-center">
                                                <i class="fas fa-bell text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                                <p class="text-xs text-gray-500">Monitor user activities and system events</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.admin.notifications.index') }}" class="text-xs text-[#0d5c2f] hover:text-[#0d5c2f]/80 font-medium">View All</a>
                                    </div>
                                </div>
                                
                                <!-- Notifications List -->
                                <div id="header-notifications-list" class="max-h-80 overflow-y-auto">
                                    <div class="px-6 py-8 text-center">
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-spinner fa-spin text-gray-400"></i>
                                        </div>
                                        <p class="text-sm text-gray-500">Loading notifications...</p>
                                    </div>
                                </div>
                                
                                <!-- Footer -->
                                <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                                    <button id="mark-all-read-header" class="w-full text-center text-xs text-[#0d5c2f] hover:text-[#0d5c2f]/80 font-medium py-2 rounded-lg hover:bg-white transition-colors">
                                        <i class="fas fa-check-double mr-2"></i>Mark all as read
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-[#0d5c2f] transition-colors">
                            <i class="fas fa-home mr-2"></i>View Site
                        </a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-600 hover:text-[#0d5c2f] transition-colors">
                                <i class="fas fa-user-circle text-xl mr-2"></i>
                                <span>{{ Auth::user()->name ?? 'User' }}</span>
                                <i class="fas fa-chevron-down ml-2 text-sm"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                
                                <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                                    <div class="font-medium truncate">{{ Auth::user()->name }}</div>
                                    <div class="text-gray-500 text-xs truncate" title="{{ Auth::user()->email }}">{{ Auth::user()->email }}</div>
                                </div>
                                
                                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-home mr-2"></i>View Site
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-6 flex-1 overflow-y-auto">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    @stack('scripts')
    
    <!-- Toast Notifications -->
    <x-toast />
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('openSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.remove('hidden');
        });

        document.getElementById('closeSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.add('hidden');
        });

        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.add('hidden');
        });

        // Auto-hide success/error messages after 5 seconds
        setTimeout(function() {
            const messages = document.querySelectorAll('.bg-green-100.border-green-400, .bg-red-100.border-red-400');
            messages.forEach(function(message) {
                message.style.display = 'none';
            });
        }, 5000);

        // Update notification count
        function updateNotificationCount() {
            fetch('{{ route("admin.admin.notifications.unread-count") }}')
            .then(response => response.json())
            .then(data => {
                const countElement = document.getElementById('notification-count');
                const headerCountElement = document.getElementById('header-notification-count');
                
                if (data.count > 0) {
                    countElement.textContent = data.count;
                    countElement.classList.remove('hidden');
                    headerCountElement.textContent = data.count;
                    headerCountElement.classList.remove('hidden');
                } else {
                    countElement.classList.add('hidden');
                    headerCountElement.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error fetching notification count:', error);
            });
        }

        // Load header notifications
        function loadHeaderNotifications() {
            fetch('{{ route("admin.admin.notifications.unread-count") }}?limit=5')
            .then(response => response.json())
            .then(data => {
                const notificationsList = document.getElementById('header-notifications-list');
                
                if (data.notifications && data.notifications.length > 0) {
                    notificationsList.innerHTML = data.notifications.map(notification => `
                        <div class="px-6 py-3 hover:bg-gray-50 transition-colors notification-item-header ${!notification.is_read ? 'bg-blue-50/30 border-l-4 border-[#0d5c2f]' : ''}" data-id="${notification.id}">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-bell text-gray-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 font-medium leading-4 mb-1">${notification.message}</p>
                                    <p class="text-xs text-gray-500">${notification.created_at}</p>
                                    ${notification.booking ? `<p class="text-xs text-[#0d5c2f] mt-1">Booking #${notification.booking.id}</p>` : ''}
                                </div>
                                ${!notification.is_read ? `
                                    <button class="mark-read-btn text-xs text-[#0d5c2f] hover:text-[#0d5c2f]/80 font-medium" data-id="${notification.id}">
                                        Mark read
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    `).join('');
                    
                    // Add event listeners for mark as read buttons
                    document.querySelectorAll('.mark-read-btn').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const notificationItem = this.closest('.notification-item-header');
                            const notificationId = notificationItem.dataset.id;
                            
                            fetch('{{ route("admin.admin.notifications.mark-as-read") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    notification_ids: [notificationId]
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    updateNotificationCount();
                                    loadHeaderNotifications();
                                }
                            });
                        });
                    });
                } else {
                    notificationsList.innerHTML = `
                        <div class="px-6 py-8 text-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-bell-slash text-gray-400"></i>
                            </div>
                            <p class="text-sm text-gray-500">No notifications</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                document.getElementById('header-notifications-list').innerHTML = `
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-red-500">Error loading notifications</p>
                    </div>
                `;
            });
        }

        // Update admin action counts
        function updateAdminActionCounts() {
            fetch('{{ route("admin.admin-action-counts") }}')
            .then(response => response.json())
            .then(data => {
                if (data.has_actions) {
                    // Update total count
                    const totalElement = document.getElementById('total-admin-actions');
                    if (totalElement) {
                        totalElement.textContent = data.formatted_total;
                    }

                    // Update individual navigation counts
                    updateNavigationCount('pending-bookings-count', data.counts.pending_bookings + data.counts.acknowledged_bookings);
                    updateNavigationCount('payment-verification-count', data.counts.payment_verification);
                    updateNavigationCount('pending-cash-inflows-count', data.counts.pending_cash_inflows);
                    updateNavigationCount('pending-budget-requests-count', data.counts.pending_budget_requests);
                    updateNavigationCount('pending-activities-count', data.counts.pending_activities);
                    updateNavigationCount('pending-users-count', data.counts.pending_users);
                    updateNavigationCount('pending-priest-leaves-count', 0); // Placeholder for future implementation

                    // Update breakdown
                    const breakdownElement = document.getElementById('admin-actions-breakdown');
                    if (breakdownElement) {
                        let breakdownHtml = '';
                        if (data.counts.pending_bookings > 0) {
                            breakdownHtml += `<div>• ${data.counts.pending_bookings} pending bookings</div>`;
                        }
                        if (data.counts.acknowledged_bookings > 0) {
                            breakdownHtml += `<div>• ${data.counts.acknowledged_bookings} acknowledged bookings</div>`;
                        }
                        if (data.counts.payment_verification > 0) {
                            breakdownHtml += `<div>• ${data.counts.payment_verification} payments to verify</div>`;
                        }
                        if (data.counts.pending_cash_inflows > 0) {
                            breakdownHtml += `<div>• ${data.counts.pending_cash_inflows} cash inflows pending</div>`;
                        }
                        if (data.counts.pending_budget_requests > 0) {
                            breakdownHtml += `<div>• ${data.counts.pending_budget_requests} budget requests pending</div>`;
                        }
                        if (data.counts.pending_activities > 0) {
                            breakdownHtml += `<div>• ${data.counts.pending_activities} activities need review</div>`;
                        }
                        if (data.counts.pending_users > 0) {
                            breakdownHtml += `<div>• ${data.counts.pending_users} new users</div>`;
                        }
                        breakdownElement.innerHTML = breakdownHtml;
                    }
                } else {
                    // Hide all count elements if no actions needed
                    updateNavigationCount('pending-bookings-count', 0);
                    updateNavigationCount('payment-verification-count', 0);
                    updateNavigationCount('pending-cash-inflows-count', 0);
                    updateNavigationCount('pending-budget-requests-count', 0);
                    updateNavigationCount('pending-activities-count', 0);
                    updateNavigationCount('pending-users-count', 0);
                    updateNavigationCount('pending-priest-leaves-count', 0);
                    
                    const breakdownElement = document.getElementById('admin-actions-breakdown');
                    if (breakdownElement) {
                        breakdownElement.innerHTML = '<div class="text-green-600">All caught up! No pending actions.</div>';
                    }
                }
            })
            .catch(error => console.error('Error fetching admin action counts:', error));
        }

        // Helper function to update individual navigation counts
        function updateNavigationCount(elementId, count) {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = count;
                element.dataset.count = count;
                if (count > 0) {
                    element.classList.remove('hidden');
                } else {
                    element.classList.add('hidden');
                }
            }
        }


        // Mark all as read from header
        document.getElementById('mark-all-read-header').addEventListener('click', function() {
            fetch('{{ route("admin.admin.notifications.mark-all-as-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateNotificationCount();
                    loadHeaderNotifications();
                }
            });
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateNotificationCount();
            loadHeaderNotifications();
        });

        // Update notification count every 30 seconds
        setInterval(updateNotificationCount, 30000);
        
        // Update admin action counts on page load and every 30 seconds
        updateAdminActionCounts();
        setInterval(updateAdminActionCounts, 30000);
    </script>
</body>
</html> 
