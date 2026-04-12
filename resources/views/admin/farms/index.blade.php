@extends('layouts.admin')

@section('title', 'Manage Farm Directory')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .fade-in-up-stagger { animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }

    /* God Mode Interactive Row */
    .farm-row { transition: all 0.4s ease; border: 1px solid rgba(30, 41, 59, 0.8); }
    .farm-row:hover { background: rgba(30, 41, 59, 0.8); transform: translateY(-4px); border-color: rgba(99, 102, 241, 0.4); box-shadow: 0 20px 40px -10px rgba(0,0,0,0.6); z-index: 10; position: relative; }

    /* Custom Pagination Styling */
    .custom-pagination nav { display: flex; align-items: center; gap: 0.5rem; justify-content: center; }
    .custom-pagination .page-item .page-link { background-color: #0f172a; border: none; color: #64748b; font-size: 11px; font-weight: 900; padding: 0.75rem 1.25rem; border-radius: 0.75rem; transition: all 0.3s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.3); }
    .custom-pagination .page-item:not(.active):not(.disabled) .page-link:hover { background-color: #6366f1; color: white; }
    .custom-pagination .page-item.active .page-link { background-color: #6366f1; color: white; box-shadow: 0 0 20px rgba(99, 102, 241, 0.4); }
    .custom-pagination .page-item.disabled .page-link { background-color: transparent; opacity: 0.3; color: #334155; cursor: not-allowed; box-shadow: none; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto py-8 space-y-8 pb-24 animate-god-in">

    {{-- 🌟 1. Header Section (Command Center Style) --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 md:p-12 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl relative overflow-hidden fade-in-up transition-all hover:border-indigo-500/30">
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-500/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="absolute right-0 top-0 w-64 h-64 bg-cyan-500/5 blur-[80px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex items-center gap-6">
            <div class="w-16 h-16 rounded-[1.5rem] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shadow-[0_0_20px_rgba(99,102,241,0.2)] shrink-0 hidden sm:flex">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 shadow-inner">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span> Global Directory
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2 leading-none">Manage <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-cyan-400">Farms</span></h1>
                <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px] mt-3">Audit, verify, and govern all luxury farm assets in the ecosystem.</p>
            </div>
        </div>

        <div class="flex items-center gap-4 relative z-10 w-full md:w-auto mt-6 md:mt-0 justify-center">
            <a href="{{ route('admin.farms.create') }}" class="w-full md:w-auto px-8 py-5 bg-gradient-to-r from-indigo-600 to-cyan-500 hover:to-cyan-400 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl transition-all shadow-[0_10px_30px_rgba(99,102,241,0.3)] flex items-center justify-center gap-3 transform active:scale-95 group">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Deploy Asset
            </a>
        </div>
    </div>

    {{-- 🌟 2. Flash Notifications (God Mode Toast) --}}
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" class="fixed top-24 right-5 z-[150] flex flex-col gap-4 pointer-events-none">
        @if(session('success'))
            <div x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10" class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-emerald-500/40 rounded-2xl shadow-[0_20px_50px_rgba(16,185,129,0.2)] p-5 flex items-start gap-4 w-96 relative overflow-hidden" x-cloak>
                <div class="absolute bottom-0 left-0 h-1 bg-emerald-500 w-full animate-[progress-shrink_6s_linear_forwards]"></div>
                <div class="bg-emerald-500/20 p-2.5 rounded-xl text-emerald-400 shrink-0 border border-emerald-500/30 shadow-inner"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                <div class="flex-1 mt-0.5"><h4 class="font-black text-white text-[11px] uppercase tracking-[0.2em]">Execution Success</h4><p class="text-slate-400 text-xs mt-1.5 font-medium leading-relaxed">{{ session('success') }}</p></div>
                <button @click="show = false" class="text-slate-600 hover:text-white transition-colors p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif
    </div>
    <style>@keyframes progress-shrink { from { width: 100%; } to { width: 0%; } }</style>

    {{-- 🌟 3. Farms Directory List --}}
    <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 overflow-hidden backdrop-blur-2xl shadow-2xl fade-in-up" style="animation-delay: 0.2s;">
        <div class="p-8 md:p-10 border-b border-slate-800 flex justify-between items-center bg-slate-950/50">
            <h2 class="text-sm font-black text-white uppercase tracking-[0.2em] flex items-center gap-3">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Asset Registry
            </h2>
            <span class="bg-slate-800 text-slate-400 py-2 px-5 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] border border-slate-700 shadow-inner">
                {{ $farms->total() }} Entries
            </span>
        </div>

        <div class="p-6 md:p-8 space-y-4 bg-slate-900/30">
            @forelse($farms as $index => $farm)
                <div class="farm-row bg-slate-950/60 rounded-[2rem] p-5 flex flex-col xl:flex-row items-center justify-between gap-6 backdrop-blur-md fade-in-up-stagger" style="animation-delay: {{ (float)$index * 0.05 }}s;">

                    {{-- Farm Image & Basic Info --}}
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 w-full xl:w-2/5">
                        <div class="relative h-28 w-40 rounded-[1.5rem] overflow-hidden border-2 border-slate-800 flex-shrink-0 shadow-lg group-hover:border-indigo-500/50 transition-colors">
                            <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : asset('backgrounds/home.JPG') }}" class="h-full w-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent"></div>
                            <span class="absolute bottom-2 left-2 bg-slate-950/80 backdrop-blur-md px-2.5 py-1 rounded-lg text-[9px] font-black text-white uppercase tracking-[0.2em] border border-slate-700 shadow-sm">ID #{{ str_pad($farm->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="text-center sm:text-left mt-2 sm:mt-0">
                            <p class="font-black text-white text-xl hover:text-indigo-400 transition-colors leading-tight mb-2 truncate max-w-[250px]">{{ $farm->name }}</p>
                            <div class="flex items-center justify-center sm:justify-start gap-2 text-slate-400 text-[10px] font-bold uppercase tracking-widest bg-slate-900 px-3 py-1.5 rounded-lg border border-slate-800 inline-flex shadow-inner">
                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" /></svg>
                                {{ Str::limit($farm->location ?? $farm->governorate, 20) }}
                            </div>
                        </div>
                    </div>

                    {{-- Stats & Status --}}
                    <div class="flex flex-wrap items-center gap-6 w-full xl:w-2/5 justify-center border-y xl:border-y-0 border-slate-800 py-6 xl:py-0">

                        {{-- Status --}}
                        <div class="flex flex-col items-center justify-center min-w-[100px]">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] mb-2">Status</p>
                            @if($farm->is_approved)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-500/10 text-emerald-400 text-[9px] font-black uppercase tracking-widest border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Approved
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 text-amber-500 text-[9px] font-black uppercase tracking-widest border border-amber-500/30 shadow-[0_0_15px_rgba(245,158,11,0.15)]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                </span>
                            @endif
                        </div>

                        <div class="hidden sm:block w-px h-10 bg-slate-800"></div>

                        {{-- Owner --}}
                        <div class="flex flex-col items-center justify-center min-w-[120px]">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] mb-2">Owner</p>
                            <p class="text-xs font-black text-white truncate max-w-[120px]">{{ $farm->owner->name ?? 'Unknown' }}</p>
                            <p class="text-[9px] font-bold text-slate-500 font-mono mt-1">{{ substr($farm->owner->phone ?? 'N/A', -6) }}</p>
                        </div>

                        <div class="hidden sm:block w-px h-10 bg-slate-800"></div>

                        {{-- Rating --}}
                        <div class="flex flex-col items-center justify-center min-w-[80px]">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] mb-2">Rating</p>
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-900 text-white text-[10px] font-black tracking-widest border border-slate-700 shadow-inner">
                                <svg class="w-3.5 h-3.5 text-yellow-500 drop-shadow-[0_0_5px_rgba(234,179,8,0.8)]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ number_format($farm->average_rating ?? $farm->rating ?? 0, 1) }}
                            </span>
                        </div>

                    </div>

                    {{-- Actions Matrix --}}
                    <div class="w-full xl:w-1/5 flex items-center justify-center xl:justify-end gap-3">
                        @if(!$farm->is_approved)
                            <form action="{{ route('admin.farms.approve', $farm->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="p-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-[1rem] transition-all shadow-[0_0_20px_rgba(16,185,129,0.3)] active:scale-95 flex items-center justify-center" title="Approve Farm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.farms.edit', $farm->id) }}" class="p-4 bg-slate-900 hover:bg-indigo-600 text-slate-400 hover:text-white rounded-[1rem] border border-slate-700 hover:border-indigo-500 transition-all shadow-inner active:scale-95 flex items-center justify-center" title="Edit Configuration">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('admin.farms.destroy', $farm->id) }}" method="POST" onsubmit="return confirm('WARNING: Archive this asset permanently? This cannot be undone.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-4 bg-slate-900 hover:bg-rose-600 text-slate-400 hover:text-white rounded-[1rem] border border-slate-700 hover:border-rose-500 transition-all shadow-inner active:scale-95 flex items-center justify-center" title="Purge Asset">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-32 text-center flex flex-col items-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-[2.5rem] bg-slate-950 mb-6 border border-slate-800 shadow-inner">
                        <svg class="h-12 w-12 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h3 class="text-3xl font-black text-white mb-2 tracking-tight">No Assets Deployed</h3>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] max-w-md mx-auto leading-relaxed">The ecosystem registry is currently empty. Initialize the first node.</p>
                </div>
            @endforelse
        </div>

        @if(method_exists($farms, 'hasPages') && $farms->hasPages())
            <div class="p-8 border-t border-slate-800 bg-slate-950/50 flex flex-col md:flex-row items-center justify-between gap-6 shrink-0">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">
                    Displaying <span class="text-indigo-400">{{ $farms->count() }}</span> of <span class="text-white">{{ $farms->total() }}</span> properties
                </p>
                <div class="custom-pagination">
                    {{ $farms->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
