@extends('layouts.driver')

@section('title', 'Driver Command Center')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }

    /* Mobile-first enhancements for driver UI */
    .mobile-card { transition: all 0.3s ease; }
    .mobile-card:active { transform: scale(0.98); }
</style>

<div class="max-w-[96%] xl:max-w-4xl mx-auto space-y-8 pb-24 animate-god-in">

    {{-- 🌟 1. Floating Toast Notifications --}}
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" class="fixed top-24 right-5 z-[150] flex flex-col gap-4 pointer-events-none">
        @if(session('success'))
            <div x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10" class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-emerald-500/40 rounded-2xl shadow-[0_20px_50px_rgba(16,185,129,0.2)] p-5 flex items-start gap-4 w-80 md:w-96 relative overflow-hidden" x-cloak>
                <div class="absolute bottom-0 left-0 h-1.5 bg-emerald-500 w-full animate-[progress-shrink_6s_linear_forwards]"></div>
                <div class="bg-emerald-500/20 p-2.5 rounded-xl text-emerald-400 shrink-0 border border-emerald-500/30 shadow-inner"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                <div class="flex-1 mt-0.5"><h4 class="font-black text-white text-[11px] uppercase tracking-[0.2em]">Route Updated</h4><p class="text-slate-400 text-xs mt-1.5 font-medium leading-relaxed">{{ session('success') }}</p></div>
                <button @click="show = false" class="text-slate-600 hover:text-white transition-colors p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif
    </div>
    <style>@keyframes progress-shrink { from { width: 100%; } to { width: 0%; } }</style>

    {{-- 🌟 2. Hero Section (Amber/Cyan Theme for Drivers) --}}
    <div class="relative bg-slate-900/80 rounded-[3rem] p-8 md:p-12 border border-slate-800 shadow-2xl overflow-hidden backdrop-blur-2xl fade-in-up">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-amber-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-cyan-500/10 blur-[80px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-4 shadow-inner text-amber-500 mx-auto md:mx-0">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse shadow-[0_0_8px_#f59e0b]"></span>
                    Fleet Commander
                </div>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-2 text-white">Trip <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-200">Management</span></h1>
                <p class="text-slate-400 font-bold text-xs md:text-sm uppercase tracking-widest max-w-md leading-relaxed mt-3">Oversee assigned routes and navigate to pickups.</p>
            </div>

            <div class="bg-slate-950/80 backdrop-blur-xl border border-slate-800 p-6 rounded-[2rem] text-center min-w-[160px] shadow-inner transform hover:scale-105 transition-transform hover:border-amber-500/30 w-full md:w-auto">
                <p class="text-[9px] uppercase tracking-[0.3em] text-slate-500 font-black mb-1">Upcoming Jobs</p>
                <p class="text-5xl font-black text-amber-400 tracking-tighter drop-shadow-md">{{ $assignedTrips->count() }}</p>
            </div>
        </div>
    </div>

    {{-- 🌟 3. Current Active Trip (The Focus Area) --}}
    @if($activeTrip)
        <div class="fade-in-up-stagger" style="animation-delay: 0.15s;">
            <div class="flex items-center justify-between mb-4 px-2">
                <h2 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] flex items-center gap-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Active Route
                </h2>
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-cyan-500 shadow-[0_0_10px_#06b6d4]"></span>
                </span>
            </div>

            <div class="bg-slate-900 rounded-[2.5rem] shadow-2xl border border-slate-700 overflow-hidden relative backdrop-blur-xl">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-cyan-500 via-blue-500 to-emerald-400"></div>

                <div class="p-6 md:p-8">
                    {{-- Active Trip Header --}}
                    <div class="flex justify-between items-start mb-8 border-b border-slate-800 pb-6">
                        <div>
                            <span class="inline-flex items-center bg-cyan-500/10 text-cyan-400 text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest border border-cyan-500/20 mb-3 shadow-[0_0_15px_rgba(6,182,212,0.15)]">
                                In Progress
                            </span>
                            <h3 class="text-2xl md:text-3xl font-black text-white tracking-tight">{{ $activeTrip->user->name ?? 'Guest User' }}</h3>

                            @if($activeTrip->user && $activeTrip->user->phone)
                                <a href="tel:{{ $activeTrip->user->phone }}" class="inline-flex items-center text-slate-300 hover:text-cyan-400 text-xs font-bold mt-3 bg-slate-950 px-3 py-2 rounded-xl border border-slate-800 transition-colors active:scale-95 shadow-inner">
                                    <svg class="w-4 h-4 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $activeTrip->user->phone }}
                                </a>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] mb-1.5">Route ID</div>
                            <div class="text-lg font-black text-white font-mono bg-slate-950 px-3 py-1.5 rounded-xl border border-slate-700 shadow-inner">#{{ str_pad($activeTrip->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>

                    {{-- Navigation Timeline --}}
                    <div class="space-y-8 pl-2">
                        <div class="relative pl-8">
                            {{-- Timeline Line --}}
                            <div class="absolute left-[13px] top-4 bottom-[-40px] w-0.5 bg-slate-800 border-l-2 border-dashed border-slate-700"></div>

                            {{-- Pickup Node --}}
                            <div class="relative mb-10">
                                <div class="absolute -left-[39px] top-1 w-6 h-6 rounded-full bg-slate-950 border-[4px] border-cyan-500 z-10 shadow-[0_0_15px_rgba(6,182,212,0.4)] flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 bg-cyan-400 rounded-full"></div>
                                </div>
                                <p class="text-[9px] font-black text-cyan-500 uppercase tracking-[0.3em] mb-1.5">Origin (Pickup)</p>
                                <p class="text-lg text-white font-black leading-tight">{{ $activeTrip->pickup_location ?? $activeTrip->start_and_return_point ?? 'Location Pending' }}</p>

                                <a href="https://maps.google.com/?q={{ urlencode($activeTrip->pickup_location ?? $activeTrip->start_and_return_point ?? '') }}" target="_blank" class="inline-flex items-center justify-center mt-4 text-[10px] font-black uppercase tracking-widest text-cyan-400 bg-cyan-500/10 px-5 py-3 rounded-xl border border-cyan-500/30 hover:bg-cyan-500/20 transition-all active:scale-95 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Navigate Origin
                                </a>
                            </div>

                            {{-- Destination Node --}}
                            <div class="relative">
                                <div class="absolute -left-[39px] top-1 w-6 h-6 rounded-full bg-slate-950 border-[4px] border-emerald-500 z-10 flex items-center justify-center shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                                    <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></div>
                                </div>
                                <p class="text-[9px] font-black text-emerald-500 uppercase tracking-[0.3em] mb-1.5">Destination (Drop-off)</p>
                                <p class="text-lg text-white font-black leading-tight">{{ $activeTrip->farmBooking->farm->name ?? 'Farm Destination' }}</p>

                                <a href="https://maps.google.com/?q={{ urlencode($activeTrip->farmBooking->farm->name ?? '') }}" target="_blank" class="inline-flex items-center justify-center mt-4 text-[10px] font-black uppercase tracking-widest text-emerald-400 bg-emerald-500/10 px-5 py-3 rounded-xl border border-emerald-500/30 hover:bg-emerald-500/20 transition-all active:scale-95 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Navigate Destination
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Notes --}}
                    @if($activeTrip->notes)
                        <div class="mt-8 bg-amber-500/10 border border-amber-500/20 rounded-2xl p-5 shadow-inner">
                            <span class="flex items-center gap-2 text-[9px] font-black text-amber-500 uppercase tracking-[0.2em] mb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Dispatch Notes
                            </span>
                            <p class="text-sm font-bold text-amber-100 leading-relaxed italic">"{{ $activeTrip->notes }}"</p>
                        </div>
                    @endif
                </div>

                {{-- Action Button (Massive Mobile Button) --}}
                <div class="p-6 bg-slate-950 border-t border-slate-800 relative z-10">
                    <form action="{{ route('transport.driver.update_status', $activeTrip->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button onclick="return confirm('Confirm secure drop-off? This will complete the route.');" class="w-full flex items-center justify-center px-4 py-5 border border-emerald-500/50 text-[11px] font-black tracking-[0.3em] rounded-2xl text-slate-950 bg-gradient-to-r from-emerald-500 to-emerald-400 hover:to-emerald-300 focus:outline-none transition-all shadow-[0_0_30px_rgba(16,185,129,0.3)] hover:shadow-[0_0_40px_rgba(16,185,129,0.5)] active:scale-95 uppercase mobile-card">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Confirm Drop-off
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- 🌟 4. Assigned Trips Queue --}}
    <div class="mb-10 fade-in-up-stagger" style="animation-delay: 0.3s;">
        <h2 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-6 pl-2 flex items-center gap-3">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Assigned Queue ({{ $assignedTrips->count() }})
        </h2>

        <div class="space-y-5">
            @forelse($assignedTrips as $trip)
                <div class="bg-slate-900/80 rounded-[2.5rem] border border-slate-800 overflow-hidden shadow-xl backdrop-blur-md {{ $activeTrip ? 'opacity-50 grayscale-[50%] pointer-events-none' : 'hover:border-cyan-500/30 hover:shadow-2xl hover:shadow-cyan-500/10' }} transition-all duration-300 mobile-card">
                    <div class="p-6 md:p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div class="text-[10px] font-black font-mono text-cyan-500 bg-slate-950 px-3 py-1.5 rounded-lg border border-slate-800 uppercase tracking-widest shadow-inner">#TRP-{{ str_pad($trip->id, 4, '0', STR_PAD_LEFT) }}</div>
                            <span class="bg-blue-500/10 text-blue-400 text-[9px] font-black px-3 py-1.5 rounded-lg border border-blue-500/20 uppercase tracking-widest shadow-sm">Ready</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4 mb-8">
                            <div class="bg-slate-950/50 p-4 rounded-2xl border border-slate-800 shadow-inner">
                                <span class="block text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1.5">Dispatch Time</span>
                                <span class="text-base font-black text-white tracking-tight">{{ optional($trip->Farm_Arrival_Time)->format('M d, H:i') ?? 'N/A' }}</span>
                            </div>
                            <div class="bg-slate-950/50 p-4 rounded-2xl border border-slate-800 shadow-inner">
                                <span class="block text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1.5">Client</span>
                                <span class="text-base font-black text-white tracking-tight truncate block">{{ $trip->user->name ?? 'N/A' }}</span>
                            </div>
                            <div class="md:col-span-2 bg-slate-950 p-5 rounded-2xl border border-slate-700 shadow-inner relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-cyan-500"></div>
                                <span class="block text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Route Vector</span>

                                <div class="flex items-center text-xs font-bold text-slate-300">
                                    <span class="w-2 h-2 rounded-full bg-cyan-500 mr-3 shrink-0 shadow-[0_0_5px_#06b6d4]"></span>
                                    <span class="truncate">{{ $trip->pickup_location ?? $trip->start_and_return_point }}</span>
                                </div>
                                <div class="w-0.5 h-3 bg-slate-700 ml-[3px] my-1"></div>
                                <div class="flex items-center text-xs font-bold text-white">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-3 shrink-0 shadow-[0_0_5px_#10b981]"></span>
                                    <span class="truncate">{{ $trip->farmBooking->farm->name ?? 'Farm Destination' }}</span>
                                </div>
                            </div>
                        </div>

                        @if(!$activeTrip)
                            <form action="{{ route('transport.driver.update_status', $trip->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">
                                <button class="w-full flex justify-center items-center py-4.5 px-6 border border-cyan-500/30 rounded-2xl shadow-[0_0_15px_rgba(6,182,212,0.15)] text-[10px] font-black tracking-[0.2em] uppercase text-slate-950 bg-cyan-500 hover:bg-cyan-400 focus:outline-none transition-all active:scale-95">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                                    Initiate Route
                                </button>
                            </form>
                        @else
                            <div class="w-full text-center py-4 bg-slate-950 text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 rounded-2xl border border-slate-800 shadow-inner">
                                Finish active route to proceed
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-slate-900/40 rounded-[3rem] border border-slate-800 border-dashed p-12 text-center flex flex-col items-center">
                    <div class="w-20 h-20 bg-slate-950 rounded-[1.5rem] flex items-center justify-center mb-5 border border-slate-800 shadow-inner">
                        <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <p class="text-xl font-black text-white tracking-tight mb-2">Queue Empty</p>
                    <p class="text-[10px] uppercase tracking-widest font-bold text-slate-500 max-w-xs">Awaiting dispatch orders from HQ.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
