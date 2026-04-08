<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Supply Hub') | Mazraa.com</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-slate-900 text-slate-200 flex h-screen overflow-hidden selection:bg-indigo-500 selection:text-white">

    {{-- Sidebar (Indigo Theme) --}}
    <aside class="w-64 bg-[#1e1b4b] text-white flex flex-col hidden md:flex shadow-xl z-20">
        <div class="h-20 flex flex-col items-center justify-center border-b border-indigo-400/20">
            <a href="{{ url('/') }}" class="text-2xl font-black tracking-tighter hover:opacity-80 transition">
                Mazraa<span class="text-indigo-400">.com</span>
            </a>
            <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-indigo-300 mt-0.5">Supply Hub</span>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('supplies.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('supplies.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }} rounded-xl font-bold text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Overview & Dispatch
            </a>

            <a href="{{ route('supplies.items.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('supplies.items.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }} rounded-xl font-bold text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Inventory Management
            </a>

            <a href="{{ route('supplies.drivers.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('supplies.drivers.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }} rounded-xl font-bold text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Supply Drivers
            </a>
        </nav>

        <div class="p-4 border-t border-indigo-400/20">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-rose-300 hover:bg-rose-500 hover:text-white rounded-xl font-bold text-sm transition-all group">
                    <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Secure Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-900">
        {{-- Header Bar --}}
        <header class="h-20 bg-slate-800 shadow-sm flex items-center justify-between px-8 z-10 border-b border-slate-700">
            <h1 class="text-2xl font-black text-white tracking-tight">@yield('title', 'Supply Hub')</h1>
            <div class="flex items-center gap-4">
                <x-notification-bell />
                <span class="text-xs font-black uppercase tracking-widest text-indigo-400 bg-indigo-900/50 px-4 py-2.5 rounded-xl border border-indigo-700/50 flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                    {{ Auth::user()->name }}
                </span>
            </div>
        </header>

        {{-- Content Area --}}
        <div class="flex-1 overflow-y-auto">
            @yield('content')
        </div>
    </main>
</body>
</html>

