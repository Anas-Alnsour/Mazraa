@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')

<style>
    /* Premium Animations & Utilities */
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .fade-in-right { animation: fadeInRight 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }

    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInRight {
        0% { opacity: 0; transform: translateX(-30px); }
        100% { opacity: 1; transform: translateX(0); }
    }

    /* Luxury Ken Burns Effect */
    @keyframes kenBurns {
        0% { transform: scale(1) translate(0, 0); }
        50% { transform: scale(1.05) translate(-1%, -1%); }
        100% { transform: scale(1) translate(0, 0); }
    }
    .animate-ken-burns {
        animation: kenBurns 25s ease-in-out infinite;
    }

    /* Animated Gradient Text */
    .text-gradient-animate {
        background: linear-gradient(to right, #ffffff, #f4e4c1, #c2a265, #ffffff);
        background-size: 200% auto;
        color: transparent;
        -webkit-background-clip: text;
        background-clip: text;
        animation: shine 4s linear infinite;
    }
    @keyframes shine {
        to { background-position: 200% center; }
    }

    /* Glassmorphism utility */
    .glass-panel {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }
</style>

{{-- Main Wrapper with an elegant gradient background --}}
<div class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen pb-24 font-sans pt-32 selection:bg-[#1d5c42] selection:text-white relative overflow-hidden">

    {{-- Ambient Light Orbs --}}
    <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-[#1d5c42]/5 rounded-full blur-[120px] pointer-events-none -z-10 mix-blend-multiply"></div>
    <div class="absolute bottom-1/4 right-0 w-[500px] h-[500px] bg-amber-500/5 rounded-full blur-[100px] pointer-events-none -z-10 mix-blend-multiply"></div>

    {{-- Alert --}}
    <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms
         class="max-w-[96%] xl:max-w-7xl mx-auto mb-8 fade-in-up" style="animation-delay: 0.1s;">
        <div class="glass-panel rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="bg-gradient-to-br from-[#1d5c42] to-[#0f3022] p-2.5 rounded-xl text-white shadow-lg shadow-[#1d5c42]/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <h4 class="text-gray-900 font-bold text-sm tracking-tight">New Premium Farms Added!</h4>
                    <p class="text-gray-500 text-xs mt-0.5">Explore our latest exclusive properties available this weekend.</p>
                </div>
            </div>
            <button @click="show = false" class="mt-4 md:mt-0 text-gray-400 hover:text-gray-800 bg-white/50 hover:bg-white px-4 py-2 rounded-xl text-xs font-bold transition-all border border-transparent hover:border-gray-200">
                Dismiss
            </button>
        </div>
    </div>

    {{-- ==========================================
         1. HERO SECTION (EXPANDED & SEPARATED)
         ========================================== --}}
    <div class="max-w-[96%] xl:max-w-7xl mx-auto flex flex-col gap-6 mb-16 relative z-20">

        {{-- Top Hero Banner --}}
        <div class="relative w-full min-h-[400px] bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl flex flex-col justify-center">

            {{-- Background --}}
            <div class="absolute inset-0">
                <img src="{{ asset('backgrounds/home.JPG') }}" alt="Mazraa Luxury" class="w-full h-full object-cover opacity-50 animate-ken-burns mix-blend-luminosity">
                <div class="absolute inset-0 bg-gradient-to-r from-[#020617] via-[#020617]/90 to-transparent"></div>
            </div>

            {{-- Content --}}
            <div class="relative z-10 px-8 md:px-16 w-full text-left">
                <div class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-white/5 border border-white/10 text-[#c2a265] text-[10px] font-black tracking-[0.2em] uppercase backdrop-blur-md mb-6 fade-in-right">
                    <span class="w-2 h-2 rounded-full bg-gradient-to-r from-[#c2a265] to-amber-300 animate-pulse"></span>
                    Guest Portal
                </div>

                <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white tracking-tighter mb-4 leading-[1.1] fade-in-right" style="animation-delay: 0.1s;">
                    Discover Your Next <br class="hidden md:block">
                    <span class="text-gradient-animate italic font-serif font-medium pr-2">Escape,</span>
                    <span class="text-white">
                        {{ explode(' ', Auth::user()->name)[0] }}
                    </span>
                </h1>
                <p class="text-gray-400 max-w-xl text-sm md:text-base mt-4 fade-in-right" style="animation-delay: 0.2s;">
                    Experience luxury farm stays across Jordan. Book your private getaway with seamless access to verified properties and premium concierge services.
                </p>
            </div>
        </div>

        {{-- Bottom Stats/Action Bar (Distinct Block) --}}
        <div class="glass-panel rounded-[2rem] p-4 md:p-6 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 fade-in-up" style="animation-delay: 0.3s;">

            {{-- User Identity Segment --}}
            <div class="flex items-center gap-5 w-full md:w-auto bg-white/50 p-3 rounded-[1.5rem] border border-white">
                <div class="shrink-0 w-16 h-16 rounded-xl bg-gradient-to-br from-[#1d5c42] to-[#0f3022] text-[#c2a265] flex items-center justify-center text-2xl font-black shadow-inner shadow-black/20">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 pr-4">
                    <p class="text-xl font-black text-gray-900 tracking-tight truncate">{{ Auth::user()->name }}</p>
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest truncate">{{ Auth::user()->email }}</p>
                        <span class="hidden sm:block w-1.5 h-1.5 rounded-full bg-gray-300 shrink-0"></span>
                        <span class="inline-flex items-center gap-1 text-[#1d5c42] text-[10px] font-black uppercase tracking-widest shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Verified Account
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3 w-full md:w-auto shrink-0">
                <a href="{{ route('profile.edit') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-700 px-6 py-4 rounded-xl text-sm font-bold transition-all border border-gray-200 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Settings
                </a>
                <a href="{{ route('explore') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-[#1d5c42] hover:bg-[#15422f] text-white px-8 py-4 rounded-xl text-sm font-bold transition-all shadow-lg shadow-[#1d5c42]/20 hover:shadow-[#1d5c42]/40 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Explore Now
                </a>
            </div>
        </div>
    </div>

    {{-- ==========================================
         2. BENTO GRID
         ========================================== --}}
    <div class="max-w-[96%] xl:max-w-7xl mx-auto px-2 lg:px-0 relative z-10">

        <div class="flex items-center justify-between mb-8 fade-in-up" style="animation-delay: 0.4s;">
            <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <span class="w-1.5 h-8 rounded-full bg-[#1d5c42]"></span>
                Platform Services
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">

            {{-- 🟢 Card 1: Explore Farms (Green Theme) --}}
            <a href="{{ route('explore') }}" class="fade-in-up group relative block h-full" style="animation-delay: 0.5s;">
                <div class="bg-white rounded-[2rem] p-8 lg:p-10 shadow-sm hover:shadow-2xl hover:shadow-[#1d5c42]/10 border border-gray-100/80 hover:border-[#1d5c42]/30 transition-all duration-500 hover:-translate-y-1.5 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-[#1d5c42]/5 to-transparent rounded-bl-full transition-transform duration-700 group-hover:scale-150"></div>

                    <div class="w-16 h-16 bg-gray-50 text-[#1d5c42] rounded-2xl flex items-center justify-center mb-8 relative z-10 ring-1 ring-gray-100 group-hover:bg-[#1d5c42] group-hover:text-white transition-colors duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>

                    <h3 class="text-2xl font-black text-gray-900 mb-3 tracking-tight group-hover:text-[#1d5c42] transition-colors relative z-10">Explore Farms</h3>
                    <p class="text-sm text-gray-500 mb-8 leading-relaxed flex-1 relative z-10">Discover, filter, and book premium luxury farm escapes with real-time availability.</p>

                    <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-6 relative z-10">
                        <span class="text-xs font-black text-[#1d5c42] uppercase tracking-widest">Browse Directory</span>
                        <div class="w-8 h-8 rounded-full bg-[#1d5c42]/5 flex items-center justify-center group-hover:bg-[#1d5c42] group-hover:text-white transition-colors text-[#1d5c42]">
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                    </div>
                </div>
            </a>

            {{-- 🟠 Card 2: My Bookings (Amber Theme) --}}
            <a href="{{ route('bookings.my_bookings') }}" class="fade-in-up group relative block h-full" style="animation-delay: 0.6s;">
                <div class="bg-white rounded-[2rem] p-8 lg:p-10 shadow-sm hover:shadow-2xl hover:shadow-amber-500/10 border border-gray-100/80 hover:border-amber-500/30 transition-all duration-500 hover:-translate-y-1.5 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-amber-500/5 to-transparent rounded-bl-full transition-transform duration-700 group-hover:scale-150"></div>

                    <div class="w-16 h-16 bg-gray-50 text-amber-500 rounded-2xl flex items-center justify-center mb-8 relative z-10 ring-1 ring-gray-100 group-hover:bg-amber-500 group-hover:text-white transition-colors duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>

                    <h3 class="text-2xl font-black text-gray-900 mb-3 tracking-tight group-hover:text-amber-500 transition-colors relative z-10">My Bookings</h3>
                    <p class="text-sm text-gray-500 mb-8 leading-relaxed flex-1 relative z-10">View your itinerary, download invoices, manage cancellations, and track history.</p>

                    <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-6 relative z-10">
                        <span class="text-xs font-black text-amber-600 uppercase tracking-widest">Manage Stays</span>
                        <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors text-amber-500">
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                    </div>
                </div>
            </a>

            {{-- 🔵 Card 3: Supply Market (Blue Theme) --}}
            <a href="{{ route('supplies.market') }}" class="fade-in-up group relative block h-full" style="animation-delay: 0.7s;">
                <div class="bg-white rounded-[2rem] p-8 lg:p-10 shadow-sm hover:shadow-2xl hover:shadow-blue-500/10 border border-gray-100/80 hover:border-blue-500/30 transition-all duration-500 hover:-translate-y-1.5 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-blue-500/5 to-transparent rounded-bl-full transition-transform duration-700 group-hover:scale-150"></div>

                    <div class="w-16 h-16 bg-gray-50 text-blue-500 rounded-2xl flex items-center justify-center mb-8 relative z-10 ring-1 ring-gray-100 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>

                    <h3 class="text-2xl font-black text-gray-900 mb-3 tracking-tight group-hover:text-blue-600 transition-colors relative z-10">Supply Market</h3>
                    <p class="text-sm text-gray-500 mb-8 leading-relaxed flex-1 relative z-10">Pre-order groceries and essentials. Delivered straight to your booked farm.</p>

                    <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-6 relative z-10">
                        <span class="text-xs font-black text-blue-600 uppercase tracking-widest">Open Market</span>
                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors text-blue-500">
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                    </div>
                </div>
            </a>

            {{-- 🟣 Card 4: Transport (Purple Theme) --}}
            <a href="{{ route('transports.index') }}" class="fade-in-up group relative block h-full" style="animation-delay: 0.8s;">
                <div class="bg-white rounded-[2rem] p-8 lg:p-10 shadow-sm hover:shadow-2xl hover:shadow-purple-500/10 border border-gray-100/80 hover:border-purple-500/30 transition-all duration-500 hover:-translate-y-1.5 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-purple-500/5 to-transparent rounded-bl-full transition-transform duration-700 group-hover:scale-150"></div>

                    <div class="w-16 h-16 bg-gray-50 text-purple-500 rounded-2xl flex items-center justify-center mb-8 relative z-10 ring-1 ring-gray-100 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>

                    <h3 class="text-2xl font-black text-gray-900 mb-3 tracking-tight group-hover:text-purple-600 transition-colors relative z-10">Transport</h3>
                    <p class="text-sm text-gray-500 mb-8 leading-relaxed flex-1 relative z-10">Book secure round-trips to your destination with verified transport partners.</p>

                    <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-6 relative z-10">
                        <span class="text-xs font-black text-purple-600 uppercase tracking-widest">Book Ride</span>
                        <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors text-purple-500">
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                    </div>
                </div>
            </a>

            {{-- 🔴 Card 5: Favorites (Rose Theme) --}}
            <a href="{{ route('favorites.index') }}" class="fade-in-up group relative block h-full" style="animation-delay: 0.9s;">
                <div class="bg-white rounded-[2rem] p-8 lg:p-10 shadow-sm hover:shadow-2xl hover:shadow-rose-500/10 border border-gray-100/80 hover:border-rose-500/30 transition-all duration-500 hover:-translate-y-1.5 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-rose-500/5 to-transparent rounded-bl-full transition-transform duration-700 group-hover:scale-150"></div>

                    <div class="w-16 h-16 bg-gray-50 text-rose-500 rounded-2xl flex items-center justify-center mb-8 relative z-10 ring-1 ring-gray-100 group-hover:bg-rose-500 group-hover:text-white transition-colors duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>

                    <h3 class="text-2xl font-black text-gray-900 mb-3 tracking-tight group-hover:text-rose-600 transition-colors relative z-10">Saved Escapes</h3>
                    <p class="text-sm text-gray-500 mb-8 leading-relaxed flex-1 relative z-10">Access your personal wishlist. Keep track of properties you love for later.</p>

                    <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-6 relative z-10">
                        <span class="text-xs font-black text-rose-600 uppercase tracking-widest">View List</span>
                        <div class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center group-hover:bg-rose-500 group-hover:text-white transition-colors text-rose-500">
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                    </div>
                </div>
            </a>

            {{-- ⚫ Card 6: Settings (Dark Slate Theme) --}}
            <a href="{{ route('profile.edit') }}" class="fade-in-up group relative block h-full" style="animation-delay: 1.0s;">
                <div class="bg-white rounded-[2rem] p-8 lg:p-10 shadow-sm hover:shadow-2xl hover:shadow-slate-800/10 border border-gray-100/80 hover:border-slate-800/30 transition-all duration-500 hover:-translate-y-1.5 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-slate-800/5 to-transparent rounded-bl-full transition-transform duration-700 group-hover:scale-150"></div>

                    <div class="w-16 h-16 bg-gray-50 text-slate-700 rounded-2xl flex items-center justify-center mb-8 relative z-10 ring-1 ring-gray-100 group-hover:bg-slate-800 group-hover:text-white transition-colors duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>

                    <h3 class="text-2xl font-black text-gray-900 mb-3 tracking-tight group-hover:text-slate-800 transition-colors relative z-10">Account Settings</h3>
                    <p class="text-sm text-gray-500 mb-8 leading-relaxed flex-1 relative z-10">Update your personal details, secure your account, and manage preferences.</p>

                    <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-6 relative z-10">
                        <span class="text-xs font-black text-slate-700 uppercase tracking-widest">Edit Account</span>
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-slate-800 group-hover:text-white transition-colors text-slate-700">
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                    </div>
                </div>
            </a>

        </div>

        {{-- ==========================================
             3. CONCIERGE BANNER (ELEGANT)
             ========================================== --}}
        <div class="mt-16 mb-8 bg-slate-900 rounded-[2.5rem] p-10 md:p-14 relative overflow-hidden shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8 fade-in-up" style="animation-delay: 1.1s;">
            <div class="absolute inset-0 bg-gradient-to-r from-[#020617] via-[#020617]/90 to-[#1d5c42]/30"></div>
            <div class="absolute -right-10 -top-40 w-[400px] h-[400px] bg-[#c2a265] rounded-full blur-[120px] pointer-events-none opacity-20"></div>

            <div class="relative z-10 max-w-2xl text-left">
                <h3 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-4 leading-tight">Need help planning your escape?</h3>
                <p class="text-gray-400 text-base leading-relaxed">Our premium concierge team is available 24/7 to assist you with custom requests, large group bookings, or resolving any issues.</p>
            </div>

            <div class="relative z-10 shrink-0 w-full md:w-auto">
                <a href="{{ route('contact') }}" class="block text-center bg-[#c2a265] hover:bg-[#b09156] text-[#0f3022] px-8 py-4 rounded-xl text-sm font-black uppercase tracking-widest transition-all duration-300">
                    Contact Concierge
                </a>
            </div>
        </div>

    </div>
</div>

@endsection
