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

<body class="bg-gray-50 font-sans antialiased text-gray-800 flex flex-col min-h-screen selection:bg-green-500 selection:text-white">

    <nav x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 w-full bg-white/80 backdrop-blur-xl shadow-[0_4px_30px_rgba(0,0,0,0.03)] border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                <div class="flex items-center gap-8">
                    <div class="flex-shrink-0">
                        <a href="{{ url('/') }}" class="group flex items-center gap-2 text-3xl font-black tracking-tighter text-[#183126] transition duration-300">
                            <div class="bg-gradient-to-tr from-green-600 to-green-400 p-1.5 rounded-xl text-white shadow-md group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </div>
                            Mazraa<span class="text-green-500">.com</span>
                        </a>
                    </div>

                    <div class="hidden lg:flex items-center space-x-1">
                        @php
                            $linkClass = "px-4 py-2 rounded-full text-sm font-bold text-gray-500 hover:text-green-700 hover:bg-green-50/80 transition-all duration-300 whitespace-nowrap";
                            $activeClass = "text-green-800 bg-green-100/50 shadow-sm";
                        @endphp

                        @if (Auth::check() && Auth::user()->role === 'admin')
                            <a href="{{ url('/') }}" class="{{ $linkClass }} {{ request()->is('/') ? $activeClass : '' }}">Home</a>
                            <a href="{{ route('admin.farms.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.farms.*') ? $activeClass : '' }}">Manage Farms</a>
                            <a href="{{ route('admin.supplies.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.supplies.*') ? $activeClass : '' }}">Manage Supplies</a>
                            <a href="{{ route('admin.contact.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.contact.*') ? $activeClass : '' }}">Contact Messages</a>
                        @else
                            <a href="{{ url('/') }}" class="{{ $linkClass }} {{ request()->is('/') ? $activeClass : '' }}">Home</a>
                            <a href="{{ route('explore') }}" class="{{ $linkClass }} {{ request()->routeIs('explore') ? $activeClass : '' }}">Explore Farms</a>

                            @auth
                                <a href="{{ route('bookings.my_bookings') }}" class="{{ $linkClass }} {{ request()->routeIs('bookings.*') ? $activeClass : '' }}">My Bookings</a>
                                <a href="{{ route('supplies.market') }}" class="{{ $linkClass }} {{ request()->routeIs('supplies.*') ? $activeClass : '' }}">Order Supplies</a>
                                <a href="{{ route('orders.my_orders') }}" class="{{ $linkClass }} {{ request()->routeIs('orders.*') ? $activeClass : '' }}">My Orders</a>
                                <a href="{{ route('transports.index') }}" class="{{ $linkClass }} {{ request()->routeIs('transports.*') ? $activeClass : '' }}">Transport</a>
                                <a href="{{ route('favorites.index') }}" class="{{ $linkClass }} {{ request()->routeIs('favorites.*') ? $activeClass : '' }}">Favorites</a>
                            @endauth

                            <a href="{{ route('contact') }}" class="{{ $linkClass }} {{ request()->routeIs('contact') ? $activeClass : '' }}">Contact</a>
                            <a href="{{ route('about') }}" class="{{ $linkClass }} {{ request()->routeIs('about') ? $activeClass : '' }}">About</a>
                        @endif
                    </div>
                </div>

                <div class="hidden lg:flex items-center gap-3 ml-auto">
                    @auth
                        @php
                            $role = Auth::user()->role;
                            $dashboardLink = match($role) {
                                'admin' => '/admin',
                                'farm_owner' => '/owner/dashboard',
                                'supply_company' => '/supplies/dashboard',
                                'transport_company' => '/transport/dashboard',
                                'supply_driver' => '/delivery/orders',
                                'transport_driver' => '/shuttle/trips',
                                default => '/dashboard'
                            };
                            $isBusiness = $role !== 'user';
                        @endphp

                        <a href="{{ url($dashboardLink) }}" class="px-5 py-2 text-sm font-bold text-green-700 bg-green-50 rounded-full hover:bg-green-100 hover:shadow-sm transition-all duration-300">
                            {{ $isBusiness ? 'Dashboard' : 'My Profile' }}
                        </a>

                        <div class="relative ml-2" x-data="{ profileOpen: false }" @click.away="profileOpen = false">
                            <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 focus:outline-none pl-3 border-l border-gray-200">
                                <div class="text-right hidden md:block">
                                    <p class="text-sm font-bold text-gray-800 leading-tight">{{ explode(' ', Auth::user()->name)[0] }}</p>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#1d5c42] to-green-500 text-white flex items-center justify-center font-black shadow-md border-2 border-white transform transition hover:scale-105">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </button>

                            <div x-show="profileOpen" x-transition x-cloak class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.1)] border border-gray-100 py-2 z-50 overflow-hidden">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors text-left">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Secure Log Out
                                    </button>
                                </form>
                            </div>
                        </div>

                    @else
                        <div class="relative" x-data="{ loginOpen: false }" @click.away="loginOpen = false">
                            <button @click="loginOpen = !loginOpen" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold text-gray-600 hover:text-[#1d5c42] hover:bg-green-50 rounded-full transition-all focus:outline-none">
                                Log in
                                <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-180': loginOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="loginOpen" x-transition.opacity.duration.200ms x-cloak class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100 py-3 z-50">

                                <div class="px-4 pb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Consumer Portal</div>
                                <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-green-700 hover:bg-green-50 transition-colors group">
                                    <div class="p-1.5 bg-green-100 text-green-600 rounded-lg group-hover:bg-green-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
                                    User Login
                                </a>

                                <div class="border-t border-gray-100 my-2"></div>

                                <div class="px-4 pb-2 pt-1 text-[10px] font-black text-gray-400 uppercase tracking-widest">Business & Logistics</div>

                                <a href="{{ route('portal.login') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-blue-700 hover:bg-blue-50 transition-colors group">
                                    <div class="p-1.5 bg-blue-100 text-blue-600 rounded-lg group-hover:bg-blue-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
                                    Farm Owner / Admin
                                </a>

                                <a href="{{ route('transport-driver.login') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-amber-700 hover:bg-amber-50 transition-colors group">
                                    <div class="p-1.5 bg-amber-100 text-amber-600 rounded-lg group-hover:bg-amber-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg></div>
                                    Transport Driver
                                </a>

                                <a href="{{ route('supply-driver.login') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-teal-700 hover:bg-teal-50 transition-colors group">
                                    <div class="p-1.5 bg-teal-100 text-teal-600 rounded-lg group-hover:bg-teal-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg></div>
                                    Supply Driver
                                </a>
                            </div>
                        </div>

                        <div class="relative ml-2" x-data="{ regOpen: false }" @click.away="regOpen = false">
                            <button @click="regOpen = !regOpen" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-black text-white bg-gradient-to-r from-[#1d5c42] to-green-600 rounded-full hover:from-[#154230] hover:to-green-700 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 focus:outline-none">
                                Get Started
                                <svg class="w-4 h-4 transition-transform duration-300 opacity-70" :class="{'rotate-180': regOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="regOpen" x-transition.opacity.duration.200ms x-cloak class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100 py-3 z-50 overflow-hidden">
                                <a href="{{ route('register') }}" class="block px-5 py-3 text-sm font-bold text-green-700 hover:bg-green-50 transition-colors">
                                    Register as User
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="{{ route('partner.register') }}" class="block px-5 py-3 text-sm font-bold text-blue-700 hover:bg-blue-50 transition-colors">
                                    List Your Farm
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>

                <div class="lg:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-[#1d5c42] p-2 bg-gray-100 rounded-xl focus:outline-none transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-collapse x-cloak class="lg:hidden bg-white border-t border-gray-100 shadow-2xl absolute w-full left-0">
            <div class="px-4 py-4 space-y-1">
                @php
                    $mobileLink = "block px-4 py-3 rounded-xl text-base font-bold text-gray-600 hover:text-green-700 hover:bg-green-50 transition-colors";
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
                        <a href="{{ route('supplies.market') }}" class="{{ $mobileLink }}">Order Supplies</a>
                        <a href="{{ route('orders.my_orders') }}" class="{{ $mobileLink }}">My Orders</a>
                        <a href="{{ route('transports.index') }}" class="{{ $mobileLink }}">Transport</a>
                        <a href="{{ route('favorites.index') }}" class="{{ $mobileLink }}">Favorites</a>
                    @endauth
                    <a href="{{ route('contact') }}" class="{{ $mobileLink }}">Contact</a>
                    <a href="{{ route('about') }}" class="{{ $mobileLink }}">About</a>
                @endif

                <div class="border-t border-gray-100 mt-4 pt-4">
                    @auth
                        <a href="{{ url($dashboardLink) }}" class="block px-4 py-3 rounded-xl text-base font-bold text-white bg-green-600 hover:bg-green-700 text-center shadow-md">
                            {{ $role !== 'user' ? 'Go to Dashboard' : 'My Profile' }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full text-center px-4 py-3 rounded-xl text-base font-bold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">Log out</button>
                        </form>
                    @else
                        <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Login Portals</p>
                        <a href="{{ route('login') }}" class="{{ $mobileLink }} text-green-700">Customer Login</a>
                        <a href="{{ route('portal.login') }}" class="{{ $mobileLink }} text-blue-700">Farm Owner Portal</a>
                        <a href="{{ route('transport-driver.login') }}" class="{{ $mobileLink }} text-amber-700">Transport Driver</a>
                        <a href="{{ route('supply-driver.login') }}" class="{{ $mobileLink }} text-teal-700">Supply Driver</a>

                        <div class="mt-4 grid grid-cols-2 gap-2">
                            <a href="{{ route('register') }}" class="block px-3 py-3 rounded-xl text-sm font-bold text-white bg-[#1d5c42] text-center shadow-md">Sign Up</a>
                            <a href="{{ route('partner.register') }}" class="block px-3 py-3 rounded-xl text-sm font-bold text-blue-700 bg-blue-50 border border-blue-100 text-center">List Farm</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" class="fixed top-24 right-5 z-50 flex flex-col gap-3 pointer-events-none">
        @if (session('success'))
            <div x-show="show" x-transition.duration.300ms class="pointer-events-auto bg-white border-l-4 border-green-500 rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] p-4 flex items-start gap-3 w-80">
                <div class="text-green-500 flex-shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                <div class="flex-1"><h4 class="font-bold text-gray-900 text-sm">Success</h4><p class="text-gray-500 text-xs mt-1 leading-relaxed">{{ session('success') }}</p></div>
                <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-600 focus:outline-none">&times;</button>
            </div>
        @endif
        @if (session('error'))
            <div x-show="show" x-transition.duration.300ms class="pointer-events-auto bg-white border-l-4 border-red-500 rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] p-4 flex items-start gap-3 w-80">
                <div class="text-red-500 flex-shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                <div class="flex-1"><h4 class="font-bold text-gray-900 text-sm">Error</h4><p class="text-gray-500 text-xs mt-1 leading-relaxed">{{ session('error') }}</p></div>
                <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-600 focus:outline-none">&times;</button>
            </div>
        @endif
    </div>

    <main class="flex-1 w-full">
        @yield('content')
    </main>

    <footer class="bg-[#0f172a] text-gray-300 border-t border-gray-800 mt-auto relative overflow-hidden">
        <div class="absolute inset-0 opacity-5 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-green-400 via-transparent to-transparent"></div>
        <div class="max-w-7xl mx-auto px-6 py-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-3xl font-black mb-4 text-white tracking-tighter">Mazraa<span class="text-green-500">.com</span></h3>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-sm">Your trusted destination to explore farms, book stays, order supplies, and enjoy an unforgettable experience in nature.</p>
                </div>
                <div>
                    <h3 class="text-xs font-black text-white uppercase tracking-widest mb-5 border-b border-gray-800 pb-3">Quick Links</h3>
                    <ul class="space-y-3 text-sm font-medium">
                        <li><a href="/" class="hover:text-green-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-gray-700"></span> Home</a></li>
                        <li><a href="{{ route('explore') }}" class="hover:text-green-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-gray-700"></span> Explore Farms</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-green-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-gray-700"></span> Contact</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-green-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-gray-700"></span> About</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xs font-black text-white uppercase tracking-widest mb-5 border-b border-gray-800 pb-3">Business & Connect</h3>
                    <div class="text-gray-400 text-sm font-medium space-y-3">
                        <p class="flex items-center gap-3"><svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> info@mazraa.com</p>
                        <p class="flex items-center gap-3"><svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> +962 79 123 4567</p>
                        <div class="pt-4 mt-4 border-t border-gray-800">
                            <a href="{{ route('portal.login') }}" class="text-blue-400 hover:text-blue-300 font-bold transition-colors flex items-center gap-2 group">
                                <div class="p-1.5 bg-blue-500/10 rounded-lg group-hover:bg-blue-500/20 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                Business Portal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-6 text-center text-gray-500 text-xs font-medium flex flex-col md:flex-row justify-between items-center gap-4">
                <span>© {{ date('Y') }} Mazraa.com — Crafted with excellence.</span>
            </div>
        </div>
    </footer>
</body>
</html>
