<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mazraa') }} - Owner Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js (Fallback in case it's not bundled in app.js yet) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Animations -->
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 20px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-900 text-slate-200" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Backdrop (Mobile) -->
        <div x-show="sidebarOpen"
             x-transition.opacity
             class="fixed inset-0 z-20 bg-[#020617]/80 backdrop-blur-sm lg:hidden"
             @click="sidebarOpen = false"
             style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-30 w-72 bg-[#020617] text-gray-300 transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col border-r border-gray-800/50 shadow-2xl">

            <!-- Logo area -->
            <div class="flex items-center justify-center h-20 border-b border-gray-800/50 bg-[#020617]">
                <a href="{{ route('owner.dashboard') ?? '#' }}" class="flex items-center gap-3">
                    <!-- Brand Icon Placeholder -->
                    <div class="w-8 h-8 rounded bg-gradient-to-br from-[#c2a265] to-yellow-700 flex items-center justify-center shadow-lg shadow-[#c2a265]/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-bold tracking-widest text-[#c2a265] uppercase leading-none">Mazraa</span>
                        <span class="text-[0.65rem] font-semibold text-gray-400 tracking-[0.2em] mt-1">OWNER PORTAL</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
                <a href="{{ route('owner.dashboard') ?? '#' }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 {{ request()->routeIs('owner.dashboard') ? 'bg-gradient-to-r from-[#1d5c42] to-[#154531] text-white shadow-lg shadow-[#1d5c42]/20 border border-[#1d5c42]/50' : 'hover:bg-gray-800/50 hover:text-[#c2a265]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="font-medium tracking-wide text-sm">Dashboard</span>
                </a>

                <a href="{{ route('owner.farms.index') ?? '#' }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 {{ request()->routeIs('owner.farms.*') ? 'bg-gradient-to-r from-[#1d5c42] to-[#154531] text-white shadow-lg shadow-[#1d5c42]/20 border border-[#1d5c42]/50' : 'hover:bg-gray-800/50 hover:text-[#c2a265]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="font-medium tracking-wide text-sm">My Farms</span>
                </a>

                <a href="{{ route('owner.bookings.index') ?? '#' }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 {{ request()->routeIs('owner.bookings.*') ? 'bg-gradient-to-r from-[#1d5c42] to-[#154531] text-white shadow-lg shadow-[#1d5c42]/20 border border-[#1d5c42]/50' : 'hover:bg-gray-800/50 hover:text-[#c2a265]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="font-medium tracking-wide text-sm">Bookings</span>
                </a>

                <a href="{{ route('owner.financials') ?? '#' }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 {{ request()->routeIs('owner.financials.*') ? 'bg-gradient-to-r from-[#1d5c42] to-[#154531] text-white shadow-lg shadow-[#1d5c42]/20 border border-[#1d5c42]/50' : 'hover:bg-gray-800/50 hover:text-[#c2a265]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium tracking-wide text-sm">Financials</span>
                </a>

                <a href="{{ route('owner.profile.edit') ?? '#' }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 {{ request()->routeIs('owner.profile.*') ? 'bg-gradient-to-r from-[#1d5c42] to-[#154531] text-white shadow-lg shadow-[#1d5c42]/20 border border-[#1d5c42]/50' : 'hover:bg-gray-800/50 hover:text-[#c2a265]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="font-medium tracking-wide text-sm">Profile</span>
                </a>
            </nav>

            <!-- Bottom Action (Logout) -->
            <div class="p-4 border-t border-gray-800/50 bg-[#020617]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold text-gray-400 transition-all duration-300 bg-gray-900/50 rounded-xl border border-gray-800 hover:bg-red-500/10 hover:text-red-400 hover:border-red-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 bg-slate-900">

            <!-- Top Navbar (Glassmorphism) -->
            <header class="sticky top-0 z-10 bg-slate-800/90 backdrop-blur-xl border-b border-slate-700 shadow-sm">
                <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-20">

                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = true" class="p-2 -ml-2 text-slate-400 rounded-lg lg:hidden hover:bg-slate-700 transition-colors focus:outline-none focus:ring-2 focus:ring-[#1d5c42]/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>

                    <!-- Page Header injected via slot -->
                    <div class="hidden lg:flex items-center text-xl font-bold text-white tracking-tight">
                        {{ $header ?? 'Dashboard' }}
                    </div>

                    <!-- Right side topbar -->
                    <div class="flex items-center gap-5 ml-auto">
                        <!-- Notifications -->
                        <x-notification-bell />

                        <!-- Divider -->
                        <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>

                        <!-- User Menu Dropdown -->
                        <div x-data="{ dropdownOpen: false }" class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="flex items-center gap-3 p-1.5 pr-4 transition-all duration-200 rounded-full border border-slate-700 hover:border-[#c2a265]/50 bg-slate-800 shadow-sm hover:shadow-md">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#1d5c42] to-[#154531] flex items-center justify-center text-white font-bold text-sm shadow-inner">
                                    {{ substr(Auth::user()->name ?? 'O', 0, 1) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-bold text-white leading-none">{{ Auth::user()->name ?? 'Owner Name' }}</p>
                                    <p class="text-xs text-[#c2a265] font-semibold mt-1">Farm Owner</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 hidden md:block transition-transform duration-200" :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="dropdownOpen"
                                 x-transition.enter="transition ease-out duration-200"
                                 x-transition.enter-start="transform opacity-0 scale-95 translate-y-2"
                                 x-transition.enter-end="transform opacity-100 scale-100 translate-y-0"
                                 x-transition.leave="transition ease-in duration-150"
                                 x-transition.leave-start="transform opacity-100 scale-100 translate-y-0"
                                 x-transition.leave-end="transform opacity-0 scale-95 translate-y-2"
                                 class="absolute right-0 w-56 mt-3 origin-top-right bg-slate-800 border border-slate-700 rounded-2xl shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none z-50 overflow-hidden"
                                 style="display: none;">
                                <div class="p-2 bg-slate-700/50 border-b border-slate-700 md:hidden">
                                    <p class="text-sm font-bold text-white">{{ Auth::user()->name ?? 'Owner Name' }}</p>
                                    <p class="text-xs text-[#c2a265] font-semibold">{{ Auth::user()->email ?? 'owner@mazraa.com' }}</p>
                                </div>
                                <div class="p-1">
                                    <a href="{{ route('owner.profile.edit') ?? '#' }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-300 rounded-xl hover:bg-slate-700 hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Account Settings
                                    </a>
                                    <div class="h-px bg-gray-100 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="w-full flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-red-600 rounded-xl hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <!-- Smooth entry animation for the nested content -->
                <div class="animate-fade-in-up max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>

        </div>
    </div>
</body>
</html>

