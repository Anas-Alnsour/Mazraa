<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Portal - Mazraa.com</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-900 font-sans antialiased text-slate-200 flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-gray-900/50 lg:hidden" @click="sidebarOpen = false"></div>

    <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="fixed inset-y-0 left-0 z-30 w-72 bg-slate-900 text-white transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-2xl">
        <div class="flex items-center justify-center h-20 border-b border-slate-800">
            <a href="#" class="text-2xl font-black tracking-tighter text-white flex items-center">
                Mazraa<span class="{{ Auth::user()->role === 'supply_driver' ? 'text-teal-400' : 'text-amber-500' }}">.Driver</span>
            </a>
        </div>

        <nav class="flex-1 px-4 py-8 space-y-3 overflow-y-auto">

            {{-- ==========================================
                 SUPPLY DRIVER SIDEBAR
                 ========================================== --}}
            @if(Auth::user()->role === 'supply_driver')
                <p class="px-2 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Supply Operations</p>

                <a href="{{ route('supply.driver.dashboard') }}" class="flex items-center gap-3 px-4 py-4 bg-teal-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-teal-900/20 transition-all transform active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Active Deliveries
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-4 text-slate-400 hover:bg-slate-800 hover:text-teal-400 rounded-2xl font-bold text-sm transition-all opacity-50 cursor-not-allowed mt-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Delivery History
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-4 text-slate-400 hover:bg-slate-800 hover:text-teal-400 rounded-2xl font-bold text-sm transition-all opacity-50 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Driver Profile
                </a>
            @endif

            {{-- ==========================================
                 TRANSPORT DRIVER SIDEBAR
                 ========================================== --}}
            @if(Auth::user()->role === 'transport_driver')
                <p class="px-2 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Fleet Operations</p>

                <a href="{{ route('transport.driver.dashboard') }}" class="flex items-center gap-3 px-4 py-4 bg-amber-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-amber-900/20 transition-all transform active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    My Schedule
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-4 text-slate-400 hover:bg-slate-800 hover:text-amber-400 rounded-2xl font-bold text-sm transition-all opacity-50 cursor-not-allowed mt-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Trip History
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-4 text-slate-400 hover:bg-slate-800 hover:text-amber-400 rounded-2xl font-bold text-sm transition-all opacity-50 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Driver Profile
                </a>
            @endif

        </nav>

        <div class="p-6 border-t border-slate-800">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center {{ Auth::user()->role === 'supply_driver' ? 'text-teal-400' : 'text-amber-400' }} font-black text-xl border border-slate-700 shadow-inner">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-bold text-white truncate max-w-[150px]">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] {{ Auth::user()->role === 'supply_driver' ? 'text-teal-400' : 'text-amber-400' }} uppercase tracking-widest mt-1 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ Auth::user()->role === 'supply_driver' ? 'bg-teal-500 shadow-[0_0_8px_rgba(20,184,166,0.8)]' : 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.8)]' }} animate-pulse"></span>
                        On Duty
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-red-400 bg-red-400/10 hover:bg-red-500 hover:text-white rounded-xl font-black uppercase tracking-widest text-[10px] transition-all border border-red-500/20 hover:border-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-900">
        <header class="h-20 bg-slate-800 border-b border-slate-700 flex items-center justify-between px-6 lg:px-10 shadow-sm shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-400 hover:text-blue-500 focus:outline-none bg-slate-800 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="hidden sm:flex items-center gap-2">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-700 px-3 py-1.5 rounded-lg">
                        {{ Auth::user()->role === 'supply_driver' ? 'Delivery Operations' : 'Fleet Operations' }}
                    </span>
                </div>
            </div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-800 px-4 py-2 rounded-xl border border-slate-700 shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4 {{ Auth::user()->role === 'supply_driver' ? 'text-teal-500' : 'text-amber-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ date('l, M j, Y') }}
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 lg:p-10">
            <div class="max-w-6xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
