<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Transport Dashboard') | Mazraa.com</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50 flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#312e81] text-white flex flex-col hidden md:flex shadow-xl z-20">
        <div class="h-20 flex items-center justify-center border-b border-white/10">
            <a href="{{ url('/') }}" class="text-2xl font-black tracking-tighter hover:opacity-80 transition">
                Mazraa<span class="text-[#facc15]">.com</span>
                <span class="text-[9px] uppercase tracking-widest text-indigo-200 block -mt-1 text-center">Transport Portal</span>
            </a>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('transport.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('transport.dashboard') ? 'bg-[#3730a3] text-white' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }} rounded-xl font-bold text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Dispatch & Trips
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 text-indigo-200 hover:bg-white/5 hover:text-white rounded-xl font-bold text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Fleet & Vehicles
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 text-indigo-200 hover:bg-white/5 hover:text-white rounded-xl font-bold text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Drivers
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-300 hover:bg-red-500 hover:text-white rounded-xl font-bold text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Log Out
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-2xl font-black text-[#312e81] tracking-tight">@yield('title', 'Transport Dashboard')</h1>
            <div class="flex items-center gap-4">
                <span class="text-xs font-black uppercase tracking-widest text-[#312e81] bg-indigo-50 px-4 py-2 rounded-full border border-indigo-100">
                    {{ Auth::user()->name }} (Transport Co.)
                </span>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </main>
</body>
</html>
