{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Home')

@section('content')
<section
    class="relative min-h-screen flex items-center justify-center text-center"
    style="
        background-image: url('{{ asset('backgrounds/home.JPG') }}');
        background-size: cover;         /* تغطّي كامل الشاشة */
        background-position: center;    /* تركيز وسط الصورة */
        background-repeat: no-repeat;
        background-attachment: fixed;   /* تأثير بارالاكس لطيف */
    "
>
    {{-- طبقة تعتيم لتحسين القراءة --}}
    <div class="absolute inset-0 bg-black/35"></div>

    {{-- المحتوى فوق الصورة --}}
    <div class="relative z-10 max-w-4xl px-4">
        <h1 class="text-4xl md:text-6xl font-extrabold mb-6 text-white drop-shadow">
            Welcome to <span class="text-green-300">Mazraa.com</span>
        </h1>

        <p class="text-lg md:text-2xl mb-10 text-white/90 drop-shadow">
            Your one-stop solution for farm booking, ordering supplies, and transport services.
        </p>

        <div class="flex flex-wrap justify-center gap-4">
            @guest
                <a href="{{ route('farms.index') }}"
                   class="px-6 md:px-8 py-3 md:py-4 bg-green-600 text-white rounded-xl shadow-lg hover:bg-green-700 transition">
                    Explore Farms
                </a>
                <a href="{{ route('register') }}"
                   class="px-6 md:px-8 py-3 md:py-4 bg-green-500 text-white rounded-xl shadow-lg hover:bg-green-600 transition">
                    Register
                </a>
                <a href="{{ route('login') }}"
                   class="px-6 md:px-8 py-3 md:py-4 bg-green-400 text-white rounded-xl shadow-lg hover:bg-green-500 transition">
                    Login
                </a>
            @endguest

            @auth
                <span class="px-6 md:px-8 py-3 md:py-4 bg-white/90 backdrop-blur text-green-900 rounded-xl border border-white/40 shadow-md">
                    {{ Auth::user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        class="px-6 md:px-8 py-3 md:py-4 bg-red-600 text-white rounded-xl shadow-lg hover:bg-red-700 transition">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</section>
@endsection