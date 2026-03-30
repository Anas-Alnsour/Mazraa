<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mazraa.com - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        /* Smooth Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #1d5c42; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #c2a265; }
    </style>
</head>

<body class="bg-[#f9f8f4] font-sans antialiased text-gray-800 flex flex-col min-h-screen selection:bg-[#c2a265] selection:text-white">

    {{-- ==========================================
         🌟 ULTRA MODERN FLOATING NAVBAR 🌟
         ========================================== --}}
    <div class="fixed top-0 inset-x-0 z-[100] transition-all duration-700 ease-out px-4 sm:px-6 lg:px-8 pointer-events-none"
         x-data="{ scrolled: false }"
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="scrolled ? 'pt-2' : 'pt-6'">

        <nav class="pointer-events-auto mx-auto max-w-[98%] xl:max-w-7xl 2xl:max-w-[1400px] bg-white/90 backdrop-blur-2xl border border-white/50 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-full transition-all duration-700 ease-out"
             :class="scrolled ? 'px-4 py-2 shadow-xl bg-white/95' : 'px-4 xl:px-6 py-3'">

            <div x-data="{ mobileMenuOpen: false }" class="flex items-center justify-between">

                {{-- 1. Logo (Left Section) --}}
                <div class="flex items-center justify-start lg:w-1/5 xl:w-1/4">
                    <a href="{{ url('/') }}" class="group flex items-center gap-2 text-xl md:text-2xl font-black tracking-tighter text-[#0f172a] transition duration-500 hover:opacity-80">
                        <div class="bg-gradient-to-tr from-[#1d5c42] to-[#2a7a5a] p-1.5 rounded-xl text-white shadow-md group-hover:rotate-6 group-hover:scale-110 transition-all duration-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        Mazraa<span class="text-[#c2a265] hidden sm:inline">.com</span>
                    </a>
                </div>

                {{-- 2. Desktop Links (Center Section - Perfectly Centered Pill) --}}
                <div class="hidden lg:flex items-center justify-center flex-1">
                    <div class="flex items-center gap-0.5 bg-gray-100/80 p-1.5 rounded-full border border-gray-200/50 shadow-inner">
                        @php
                            $linkClass = "px-2.5 xl:px-4 py-2 rounded-full text-[10px] xl:text-xs font-black uppercase tracking-widest text-gray-500 hover:text-[#1d5c42] hover:bg-white hover:shadow-sm transition-all duration-300 whitespace-nowrap";
                            $activeClass = "text-[#1d5c42] bg-white shadow-sm border border-gray-100";
                        @endphp

                        @if (Auth::check() && Auth::user()->role === 'admin')
                            <a href="{{ url('/') }}" class="{{ $linkClass }} {{ request()->is('/') ? $activeClass : '' }}">Home</a>
                            <a href="{{ route('admin.farms.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.farms.*') ? $activeClass : '' }}">Manage Farms</a>
                            <a href="{{ route('admin.supplies.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.supplies.*') ? $activeClass : '' }}">Supplies</a>
                            <a href="{{ route('admin.contact.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.contact.*') ? $activeClass : '' }}">Messages</a>
                        @else
                            <a href="{{ url('/') }}" class="{{ $linkClass }} {{ request()->is('/') ? $activeClass : '' }}">Home</a>
                            <a href="{{ route('explore') }}" class="{{ $linkClass }} {{ request()->routeIs('explore') ? $activeClass : '' }}">Explore</a>
                            @auth
                                <a href="{{ route('bookings.my_bookings') }}" class="{{ $linkClass }} {{ request()->routeIs('bookings.*') ? $activeClass : '' }}">Bookings</a>
                                <a href="{{ route('favorites.index') }}" class="{{ $linkClass }} {{ request()->routeIs('favorites.*') ? $activeClass : '' }}">Favorites</a>
                                <a href="{{ route('supplies.market') }}" class="{{ $linkClass }} {{ request()->routeIs('supplies.*') ? $activeClass : '' }}">Market</a>
                                <a href="{{ route('transports.index') }}" class="{{ $linkClass }} {{ request()->routeIs('transports.*') ? $activeClass : '' }}">Transport</a>
                            @endauth
                            <a href="{{ route('about') }}" class="{{ $linkClass }} {{ request()->routeIs('about') ? $activeClass : '' }}">About</a>
                            <a href="{{ route('contact') }}" class="{{ $linkClass }} {{ request()->routeIs('contact') ? $activeClass : '' }}">Contact</a>
                        @endif
                    </div>
                </div>

                {{-- 3. Right Auth Buttons (Right Section) --}}
                <div class="hidden lg:flex items-center justify-end gap-2 xl:gap-3 lg:w-1/5 xl:w-1/4">
                    @auth
                        @php
                            $role = Auth::user()->role;
                            $dashboardLink = match($role) {
                                'admin' => '/admin',
                                'farm_owner' => '/owner/dashboard',
                                'supply_company' => '/supplies/dashboard',
                                'transport_company' => '/transport/dashboard',
                                'supply_driver' => '/delivery/orders',
                                'transport_driver' => '/shuttle/trips',
                                default => '/dashboard'
                            };
                            $isBusiness = $role !== 'user';
                        @endphp

                        <a href="{{ url($dashboardLink) }}" class="px-4 xl:px-5 py-2.5 text-[10px] xl:text-xs font-black uppercase tracking-widest text-[#1d5c42] bg-green-50 rounded-full hover:bg-green-100 hover:shadow-md transition-all duration-300 border border-green-200/50 hover:-translate-y-0.5 whitespace-nowrap">
                            {{ $isBusiness ? 'Dashboard' : 'Profile' }}
                        </a>

                        {{-- User Profile Dropdown --}}
                        <div class="relative ml-1" x-data="{ profileOpen: false }" @click.away="profileOpen = false">
                            <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 xl:gap-3 focus:outline-none pl-3 xl:pl-4 border-l border-gray-200">
                                <div class="text-right hidden xl:block">
                                    <p class="text-xs font-black text-gray-800 leading-tight">{{ explode(' ', Auth::user()->name)[0] }}</p>
                                    <p class="text-[9px] font-black text-[#c2a265] uppercase tracking-widest">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                                </div>
                                <div class="w-9 h-9 xl:w-10 xl:h-10 rounded-full bg-gradient-to-tr from-[#1d5c42] to-[#c2a265] text-white flex items-center justify-center font-black shadow-inner border-2 border-white transform transition hover:scale-110 duration-300">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </button>

                            <div x-show="profileOpen" x-transition.opacity.duration.200ms x-cloak class="absolute right-0 mt-4 w-48 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-100 py-2 z-50 overflow-hidden">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-5 py-3 text-xs font-black uppercase tracking-widest text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors text-left group">
                                        <div class="p-1.5 bg-red-100 rounded-md group-hover:scale-110 transition-transform"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg></div>
                                        Secure Log Out
                                    </button>
                                </form>
                            </div>
                        </div>

                    @else
                        {{-- Guest Dropdowns --}}
                        <div class="relative" x-data="{ loginOpen: false }" @click.away="loginOpen = false">
                            <button @click="loginOpen = !loginOpen" class="inline-flex items-center gap-1.5 px-3 xl:px-5 py-2.5 text-[10px] xl:text-xs font-black uppercase tracking-widest text-gray-600 hover:text-[#1d5c42] rounded-full transition-all focus:outline-none hover:bg-gray-50 whitespace-nowrap">
                                Log in
                                <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-180': loginOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="loginOpen" x-transition.opacity.duration.200ms x-cloak class="absolute right-0 mt-4 w-64 bg-white/95 backdrop-blur-xl rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100 py-3 z-50">
                                <div class="px-5 pb-2 text-[9px] font-black text-gray-400 uppercase tracking-widest">Consumer Portal</div>
                                <a href="{{ route('login') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-gray-800 hover:bg-gray-50 transition-colors group">
                                    <div class="p-1.5 bg-gray-100 text-gray-600 rounded-lg group-hover:bg-[#1d5c42] group-hover:text-white transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
                                    User Login
                                </a>
                                <div class="border-t border-gray-100 my-2"></div>
                                <div class="px-5 pb-2 pt-1 text-[9px] font-black text-gray-400 uppercase tracking-widest">Business Portals</div>
                                <a href="{{ route('portal.login') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-gray-800 hover:bg-blue-50 transition-colors group">
                                    <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
                                    Farm Owner
                                </a>
                                <a href="{{ route('transport-driver.login') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-gray-800 hover:bg-amber-50 transition-colors group">
                                    <div class="p-1.5 bg-amber-50 text-amber-600 rounded-lg group-hover:bg-amber-500 group-hover:text-white transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg></div>
                                    Transport Driver
                                </a>
                                <a href="{{ route('supply-driver.login') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-gray-800 hover:bg-teal-50 transition-colors group">
                                    <div class="p-1.5 bg-teal-50 text-teal-600 rounded-lg group-hover:bg-teal-500 group-hover:text-white transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg></div>
                                    Supply Driver
                                </a>
                            </div>
                        </div>

                        <div class="relative ml-1" x-data="{ regOpen: false }" @click.away="regOpen = false">
                            <button @click="regOpen = !regOpen" class="inline-flex items-center gap-2 px-4 xl:px-6 py-2.5 text-[10px] xl:text-xs font-black uppercase tracking-widest text-white bg-gradient-to-r from-[#1d5c42] to-[#154230] rounded-full shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 focus:outline-none border border-[#1d5c42]/50 whitespace-nowrap">
                                Get Started
                                <svg class="w-4 h-4 transition-transform duration-300 opacity-70" :class="{'rotate-180': regOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="regOpen" x-transition.opacity.duration.200ms x-cloak class="absolute right-0 mt-4 w-60 bg-white/95 backdrop-blur-xl rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100 py-3 z-50 overflow-hidden">
                                <a href="{{ route('register') }}" class="block px-6 py-3 text-sm font-bold text-[#1d5c42] hover:bg-green-50 transition-colors">Register as User</a>
                                <div class="border-t border-gray-50 my-1"></div>
                                <a href="{{ route('become.partner') }}" class="block px-6 py-3 text-sm font-bold text-[#c2a265] hover:bg-amber-50 transition-colors">List Your Farm</a>
                            </div>
                        </div>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <div class="lg:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-800 p-2 rounded-full hover:bg-gray-100 focus:outline-none transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Dropdown Menu --}}
            <div x-show="mobileMenuOpen" x-collapse x-cloak class="lg:hidden mt-4 border-t border-gray-100 pt-4 pb-4 space-y-2">
                @if (Auth::check() && Auth::user()->role === 'admin')
                    <a href="{{ url('/') }}" class="block px-4 py-3 rounded-2xl text-sm font-black uppercase text-gray-600 hover:bg-gray-50">Home</a>
                    <a href="{{ route('admin.farms.index') }}" class="block px-4 py-3 rounded-2xl text-sm font-black uppercase text-gray-600 hover:bg-gray-50">Manage Farms</a>
                @else
                    <a href="{{ url('/') }}" class="block px-4 py-3 rounded-2xl text-sm font-black uppercase text-gray-600 hover:bg-gray-50">Home</a>
                    <a href="{{ route('explore') }}" class="block px-4 py-3 rounded-2xl text-sm font-black uppercase text-[#1d5c42] bg-green-50">Explore Farms</a>

                    @auth
                        <a href="{{ route('bookings.my_bookings') }}" class="block px-4 py-3 rounded-2xl text-sm font-black uppercase text-gray-600 hover:bg-gray-50">Bookings</a>
                        <a href="{{ route('favorites.index') }}" class="block px-4 py-3 rounded-2xl text-sm font-black uppercase text-gray-600 hover:bg-gray-50">Favorites</a>
                        <a href="{{ route('supplies.market') }}" class="block px-4 py-3 rounded-2xl text-sm font-black uppercase text-gray-600 hover:bg-gray-50">Market</a>
                        <a href="{{ route('transports.index') }}" class="block px-4 py-3 rounded-2xl text-sm font-black uppercase text-gray-600 hover:bg-gray-50">Transport</a>
                    @endauth

                    <a href="{{ route('about') }}" class="block px-4 py-3 rounded-2xl text-sm font-black uppercase text-gray-600 hover:bg-gray-50">About</a>
                    <a href="{{ route('contact') }}" class="block px-4 py-3 rounded-2xl text-sm font-black uppercase text-gray-600 hover:bg-gray-50">Contact</a>
                @endif

                <div class="border-t border-gray-100 mt-4 pt-4">
                    @auth
                        <a href="{{ url($dashboardLink) }}" class="block px-4 py-3 rounded-2xl text-sm font-black uppercase text-white bg-[#1d5c42] text-center shadow-md">Go to Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full text-center px-4 py-3 rounded-2xl text-sm font-black uppercase text-red-500 bg-red-50">Log out</button>
                        </form>
                    @else
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('login') }}" class="block px-3 py-3 rounded-2xl text-xs font-black uppercase text-gray-700 bg-gray-100 border border-gray-200 text-center">Login</a>
                            <a href="{{ route('register') }}" class="block px-3 py-3 rounded-2xl text-xs font-black uppercase text-white bg-[#1d5c42] text-center shadow-md">Sign Up</a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>
    </div>

    {{-- Flash Messages --}}
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" class="fixed top-28 right-5 z-[110] flex flex-col gap-3 pointer-events-none">
        @if (session('success'))
            <div x-show="show" x-transition.duration.500ms class="pointer-events-auto bg-white/90 backdrop-blur-xl border border-green-200 rounded-2xl shadow-2xl p-4 flex items-start gap-4 w-80 transform hover:scale-105 transition-transform">
                <div class="bg-green-100 p-2 rounded-full text-green-600 flex-shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                <div class="flex-1"><h4 class="font-black text-gray-900 text-sm uppercase tracking-wider">Success</h4><p class="text-gray-500 text-xs mt-1 font-medium leading-relaxed">{{ session('success') }}</p></div>
                <button @click="show = false" class="text-gray-400 hover:text-gray-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif
        @if (session('error'))
            <div x-show="show" x-transition.duration.500ms class="pointer-events-auto bg-white/90 backdrop-blur-xl border border-red-200 rounded-2xl shadow-2xl p-4 flex items-start gap-4 w-80 transform hover:scale-105 transition-transform">
                <div class="bg-red-100 p-2 rounded-full text-red-600 flex-shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                <div class="flex-1"><h4 class="font-black text-gray-900 text-sm uppercase tracking-wider">Error</h4><p class="text-gray-500 text-xs mt-1 font-medium leading-relaxed">{{ session('error') }}</p></div>
                <button @click="show = false" class="text-gray-400 hover:text-gray-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <main class="flex-1 w-full">
        @yield('content')
    </main>

    {{-- ==========================================
         🌟 ULTRA MODERN ANIMATED FOOTER 🌟
         ========================================== --}}
    <footer x-data="{ showFooter: false }"
            x-init="
                let observer = new IntersectionObserver((entries) => {
                    if(entries[0].isIntersecting) { showFooter = true; }
                }, { threshold: 0.1 });
                observer.observe($el);
            "
            class="relative bg-[#020617] text-white pt-24 pb-12 overflow-hidden border-t border-white/10 mt-auto z-10">

        {{-- Giant Watermark Text --}}
        <div class="absolute bottom-[-5%] left-0 right-0 flex justify-center pointer-events-none opacity-[0.03] select-none overflow-hidden"
             :class="showFooter ? 'translate-y-0 scale-100' : 'translate-y-20 scale-95'" style="transition: all 1.5s cubic-bezier(0.4, 0, 0.2, 1);">
            <h1 class="text-[18vw] font-black uppercase tracking-tighter text-white whitespace-nowrap">MAZRAA</h1>
        </div>

        {{-- Subtle Animated Glow Orbs --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-[#1d5c42]/20 rounded-full blur-[120px] pointer-events-none animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-[#c2a265]/10 rounded-full blur-[120px] pointer-events-none animate-pulse" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">

                {{-- Column 1: Brand & About (Delay 100ms) --}}
                <div class="space-y-6 transition-all duration-1000 ease-out transform" :class="showFooter ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 group inline-block">
                        <div class="bg-gradient-to-br from-[#1d5c42] to-[#154230] p-2.5 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-500 border border-white/10">
                            <svg class="w-6 h-6 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <span class="text-3xl font-black text-white tracking-tighter">Mazraa<span class="text-[#c2a265]">.com</span></span>
                    </a>
                    <p class="text-sm font-medium text-gray-400 leading-relaxed max-w-sm">
                        The ultimate luxury ecosystem. We bridge the gap between premium farm stays, reliable supply chains, and safe transport.
                    </p>
                    <div class="flex space-x-4 pt-2">
                        <a href="javascript:void(0)" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-[#c2a265] hover:text-[#020617] hover:scale-110 transition-all duration-300 border border-white/10"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg></a>
                        <a href="javascript:void(0)" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-[#c2a265] hover:text-[#020617] hover:scale-110 transition-all duration-300 border border-white/10"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg></a>
                        <a href="javascript:void(0)" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-[#c2a265] hover:text-[#020617] hover:scale-110 transition-all duration-300 border border-white/10"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                    </div>
                </div>

                {{-- Column 2: Quick Links (Delay 200ms) --}}
                <div class="transition-all duration-1000 delay-200 ease-out transform" :class="showFooter ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                    <h3 class="text-xs font-black text-white uppercase tracking-[0.2em] mb-6">Quick Links</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('explore') }}" class="text-sm font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Explore Escapes</a></li>
                        <li><a href="{{ route('supplies.market') }}" class="text-sm font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Supply Market</a></li>
                        <li><a href="{{ route('about') }}" class="text-sm font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Our Story</a></li>
                        <li><a href="{{ route('contact') }}" class="text-sm font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Support Center</a></li>
                    </ul>
                </div>

                {{-- Column 3: Partner Portals (Delay 300ms) --}}
                <div class="transition-all duration-1000 delay-300 ease-out transform" :class="showFooter ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                    <h3 class="text-xs font-black text-white uppercase tracking-[0.2em] mb-6">Partner Network</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('portal.login') }}" class="text-sm font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Owner Portal</a></li>
                        <li><a href="{{ route('transport-driver.login') }}" class="text-sm font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Transport Dispatch</a></li>
                        <li><a href="{{ route('supply-driver.login') }}" class="text-sm font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Supply Logistics</a></li>
                        <li><a href="{{ route('partner.register') }}" class="text-sm font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Join Partner Program</a></li>
                    </ul>
                </div>

                {{-- Column 4: Newsletter (Delay 400ms) --}}
                <div class="transition-all duration-1000 delay-500 ease-out transform" :class="showFooter ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                    <h3 class="text-xs font-black text-white uppercase tracking-[0.2em] mb-6">Stay Connected</h3>
                    <p class="text-sm text-gray-400 font-medium mb-6">Subscribe for exclusive luxury farm drops and platform updates.</p>

                    {{-- 🌟 Interactive Alpine.js Form (No Page Jump!) 🌟 --}}
                    <form x-data="{ subscribed: false }" @submit.prevent="subscribed = true" class="flex flex-col space-y-3 relative group">

                        <div x-show="!subscribed" x-transition.opacity class="relative w-full">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-[#1d5c42] to-[#c2a265] rounded-2xl blur opacity-0 group-hover:opacity-30 transition duration-500"></div>
                            <div class="relative flex bg-[#0f172a] p-1.5 rounded-2xl border border-white/10 focus-within:border-[#c2a265]/50 transition-colors">
                                <input type="email" placeholder="Enter your email..." required
                                       class="w-full bg-transparent border-none text-white text-sm rounded-xl pl-4 pr-2 focus:ring-0 focus:outline-none placeholder-gray-500">
                                <button type="submit" class="bg-gradient-to-r from-[#1d5c42] to-[#154230] hover:from-[#154230] hover:to-[#0a1c14] text-white p-3 rounded-xl transition-all shadow-md hover:shadow-lg hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div x-show="subscribed" x-transition.opacity x-cloak class="p-4 rounded-2xl bg-green-500/10 border border-green-500/20 text-center flex flex-col items-center justify-center">
                            <svg class="w-6 h-6 text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-green-400 font-bold text-sm">Successfully Subscribed!</span>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bottom Footer Line --}}
            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 transition-all duration-1000 delay-700 ease-out transform" :class="showFooter ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                <p class="text-xs font-black text-gray-500 tracking-wider">
                    Anas Alnsour - Adnan Awad - Aktham Zayed - Hafez Fares - Malek Al-sarayerah
                </p>
                <div class="flex space-x-8">
                    <a href="javascript:void(0)" class="text-xs font-bold text-gray-500 hover:text-white uppercase tracking-widest transition-colors">Privacy</a>
                    <a href="javascript:void(0)" class="text-xs font-bold text-gray-500 hover:text-white uppercase tracking-widest transition-colors">Terms</a>
                    <a href="javascript:void(0)" class="text-xs font-bold text-gray-500 hover:text-white uppercase tracking-widest transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
