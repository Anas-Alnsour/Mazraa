<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">

    <title>Driver Portal - Mazraa.com</title>

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

        /* Smooth Entrance */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-content { animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        /* Progress Bar Animation for Toasts */
        @keyframes progress-shrink { from { width: 100%; } to { width: 0%; } }

        /* =========================================================
           🚀 GLOBAL MAGIC OVERRIDES (الشامل لكل النظام وللصفحات الداخلية) 🚀
           يستهدف الألوان الفاتحة القديمة ويحولها إلى Dark Glassmorphism أنيق
           ========================================================= */

        /* استهداف النصوص الداكنة في أي مكان بالنظام وتحويلها لأبيض ساطع */
        .text-gray-900, .text-gray-800, .text-black, .text-slate-900 {
            color: #ffffff !important;
        }

        /* استهداف النصوص الرمادية وتحويلها لرمادي فاتح مناسب للـ Dark Mode */
        .text-gray-700, .text-gray-600 { color: #cbd5e1 !important; }
        .text-gray-500 { color: #94a3b8 !important; }

        /* تحويل خلفيات الكروت البيضاء إلى Glassmorphism تلقائياً */
        .bg-white {
            background: rgba(30, 41, 59, 0.5) !important;
            border: 1px solid rgba(255,255,255,0.05) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5) !important;
        }

        /* تحويل الخلفيات الرمادية الفاتحة للوضع الليلي */
        .bg-gray-50, .bg-gray-100, .bg-slate-50, .bg-slate-100 {
            background: rgba(15, 23, 42, 0.4) !important;
        }

        /* تبهيت حواف الجداول والكروت القديمة */
        .border-gray-200, .border-gray-300, .border-slate-200 {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* 👑 تضبيط احترافي لعناوين الصفحات الداخلية 👑 */
        main h1, main h2, header h2 {
            color: #ffffff !important;
            font-weight: 900 !important;
            letter-spacing: 0.05em !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#020617] text-slate-200 selection:bg-slate-700 selection:text-white overflow-hidden" x-data="{ sidebarOpen: false }">

    {{-- 🎨 Ambient Background Glows (Aurora) 🎨 --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[20%] -left-[10%] w-[50vw] h-[50vw] rounded-full blur-[120px] mix-blend-screen opacity-50 {{ Auth::user()->role === 'supply_driver' ? 'bg-teal-900/20' : 'bg-amber-900/20' }}"></div>
        <div class="absolute bottom-[10%] -right-[10%] w-[40vw] h-[40vw] bg-indigo-900/10 rounded-full blur-[150px] mix-blend-screen"></div>
    </div>

    <div class="flex h-screen relative z-10">

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-[#020617]/90 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false" x-cloak></div>

        {{-- 🌟 Premium Sidebar 🌟 --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-[#020617]/95 backdrop-blur-2xl border-r border-slate-800/80 transition-transform duration-500 ease-in-out lg:static lg:translate-x-0 flex flex-col shadow-[20px_0_50px_rgba(0,0,0,0.5)]">

            {{-- Brand Header --}}
            <div class="flex items-center justify-center h-24 border-b border-slate-800/60 relative overflow-hidden shrink-0">
                <div class="absolute inset-0 bg-gradient-to-b from-slate-800/20 to-transparent pointer-events-none"></div>
                <a href="{{ Auth::user()->role === 'supply_driver' ? route('supply.driver.dashboard') : route('transport.driver.dashboard') }}" class="flex items-center gap-4 relative z-10 group px-6 w-full">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg transition-transform duration-500 group-hover:scale-105 border {{ Auth::user()->role === 'supply_driver' ? 'bg-gradient-to-br from-teal-500 to-teal-800 border-teal-500/30 shadow-teal-900/30' : 'bg-gradient-to-br from-amber-500 to-amber-800 border-amber-500/30 shadow-amber-900/30' }}">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-black tracking-widest text-white uppercase leading-none transition-colors">Mazraa</span>
                        <span class="text-[9px] font-black tracking-[0.4em] mt-1.5 uppercase {{ Auth::user()->role === 'supply_driver' ? 'text-teal-400' : 'text-amber-500' }}">Driver Hub</span>
                    </div>
                </a>
            </div>

            <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto custom-scrollbar relative z-10">

                {{-- ==========================================
                     SUPPLY DRIVER SIDEBAR
                     ========================================== --}}
                @if(Auth::user()->role === 'supply_driver')
                    <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Supply Operations</p>

                    <a href="{{ route('supply.driver.dashboard') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-[11px] uppercase tracking-widest transition-all group {{ request()->routeIs('supply.driver.dashboard') ? 'bg-gradient-to-r from-teal-600/20 to-transparent border-l-2 border-teal-500 text-white' : 'text-slate-400 hover:bg-slate-800/50 hover:text-teal-400' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('supply.driver.dashboard') ? 'text-teal-400' : 'opacity-70 group-hover:opacity-100 group-hover:scale-110 transition-transform' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Active Deliveries
                    </a>

                    <a href="{{ route('supply.driver.history') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-[11px] uppercase tracking-widest transition-all group {{ request()->routeIs('supply.driver.history') ? 'bg-gradient-to-r from-teal-600/20 to-transparent border-l-2 border-teal-500 text-white' : 'text-slate-400 hover:bg-slate-800/50 hover:text-teal-400' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('supply.driver.history') ? 'text-teal-400' : 'opacity-70 group-hover:opacity-100 group-hover:scale-110 transition-transform' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Delivery History
                    </a>

                    <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-widest pt-8 mb-4">Configuration</p>
                    <a href="{{ route('supply.driver.profile') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-[11px] uppercase tracking-widest transition-all group {{ request()->routeIs('supply.driver.profile') ? 'bg-gradient-to-r from-teal-600/20 to-transparent border-l-2 border-teal-500 text-white' : 'text-slate-400 hover:bg-slate-800/50 hover:text-teal-400' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('supply.driver.profile') ? 'text-teal-400' : 'opacity-70 group-hover:opacity-100 group-hover:scale-110 transition-transform' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Driver Profile
                    </a>
                @endif

                {{-- ==========================================
                     TRANSPORT DRIVER SIDEBAR
                     ========================================== --}}
                @if(Auth::user()->role === 'transport_driver')
                    <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Fleet Operations</p>

                    <a href="{{ route('transport.driver.dashboard') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-[11px] uppercase tracking-widest transition-all group {{ request()->routeIs('transport.driver.dashboard') ? 'bg-gradient-to-r from-amber-600/20 to-transparent border-l-2 border-amber-500 text-white' : 'text-slate-400 hover:bg-slate-800/50 hover:text-amber-400' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('transport.driver.dashboard') ? 'text-amber-400' : 'opacity-70 group-hover:opacity-100 group-hover:scale-110 transition-transform' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        My Schedule
                    </a>

                    <a href="{{ route('transport.driver.history') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-[11px] uppercase tracking-widest transition-all group {{ request()->routeIs('transport.driver.history') ? 'bg-gradient-to-r from-amber-600/20 to-transparent border-l-2 border-amber-500 text-white' : 'text-slate-400 hover:bg-slate-800/50 hover:text-amber-400' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('transport.driver.history') ? 'text-amber-400' : 'opacity-70 group-hover:opacity-100 group-hover:scale-110 transition-transform' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Trip History
                    </a>

                    <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-widest pt-8 mb-4">Configuration</p>
                    <a href="{{ route('transport.driver.profile') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-[11px] uppercase tracking-widest transition-all group {{ request()->routeIs('transport.driver.profile') ? 'bg-gradient-to-r from-amber-600/20 to-transparent border-l-2 border-amber-500 text-white' : 'text-slate-400 hover:bg-slate-800/50 hover:text-amber-400' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('transport.driver.profile') ? 'text-amber-400' : 'opacity-70 group-hover:opacity-100 group-hover:scale-110 transition-transform' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Driver Profile
                    </a>
                @endif
            </nav>

            {{-- Footer Profile & Logout --}}
            <div class="p-6 border-t border-slate-800/80 bg-slate-900/30 shrink-0">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-11 h-11 rounded-2xl bg-slate-800 flex items-center justify-center font-black text-xl border border-slate-700 shadow-inner {{ Auth::user()->role === 'supply_driver' ? 'text-teal-400' : 'text-amber-400' }}">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs font-black text-white truncate max-w-[140px] uppercase tracking-widest">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] font-bold uppercase tracking-widest mt-1 flex items-center gap-1.5 {{ Auth::user()->role === 'supply_driver' ? 'text-teal-400' : 'text-amber-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full animate-pulse {{ Auth::user()->role === 'supply_driver' ? 'bg-teal-500 shadow-[0_0_8px_rgba(20,184,166,0.8)]' : 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.8)]' }}"></span>
                            Active Unit
                        </p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-3 px-4 py-3.5 text-rose-500 bg-rose-500/5 hover:bg-rose-600 hover:text-white rounded-2xl font-black uppercase tracking-widest text-[10px] transition-all border border-rose-500/20 hover:shadow-[0_0_20px_rgba(244,63,94,0.3)] active:scale-95 group">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Disconnect
                    </button>
                </form>
            </div>
        </aside>

        {{-- 🌟 Main Workspace 🌟 --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-transparent relative z-10">

            {{-- Top Navbar --}}
            <header class="h-20 bg-slate-900/60 backdrop-blur-2xl border-b border-slate-800/80 flex items-center justify-between px-6 lg:px-10 shrink-0">
                <div class="flex items-center gap-5">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2.5 text-slate-400 hover:text-white focus:outline-none bg-slate-800 rounded-xl transition-colors border border-slate-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="hidden sm:flex items-center">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1.5 rounded-lg border shadow-sm {{ Auth::user()->role === 'supply_driver' ? 'text-teal-400 bg-teal-500/10 border-teal-500/20' : 'text-amber-400 bg-amber-500/10 border-amber-500/20' }}">
                            {{ Auth::user()->role === 'supply_driver' ? 'Delivery Operations' : 'Fleet Operations' }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="relative group">
                        <x-notification-bell />
                    </div>

                    <div class="h-6 w-px bg-slate-700 hidden sm:block"></div>

                    <div class="text-[10px] font-black text-slate-300 uppercase tracking-widest bg-slate-800/50 px-4 py-2 rounded-xl border border-slate-700 shadow-inner flex items-center gap-2">
                        <svg class="w-4 h-4 {{ Auth::user()->role === 'supply_driver' ? 'text-teal-500' : 'text-amber-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ date('M j, Y') }}
                    </div>
                </div>
            </header>

            {{-- Main Content Space --}}
            <main class="flex-1 overflow-y-auto p-4 lg:p-10 relative">
                <div class="max-w-7xl mx-auto">

                    {{-- 💡 FLOATING GLOBAL TOASTS 💡 --}}
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 7000)" class="fixed top-24 right-5 z-[200] flex flex-col gap-4 pointer-events-none">
                        @if (session('success'))
                            <div x-show="show" x-transition:enter="toast-enter" x-transition:leave="transition ease-in duration-300 transform opacity-0 scale-95"
                                 class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-emerald-500/40 rounded-[2rem] shadow-[0_20px_50px_rgba(16,185,129,0.2)] p-5 flex items-start gap-4 w-96 relative overflow-hidden" x-cloak>
                                <div class="absolute bottom-0 left-0 h-1.5 bg-emerald-500 w-full animate-[progress-shrink_7s_linear_forwards]"></div>
                                <div class="bg-emerald-500/20 p-3 rounded-xl text-emerald-400 shrink-0 border border-emerald-500/30 shadow-inner"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                                <div class="flex-1 mt-1"><h4 class="font-black text-white text-[11px] uppercase tracking-widest">Execution Success</h4><p class="text-slate-400 text-xs mt-2 font-medium leading-relaxed">{{ session('success') }}</p></div>
                                <button @click="show = false" class="text-slate-600 hover:text-white transition-colors p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                        @endif

                        @if (session('error') || $errors->any())
                            <div x-show="show" x-transition:enter="toast-enter" x-transition:leave="transition ease-in duration-300 transform opacity-0 scale-95"
                                 class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-rose-500/40 rounded-[2rem] shadow-[0_20px_50px_rgba(244,63,94,0.2)] p-5 flex items-start gap-4 w-96 relative overflow-hidden" x-cloak>
                                <div class="absolute bottom-0 left-0 h-1.5 bg-rose-500 w-full animate-[progress-shrink_7s_linear_forwards]"></div>
                                <div class="bg-rose-500/20 p-3 rounded-xl text-rose-400 shrink-0 border border-rose-500/30 shadow-inner"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
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

                    {{-- Content Injected Here --}}
                    <div class="animate-content pb-10">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
