@extends('layouts.supply')

@section('title', 'HQ Nodes Overview')

@section('content')
<div class="max-w-7xl mx-auto space-y-10 pb-24 mt-8 animate-god-in">

    {{-- HQ Header --}}
    <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-xl overflow-hidden relative">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-[60px]"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-[60px]"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 bg-slate-950 rounded-2xl border border-slate-800 shadow-inner flex items-center justify-center text-cyan-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white tracking-tight">HQ Operations Overview</h2>
                    <p class="text-xs font-black text-slate-500 mt-1 uppercase tracking-widest text-cyan-500/50">High-Level Supply Network Health</p>
                </div>
            </div>
            
            <div class="flex gap-4">
                <div class="px-6 py-4 bg-slate-950 border border-slate-800 rounded-2xl flex flex-col items-center">
                    <span class="text-3xl font-black text-white">{{ $globalSuppliesCount }}</span>
                    <span class="text-[9px] font-black uppercase text-slate-500 tracking-widest mt-1">Catalog Size</span>
                </div>
                <div class="px-6 py-4 bg-slate-950 border border-slate-800 rounded-2xl flex flex-col items-center">
                    <span class="text-3xl font-black text-indigo-400">{{ $branches->count() }}</span>
                    <span class="text-[9px] font-black uppercase text-slate-500 tracking-widest mt-1">Active Nodes</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Regional Branches Grid --}}
    <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl overflow-hidden mt-12">
        <div class="p-8 border-b border-slate-800 bg-slate-950/40">
            <h3 class="text-xl font-black text-white tracking-tight flex items-center gap-3">
                <div class="p-2 bg-indigo-500/10 rounded-xl border border-indigo-500/20 text-indigo-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                Regional Network Architecture
            </h3>
        </div>
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($branches as $branch)
                <a href="{{ route('supplies.hq.telemetry', ['branch_id' => $branch->id]) }}" class="group block p-6 bg-slate-950 rounded-[2rem] border border-slate-800 hover:border-indigo-500/50 transition-all hover:bg-slate-900/80 shadow-inner relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-500/5 rounded-bl-full group-hover:bg-indigo-500/10 transition-colors pointer-events-none"></div>
                    <h4 class="text-xl font-black text-white group-hover:text-indigo-400 transition-colors mb-2">{{ $branch->name }}</h4>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4 text-cyan-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $branch->governorate ?? 'Unassigned' }}
                    </p>
                    <div class="mt-6 flex justify-end">
                        <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-[9px] font-black uppercase tracking-widest rounded-lg flex items-center gap-1 group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                            View Telemetry
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                        </span>
                    </div>
                </a>
            @endforeach
            @if($branches->isEmpty())
                <div class="col-span-full py-12 text-center text-slate-500 text-xs font-bold uppercase tracking-widest">No regional branches found.</div>
            @endif
        </div>
    </div>
</div>
@endsection
