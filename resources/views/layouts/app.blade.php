<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mazraa.com - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>

<body class="bg-gray-50 font-sans antialiased text-gray-800 flex flex-col min-h-screen">

    <nav x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                <div class="flex items-center">

                    <div class="flex-shrink-0">
                        <a href="{{ url('/') }}" class="text-3xl font-black tracking-tighter text-green-900 hover:text-green-700 transition duration-300 flex items-center">
                            Mazraa<span class="text-green-600">.com</span>
                        </a>
                    </div>

                    <div class="hidden lg:flex items-center ml-10 space-x-6 xl:space-x-8">
                        @php
                            // ستايل الروابط
                            $linkClass = "text-sm font-bold text-gray-600 hover:text-green-600 transition duration-300 py-2 border-b-2 border-transparent hover:border-green-500 whitespace-nowrap";
                            $activeClass = "text-green-700 border-green-600";
                        @endphp

                        @if (Auth::check() && Auth::user()->role === 'admin')
                            {{-- Admin Links --}}
                            <a href="{{ url('/') }}" class="{{ $linkClass }} {{ request()->is('/') ? $activeClass : '' }}">Home</a>
                            <a href="{{ route('admin.farms.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.farms.*') ? $activeClass : '' }}">Manage Farms</a>
                            <a href="{{ route('admin.supplies.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.supplies.*') ? $activeClass : '' }}">Manage Supplies</a>
                            <a href="{{ route('admin.contact.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.contact.*') ? $activeClass : '' }}">Contact Messages</a>
                        @else
                            {{-- User Links (Original Names) --}}
                            <a href="{{ url('/') }}" class="{{ $linkClass }} {{ request()->is('/') ? $activeClass : '' }}">Home</a>
                            <a href="{{ route('explore') }}" class="{{ $linkClass }} {{ request()->routeIs('explore') ? $activeClass : '' }}">Explore Farms</a>

                            @auth
                                <a href="{{ route('bookings.my_bookings') }}" class="{{ $linkClass }} {{ request()->routeIs('bookings.*') ? $activeClass : '' }}">My Bookings</a>
                                <a href="{{ route('supplies.index') }}" class="{{ $linkClass }} {{ request()->routeIs('supplies.*') ? $activeClass : '' }}">Order Supplies</a>
                                <a href="{{ route('orders.my_orders') }}" class="{{ $linkClass }} {{ request()->routeIs('orders.*') ? $activeClass : '' }}">My Orders</a>
                                <a href="{{ route('transports.index') }}" class="{{ $linkClass }} {{ request()->routeIs('transports.*') ? $activeClass : '' }}">Transport</a>
                                <a href="{{ route('favorites.index') }}" class="{{ $linkClass }} {{ request()->routeIs('favorites.*') ? $activeClass : '' }}">Favorites</a>
                            @endauth

                            <a href="{{ route('contact') }}" class="{{ $linkClass }} {{ request()->routeIs('contact') ? $activeClass : '' }}">Contact</a>
                            <a href="{{ route('about') }}" class="{{ $linkClass }} {{ request()->routeIs('about') ? $activeClass : '' }}">About</a>
                        @endif
                    </div>
                </div>

                <div class="hidden lg:flex items-center space-x-4 ml-auto">
                    @auth
                        <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                            <span class="text-sm font-bold text-gray-700 max-w-[150px] truncate">
                                {{ Auth::user()->name }}
                            </span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="group bg-red-50 text-red-500 hover:bg-red-100 p-2 rounded-full transition duration-300" title="Logout">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-green-600 transition">Login</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-green-600 rounded-full hover:bg-green-700 shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                            Register
                        </a>
                    @endauth
                </div>

                <div class="lg:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-green-600 p-2 focus:outline-none">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-collapse x-cloak class="lg:hidden border-t border-gray-100 bg-white shadow-lg">
            <div class="px-4 pt-2 pb-6 space-y-1">
                @php
                    $mobileLink = "block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-green-700 hover:bg-green-50 transition";
                @endphp

                @if (Auth::check() && Auth::user()->role === 'admin')
                    <a href="{{ url('/') }}" class="{{ $mobileLink }}">Home</a>
                    <a href="{{ route('admin.farms.index') }}" class="{{ $mobileLink }}">Manage Farms</a>
                    <a href="{{ route('admin.supplies.index') }}" class="{{ $mobileLink }}">Manage Supplies</a>
                    <a href="{{ route('admin.contact.index') }}" class="{{ $mobileLink }}">Contact Messages</a>
                @else
                    <a href="{{ url('/') }}" class="{{ $mobileLink }}">Home</a>
                    <a href="{{ route('explore') }}" class="{{ $mobileLink }}">Explore Farms</a>
                    @auth
                        <a href="{{ route('bookings.my_bookings') }}" class="{{ $mobileLink }}">My Bookings</a>
                        <a href="{{ route('supplies.index') }}" class="{{ $mobileLink }}">Order Supplies</a>
                        <a href="{{ route('orders.my_orders') }}" class="{{ $mobileLink }}">My Orders</a>
                        <a href="{{ route('transports.index') }}" class="{{ $mobileLink }}">Transport</a>
                        <a href="{{ route('favorites.index') }}" class="{{ $mobileLink }}">Favorites</a>
                    @endauth
                    <a href="{{ route('contact') }}" class="{{ $mobileLink }}">Contact</a>
                    <a href="{{ route('about') }}" class="{{ $mobileLink }}">About</a>
                @endif

                <div class="border-t border-gray-100 mt-4 pt-4">
                    @auth
                        <div class="flex items-center px-3 mb-3">
                            <div class="text-base font-medium text-gray-800">Signed in as <span class="font-bold text-green-700">{{ Auth::user()->name }}</span></div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 mt-1 rounded-md text-base font-medium text-white bg-green-600 text-center hover:bg-green-700">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" class="fixed top-24 right-5 z-50 flex flex-col gap-3 pointer-events-none">
        @if (session('success'))
            <div x-show="show" x-transition.duration.300ms class="pointer-events-auto bg-white border-l-4 border-green-500 rounded-lg shadow-xl p-4 flex items-start gap-3 w-80">
                <div class="text-green-500 flex-shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                <div class="flex-1"><h4 class="font-bold text-gray-900 text-sm">Success</h4><p class="text-gray-600 text-xs mt-1">{{ session('success') }}</p></div>
                <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
            </div>
        @endif
        @if (session('error'))
            <div x-show="show" x-transition.duration.300ms class="pointer-events-auto bg-white border-l-4 border-red-500 rounded-lg shadow-xl p-4 flex items-start gap-3 w-80">
                <div class="text-red-500 flex-shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                <div class="flex-1"><h4 class="font-bold text-gray-900 text-sm">Error</h4><p class="text-gray-600 text-xs mt-1">{{ session('error') }}</p></div>
                <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
            </div>
        @endif
    </div>

    <main class="flex-1 w-full">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-300 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-3xl font-extrabold mb-4 text-white">Mazraa<span class="text-green-500">.com</span></h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Your trusted destination to explore farms, book stays, order supplies, and enjoy an unforgettable experience in nature.</p>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-5 border-b border-gray-700 pb-2">Quick Links</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="/" class="hover:text-green-400 transition">Home</a></li>
                        <li><a href="{{ route('explore') }}" class="hover:text-green-400 transition">Explore Farms</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-green-400 transition">Contact</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-green-400 transition">About</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-5 border-b border-gray-700 pb-2">Connect</h3>
                    <div class="text-gray-400 text-sm space-y-3">
                        <p class="flex items-center gap-2"> info@mazraa.com</p>
                        <p class="flex items-center gap-2"> +962 79 123 4567</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-10 pt-6 text-center text-gray-500 text-xs">
                © {{ date('Y') }} Mazraa.com — All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
