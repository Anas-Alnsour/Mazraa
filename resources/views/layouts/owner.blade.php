<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Owner Dashboard') | Mazraa.com</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- FullCalendar CSS --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
</head>
<body class="font-sans antialiased bg-gray-50 flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#183126] text-white flex flex-col hidden md:flex shadow-xl z-20">
        <div class="h-20 flex items-center justify-center border-b border-white/10">
            <a href="{{ url('/') }}" class="text-2xl font-black tracking-tighter hover:opacity-80 transition">
                Mazraa<span class="text-[#b46146]">.com</span>
                <span class="text-[9px] uppercase tracking-widest text-gray-400 block -mt-1 text-center">Partner Portal</span>
            </a>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('owner.dashboard') ? 'bg-[#1d5c42] text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} rounded-xl font-bold text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Overview
            </a>

            <a href="{{ route('owner.farms.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('owner.farms.index') ? 'bg-[#1d5c42] text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} rounded-xl font-bold text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                My Farms
            </a>

            <a href="{{ route('owner.farms.create') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('owner.farms.create') ? 'bg-[#1d5c42] text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} rounded-xl font-bold text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Farm
            </a>

            <a href="{{ route('owner.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('owner.bookings.index') ? 'bg-[#1d5c42] text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} rounded-xl font-bold text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Bookings
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500 hover:text-white rounded-xl font-bold text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Log Out
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">

        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-2xl font-black text-[#1d5c42] tracking-tight">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center gap-4">
                <span class="text-xs font-black uppercase tracking-widest text-[#b46146] bg-orange-50 px-4 py-2 rounded-full border border-orange-100">
                    {{ Auth::user()->name }} (Farm Owner)
                </span>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>

    </main>

    @stack('scripts')
</body>
</html>
