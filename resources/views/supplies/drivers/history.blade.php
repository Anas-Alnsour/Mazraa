@extends('layouts.driver')

@section('title', 'Supply Archive Log')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }

    /* Mobile hover fixes */
    .mobile-card { transition: all 0.3s ease; }
    .mobile-card:hover { transform: translateY(-4px); }
</style>

<div class="max-w-[96%] xl:max-w-6xl mx-auto space-y-10 pb-24 animate-god-in">

    {{-- 🌟 1. HERO SECTION (SUPPLY HISTORY THEME) --}}
    <div class="relative bg-slate-900/80 rounded-[3rem] p-8 md:p-12 border border-slate-800 shadow-2xl overflow-hidden backdrop-blur-2xl fade-in-up">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-teal-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-500/10 blur-[80px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-4 shadow-inner text-teal-400 mx-auto md:mx-0">
                    <svg class="w-3 h-3 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Historical Archive
                </div>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-2 text-white">Delivery <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400">History</span></h1>
                <p class="text-slate-400 font-bold text-xs md:text-sm uppercase tracking-widest max-w-md leading-relaxed mt-3">Review completed supply drops and logistical milestones.</p>
            </div>

            <div class="bg-slate-950/80 backdrop-blur-xl border border-slate-800 p-6 rounded-[2rem] text-center min-w-[160px] shadow-inner transform hover:scale-105 transition-transform hover:border-teal-500/30 w-full md:w-auto">
                <p class="text-[9px] uppercase tracking-[0.3em] text-slate-500 font-black mb-1">Total Deliveries</p>
                <p class="text-5xl font-black text-teal-400 tracking-tighter drop-shadow-md">{{ $groupedHistory->count() }}</p>
            </div>
        </div>
    </div>

    {{-- 🌟 2. HISTORY GRID (ARCHIVE) --}}
    @if($groupedHistory->isEmpty())
        <div class="bg-slate-900/40 backdrop-blur-xl rounded-[3rem] border border-slate-800 border-dashed p-16 text-center flex flex-col items-center shadow-inner fade-in-up" style="animation-delay: 0.15s;">
            <div class="w-24 h-24 bg-slate-950 rounded-[2rem] flex items-center justify-center mx-auto mb-6 border border-slate-800 shadow-inner">
                <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-white tracking-tight mb-2">Archive Null</h3>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 max-w-sm">No completed deliveries found in your operational records yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 fade-in-up-stagger" style="animation-delay: 0.2s;">
            @foreach($groupedHistory as $invoiceId => $items)
                @php
                    $firstItem = $items->first();
                    $totalInvoiceValue = $items->sum('total_price');
                @endphp
                <div class="bg-slate-900/60 rounded-[2.5rem] border border-slate-800 overflow-hidden shadow-2xl backdrop-blur-xl flex flex-col hover:border-emerald-500/30 hover:shadow-[0_15px_40px_rgba(16,185,129,0.1)] transition-all duration-300 mobile-card group">

                    {{-- Card Header --}}
                    <div class="p-6 md:p-8 border-b border-slate-800 bg-slate-950/40 relative flex justify-between items-start">
                        <div>
                            <div class="bg-slate-950 border border-slate-800 px-4 py-3 rounded-2xl shadow-inner inline-block mb-4">
                                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-teal-500 mb-1 leading-none">Invoice Trace</p>
                                <h3 class="text-xl font-black text-white font-mono tracking-widest">#{{ $invoiceId }}</h3>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Completed On</p>
                                <p class="text-sm font-black text-white">{{ $firstItem->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <div class="bg-emerald-500/10 text-emerald-400 text-[9px] font-black px-3 py-1.5 rounded-xl border border-emerald-500/20 uppercase tracking-widest shadow-[0_0_15px_rgba(16,185,129,0.15)] flex items-center gap-1.5 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Delivered
                        </div>
                    </div>

                    {{-- Card Body (Details) --}}
                    <div class="p-6 md:p-8 flex-grow bg-slate-900/20">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Destination --}}
                            <div class="space-y-2">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] flex items-center gap-2">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    Destination
                                </p>
                                <p class="text-sm font-bold text-white leading-tight">{{ $firstItem->booking->farm->name ?? 'Farm Destination' }}</p>
                            </div>

                            {{-- Client --}}
                            <div class="space-y-2">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] flex items-center gap-2">
                                    <svg class="w-3 h-3 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Client Identity
                                </p>
                                <p class="text-sm font-bold text-white">{{ $firstItem->user->name ?? 'Guest User' }}</p>
                            </div>
                        </div>

                        {{-- Manifest Summary --}}
                        <div class="bg-slate-950 rounded-2xl p-5 border border-slate-800 shadow-inner group-hover:border-teal-500/20 transition-colors">
                            <p class="text-[9px] font-black text-teal-500 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                Manifest Payload
                            </p>
                            <div class="space-y-3">
                                @foreach($items as $item)
                                    <div class="flex justify-between items-center text-xs">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded bg-teal-500/10 flex items-center justify-center text-[9px] font-black text-teal-400 border border-teal-500/20">{{ $item->quantity }}x</span>
                                            <span class="font-bold text-slate-300">{{ $item->supply->name }}</span>
                                        </div>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-500">Verified</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Card Footer (Total Value) --}}
                    <div class="p-6 md:px-8 md:py-5 bg-slate-950 border-t border-slate-800 flex items-center justify-between">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">Total Yield</p>
                        <p class="text-xl font-black text-teal-400 tracking-tighter">{{ number_format($totalInvoiceValue, 2) }} <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest ml-0.5">JOD</span></p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    /* Clean up pagination if needed later */
    .custom-pagination nav { @apply flex items-center justify-center gap-2; }
</style>
@endsection
