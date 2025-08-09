<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Santa Marta | San Roque</title>
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
                    <a href="{{ route('pages.index') }}" class="text-gray-600 hover:text-[#0d5c2f] transition-colors">Pages</a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-[#0d5c2f] transition-colors">Contact</a>
                    
                    @auth
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
</body>
</html>