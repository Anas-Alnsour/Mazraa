@extends('layouts.supply')

@section('title', 'Global Inventory Core')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }

    .table-scroll::-webkit-scrollbar { height: 8px; width: 6px; }
    .table-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb:hover { background: #10b981; }

    /* Custom Pagination Styling */
    .custom-pagination nav { display: flex; align-items: center; gap: 0.5rem; justify-content: center; }
    .custom-pagination .page-item .page-link { background-color: #0f172a; border: none; color: #64748b; font-size: 11px; font-weight: 900; padding: 0.75rem 1.25rem; border-radius: 0.75rem; transition: all 0.3s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.3); }
    .custom-pagination .page-item:not(.active):not(.disabled) .page-link:hover { background-color: #10b981; color: white; }
    .custom-pagination .page-item.active .page-link { background-color: #10b981; color: white; box-shadow: 0 0 20px rgba(16, 185, 129, 0.4); }
    .custom-pagination .page-item.disabled .page-link { background-color: transparent; opacity: 0.3; color: #334155; cursor: not-allowed; box-shadow: none; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-10 pb-24 pt-4 animate-god-in">

    {{-- 🌟 1. Header Section (Ultra-Modern) --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 bg-slate-900/80 p-8 md:p-12 rounded-[3rem] border border-slate-800 shadow-2xl relative overflow-hidden backdrop-blur-2xl transition-all hover:border-emerald-500/30 fade-in-up">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
            <div class="w-16 h-16 rounded-[1.5rem] bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.2)] shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 shadow-inner mx-auto md:mx-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Core Inventory
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-1">Inventory <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">Management</span></h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-2">Manage products, stock flow, and pricing matrices.</p>
            </div>
        </div>

        <div class="relative z-10 w-full md:w-auto mt-6 md:mt-0 flex justify-center md:justify-end">
            <a href="{{ route('supplies.items.create') }}" class="w-full md:w-auto px-8 py-5 bg-gradient-to-tr from-emerald-600 to-emerald-400 hover:to-emerald-300 text-slate-950 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_30px_rgba(16,185,129,0.3)] transform hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Deploy Asset
            </a>
        </div>
    </div>

    {{-- 🌟 2. Floating Toast Notifications --}}
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" class="fixed top-24 right-5 z-[150] flex flex-col gap-4 pointer-events-none">
        @if(session('success'))
            <div x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10" class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-emerald-500/40 rounded-2xl shadow-[0_20px_50px_rgba(16,185,129,0.2)] p-5 flex items-start gap-4 w-96 relative overflow-hidden" x-cloak>
                <div class="absolute bottom-0 left-0 h-1 bg-emerald-500 w-full animate-[progress-shrink_6s_linear_forwards]"></div>
                <div class="bg-emerald-500/20 p-2.5 rounded-xl text-emerald-400 shrink-0 border border-emerald-500/30 shadow-inner"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                <div class="flex-1 mt-0.5"><h4 class="font-black text-white text-[11px] uppercase tracking-[0.2em]">Execution Success</h4><p class="text-slate-400 text-xs mt-1.5 font-medium leading-relaxed">{{ session('success') }}</p></div>
                <button @click="show = false" class="text-slate-600 hover:text-white transition-colors p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif
        @if(session('error'))
            <div x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10" class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-rose-500/40 rounded-[2rem] shadow-[0_20px_50px_rgba(244,63,94,0.2)] p-5 flex items-start gap-4 w-96 relative overflow-hidden" x-cloak>
                <div class="absolute bottom-0 left-0 h-1 bg-rose-500 w-full animate-[progress-shrink_6s_linear_forwards]"></div>
                <div class="bg-rose-500/20 p-3 rounded-xl text-rose-400 shrink-0 border border-rose-500/30 shadow-inner"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                <div class="flex-1 mt-1"><h4 class="font-black text-white text-xs uppercase tracking-[0.2em]">System Alert</h4><p class="text-slate-400 text-[11px] mt-1.5 font-medium leading-relaxed">{{ session('error') }}</p></div>
                <button @click="show = false" class="text-slate-600 hover:text-white transition-colors p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif
    </div>
    <style>@keyframes progress-shrink { from { width: 100%; } to { width: 0%; } }</style>

    {{-- 🌟 3. Inventory Table --}}
    <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 overflow-hidden backdrop-blur-2xl shadow-2xl fade-in-up" style="animation-delay: 0.15s;">
        @if($supplies->count() > 0)
            <div class="w-full overflow-x-auto table-scroll bg-slate-900/20">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead class="bg-slate-950/80 border-b border-slate-800">
                        <tr>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Product Identity</th>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Asset Category</th>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap text-right">Net Value</th>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap text-center">Liquidity / Stock</th>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap text-right">Actions Matrix</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/40">
                        @foreach($supplies as $item)
                            <tr class="hover:bg-white/5 transition-colors group">

                                {{-- Product Info --}}
                                <td class="px-10 py-6 whitespace-nowrap">
                                    <div class="flex items-center gap-5">
                                        <div class="h-16 w-16 rounded-[1.2rem] bg-slate-950 border border-slate-700 overflow-hidden shrink-0 flex items-center justify-center shadow-inner group-hover:border-emerald-500/50 transition-colors relative">
                                            @if($item->image)
                                                <img src="{{ asset('storage/supplies/' . $item->image) }}" alt="{{ $item->name }}" class="h-full w-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
                                            @else
                                                <svg class="h-8 w-8 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @endif
                                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-50"></div>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-base font-black text-white group-hover:text-emerald-400 transition-colors truncate tracking-tight mb-1">{{ $item->name }}</p>
                                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest truncate max-w-[200px]">{{ $item->description ?: 'No metadata available' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Category --}}
                                <td class="px-10 py-6 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-950 text-slate-400 text-[9px] font-black uppercase tracking-widest border border-slate-800 shadow-inner group-hover:border-emerald-500/30 transition-all">
                                        <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        {{ $item->category }}
                                    </span>
                                </td>

                                {{-- Price --}}
                                <td class="px-10 py-6 whitespace-nowrap text-right">
                                    <p class="text-2xl font-black text-emerald-400 tracking-tighter">{{ number_format($item->price, 2) }} <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest ml-0.5">JOD</span></p>
                                </td>

                                {{-- Stock Status --}}
                                <td class="px-10 py-6 whitespace-nowrap text-center">
                                    @if($item->stock > 10)
                                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[9px] font-black bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-widest shadow-[0_0_15px_rgba(16,185,129,0.1)]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Optimal ({{ $item->stock }})
                                        </span>
                                    @elseif($item->stock > 0)
                                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[9px] font-black bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase tracking-widest shadow-[0_0_15px_rgba(245,158,11,0.1)]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Low Alert ({{ $item->stock }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[9px] font-black bg-rose-500/10 text-rose-400 border border-rose-500/20 uppercase tracking-widest shadow-[0_0_15px_rgba(244,63,94,0.1)]">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            Deficit
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions Matrix --}}
                                <td class="px-10 py-6 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-4 group-hover:translate-x-0">
                                        <a href="{{ route('supplies.items.edit', $item->id) }}" class="p-3.5 bg-slate-950 text-slate-400 border border-slate-700 hover:border-emerald-500 hover:text-emerald-400 rounded-xl transition-all shadow-inner active:scale-95" title="Configure Asset">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('supplies.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('WARNING: Purge this product permanently?');" class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-3.5 bg-slate-950 text-rose-500 border border-slate-700 hover:border-rose-500 hover:bg-rose-600 hover:text-white rounded-xl transition-all active:scale-95 shadow-inner" title="Purge Asset">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(method_exists($supplies, 'hasPages') && $supplies->hasPages())
                <div class="px-10 py-10 border-t border-slate-800 bg-slate-950/40 flex flex-col sm:flex-row items-center justify-between gap-6 shrink-0">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em]">
                        Displaying <span class="text-emerald-400">{{ $supplies->count() }}</span> of <span class="text-white">{{ $supplies->total() }}</span> assets
                    </p>
                    <div class="custom-pagination">
                        {{ $supplies->links() }}
                    </div>
                </div>
            @endif

        @else
            {{-- 🌟 Empty State (Zero Data) --}}
            <div class="py-32 text-center bg-slate-900/40 border-dashed border-0 border-t border-slate-800 flex flex-col items-center shadow-inner relative overflow-hidden">
                <div class="w-24 h-24 bg-slate-950 rounded-[2rem] flex items-center justify-center mb-6 shadow-inner border border-slate-800 relative z-10">
                    <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-white mb-2 tracking-tight">Inventory Null</h3>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] max-w-sm mx-auto mb-8 leading-relaxed">No products detected in your database sector. Deploy your first asset to populate the marketplace.</p>
                <a href="{{ route('supplies.items.create') }}" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-slate-950 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(16,185,129,0.3)] active:scale-95 transition-all flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    Initialize First Item
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
