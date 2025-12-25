@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="relative min-h-[calc(100vh-5rem)] flex items-center justify-center p-4 overflow-hidden"
        style="background-image:url('{{ asset('backgrounds/login&register.jpg') }}'); background-size:cover; background-position:center;">

        {{-- طبقة التعتيم والتدريج اللوني --}}
        <div class="absolute inset-0 bg-gradient-to-br from-green-900/80 via-black/60 to-black/80"></div>

        {{-- الكارد الزجاجي --}}
        <div class="relative z-10 w-full max-w-lg bg-white/10 backdrop-blur-lg rounded-3xl border border-white/20 shadow-2xl overflow-hidden animate-fade-in-up">

            <div class="p-8 md:p-12">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-extrabold text-white mb-3 tracking-tight">Forgot Password?</h2>
                    <p class="text-green-100 text-sm leading-relaxed">
                        No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-6 bg-green-500/20 border border-green-500/50 p-4 rounded-xl flex items-start gap-3 shadow-inner">
                        <svg class="w-6 h-6 text-green-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-green-100 text-sm font-medium">{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div class="group">
                        <label for="email" class="block text-sm font-semibold text-green-100 mb-2 pl-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-green-200 group-focus-within:text-green-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com"
                                class="w-full pl-11 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white/10 transition-all duration-300" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-300 pl-1" />
                    </div>

                    <button type="submit"
                        class="w-full px-8 py-3 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-500 hover:to-green-400 text-white font-bold rounded-xl shadow-lg hover:shadow-green-500/30 transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                        <span>Email Password Reset Link</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center text-sm text-green-200 hover:text-white transition group">
                        <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Login
                    </a>
                </div>
            </div>

            <div class="h-1.5 w-full bg-gradient-to-r from-green-400 via-green-500 to-emerald-600"></div>
        </div>
    </div>

    {{-- Animations --}}
    <style>
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
@endsection
