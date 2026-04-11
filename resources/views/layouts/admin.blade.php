<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God Mode | Mazraa Command Center</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        /* Premium Dark Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }

        /* Smooth Sidebar Links */
        .sidebar-link { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-link:hover { transform: translateX(8px); }

        /* Glass Header */
        .glass-header { background: rgba(2, 6, 23, 0.7); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border-bottom: 1px solid rgba(255,255,255,0.05); }

        /* Toast Animation */
        .toast-enter { animation: toastSlideIn 0.5s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
        @keyframes toastSlideIn {
            0% { opacity: 0; transform: translateX(100px) scale(0.9); }
            100% { opacity: 1; transform: translateX(0) scale(1); }
        }
    </style>
</head>
<body class="bg-[#020617] font-sans antialiased text-slate-200 flex h-screen overflow-hidden selection:bg-emerald-500/30 selection:text-emerald-200" x-data="{ sidebarOpen: false }">

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen" x-transition.opacity.duration.400ms class="fixed inset-0 z-40 bg-[#020617]/80 backdrop-blur-md lg:hidden" @click="sidebarOpen = false"></div>

    {{-- 🌟 Premium Sidebar --}}
    <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="fixed inset-y-0 left-0 z-50 w-[280px] bg-[#020617]/95 backdrop-blur-3xl text-white transition-transform duration-500 ease-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col border-r border-slate-800/80 shadow-[15px_0_40px_rgba(0,0,0,0.5)]">

        {{-- Logo Area --}}
        <div class="flex items-center justify-center h-24 border-b border-slate-800/80 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-emerald-500/5 to-transparent pointer-events-none"></div>
            <h1 class="text-2xl font-black tracking-[0.2em] text-emerald-400 flex items-center gap-3 relative z-10 drop-shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                <div class="p-2.5 bg-emerald-500/10 rounded-xl border border-emerald-500/20 shadow-inner">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                MAZRAA <span class="text-white/80 text-[10px] font-bold tracking-widest bg-white/10 px-2 py-1 rounded-md border border-white/5">CORE</span>
            </h1>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 px-5 py-8 space-y-2 overflow-y-auto">
            <p class="px-3 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] mb-5">Command Center</p>

            <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-[11px] uppercase tracking-widest {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-[0_0_20px_rgba(16,185,129,0.3)] border border-emerald-400/50' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white border border-transparent hover:border-slate-700/50' }}">
                <svg class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Financial Hub
            </a>

            @php $pendingCount = \App\Models\Farm::where('is_approved', false)->count(); @endphp
            <a href="{{ route('admin.verifications') }}" class="sidebar-link flex items-center justify-between px-4 py-3.5 rounded-2xl font-bold text-[11px] uppercase tracking-widest {{ request()->routeIs('admin.verifications') ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-[0_0_20px_rgba(99,102,241,0.3)] border border-indigo-400/50' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white border border-transparent hover:border-slate-700/50' }}">
                <div class="flex items-center gap-4">
                    <svg class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Verifications
                </div>
                @if($pendingCount > 0)
                    <span class="bg-rose-500 text-white text-[9px] font-black px-2.5 py-1 rounded-md shadow-[0_0_15px_rgba(244,63,94,0.6)] animate-pulse border border-rose-400/50">{{ $pendingCount }} NEW</span>
                @endif
            </a>

            @php $unreadMessages = \App\Models\ContactMessage::where('status', 'unread')->count(); @endphp
            <a href="{{ route('admin.contacts.index') }}" class="sidebar-link flex items-center justify-between px-4 py-3.5 rounded-2xl font-bold text-[11px] uppercase tracking-widest {{ request()->routeIs('admin.contacts.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_0_20px_rgba(59,130,246,0.3)] border border-blue-400/50' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white border border-transparent hover:border-slate-700/50' }}">
                <div class="flex items-center gap-4">
                    <svg class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Messages
                </div>
                @if($unreadMessages > 0)
                    <span class="bg-emerald-500 text-white text-[9px] font-black px-2.5 py-1 rounded-md shadow-[0_0_15px_rgba(16,185,129,0.6)] animate-pulse border border-emerald-400/50">{{ $unreadMessages }} NEW</span>
                @endif
            </a>

            <div class="my-6 border-t border-slate-800/60"></div>
            <p class="px-3 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] mb-5">System Management</p>

            <a href="{{ route('admin.farms.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-[11px] uppercase tracking-widest {{ request()->routeIs('admin.farms.*') ? 'bg-slate-800 text-emerald-400 shadow-inner border border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white border border-transparent hover:border-slate-700/50' }}">
                <svg class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Manage Farms
            </a>

            <a href="{{ route('admin.supplies.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-[11px] uppercase tracking-widest {{ request()->routeIs('admin.supplies.*') ? 'bg-slate-800 text-lime-400 shadow-inner border border-lime-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white border border-transparent hover:border-slate-700/50' }}">
                <svg class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Global Inventory
            </a>

            <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-[11px] uppercase tracking-widest {{ request()->routeIs('admin.users.*') ? 'bg-slate-800 text-indigo-400 shadow-inner border border-indigo-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white border border-transparent hover:border-slate-700/50' }}">
                <svg class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Users & Roles
            </a>

            <a href="{{ route('admin.payouts') }}" class="sidebar-link flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-[11px] uppercase tracking-widest {{ request()->routeIs('admin.payouts') ? 'bg-slate-800 text-amber-400 shadow-inner border border-amber-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white border border-transparent hover:border-slate-700/50' }}">
                <svg class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Financial Payouts
            </a>

            <a href="{{ route('admin.financials') }}" class="sidebar-link flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-[11px] uppercase tracking-widest {{ request()->routeIs('admin.financials') ? 'bg-slate-800 text-cyan-400 shadow-inner border border-cyan-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white border border-transparent hover:border-slate-700/50' }}">
                <svg class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Financial Reports
            </a>

            <a href="{{ route('admin.system') }}" class="sidebar-link flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-[11px] uppercase tracking-widest {{ request()->routeIs('admin.system') ? 'bg-slate-800 text-white shadow-inner border border-white/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white border border-transparent hover:border-slate-700/50' }}">
                <svg class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                System Settings
            </a>
        </nav>

        {{-- Logout Area --}}
        <div class="px-5 py-6 border-t border-slate-800/80 bg-[#020617]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 px-4 py-4 bg-rose-500/10 text-rose-400 hover:text-white hover:bg-rose-600 hover:shadow-[0_0_20px_rgba(244,63,94,0.4)] rounded-2xl transition-all font-black text-[10px] uppercase tracking-widest border border-rose-500/20 active:scale-95 group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Secure Terminate Session
                </button>
            </form>
        </div>
    </aside>

    {{-- 🌟 Main Content Area --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-[#0a0a0a] relative">

        {{-- Subtle Background Glow --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[300px] bg-emerald-500/5 blur-[150px] pointer-events-none"></div>

        {{-- Top Glass Header --}}
        <header class="h-20 glass-header flex items-center justify-between px-6 lg:px-10 z-30 shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-400 hover:text-emerald-400 focus:outline-none bg-slate-900 border border-slate-700 rounded-xl transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="hidden sm:flex items-center gap-2">
                    <span class="text-[9px] font-black text-emerald-400 uppercase tracking-[0.3em] bg-emerald-500/10 border border-emerald-500/20 px-3 py-1.5 rounded-lg shadow-[0_0_10px_rgba(16,185,129,0.1)] flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Encrypted Connection
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-6">
                {{-- Notifications Component --}}
                <x-notification-bell />

                {{-- Date Badge --}}
                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest bg-slate-900 px-4 py-2 rounded-xl border border-slate-800 shadow-inner hidden sm:flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ date('l, M j, Y') }}
                </div>

                {{-- Admin Profile Dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <div @click="open = !open" class="h-10 w-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-emerald-400 text-slate-950 flex items-center justify-center font-black border border-emerald-300 shadow-[0_0_15px_rgba(16,185,129,0.4)] cursor-pointer hover:scale-105 transition-transform">
                        SA
                    </div>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                         class="absolute right-0 mt-4 w-64 bg-slate-900/95 backdrop-blur-xl border border-slate-700/80 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] py-3 z-50 overflow-hidden">

                        <div class="px-6 py-4 border-b border-slate-800 mb-2 bg-slate-800/30">
                            <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1">Super Administrator</p>
                            <p class="text-sm font-bold text-white truncate">{{ auth()->user()->email ?? 'admin@mazraa.com' }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-6 py-3 text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
                            <div class="p-1.5 rounded-lg bg-slate-800 group-hover:bg-slate-700 transition-colors"><svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
                            Access Profile Settings
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-slate-800/50 pt-2">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-6 py-3 text-xs font-bold text-rose-400 hover:text-white hover:bg-rose-500/20 transition-colors group">
                                <div class="p-1.5 rounded-lg bg-rose-500/10 group-hover:bg-rose-500/20 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg></div>
                                Execute Disconnect
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- 🌟 Dynamic Main Content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-8 relative z-20">

            {{-- 💡 FLOATING TOAST NOTIFICATIONS (FIXED & RESTORED) 💡 --}}
            <div class="fixed top-24 right-5 z-[150] flex flex-col gap-3 pointer-events-none">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                         class="toast-enter pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-emerald-500/30 rounded-[1.5rem] shadow-[0_15px_40px_rgba(16,185,129,0.15)] p-4 flex items-start gap-4 w-80 relative overflow-hidden">
                        <div class="absolute bottom-0 left-0 h-1 bg-emerald-500 w-full animate-[shrink_6s_linear_forwards]"></div>
                        <div class="bg-emerald-500/20 p-2.5 rounded-xl text-emerald-400 flex-shrink-0 border border-emerald-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                        <div class="flex-1 mt-0.5"><h4 class="font-black text-white text-[11px] uppercase tracking-widest">Success</h4><p class="text-slate-400 text-xs mt-1 font-medium leading-relaxed">{{ session('success') }}</p></div>
                        <button @click="show = false" class="text-slate-500 hover:text-white transition-colors focus:outline-none"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                         class="toast-enter pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-rose-500/30 rounded-[1.5rem] shadow-[0_15px_40px_rgba(244,63,94,0.15)] p-4 flex items-start gap-4 w-80 relative overflow-hidden">
                        <div class="absolute bottom-0 left-0 h-1 bg-rose-500 w-full animate-[shrink_6s_linear_forwards]"></div>
                        <div class="bg-rose-500/20 p-2.5 rounded-xl text-rose-400 flex-shrink-0 border border-rose-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                        <div class="flex-1 mt-0.5"><h4 class="font-black text-white text-[11px] uppercase tracking-widest">Error</h4><p class="text-slate-400 text-xs mt-1 font-medium leading-relaxed">{{ session('error') }}</p></div>
                        <button @click="show = false" class="text-slate-500 hover:text-white transition-colors focus:outline-none"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
                         class="toast-enter pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-amber-500/30 rounded-[1.5rem] shadow-[0_15px_40px_rgba(245,158,11,0.15)] p-4 flex items-start gap-4 w-80 relative overflow-hidden">
                        <div class="absolute bottom-0 left-0 h-1 bg-amber-500 w-full animate-[shrink_8s_linear_forwards]"></div>
                        <div class="bg-amber-500/20 p-2.5 rounded-xl text-amber-400 flex-shrink-0 border border-amber-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
                        <div class="flex-1 mt-0.5">
                            <h4 class="font-black text-white text-[11px] uppercase tracking-widest">Validation Failed</h4>
                            <ul class="list-disc list-inside text-slate-400 text-[10px] mt-1.5 font-medium leading-relaxed space-y-1">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                        <button @click="show = false" class="text-slate-500 hover:text-white transition-colors focus:outline-none"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endif
            </div>

            <style>@keyframes shrink { from { width: 100%; } to { width: 0%; } }</style>

            {{-- 🌟 Page Specific Content Injected Here --}}
            @yield('content')

        </main>
    </div>
    @stack('scripts')
</body>
</html>
