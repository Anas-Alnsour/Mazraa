@extends('layouts.admin')

@section('title', 'Manage Farm Directory')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    .farm-row { transition: all 0.4s ease; }
    .farm-row:hover { background: rgba(30, 41, 59, 0.6); transform: scale(1.01); border-color: rgba(16, 185, 129, 0.2); box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5); z-index: 10; position: relative; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto py-8 space-y-8 pb-24">

    {{-- 🌟 Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 rounded-[2.5rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden fade-in-up">
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2">Manage <span class="text-emerald-400">Farms</span></h1>
            <p class="text-slate-400 font-medium text-sm">Audit, verify, and govern all luxury farm assets in the ecosystem.</p>
        </div>
        <div class="flex items-center gap-4 relative z-10">
            <a href="{{ route('admin.farms.create') }}" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs uppercase tracking-widest rounded-2xl transition-all shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:shadow-[0_0_30px_rgba(16,185,129,0.5)] flex items-center gap-2 group transform active:scale-95">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Deploy Asset
            </a>
        </div>
    </div>

    {{-- 🌟 Alerts --}}
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl shadow-sm font-black uppercase tracking-widest text-xs flex items-center gap-3 fade-in-up" style="animation-delay: 0.1s;">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- 🌟 Farms Directory Grid/Table --}}
    <div class="bg-slate-900/60 rounded-[2.5rem] border border-slate-800 overflow-hidden backdrop-blur-2xl shadow-2xl fade-in-up" style="animation-delay: 0.2s;">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center bg-slate-950/50">
            <h2 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Asset Registry
            </h2>
            <span class="bg-slate-800 text-slate-400 py-1.5 px-4 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-700">{{ $farms->total() }} Entries</span>
        </div>

        <div class="p-4 space-y-2">
            @forelse($farms as $farm)
                <div class="farm-row bg-slate-950/40 border border-slate-800 rounded-[2rem] p-4 flex flex-col xl:flex-row items-center justify-between gap-6">

                    {{-- Farm Image & Basic Info --}}
                    <div class="flex items-center gap-6 w-full xl:w-2/5">
                        <div class="relative h-24 w-36 rounded-[1.5rem] overflow-hidden border-2 border-slate-800 flex-shrink-0 shadow-lg group-hover:border-emerald-500/50 transition-colors">
                            <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : asset('backgrounds/home.JPG') }}" class="h-full w-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                            <span class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-md px-2 py-0.5 rounded text-[9px] font-black text-white uppercase tracking-widest border border-white/10">ID #{{ str_pad($farm->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div>
                            <p class="font-black text-white text-xl group-hover:text-emerald-400 transition-colors leading-tight mb-1 truncate max-w-[200px]">{{ $farm->name }}</p>
                            <div class="flex items-center gap-1.5 text-slate-400 text-xs font-bold">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" /></svg>
                                {{ Str::limit($farm->location ?? $farm->governorate, 25) }}
                            </div>
                        </div>
                    </div>

                    {{-- Stats & Status --}}
                    <div class="flex items-center gap-8 w-full xl:w-2/5 justify-between xl:justify-center border-y xl:border-y-0 border-slate-800 py-4 xl:py-0">
                        <div class="text-center">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Status</p>
                            @if($farm->status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-500/10 text-amber-400 text-[10px] font-black uppercase tracking-widest border border-amber-500/20">
                                    {{ ucfirst($farm->status) }}
                                </span>
                            @endif
                        </div>
                        <div class="text-center px-6 border-x border-slate-800">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Rating</p>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-slate-900 text-slate-300 text-[10px] font-black uppercase tracking-widest border border-slate-700">
                                <svg class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ $farm->average_rating ?? $farm->rating ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="text-center">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Base Price</p>
                            <p class="text-lg font-black text-emerald-400 tracking-tighter leading-none">{{ number_format($farm->price_per_night ?? 0, 2) }} <span class="text-[9px] text-slate-500 tracking-widest">JOD</span></p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="w-full xl:w-1/5 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.farms.edit', $farm->id) }}" class="p-3 bg-slate-900 hover:bg-blue-600 text-slate-400 hover:text-white rounded-xl border border-slate-700 hover:border-blue-500 transition-all shadow-lg active:scale-95" title="Edit Asset">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </a>
                        <form action="{{ route('admin.farms.destroy', $farm->id) }}" method="POST" onsubmit="return confirm('Archive this asset permanently?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-3 bg-slate-900 hover:bg-rose-600 text-slate-400 hover:text-white rounded-xl border border-slate-700 hover:border-rose-500 transition-all shadow-lg active:scale-95" title="Archive Asset">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-24 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-[2rem] bg-slate-900 mb-6 border border-slate-800 shadow-inner">
                        <svg class="h-10 w-10 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2 tracking-tight">No Assets Deployed</h3>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest max-w-sm mx-auto leading-relaxed">The ecosystem registry is currently empty. Initialize the first node.</p>
                </div>
            @endforelse
        </div>

        @if($farms->hasPages())
            <div class="p-6 bg-slate-950/80 border-t border-slate-800 flex justify-center text-white custom-pagination">
                {{ $farms->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
