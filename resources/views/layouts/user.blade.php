<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Santa Marta | San Roque</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.2/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-['Poppins'] min-h-full flex flex-col" x-data="{ mobileMenu: false }">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg relative z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('images/church-logo.png') }}" alt="Logo" class="h-12 w-12">
                    <span class="ml-3 text-xl font-semibold text-[#0d5c2f]">SANTA MARTA | SAN ROQUE</span>
                </div>
                
                <!-- In the main navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-[#0d5c2f] transition-colors">Home</a>
                    @php
                        try {
                            $navPages = \App\Models\Page::published()
                                ->orderBy('title')
                                ->get(['slug','title']);
                        } catch (\Throwable $e) {
                            $navPages = collect();
                        }
                    @endphp
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @keydown.escape.window="open=false" class="flex items-center text-gray-600 hover:text-[#0d5c2f] transition-colors">
                            <span>Pages</span>
                            <i class="fas fa-chevron-down text-xs ml-2"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-100 py-2 z-50">
                            <a href="{{ route('pages.index') }}" class="block px-4 py-2 text-sm text-[#0d5c2f] hover:bg-gray-50">View All Pages</a>
                            <div class="my-1 border-t border-gray-100"></div>
                            @forelse($navPages as $p)
                                <a href="{{ route('page.show', $p->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 truncate" title="{{ $p->title }}">{{ $p->title }}</a>
                            @empty
                                <span class="block px-4 py-2 text-sm text-gray-400">No pages available</span>
                            @endforelse
                        </div>
                    </div>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-[#0d5c2f] transition-colors">Contact</a>
                    
                    @auth
                                                <!-- Notification Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="relative flex items-center text-gray-600 hover:text-[#0d5c2f] transition-colors p-2 rounded-lg hover:bg-gray-100">
                                <i class="fas fa-bell text-xl"></i>
                                <span id="header-notification-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden font-medium" data-notification-count="0">0</span>
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
                                                <p class="text-xs text-gray-500">Stay updated with your activities</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('user.notifications.index') }}" class="text-xs text-[#0d5c2f] hover:text-[#0d5c2f]/80 font-medium">View All</a>
                                    </div>
                                </div>
                                
                                <!-- Notifications List -->
                                <div id="header-notifications-list" class="max-h-80 overflow-y-auto">
                                    <!-- Notifications will be loaded here -->
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
                        
                        <a href="{{ route('userServices') }}" class="bg-[#0d5c2f] text-white px-6 py-2 rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">Book Now</a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-600 hover:text-[#0d5c2f]">
                                <span>{{ Auth::user()->name ?? 'My Account' }}</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <a href="{{ route('booking.my-bookings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Bookings</a>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Panel</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-[#0d5c2f] hover:text-[#0d5c2f]/80">Login</a>
                        <a href="{{ route('signup') }}" class="bg-[#0d5c2f] text-white px-6 py-2 rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">Register</a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-600 hover:text-[#0d5c2f]" @click="mobileMenu = !mobileMenu">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile menu -->
    <div class="md:hidden" x-show="mobileMenu" @click.away="mobileMenu = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95">
        <div class="px-2 pt-2 pb-3 space-y-1 bg-white shadow-lg">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-600 hover:text-[#0d5c2f]">Home</a>
            <a href="{{ route('pages.index') }}" class="block px-3 py-2 text-gray-600 hover:text-[#0d5c2f]">Pages</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 text-gray-600 hover:text-[#0d5c2f]">Contact</a>
            @auth
                <!-- Mobile Notification Bell -->
                <div class="px-3 py-2">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Notifications</span>
                        <span id="mobile-notification-count" class="bg-red-500 text-white text-xs rounded-full px-2 py-1 hidden" data-notification-count="0">0</span>
                    </div>
                </div>
                
                <a href="{{ route('userServices') }}" class="block px-3 py-2 text-[#0d5c2f] font-medium">Book Now</a>
                <a href="{{ route('profile') }}" class="block px-3 py-2 text-gray-600 hover:text-[#0d5c2f]">Profile</a>
                <a href="{{ route('booking.my-bookings') }}" class="block px-3 py-2 text-gray-600 hover:text-[#0d5c2f]">My Bookings</a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-gray-600 hover:text-[#0d5c2f]">Admin Panel</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-gray-600 hover:text-[#0d5c2f]">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 text-[#0d5c2f]">Login</a>
                <a href="{{ route('signup') }}" class="block px-3 py-2 text-[#0d5c2f]">Register</a>
            @endauth
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#0d5c2f] text-white mt-auto">
        <div class="container mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-semibold mb-4">About Us</h3>
                    <p class="text-white/80">Serving the community with faith, love, and dedication.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-white/80 hover:text-white">Services</a></li>
                        <li><a href="#" class="text-white/80 hover:text-white">Schedule</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2 text-white/80">
                        <li>B. Morcilla St.,</li>
                        <li>Pateros, Metro Manila</li>
                        <li>Phone: 0917-366-4359</li>
                        <li>Email: diocesansaintmartha@gmail.com</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/StaMartaYSanRoque" class="text-white/80 hover:text-white"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.youtube.com/channel/UCclt6h0RgU0jv6amSIcBsrA" class="text-white/80 hover:text-white"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/20 mt-8 pt-8 text-center text-white/60">
                <p>&copy; {{ date('Y') }} Santa Marta Parish. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Toast Notifications -->
    <x-toast />
    
    <!-- Chatbot -->
    <x-chatbot />
    
    <script>
        @auth
        // Load header notifications for authenticated users
        function loadHeaderNotifications() {
            fetch('{{ route("user.notifications.unread-count") }}?limit=5')
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
                
                // Update mobile notification count
                const mobileCount = document.getElementById('mobile-notification-count');
                if (mobileCount) {
                    if (data.count > 0) {
                        mobileCount.textContent = data.count;
                        mobileCount.classList.remove('hidden');
                    } else {
                        mobileCount.classList.add('hidden');
                    }
                }
                
                // Load recent notifications
                if (data.notifications && data.notifications.length > 0) {
                    let html = '';
                    data.notifications.forEach(notification => {
                        const isRead = notification.is_read ? 'border-gray-300' : 'border-[#0d5c2f]';
                        const badge = notification.is_read ? '' : '<span class="inline-block w-2 h-2 bg-[#0d5c2f] rounded-full mr-2"></span>';
                        
                        // Determine icon and styling based on notification type
                        let icon = 'fas fa-bell';
                        let iconBg = 'bg-blue-100';
                        let iconColor = 'text-blue-600';
                        let borderColor = notification.is_read ? 'border-gray-100' : 'border-[#0d5c2f]';
                        
                        if (notification.message.toLowerCase().includes('booking')) {
                            icon = 'fas fa-calendar-check';
                            iconBg = 'bg-green-100';
                            iconColor = 'text-green-600';
                        } else if (notification.message.toLowerCase().includes('payment')) {
                            icon = 'fas fa-credit-card';
                            iconBg = 'bg-purple-100';
                            iconColor = 'text-purple-600';
                        } else if (notification.message.toLowerCase().includes('approved') || notification.message.toLowerCase().includes('confirmed')) {
                            icon = 'fas fa-check-circle';
                            iconBg = 'bg-green-100';
                            iconColor = 'text-green-600';
                        } else if (notification.message.toLowerCase().includes('rejected') || notification.message.toLowerCase().includes('cancelled')) {
                            icon = 'fas fa-times-circle';
                            iconBg = 'bg-red-100';
                            iconColor = 'text-red-600';
                        } else if (notification.message.toLowerCase().includes('reminder')) {
                            icon = 'fas fa-clock';
                            iconBg = 'bg-orange-100';
                            iconColor = 'text-orange-600';
                        }
                        
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
                            
                            fetch('{{ route("user.notifications.mark-as-read") }}', {
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
            fetch('{{ route("user.notifications.mark-all-as-read") }}', {
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
                    loadHeaderNotifications();
                }
            });
        });

        // Load header notifications on page load
        loadHeaderNotifications();
        @endauth
    </script>
    
    @stack('scripts')
</body>
</html>