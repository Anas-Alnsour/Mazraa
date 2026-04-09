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

        /* ── Gradient text ── */
        .gradient-brand {
            background: linear-gradient(135deg, #1d5c42 0%, #c2a265 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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

        /* ── Dropdown ── */
        .dropdown-enter { animation: dropIn .18s ease forwards; }
        @keyframes dropIn {
            from { opacity:0; transform: translateY(-6px) scale(.97); }
            to   { opacity:1; transform: translateY(0) scale(1); }
        }
    </style>
</head>

<body class="bg-[#f9f8f4] font-sans antialiased text-gray-800 selection:bg-[#c2a265]/30 selection:text-[#1d5c42] min-h-screen flex flex-col">

{{-- ╔══════════════════════════════════════════════╗
     ║           STICKY ISLAND NAVBAR               ║
     ╚══════════════════════════════════════════════╝ --}}
<header
    x-data="{ scrolled: false }"
    @scroll.window="scrolled = window.scrollY > 12"
    :class="scrolled
        ? 'pt-2 pb-2 bg-white/60 backdrop-blur-2xl border-b border-gray-200/40 shadow-sm'
        : 'pt-4 pb-1 bg-transparent border-b border-transparent'"
    class="sticky top-0 z-[100] w-full flex justify-center pointer-events-none transition-all duration-300">

    <nav
        x-data="{ mobile: false }"
        :class="scrolled
            ? 'bg-white/95 border-gray-200/70 shadow-[0_8px_32px_rgba(0,0,0,0.07)] py-2 px-4 xl:px-6'
            : 'bg-white/80 backdrop-blur-2xl border-white/50 shadow-[0_4px_24px_rgba(0,0,0,0.04)] py-3 px-5 lg:px-8'"
        class="nav-pill pointer-events-auto w-[97%] max-w-[1500px] 2xl:max-w-[1620px] rounded-[2rem] flex flex-wrap items-center justify-between gap-3 border">

        {{-- ── 1. LOGO ── --}}
        <a href="{{ url('/') }}"
           class="group flex items-center gap-2.5 shrink-0 select-none">
            <div class="bg-gradient-to-tr from-[#1d5c42] to-[#2a7a5a] p-2 rounded-[13px] text-white shadow
                        group-hover:rotate-6 group-hover:scale-105 transition-all duration-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2
                             2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0
                             011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <span class="text-[1.35rem] font-black tracking-tight text-gray-900 leading-none">
                Mazraa<span class="text-[#c2a265]">.com</span>
            </span>
        </a>

        {{-- ── 2. DESKTOP LINKS ── --}}
        @php
            $lnk = "shrink-0 px-3.5 py-2 rounded-full text-[13.5px] xl:text-[14.5px] font-semibold
                    text-gray-500 hover:text-[#1d5c42] hover:bg-emerald-50/70 transition-all duration-200 whitespace-nowrap";
            $act = "nav-active";
        @endphp

        <div class="hidden lg:flex items-center flex-1 min-w-0 justify-center mx-2">
            <div class="flex items-center gap-1 xl:gap-2 overflow-x-auto no-scrollbar px-1 py-0.5">

                @if(Auth::check() && Auth::user()->role === 'admin')
                    <a href="{{ url('/') }}"
                       class="{{ $lnk }} {{ request()->is('/') ? $act : '' }}">Home</a>
                    <a href="{{ route('admin.farms.index') }}"
                       class="{{ $lnk }} {{ request()->routeIs('admin.farms.*') ? $act : '' }}">Manage Farms</a>
                    <a href="{{ route('admin.supplies.index') }}"
                       class="{{ $lnk }} {{ request()->routeIs('admin.supplies.*') ? $act : '' }}">Supplies</a>
                    <a href="{{ route('admin.contacts.index') }}"
                       class="{{ $lnk }} {{ request()->routeIs('admin.contacts.*') ? $act : '' }}">Messages</a>
                @else
                    <a href="{{ url('/') }}"
                       class="{{ $lnk }} {{ request()->is('/') ? $act : '' }}">Home</a>
                    <a href="{{ route('explore') }}"
                       class="{{ $lnk }} {{ request()->routeIs('explore') ? $act : '' }}">Explore</a>
                    @auth
                        <a href="{{ route('bookings.my_bookings') }}"
                           class="{{ $lnk }} {{ request()->routeIs('bookings.*') ? $act : '' }}">Bookings</a>
                        <a href="{{ route('favorites.index') }}"
                           class="{{ $lnk }} {{ request()->routeIs('favorites.*') ? $act : '' }}">Favorites</a>
                        <a href="{{ route('supplies.market') }}"
                           class="{{ $lnk }} {{ request()->routeIs('supplies.*') ? $act : '' }}">Market</a>
                        <a href="{{ route('transports.index') }}"
                           class="{{ $lnk }} {{ request()->routeIs('transports.*') ? $act : '' }}">Transport</a>
                    @endauth
                    <a href="{{ route('about') }}"
                       class="{{ $lnk }} {{ request()->routeIs('about') ? $act : '' }}">About</a>
                    <a href="{{ route('contact') }}"
                       class="{{ $lnk }} {{ request()->routeIs('contact') ? $act : '' }}">Contact</a>
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

                {{-- Dashboard pill --}}
                <a href="{{ url($dash) }}"
                   class="px-4 py-2 text-[13px] xl:text-sm font-bold text-[#1d5c42] bg-emerald-50
                          border border-emerald-200/60 rounded-full hover:bg-emerald-100 hover:shadow
                          transition-all whitespace-nowrap">
                    {{ $isBiz ? 'Dashboard' : 'My Profile' }}
                </a>

                <span class="h-5 w-px bg-gray-200"></span>

                {{-- Notification bell --}}
                <x-notification-bell />

                {{-- Avatar dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open"
                            class="flex items-center gap-2.5 focus:outline-none group">
                        <div class="hidden xl:block text-right leading-tight">
                            <p class="text-[14px] font-black text-gray-800 group-hover:text-[#1d5c42] transition-colors">
                                {{ explode(' ', Auth::user()->name)[0] }}
                            </p>
                            <p class="text-[9.5px] font-black text-[#c2a265] uppercase tracking-widest mt-0.5">
                                {{ str_replace('_', ' ', Auth::user()->role) }}
                            </p>
                        </div>
                        <div class="w-9 h-9 xl:w-10 xl:h-10 rounded-full bg-gradient-to-tr from-[#1d5c42] to-[#c2a265]
                                    text-white flex items-center justify-center text-base font-black shadow
                                    border-2 border-white ring-2 ring-transparent group-hover:ring-[#c2a265]/40 transition-all">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </button>

                    {{-- Profile dropdown --}}
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         class="absolute right-0 top-full mt-3 w-52 bg-white rounded-2xl
                                shadow-[0_20px_48px_rgba(0,0,0,0.11)] border border-gray-100 py-2 z-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold
                                           text-red-500 hover:bg-red-50 transition-colors group">
                                <span class="p-1.5 bg-red-100/80 rounded-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0
                                                 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </span>
                                Secure Log Out
                            </button>
                        </form>
                    </div>
                </div>

            @else

                {{-- ── Partner Portal dropdown ── --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 xl:px-4 xl:py-2 text-[13px] xl:text-sm
                                   font-bold text-[#c2a265] hover:text-yellow-700 hover:bg-amber-50 rounded-full
                                   border border-transparent hover:border-[#c2a265]/30 transition-all focus:outline-none whitespace-nowrap">
                        <svg class="w-4 h-4 hidden xl:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16
                                     6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0
                                     002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Partner Portal
                        <svg class="w-3.5 h-3.5 transition-transform duration-300"
                             :class="{'rotate-180': open}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Mega dropdown --}}
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         class="absolute right-0 top-full mt-3 w-72 bg-white rounded-3xl
                                shadow-[0_24px_56px_rgba(0,0,0,0.1)] border border-gray-100 py-3 z-50">

                        {{-- Farm Owners --}}
                        <p class="px-5 pt-1 pb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Farm Owners</p>
                        @foreach([
                            ['route' => 'partner.register', 'label' => 'List Your Farm (Sign Up)', 'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z', 'color' => 'green'],
                            ['route' => 'portal.login',     'label' => 'Owner Login',               'icon' => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1', 'color' => 'gray'],
                        ] as $item)
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-5 py-2.5 text-[14px] font-semibold text-gray-700
                                  hover:bg-{{ $item['color'] }}-50 transition-colors group">
                            <span class="p-1.5 bg-{{ $item['color'] }}-100 text-{{ $item['color'] }}-600 rounded-lg
                                         group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                                </svg>
                            </span>
                            {{ $item['label'] }}
                        </a>
                        @endforeach

                        <div class="border-t border-gray-100 my-2 mx-4"></div>

                        {{-- B2B --}}
                        <p class="px-5 pb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">B2B Companies</p>
                        <a href="{{ route('supply-company.login') }}"
                           class="flex items-center gap-3 px-5 py-2.5 text-[14px] font-semibold text-gray-700 hover:bg-indigo-50 transition-colors group">
                            <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2
                                             0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </span>
                            Supply Company Login
                        </a>
                        <a href="{{ route('transport-company.login') }}"
                           class="flex items-center gap-3 px-5 py-2.5 text-[14px] font-semibold text-gray-700 hover:bg-rose-50 transition-colors group">
                            <span class="p-1.5 bg-rose-50 text-rose-600 rounded-lg group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </span>
                            Transport Company Login
                        </a>

                        <div class="border-t border-gray-100 my-2 mx-4"></div>

                        {{-- Fleet --}}
                        <p class="px-5 pb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Fleet & Logistics</p>
                        <a href="{{ route('transport-driver.login') }}"
                           class="flex items-center gap-3 px-5 py-2.5 text-[14px] font-semibold text-gray-700 hover:bg-amber-50 transition-colors group">
                            <span class="p-1.5 bg-amber-50 text-amber-600 rounded-lg group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </span>
                            Transport Driver
                        </a>
                        <a href="{{ route('supply-driver.login') }}"
                           class="flex items-center gap-3 px-5 py-2.5 text-[14px] font-semibold text-gray-700 hover:bg-teal-50 transition-colors group">
                            <span class="p-1.5 bg-teal-50 text-teal-600 rounded-lg group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0
                                             002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                            </span>
                            Supply Driver
                        </a>
                    </div>
                </div>

                <span class="h-5 w-px bg-gray-200"></span>

                <a href="{{ route('login') }}"
                   class="px-3.5 py-2 text-[13px] xl:text-sm font-bold text-gray-600 hover:text-[#1d5c42]
                          rounded-full hover:bg-gray-100 transition-all whitespace-nowrap">
                    Log In
                </a>
                <a href="{{ route('register') }}"
                   class="px-5 py-2 xl:px-6 text-[13px] xl:text-sm font-black text-white
                          bg-gradient-to-r from-[#1d5c42] to-[#154230] rounded-full shadow
                          hover:shadow-lg hover:-translate-y-0.5 transition-all whitespace-nowrap">
                    Sign Up
                </a>

            @endauth
        </div>

        {{-- ── Mobile Toggle ── --}}
        <button @click="mobile = !mobile"
                class="lg:hidden p-2 rounded-full hover:bg-gray-100 text-gray-700 transition-colors focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!mobile" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="mobile" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- ╔══════════════════════╗
             ║   MOBILE MENU        ║
             ╚══════════════════════╝ --}}
        <div x-show="mobile" x-collapse x-cloak
             class="lg:hidden w-full border-t border-gray-100 mt-3 pt-4 pb-3 space-y-1">

            @php
                $mob = "flex items-center gap-2.5 px-4 py-3 rounded-xl text-[15px] font-semibold text-gray-700
                        hover:bg-gray-50 hover:text-[#1d5c42] transition-colors";
                $mobAct = "text-[#1d5c42] bg-emerald-50 font-bold";
            @endphp

            @if(Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ url('/') }}" class="{{ $mob }} {{ request()->is('/') ? $mobAct : '' }}">
                    <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Home
                </a>
                <a href="{{ route('admin.farms.index') }}" class="{{ $mob }} {{ request()->routeIs('admin.farms.*') ? $mobAct : '' }}">
                    <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Manage Farms
                </a>
            @else
                <a href="{{ url('/') }}" class="{{ $mob }} {{ request()->is('/') ? $mobAct : '' }}">
                    <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Home
                </a>
                <a href="{{ route('explore') }}" class="{{ $mob }} {{ request()->routeIs('explore') ? $mobAct : '' }}">
                    <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Explore Farms
                </a>
                @auth
                    <a href="{{ route('bookings.my_bookings') }}" class="{{ $mob }} {{ request()->routeIs('bookings.*') ? $mobAct : '' }}">
                        <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Bookings
                    </a>
                    <a href="{{ route('favorites.index') }}" class="{{ $mob }} {{ request()->routeIs('favorites.*') ? $mobAct : '' }}">
                        <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        Favorites
                    </a>
                    <a href="{{ route('supplies.market') }}" class="{{ $mob }} {{ request()->routeIs('supplies.*') ? $mobAct : '' }}">
                        <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Market
                    </a>
                    <a href="{{ route('transports.index') }}" class="{{ $mob }} {{ request()->routeIs('transports.*') ? $mobAct : '' }}">
                        <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Transport
                    </a>
                @endauth
                <a href="{{ route('about') }}" class="{{ $mob }} {{ request()->routeIs('about') ? $mobAct : '' }}">
                    <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    About
                </a>
                <a href="{{ route('contact') }}" class="{{ $mob }} {{ request()->routeIs('contact') ? $mobAct : '' }}">
                    <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Contact
                </a>
            @endif

            {{-- Mobile Auth CTA --}}
            <div class="border-t border-gray-100 pt-4 mt-3 space-y-2.5 px-1">
                @auth
                    <a href="{{ url($dash ?? '/dashboard') }}"
                       class="flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl text-base font-black
                              text-white bg-gradient-to-r from-[#1d5c42] to-[#154230] shadow-md">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full py-3 rounded-2xl text-base font-bold text-red-500 bg-red-50
                                       hover:bg-red-100 transition-colors">
                            Log Out
                        </button>
                    </form>
                @else
                    {{-- Business Partners box --}}
                    <div class="bg-amber-50/60 rounded-2xl p-3 border border-amber-100/60 space-y-1">
                        <p class="text-[10.5px] font-black text-[#c2a265] uppercase tracking-widest px-2 mb-2">
                            Business Partners
                        </p>
                        <a href="{{ route('partner.register') }}"
                           class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold text-[#1d5c42] hover:bg-green-50 transition-colors">
                            List Your Farm
                        </a>
                        <a href="{{ route('portal.login') }}"
                           class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-white transition-colors">
                            Owner Login
                        </a>
                        <div class="border-t border-amber-100 my-1"></div>
                        <a href="{{ route('supply-company.login') }}"
                           class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold text-indigo-700 hover:bg-indigo-50 transition-colors">
                            Supply Company
                        </a>
                        <a href="{{ route('transport-company.login') }}"
                           class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold text-rose-700 hover:bg-rose-50 transition-colors">
                            Transport Company
                        </a>
                        <div class="border-t border-amber-100 my-1"></div>
                        <a href="{{ route('transport-driver.login') }}"
                           class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold text-amber-700 hover:bg-amber-50 transition-colors">
                            Transport Driver
                        </a>
                        <a href="{{ route('supply-driver.login') }}"
                           class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold text-teal-700 hover:bg-teal-50 transition-colors">
                            Supply Driver
                        </a>
                    </div>

                    {{-- Customer buttons --}}
                    <div class="grid grid-cols-2 gap-2.5 px-1">
                        <a href="{{ route('login') }}"
                           class="text-center py-3.5 rounded-2xl text-sm font-bold text-gray-700
                                  bg-gray-100 border border-gray-200 hover:bg-gray-200 transition-colors">
                            Log In
                        </a>
                        <a href="{{ route('register') }}"
                           class="text-center py-3.5 rounded-2xl text-sm font-black text-white
                                  bg-gradient-to-r from-[#1d5c42] to-[#154230] shadow hover:shadow-md transition-all">
                            Sign Up
                        </a>
                    </div>
                @endauth
            </div>
        </div>

    </nav>
</header>


{{-- ╔══════════════════════════════════════════════╗
     ║               FLASH MESSAGES                 ║
     ╚══════════════════════════════════════════════╝ --}}
<div x-data="{ show: true }"
     x-init="setTimeout(() => show = false, 7000)"
     class="fixed top-20 right-4 z-[110] flex flex-col gap-2.5 pointer-events-none max-w-xs w-full">

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
                shadow-[0_8px_32px_rgba(0,0,0,0.09)] p-4 flex items-start gap-3">
        <span class="shrink-0 p-1.5 bg-{{ $a['color'] }}-100 rounded-full text-{{ $a['color'] }}-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $a['icon'] }}"/>
            </svg>
        </span>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-black text-gray-900 uppercase tracking-wide">{{ $a['title'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $a['msg'] }}</p>
        </div>
        <button @click="show = false" class="shrink-0 text-gray-300 hover:text-gray-600 transition-colors mt-0.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    @endforeach

    @if($errors->any())
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-6"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="pointer-events-auto bg-white border border-amber-200 rounded-2xl
                shadow-[0_8px_32px_rgba(0,0,0,0.09)] p-4 flex items-start gap-3">
        <span class="shrink-0 p-1.5 bg-amber-100 rounded-full text-amber-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732
                         4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </span>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-black text-gray-900 uppercase tracking-wide">Attention</p>
            <ul class="mt-1 space-y-0.5">
                @foreach($errors->all() as $e)
                    <li class="text-[11px] text-gray-500 leading-relaxed">• {{ $e }}</li>
                @endforeach
            </ul>
        </div>
        <button @click="show = false" class="shrink-0 text-gray-300 hover:text-gray-600 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    @endif
</div>


{{-- ╔══════════════════════════════════════════════╗
     ║               MAIN CONTENT                   ║
     ╚══════════════════════════════════════════════╝ --}}
<main class="flex-1 w-full flex flex-col relative z-10">
    @yield('content')
</main>


{{-- ╔══════════════════════════════════════════════╗
     ║           ULTRA MODERN FOOTER                ║
     ╚══════════════════════════════════════════════╝ --}}
<footer
    x-data="{ visible: false }"
    x-init="
        new IntersectionObserver(([e]) => { if(e.isIntersecting) visible = true; }, { threshold: 0.08 })
            .observe($el);
    "
    class="relative bg-[#030712] text-white pt-20 pb-10 overflow-hidden border-t border-white/[0.06] z-20 mt-auto">

    {{-- Glow orbs --}}
    <div class="orb absolute -top-12 left-1/4 w-80 h-80 bg-[#1d5c42]/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="orb orb-delay absolute -bottom-12 right-1/4 w-80 h-80 bg-[#c2a265]/12 rounded-full blur-[100px] pointer-events-none"></div>

    {{-- Background watermark --}}
    <div class="absolute bottom-0 inset-x-0 flex justify-center overflow-hidden opacity-[0.025] pointer-events-none select-none">
        <span class="footer-wm text-white">MAZRAA</span>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">

        {{-- Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 xl:gap-14 mb-16">

            {{-- ── Col 1 : Brand ── --}}
            <div class="space-y-5 transition-all duration-700 ease-out"
                 :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">

                <a href="{{ url('/') }}"
                   class="inline-flex items-center gap-3 group">
                    <div class="p-2.5 bg-gradient-to-br from-[#1d5c42] to-[#0f3324] rounded-xl border border-white/10
                                shadow-lg group-hover:scale-110 transition-transform duration-400">
                        <svg class="w-5 h-5 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2
                                     2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0
                                     011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-black tracking-tight">
                        Mazraa<span class="text-[#c2a265]">.com</span>
                    </span>
                </a>

                <p class="text-[15px] font-medium text-gray-400 leading-relaxed max-w-[260px]">
                    The ultimate luxury ecosystem bridging premium farm stays,
                    supply chains, and safe transport.
                </p>

                {{-- Social icons --}}
                <div class="flex items-center gap-3 pt-1">
                    @foreach([
                        ['path' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
                        ['path' => 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z'],
                        ['path' => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z'],
                    ] as $s)
                    <a href="javascript:void(0)"
                       class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center
                              text-gray-400 hover:text-white transition-all hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="{{ $s['path'] }}"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- ── Col 2 : Quick Links ── --}}
            <div class="transition-all duration-700 delay-150 ease-out"
                 :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
                <h4 class="text-[11px] font-black text-white/50 uppercase tracking-[.18em] mb-5">Quick Links</h4>
                <ul class="space-y-3.5">
                    @foreach([
                        ['href' => route('explore'),         'label' => 'Explore Escapes'],
                        ['href' => route('supplies.market'), 'label' => 'Supply Market'],
                        ['href' => route('about'),           'label' => 'Our Story'],
                        ['href' => route('contact'),         'label' => 'Support Center'],
                    ] as $li)
                    <li>
                        <a href="{{ $li['href'] }}"
                           class="group inline-flex items-center gap-2.5 text-[14.5px] font-medium
                                  text-gray-400 hover:text-[#c2a265] transition-all duration-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-700 group-hover:bg-[#c2a265] transition-colors shrink-0"></span>
                            {{ $li['label'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- ── Col 3 : Partner Network ── --}}
            <div class="transition-all duration-700 delay-300 ease-out"
                 :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
                <h4 class="text-[11px] font-black text-white/50 uppercase tracking-[.18em] mb-5">Partner Network</h4>
                <ul class="space-y-3.5">
                    @foreach([
                        ['href' => route('portal.login'),            'label' => 'Owner Login'],
                        ['href' => route('partner.register'),        'label' => 'Register as Owner'],
                        ['href' => route('transport-driver.login'),  'label' => 'Transport Dispatch'],
                        ['href' => route('supply-driver.login'),     'label' => 'Supply Logistics'],
                    ] as $li)
                    <li>
                        <a href="{{ $li['href'] }}"
                           class="group inline-flex items-center gap-2.5 text-[14.5px] font-medium
                                  text-gray-400 hover:text-[#c2a265] transition-all duration-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-700 group-hover:bg-[#c2a265] transition-colors shrink-0"></span>
                            {{ $li['label'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- ── Col 4 : Newsletter ── --}}
            <div class="transition-all duration-700 delay-[450ms] ease-out"
                 :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
                <h4 class="text-[11px] font-black text-white/50 uppercase tracking-[.18em] mb-5">Stay Connected</h4>
                <p class="text-[14.5px] font-medium text-gray-400 mb-5 leading-relaxed">
                    Subscribe for exclusive luxury farm drops and platform updates.
                </p>

                <form x-data="{ done: false }" @submit.prevent="done = true" class="group">
                    <div x-show="!done">
                        <div class="relative flex items-center bg-white/[0.05] border border-white/10 rounded-2xl p-1.5
                                    focus-within:border-[#c2a265]/50 transition-all duration-300
                                    hover:border-white/20">
                            <input type="email"
                                   placeholder="your@email.com"
                                   required
                                   class="flex-1 bg-transparent border-none text-white text-sm placeholder-gray-600
                                          pl-3.5 focus:ring-0 focus:outline-none">
                            <button type="submit"
                                    class="shrink-0 bg-gradient-to-r from-[#1d5c42] to-[#154230] hover:from-[#154230] hover:to-[#0a2018]
                                           text-white p-2.5 rounded-xl transition-all hover:shadow-lg hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                          d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div x-show="done" x-cloak x-transition.opacity
                         class="flex flex-col items-center justify-center gap-2 py-4 px-5 rounded-2xl
                                bg-emerald-500/10 border border-emerald-500/20 text-center">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-emerald-400 font-bold text-sm">Successfully Subscribed!</span>
                    </div>
                </form>
            </div>

        </div>{{-- /grid --}}

        {{-- ── Bottom bar ── --}}
        <div class="border-t border-white/[0.07] pt-7 flex flex-col sm:flex-row justify-between items-center gap-4
                    transition-all duration-700 delay-500 ease-out"
             :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5'">

            <p class="text-[12.5px] font-semibold text-gray-600 text-center sm:text-left">
                Anas Alnsour · Adnan Awad · Aktham Zayed · Hafez Fares · Malek Al-sarayerah
            </p>

            <div class="flex items-center gap-5">
                @foreach(['Privacy','Terms','Cookies'] as $pg)
                <a href="javascript:void(0)"
                   class="text-[12px] font-bold text-gray-600 hover:text-white uppercase tracking-widest transition-colors">
                    {{ $pg }}
                </a>
                @endforeach
            </div>
        </div>

    </div>
</footer>

@stack('scripts')
</body>
</html>
