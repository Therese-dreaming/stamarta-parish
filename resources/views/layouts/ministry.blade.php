<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Ministry Panel</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.2/css/all.css">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 font-['Poppins'] min-h-full flex flex-col">
    <div class="min-h-screen">
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg" id="sidebar">
            <div class="h-16 px-6 flex items-center border-b">
                <h1 class="text-lg font-bold text-[#0d5c2f]">Ministry Panel</h1>
            </div>
            <nav class="mt-6 px-4 space-y-2">
                <a href="{{ route('ministry.dashboard') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('ministry.dashboard') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-gauge w-4 h-4 mr-2"></i>
                    Dashboard
                </a>
                <a href="{{ route('ministry.members.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('ministry.members.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-users w-4 h-4 mr-2"></i>
                    Members
                </a>
                <a href="{{ route('ministry.budget-requests.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('ministry.budget-requests.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-file-invoice-dollar w-4 h-4 mr-2"></i>
                    Budget Requests
                </a>
                <a href="{{ route('ministry.activities.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('ministry.activities.*') ? 'bg-[#0d5c2f] text-white' : '' }}">
                    <i class="fas fa-calendar-star w-4 h-4 mr-2"></i>
                    Activities
                </a>
            </nav>
        </div>

        <div class="lg:ml-64">
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-6">
                    <h2 class="text-lg font-semibold text-gray-900">@yield('title')</h2>
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-[#0d5c2f] transition-colors">
                        <i class="fas fa-home mr-2"></i>View Site
                    </a>
                </div>
            </div>
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>


