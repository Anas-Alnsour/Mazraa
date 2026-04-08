@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-[fade-in_0.8s_ease-out]">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-800/50 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter mb-2">Global <span class="text-lime-400">Inventory</span></h1>
            <p class="text-slate-400 font-medium">Manage marketplace supplies, track stock levels, and audit prices.</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.supplies.create') }}" class="px-8 py-4 bg-lime-600 hover:bg-lime-500 text-white font-black rounded-2xl transition-all shadow-xl shadow-lime-900/20 flex items-center gap-2 group transform active:scale-95">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Deploy New Supply
            </a>
        </div>
    </div>

    {{-- Supplies Grid (Upgraded to consistent God Mode aesthetic) --}}
    @if($supplies->isEmpty())
        <div class="bg-slate-800/30 rounded-[2.5rem] border border-slate-700/50 p-32 flex flex-col items-center justify-center text-center shadow-2xl backdrop-blur-md">
            <div class="h-24 w-24 rounded-full bg-slate-800 flex items-center justify-center text-slate-700 border border-slate-700 shadow-inner mb-6">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 12 12"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
            <h3 class="text-xl font-black text-slate-300">Inventory Depleted</h3>
            <p class="text-slate-500 max-w-xs font-medium mt-2">No active marketplace supplies found in the system cache.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($supplies as $supply)
                <div class="group bg-slate-800/50 rounded-[2rem] border border-slate-700/50 overflow-hidden backdrop-blur-xl hover:bg-slate-700/50 transition-all duration-500 hover:-translate-y-2 shadow-xl shadow-black/20">
                    {{-- Product Image --}}
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ $supply->image ? Storage::url($supply->image) : 'https://via.placeholder.com/800x600?text=Premium+Supply' }}" 
                             class="h-full w-full object-cover transform scale-105 group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-60"></div>
                        
                        {{-- Stock Badge --}}
                        <div class="absolute top-4 left-4">
                            @if($supply->stock == 0)
                                <span class="px-3 py-1 bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-lg">Deficit</span>
                            @elseif($supply->stock < 10)
                                <span class="px-3 py-1 bg-amber-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-lg animate-pulse">Low Stock: {{ $supply->stock }}</span>
                            @else
                                <span class="px-3 py-1 bg-lime-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-lg">Available: {{ $supply->stock }}</span>
                            @endif
                        </div>

                        {{-- Price Overly --}}
                        <div class="absolute bottom-4 right-4 bg-slate-900/90 backdrop-blur px-4 py-2 rounded-xl border border-slate-700 shadow-xl">
                            <span class="text-lg font-black text-lime-400">{{ number_format($supply->price, 2) }} <span class="text-[10px] text-slate-500 uppercase tracking-widest">JOD</span></span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6">
                        <div class="mb-6">
                            <h3 class="text-lg font-black text-white mb-2 line-clamp-1 group-hover:text-lime-400 transition-colors">{{ $supply->name }}</h3>
                            <p class="text-xs text-slate-400 font-medium leading-relaxed line-clamp-2 italic">"{{ $supply->description }}"</p>
                        </div>

                        {{-- Action Suite --}}
                        <div class="flex items-center gap-3 pt-4 border-t border-slate-700/50">
                            <a href="{{ route('admin.supplies.edit', $supply->id) }}" class="flex-1 py-3 bg-slate-900 hover:bg-slate-800 text-slate-300 font-black text-[10px] uppercase tracking-widest rounded-xl border border-slate-700 transition-all text-center flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                Adjust
                            </a>
                            <form action="{{ route('admin.supplies.destroy', $supply->id) }}" method="POST" class="flex-shrink-0" onsubmit="return confirm('Decommission this supply?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-3 bg-slate-900 hover:bg-rose-600 text-slate-500 hover:text-white rounded-xl border border-slate-700 transition-all shadow-xl">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($supplies->hasPages())
            <div class="mt-12 flex justify-center pb-8 text-white">
                {{ $supplies->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
