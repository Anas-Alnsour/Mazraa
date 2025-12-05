<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mazraa.com - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased text-gray-800 flex flex-col min-h-screen">
    <nav class="bg-white shadow-xl sticky top-0 z-50 border-b border-green-200/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-start h-20 space-x-10">

                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ url('/') }}"
                        class="text-4xl font-black tracking-tighter text-green-800 hover:text-green-600 transition duration-300">
                        Mazraa.<span class="text-green-600">com</span>
                    </a>
                </div>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center space-x-8 text-sm font-semibold text-gray-600">

                    @if (Auth::check() && Auth::user()->role === 'admin')
                        {{--  روابط الأدمن  --}}
                        <a href="{{ url('/') }}" class="py-2 px-3 relative group transition duration-300">
                            Home
                            <span
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-green-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>

                        <a href="{{ route('admin.farms.index') }}" class="py-2 px-3 relative group transition duration-300">
                            Manage Farms
                            <span
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-green-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                    @else
                        
                        <a href="{{ url('/') }}"
                            class="py-2 px-3 relative text-center group transition duration-300">
                            Home
                            <span
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-green-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>

                        <a href="{{ route('explore') }}"
                            class="py-2 px-3 relative text-center group transition duration-300">
                            Explore Farms
                            <span
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-green-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>

                        @auth
                            <a href="{{ route('bookings.my_bookings') }}"
                                class="py-2 px-3 group relative text-center transition">
                                My Bookings
                                <span
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-green-500 transform scale-x-0 group-hover:scale-x-100 transition duration-300"></span>
                            </a>
                            <a href="{{ route('supplies.index') }}"
                                class="py-2 px-3 relative text-center text-center group text-center transition duration-300">
                                Order
                                Supplies <span
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-green-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                            </a>
                            <a href="{{ route('orders.my_orders') }}"
                                class="py-2 px-3 relative text-center text-center text-center group transition duration-300">
                                My Orders
                                <span
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-green-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                            </a>
                            <a href="{{ route('transports.index') }}"
                                class="py-2 px-3 relative text-center text-center group transition duration-300"> Transport
                                <span
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-green-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                            </a>
                            <a href="{{ route('contact') }}"
                                class="py-2 px-3 relative text-center text-center group transition duration-300"> Contact
                                <span
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-green-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                            </a>
                            <a href="{{ route('about') }}"
                                class="py-2 px-3 relative text-center text-center group transition duration-300"> About
                                <span
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-green-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                            </a>
                            <a href="{{ route('favorites.index') }}"
                                class="py-2 px-3 group relative text-center transition">
                                Favorites
                                <span
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-green-500 transform scale-x-0 group-hover:scale-x-100 transition duration-300"></span>
                            </a>
                        @endauth
                    @endif
                </div>
                

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-5 text-sm font-medium">
                    @auth
                        <div
                            class="flex items-center gap-2 whitespace-nowrap bg-green-200 rounded-full py-2 px-4 text-green-800">
                            <span class="hidden lg:inline font-semibold">{{ Auth::user()->name }}</span>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-full text-sm font-semibold transition duration-300 shadow-lg shadow-red-200/50 hover:shadow-red-300/60 flex items-center gap-1 group">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-full text-sm font-semibold transition duration-300 shadow-lg shadow-green-300/50 hover:shadow-green-400/60 transform hover:scale-105">
                            Login
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </nav>





    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-24 right-6 z-50">
        @if (session('success'))
            <div
                class="max-w-xs shadow-2xl rounded-lg overflow-hidden transition-all duration-500 transform translate-x-0 opacity-100">
                <div class="bg-green-500 border-l-4 border-green-700 text-white p-4" role="alert">
                    <div class="flex items-center">
                        <div class="py-1 text-2xl"></div>
                        <div class="ml-3">
                            <p class="font-bold">Success!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div
                class="max-w-xs shadow-2xl rounded-lg overflow-hidden transition-all duration-500 transform translate-x-0 opacity-100">
                <div class="bg-red-500 border-l-4 border-red-700 text-white p-4" role="alert">
                    <div class="flex items-center">
                        <div class="py-1 text-2xl"></div>
                        <div class="ml-3">
                            <p class="font-bold">Error!</p>
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>


    <main class="flex-1">
        @yield('content')
    </main>



    <footer class="bg-gray-800 text-white border-t-8 border-green-700">
        <div class="max-w-7xl mx-auto px-6 py-12">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">

                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-3xl font-extrabold mb-4 text-green-400">Mazraa.com</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Your trusted destination to explore farms, book stays, order supplies,
                        and enjoy an unforgettable experience in nature. We are committed to connecting you
                        with the best of rural life.
                    </p>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-5 border-b-2 border-green-600 pb-1">Quick Links</h3>
                    @if (Auth::check() && Auth::user()->role === 'admin')
                        <ul class="space-y-3 text-sm">
                            <li><a href="/"
                                    class="text-gray-400 hover:text-green-300 transition duration-200 flex items-center gap-2">
                                    Home</a></li>
                            <li><a href="{{ route('explore') }}"
                                    class="text-gray-400 hover:text-green-300 transition duration-200 flex items-center gap-2">
                                    Manage Farms</a></li>

                        </ul>
                    @else
                        <ul class="space-y-3 text-sm">
                            <li><a href="/"
                                    class="text-gray-400 hover:text-green-300 transition duration-200 flex items-center gap-2">
                                    Home</a></li>
                            <li><a href="{{ route('explore') }}"
                                    class="text-gray-400 hover:text-green-300 transition duration-200 flex items-center gap-2">
                                    Explore Farms</a></li>
                            <li><a href="{{ route('contact') }}"
                                    class="text-gray-400 hover:text-green-300 transition duration-200 flex items-center gap-2">
                                    Contact</a></li>
                            <li><a href="{{ route('about') }}"
                                    class="text-gray-400 hover:text-green-300 transition duration-200 flex items-center gap-2">
                                    About</a></li>
                        </ul>
                    @endif
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-5 border-b-2 border-green-600 pb-1">Stay Connected</h3>
                    <div class="mt-6 text-gray-400 text-sm">
                        <p>Email: info@mazraa.com</p>
                        <p>Phone: +962 7XXXXXXXX</p>
                    </div>
                </div>

            </div>

            <div class="border-t border-gray-700 mt-10 pt-6 text-center text-gray-500 text-xs">
                © {{ date('Y') }} Mazraa.com — All rights reserved. Built with passion for farming and nature.
            </div>

        </div>
    </footer>

</body>

</html>
