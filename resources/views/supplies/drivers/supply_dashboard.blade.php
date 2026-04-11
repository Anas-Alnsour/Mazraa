@extends('layouts.driver')

@section('title', 'Supply Node Dashboard')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }

    /* Modern Custom Select for Dark Mode */
    .dark-select {
        appearance: none;
        background-color: rgba(2, 6, 23, 0.8) !important;
        border: 1px solid rgba(20, 184, 166, 0.3) !important;
        color: #fff !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%232dd4bf' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 7l5 5 5-5'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        transition: all 0.3s ease;
    }
    .dark-select:focus {
        border-color: #14b8a6 !important;
        box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.15) !important;
        outline: none;
    }
    .dark-select option {
        background-color: #0f172a;
        color: #fff;
    }

    /* Mobile Card Interactions */
    .mobile-card { transition: all 0.3s ease; }
    .mobile-card:active { transform: scale(0.98); }
</style>

<div class="max-w-[96%] xl:max-w-6xl mx-auto space-y-12 pb-24 animate-god-in">

    {{-- 🌟 1. HERO SECTION (SUPPLY THEME: TEAL/EMERALD) --}}
    <div class="relative bg-slate-900/80 rounded-[3rem] p-8 md:p-12 border border-slate-800 shadow-2xl overflow-hidden backdrop-blur-2xl fade-in-up">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-teal-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-500/10 blur-[80px] rounded-full pointer-events-none"></div>

        <svg class="absolute bottom-[-20%] right-[5%] w-[40%] h-[auto] text-teal-500/5 transform rotate-12 pointer-events-none" fill="currentColor" viewBox="0 0 24 24"><path d="M20 8h-3V4H3v13h2v-2h14v2h2V10l-3-2zM5 15V6h10v9H5zm14 0h-2v-3h-4v-2h3.5l2.5 1.67V15z"/></svg>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-4 shadow-inner text-teal-400 mx-auto md:mx-0">
                    <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse shadow-[0_0_8px_#2dd4bf]"></span>
                    Logistics Hub
                </div>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-2 text-white">Delivery <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400">Operations</span></h1>
                <p class="text-slate-400 font-bold text-xs md:text-sm uppercase tracking-widest max-w-md leading-relaxed mt-3">Manage assigned supply drops, track routes, and confirm deliveries for farm owners.</p>
            </div>

            <div class="bg-slate-950/80 backdrop-blur-xl border border-slate-800 p-6 rounded-[2rem] text-center min-w-[160px] shadow-inner transform hover:scale-105 transition-transform hover:border-teal-500/30 w-full md:w-auto">
                <p class="text-[9px] uppercase tracking-[0.3em] text-slate-500 font-black mb-1">Total Invoices</p>
                <p class="text-5xl font-black text-teal-400 tracking-tighter drop-shadow-md">{{ $groupedOrders->count() }}</p>
            </div>
        </div>
    </div>

    {{-- 🌟 2. ORDERS GRID (GROUPED BY INVOICE) --}}
    @if($groupedOrders->isEmpty())
        <div class="bg-slate-900/40 backdrop-blur-xl rounded-[3rem] border border-slate-800 border-dashed p-16 text-center flex flex-col items-center shadow-inner fade-in-up" style="animation-delay: 0.15s;">
            <div class="w-24 h-24 bg-slate-950 rounded-[2rem] flex items-center justify-center mx-auto mb-6 border border-slate-800 shadow-inner">
                <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-white tracking-tight mb-2">No Deliveries Found</h3>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 max-w-sm">You currently have no assigned supply deliveries. Rest and await dispatch orders.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 fade-in-up-stagger" style="animation-delay: 0.2s;">
            @foreach($groupedOrders as $invoiceId => $items)
                @php
                    $firstItem = $items->first();
                    $invoiceTotal = $items->sum('total_price');
                    $isDelivered = $firstItem->status === 'delivered';
                @endphp

                <div class="bg-slate-900/60 rounded-[2.5rem] border border-slate-800 overflow-hidden shadow-2xl backdrop-blur-xl flex flex-col hover:border-teal-500/30 transition-all duration-300 {{ $isDelivered ? 'opacity-60 grayscale-[30%] pointer-events-none' : 'mobile-card' }}">

                    {{-- 🌟 Invoice Header --}}
                    <div class="p-6 md:p-8 border-b border-slate-800 bg-slate-950/40 relative">
                        <div class="flex justify-between items-start mb-6">
                            <div class="bg-slate-950 border border-slate-800 px-4 py-3 rounded-2xl shadow-inner">
                                <p class="text-[9px] font-black uppercase tracking-widest text-teal-500 mb-1 leading-none">Invoice ID</p>
                                <h3 class="text-xl font-black text-white font-mono tracking-widest">#{{ $invoiceId }}</h3>
                            </div>

                            @if($firstItem->booking->farm->location_link && !$isDelivered)
                                <a href="{{ $firstItem->booking->farm->location_link }}" target="_blank" class="w-12 h-12 rounded-[1rem] bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center shadow-[0_0_15px_rgba(20,184,166,0.2)] hover:bg-teal-500/20 transition-all active:scale-95" title="Navigate to Farm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </a>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Destination Node</p>
                                <p class="text-sm font-bold text-white">{{ $firstItem->booking->farm->name ?? 'N/A' }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest truncate line-clamp-1">{{ $firstItem->booking->farm->location ?? 'No location details' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Client Identity</p>
                                <p class="text-sm font-bold text-white">{{ $firstItem->user->name ?? 'Guest' }}</p>
                                <a href="tel:{{ $firstItem->user->phone }}" class="text-[10px] text-teal-400 font-black uppercase tracking-widest flex items-center gap-1.5 hover:text-teal-300 transition-colors mt-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $firstItem->user->phone ?? 'Call' }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- 🌟 Items List --}}
                    <div class="p-6 md:p-8 space-y-4 flex-grow bg-slate-900/20 relative z-10">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Manifest Payload</p>
                        @foreach($items as $item)
                            <div class="flex justify-between items-center bg-slate-950 p-4 rounded-[1.25rem] border border-slate-800 shadow-inner">
                                <div class="flex items-center gap-4">
                                    <span class="w-9 h-9 rounded-lg bg-teal-500/10 flex items-center justify-center text-[10px] font-black text-teal-400 border border-teal-500/20 shadow-sm shrink-0">{{ $item->quantity }}x</span>
                                    <span class="text-sm font-bold text-slate-300">{{ $item->supply->name }}</span>
                                </div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ number_format($item->total_price, 2) }} JOD</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- 🌟 Status Update Controls --}}
                    <div class="p-6 md:p-8 bg-slate-950 border-t border-slate-800 relative z-10">
                        @if(!$isDelivered)
                            <form action="{{ route('supply.driver.update_status', $invoiceId) }}" method="POST" class="flex flex-col gap-5">
                                @csrf @method('PATCH')

                                <div class="relative group">
                                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Execution Protocol</p>
                                    <select name="status" class="w-full rounded-[1.25rem] px-5 py-4 text-[11px] font-black uppercase tracking-widest dark-select cursor-pointer shadow-inner">
                                        <option value="waiting_driver" {{ $firstItem->status == 'waiting_driver' ? 'selected' : '' }}>Picking Up Order</option>
                                        <option value="in_way" {{ $firstItem->status == 'in_way' ? 'selected' : '' }}>On My Way to Farm</option>
                                        <option value="delivered" {{ $firstItem->status == 'delivered' ? 'selected' : '' }}>Package Delivered</option>
                                    </select>
                                </div>

                                <button type="submit" class="w-full py-4.5 bg-gradient-to-r from-teal-600 to-emerald-500 hover:to-emerald-400 text-slate-950 text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl transition-all shadow-[0_10px_20px_rgba(20,184,166,0.2)] active:scale-[0.98]">
                                    Update Progression
                                </button>
                            </form>
                        @else
                            <div class="py-4 text-center">
                                <p class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black text-emerald-400 uppercase tracking-widest bg-emerald-500/10 border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.15)]">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    Delivery Successfully Logged
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
