{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')
@section('title', 'Register')

@section('content')
    <div class="relative min-h-[calc(100vh-5rem)] flex items-center justify-center p-4 overflow-hidden"
        style="background-image:url('{{ asset('backgrounds/login&register.jpg') }}'); background-size:cover; background-position:center;">

        {{-- طبقة التعتيم والتدريج اللوني --}}
        <div class="absolute inset-0 bg-gradient-to-br from-green-900/80 via-black/60 to-black/80"></div>

        {{-- الكارد الزجاجي --}}
        <div class="relative z-10 w-full max-w-2xl bg-white/10 backdrop-blur-lg rounded-3xl border border-white/20 shadow-2xl overflow-hidden animate-fade-in-up">

            <div class="p-8 md:p-12">
                <div class="text-center mb-10">
                    <h2 class="text-4xl font-extrabold text-white mb-3 tracking-tight">Create Account</h2>
                    <p class="text-green-100 text-sm md:text-base">Join <span class="font-bold text-green-400">Mazraa.com</span> to manage your farm bookings and supplies.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-6" x-data="{ showPassword: false }">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label for="name" class="block text-sm font-semibold text-green-100 mb-2 pl-1">Full Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-green-200 group-focus-within:text-green-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus placeholder="John Doe"
                                    class="w-full pl-11 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white/10 transition-all duration-300" />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-300 pl-1" />
                        </div>

                        <div class="group">
                            <label for="phone" class="block text-sm font-semibold text-green-100 mb-2 pl-1">Phone Number</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-green-200 group-focus-within:text-green-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required placeholder="078xxxxxxx"
                                    class="w-full pl-11 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white/10 transition-all duration-300" />
                            </div>
                            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-red-300 pl-1" />
                        </div>
                    </div>

                    <div class="group">
                        <label for="email" class="block text-sm font-semibold text-green-100 mb-2 pl-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-green-200 group-focus-within:text-green-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="name@example.com"
                                class="w-full pl-11 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white/10 transition-all duration-300" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-300 pl-1" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label for="password" class="block text-sm font-semibold text-green-100 mb-2 pl-1">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-green-200 group-focus-within:text-green-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required placeholder="••••••••"
                                    class="w-full pl-11 pr-10 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white/10 transition-all duration-300" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-300 pl-1" />
                        </div>

                        <div class="group">
                            <label for="password_confirmation" class="block text-sm font-semibold text-green-100 mb-2 pl-1">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-green-200 group-focus-within:text-green-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" :type="showPassword ? 'text' : 'password'" required placeholder="••••••••"
                                    class="w-full pl-11 pr-10 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white/10 transition-all duration-300" />
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-300 pl-1" />
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="show_password" type="checkbox" @click="showPassword = !showPassword"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 cursor-pointer">
                        <label for="show_password" class="ml-2 block text-sm text-green-100 cursor-pointer hover:text-white transition">Show Password</label>
                    </div>

                    <div class="pt-4 flex flex-col md:flex-row items-center justify-between gap-4">
                        <a href="{{ route('login') }}" class="text-sm text-green-200 hover:text-white transition duration-200 underline decoration-green-400/50 hover:decoration-white">
                            Already have an account?
                        </a>

                        <button type="submit"
                            class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-500 hover:to-green-400 text-white font-bold rounded-xl shadow-lg hover:shadow-green-500/30 transform hover:-translate-y-0.5 transition-all duration-300">
                            Register Now
                        </button>
                    </div>
                </form>
            </div>

            <div class="h-1.5 w-full bg-gradient-to-r from-green-400 via-green-500 to-emerald-600"></div>
        </div>
    </div>

    {{-- Simple fade-in animation --}}
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection
