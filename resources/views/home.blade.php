@extends('layouts.app')

@section('title', 'Welcome to Mazraa')

@section('content')

<style>
    /* Ultra Modern Custom Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-20px) scale(1.05); }
    }
    @keyframes pulse-glow {
        0%, 100% { opacity: 0.4; }
        50% { opacity: 0.8; }
    }
    @keyframes shimmer {
        100% { transform: translateX(100%); }
    }

    .animate-float { animation: float 8s ease-in-out infinite; }
    .animate-float-delayed { animation: float 8s ease-in-out 4s infinite; }
    .animate-pulse-glow { animation: pulse-glow 4s ease-in-out infinite; }

    /* Premium Glassmorphism */
    .glass-card {
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }
    .glass-card:hover {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(194, 162, 101, 0.3);
    }
</style>

<div x-data="{ mounted: false }" x-init="setTimeout(() => mounted = true, 100)" class="bg-[#020617] overflow-hidden selection:bg-[#c2a265] selection:text-[#020617]">

    {{-- ==========================================
         1. HERO SECTION (ULTRA MODERN & CLICKABLE)
         ========================================== --}}
    <section class="relative w-full min-h-screen flex flex-col items-center justify-center pt-20 pb-12">
        {{-- Background Elements (pointer-events-none is CRITICAL here so buttons work) --}}
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <img src="{{ asset('backgrounds/home.JPG') }}" alt="Mazraa Home"
                 class="w-full h-full object-cover opacity-50 scale-110 transition-transform duration-[40s] ease-out"
                 :class="mounted ? 'scale-100' : 'scale-110'">
            <div class="absolute inset-0 bg-gradient-to-b from-[#020617]/95 via-[#020617]/70 to-[#020617]"></div>

            {{-- Glowing Orbs --}}
            <div class="absolute top-1/4 left-1/4 w-[30rem] h-[30rem] bg-[#1d5c42]/20 rounded-full blur-[120px] animate-float"></div>
            <div class="absolute bottom-1/4 right-1/4 w-[30rem] h-[30rem] bg-[#c2a265]/15 rounded-full blur-[120px] animate-float-delayed"></div>
        </div>

        {{-- Hero Content (Staggered Animation) --}}
        <div class="relative z-20 container mx-auto px-4 flex flex-col items-center text-center">

            <div class="transition-all duration-1000 ease-out transform"
                 :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'">
                <div class="inline-flex items-center gap-3 py-1.5 px-5 rounded-full bg-white/5 border border-white/10 text-[#c2a265] text-xs font-black tracking-[0.2em] uppercase backdrop-blur-xl mb-8 shadow-2xl hover:bg-white/10 transition-colors cursor-default">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#c2a265] opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#c2a265]"></span>
                    </span>
                    The Ultimate Farm Escape
                </div>
            </div>

            <h1 class="text-6xl md:text-8xl font-black text-white tracking-tighter drop-shadow-2xl leading-[1.1] mb-6 transition-all duration-1000 delay-200 ease-out transform"
                :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'">
                Welcome to <br class="md:hidden" />
                <span class="relative whitespace-nowrap inline-block group">
                    <span class="absolute -inset-2 bg-gradient-to-r from-[#1d5c42] to-[#c2a265] blur-3xl opacity-30 group-hover:opacity-50 transition-opacity duration-500 pointer-events-none"></span>
                    <span class="relative text-transparent bg-clip-text bg-gradient-to-r from-white via-[#f4e4c1] to-[#c2a265]">
                        Mazraa.com
                    </span>
                </span>
            </h1>

            <p class="text-lg md:text-2xl text-gray-400 max-w-3xl mx-auto leading-relaxed font-medium mb-12 transition-all duration-1000 delay-300 ease-out transform"
               :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'">
                Jordan's first integrated ecosystem for luxury farm bookings, seamless on-demand supplies, and premium transport services.
            </p>

            {{-- Action Buttons (Z-INDEX 50 is critical here) --}}
            <div class="w-full max-w-4xl mx-auto transition-all duration-1000 delay-500 ease-out transform relative z-50"
                 :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'">

                <div class="flex flex-col md:flex-row items-center justify-center gap-5">
                    @guest
                        {{-- Explore Button --}}
                        <a href="{{ route('explore') }}"
                           class="group relative w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-[#1d5c42] to-[#113827] text-white font-black text-sm uppercase tracking-widest rounded-full shadow-[0_0_40px_rgba(29,92,66,0.3)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_0_60px_rgba(29,92,66,0.6)] flex items-center justify-center gap-3 overflow-hidden border border-[#1d5c42]/50">
                            <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out"></div>
                            <span class="relative z-10">Explore Farms</span>
                            <svg class="relative z-10 w-5 h-5 group-hover:translate-x-1.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>

                        {{-- Register Dropdown --}}
                        <div class="relative w-full sm:w-auto z-50" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open"
                                    class="w-full px-10 py-4 glass-card text-white font-black text-sm uppercase tracking-widest rounded-full transition-all duration-300 flex items-center justify-center gap-2 relative overflow-hidden group">
                                <div class="absolute inset-0 bg-white/5 group-hover:bg-white/10 transition-colors"></div>
                                <span class="relative z-10">Register</span>
                                <svg class="relative z-10 w-4 h-4 transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-2" x-cloak class="absolute left-0 sm:left-1/2 sm:-translate-x-1/2 mt-4 w-72 bg-white/95 backdrop-blur-2xl rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/20 overflow-hidden py-2 text-left z-50">
                                <p class="px-5 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 mb-1">Create an Account</p>
                                <a href="{{ route('register') }}" class="flex items-center px-5 py-3 text-sm font-bold text-gray-700 hover:bg-green-50 transition-colors group">
                                    <div class="p-2 bg-gray-100 rounded-lg mr-3 group-hover:bg-[#1d5c42] group-hover:text-white transition-colors shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
                                    <div><span class="block text-gray-900 font-black">Consumer Account</span><span class="block text-[10px] text-gray-500 font-bold uppercase mt-0.5">Standard User</span></div>
                                </a>
                                <a href="{{ route('partner.register') }}" class="flex items-center px-5 py-3 text-sm font-bold text-gray-700 hover:bg-amber-50 transition-colors group">
                                    <div class="p-2 bg-gray-100 rounded-lg mr-3 group-hover:bg-[#c2a265] group-hover:text-white transition-colors shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
                                    <div><span class="block text-gray-900 font-black">List Your Farm</span><span class="block text-[10px] text-gray-500 font-bold uppercase mt-0.5">Partner Program</span></div>
                                </a>
                            </div>
                        </div>

                        {{-- Login Dropdown --}}
                        <div class="relative w-full sm:w-auto z-50" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open"
                                    class="w-full px-10 py-4 bg-white text-[#0f172a] font-black text-sm uppercase tracking-widest rounded-full hover:bg-gray-100 transition-all duration-300 flex items-center justify-center gap-2 shadow-[0_0_30px_rgba(255,255,255,0.15)] hover:shadow-[0_0_40px_rgba(255,255,255,0.25)] hover:-translate-y-1">
                                <span>Log In</span>
                                <svg class="w-4 h-4 text-[#c2a265] transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-2" x-cloak class="absolute right-0 sm:left-1/2 sm:-translate-x-1/2 mt-4 w-72 bg-white/95 backdrop-blur-2xl rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/20 overflow-hidden py-2 text-left z-50">
                                <p class="px-5 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 mb-1">Select Portal</p>
                                <a href="{{ route('login') }}" class="flex items-center px-5 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors group">
                                    <div class="p-2 bg-gray-100 rounded-lg mr-3 group-hover:bg-[#1d5c42] group-hover:text-white transition-colors shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg></div>
                                    <span class="text-gray-900 font-black">Customer Login</span>
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="{{ route('portal.login') }}" class="flex items-center px-5 py-3 text-sm font-bold text-gray-700 hover:bg-blue-50 transition-colors group">
                                    <div class="p-1.5 bg-gray-50 rounded-md mr-3 group-hover:bg-[#1d5c42] group-hover:text-white transition-colors shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></div>
                                    Admin / Farm Owner
                                </a>
                                <a href="{{ route('supply-company.login') }}" class="flex items-center px-5 py-3 text-sm font-bold text-gray-700 hover:bg-teal-50 transition-colors group">
                                    <div class="p-1.5 bg-gray-50 rounded-md mr-3 group-hover:bg-[#1d5c42] group-hover:text-white transition-colors shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg></div>
                                    Supply Company
                                </a>
                                <a href="{{ route('supply-driver.login') }}" class="flex items-center px-5 py-3 text-sm font-bold text-gray-700 hover:bg-emerald-50 transition-colors group">
                                    <div class="p-1.5 bg-gray-50 rounded-md mr-3 group-hover:bg-[#1d5c42] group-hover:text-white transition-colors shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 00-1 1h1m6-1a1 1 0 01-1 1h1m-6-1h4"></path></svg></div>
                                    Supply Driver
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="{{ route('transport-company.login') }}" class="flex items-center px-5 py-3 text-sm font-bold text-gray-700 hover:bg-amber-50 transition-colors group">
                                    <div class="p-1.5 bg-gray-50 rounded-md mr-3 group-hover:bg-[#c2a265] group-hover:text-white transition-colors shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg></div>
                                    Transport Company
                                </a>
                                <a href="{{ route('transport-driver.login') }}" class="flex items-center px-5 py-3 text-sm font-bold text-gray-700 hover:bg-amber-50 transition-colors group">
                                    <div class="p-1.5 bg-gray-50 rounded-md mr-3 group-hover:bg-[#c2a265] group-hover:text-white transition-colors shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
                                    Transport Driver
                                </a>
                            </div>
                        </div>
                    @endguest

                    @auth
                        <div class="flex flex-col md:flex-row items-center gap-4 glass-card p-2 rounded-full shadow-2xl border border-white/20 relative z-50">
                            <div class="flex items-center gap-3 px-4 py-1">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-[#1d5c42] to-[#c2a265] flex items-center justify-center text-white font-black text-xl shadow-inner border-2 border-white/20">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-left hidden md:block">
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Welcome back</p>
                                    <p class="text-white font-black text-base">{{ explode(' ', Auth::user()->name)[0] }}</p>
                                </div>
                            </div>
                            <div class="hidden md:block w-px h-10 bg-white/10"></div>
                            <div class="flex gap-2 w-full md:w-auto pr-2">
                                <a href="{{ route('explore') }}" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white rounded-full font-black text-xs uppercase tracking-widest transition-all">Explore</a>
                                <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gradient-to-r from-[#1d5c42] to-[#154230] text-white rounded-full font-black text-xs uppercase tracking-widest transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 border border-[#1d5c42]/50">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="w-full px-6 py-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 rounded-full font-black text-xs uppercase tracking-widest transition-all border border-red-500/20 hover:border-red-500/50">Logout</button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 transition-all duration-1000 delay-1000 opacity-0" :class="mounted ? 'opacity-100' : 'opacity-0'">
            <span class="text-[9px] text-gray-500 uppercase tracking-[0.4em] font-black">Scroll Down</span>
            <div class="w-px h-16 bg-gradient-to-b from-gray-500 to-transparent"></div>
        </div>
    </section>

    {{-- ==========================================
         2. STATS BANNER
         ========================================== --}}
    <section class="border-y border-white/5 bg-[#020617] relative z-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-[#1d5c42]/5 via-transparent to-[#c2a265]/5 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 py-12 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-white/5 text-center">
                <div class="space-y-3 group">
                    <h3 class="text-4xl md:text-5xl font-black text-white group-hover:text-[#c2a265] transition-colors duration-300">500+</h3>
                    <p class="text-xs text-gray-500 uppercase tracking-widest font-black">Listed Farms</p>
                </div>
                <div class="space-y-3 group">
                    <h3 class="text-4xl md:text-5xl font-black text-white group-hover:text-[#c2a265] transition-colors duration-300">10k+</h3>
                    <p class="text-xs text-gray-500 uppercase tracking-widest font-black">Happy Guests</p>
                </div>
                <div class="space-y-3 group">
                    <h3 class="text-4xl md:text-5xl font-black text-[#c2a265] group-hover:text-white transition-colors duration-300">24/7</h3>
                    <p class="text-xs text-gray-500 uppercase tracking-widest font-black">Support & Transport</p>
                </div>
                <div class="space-y-3 group">
                    <h3 class="text-4xl md:text-5xl font-black text-[#1d5c42] group-hover:text-white transition-colors duration-300">100%</h3>
                    <p class="text-xs text-gray-500 uppercase tracking-widest font-black">Verified Estates</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         3. THE ECOSYSTEM (SERVICES CARDS)
         ========================================== --}}
    <section class="py-32 relative z-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-24 space-y-4">
                <h2 class="text-4xl md:text-6xl font-black text-white tracking-tight">The Mazraa <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#c2a265] to-[#f4e4c1]">Ecosystem</span></h2>
                <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto font-medium">Everything you need for the perfect getaway, seamlessly integrated.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                {{-- Card 1: Luxury Stays --}}
                <div class="group relative rounded-[2.5rem] overflow-hidden bg-[#0f172a] p-1 shadow-2xl hover:shadow-[0_0_50px_rgba(29,92,66,0.2)] transition-shadow duration-500 flex flex-col">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#1d5c42]/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative flex-1 glass-card p-10 rounded-[2.3rem] transition-transform duration-500 group-hover:-translate-y-2 flex flex-col">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#1d5c42] to-green-700 flex items-center justify-center mb-8 shadow-[0_10px_20px_rgba(29,92,66,0.4)] border border-white/10 shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-4 shrink-0">Luxury Stays</h3>
                        <p class="text-gray-400 leading-relaxed mb-8 flex-1">Discover and book verified, premium farms across the country. From cozy chalets to massive estates.</p>
                        <div class="shrink-0 mt-auto pt-4">
                            <a href="{{ route('explore') }}" class="inline-flex items-center text-[#c2a265] font-black uppercase tracking-widest text-[10px] group-hover:text-white transition-colors">
                                Explore Now <svg class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Card 2: On-Demand Supplies --}}
                <div class="group relative rounded-[2.5rem] overflow-hidden bg-[#0f172a] p-1 shadow-2xl hover:shadow-[0_0_50px_rgba(194,162,101,0.2)] transition-shadow duration-500 flex flex-col">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative flex-1 glass-card p-10 rounded-[2.3rem] transition-transform duration-500 group-hover:-translate-y-2 flex flex-col">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center mb-8 shadow-[0_10px_20px_rgba(37,99,235,0.4)] border border-white/10 shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-4 shrink-0">On-Demand Supplies</h3>
                        <p class="text-gray-400 leading-relaxed mb-8 flex-1">Need charcoal, meat, or snacks? Order from our vetted supply partners directly to your farm gate.</p>
                        <div class="shrink-0 mt-auto pt-4">
                            <span class="inline-flex items-center text-blue-400 font-black uppercase tracking-widest text-[10px] bg-blue-500/10 px-4 py-2 rounded-full border border-blue-500/20">Available in Dashboard</span>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Shuttle Transport --}}
                <div class="group relative rounded-[2.5rem] overflow-hidden bg-[#0f172a] p-1 shadow-2xl hover:shadow-[0_0_50px_rgba(194,162,101,0.2)] transition-shadow duration-500 flex flex-col">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#c2a265]/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative flex-1 glass-card p-10 rounded-[2.3rem] transition-transform duration-500 group-hover:-translate-y-2 flex flex-col">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#c2a265] to-amber-700 flex items-center justify-center mb-8 shadow-[0_10px_20px_rgba(194,162,101,0.4)] border border-white/10 shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-4 shrink-0">Shuttle Transport</h3>
                        <p class="text-gray-400 leading-relaxed mb-8 flex-1">Don't want to drive? Add secure, professional transport to your booking and travel with peace of mind.</p>
                        <div class="shrink-0 mt-auto pt-4">
                            <span class="inline-flex items-center text-[#c2a265] font-black uppercase tracking-widest text-[10px] bg-[#c2a265]/10 px-4 py-2 rounded-full border border-[#c2a265]/20">Add at Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         4. BENTO GRID (WHY US) - FULLY CLICKABLE
         ========================================== --}}
    <section class="py-24 relative z-20 border-t border-white/5 bg-gradient-to-b from-[#020617] to-[#0a0f1c]">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 md:auto-rows-[320px]">

                {{-- Box 1: Large (Explore) --}}
                <a href="{{ route('explore') }}" class="block md:col-span-2 md:row-span-2 rounded-[2.5rem] relative overflow-hidden group shadow-2xl flex flex-col">
                    <img src="{{ asset('backgrounds/home.JPG') }}" class="absolute inset-0 w-full h-full object-cover opacity-30 group-hover:opacity-50 group-hover:scale-105 transition-all duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-12 w-full flex justify-between items-end">
                        <div>
                            <span class="text-[#c2a265] text-[10px] font-black uppercase tracking-widest mb-3 block">Discover</span>
                            <h3 class="text-4xl font-black text-white mb-3">Immersive Experiences</h3>
                            <p class="text-gray-300 font-medium max-w-sm">We meticulously vet every farm to ensure top-tier quality and unmatched landscapes.</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 group-hover:bg-[#c2a265] group-hover:border-transparent transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                    </div>
                </a>

                {{-- Box 2 (Security) - UPGRADED --}}
                <div class="md:col-span-2 rounded-[2.5rem] p-12 flex flex-col justify-center relative overflow-hidden group transition-all duration-500 shadow-xl border border-white/10 bg-gradient-to-br from-[#0f172a] to-[#0a0f1c] hover:from-[#1e293b] hover:to-[#0f172a] h-full">
                    {{-- Animated Background Glow --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#c2a265]/5 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite]"></div>

                    {{-- Big Background Icon --}}
                    <div class="absolute -right-5 -top-5 text-white/5 group-hover:text-[#c2a265]/10 group-hover:scale-110 transition-all duration-700 ease-out">
                        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>

                    {{-- Content --}}
                    <div class="relative z-10 flex items-center gap-3 mb-4 mt-auto shrink-0">
                        <div class="w-2 h-2 rounded-full bg-[#c2a265] animate-pulse"></div>
                        <span class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">Enterprise Protection</span>
                    </div>

                    <h3 class="text-4xl font-black text-white mb-3 relative z-10 flex items-center gap-3 shrink-0">
                        <span class="text-[#c2a265]">100%</span> Secure
                    </h3>

                    <p class="text-gray-400 relative z-10 max-w-sm text-sm leading-relaxed font-medium group-hover:text-gray-300 transition-colors mb-auto">
                        Your transactions and personal data are encrypted and protected by bank-level security protocols.
                    </p>
                </div>

                {{-- Box 3 (List Farm) --}}
                <a href="{{ route('partner.register') }}" class="block rounded-[2.5rem] bg-gradient-to-br from-[#1d5c42] to-[#0f291e] p-10 flex flex-col justify-between group hover:from-[#154230] hover:to-[#0a1c14] transition-all shadow-xl overflow-hidden relative border border-[#1d5c42]/50 h-full">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:bg-white/10 transition-colors"></div>
                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center border border-white/20 group-hover:scale-110 transition-transform shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div class="mt-8 relative z-10 shrink-0">
                        <h3 class="text-2xl font-black text-white mb-2">List Your Farm</h3>
                        <p class="text-green-100/70 text-sm font-medium">Join as a partner and start earning today.</p>
                    </div>
                </a>

                {{-- Box 4 (Support) --}}
                <a href="{{ route('contact') }}" class="block rounded-[2.5rem] glass-card p-10 flex flex-col justify-center items-center text-center group hover:border-[#c2a265]/50 hover:bg-[#c2a265]/5 transition-all shadow-xl relative overflow-hidden h-full">
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#c2a265]/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                    <h3 class="text-5xl font-black text-white mb-3 group-hover:text-[#c2a265] transition-colors relative z-10 shrink-0 mt-auto">24/7</h3>
                    <p class="text-gray-400 text-xs uppercase tracking-widest font-black relative z-10 group-hover:text-white transition-colors mb-auto">Customer Support</p>
                </a>

            </div>
        </div>
    </section>

    {{-- ==========================================
         5. FINAL CTA
         ========================================== --}}
    <section class="py-32 relative z-20">
        <div class="max-w-6xl mx-auto px-4">
            <div class="rounded-[3rem] bg-gradient-to-r from-[#1d5c42] to-[#0a1c14] p-16 md:p-24 text-center relative overflow-hidden shadow-[0_20px_50px_rgba(29,92,66,0.3)] border border-[#1d5c42]/50">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-[#c2a265]/20 rounded-full blur-[100px] animate-pulse-glow pointer-events-none"></div>

                <div class="relative z-10 space-y-8">
                    <h2 class="text-5xl md:text-7xl font-black text-white tracking-tight">Ready to unplug?</h2>
                    <p class="text-green-100/80 text-xl font-medium max-w-2xl mx-auto">Join thousands of others who have discovered their perfect weekend retreat with Mazraa.</p>
                    <div class="pt-8">
                        <a href="{{ route('explore') }}" class="inline-block px-12 py-6 bg-gradient-to-r from-[#c2a265] to-[#a3854d] hover:from-[#b09055] hover:to-[#8a6e3c] text-[#020617] rounded-full font-black uppercase tracking-widest transition-transform hover:scale-105 shadow-2xl hover:shadow-[0_0_40px_rgba(194,162,101,0.5)]">
                            Start Exploring Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
