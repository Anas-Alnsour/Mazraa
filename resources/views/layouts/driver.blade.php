<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#020617">

    <title>@yield('title', 'Driver Terminal') | Mazraa.com</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; border: 1px solid #020617; }
        ::-webkit-scrollbar-thumb:hover { background: #4f46e5; }

        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-god-in { animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes toastSlideIn { from { transform: translateX(100%) scale(0.95); opacity: 0; } to { transform: translateX(0) scale(1); opacity: 1; } }
        .toast-enter { animation: toastSlideIn 0.4s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
        @keyframes progress-shrink { from { width: 100%; } to { width: 0%; } }

        .glass-nav { background: rgba(2, 6, 23, 0.85); backdrop-filter: blur(24px); border-bottom: 1px solid rgba(255, 255, 255, 0.05); }

        /* Dynamic Sidebar Link Hover States based on Role */
        .sidebar-link { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border-left: 3px solid transparent; }

        /* Supply Driver Theme (Teal) */
        .supply-theme:hover { transform: translateX(6px); border-left-color: #14b8a6; background: rgba(20, 184, 166, 0.1); color: #2dd4bf; }
        .supply-active { background: linear-gradient(to right, rgba(20, 184, 166, 0.15), transparent); border-left-color: #14b8a6; color: #2dd4bf !important; }

        /* Transport Driver Theme (Amber) */
        .transport-theme:hover { transform: translateX(6px); border-left-color: #f59e0b; background: rgba(245, 158, 11, 0.1); color: #fbbf24; }
        .transport-active { background: linear-gradient(to right, rgba(245, 158, 11, 0.15), transparent); border-left-color: #f59e0b; color: #fbbf24 !important; }
    </style>
</head>
<body class="font-sans antialiased bg-[#020617] text-slate-300 overflow-hidden" x-data="{ sidebarOpen: false }">

    @php
        $userRole = auth()->check() ? auth()->user()->role : null;
        $isSupply = $userRole === 'supply_driver';
    @endphp

    {{-- Ambient Background Glows --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[20%] -left-[10%] w-[50vw] h-[50vw] {{ $isSupply ? 'bg-teal-900/20' : 'bg-amber-900/20' }} rounded-full blur-[120px] mix-blend-screen"></div>
        <div class="absolute bottom-[10%] -right-[10%] w-[40vw] h-[40vw] {{ $isSupply ? 'bg-emerald-900/10' : 'bg-orange-900/10' }} rounded-full blur-[150px] mix-blend-screen"></div>
    </div>

    <div class="flex h-screen relative z-10">

        {{-- Mobile Sidebar Backdrop --}}
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-[#020617]/90 backdrop-blur-md lg:hidden" @click="sidebarOpen = false" x-cloak></div>

        {{-- 🌟 Premium Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-[#020617] lg:bg-transparent border-r border-slate-800/60 lg:border-slate-800/40 transition-transform duration-500 lg:static lg:translate-x-0 flex flex-col shadow-[20px_0_50px_rgba(0,0,0,0.5)] relative overflow-hidden">

            <div class="absolute inset-0 bg-gradient-to-b {{ $isSupply ? 'from-teal-500/5' : 'from-amber-500/5' }} to-transparent pointer-events-none"></div>

            {{-- Logo Header --}}
            <div class="flex items-center justify-center h-24 border-b border-slate-800/60 relative z-10 shrink-0">
                <a href="{{ url('/') }}" class="flex items-center gap-4 group px-6 w-full">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br {{ $isSupply ? 'from-teal-600 to-emerald-800 border-teal-500/30 shadow-[0_0_20px_rgba(20,184,166,0.3)]' : 'from-amber-600 to-orange-800 border-amber-500/30 shadow-[0_0_20px_rgba(245,158,11,0.3)]' }} flex items-center justify-center group-hover:scale-105 group-hover:rotate-6 transition-all duration-500 border">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-black tracking-widest text-white uppercase leading-none {{ $isSupply ? 'group-hover:text-teal-400' : 'group-hover:text-amber-400' }} transition-colors">Mazraa</span>
                        <span class="text-[9px] font-black {{ $isSupply ? 'text-teal-500' : 'text-amber-500' }} tracking-[0.4em] mt-1.5 uppercase">Driver Terminal</span>
                    </div>
                </a>
            </div>

            {{-- Navigation Menu (Uses your exact links) --}}
            <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto custom-scrollbar relative z-10">
                @if($userRole === 'supply_driver')
                    <p class="px-4 text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] mb-5">Supply Operations</p>

                    <a href="{{ route('supply.driver.dashboard') }}" class="sidebar-link supply-theme flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-[11px] uppercase tracking-widest text-slate-400 {{ request()->routeIs('supply.driver.dashboard') ? 'supply-active' : '' }}">
                        <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Active Deliveries
                    </a>

                    <a href="{{ route('supply.driver.history') }}" class="sidebar-link supply-theme flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-[11px] uppercase tracking-widest text-slate-400 {{ request()->routeIs('supply.driver.history') ? 'supply-active' : '' }}">
                        <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Delivery History
                    </a>

                    <p class="px-4 text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] pt-6 mb-5">Configuration</p>

                    <a href="{{ route('supply.driver.profile') }}" class="sidebar-link supply-theme flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-[11px] uppercase tracking-widest text-slate-400 {{ request()->routeIs('supply.driver.profile') ? 'supply-active' : '' }}">
                        <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Driver Profile
                    </a>
                @endif

                @if($userRole === 'transport_driver')
                    <p class="px-4 text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] mb-5">Fleet Operations</p>

                    <a href="{{ route('transport.driver.dashboard') }}" class="sidebar-link transport-theme flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-[11px] uppercase tracking-widest text-slate-400 {{ request()->routeIs('transport.driver.dashboard') ? 'transport-active' : '' }}">
                        <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        My Schedule
                    </a>

                    <a href="{{ route('transport.driver.history') }}" class="sidebar-link transport-theme flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-[11px] uppercase tracking-widest text-slate-400 {{ request()->routeIs('transport.driver.history') ? 'transport-active' : '' }}">
                        <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Trip History
                    </a>

                    <p class="px-4 text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] pt-6 mb-5">Configuration</p>

                    <a href="{{ route('transport.driver.profile') }}" class="sidebar-link transport-theme flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-[11px] uppercase tracking-widest text-slate-400 {{ request()->routeIs('transport.driver.profile') ? 'transport-active' : '' }}">
                        <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Driver Profile
                    </a>
                @endif
            </nav>

            {{-- Logout Button --}}
            <div class="p-5 border-t border-slate-800/60 shrink-0 bg-[#0a0a0a]/50 relative z-10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center justify-center gap-3 px-4 py-4 bg-rose-500/10 text-rose-500 hover:text-white hover:bg-rose-600 rounded-xl transition-all font-black text-[10px] uppercase tracking-widest border border-rose-500/20 active:scale-95 group shadow-sm hover:shadow-[0_0_20px_rgba(244,63,94,0.4)]">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        System Logoff
                    </button>
                </form>
            </div>
        </aside>

        {{-- 🌟 Main Content Area --}}
        <div class="flex-1 flex flex-col min-w-0 bg-transparent relative z-10 overflow-hidden">

            {{-- Top Navbar (Glassmorphism) --}}
            <header class="glass-nav sticky top-0 z-30 shrink-0">
                <div class="flex items-center justify-between px-6 lg:px-10 h-20">

                    {{-- Left side: Mobile Toggle & Status --}}
                    <div class="flex items-center gap-6">
                        <button @click="sidebarOpen = true" class="p-2.5 text-slate-400 rounded-xl lg:hidden bg-slate-900 border border-slate-800 transition-all {{ $isSupply ? 'hover:text-teal-400' : 'hover:text-amber-400' }} focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>

                        <div class="hidden lg:flex items-center gap-3">
                            <span class="text-[10px] font-black {{ $isSupply ? 'text-teal-400 bg-teal-500/10 border-teal-500/20 shadow-[0_0_15px_rgba(20,184,166,0.15)]' : 'text-amber-400 bg-amber-500/10 border-amber-500/20 shadow-[0_0_15px_rgba(245,158,11,0.15)]' }} uppercase tracking-[0.3em] px-3 py-1.5 rounded-lg flex items-center gap-2 border">
                                <span class="w-1.5 h-1.5 rounded-full {{ $isSupply ? 'bg-teal-500' : 'bg-amber-500' }} animate-pulse"></span>
                                Terminal Online
                            </span>
                        </div>
                    </div>

                    {{-- Center: Page Title --}}
                    <div class="hidden md:flex flex-col items-center justify-center absolute left-1/2 -translate-x-1/2 text-center w-full max-w-lg">
                        <span class="text-sm font-black text-white uppercase tracking-[0.4em] opacity-90 drop-shadow-md">@yield('title', 'Driver Node')</span>
                    </div>

                    {{-- Right side: Profile --}}
                    <div class="flex items-center gap-5 lg:gap-8">
                        <div class="h-6 w-px bg-slate-700 hidden sm:block"></div>

                        {{-- User Dropdown --}}
                        <div x-data="{ dropdownOpen: false }" class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false"
                                    class="flex items-center gap-3 p-1.5 rounded-2xl transition-all duration-300 border border-slate-800 {{ $isSupply ? 'hover:border-teal-500/50' : 'hover:border-amber-500/50' }} bg-slate-900/50 group shadow-sm hover:shadow-md outline-none">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr {{ $isSupply ? 'from-teal-600 to-emerald-800 border border-teal-500/30 shadow-[0_0_15px_rgba(20,184,166,0.4)]' : 'from-amber-600 to-orange-800 border border-amber-500/30 shadow-[0_0_15px_rgba(245,158,11,0.4)]' }} flex items-center justify-center text-white font-black text-sm group-hover:scale-105 transition-transform">
                                    {{ substr(auth()->user()->name ?? 'D', 0, 1) }}
                                </div>
                                <div class="hidden lg:block text-left pr-4">
                                    <p class="text-xs font-black text-white uppercase tracking-widest leading-none">{{ auth()->user()->name ?? 'Driver' }}</p>
                                    <p class="text-[9px] {{ $isSupply ? 'text-teal-400' : 'text-amber-400' }} font-bold uppercase tracking-widest mt-1">Field Operative</p>
                                </div>
                            </button>

                            {{-- Dropdown Content --}}
                            <div x-show="dropdownOpen"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95 translate-y-3"
                                 x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="transform opacity-0 scale-95 translate-y-3"
                                 class="absolute right-0 mt-4 w-64 bg-slate-900/98 backdrop-blur-3xl border border-slate-700/80 rounded-[2rem] shadow-[0_25px_60px_rgba(0,0,0,0.8)] py-3 z-50 overflow-hidden"
                                 style="display: none;" x-cloak>

                                <div class="px-6 py-5 border-b border-slate-800/80 mb-2 bg-slate-800/20">
                                    <p class="text-[9px] font-black {{ $isSupply ? 'text-teal-400' : 'text-amber-400' }} uppercase tracking-widest mb-1">Authenticated Entity</p>
                                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->email ?? 'driver@mazraa.com' }}</p>
                                </div>

                                <div class="px-3 space-y-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="w-full flex items-center gap-4 px-5 py-3.5 text-[11px] font-black uppercase tracking-widest text-rose-400 hover:text-white hover:bg-rose-600 rounded-xl transition-all group/item mt-1">
                                            <div class="p-2 rounded-lg bg-rose-500/10 group-hover/item:bg-rose-500/20 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            </div>
                                            Terminate Session
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-10 relative z-20">

                {{-- 💡 FLOATING TOAST NOTIFICATIONS --}}
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 7000)" class="fixed top-24 right-5 z-[200] flex flex-col gap-4 pointer-events-none">
                    @if (session('success'))
                        <div x-show="show" x-transition:enter="toast-enter" x-transition:leave="transition ease-in duration-300 transform opacity-0 scale-95"
                             class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-emerald-500/40 rounded-[2rem] shadow-[0_20px_50px_rgba(16,185,129,0.2)] p-5 flex items-start gap-4 w-96 relative overflow-hidden" x-cloak>
                            <div class="absolute bottom-0 left-0 h-1.5 bg-emerald-500 w-full animate-[progress-shrink_7s_linear_forwards]"></div>
                            <div class="bg-emerald-500/20 p-3 rounded-xl text-emerald-400 shrink-0 border border-emerald-500/30 shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="flex-1 mt-1">
                                <h4 class="font-black text-white text-[11px] uppercase tracking-widest">Execution Success</h4>
                                <p class="text-slate-400 text-xs mt-2 font-medium leading-relaxed">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="text-slate-600 hover:text-white transition-colors p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                    @endif

                    @if (session('error') || $errors->any())
                        <div x-show="show" x-transition:enter="toast-enter" x-transition:leave="transition ease-in duration-300 transform opacity-0 scale-95"
                             class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-rose-500/40 rounded-[2rem] shadow-[0_20px_50px_rgba(244,63,94,0.2)] p-5 flex items-start gap-4 w-96 relative overflow-hidden" x-cloak>
                            <div class="absolute bottom-0 left-0 h-1.5 bg-rose-500 w-full animate-[progress-shrink_7s_linear_forwards]"></div>
                            <div class="bg-rose-500/20 p-3 rounded-xl text-rose-400 shrink-0 border border-rose-500/30 shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div class="flex-1 mt-1">
                                <h4 class="font-black text-white text-[11px] uppercase tracking-widest">Runtime Error</h4>
                                <p class="text-slate-400 text-xs mt-2 font-medium leading-relaxed">
                                    @if(session('error')) {{ session('error') }} @else {{ $errors->first() }} @endif
                                </p>
                            </div>
                            <button @click="show = false" class="text-slate-600 hover:text-white transition-colors p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                    @endif
                </div>

                <div class="animate-god-in">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
