@extends('layouts.driver')

@section('title', 'Trip History Log')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }

    /* Mobile hover fixes */
    .mobile-card { transition: all 0.3s ease; }
    .mobile-card:active { transform: scale(0.98); }
</style>

<div class="max-w-[96%] xl:max-w-4xl mx-auto space-y-8 pb-24 animate-god-in">

    {{-- 🌟 1. Hero Section (Amber/Cyan Theme for Drivers) --}}
    <div class="relative bg-slate-900/80 rounded-[3rem] p-8 md:p-12 border border-slate-800 shadow-2xl overflow-hidden backdrop-blur-2xl fade-in-up">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-amber-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-500/10 blur-[80px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-4 shadow-inner text-amber-500 mx-auto md:mx-0">
                    <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Fleet Logs
                </div>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-2 text-white">Trip <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-200">History</span></h1>
                <p class="text-slate-400 font-bold text-xs md:text-sm uppercase tracking-widest max-w-md leading-relaxed mt-3">Review completed routes, drop-off nodes, and chronological logs.</p>
            </div>

            <div class="bg-slate-950/80 backdrop-blur-xl border border-slate-800 p-6 rounded-[2rem] text-center min-w-[160px] shadow-inner transform hover:scale-105 transition-transform hover:border-amber-500/30 w-full md:w-auto">
                <p class="text-[9px] uppercase tracking-[0.3em] text-slate-500 font-black mb-1">Completed Trips</p>
                <p class="text-5xl font-black text-amber-400 tracking-tighter drop-shadow-md">{{ $trips->count() }}</p>
            </div>
        </div>
    </div>

    {{-- 🌟 2. History List (Mobile-Optimized Cards) --}}
    @if($trips->isEmpty())
        <div class="bg-slate-900/40 backdrop-blur-xl rounded-[3rem] border border-slate-800 border-dashed p-16 text-center flex flex-col items-center shadow-inner fade-in-up" style="animation-delay: 0.2s;">
            <div class="w-24 h-24 bg-slate-950 rounded-[2rem] flex items-center justify-center mx-auto mb-6 border border-slate-800 shadow-inner">
                <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-white tracking-tight mb-2">Logbook Empty</h3>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 max-w-sm">No completed transport routes found in your database sector yet.</p>
        </div>
    @else
        <div class="space-y-6 fade-in-up" style="animation-delay: 0.2s;">
            @foreach($trips as $trip)
                <div class="group bg-slate-900/60 hover:bg-slate-900 backdrop-blur-md transition-all duration-300 rounded-[2.5rem] border border-slate-800 hover:border-emerald-500/30 p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xl hover:shadow-[0_15px_40px_rgba(16,185,129,0.1)] mobile-card">

                    {{-- Left Side: Date & Route --}}
                    <div class="flex flex-col md:flex-row md:items-center gap-6 md:gap-8 flex-1">

                        {{-- Date Stamp --}}
                        <div class="flex flex-row md:flex-col items-center md:items-start justify-between md:justify-center md:min-w-[100px] border-b md:border-b-0 md:border-r border-slate-800 pb-4 md:pb-0 md:pr-6">
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 mb-1">Time Node</p>
                                <p class="text-xl font-black text-white tracking-tighter leading-none">{{ $trip->updated_at->format('M d') }}</p>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">{{ $trip->updated_at->format('Y, h:i A') }}</p>
                            </div>

                            {{-- Mobile Trip ID display --}}
                            <div class="md:hidden text-right">
                                <p class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-1">Log ID</p>
                                <p class="text-[10px] font-mono font-black text-amber-500 bg-slate-950 px-2 py-1 rounded border border-slate-800">#{{ str_pad($trip->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>

                        {{-- Route Vector --}}
                        <div class="flex-1 space-y-5 py-2">
                            <div class="relative">
                                {{-- Visual Connection Line --}}
                                <div class="absolute left-[7px] top-4 bottom-[-16px] w-0.5 bg-slate-800 border-l border-dashed border-slate-700"></div>

                                {{-- Origin --}}
                                <div class="flex items-start gap-4 mb-4 relative">
                                    <div class="w-4 h-4 rounded-full bg-slate-950 border-[3px] border-cyan-500 z-10 shadow-[0_0_10px_rgba(6,182,212,0.4)] mt-0.5 flex shrink-0"></div>
                                    <div>
                                        <p class="text-[9px] font-black text-cyan-500 uppercase tracking-widest mb-0.5">Origin</p>
                                        <p class="text-sm font-bold text-slate-300 leading-snug group-hover:text-white transition-colors">{{ $trip->pickup_location ?? $trip->start_and_return_point }}</p>
                                    </div>
                                </div>

                                {{-- Destination --}}
                                <div class="flex items-start gap-4 relative">
                                    <div class="w-4 h-4 rounded-full bg-slate-950 border-[3px] border-emerald-500 z-10 shadow-[0_0_10px_rgba(16,185,129,0.4)] mt-0.5 flex shrink-0"></div>
                                    <div>
                                        <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-0.5">Destination</p>
                                        <p class="text-sm font-bold text-slate-300 leading-snug group-hover:text-white transition-colors">{{ $trip->farmBooking->farm->name ?? 'Farm Destination' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Side: Desktop ID & Status --}}
                    <div class="flex items-center justify-between md:flex-col md:items-end gap-4 md:gap-6 border-t md:border-t-0 border-slate-800 pt-5 md:pt-0">
                        <div class="hidden md:block text-right">
                            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 mb-1">Log ID</p>
                            <p class="text-xs font-mono font-black text-amber-500 bg-slate-950 px-3 py-1.5 rounded-lg border border-slate-800 shadow-inner">#TRP-{{ str_pad($trip->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="text-left md:text-right w-full md:w-auto">
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-1.5 hidden md:block">Status Matrix</p>
                            <span class="inline-flex items-center justify-center w-full md:w-auto gap-2 text-[10px] font-black text-emerald-400 uppercase tracking-widest bg-emerald-500/10 px-4 py-2.5 rounded-xl border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                Completed
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Pagination (If needed later) --}}
            @if(method_exists($trips, 'hasPages') && $trips->hasPages())
                <div class="mt-10 flex justify-center custom-pagination">
                    {{ $trips->links() }}
                </div>
            @endif
        </div>
    @endif
</div>

<style>
    .custom-pagination nav { @apply flex items-center justify-center gap-2; }
    .custom-pagination .page-link { @apply bg-slate-900 border-none text-slate-400 text-[11px] font-black px-5 py-3 rounded-xl transition-all hover:bg-amber-600 hover:text-slate-950 shadow-lg; }
    .custom-pagination .active .page-link { @apply bg-amber-600 text-slate-950 shadow-[0_0_20px_rgba(245,158,11,0.4)]; }
    .custom-pagination .disabled .page-link { @apply bg-transparent opacity-20 text-slate-700 cursor-not-allowed; }
</style>
@endsection
