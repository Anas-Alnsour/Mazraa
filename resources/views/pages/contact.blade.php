@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')

<style>
    /* Advanced Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }
    .animate-float-slow { animation: float 6s ease-in-out infinite; }
    .animate-float-delayed { animation: float 6s ease-in-out 3s infinite; }

    /* Premium Glassmorphism */
    .glass-panel {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 30px 60px rgba(0,0,0,0.2);
    }

    .glass-input {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }
    .glass-input:focus {
        background: rgba(15, 23, 42, 0.8);
        border-color: rgba(194, 162, 101, 0.5);
        box-shadow: 0 0 15px rgba(194, 162, 101, 0.1);
    }

    .info-card { transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1); }
    .info-card:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.08); border-color: rgba(194, 162, 101, 0.3); }
</style>

<div class="relative min-h-screen bg-[#020617] pt-32 pb-24 font-sans selection:bg-[#c2a265] selection:text-[#020617] overflow-hidden">

    {{-- ==========================================
         1. ANIMATED BACKGROUND
         ========================================== --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        {{-- Background Image --}}
        <img src="{{ asset('backgrounds/Contact&about.JPG') }}" onerror="this.src='{{ asset('backgrounds/home.JPG') }}'" alt="Contact Background"
             class="w-full h-full object-cover opacity-20 scale-105 animate-[pulse_20s_ease-in-out_infinite]">

        {{-- Gradient Overlays --}}
        <div class="absolute inset-0 bg-gradient-to-b from-[#020617]/95 via-[#020617]/80 to-[#020617]"></div>

        {{-- Glowing Orbs --}}
        <div class="absolute top-1/4 left-1/4 w-[30rem] h-[30rem] bg-[#1d5c42]/20 rounded-full blur-[120px] animate-float-slow"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[25rem] h-[25rem] bg-[#c2a265]/15 rounded-full blur-[100px] animate-float-delayed"></div>
    </div>

    {{-- ==========================================
         2. MAIN CONTENT CONTAINER
         ========================================== --}}
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header Section --}}
        <div class="text-center mb-16 animate-[fade-in-up_0.8s_ease-out]">
            <span class="inline-flex items-center gap-2 py-1.5 px-5 rounded-full bg-white/5 border border-white/10 text-[#c2a265] text-[10px] font-black tracking-[0.3em] uppercase backdrop-blur-xl mb-6 shadow-2xl">
                <span class="w-1.5 h-1.5 rounded-full bg-[#c2a265] animate-ping"></span>
                We're Here For You
            </span>
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter mb-6 leading-tight drop-shadow-2xl">
                Let's <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-[#f4e4c1] to-[#c2a265]">Connect</span>
            </h1>
            <p class="text-lg text-gray-400 font-medium max-w-2xl mx-auto leading-relaxed">
                Have questions about booking a luxury farm, joining our partner network, or need support? Drop us a line.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

            {{-- ==========================================
                 3. CONTACT INFORMATION (Left Column)
                 ========================================== --}}
            <div class="lg:col-span-5 flex flex-col gap-6 animate-[fade-in-up_1s_ease-out_0.2s_both]">

                {{-- Email Card --}}
                <div class="info-card bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 flex items-start gap-5 shadow-xl">
                    <div class="w-14 h-14 shrink-0 rounded-full bg-gradient-to-br from-[#1d5c42] to-[#154230] flex items-center justify-center shadow-lg border border-[#1d5c42]/50">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5">Email Us</h3>
                        <p class="text-lg font-bold text-white mb-1">support@mazraa.com</p>
                        <p class="text-xs text-gray-500 font-medium">We usually reply within 24 hours.</p>
                    </div>
                </div>

                {{-- Phone Card --}}
                <div class="info-card bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 flex items-start gap-5 shadow-xl">
                    <div class="w-14 h-14 shrink-0 rounded-full bg-gradient-to-br from-[#c2a265] to-[#a3854d] flex items-center justify-center shadow-lg border border-[#c2a265]/50">
                        <svg class="w-6 h-6 text-[#020617]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5">Call Us</h3>
                        <p class="text-lg font-bold text-white mb-1">+962 79 123 4567</p>
                        <p class="text-xs text-gray-500 font-medium">Mon-Fri from 9am to 6pm (Amman Time).</p>
                    </div>
                </div>

                {{-- Office Card --}}
                <div class="info-card bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 flex items-start gap-5 shadow-xl">
                    <div class="w-14 h-14 shrink-0 rounded-full bg-white/10 flex items-center justify-center shadow-lg border border-white/20">
                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5">Our Office</h3>
                        <p class="text-lg font-bold text-white mb-1">Amman, Jordan</p>
                        <p class="text-xs text-gray-500 font-medium leading-relaxed">Mecca Street, Mazraa HQ Building, 4th Floor.</p>
                    </div>
                </div>

            </div>

            {{-- ==========================================
                 4. CONTACT FORM (Right Column)
                 ========================================== --}}
            <div class="lg:col-span-7 animate-[fade-in-up_1s_ease-out_0.4s_both]">
                <div class="glass-panel rounded-[2.5rem] p-8 md:p-12 relative overflow-hidden">

                    {{-- Decorative top border --}}
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1d5c42] via-[#c2a265] to-transparent"></div>

                    <h2 class="text-3xl font-black text-white mb-2">Send a Message</h2>
                    <p class="text-gray-400 text-sm mb-8 font-medium">Fill out the form below and our team will get back to you immediately.</p>

                    {{-- Alpine JS Success Alert --}}
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.opacity.duration.500ms
                             class="mb-8 p-4 bg-green-500/10 border border-green-500/30 rounded-2xl flex items-start gap-4 backdrop-blur-md">
                            <div class="bg-green-500/20 p-1.5 rounded-full text-green-400 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-black text-green-400 uppercase tracking-widest mb-1">Message Sent</h4>
                                <p class="text-xs text-green-200/70 font-medium">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="text-green-400/50 hover:text-green-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.submit') }}" class="space-y-6">
                        @csrf

                        {{-- Name Input --}}
                        <div class="relative group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 pl-1 group-focus-within:text-[#c2a265] transition-colors" for="name">Your Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-500 group-focus-within:text-[#c2a265] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. John Doe"
                                       class="glass-input w-full pl-12 pr-4 py-4 rounded-2xl text-white placeholder-gray-600 font-medium text-sm outline-none" />
                            </div>
                        </div>

                        {{-- Email Input --}}
                        <div class="relative group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 pl-1 group-focus-within:text-[#c2a265] transition-colors" for="email">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-500 group-focus-within:text-[#c2a265] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                </div>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="e.g. name@example.com"
                                       class="glass-input w-full pl-12 pr-4 py-4 rounded-2xl text-white placeholder-gray-600 font-medium text-sm outline-none" />
                            </div>
                        </div>

                        {{-- Message Textarea --}}
                        <div class="relative group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 pl-1 group-focus-within:text-[#c2a265] transition-colors" for="message">Your Message</label>
                            <div class="relative">
                                <textarea id="message" name="message" rows="5" required placeholder="How can we help you today?"
                                          class="glass-input w-full p-5 rounded-2xl text-white placeholder-gray-600 font-medium text-sm outline-none resize-none"></textarea>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full py-4 px-8 rounded-2xl bg-gradient-to-r from-[#c2a265] to-[#a3854d] hover:from-[#b09055] hover:to-[#8a6e3c] text-[#020617] font-black text-sm uppercase tracking-widest shadow-[0_10px_20px_rgba(194,162,101,0.3)] hover:shadow-[0_15px_30px_rgba(194,162,101,0.5)] transform hover:-translate-y-1 transition-all duration-300 focus:outline-none flex items-center justify-center gap-2">
                                Send Message
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
