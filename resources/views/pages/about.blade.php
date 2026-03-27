@extends('layouts.app')

@section('title', 'About Us - Mazraa')

@section('content')

<style>
    /* Advanced Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }
    .animate-float-slow { animation: float 7s ease-in-out infinite; }
    .animate-float-delayed { animation: float 7s ease-in-out 3.5s infinite; }

    /* Premium Glassmorphism */
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 30px 60px rgba(0,0,0,0.12);
    }
    .glass-card-light {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
    }
</style>

<div class="bg-[#020617] min-h-screen font-sans selection:bg-[#c2a265] selection:text-[#020617] overflow-hidden" x-data="{ mounted: false }" x-init="setTimeout(() => mounted = true, 100)">

    {{-- ==========================================
         1. HERO SECTION (Dark & Cinematic)
         ========================================== --}}
    <section class="relative w-full min-h-[70vh] flex flex-col items-center justify-center pt-32 pb-20">
        {{-- Deep Animated Background --}}
        <img src="{{ asset('backgrounds/contact&about.JPG') }}" alt="About Mazraa"
             class="absolute inset-0 w-full h-full object-cover opacity-20 scale-105 transition-transform duration-[40s] ease-out"
             :class="mounted ? 'scale-100' : 'scale-110'">

        <div class="absolute inset-0 bg-gradient-to-b from-[#020617]/95 via-[#020617]/60 to-[#020617]"></div>

        {{-- Glowing Orbs --}}
        <div class="absolute top-1/4 left-1/4 w-[35rem] h-[35rem] bg-[#1d5c42]/20 rounded-full blur-[140px] animate-float-slow pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[30rem] h-[30rem] bg-[#c2a265]/15 rounded-full blur-[120px] animate-float-delayed pointer-events-none"></div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <div class="animate-[fade-in-up_0.8s_ease-out]">
                <div class="inline-flex items-center gap-3 py-2 px-6 rounded-full bg-white/5 border border-white/10 text-[#c2a265] text-[10px] font-black tracking-[0.3em] uppercase backdrop-blur-xl mb-8 shadow-2xl">
                    <span class="w-2 h-2 rounded-full bg-[#c2a265] animate-ping absolute"></span>
                    <span class="w-2 h-2 rounded-full bg-[#c2a265] relative"></span>
                    Our Story
                </div>
            </div>

            <h1 class="text-6xl md:text-8xl font-black text-white tracking-tighter mb-8 leading-[1.1] animate-[fade-in-up_1s_ease-out_0.2s_both] drop-shadow-[0_10px_20px_rgba(0,0,0,0.5)]">
                Redefining The <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-[#f4e4c1] to-[#c2a265] relative inline-block group py-2">
                    Farm Escape
                    <span class="absolute bottom-0 left-0 w-full h-1.5 bg-gradient-to-r from-transparent via-[#c2a265] to-transparent opacity-60"></span>
                </span>
            </h1>

            <p class="text-lg md:text-2xl text-gray-300 font-medium max-w-3xl mx-auto leading-relaxed animate-[fade-in-up_1s_ease-out_0.4s_both] drop-shadow-md">
                Mazraa is Jordan's premier digital ecosystem, bridging the gap between nature lovers and unique agricultural properties through seamless booking, logistics, and supply integrations.
            </p>
        </div>
    </section>

    {{-- ==========================================
         2. VISION & VALUES (Glassmorphism)
         ========================================== --}}
    <section class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 mb-32">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- Vision --}}
            <div class="glass-card rounded-[2.5rem] p-10 md:p-14 group hover:bg-white/10 transition-all duration-500 hover:-translate-y-2 border-t border-white/20 animate-[fade-in-up_1s_ease-out_0.6s_both]">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#1d5c42] to-[#154230] flex items-center justify-center mb-8 shadow-[0_10px_20px_rgba(29,92,66,0.4)] border border-white/10 transform group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-8 h-8 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-white mb-4 tracking-tight">Our Vision</h3>
                <p class="text-gray-400 text-lg leading-relaxed font-medium">
                    To establish the ultimate digital marketplace for agricultural tourism, fostering trust, transparency, and unparalleled convenience for both property owners and getaway seekers.
                </p>
            </div>

            {{-- Values --}}
            <div class="glass-card rounded-[2.5rem] p-10 md:p-14 group hover:bg-white/10 transition-all duration-500 hover:-translate-y-2 border-t border-white/20 animate-[fade-in-up_1s_ease-out_0.8s_both]">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#c2a265] to-amber-700 flex items-center justify-center mb-8 shadow-[0_10px_20px_rgba(194,162,101,0.4)] border border-white/10 transform group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-white mb-6 tracking-tight">Core Values</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-4">
                        <div class="mt-1 bg-[#1d5c42]/20 p-1 rounded-full"><svg class="w-4 h-4 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                        <span class="text-gray-300 font-medium text-lg">Uncompromising Quality & Vetted Properties.</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="mt-1 bg-[#1d5c42]/20 p-1 rounded-full"><svg class="w-4 h-4 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                        <span class="text-gray-300 font-medium text-lg">End-to-end Ecosystem Integration.</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="mt-1 bg-[#1d5c42]/20 p-1 rounded-full"><svg class="w-4 h-4 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                        <span class="text-gray-300 font-medium text-lg">Absolute Trust & Financial Security.</span>
                    </li>
                </ul>
            </div>

        </div>
    </section>

    {{-- ==========================================
         3. WHAT WE OFFER (Light Bento Grid)
         ========================================== --}}
    <section class="relative z-20 bg-[#f8fafc] rounded-t-[4rem] pt-32 pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-20">
                <span class="text-[#1d5c42] text-[10px] font-black uppercase tracking-widest block mb-4">The Platform</span>
                <h2 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tight">The Ecosystem</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Box 1 --}}
                <div class="glass-card-light p-8 rounded-[2rem] hover:-translate-y-2 transition-all duration-500 group border-b-4 border-transparent hover:border-[#1d5c42]">
                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center mb-6 text-[#1d5c42] group-hover:scale-110 transition-transform duration-500 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 mb-3">Explore & Book</h4>
                    <p class="text-gray-500 font-medium leading-relaxed">Browse curated farms with high-resolution imagery and secure your dates instantly.</p>
                </div>

                {{-- Box 2 --}}
                <div class="glass-card-light p-8 rounded-[2rem] hover:-translate-y-2 transition-all duration-500 group border-b-4 border-transparent hover:border-blue-500">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 text-blue-600 group-hover:scale-110 transition-transform duration-500 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 mb-3">Supply Market</h4>
                    <p class="text-gray-500 font-medium leading-relaxed">Pre-order charcoal, premium meats, and snacks delivered directly to your farm gate.</p>
                </div>

                {{-- Box 3 --}}
                <div class="glass-card-light p-8 rounded-[2rem] hover:-translate-y-2 transition-all duration-500 group border-b-4 border-transparent hover:border-[#c2a265]">
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mb-6 text-[#c2a265] group-hover:scale-110 transition-transform duration-500 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 mb-3">Shuttle Service</h4>
                    <p class="text-gray-500 font-medium leading-relaxed">Don't want to drive? Book professional transport fleets for your entire group.</p>
                </div>

                {{-- Box 4 --}}
                <div class="glass-card-light p-8 rounded-[2rem] hover:-translate-y-2 transition-all duration-500 group border-b-4 border-transparent hover:border-gray-900">
                    <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mb-6 text-gray-900 group-hover:scale-110 transition-transform duration-500 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 mb-3">Enterprise Security</h4>
                    <p class="text-gray-500 font-medium leading-relaxed">Encrypted payments, verified reviews, and dedicated partner dashboards.</p>
                </div>

            </div>

            {{-- CTA Box inside Light Section --}}
            <div class="mt-20 bg-gradient-to-r from-[#1d5c42] to-[#0a1c14] rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-[0_20px_50px_rgba(29,92,66,0.2)]">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="relative z-10">
                    <h3 class="text-3xl md:text-5xl font-black text-white mb-6">Ready to join the ecosystem?</h3>
                    <p class="text-green-100/80 font-medium mb-10 max-w-xl mx-auto">Whether you're looking for an escape, or you own a property to list, Mazraa is your home.</p>
                    <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                        <a href="{{ route('explore') }}" class="px-10 py-5 bg-[#c2a265] hover:bg-[#a3854d] text-[#020617] rounded-full font-black uppercase tracking-widest transition-transform hover:scale-105 shadow-xl w-full sm:w-auto">
                            Explore Escapes
                        </a>
                        <a href="{{ route('partner.register') }}" class="px-10 py-5 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-full font-black uppercase tracking-widest transition-transform hover:scale-105 shadow-xl backdrop-blur-md w-full sm:w-auto">
                            List Your Farm
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

</div>
@endsection
