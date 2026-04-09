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

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #1d5c42; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #c2a265; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* ── Navbar pill ── */
        .nav-pill {
            transition: background 0.4s, box-shadow 0.4s, padding 0.4s, border-color 0.4s;
        }

        /* ── Active nav link indicator ── */
        .nav-active {
            color: #1d5c42;
            background: rgba(29,92,66,.08);
            box-shadow: inset 0 0 0 1px rgba(29,92,66,.15);
        }

        /* ── Glow orb ── */
        @keyframes orb-float {
            0%,100% { transform: translateY(0) scale(1); opacity:.18; }
            50%      { transform: translateY(-18px) scale(1.06); opacity:.26; }
        }
        .orb { animation: orb-float 7s ease-in-out infinite; }
        .orb-delay { animation-delay: 3.5s; }

        /* ── Watermark ── */
        .footer-wm {
            font-size: clamp(80px, 18vw, 260px);
            line-height: 1;
            font-weight: 900;
            letter-spacing: -.05em;
            user-select: none;
            pointer-events: none;
            white-space: nowrap;
        }
    </style>
</head>

<body class="bg-[#f9f8f4] font-sans antialiased text-gray-800 selection:bg-[#c2a265]/30 selection:text-[#1d5c42] min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">

{{-- ╔══════════════════════════════════════════════╗
     ║           STICKY ISLAND NAVBAR               ║
     ╚══════════════════════════════════════════════╝ --}}
<header
    x-data="{ scrolled: false }"
    @scroll.window="scrolled = window.scrollY > 12"
    :class="scrolled
        ? 'pt-2 pb-2 bg-white/60 backdrop-blur-2xl border-b border-gray-200/40 shadow-sm'
        : 'pt-4 pb-1 bg-transparent border-b border-transparent'"
    class="sticky top-0 z-[500] w-full flex justify-center pointer-events-none transition-all duration-300">

    <nav
        :class="scrolled
            ? 'bg-white/95 border-gray-200/70 shadow-[0_8px_32px_rgba(0,0,0,0.07)] py-2 px-4 xl:px-6'
            : 'bg-white/80 backdrop-blur-2xl border-white/50 shadow-[0_4px_24px_rgba(0,0,0,0.04)] py-3 px-5 lg:px-8'"
        class="nav-pill pointer-events-auto w-[97%] max-w-[1500px] 2xl:max-w-[1620px] rounded-[2rem] flex items-center justify-between gap-3 border relative z-[501]">

        {{-- ── 1. LOGO ── --}}
        <a href="{{ url('/') }}" class="group flex items-center gap-2.5 shrink-0 select-none">
            <div class="bg-gradient-to-tr from-[#1d5c42] to-[#2a7a5a] p-2 rounded-[13px] text-white shadow group-hover:rotate-6 group-hover:scale-105 transition-all duration-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <span class="text-[1.35rem] font-black tracking-tight text-gray-900 leading-none">
                Mazraa<span class="text-[#c2a265]">.com</span>
            </span>
        </a>

        {{-- ── 2. DESKTOP LINKS ── --}}
        @php
            $lnk = "shrink-0 px-3.5 py-2 rounded-full text-[13.5px] xl:text-[14.5px] font-semibold text-gray-500 hover:text-[#1d5c42] hover:bg-emerald-50/70 transition-all duration-200 whitespace-nowrap";
            $act = "nav-active";
        @endphp

        <div class="hidden lg:flex items-center flex-1 min-w-0 justify-center mx-2">
            <div class="flex items-center gap-1 xl:gap-2 overflow-x-auto no-scrollbar px-1 py-0.5">
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <a href="{{ url('/') }}" class="{{ $lnk }} {{ request()->is('/') ? $act : '' }}">Home</a>
                    <a href="{{ route('admin.farms.index') }}" class="{{ $lnk }} {{ request()->routeIs('admin.farms.*') ? $act : '' }}">Manage Farms</a>
                    <a href="{{ route('admin.supplies.index') }}" class="{{ $lnk }} {{ request()->routeIs('admin.supplies.*') ? $act : '' }}">Supplies</a>
                    <a href="{{ route('admin.contacts.index') }}" class="{{ $lnk }} {{ request()->routeIs('admin.contacts.*') ? $act : '' }}">Messages</a>
                @else
                    <a href="{{ url('/') }}" class="{{ $lnk }} {{ request()->is('/') ? $act : '' }}">Home</a>
                    <a href="{{ route('explore') }}" class="{{ $lnk }} {{ request()->routeIs('explore') ? $act : '' }}">Explore</a>
                    @auth
                        <a href="{{ route('bookings.my_bookings') }}" class="{{ $lnk }} {{ request()->routeIs('bookings.*') ? $act : '' }}">Bookings</a>
                        <a href="{{ route('favorites.index') }}" class="{{ $lnk }} {{ request()->routeIs('favorites.*') ? $act : '' }}">Favorites</a>
                        <a href="{{ route('supplies.market') }}" class="{{ $lnk }} {{ request()->routeIs('supplies.*') ? $act : '' }}">Market</a>
                        <a href="{{ route('transports.index') }}" class="{{ $lnk }} {{ request()->routeIs('transports.*') ? $act : '' }}">Transport</a>
                    @endauth
                    <a href="{{ route('about') }}" class="{{ $lnk }} {{ request()->routeIs('about') ? $act : '' }}">About</a>
                    <a href="{{ route('contact') }}" class="{{ $lnk }} {{ request()->routeIs('contact') ? $act : '' }}">Contact</a>
                @endif
            </div>
        </div>

        {{-- ── 3. RIGHT SECTION (Auth / Guest) ── --}}
        <div class="hidden lg:flex items-center gap-2.5 xl:gap-3 shrink-0">
            @auth
                @php
                    $role = Auth::user()->role;
                    $dash = match($role) {
                        'admin'             => '/admin',
                        'farm_owner'        => '/owner/dashboard',
                        'supply_company'    => '/supplies/dashboard',
                        'transport_company' => '/transport/dashboard',
                        'supply_driver'     => '/driver/supply/dashboard',
                        'transport_driver'  => '/driver/transport/dashboard',
                        default             => '/dashboard',
                    };
                    $isBiz = $role !== 'user';
                @endphp

                <a href="{{ url($dash) }}" class="px-4 py-2 text-[13px] xl:text-sm font-bold text-[#1d5c42] bg-emerald-50 border border-emerald-200/60 rounded-full hover:bg-emerald-100 hover:shadow transition-all whitespace-nowrap">
                    {{ $isBiz ? 'Dashboard' : 'My Profile' }}
                </a>
                <span class="h-5 w-px bg-gray-200"></span>

                <x-notification-bell />

                <div class="relative" x-data="{ openProfile: false }" @click.away="openProfile = false">
                    <button @click="openProfile = !openProfile" class="flex items-center gap-2.5 focus:outline-none group">
                        <div class="hidden xl:block text-right leading-tight">
                            <p class="text-[14px] font-black text-gray-800 group-hover:text-[#1d5c42] transition-colors">{{ explode(' ', Auth::user()->name)[0] }}</p>
                            <p class="text-[9.5px] font-black text-[#c2a265] uppercase tracking-widest mt-0.5">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                        </div>
                        <div class="w-9 h-9 xl:w-10 xl:h-10 rounded-full bg-gradient-to-tr from-[#1d5c42] to-[#c2a265] text-white flex items-center justify-center text-base font-black shadow border-2 border-white ring-2 ring-transparent group-hover:ring-[#c2a265]/40 transition-all">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </button>

                    <div x-show="openProfile" x-cloak x-transition.duration.150ms class="absolute right-0 top-full mt-3 w-52 bg-white rounded-2xl shadow-[0_20px_48px_rgba(0,0,0,0.15)] border border-gray-100 py-2 z-[999]">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 transition-colors group">
                                <span class="p-1.5 bg-red-100/80 rounded-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                </span>
                                Secure Log Out
                            </button>
                        </form>
                    </div>
                </div>

            @else
                <div class="relative" x-data="{ openPartner: false }" @click.away="openPartner = false">
                    <button @click="openPartner = !openPartner" class="inline-flex items-center gap-1.5 px-3.5 py-2 xl:px-4 xl:py-2 text-[13px] xl:text-sm font-bold text-[#c2a265] hover:text-yellow-700 hover:bg-amber-50 rounded-full border border-transparent hover:border-[#c2a265]/30 transition-all focus:outline-none whitespace-nowrap">
                        <svg class="w-4 h-4 hidden xl:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Partner Portal
                        <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="{'rotate-180': openPartner}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="openPartner" x-cloak x-transition.duration.150ms class="absolute right-0 top-full mt-3 w-72 bg-white rounded-3xl shadow-[0_24px_56px_rgba(0,0,0,0.15)] border border-gray-100 py-3 z-[999]">
                        {{-- Farm Owners --}}
                        <p class="px-5 pt-1 pb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Farm Owners</p>
                        <a href="{{ route('partner.register') }}" class="flex items-center gap-3 px-5 py-2.5 text-[14px] font-semibold text-gray-700 hover:bg-green-50 transition-colors group">
                            <span class="p-1.5 bg-green-100 text-green-600 rounded-lg group-hover:scale-110 transition-transform"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg></span>
                            List Your Farm (Sign Up)
                        </a>
                        <a href="{{ route('portal.login') }}" class="flex items-center gap-3 px-5 py-2.5 text-[14px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors group">
                            <span class="p-1.5 bg-gray-100 text-gray-600 rounded-lg group-hover:scale-110 transition-transform"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg></span>
                            Owner Login
                        </a>

                        <div class="border-t border-gray-100 my-2 mx-4"></div>

                        {{-- B2B Companies --}}
                        <p class="px-5 pb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">B2B Companies</p>
                        <a href="{{ route('supply-company.login') }}" class="flex items-center gap-3 px-5 py-2.5 text-[14px] font-semibold text-gray-700 hover:bg-indigo-50 transition-colors group">
                            <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg group-hover:scale-110 transition-transform"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></span>
                            Supply Company
                        </a>
                        <a href="{{ route('transport-company.login') }}" class="flex items-center gap-3 px-5 py-2.5 text-[14px] font-semibold text-gray-700 hover:bg-rose-50 transition-colors group">
                            <span class="p-1.5 bg-rose-50 text-rose-600 rounded-lg group-hover:scale-110 transition-transform"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></span>
                            Transport Company
                        </a>

                        <div class="border-t border-gray-100 my-2 mx-4"></div>

                        {{-- Fleet & Logistics --}}
                        <p class="px-5 pb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Fleet & Logistics</p>
                        <a href="{{ route('transport-driver.login') }}" class="flex items-center gap-3 px-5 py-2.5 text-[14px] font-semibold text-gray-700 hover:bg-amber-50 transition-colors group">
                            <span class="p-1.5 bg-amber-50 text-amber-600 rounded-lg group-hover:scale-110 transition-transform"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg></span>
                            Transport Driver
                        </a>
                        <a href="{{ route('supply-driver.login') }}" class="flex items-center gap-3 px-5 py-2.5 text-[14px] font-semibold text-gray-700 hover:bg-teal-50 transition-colors group">
                            <span class="p-1.5 bg-teal-50 text-teal-600 rounded-lg group-hover:scale-110 transition-transform"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg></span>
                            Supply Driver
                        </a>
                    </div>
                </div>

                <span class="h-5 w-px bg-gray-200"></span>

                <a href="{{ route('login') }}" class="px-3.5 py-2 text-[13px] xl:text-sm font-bold text-gray-600 hover:text-[#1d5c42] rounded-full hover:bg-gray-100 transition-all whitespace-nowrap">Log In</a>
                <a href="{{ route('register') }}" class="px-5 py-2 xl:px-6 text-[13px] xl:text-sm font-black text-white bg-gradient-to-r from-[#1d5c42] to-[#154230] rounded-full shadow hover:shadow-lg hover:-translate-y-0.5 transition-all whitespace-nowrap">Sign Up</a>
            @endauth
        </div>

        {{-- ── Mobile Toggle ── --}}
        <button @click="mobileMenuOpen = true" class="lg:hidden p-2 rounded-full hover:bg-gray-100 text-gray-700 transition-colors focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </nav>
</header>

{{-- ╔══════════════════════════════════════════════╗
     ║        MOBILE SLIDE-OVER DRAWER MENU         ║
     ╚══════════════════════════════════════════════╝ --}}
<div x-show="mobileMenuOpen" class="fixed inset-0 z-[9999] lg:hidden" x-cloak>
    {{-- Backdrop --}}
    <div x-show="mobileMenuOpen"
         x-transition.opacity.duration.300ms
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="mobileMenuOpen = false"></div>

    {{-- Panel --}}
    <div x-show="mobileMenuOpen"
         x-transition:enter="transform transition ease-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="fixed right-0 top-0 h-full w-[85%] max-w-sm bg-white shadow-2xl flex flex-col overflow-hidden">

        <div class="flex items-center justify-between px-6 py-6 border-b border-slate-100">
            <span class="text-2xl font-black text-[#1d5c42] tracking-tighter">Mazraa<span class="text-[#c2a265]">.com</span></span>
            <button @click="mobileMenuOpen = false" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto py-6 space-y-8 no-scrollbar">
            {{-- Main Navigation --}}
            <section class="px-6 space-y-3">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Quick Navigation</h3>
                <div class="grid grid-cols-1 gap-2 text-lg font-bold text-slate-900">
                    <a href="{{ url('/') }}" class="py-2 hover:text-[#1d5c42]">Home</a>
                    @if (Auth::check() && Auth::user()->role === 'admin')
                        <a href="{{ route('admin.farms.index') }}" class="py-2 hover:text-[#1d5c42]">Manage Farms</a>
                        <a href="{{ route('admin.supplies.index') }}" class="py-2 hover:text-[#1d5c42]">Supplies</a>
                    @else
                        <a href="{{ route('explore') }}" class="py-2 text-[#1d5c42]">Explore Escapes</a>
                        @auth
                            <a href="{{ route('bookings.my_bookings') }}" class="py-2 hover:text-[#1d5c42]">My Bookings</a>
                            <a href="{{ route('favorites.index') }}" class="py-2 hover:text-[#1d5c42]">Favorites</a>
                        @endauth
                    @endif
                    <a href="{{ route('about') }}" class="py-2 text-slate-500">About Us</a>
                    <a href="{{ route('contact') }}" class="py-2 text-slate-500">Contact Support</a>
                </div>
            </section>

            {{-- Partner Section --}}
            <section class="bg-emerald-50/50 px-6 py-8 border-y border-emerald-100">
                <h3 class="text-[10px] font-black text-emerald-700 uppercase tracking-widest mb-4">Partner Portals</h3>
                <div class="space-y-4">
                    @guest
                        <a href="{{ route('partner.register') }}" class="flex items-center justify-center w-full py-4 bg-[#1d5c42] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg">List Your Farm</a>
                        <a href="{{ route('portal.login') }}" class="block text-sm font-bold text-[#1d5c42] italic text-center border border-emerald-200 py-3 rounded-2xl bg-white hover:bg-emerald-50 transition-colors">Owner / Admin Login</a>
                    @endguest
                    <div class="grid grid-cols-1 gap-3 pt-2">
                        <a href="{{ route('supply-company.login') }}" class="flex items-center justify-between text-sm font-bold text-slate-600 bg-white p-3 rounded-xl border border-emerald-100 shadow-sm">Supply Company <span class="text-indigo-400">→</span></a>
                        <a href="{{ route('transport-company.login') }}" class="flex items-center justify-between text-sm font-bold text-slate-600 bg-white p-3 rounded-xl border border-emerald-100 shadow-sm">Transport Company <span class="text-rose-400">→</span></a>
                        <a href="{{ route('transport-driver.login') }}" class="flex items-center justify-between text-sm font-bold text-slate-600 bg-white p-3 rounded-xl border border-emerald-100 shadow-sm">Transport Driver <span class="text-amber-400">→</span></a>
                        <a href="{{ route('supply-driver.login') }}" class="flex items-center justify-between text-sm font-bold text-slate-600 bg-white p-3 rounded-xl border border-emerald-100 shadow-sm">Supply Driver <span class="text-teal-400">→</span></a>
                    </div>
                </div>
            </section>

            {{-- Identity / Auth --}}
            <section class="px-6 pb-12">
                @auth
                    <div class="flex items-center gap-4 mb-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-tr from-[#1d5c42] to-[#c2a265] flex items-center justify-center text-white font-black text-xl">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="font-black text-slate-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] font-bold text-[#c2a265] uppercase tracking-widest">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <a href="{{ url($dash ?? '/dashboard') }}" class="block text-center w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest">Open Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-slate-100">
                            @csrf
                            <button type="submit" class="w-full text-center py-4 text-red-500 rounded-2xl font-black text-xs uppercase tracking-widest bg-red-50 mt-2">Sign Out</button>
                        </form>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('login') }}" class="w-full py-4 text-center bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl">Customer Login</a>
                        <a href="{{ route('register') }}" class="w-full py-4 text-center border-2 border-slate-900 text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest">Create Account</a>
                    </div>
                @endauth
            </section>
        </div>
    </div>
</div>

{{-- ╔══════════════════════════════════════════════╗
     ║               FLASH MESSAGES                 ║
     ╚══════════════════════════════════════════════╝ --}}
<div x-data="{ show: true }"
     x-init="setTimeout(() => show = false, 7000)"
     class="fixed top-20 right-4 z-[9999] flex flex-col gap-2.5 pointer-events-none max-w-xs w-full">

    @php
        $alerts = [];
        if (session('success')) $alerts[] = ['type' => 'success', 'title' => 'Success',   'msg' => session('success'), 'color' => 'green',  'icon' => 'M5 13l4 4L19 7'];
        if (session('error'))   $alerts[] = ['type' => 'error',   'title' => 'Error',     'msg' => session('error'),   'color' => 'red',    'icon' => 'M6 18L18 6M6 6l12 12'];
    @endphp

    @foreach($alerts as $a)
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-6"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0 translate-x-6"
         class="pointer-events-auto bg-white border border-{{ $a['color'] }}-200 rounded-2xl
                shadow-[0_8px_32px_rgba(0,0,0,0.15)] p-4 flex items-start gap-3">
        <span class="shrink-0 p-1.5 bg-{{ $a['color'] }}-100 rounded-full text-{{ $a['color'] }}-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $a['icon'] }}"/></svg>
        </span>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-black text-gray-900 uppercase tracking-wide">{{ $a['title'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $a['msg'] }}</p>
        </div>
        <button @click="show = false" class="shrink-0 text-gray-300 hover:text-gray-600 transition-colors mt-0.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endforeach
</div>

{{-- ╔══════════════════════════════════════════════╗
     ║               MAIN CONTENT                   ║
     ╚══════════════════════════════════════════════╝ --}}
<main class="flex-1 w-full flex flex-col relative z-10">
    @yield('content')
</main>


{{-- ╔══════════════════════════════════════════════╗
     ║          ULTRA MODERN FOOTER                 ║
     ╚══════════════════════════════════════════════╝ --}}
<footer
    x-data="{ visible: false }"
    x-init="new IntersectionObserver(([e]) => { if(e.isIntersecting) visible = true; }, { threshold: 0.08 }).observe($el);"
    class="relative bg-[#030712] text-white pt-20 pb-10 overflow-hidden border-t border-white/[0.06] z-20 mt-auto">

    {{-- Glow orbs --}}
    <div class="orb absolute -top-12 left-1/4 w-80 h-80 bg-[#1d5c42]/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="orb orb-delay absolute -bottom-12 right-1/4 w-80 h-80 bg-[#c2a265]/12 rounded-full blur-[100px] pointer-events-none"></div>

    {{-- Background watermark --}}
    <div class="absolute bottom-0 inset-x-0 flex justify-center overflow-hidden opacity-[0.025] pointer-events-none select-none">
        <span class="footer-wm text-white">MAZRAA</span>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 xl:gap-14 mb-16">
            {{-- Brand --}}
            <div class="space-y-5 transition-all duration-700 ease-out" :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group">
                    <div class="p-2.5 bg-gradient-to-br from-[#1d5c42] to-[#0f3324] rounded-xl border border-white/10 shadow-lg group-hover:scale-110 transition-transform duration-400">
                        <svg class="w-5 h-5 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <span class="text-2xl font-black tracking-tight">Mazraa<span class="text-[#c2a265]">.com</span></span>
                </a>
                <p class="text-[15px] font-medium text-gray-400 leading-relaxed max-w-[260px]">The ultimate luxury ecosystem bridging premium farm stays, supply chains, and safe transport.</p>
            </div>
            {{-- Quick Links --}}
            <div class="transition-all duration-700 delay-150 ease-out" :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
                <h4 class="text-[11px] font-black text-white/50 uppercase tracking-[.18em] mb-5">Quick Links</h4>
                <ul class="space-y-3.5">
                    <li><a href="{{ route('explore') }}" class="group inline-flex items-center gap-2.5 text-[14.5px] font-medium text-gray-400 hover:text-[#c2a265] transition-all duration-200"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 group-hover:bg-[#c2a265] transition-colors shrink-0"></span> Explore Escapes</a></li>
                    <li><a href="{{ route('supplies.market') }}" class="group inline-flex items-center gap-2.5 text-[14.5px] font-medium text-gray-400 hover:text-[#c2a265] transition-all duration-200"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 group-hover:bg-[#c2a265] transition-colors shrink-0"></span> Supply Market</a></li>
                    <li><a href="{{ route('about') }}" class="group inline-flex items-center gap-2.5 text-[14.5px] font-medium text-gray-400 hover:text-[#c2a265] transition-all duration-200"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 group-hover:bg-[#c2a265] transition-colors shrink-0"></span> Our Story</a></li>
                </ul>
            </div>
            {{-- Network --}}
            <div class="transition-all duration-700 delay-300 ease-out" :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
                <h4 class="text-[11px] font-black text-white/50 uppercase tracking-[.18em] mb-5">Partner Network</h4>
                <ul class="space-y-3.5">
                    <li><a href="{{ route('portal.login') }}" class="group inline-flex items-center gap-2.5 text-[14.5px] font-medium text-gray-400 hover:text-[#c2a265] transition-all duration-200"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 group-hover:bg-[#c2a265] transition-colors shrink-0"></span> Owner Login</a></li>
                    <li><a href="{{ route('partner.register') }}" class="group inline-flex items-center gap-2.5 text-[14.5px] font-medium text-gray-400 hover:text-[#c2a265] transition-all duration-200"><span class="w-1.5 h-1.5 rounded-full bg-gray-700 group-hover:bg-[#c2a265] transition-colors shrink-0"></span> Register as Owner</a></li>
                </ul>
            </div>
            {{-- Newsletter --}}
            <div class="transition-all duration-700 delay-[450ms] ease-out" :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
                <h4 class="text-[11px] font-black text-white/50 uppercase tracking-[.18em] mb-5">Stay Connected</h4>
                <form x-data="{ done: false }" @submit.prevent="done = true" class="group">
                    <div x-show="!done" class="relative flex items-center bg-white/[0.05] border border-white/10 rounded-2xl p-1.5 focus-within:border-[#c2a265]/50 transition-all duration-300 hover:border-white/20">
                        <input type="email" placeholder="your@email.com" required class="flex-1 bg-transparent border-none text-white text-sm placeholder-gray-600 pl-3.5 focus:ring-0 focus:outline-none">
                        <button type="submit" class="shrink-0 bg-gradient-to-r from-[#1d5c42] to-[#154230] hover:from-[#154230] hover:to-[#0a2018] text-white p-2.5 rounded-xl transition-all hover:shadow-lg hover:scale-105">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                    <div x-show="done" x-cloak x-transition.opacity class="flex flex-col items-center justify-center gap-2 py-4 px-5 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-center">
                        <span class="text-emerald-400 font-bold text-sm">Successfully Subscribed!</span>
                    </div>
                </form>
            </div>
        </div>
        <div class="border-t border-white/[0.07] pt-7 flex flex-col sm:flex-row justify-between items-center gap-4 transition-all duration-700 delay-500 ease-out" :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5'">
            <p class="text-[12.5px] font-semibold text-gray-600 text-center sm:text-left">Anas Alnsour · Adnan Awad · Aktham Zayed · Hafez Fares · Malek Al-sarayerah</p>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
