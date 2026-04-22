@extends('layouts.admin')

@section('title', 'Global Catalog Command')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .fade-in-up-stagger { animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto py-8 space-y-8 pb-24 animate-god-in">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 md:p-12 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl relative overflow-hidden fade-in-up transition-all hover:border-cyan-500/30">
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-cyan-500/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-16 h-16 rounded-[1.5rem] bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 shadow-[0_0_20px_rgba(6,182,212,0.2)] shrink-0 hidden sm:flex">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 shadow-inner">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span> Master Database
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2 leading-none">Global <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-indigo-400">Catalog</span></h1>
                <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px] mt-3">Add items once here. Regional branches will manage their own stock.</p>
            </div>
        </div>
        <div class="relative z-10">
            <a href="{{ route('admin.central-supplies.create') }}" class="px-8 py-5 bg-gradient-to-r from-cyan-600 to-indigo-600 hover:to-indigo-500 text-white font-black text-[11px] uppercase tracking-widest rounded-2xl shadow-[0_0_30px_rgba(6,182,212,0.3)] flex items-center gap-3 active:scale-95 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Publish New Item
            </a>
        </div>
    </div>

    {{-- 💡 Filter Categories --}}
    @php
        $categories = ['لحوم ومشاوي', 'خضار وفواكه', 'مقبلات وسلطات', 'أدوات ومعدات الشواء', 'تسالي وحلويات', 'مشروبات وثلج', 'مستلزمات السفرة والنظافة', 'ألعاب وترفيه'];
        $currentCategory = request('category');
    @endphp

    <div class="mb-8 fade-in-up" style="animation-delay: 0.15s;">
        <div class="bg-slate-900/80 backdrop-blur-2xl rounded-[2rem] p-3 shadow-2xl border border-slate-800">
            <div class="flex overflow-x-auto hide-scrollbar gap-2 px-1 py-1">
                <a href="{{ route('admin.central-supplies.index') }}" class="whitespace-nowrap px-6 py-3 rounded-xl font-black text-[10px] md:text-xs uppercase tracking-widest transition-all border {{ !$currentCategory ? 'bg-cyan-600 text-white border-cyan-500 shadow-[0_8px_20px_rgba(6,182,212,0.3)]' : 'bg-slate-950 text-slate-400 border-slate-800 hover:bg-slate-800 hover:text-cyan-400 shadow-sm' }}">الكل</a>
                @foreach($categories as $cat)
                    <a href="{{ route('admin.central-supplies.index', ['category' => $cat]) }}" class="whitespace-nowrap px-6 py-3 rounded-xl font-black text-[10px] md:text-xs uppercase tracking-widest transition-all border {{ $currentCategory === $cat ? 'bg-cyan-600 text-white border-cyan-500 shadow-[0_8px_20px_rgba(6,182,212,0.3)]' : 'bg-slate-950 text-slate-400 border-slate-800 hover:bg-slate-800 hover:text-cyan-400 shadow-sm' }}">{{ $cat }}</a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Grid --}}
    <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 overflow-hidden backdrop-blur-2xl shadow-2xl fade-in-up" style="animation-delay: 0.2s;">
        <div class="p-8 border-b border-slate-800 bg-slate-950/50 flex justify-between items-center">
            <h2 class="text-sm font-black text-white uppercase tracking-[0.2em] flex items-center gap-3">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Master Items List
            </h2>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            @forelse($supplies as $item)
                <div class="bg-slate-950/60 rounded-[2rem] border border-slate-800 p-5 shadow-xl hover:border-cyan-500/40 transition-colors group relative overflow-hidden flex flex-col h-full">
                    <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <div class="w-full h-40 bg-slate-900 rounded-xl mb-4 overflow-hidden border border-slate-800 relative z-10">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-700 text-xs font-black uppercase">No Image</div>
                        @endif
                    </div>
                    <div class="relative z-10 flex-1 flex flex-col">
                        <h4 class="font-black text-white text-lg truncate mb-1">{{ $item->name }}</h4>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-4">{{ $item->category }}</p>
                        <p class="text-2xl font-black text-cyan-400 tracking-tighter mb-4">{{ number_format($item->price, 2) }} <span class="text-[10px] text-slate-500 font-bold tracking-widest">JOD</span></p>

                        <div class="mt-auto flex items-center gap-2 border-t border-slate-800/50 pt-4">
                            <a href="{{ route('admin.central-supplies.edit', $item->id) }}" class="flex-1 py-3 text-center bg-slate-900 hover:bg-indigo-600 text-slate-300 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-700 hover:border-indigo-500 transition-all shadow-inner">Edit</a>
                            <form action="{{ route('admin.central-supplies.destroy', $item->id) }}" method="POST" class="w-auto" onsubmit="return confirm('WARNING: Delete this global item? It will vanish from ALL regional branches.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-4 py-3 bg-slate-900 hover:bg-rose-600 text-rose-500 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-700 hover:border-rose-500 transition-all shadow-inner" title="Delete">✕</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center flex flex-col items-center">
                    <div class="w-20 h-20 bg-slate-900 rounded-[2rem] flex items-center justify-center mb-4 shadow-inner border border-slate-800 relative z-10">
                        <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-white tracking-tight mb-2">No Products Found</h3>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">No items exist in this category yet.</p>
                </div>
            @endforelse
        </div>

        {{-- 💡 Pagination --}}
        @if(method_exists($supplies, 'hasPages') && $supplies->hasPages())
            <div class="p-6 bg-slate-950/50 border-t border-slate-800 flex justify-center text-white custom-pagination">
                {{ $supplies->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
