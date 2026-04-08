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
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #1d5c42; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #c2a265; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-[#f9f8f4] font-sans antialiased text-gray-800 selection:bg-[#c2a265] selection:text-white min-h-screen flex flex-col">

    {{-- ==========================================
         🌟 THE PERFECT STICKY ISLAND NAVBAR 🌟
         ========================================== --}}
    <header class="sticky top-0 z-[100] w-full flex justify-center pointer-events-none transition-all duration-300"
            x-data="{ scrolled: false }"
            @scroll.window="scrolled = (window.pageYOffset > 10)"
            :class="scrolled ? 'pt-2 pb-2 bg-white/40 backdrop-blur-xl border-b border-gray-200/50 shadow-sm' : 'pt-4 pb-1 bg-transparent border-b border-transparent'">

        <nav class="pointer-events-auto w-[98%] max-w-[1500px] 2xl:max-w-[1600px] transition-all duration-500 ease-out rounded-full flex items-center justify-between gap-4 xl:gap-6 border"
             :class="scrolled ? 'bg-white/95 border-gray-200/80 shadow-[0_8px_30px_rgba(0,0,0,0.08)] py-2 px-4 xl:px-6' : 'bg-white/80 backdrop-blur-2xl border-white/60 shadow-[0_4px_20px_rgba(0,0,0,0.04)] py-3 px-5 lg:px-8'">

            <div x-data="{ mobileMenuOpen: false }" class="flex items-center justify-between w-full">

                {{-- 1. Logo (Left Section) --}}
                <div class="flex items-center justify-start shrink-0">
                    <a href="{{ url('/') }}" class="group flex items-center gap-3 text-2xl font-black tracking-tight text-[#0f172a] transition duration-500 hover:opacity-90">
                        <div class="bg-gradient-to-tr from-[#1d5c42] to-[#2a7a5a] p-2 rounded-[14px] text-white shadow-md group-hover:rotate-6 group-hover:scale-105 transition-all duration-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        {{-- التعديل هنا: إظهار .com دائماً باللون المعتمد للموقع --}}
                        <span>Mazraa<span class="text-[#c2a265]">.com</span></span>
                    </a>
                </div>

                {{-- 2. Desktop Links (Center Section) --}}
                <div class="hidden lg:flex items-center justify-center flex-1 min-w-0 mx-4">
                    <div class="flex items-center gap-2 xl:gap-4 overflow-x-auto no-scrollbar max-w-full px-2 py-1">
                        @php
                            $linkClass = "shrink-0 px-4 py-2 rounded-full text-[14px] xl:text-[15px] font-semibold text-gray-500 hover:text-[#1d5c42] hover:bg-gray-50/80 transition-all duration-300 whitespace-nowrap";
                            $activeClass = "text-[#1d5c42] bg-emerald-50/80 shadow-sm ring-1 ring-emerald-100/50";
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
                <div class="hidden lg:flex items-center justify-end gap-3 xl:gap-4 shrink-0">
                    @auth
                        @php
                            $role = Auth::user()->role;
                            $dashboardLink = match($role) {
                                'admin' => '/admin',
                                'farm_owner' => '/owner/dashboard',
                                'supply_company' => '/supplies/dashboard',
                                'transport_company' => '/transport/dashboard',
                                'supply_driver' => '/driver/supply/dashboard',
                                'transport_driver' => '/driver/transport/dashboard',
                                default => '/dashboard'
                            };
                            $isBusiness = $role !== 'user';
                        @endphp

                        <a href="{{ url($dashboardLink) }}" class="px-4 py-2.5 xl:px-6 xl:py-2.5 text-[13px] xl:text-sm font-black text-[#1d5c42] bg-emerald-50/80 rounded-full hover:bg-emerald-100 hover:shadow-md transition-all duration-300 border border-emerald-200/60 whitespace-nowrap">
                            {{ $isBusiness ? 'Dashboard' : 'My Profile' }}
                        </a>

                        <div class="h-6 w-px bg-gray-200 mx-1"></div>

                        

                        <x-notification-bell />

                        <div class="relative" x-data="{ profileOpen: false }" @click.away="profileOpen = false">
                            <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 focus:outline-none group">
                                <div class="text-right hidden xl:block">
                                    <p class="text-[15px] font-black text-gray-800 leading-none group-hover:text-[#1d5c42] transition-colors">{{ explode(' ', Auth::user()->name)[0] }}</p>
                                    <p class="text-[10px] font-black text-[#c2a265] uppercase tracking-widest mt-1">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                                </div>
                                <div class="w-10 h-10 xl:w-11 xl:h-11 shrink-0 rounded-full bg-gradient-to-tr from-[#1d5c42] to-[#c2a265] text-white flex items-center justify-center text-lg font-black shadow-md border-[1.5px] border-white ring-2 ring-transparent group-hover:ring-[#c2a265]/50 transform transition duration-300">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </button>

                            <div x-show="profileOpen" x-transition.opacity.duration.200ms x-cloak class="absolute right-0 top-full mt-4 w-52 bg-white/95 backdrop-blur-xl rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100 py-2 z-50 overflow-hidden">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-5 py-3 text-sm font-bold text-red-500 hover:bg-red-50 transition-colors text-left group">
                                        <div class="p-1.5 bg-red-100/80 rounded-md group-hover:scale-110 transition-transform"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg></div>
                                        Secure Log Out
                                    </button>
                                </form>
                            </div>
                        </div>

                    @else
                        <div class="relative" x-data="{ partnerOpen: false }" @click.away="partnerOpen = false">
                            <button @click="partnerOpen = !partnerOpen" class="inline-flex items-center gap-2 px-3 py-2.5 xl:px-5 xl:py-2.5 text-[13px] xl:text-sm font-bold text-[#c2a265] hover:text-yellow-700 hover:bg-amber-50/80 rounded-full transition-all focus:outline-none whitespace-nowrap border border-transparent hover:border-[#c2a265]/30">
                                <svg class="w-4 h-4 hidden xl:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span>Partner Portal</span>
                                <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-180': partnerOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="partnerOpen" x-transition.opacity.duration.200ms x-cloak class="absolute right-0 top-full mt-4 w-72 bg-white/95 backdrop-blur-xl rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100 py-3 z-50 overflow-hidden">
                                <div class="px-5 pb-2 pt-1 text-[11px] font-black text-gray-400 uppercase tracking-widest">Farm Owners</div>
                                <a href="{{ route('partner.register') }}" class="flex items-center gap-3 px-5 py-3 text-[15px] font-bold text-[#1d5c42] hover:bg-green-50 transition-colors group">
                                    <div class="p-1.5 bg-green-100 text-green-600 rounded-lg group-hover:bg-[#1d5c42] group-hover:text-white transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg></div>
                                    List Your Farm (Sign Up)
                                </a>
                                <a href="{{ route('portal.login') }}" class="flex items-center gap-3 px-5 py-3 text-[15px] font-bold text-gray-700 hover:bg-gray-50 transition-colors group">
                                    <div class="p-1.5 bg-gray-100 text-gray-600 rounded-lg group-hover:bg-gray-600 group-hover:text-white transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg></div>
                                    Owner Login
                                </a>

                                <div class="border-t border-gray-100 my-2"></div>

                                <div class="px-5 pb-2 pt-1 text-[11px] font-black text-gray-400 uppercase tracking-widest">B2B Companies</div>
                                <a href="{{ route('supply-company.login') }}" class="flex items-center gap-3 px-5 py-3 text-[15px] font-bold text-gray-700 hover:bg-indigo-50 transition-colors group">
                                    <div class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-500 group-hover:text-white transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></div>
                                    Supply Company Login
                                </a>
                                <a href="{{ route('transport-company.login') }}" class="flex items-center gap-3 px-5 py-3 text-[15px] font-bold text-gray-700 hover:bg-rose-50 transition-colors group">
                                    <div class="p-1.5 bg-rose-50 text-rose-600 rounded-lg group-hover:bg-rose-500 group-hover:text-white transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
                                    Transport Company Login
                                </a>

                                <div class="border-t border-gray-100 my-2"></div>

                                <div class="px-5 pb-2 pt-1 text-[11px] font-black text-gray-400 uppercase tracking-widest">Fleet & Logistics</div>
                                <a href="{{ route('transport-driver.login') }}" class="flex items-center gap-3 px-5 py-3 text-[15px] font-bold text-gray-700 hover:bg-amber-50 transition-colors group">
                                    <div class="p-1.5 bg-amber-50 text-amber-600 rounded-lg group-hover:bg-amber-500 group-hover:text-white transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg></div>
                                    Transport Driver
                                </a>
                                <a href="{{ route('supply-driver.login') }}" class="flex items-center gap-3 px-5 py-3 text-[15px] font-bold text-gray-700 hover:bg-teal-50 transition-colors group">
                                    <div class="p-1.5 bg-teal-50 text-teal-600 rounded-lg group-hover:bg-teal-500 group-hover:text-white transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg></div>
                                    Supply Driver
                                </a>
                            </div>
                        </div>

                        <div class="h-6 w-px bg-gray-200 mx-1"></div>

                        <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-2.5 xl:px-4 xl:py-2.5 text-[13px] xl:text-sm font-bold text-gray-600 hover:text-[#1d5c42] rounded-full transition-all focus:outline-none hover:bg-gray-100 whitespace-nowrap">
                            Log In
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2.5 xl:px-7 xl:py-2.5 text-[13px] xl:text-sm font-black text-white bg-gradient-to-r from-[#1d5c42] to-[#154230] rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 focus:outline-none whitespace-nowrap">
                            Sign Up
                        </a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <div class="lg:hidden flex items-center shrink-0">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-800 p-2 rounded-full hover:bg-gray-100 focus:outline-none transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Dropdown Menu --}}
            <div x-show="mobileMenuOpen" x-collapse x-cloak class="lg:hidden mt-4 border-t border-gray-100 pt-4 pb-4 space-y-2">
                @if (Auth::check() && Auth::user()->role === 'admin')
                    <a href="{{ url('/') }}" class="block px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:bg-gray-50 hover:text-[#1d5c42]">Home</a>
                    <a href="{{ route('admin.farms.index') }}" class="block px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:bg-gray-50 hover:text-[#1d5c42]">Manage Farms</a>
                @else
                    <a href="{{ url('/') }}" class="block px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:bg-gray-50 hover:text-[#1d5c42]">Home</a>
                    <a href="{{ route('explore') }}" class="block px-4 py-3 rounded-2xl text-base font-black text-[#1d5c42] bg-green-50">Explore Farms</a>

                    @auth
                        <a href="{{ route('bookings.my_bookings') }}" class="block px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:bg-gray-50 hover:text-[#1d5c42]">Bookings</a>
                        <a href="{{ route('favorites.index') }}" class="block px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:bg-gray-50 hover:text-[#1d5c42]">Favorites</a>
                        <a href="{{ route('supplies.market') }}" class="block px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:bg-gray-50 hover:text-[#1d5c42]">Market</a>
                        <a href="{{ route('transports.index') }}" class="block px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:bg-gray-50 hover:text-[#1d5c42]">Transport</a>
                    @endauth

                    <a href="{{ route('about') }}" class="block px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:bg-gray-50 hover:text-[#1d5c42]">About</a>
                    <a href="{{ route('contact') }}" class="block px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:bg-gray-50 hover:text-[#1d5c42]">Contact</a>
                @endif

                <div class="border-t border-gray-100 mt-4 pt-4 px-2">
                    @auth
                        <a href="{{ url($dashboardLink) }}" class="block px-4 py-4 rounded-2xl text-base font-black text-white bg-[#1d5c42] text-center shadow-md">Go to Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full text-center px-4 py-4 rounded-2xl text-base font-bold text-red-500 bg-red-50">Log out</button>
                        </form>
                    @else
                        <div class="mb-4 bg-amber-50/50 rounded-2xl p-3 border border-amber-100/50">
                            <div class="text-xs font-black text-[#c2a265] uppercase tracking-widest px-2 mb-3 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> For Business Partners</div>

                            <a href="{{ route('partner.register') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-[#1d5c42] hover:bg-green-50">List Your Farm (Sign Up)</a>
                            <a href="{{ route('portal.login') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-700 hover:bg-gray-100">Owner Login</a>

                            <div class="border-t border-amber-100 my-2 mx-2"></div>
                            <a href="{{ route('supply-company.login') }}" class="block px-4 py-3 rounded-xl text-sm font-bold text-indigo-700 hover:bg-indigo-50">Supply Company Login</a>
                            <a href="{{ route('transport-company.login') }}" class="block px-4 py-3 rounded-xl text-sm font-bold text-rose-700 hover:bg-rose-50">Transport Company Login</a>
                        </div>

                        <div class="mb-2 px-2">
                            <div class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> For Customers</div>
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('login') }}" class="block px-3 py-4 rounded-2xl text-sm font-bold text-gray-700 bg-gray-100 border border-gray-200 text-center hover:bg-gray-200">Log In</a>
                                <a href="{{ route('register') }}" class="block px-3 py-4 rounded-2xl text-sm font-black text-white bg-[#1d5c42] text-center shadow-md hover:bg-[#154230]">Sign Up</a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    {{-- Flash Messages --}}
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" class="fixed top-24 right-5 z-[110] flex flex-col gap-3 pointer-events-none">
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

    <main class="flex-1 w-full flex flex-col relative z-10">
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
            class="relative bg-[#020617] text-white pt-20 pb-12 overflow-hidden border-t border-white/10 z-20 mt-auto">

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

                {{-- Column 1: Brand & About --}}
                <div class="space-y-6 transition-all duration-1000 ease-out transform" :class="showFooter ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group inline-flex">
                        <div class="bg-gradient-to-br from-[#1d5c42] to-[#154230] p-2.5 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-500 border border-white/10">
                            <svg class="w-6 h-6 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <span class="text-3xl font-black text-white tracking-tighter">Mazraa<span class="text-[#c2a265]">.com</span></span>
                    </a>
                    <p class="text-base font-medium text-gray-400 leading-relaxed max-w-sm">
                        The ultimate luxury ecosystem. We bridge the gap between premium farm stays, reliable supply chains, and safe transport.
                    </p>
                </div>

                {{-- Column 2: Quick Links --}}
                <div class="transition-all duration-1000 delay-200 ease-out transform" :class="showFooter ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                    <h3 class="text-sm font-black text-white uppercase tracking-[0.2em] mb-6">Quick Links</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('explore') }}" class="text-base font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Explore Escapes</a></li>
                        <li><a href="{{ route('supplies.market') }}" class="text-base font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Supply Market</a></li>
                        <li><a href="{{ route('about') }}" class="text-base font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Our Story</a></li>
                        <li><a href="{{ route('contact') }}" class="text-base font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Support Center</a></li>
                    </ul>
                </div>

                {{-- Column 3: Partner Portals --}}
                <div class="transition-all duration-1000 delay-300 ease-out transform" :class="showFooter ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                    <h3 class="text-sm font-black text-white uppercase tracking-[0.2em] mb-6">Partner Network</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('portal.login') }}" class="text-base font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Owner Login</a></li>
                        <li><a href="{{ route('partner.register') }}" class="text-base font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Register as Owner</a></li>
                        <li><a href="{{ route('transport-driver.login') }}" class="text-base font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Transport Dispatch</a></li>
                        <li><a href="{{ route('supply-driver.login') }}" class="text-base font-medium text-gray-400 hover:text-[#c2a265] hover:translate-x-2 inline-flex items-center transition-all duration-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 mr-3"></span> Supply Logistics</a></li>
                    </ul>
                </div>

                {{-- Column 4: Newsletter --}}
                <div class="transition-all duration-1000 delay-500 ease-out transform" :class="showFooter ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                    <h3 class="text-sm font-black text-white uppercase tracking-[0.2em] mb-6">Stay Connected</h3>
                    <p class="text-base text-gray-400 font-medium mb-6">Subscribe for exclusive luxury farm drops and platform updates.</p>

                    <form x-data="{ subscribed: false }" @submit.prevent="subscribed = true" class="flex flex-col space-y-3 relative group">
                        <div x-show="!subscribed" x-transition.opacity class="relative w-full">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-[#1d5c42] to-[#c2a265] rounded-2xl blur opacity-0 group-hover:opacity-30 transition duration-500"></div>
                            <div class="relative flex bg-[#0f172a] p-1.5 rounded-2xl border border-white/10 focus-within:border-[#c2a265]/50 transition-colors">
                                <input type="email" placeholder="Enter your email..." required class="w-full bg-transparent border-none text-white text-base rounded-xl pl-4 pr-2 focus:ring-0 focus:outline-none placeholder-gray-500">
                                <button type="submit" class="bg-gradient-to-r from-[#1d5c42] to-[#154230] hover:from-[#154230] hover:to-[#0a1c14] text-white p-3 rounded-xl transition-all shadow-md hover:shadow-lg hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div x-show="subscribed" x-transition.opacity x-cloak class="p-4 rounded-2xl bg-green-500/10 border border-green-500/20 text-center flex flex-col items-center justify-center">
                            <svg class="w-6 h-6 text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-green-400 font-bold text-base">Successfully Subscribed!</span>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bottom Footer Line --}}
            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 transition-all duration-1000 delay-700 ease-out transform" :class="showFooter ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                <p class="text-sm font-black text-gray-500 tracking-wider">
                    Anas Alnsour - Adnan Awad - Aktham Zayed - Hafez Fares - Malek Al-sarayerah
                </p>
                <div class="flex space-x-8">
                    <a href="javascript:void(0)" class="text-sm font-bold text-gray-500 hover:text-white uppercase tracking-widest transition-colors">Privacy</a>
                    <a href="javascript:void(0)" class="text-sm font-bold text-gray-500 hover:text-white uppercase tracking-widest transition-colors">Terms</a>
                    <a href="javascript:void(0)" class="text-sm font-bold text-gray-500 hover:text-white uppercase tracking-widest transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>


