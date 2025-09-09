<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Priest Dashboard</title>

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
<body class="bg-gray-50 font-['Poppins'] min-h-full flex flex-col">
    <div class="min-h-screen">
        <!-- Sidebar -->
                    <div class="fixed inset-y-0 left-0 z-50 w-60 bg-white shadow-lg transform transition-transform duration-300 ease-in-out" id="sidebar">
            <div class="flex items-center justify-between h-14 px-5 border-b border-gray-200">
                <div class="flex items-center">
                    <img src="{{ asset('images/church-logo.png') }}" alt="Logo" class="h-8 w-8">
                    <h1 class="ml-3 text-l font-bold text-[#0d5c2f]">Priest Dashboard</h1>
                </div>
                <button id="closeSidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="mt-4 px-3">
                <div class="space-y-1.5 text-sm">
                    <a href="{{ route('priest.dashboard') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('priest.dashboard') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>
                    
                    <!-- Booking Management -->
                    <div class="pt-4">
                        <h3 class="px-4 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Booking Management</h3>
                    </div>
                    
                    <a href="{{ route('priest.bookings.index') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('priest.bookings.index') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <i class="fas fa-calendar-check w-5 h-5 mr-3"></i>
                        My Bookings
                    </a>
                    
                    <a href="{{ route('priest.bookings.calendar') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('priest.bookings.calendar') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                        Calendar View
                    </a>

                    <!-- Profile & Leave Management -->
                    <div class="pt-4">
                        <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Account</h3>
                    </div>
                    
                    <a href="{{ route('priest.profile.edit') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('priest.profile.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <i class="fas fa-user-edit w-5 h-5 mr-3"></i>
                        Edit Profile
                    </a>
                    
                    <a href="{{ route('priest.leave.create') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('priest.leave.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <i class="fas fa-calendar-times w-5 h-5 mr-3"></i>
                        File Leave
                    </a>
                    
                    <a href="{{ route('priest.notifications.index') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('priest.notifications.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <i class="fas fa-bell w-5 h-5 mr-3"></i>
                        Notifications
                        <span id="notification-count" class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 hidden" data-notification-count="0">0</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="lg:ml-60">
            <!-- Top Navigation -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between h-14 px-5">
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
                                                <p class="text-xs text-gray-500">Stay updated on your assignments</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('priest.notifications.index') }}" class="text-xs text-[#0d5c2f] hover:text-[#0d5c2f]/80 font-medium">View All</a>
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
                                    <div class="text-xs text-[#0d5c2f] font-medium">Priest</div>
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
            <main class="p-5">
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
            fetch('{{ route("priest.notifications.unread-count") }}')
            .then(response => response.json())
            .then(data => {
                const countElement = document.getElementById('notification-count');
                const headerCount = document.getElementById('header-notification-count');
                
                if (countElement) {
                    countElement.textContent = data.count;
                    if (data.count > 0) {
                        countElement.classList.remove('hidden');
                    } else {
                        countElement.classList.add('hidden');
                    }
                }
                
                if (headerCount) {
                    if (data.count > 0) {
                        headerCount.textContent = data.count;
                        headerCount.classList.remove('hidden');
                    } else {
                        headerCount.classList.add('hidden');
                    }
                }
            })
            .catch(error => console.error('Error fetching notification count:', error));
        }

        // Load header notifications
        function loadHeaderNotifications() {
            fetch('{{ route("priest.notifications.unread-count") }}?limit=5')
            .then(response => response.json())
            .then(data => {
                const notificationsList = document.getElementById('header-notifications-list');
                const headerCount = document.getElementById('header-notification-count');
                
                // Update header count
                if (data.count > 0) {
                    headerCount.textContent = data.count;
                    headerCount.classList.remove('hidden');
                } else {
                    headerCount.classList.add('hidden');
                }
                
                // Load recent notifications
                if (data.notifications && data.notifications.length > 0) {
                    let html = '';
                    data.notifications.forEach(notification => {
                        const borderColor = notification.is_read ? 'border-gray-100' : 'border-[#0d5c2f]';
                        let icon = 'fas fa-bell';
                        let iconBg = 'bg-blue-100';
                        let iconColor = 'text-blue-600';
                        const msg = (notification.message || '').toLowerCase();
                        if (msg.includes('approved')) { icon = 'fas fa-check-circle'; iconBg = 'bg-green-100'; iconColor = 'text-green-600'; }
                        else if (msg.includes('rejected')) { icon = 'fas fa-times-circle'; iconBg = 'bg-red-100'; iconColor = 'text-red-600'; }
                        else if (msg.includes('assigned') || msg.includes('booking')) { icon = 'fas fa-calendar-check'; iconBg = 'bg-purple-100'; iconColor = 'text-purple-600'; }

                        html += `
                            <div class="px-6 py-4 hover:bg-gray-50 transition-colors cursor-pointer notification-item-header border-l-4 ${borderColor} ${!notification.is_read ? 'bg-blue-50/50' : ''}" data-id="${notification.id}" data-read="${notification.is_read}">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 ${iconBg} rounded-full flex items-center justify-center">
                                            <i class="${icon} ${iconColor} text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900 font-medium leading-5 mb-1">${notification.message}</p>
                                                <div class="flex items-center space-x-2">
                                                    <p class="text-xs text-gray-500">${notification.created_at}</p>
                                                    ${!notification.is_read ? '<span class="inline-block w-2 h-2 bg-[#0d5c2f] rounded-full"></span>' : ''}
                                                </div>
                                            </div>
                                            ${!notification.is_read ? '<button class="mark-read-header-btn text-xs text-[#0d5c2f] hover:text-[#0d5c2f]/80 font-medium flex-shrink-0 ml-3">Mark read</button>' : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    notificationsList.innerHTML = html;
                    
                    // Add event listeners for mark as read buttons
                    document.querySelectorAll('.mark-read-header-btn').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const notificationItem = this.closest('.notification-item-header');
                            const notificationId = notificationItem.dataset.id;
                            
                            fetch('{{ route("priest.notifications.mark-as-read") }}', {
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
                                    notificationItem.classList.remove('border-[#0d5c2f]');
                                    notificationItem.classList.add('border-gray-300');
                                    notificationItem.dataset.read = 'true';
                                    this.remove();
                                    updateNotificationCount();
                                    loadHeaderNotifications();
                                }
                            });
                        });
                    });
                } else {
                    notificationsList.innerHTML = `
                        <div class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 font-medium mb-1">No new notifications</p>
                            <p class="text-xs text-gray-400">You're all caught up!</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading header notifications:', error);
                document.getElementById('header-notifications-list').innerHTML = '<div class="px-4 py-3 text-center text-red-500 text-sm">Error loading notifications</div>';
            });
        }

        // Mark all as read from header
        document.getElementById('mark-all-read-header').addEventListener('click', function() {
            fetch('{{ route("priest.notifications.mark-all-as-read") }}', {
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

        // Update count on page load and every 30 seconds
        updateNotificationCount();
        loadHeaderNotifications();
        setInterval(updateNotificationCount, 30000);
        setInterval(loadHeaderNotifications, 30000);
    </script>
</body>
</html> 