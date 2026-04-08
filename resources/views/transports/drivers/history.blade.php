@extends('layouts.driver')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">

    {{-- ==========================================
         HERO SECTION (TRANSPORT HISTORY THEME)
         ========================================== --}}
    <div class="relative bg-gradient-to-r from-slate-800 to-amber-900 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl border border-amber-800/30 overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-amber-400/5 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>
        
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-black uppercase tracking-widest mb-6 backdrop-blur-md">
                <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Fleet logs
            </div>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-4">Trip <span class="text-amber-500/80">History</span></h1>
            <p class="text-slate-400 font-medium max-w-md leading-relaxed">Your completed logistical journeys across the region. Review pickup locations, drop-off farms, and trip summaries.</p>
        </div>
    </div>

    {{-- ==========================================
         HISTORY LIST
         ========================================== --}}

    @if($trips->isEmpty())
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-[2.5rem] border border-slate-700/50 border-dashed p-16 text-center">
            <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-700 shadow-inner">
                <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </div>
            <h3 class="text-xl font-black text-white tracking-tight">Logbook empty</h3>
            <p class="mt-2 text-slate-400 font-medium">No completed transport trips found in your history yet.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($trips as $trip)
                <div class="group bg-slate-800/40 hover:bg-slate-800/60 transition-all rounded-[2rem] border border-slate-700/50 p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-lg">
                    <div class="flex flex-col md:flex-row md:items-center gap-6 flex-1">
                        <div class="text-center md:text-left min-w-[100px]">
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-1">Date</p>
                            <p class="text-sm font-black text-white uppercase">{{ $trip->updated_at->format('M d') }}</p>
                            <p class="text-[10px] font-bold text-slate-500">{{ $trip->updated_at->format('Y') }}</p>
                        </div>

                        <div class="h-10 w-0.5 bg-slate-700 hidden md:block"></div>

                        <div class="flex-1 space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.5)]"></span>
                                <p class="text-xs font-bold text-slate-300 truncate">{{ $trip->pickup_location ?? $trip->start_and_return_point }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                                <p class="text-xs font-bold text-slate-300 truncate">{{ $trip->farmBooking->farm->name ?? 'Farm Destination' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between md:justify-end gap-8 border-t md:border-t-0 border-slate-700/50 pt-4 md:pt-0">
                        <div class="text-right">
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-1">Trip ID</p>
                            <p class="text-xs font-black text-slate-300">#TRP-{{ str_pad($trip->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-1">Status</p>
                            <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest bg-emerald-500/10 px-3 py-1 rounded-lg border border-emerald-500/20">Completed</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
</style>
@endsection
