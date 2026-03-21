<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mazraa') }} - Dashboard</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <div x-show="sidebarOpen" x-transition.opacity
         class="fixed inset-0 z-20 bg-gray-900/50 lg:hidden"
         @click="sidebarOpen = false" x-cloak style="display: none;"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-30 w-64 bg-green-900 text-white transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 lg:flex lg:flex-col shadow-xl h-screen shrink-0">

        <div class="flex items-center justify-center h-20 border-b border-green-800 shrink-0">
            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-2 group">
                <svg class="w-8 h-8 text-green-400 group-hover:text-green-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-2xl font-bold tracking-tight text-white group-hover:text-green-50 transition-colors">Mazraa</span>
            </a>
        </div>

        <div class="flex-1 overflow-y-auto py-6 px-4">
            <nav class="space-y-2">

                <div class="mb-6 px-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-800 text-green-100 uppercase tracking-wider border border-green-700">
                        {{ str_replace('_', ' ', Auth::user()->role) }} Panel
                    </span>
                </div>

                <a href="{{ url('/owner/dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->is('owner/dashboard') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800 hover:text-white' }} group transition-colors">
                    <svg class="w-5 h-5 {{ request()->is('owner/dashboard') ? 'text-white' : 'text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Overview
                </a>

                <a href="{{ url('/owner/farms') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->is('owner/farms*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800 hover:text-white' }} group transition-colors">
                    <svg class="w-5 h-5 {{ request()->is('owner/farms*') ? 'text-white' : 'text-green-400' }} group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    My Farms
                </a>

                <a href="{{ route('owner.financials') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('owner.financials') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800 hover:text-white' }} group transition-colors">
                    <svg class="w-5 h-5 {{ request()->routeIs('owner.financials') ? 'text-white' : 'text-green-400' }} group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Financials
                </a>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('profile.edit') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800 hover:text-white' }} group transition-colors">
                    <svg class="w-5 h-5 {{ request()->routeIs('profile.edit') ? 'text-white' : 'text-green-400' }} group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Settings
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-green-800 shrink-0">
            <p class="text-xs text-green-300 text-center">Mazraa System v2.0</p>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden transition-all duration-300">

        <header class="bg-white border-b border-gray-200 shadow-sm shrink-0 z-10">
            <div class="flex items-center justify-between px-4 sm:px-6 h-20">

                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-green-600 focus:outline-none p-2 rounded-md transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    @if (isset($header))
                        <div class="hidden sm:block">
                            {{ $header }}
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4">

                    <x-dropdown align="right" width="64">
    <x-slot name="trigger">
        <button class="relative p-2 text-gray-400 hover:text-green-600 transition-colors rounded-full hover:bg-gray-100 focus:outline-none">
            @if(isset($pendingApprovalCount) && $pendingApprovalCount > 0)
                <span class="absolute top-1 right-1 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border border-white"></span>
                </span>
            @endif
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
        </button>
    </x-slot>

    <x-slot name="content">
        <div class="block px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
            Notifications
        </div>
        @if(isset($pendingApprovalCount) && $pendingApprovalCount > 0)
            <a href="{{ route('owner.dashboard') }}" class="block px-4 py-4 hover:bg-green-50 transition-colors">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">New Booking Requests</p>
                        <p class="text-xs text-gray-500 mt-1">You have {{ $pendingApprovalCount }} booking(s) pending your approval.</p>
                    </div>
                </div>
            </a>
        @else
            <div class="px-4 py-6 text-center">
                <p class="text-sm text-gray-500">No recent notifications</p>
            </div>
        @endif
    </x-slot>
</x-dropdown>
                    <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                        <div class="hidden md:flex flex-col items-end">
                            <span class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</span>
                            <span class="text-xs font-medium text-gray-500">{{ Auth::user()->email }}</span>
                        </div>

                        <div class="w-10 h-10 rounded-full bg-green-100 border-2 border-green-200 flex items-center justify-center text-green-700 font-bold overflow-hidden shadow-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" class="group bg-red-50 text-red-500 hover:bg-red-500 hover:text-white p-2 rounded-full transition-all duration-300 shadow-sm" title="Log Out">
                                <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 lg:p-10">
            @yield('content')

            {{ $slot ?? '' }}
        </main>

    </div>

</body>
</html>
