@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')

<style>
    /* Custom Animations for Dashboard */
    .fade-in-up-stagger {
        animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both;
    }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="bg-[#f8fafc] min-h-screen pb-24 font-sans selection:bg-[#1d5c42] selection:text-white">

    {{-- ==========================================
         1. DASHBOARD HERO SECTION (MINI)
         ========================================== --}}
    <div class="relative w-full h-[45vh] min-h-[400px] flex items-center justify-center bg-[#020617] overflow-hidden">
        {{-- Animated Background Image --}}
        <img src="{{ asset('backgrounds/home.JPG') }}" alt="Dashboard Background"
             class="absolute inset-0 w-full h-full object-cover opacity-30 animate-[pulse_15s_ease-in-out_infinite] grayscale-[20%]">

        {{-- Deep Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-b from-[#020617]/95 via-[#020617]/60 to-[#f8fafc]"></div>

        {{-- Glowing Orbs --}}
        <div class="absolute top-1/4 left-1/3 w-96 h-96 bg-[#1d5c42]/20 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto -mt-8">
            <div class="animate-[fade-in-up_0.8s_ease-out]">
                <div class="inline-flex items-center gap-3 py-1.5 px-5 rounded-full bg-white/5 border border-white/10 text-[#c2a265] text-[10px] font-black tracking-[0.2em] uppercase backdrop-blur-xl mb-6 shadow-xl">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#c2a265] animate-ping absolute"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-[#c2a265] relative"></span>
                    Consumer Dashboard
                </div>
            </div>

            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter mb-4 drop-shadow-2xl leading-tight animate-[fade-in-up_1s_ease-out_0.2s_both]">
                Welcome Back, <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-[#f4e4c1] to-[#c2a265]">
                    {{ explode(' ', Auth::user()->name)[0] }}
                </span>
            </h1>
            <p class="text-base md:text-lg text-gray-400 font-medium max-w-xl mx-auto animate-[fade-in-up_1s_ease-out_0.4s_both]">
                Manage your luxury escapes, track your orders, and update your preferences all in one place.
            </p>
        </div>
    </div>

    {{-- ==========================================
         2. BENTO GRID ACTIONS
         ========================================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-30 -mt-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Card 1: Explore Farms --}}
            <a href="{{ route('explore') }}" class="fade-in-up-stagger group bg-white rounded-[2rem] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] border border-gray-100 transition-all duration-500 hover:-translate-y-2 overflow-hidden relative" style="animation-delay: 0.5s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-green-50/50 rounded-bl-[4rem] -mr-8 -mt-8 transition-transform duration-500 group-hover:scale-125 group-hover:bg-green-100/50"></div>
                <div class="relative z-10 w-14 h-14 bg-green-50 text-[#1d5c42] rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#1d5c42] group-hover:text-white transition-colors duration-500 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <h3 class="relative z-10 text-2xl font-black text-gray-900 mb-2">Explore Farms</h3>
                <p class="relative z-10 text-sm font-medium text-gray-500 mb-8 leading-relaxed">Discover and book premium luxury farm escapes tailored to you.</p>
                <span class="relative z-10 text-sm font-black text-[#1d5c42] uppercase tracking-widest flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
                    Browse Now
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </span>
            </a>

            {{-- Card 2: My Bookings --}}
            <a href="{{ route('bookings.my_bookings') ?? '#' }}" class="fade-in-up-stagger group bg-white rounded-[2rem] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] border border-gray-100 transition-all duration-500 hover:-translate-y-2 overflow-hidden relative" style="animation-delay: 0.6s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-50/50 rounded-bl-[4rem] -mr-8 -mt-8 transition-transform duration-500 group-hover:scale-125 group-hover:bg-amber-100/50"></div>
                <div class="relative z-10 w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-amber-500 group-hover:text-white transition-colors duration-500 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="relative z-10 text-2xl font-black text-gray-900 mb-2">My Bookings</h3>
                <p class="relative z-10 text-sm font-medium text-gray-500 mb-8 leading-relaxed">View and manage your upcoming and past farm stay reservations.</p>
                <span class="relative z-10 text-sm font-black text-amber-500 uppercase tracking-widest flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
                    View Stays
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </span>
            </a>

            {{-- Card 3: Supply Market --}}
            <a href="{{ route('supplies.market') ?? '#' }}" class="fade-in-up-stagger group bg-white rounded-[2rem] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] border border-gray-100 transition-all duration-500 hover:-translate-y-2 overflow-hidden relative" style="animation-delay: 0.7s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-bl-[4rem] -mr-8 -mt-8 transition-transform duration-500 group-hover:scale-125 group-hover:bg-blue-100/50"></div>
                <div class="relative z-10 w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-500 group-hover:text-white transition-colors duration-500 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="relative z-10 text-2xl font-black text-gray-900 mb-2">Supply Market</h3>
                <p class="relative z-10 text-sm font-medium text-gray-500 mb-8 leading-relaxed">Order fresh groceries, supplies, and essentials directly to your farm.</p>
                <span class="relative z-10 text-sm font-black text-blue-500 uppercase tracking-widest flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
                    Shop Essentials
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </span>
            </a>

            {{-- Card 4: Transport --}}
            <a href="{{ route('transports.index') ?? '#' }}" class="fade-in-up-stagger group bg-white rounded-[2rem] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] border border-gray-100 transition-all duration-500 hover:-translate-y-2 overflow-hidden relative" style="animation-delay: 0.8s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50/50 rounded-bl-[4rem] -mr-8 -mt-8 transition-transform duration-500 group-hover:scale-125 group-hover:bg-purple-100/50"></div>
                <div class="relative z-10 w-14 h-14 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-500 group-hover:text-white transition-colors duration-500 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <h3 class="relative z-10 text-2xl font-black text-gray-900 mb-2">Transport</h3>
                <p class="relative z-10 text-sm font-medium text-gray-500 mb-8 leading-relaxed">Book secure and reliable transportation to and from your destination.</p>
                <span class="relative z-10 text-sm font-black text-purple-500 uppercase tracking-widest flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
                    Book Ride
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </span>
            </a>

{{-- Card 5: Saved Favorites (FIXED LINK) --}}
            <a href="{{ route('favorites.index') }}" class="fade-in-up-stagger group bg-white rounded-[2rem] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] border border-gray-100 transition-all duration-500 hover:-translate-y-2 overflow-hidden relative" style="animation-delay: 0.9s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-red-50/50 rounded-bl-[4rem] -mr-8 -mt-8 transition-transform duration-500 group-hover:scale-125 group-hover:bg-red-100/50"></div>
                <div class="relative z-10 w-14 h-14 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-red-500 group-hover:text-white transition-colors duration-500 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="relative z-10 text-2xl font-black text-gray-900 mb-2">Saved Escapes</h3>
                <p class="relative z-10 text-sm font-medium text-gray-500 mb-8 leading-relaxed">Access your wishlist and quickly book your favorite farm locations.</p>
                <span class="relative z-10 text-sm font-black text-red-500 uppercase tracking-widest flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
                    View Wishlist
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </span>
            </a>

            {{-- Card 6: Profile Settings (FIXED LINK) --}}
            {{-- هون تأكد من اسم الراوت، عادةً في Laravel Breeze بكون 'profile.edit' --}}
            <a href="{{ route('profile.edit') }}" class="fade-in-up-stagger group bg-white rounded-[2rem] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] border border-gray-100 transition-all duration-500 hover:-translate-y-2 overflow-hidden relative" style="animation-delay: 1.0s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50/80 rounded-bl-[4rem] -mr-8 -mt-8 transition-transform duration-500 group-hover:scale-125 group-hover:bg-gray-100"></div>
                <div class="relative z-10 w-14 h-14 bg-gray-100 text-gray-700 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-gray-800 group-hover:text-white transition-colors duration-500 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h3 class="relative z-10 text-2xl font-black text-gray-900 mb-2">Account Settings</h3>
                <p class="relative z-10 text-sm font-medium text-gray-500 mb-8 leading-relaxed">Update your personal information, security, and preferences.</p>
                <span class="relative z-10 text-sm font-black text-gray-800 uppercase tracking-widest flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
                    Edit Profile
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </span>
            </a>

        </div>
    </div>
</div>
@endsection
