@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="relative min-h-[calc(100vh-8rem)]"
     style="background-image:url('{{ asset('backgrounds/login&register.jpg') }}'); background-size:cover; background-position:center;">
    <!-- طبقة تغميق بسيطة على الخلفية -->
    <div class="absolute inset-0 bg-black/45"></div>

    <!-- الكارد الزجاجي -->
    <div class="relative z-10 flex justify-center items-center min-h-[calc(100vh-8rem)] px-4">
        <div class="w-full max-w-xl
                    bg-white/25 backdrop-blur-xl
                    rounded-3xl shadow-2xl
                    border border-white/40
                    p-10">

            <h2 class="text-3xl font-extrabold text-white/90 mb-6 text-center drop-shadow">
                Login
            </h2>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="text-white/90 font-medium mb-2 block">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-5 py-3 rounded-2xl
                                  bg-white/30 text-white placeholder-white/70
                                  border border-white/40
                                  focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-transparent transition" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-200"/>
                </div>

                <div>
                    <label for="password" class="text-white/90 font-medium mb-2 block">Password</label>
                    <input id="password" name="password" type="password" required
                           class="w-full px-5 py-3 rounded-2xl
                                  bg-white/30 text-white placeholder-white/70
                                  border border-white/40
                                  focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-transparent transition" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-200"/>
                </div>

                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center text-white/90 text-sm">
                        <input type="checkbox"
                               class="rounded bg-white/30 border-white/40 text-green-500 focus:ring-green-300">
                        <span class="ml-2">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-white/90 hover:text-white underline" href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full bg-green-600/90 hover:bg-green-600
                               text-white px-6 py-3 rounded-2xl shadow-lg font-semibold
                               transition transform hover:scale-[1.02] mt-4">
                    Login
                </button>
            </form>

            <p class="mt-6 text-white/90 text-sm text-center">
                Don't have an account?
                <a href="{{ route('register') }}" class="underline hover:text-white">Register</a>
            </p>
        </div>
    </div>
</div>
@endsection