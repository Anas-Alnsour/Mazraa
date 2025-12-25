@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="relative min-h-[calc(100vh-5rem)] flex items-center justify-center p-4 overflow-hidden"
        style="background-image:url('{{ asset('backgrounds/login&register.jpg') }}'); background-size:cover; background-position:center;">

        {{-- طبقة التعتيم والتدريج اللوني --}}
        <div class="absolute inset-0 bg-gradient-to-br from-green-900/80 via-black/60 to-black/80"></div>

        {{-- الكارد الزجاجي --}}
        <div class="relative z-10 w-full max-w-lg bg-white/10 backdrop-blur-lg rounded-3xl border border-white/20 shadow-2xl overflow-hidden animate-fade-in-up">

            <div class="p-8 md:p-12">
                <div class="text-center mb-10">
                    <h2 class="text-4xl font-extrabold text-white mb-3 tracking-tight">Welcome Back!</h2>
                    <p class="text-green-100 text-sm md:text-base">Please sign in to access your account.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ showPassword: false }">
                    @csrf

                    <div class="group">
                        <label for="email" class="block text-sm font-semibold text-green-100 mb-2 pl-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-green-200 group-focus-within:text-green-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com"
                                class="w-full pl-11 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white/10 transition-all duration-300" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-300 pl-1" />
                    </div>

                    <div class="group">
                        <label for="password" class="block text-sm font-semibold text-green-100 mb-2 pl-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-green-200 group-focus-within:text-green-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required placeholder="••••••••"
                                class="w-full pl-11 pr-10 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white/10 transition-all duration-300" />

                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-green-200 hover:text-white transition focus:outline-none">
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-300 pl-1" />
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center text-green-100 hover:text-white cursor-pointer transition">
                            <input type="checkbox" name="remember" class="rounded border-white/30 bg-white/10 text-green-500 focus:ring-green-500 focus:ring-offset-0 transition">
                            <span class="ml-2 font-medium">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="font-medium text-green-200 hover:text-white transition underline decoration-green-400/50 hover:decoration-white">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full px-8 py-3 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-500 hover:to-green-400 text-white font-bold rounded-xl shadow-lg hover:shadow-green-500/30 transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                        <span>Sign In</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-green-100">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-bold text-white hover:text-green-300 transition underline decoration-white/50 hover:decoration-green-300">
                            Create one now
                        </a>
                    </p>
                </div>
            </div>

            <div class="h-1.5 w-full bg-gradient-to-r from-green-400 via-green-500 to-emerald-600"></div>
        </div>
    </div>

    {{-- Animations --}}
    <style>
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        [x-cloak] { display: none !important; }
    </style>
@endsection
