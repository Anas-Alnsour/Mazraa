@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-green-100 via-green-200 to-green-100 h-screen flex flex-col justify-center items-center text-center px-4">

    <!-- Background Shapes (Optional Modern Look) -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-green-300 rounded-full opacity-30 animate-pulse"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-green-400 rounded-full opacity-20 animate-pulse"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 max-w-4xl">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-6 text-green-900 leading-tight">
            Welcome to <span class="text-green-600">Mazraa.com</span>
        </h1>
        <p class="text-xl md:text-2xl mb-10 text-green-800">
            Your one-stop solution for farm booking, ordering supplies, and transport services.
        </p>

        <div class="flex flex-wrap justify-center gap-4">

            @guest
                <a href="{{ route('farms.index') }}" class="px-8 py-4 bg-green-600 text-white  rounded-xl shadow-lg hover:bg-green-700 transform hover:scale-105 transition-all duration-300">
                    Explore Farms
                </a>

                <a href="{{ route('register') }}" class="px-8 py-4 bg-green-500 text-white rounded-xl shadow-lg hover:bg-green-600 transform hover:scale-105 transition-all duration-300">
                    Register
                </a>

                <a href="{{ route('login') }}" class="px-8 py-4 bg-green-400 text-white rounded-xl shadow-lg hover:bg-green-500 transform hover:scale-105 transition-all duration-300">
                    Login
                </a>
            @endguest

            @auth
                <span class="px-8 py-4 bg-white text-green-900 rounded-xl border border-green-700 shadow-md">
                    {{ Auth::user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-8 py-4 bg-red-600 text-white rounded-xl shadow-lg hover:bg-red-700 transform hover:scale-105 transition-all duration-300">
                        Logout
                    </button>
                </form>
            @endauth

        </div>
    </div>

</section>
@endsection
