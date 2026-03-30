@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')

<style>
    /* Custom Animations for Dashboard */
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    .fade-in-up-stagger { animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; }

    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>

{{-- 💡 pt-36 to push content further down from the fixed navbar --}}
<div class="bg-[#f8fafc] min-h-screen pb-24 font-sans pt-36 selection:bg-[#1d5c42] selection:text-white">

    {{-- ==========================================
         1. DASHBOARD HERO SECTION (PREMIUM)
         ========================================== --}}
    <div class="relative w-full h-[30vh] min-h-[300px] flex items-center justify-center bg-[#020617] overflow-hidden mb-12 rounded-[2.5rem] mx-auto max-w-[96%] xl:max-w-[94%] shadow-2xl shadow-gray-900/10">
        {{-- Animated Background Image --}}
        <img src="{{ asset('backgrounds/home.JPG') }}" alt="Dashboard Background"
             class="absolute inset-0 w-full h-full object-cover opacity-30 animate-[pulse_15s_ease-in-out_infinite] grayscale-[20%]">

        {{-- Deep Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-b from-[#020617]/95 via-[#020617]/70 to-[#f8fafc]/10"></div>

        {{-- Glowing Orbs --}}
        <div class="absolute top-1/4 left-1/3 w-96 h-96 bg-[#1d5c42]/30 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-[#c2a265]/20 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-4">
            <div class="animate-[fade-in-up_0.8s_ease-out]">
                <div class="inline-flex items-center gap-3 py-1.5 px-5 rounded-full bg-white/5 border border-white/10 text-[#c2a265] text-[10px] font-black tracking-[0.2em] uppercase backdrop-blur-xl mb-6 shadow-xl">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#c2a265] animate-ping absolute"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-[#c2a265] relative"></span>
                    Consumer Dashboard
                </div>
            </div>

            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter mb-4 drop-shadow-2xl leading-tight fade-in-up" style="animation-delay: 0.1s;">
                Welcome Back, <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-[#f4e4c1] to-[#c2a265]">
                    {{ explode(' ', Auth::user()->name)[0] }}
                </span>
            </h1>
            <p class="text-base md:text-lg text-gray-300 font-medium max-w-xl mx-auto fade-in-up leading-relaxed" style="animation-delay: 0.2s;">
                Manage your luxury escapes, track your orders, and update your preferences all in one place.
            </p>
        </div>
    </div>

    {{-- ==========================================
         2. BENTO GRID ACTIONS
         ========================================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-30">

        {{-- Quick Stats / Welcome Bar --}}
        <div class="flex items-center justify-between bg-white p-4 rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-100 mb-8 fade-in-up" style="animation-delay: 0.3s;">
            <div class="px-5 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#1d5c42] to-[#c2a265] text-white flex items-center justify-center font-black shadow-inner border-2 border-white">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-black text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="hidden sm:block px-5">
                <span class="bg-green-50 text-[#1d5c42] px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest border border-green-100">
                    Active Member
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- Card 1: Explore Farms --}}
            <a href="{{ route('explore') }}" class="fade-in-up-stagger group bg-white rounded-[2.5rem] p-8 md:p-10 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.12)] border border-gray-100 hover:border-[#1d5c42]/30 transition-all duration-500 hover:-translate-y-2 overflow-hidden relative flex flex-col h-full" style="animation-delay: 0.4s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-green-50/80 rounded-bl-[4rem] -mr-8 -mt-8 transition-transform duration-700 group-hover:scale-150 group-hover:bg-green-100/50 ease-out"></div>
                <div class="relative z-10 w-16 h-16 bg-green-50 text-[#1d5c42] rounded-2xl flex items-center justify-center mb-8 group-hover:bg-[#1d5c42] group-hover:text-white transition-colors duration-500 shadow-sm border border-green-100/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <h3 class="relative z-10 text-2xl font-black text-gray-900 mb-3 tracking-tight group-hover:text-[#1d5c42] transition-colors">Explore Farms</h3>
                <p class="relative z-10 text-sm font-medium text-gray-500 mb-10 leading-relaxed flex-1">Discover and book premium luxury farm escapes tailored to your taste.</p>
                <div class="relative z-10 mt-auto flex items-center gap-2">
                    <span class="text-xs font-black text-[#1d5c42] uppercase tracking-widest transition-all">Browse Now</span>
                    <svg class="w-4 h-4 text-[#1d5c42] transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </div>
            </a>

            {{-- Card 2: My Bookings --}}
            <a href="{{ route('bookings.my_bookings') ?? '#' }}" class="fade-in-up-stagger group bg-white rounded-[2.5rem] p-8 md:p-10 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.12)] border border-gray-100 hover:border-amber-500/30 transition-all duration-500 hover:-translate-y-2 overflow-hidden relative flex flex-col h-full" style="animation-delay: 0.5s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-50/80 rounded-bl-[4rem] -mr-8 -mt-8 transition-transform duration-700 group-hover:scale-150 group-hover:bg-amber-100/50 ease-out"></div>
                <div class="relative z-10 w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-amber-500 group-hover:text-white transition-colors duration-500 shadow-sm border border-amber-100/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="relative z-10 text-2xl font-black text-gray-900 mb-3 tracking-tight group-hover:text-amber-500 transition-colors">My Bookings</h3>
                <p class="relative z-10 text-sm font-medium text-gray-500 mb-10 leading-relaxed flex-1">View and manage your upcoming and past farm stay reservations seamlessly.</p>
                <div class="relative z-10 mt-auto flex items-center gap-2">
                    <span class="text-xs font-black text-amber-500 uppercase tracking-widest transition-all">View Stays</span>
                    <svg class="w-4 h-4 text-amber-500 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </div>
            </a>

            {{-- Card 3: Supply Market --}}
            <a href="{{ route('supplies.market') ?? '#' }}" class="fade-in-up-stagger group bg-white rounded-[2.5rem] p-8 md:p-10 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.12)] border border-gray-100 hover:border-blue-500/30 transition-all duration-500 hover:-translate-y-2 overflow-hidden relative flex flex-col h-full" style="animation-delay: 0.6s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/80 rounded-bl-[4rem] -mr-8 -mt-8 transition-transform duration-700 group-hover:scale-150 group-hover:bg-blue-100/50 ease-out"></div>
                <div class="relative z-10 w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-blue-500 group-hover:text-white transition-colors duration-500 shadow-sm border border-blue-100/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="relative z-10 text-2xl font-black text-gray-900 mb-3 tracking-tight group-hover:text-blue-500 transition-colors">Supply Market</h3>
                <p class="relative z-10 text-sm font-medium text-gray-500 mb-10 leading-relaxed flex-1">Order fresh groceries, supplies, and essentials delivered directly to your farm gate.</p>
                <div class="relative z-10 mt-auto flex items-center gap-2">
                    <span class="text-xs font-black text-blue-500 uppercase tracking-widest transition-all">Shop Essentials</span>
                    <svg class="w-4 h-4 text-blue-500 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </div>
            </a>

            {{-- Card 4: Transport --}}
            <a href="{{ route('transports.index') ?? '#' }}" class="fade-in-up-stagger group bg-white rounded-[2.5rem] p-8 md:p-10 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.12)] border border-gray-100 hover:border-purple-500/30 transition-all duration-500 hover:-translate-y-2 overflow-hidden relative flex flex-col h-full" style="animation-delay: 0.7s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50/80 rounded-bl-[4rem] -mr-8 -mt-8 transition-transform duration-700 group-hover:scale-150 group-hover:bg-purple-100/50 ease-out"></div>
                <div class="relative z-10 w-16 h-16 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-purple-500 group-hover:text-white transition-colors duration-500 shadow-sm border border-purple-100/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <h3 class="relative z-10 text-2xl font-black text-gray-900 mb-3 tracking-tight group-hover:text-purple-500 transition-colors">Transport</h3>
                <p class="relative z-10 text-sm font-medium text-gray-500 mb-10 leading-relaxed flex-1">Book secure and reliable transportation to and from your farm destination.</p>
                <div class="relative z-10 mt-auto flex items-center gap-2">
                    <span class="text-xs font-black text-purple-500 uppercase tracking-widest transition-all">Book Ride</span>
                    <svg class="w-4 h-4 text-purple-500 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </div>
            </a>

            {{-- Card 5: Saved Favorites --}}
            <a href="{{ route('favorites.index') }}" class="fade-in-up-stagger group bg-white rounded-[2.5rem] p-8 md:p-10 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.12)] border border-gray-100 hover:border-red-500/30 transition-all duration-500 hover:-translate-y-2 overflow-hidden relative flex flex-col h-full" style="animation-delay: 0.8s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-red-50/80 rounded-bl-[4rem] -mr-8 -mt-8 transition-transform duration-700 group-hover:scale-150 group-hover:bg-red-100/50 ease-out"></div>
                <div class="relative z-10 w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-red-500 group-hover:text-white transition-colors duration-500 shadow-sm border border-red-100/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="relative z-10 text-2xl font-black text-gray-900 mb-3 tracking-tight group-hover:text-red-500 transition-colors">Saved Escapes</h3>
                <p class="relative z-10 text-sm font-medium text-gray-500 mb-10 leading-relaxed flex-1">Access your wishlist and quickly book your favorite farm locations.</p>
                <div class="relative z-10 mt-auto flex items-center gap-2">
                    <span class="text-xs font-black text-red-500 uppercase tracking-widest transition-all">View Wishlist</span>
                    <svg class="w-4 h-4 text-red-500 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </div>
            </a>

            {{-- Card 6: Profile Settings --}}
            <a href="{{ route('profile.edit') }}" class="fade-in-up-stagger group bg-white rounded-[2.5rem] p-8 md:p-10 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.12)] border border-gray-100 hover:border-gray-800/30 transition-all duration-500 hover:-translate-y-2 overflow-hidden relative flex flex-col h-full" style="animation-delay: 0.9s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50/80 rounded-bl-[4rem] -mr-8 -mt-8 transition-transform duration-700 group-hover:scale-150 group-hover:bg-gray-100 ease-out"></div>
                <div class="relative z-10 w-16 h-16 bg-gray-100 text-gray-700 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-gray-800 group-hover:text-white transition-colors duration-500 shadow-sm border border-gray-200/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h3 class="relative z-10 text-2xl font-black text-gray-900 mb-3 tracking-tight group-hover:text-gray-800 transition-colors">Account Settings</h3>
                <p class="relative z-10 text-sm font-medium text-gray-500 mb-10 leading-relaxed flex-1">Update your personal information, security, and application preferences.</p>
                <div class="relative z-10 mt-auto flex items-center gap-2">
                    <span class="text-xs font-black text-gray-800 uppercase tracking-widest transition-all">Edit Profile</span>
                    <svg class="w-4 h-4 text-gray-800 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </div>
            </a>

        </div>
    </div>
</div>
@endsection
