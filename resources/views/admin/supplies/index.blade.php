@extends('layouts.admin')

@section('title', 'Global Inventory')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    .supply-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .supply-card:hover { transform: translateY(-8px); z-index: 10; box-shadow: 0 25px 50px -12px rgba(132, 204, 34, 0.15); border-color: rgba(132, 204, 34, 0.3); }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto py-8 space-y-8 pb-24">

    {{-- 🌟 Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 rounded-[2.5rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden fade-in-up">
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-lime-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-lime-500/10 border border-lime-500/30 mb-4 shadow-[0_0_15px_rgba(132,204,22,0.2)]">
                <span class="w-2 h-2 rounded-full bg-lime-500 animate-pulse"></span>
                <span class="text-[10px] font-black tracking-widest text-lime-400 uppercase">E-Commerce Core</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2">Global <span class="text-transparent bg-clip-text bg-gradient-to-r from-lime-400 to-emerald-400">Inventory</span></h1>
            <p class="text-slate-400 font-medium text-sm">Manage marketplace supplies, track stock levels, and audit pricing architecture.</p>
        </div>
        <div class="flex items-center gap-4 relative z-10">
            <a href="{{ route('admin.supplies.create') }}" class="px-8 py-4 bg-gradient-to-tr from-lime-600 to-lime-500 hover:from-lime-500 hover:to-lime-400 text-slate-950 font-black text-xs uppercase tracking-widest rounded-2xl transition-all shadow-[0_10px_20px_rgba(132,204,22,0.3)] hover:shadow-[0_15px_30px_rgba(132,204,22,0.4)] flex items-center gap-2 group transform active:scale-95">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Deploy New Item
            </a>
        </div>
    </div>

    {{-- 🌟 Inventory Grid --}}
    <div class="fade-in-up" style="animation-delay: 0.2s;">
        @if($supplies->isEmpty())
            <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 p-32 flex flex-col items-center justify-center text-center shadow-2xl backdrop-blur-md">
                <div class="h-24 w-24 rounded-[2rem] bg-slate-950 flex items-center justify-center text-slate-700 border border-slate-800 shadow-inner mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <h3 class="text-3xl font-black text-white mb-2 tracking-tight">Inventory Depleted</h3>
                <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">No active marketplace supplies found in the system cache.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($supplies as $index => $supply)
                    <div class="supply-card bg-slate-900/60 rounded-[2.5rem] border border-slate-800 overflow-hidden backdrop-blur-xl flex flex-col h-full fade-in-up-stagger group" style="animation-delay: {{ $index * 0.05 }}s;">

                        {{-- Product Image & Overlays --}}
                        <div class="relative h-60 overflow-hidden bg-slate-950 p-2 shrink-0">
                            <div class="relative h-full w-full rounded-[1.5rem] overflow-hidden border border-slate-800/50 shadow-inner">
                                <img src="{{ $supply->image ? asset('storage/supplies/' . $supply->image) :  asset('storage/supplies/' . $supply->image) }}"
                                     class="h-full w-full object-cover transform scale-105 group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent opacity-90"></div>

                                {{-- Smart Stock Badge --}}
                                <div class="absolute top-4 left-4 z-10">
                                    @if($supply->stock == 0)
                                        <span class="px-3 py-1.5 bg-rose-500/90 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-widest rounded-lg shadow-[0_0_15px_rgba(244,63,94,0.6)] border border-rose-400">Deficit</span>
                                    @elseif($supply->stock < 10)
                                        <span class="px-3 py-1.5 bg-amber-500/90 backdrop-blur-md text-slate-900 text-[9px] font-black uppercase tracking-widest rounded-lg shadow-[0_0_15px_rgba(245,158,11,0.6)] border border-amber-400 animate-pulse">Low: {{ $supply->stock }}</span>
                                    @else
                                        <span class="px-3 py-1.5 bg-lime-500/90 backdrop-blur-md text-slate-900 text-[9px] font-black uppercase tracking-widest rounded-lg shadow-[0_0_15px_rgba(132,204,22,0.4)] border border-lime-400">Stock: {{ $supply->stock }}</span>
                                    @endif
                                </div>

                                {{-- Price Overlay --}}
                                <div class="absolute bottom-4 right-4 bg-slate-950/80 backdrop-blur-md px-4 py-2 rounded-xl border border-slate-700 shadow-xl">
                                    <span class="text-xl font-black text-lime-400 tracking-tighter">{{ number_format($supply->price, 2) }} <span class="text-[9px] text-slate-500 uppercase tracking-widest ml-0.5">JOD</span></span>
                                </div>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="px-6 py-6 flex flex-col flex-1">
                            <h3 class="text-xl font-black text-white mb-2 line-clamp-1 group-hover:text-lime-400 transition-colors tracking-tight">{{ $supply->name }}</h3>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-4 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                {{ $supply->category ?? 'General' }}
                            </p>
                            <p class="text-xs text-slate-400 font-medium leading-relaxed line-clamp-2 italic h-10 mb-6">"{{ $supply->description }}"</p>

                            {{-- Action Suite --}}
                            <div class="mt-auto flex items-center gap-3 pt-5 border-t border-slate-800/80">
                                <a href="{{ route('admin.supplies.edit', $supply->id) }}" class="flex-1 py-3.5 bg-slate-950 hover:bg-slate-800 text-slate-300 hover:text-lime-400 font-black text-[10px] uppercase tracking-widest rounded-xl border border-slate-800 hover:border-lime-500/30 transition-all text-center flex items-center justify-center gap-2 active:scale-95 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    Adjust
                                </a>
                                <form action="{{ route('admin.supplies.destroy', $supply->id) }}" method="POST" class="flex-shrink-0" onsubmit="return confirm('Decommission this supply?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-3.5 bg-slate-950 hover:bg-rose-600 text-slate-500 hover:text-white rounded-xl border border-slate-800 hover:border-rose-500 transition-all shadow-xl active:scale-95" title="Decommission Item">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($supplies->hasPages())
                <div class="mt-12 flex justify-center text-white pb-8">
                    <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 px-6 py-3 rounded-full shadow-2xl custom-pagination">
                        {{ $supplies->links() }}
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

<style>
    /* Custom Pagination Styling for Dark Theme */
    .custom-pagination nav { @apply flex items-center gap-2; }
    .custom-pagination .page-link { @apply bg-slate-950 border border-slate-800 text-slate-400 text-[10px] font-black px-4 py-2 rounded-lg transition-all hover:bg-slate-800 hover:text-lime-400 hover:border-lime-500/30; }
    .custom-pagination .active .page-link { @apply bg-lime-600 border-lime-500 text-slate-950 shadow-[0_0_15px_rgba(132,204,22,0.3)]; }
</style>
@endsection
