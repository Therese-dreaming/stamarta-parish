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
<body class="bg-gray-50 font-['Poppins'] min-h-full flex flex-col">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out" id="sidebar">
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
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>
                    
                    <div class="pt-4">
                        <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Content Management</h3>
                    </div>
                    
                    <a href="{{ route('admin.cms.pages.index') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.cms.pages.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <i class="fas fa-file-alt w-5 h-5 mr-3"></i>
                        Pages
                    </a>
                    
                    <a href="{{ route('admin.cms.media.index') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.cms.media.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <i class="fas fa-images w-5 h-5 mr-3"></i>
                        Media Library
                    </a>
                </div>

                <!-- Priest Management -->
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Priest Management</h3>
                </div>
                
                <a href="{{ route('admin.priests.index') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.priests.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-cross w-5 h-5 mr-3"></i>
                    Priests
                </a>

                <!-- User Management -->
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">User Management</h3>
                </div>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                    Users
                </a>

                <!-- Service Management -->
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Service Management</h3>
                </div>
                
                <a href="{{ route('admin.services.index') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.services.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                    Services
                </a>

                <!-- Booking Management -->
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Booking Management</h3>
                </div>
                <div class="relative group">
                    <button class="w-full flex items-center justify-between px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.bookings.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-bookmark w-5 h-5 mr-3"></i>
                            Bookings
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                    </button>
                    <div class="absolute left-0 right-0 top-full bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <a href="{{ route('admin.bookings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-list mr-2"></i>
                            View Bookings
                        </a>
                        <a href="{{ route('admin.bookings.calendar') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Calendar
                        </a>
                    </div>
                </div>

                <!-- Parochial Activities -->
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Parochial Activities</h3>
                </div>
                
                <a href="{{ route('admin.parochial-activities.index') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.parochial-activities.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-church w-5 h-5 mr-3"></i>
                    Activities
                </a>

                <!-- Notifications -->
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Notifications</h3>
                </div>
                
                <a href="{{ route('admin.notifications.index') }}" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.notifications.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-bell w-5 h-5 mr-3"></i>
                    Notifications
                    <span id="notification-count" class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 hidden" data-notification-count="0">0</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="lg:ml-64">
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
                            <button @click="open = !open" class="relative flex items-center text-gray-600 hover:text-[#0d5c2f] transition-colors">
                                <i class="fas fa-bell text-xl mr-2"></i>
                                <span id="header-notification-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-2 py-1 hidden" data-notification-count="0">0</span>
                            </button>
                            
                            <!-- Notification Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50 max-h-96 overflow-y-auto">
                                
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                        <a href="{{ route('admin.notifications.index') }}" class="text-xs text-[#0d5c2f] hover:text-[#0d5c2f]/80">View All</a>
                                    </div>
                                </div>
                                
                                <div id="header-notifications-list" class="divide-y divide-gray-100">
                                    <!-- Notifications will be loaded here -->
                                    <div class="px-4 py-3 text-center text-gray-500 text-sm">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>
                                        Loading notifications...
                                    </div>
                                </div>
                                
                                <div class="px-4 py-2 border-t border-gray-100">
                                    <button id="mark-all-read-header" class="w-full text-left text-xs text-[#0d5c2f] hover:text-[#0d5c2f]/80">
                                        Mark all as read
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
            <main class="p-6">
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
            fetch('{{ route("admin.notifications.unread-count") }}')
            .then(response => response.json())
            .then(data => {
                const countElement = document.getElementById('notification-count');
                if (countElement) {
                    countElement.textContent = data.count;
                    if (data.count > 0) {
                        countElement.classList.remove('hidden');
                    } else {
                        countElement.classList.add('hidden');
                    }
                }
            })
            .catch(error => console.error('Error fetching notification count:', error));
        }

        // Update count on page load
        updateNotificationCount();

        // Update count every 30 seconds
        setInterval(updateNotificationCount, 30000);

        // Load header notifications
        function loadHeaderNotifications() {
            fetch('{{ route("admin.notifications.unread-count") }}?limit=5')
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
                        const isRead = notification.is_read ? 'border-gray-300' : 'border-[#0d5c2f]';
                        const badge = notification.is_read ? '' : '<span class="inline-block w-2 h-2 bg-[#0d5c2f] rounded-full mr-2"></span>';
                        
                        html += `
                            <div class="px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer notification-item-header" data-id="${notification.id}" data-read="${notification.is_read}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        ${badge}
                                        <p class="text-sm text-gray-900 font-medium">${notification.message}</p>
                                        <p class="text-xs text-gray-500 mt-1">${notification.created_at}</p>
                                    </div>
                                    ${!notification.is_read ? '<button class="mark-read-header-btn text-xs text-[#0d5c2f] hover:text-[#0d5c2f]/80 ml-2">Mark read</button>' : ''}
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
                            
                            fetch('{{ route("admin.notifications.mark-as-read") }}', {
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
                    notificationsList.innerHTML = '<div class="px-4 py-3 text-center text-gray-500 text-sm">No new notifications</div>';
                }
            })
            .catch(error => {
                console.error('Error loading header notifications:', error);
                document.getElementById('header-notifications-list').innerHTML = '<div class="px-4 py-3 text-center text-red-500 text-sm">Error loading notifications</div>';
            });
        }

        // Mark all as read from header
        document.getElementById('mark-all-read-header').addEventListener('click', function() {
            fetch('{{ route("admin.notifications.mark-all-as-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    type: 'all'
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

        // Load header notifications on page load
        loadHeaderNotifications();
    </script>
</body>
</html> 