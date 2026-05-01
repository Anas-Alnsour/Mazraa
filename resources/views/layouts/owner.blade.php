<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#020617">

    <title>{{ config('app.name', 'Mazraa') }} - Owner Command Center</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        /* Modern Sleek Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Entrance Animations */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-god-in { animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        /* Toast Animation */
        @keyframes toastSlideIn {
            from { transform: translateX(100%) scale(0.95); opacity: 0; }
            to { transform: translateX(0) scale(1); opacity: 1; }
        }
        .toast-enter { animation: toastSlideIn 0.4s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
        @keyframes progress-shrink { from { width: 100%; } to { width: 0%; } }

        /* Glassmorphism Classes */
        .glass-panel {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Sidebar Link Hover & Active States */
        .sidebar-link { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border-left: 3px solid transparent; }
        .sidebar-link:hover { background: rgba(30, 41, 59, 0.5); border-left-color: #c2a265; transform: translateX(4px); }
        .sidebar-link span { color: #cbd5e1; transition: color 0.3s; }
        .sidebar-link:hover span { color: #ffffff; }

        .active-nav-item {
            background: linear-gradient(90deg, rgba(29, 92, 66, 0.3) 0%, transparent 100%);
            border-left: 3px solid #10b981;
        }
        .active-nav-item span { color: #ffffff !important; font-weight: 900; }
        .active-nav-item svg { color: #10b981 !important; }

        /* =========================================================
           🚀 GLOBAL MAGIC OVERRIDES (الشامل لكل النظام والهيدر) 🚀
           ========================================================= */

        /* استهداف النصوص الداكنة في أي مكان بالنظام وتحويلها لأبيض ساطع */
        .text-gray-900, .text-gray-800, .text-black {
            color: #ffffff !important;
        }

        /* استهداف النصوص الرمادية وتحويلها لرمادي فاتح مناسب للـ Dark Mode */
        .text-gray-700, .text-gray-600 {
            color: #cbd5e1 !important;
        }
        .text-gray-500 {
            color: #94a3b8 !important;
        }

        /* تحويل خلفيات الكروت البيضاء إلى Glassmorphism تلقائياً */
        .bg-white {
            background: rgba(15, 23, 42, 0.4) !important;
            border: 1px solid rgba(255,255,255,0.05) !important;
            backdrop-filter: blur(12px) !important;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5) !important;
        }

        .bg-gray-50, .bg-gray-100 {
            background: rgba(30, 41, 59, 0.3) !important;
        }
        .border-gray-200, .border-gray-300 {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* 👑 تضبيط احترافي مخصص لمنطقة الـ Navbar (الهيدر) 👑 */
        header h2, header h1 {
            color: #ffffff !important;
            font-weight: 900 !important;
            letter-spacing: 0.1em !important;
            text-transform: uppercase !important;
            font-size: 1.1rem !important;
            margin-bottom: 0.1rem !important;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        header p, header .text-gray-600, header .text-gray-500 {
            color: #10b981 !important; /* لون أخضر زمردي للوصف عشان يطلع فخم */
            font-weight: 700 !important;
            letter-spacing: 0.1em !important;
            font-size: 0.7rem !important;
            text-transform: uppercase !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#020617] text-slate-200 selection:bg-emerald-500/30 selection:text-emerald-200 overflow-hidden" x-data="{ sidebarOpen: false }">

    {{-- Ambient Background Glows --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[20%] -left-[10%] w-[50vw] h-[50vw] bg-emerald-900/10 rounded-full blur-[120px] mix-blend-screen"></div>
        <div class="absolute bottom-[10%] -right-[10%] w-[40vw] h-[40vw] bg-[#c2a265]/5 rounded-full blur-[150px] mix-blend-screen"></div>
    </div>

    <div class="flex h-screen relative z-10">

        {{-- Mobile Sidebar Backdrop --}}
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-[#020617]/90 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false" x-cloak></div>

        {{-- 🌟 Premium Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-[#020617] border-r border-slate-800/60 transition-transform duration-500 ease-in-out lg:static lg:translate-x-0 flex flex-col shadow-[20px_0_50px_rgba(0,0,0,0.5)]">

            {{-- Logo Header --}}
            <div class="flex items-center justify-center h-24 border-b border-slate-800/60 relative overflow-hidden shrink-0">
                <div class="absolute inset-0 bg-gradient-to-b from-[#c2a265]/5 to-transparent pointer-events-none"></div>
                <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-4 relative z-10 group px-6 w-full">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#c2a265] to-yellow-800 flex items-center justify-center shadow-[0_0_20px_rgba(194,162,101,0.3)] group-hover:scale-105 transition-transform duration-500 border border-[#c2a265]/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-black tracking-widest text-white uppercase leading-none group-hover:text-[#c2a265] transition-colors">Mazraa</span>
                        <span class="text-[9px] font-black text-emerald-500 tracking-[0.4em] mt-1.5 uppercase">Owner </span>
                    </div>
                </a>
            </div>

            {{-- Navigation Menu --}}
            <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto custom-scrollbar relative z-10">
                <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Operations Panel</p>

                @php
                    $navItems = [
                        ['route' => 'owner.dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', 'label' => 'Dashboard'],
                        ['route' => 'owner.farms.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'My Properties'],
                        ['route' => 'owner.bookings.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Bookings'],
                        ['route' => 'owner.financials', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Payments'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}" class="sidebar-link flex items-center gap-4 px-4 py-4 rounded-xl {{ request()->routeIs($item['route']) ? 'active-nav-item' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs($item['route']) ? 'text-emerald-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg>
                        <span class="text-[11px] font-bold uppercase tracking-widest">{{ $item['label'] }}</span>
                    </a>
                @endforeach

                <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-widest pt-8 mb-4">Configuration</p>

                <a href="{{ route('owner.profile.edit') }}" class="sidebar-link flex items-center gap-4 px-4 py-4 rounded-xl {{ request()->routeIs('owner.profile.*') ? 'active-nav-item' : '' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('owner.profile.*') ? 'text-emerald-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="text-[11px] font-bold uppercase tracking-widest">Account settings</span>
                </a>
            </nav>

            {{-- Logout Button --}}
            <div class="p-6 border-t border-slate-800/60 bg-[#020617] shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center justify-center gap-3 px-4 py-4 bg-rose-500/10 text-rose-500 hover:text-white hover:bg-rose-600 rounded-xl transition-all font-black text-[10px] uppercase tracking-widest border border-rose-500/20 active:scale-95 group shadow-sm hover:shadow-[0_0_20px_rgba(244,63,94,0.4)]">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                         Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- 🌟 Main Content Area --}}
        <div class="flex-1 flex flex-col min-w-0 bg-transparent relative z-10">

            {{-- Top Navbar (Glassmorphism) --}}
            <header class="glass-panel sticky top-0 z-30 shrink-0">
                <div class="flex items-center justify-between px-6 lg:px-10 h-20">

                    {{-- Left side: Mobile Toggle & Status --}}
                    <div class="flex items-center gap-6">
                        <button @click="sidebarOpen = true" class="p-2.5 text-slate-400 rounded-xl lg:hidden bg-slate-900 border border-slate-800 transition-all hover:text-emerald-400 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>

                        <div class="hidden lg:flex items-center gap-3">
                            <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest bg-emerald-500/10 border border-emerald-500/20 px-3 py-1.5 rounded-lg shadow-[0_0_15px_rgba(16,185,129,0.1)] flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Connection Secured
                            </span>
                        </div>
                    </div>

                    {{-- Center: Page Title (تم تعديل الـ Wrapper ليدعم السطرين ويوسطهم صح) --}}
                    <div class="hidden md:flex flex-col items-center justify-center absolute left-1/2 -translate-x-1/2 text-center w-full max-w-lg">
                        {{ $header ?? 'Ecosystem Command' }}
                    </div>

                    {{-- Right side: Notifications & Profile --}}
                    <div class="flex items-center gap-5 lg:gap-8">
                        <div class="relative group">
                            <x-notification-bell />
                        </div>

                        <div class="h-6 w-px bg-slate-700 hidden sm:block"></div>

                        {{-- User Dropdown --}}
                        <div x-data="{ dropdownOpen: false }" class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false"
                                    class="flex items-center gap-3 p-1.5 rounded-2xl transition-all duration-300 border border-slate-800 hover:border-[#c2a265]/50 bg-slate-900/50 group shadow-sm hover:shadow-md">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#c2a265] to-[#8a6d3b] flex items-center justify-center text-slate-950 font-black text-sm shadow-[0_0_15px_rgba(194,162,101,0.4)] group-hover:scale-105 transition-transform">
                                    {{ substr(Auth::user()->name ?? 'O', 0, 1) }}
                                </div>
                                <div class="hidden lg:block text-left pr-4">
                                    <p class="text-xs font-black text-white uppercase tracking-widest leading-none">{{ Auth::user()->name ?? 'Owner' }}</p>
                                    <p class="text-[9px] text-[#c2a265] font-bold uppercase tracking-widest mt-1">Principal Asset Partner</p>
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
                                 class="absolute right-0 w-64 mt-4 bg-slate-900/98 backdrop-blur-3xl border border-slate-700/80 rounded-[2rem] shadow-[0_25px_60px_rgba(0,0,0,0.8)] py-3 z-50 overflow-hidden"
                                 style="display: none;" x-cloak>

                                <div class="px-6 py-5 border-b border-slate-800/80 mb-2 bg-slate-800/20">
                                    <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1">Authenticated Node</p>
                                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <div class="px-3 space-y-1">
                                    <a href="{{ route('owner.profile.edit') }}" class="flex items-center gap-4 px-5 py-3.5 text-[11px] font-black uppercase tracking-widest text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all group/item">
                                        <div class="p-2 rounded-lg bg-slate-800 group-hover/item:bg-[#c2a265]/20 transition-colors">
                                            <svg class="w-4 h-4 group-hover/item:text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        Security Settings
                                    </a>

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

                {{-- 💡 FLOATING TOAST NOTIFICATIONS 💡 --}}
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
                    {{ $slot }}
                </div>
            </main>

        </div>
    </div>
    @stack('scripts')
</body>
</html>
