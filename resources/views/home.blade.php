{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Home')

@section('content')
<section class="relative w-full min-h-screen flex flex-col items-center justify-center overflow-hidden bg-gray-900">

    {{-- 1. الخلفية مع تأثير التدرج --}}
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('backgrounds/home.JPG') }}" alt="Mazraa Home"
             class="w-full h-full object-cover opacity-80 scale-105 animate-slow-zoom"> {{-- تأثير زووم بطيء اختياري --}}

        {{-- تدرج لوني لجعل النص مقروءاً بشكل ممتاز --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-green-900/90"></div>
    </div>

    {{-- 2. المحتوى الرئيسي --}}
    <div class="relative z-10 container mx-auto px-4 text-center space-y-8">

        {{-- العناوين --}}
        <div class="space-y-4 animate-fade-in-up">
            <span class="inline-block py-1 px-3 rounded-full bg-green-500/20 border border-green-500/50 text-green-300 text-sm font-semibold tracking-wider uppercase backdrop-blur-md mb-2">
                Discover Nature's Beauty
            </span>

            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight drop-shadow-2xl">
                Welcome to <br class="md:hidden" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-200">
                    Mazraa.com
                </span>
            </h1>

            <p class="text-lg md:text-2xl text-gray-200 max-w-2xl mx-auto leading-relaxed font-light">
                Your one-stop destination for farm booking, ordering supplies, and secure transport services.
            </p>
        </div>

        {{-- 3. الأزرار (Guest & Auth) --}}
        <div class="flex flex-col md:flex-row items-center justify-center gap-4 mt-8 animate-fade-in-up delay-100">
            @guest
                {{-- زر Explore (الرئيسي) --}}
                <a href="{{ route('farms.index') }}"
                   class="group relative px-8 py-4 bg-green-600 hover:bg-green-500 text-white font-bold text-lg rounded-2xl shadow-lg shadow-green-500/30 transition-all duration-300 transform hover:-translate-y-1 hover:scale-105 flex items-center gap-2">
                    <span>Explore Farms</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>

                {{-- مجموعة أزرار الدخول (ثانوية - Glass style) --}}
                <div class="flex gap-4">
                    <a href="{{ route('register') }}"
                       class="px-8 py-4 bg-white/10 backdrop-blur-md border border-white/20 text-white font-semibold rounded-2xl hover:bg-white/20 transition-all duration-300">
                        Register
                    </a>
                    <a href="{{ route('login') }}"
                       class="px-8 py-4 text-green-300 font-semibold hover:text-white transition-colors duration-300">
                        Login
                    </a>
                </div>
            @endguest

            @auth
                <div class="flex flex-col md:flex-row items-center gap-4 bg-white/10 backdrop-blur-md p-2 rounded-3xl border border-white/10">
                    <div class="flex items-center gap-3 px-4 py-2">
                        <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="text-left">
                            <p class="text-xs text-green-300 uppercase">Welcome back</p>
                            <p class="text-white font-bold">{{ Auth::user()->name }}</p>
                        </div>
                    </div>

                    <div class="h-px w-full md:h-10 md:w-px bg-white/20"></div>

                    <div class="flex gap-2">
                        <a href="{{ route('farms.index') }}"
                           class="px-6 py-3 bg-green-600 hover:bg-green-500 text-white rounded-xl font-semibold transition shadow-lg">
                            Browse Farms
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="px-6 py-3 bg-red-500/80 hover:bg-red-600 text-white rounded-xl font-semibold transition backdrop-blur-sm">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>

        {{-- 4. أيقونات الخدمات (إضافة جمالية) --}}
        <div class="grid grid-cols-3 gap-4 max-w-2xl mx-auto mt-12 pt-8 border-t border-white/10 opacity-80">
            <div class="flex flex-col items-center text-white space-y-2">
                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-xs md:text-sm font-medium uppercase tracking-wide">Cozy Stays</span>
            </div>
            <div class="flex flex-col items-center text-white space-y-2">
                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span class="text-xs md:text-sm font-medium uppercase tracking-wide">Fresh Supplies</span>
            </div>
            <div class="flex flex-col items-center text-white space-y-2">
                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span class="text-xs md:text-sm font-medium uppercase tracking-wide">Fast Transport</span>
            </div>
        </div>

    </div>
</section>
@endsection
