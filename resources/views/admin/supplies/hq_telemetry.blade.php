@extends('layouts.supply')

@section('title', 'Logistics Telemetry')

@section('content')
<style>
    /* Sleek Horizontal Scrollbar for Filters */
    .filter-scroll::-webkit-scrollbar { height: 4px; }
    .filter-scroll::-webkit-scrollbar-track { background: transparent; }
    .filter-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 4px; }
    .filter-scroll::-webkit-scrollbar-thumb:hover { background: #3b82f6; }
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-8 pb-24 mt-8 animate-god-in">

    {{-- Session Alerts --}}
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl mb-6 shadow-[0_0_15px_rgba(16,185,129,0.1)] text-sm font-bold fade-in-up">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-xl mb-6 shadow-[0_0_15px_rgba(244,63,94,0.1)] text-sm font-bold fade-in-up">
            {{ session('error') }}
        </div>
    @endif

    {{-- Telemetry Header & Branch Selector --}}
    <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2rem] p-6 border border-slate-800 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden fade-in-up">
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-cyan-500/5 rounded-full blur-[60px]"></div>

        <div class="flex items-center gap-4 relative z-10 w-full md:w-auto">
            <div class="p-3 bg-cyan-500/10 rounded-xl border border-cyan-500/20 text-cyan-400 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <h2 class="text-2xl font-black text-white tracking-tight">Node Telemetry</h2>
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mt-0.5">Real-time load balancing</p>
            </div>
        </div>

        <div class="w-full md:w-auto relative z-10">
            <form action="{{ route('supplies.hq.telemetry') }}" method="GET" class="flex gap-2">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <select name="branch_id" class="bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none w-full md:w-64 appearance-none cursor-pointer">
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($selectedBranch && $selectedBranch->id == $branch->id) ? 'selected' : '' }}>
                            {{ $branch->governorate ?? 'Unknown' }} - {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-[0_0_15px_rgba(79,70,229,0.3)] transition-colors font-black text-xs uppercase tracking-widest active:scale-95">
                    Load
                </button>
            </form>
        </div>
    </div>

    {{-- Category Filter Bar --}}
    <div class="flex overflow-x-auto filter-scroll pb-2 gap-2 fade-in-up" style="animation-delay: 0.1s;">
        @php
            $categories = ['لحوم ومشاوي', 'خضار وفواكه', 'مقبلات وسلطات', 'أدوات ومعدات الشواء', 'تسالي وحلويات', 'مشروبات وثلج', 'مستلزمات السفرة والنظافة', 'ألعاب وترفيه'];
        @endphp

        <a href="{{ route('supplies.hq.telemetry', ['branch_id' => request('branch_id')]) }}"
           class="px-5 py-2.5 rounded-xl whitespace-nowrap text-sm font-black transition-all border shadow-sm {{ !request('category') ? 'bg-indigo-600 text-white border-indigo-500 shadow-[0_0_10px_rgba(79,70,229,0.3)]' : 'bg-slate-900 border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800' }}">
            الكل (All)
        </a>

        @foreach($categories as $cat)
            <a href="{{ route('supplies.hq.telemetry', ['branch_id' => request('branch_id'), 'category' => $cat]) }}"
               class="px-5 py-2.5 rounded-xl whitespace-nowrap text-sm font-black transition-all border shadow-sm {{ request('category') == $cat ? 'bg-cyan-600 text-white border-cyan-500 shadow-[0_0_10px_rgba(8,145,178,0.3)]' : 'bg-slate-900 border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    {{-- Inventory Matrix --}}
    <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl p-8 fade-in-up" style="animation-delay: 0.2s;">
        @if(!$selectedBranch)
            <div class="py-20 text-center flex flex-col items-center">
                <div class="w-20 h-20 bg-slate-950 rounded-[2rem] flex items-center justify-center mb-6 border border-slate-800 shadow-inner">
                    <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m-14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-black text-white tracking-tight">No Branch Selected</h3>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-2">Please select a regional branch to view telemetry.</p>
            </div>
        @else
            <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-800">
                <div>
                    <h3 class="text-2xl font-black text-white flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.8)] animate-pulse"></span>
                        Node: <span class="text-indigo-400">{{ $selectedBranch->governorate ?? 'Unknown' }}</span>
                    </h3>
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1">Live Stock Overview (Sorted Lowest to Highest)</p>
                </div>
            </div>

            @if($inventory->isEmpty())
                <div class="py-20 text-center flex flex-col items-center">
                    <div class="w-20 h-20 bg-slate-950 rounded-[2rem] flex items-center justify-center mb-6 border border-slate-800 shadow-inner">
                        <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-white tracking-tight">No Products Found</h3>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-2">Adjust filters or deploy new global products.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($inventory as $item)
                        @php
                            $isLowStock = ($item->stock === null || $item->stock <= 5);
                        @endphp
                        <div class="bg-slate-950 border {{ $isLowStock ? 'border-rose-500/40 hover:border-rose-500' : 'border-slate-800 hover:border-slate-700' }} rounded-[2rem] p-6 shadow-xl relative overflow-hidden flex flex-col justify-between group transition-all hover:-translate-y-1">

                            @if($isLowStock)
                                <div class="absolute top-0 right-0 w-16 h-16 bg-rose-500/10 rounded-bl-full pointer-events-none"></div>
                                <div class="absolute top-4 right-4 w-2 h-2 rounded-full bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.8)] animate-pulse"></div>
                            @else
                                <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/5 rounded-bl-full pointer-events-none"></div>
                            @endif

                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="px-2.5 py-1 bg-slate-900 border border-slate-800 rounded text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $item->category ?: 'Uncategorized' }}</span>
                                </div>
                                <div class="flex gap-4 items-center mb-6">
                                    <div class="w-12 h-12 rounded-xl bg-slate-900 border border-slate-800 overflow-hidden shrink-0">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex justify-center items-center text-[8px] font-black text-slate-600">N/A</div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-white line-clamp-2 leading-tight" title="{{ $item->name }}">{{ $item->name }}</h4>
                                        <div class="flex items-baseline gap-1 mt-1">
                                            <span class="text-xl font-black {{ $isLowStock ? 'text-rose-500' : 'text-emerald-400' }} tracking-tighter">{{ $item->stock ?? 0 }}</span>
                                            <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest">In Node</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Replenishment Form --}}
                            <div class="mt-auto border-t border-slate-800/50 pt-5">
                                <form action="{{ route('supplies.hq.replenish') }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="hidden" name="supply_id" value="{{ $item->id }}">
                                    <input type="hidden" name="company_id" value="{{ $selectedBranch->id }}">

                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500 font-black text-[9px] uppercase tracking-widest">+</span>
                                        </div>
                                        <input type="number" name="qty" min="1" required placeholder="Qty" class="w-full bg-slate-900 border {{ $isLowStock ? 'border-rose-900/50 focus:border-rose-500' : 'border-slate-700/50 focus:border-indigo-500' }} rounded-xl py-2.5 pl-8 pr-3 text-xs text-white font-bold focus:ring-2 outline-none text-center transition-colors">
                                    </div>
                                    <button type="submit" class="px-4 py-2.5 {{ $isLowStock ? 'bg-rose-600 hover:bg-rose-500 shadow-[0_0_15px_rgba(244,63,94,0.3)]' : 'bg-indigo-600 hover:bg-indigo-500 shadow-[0_0_15px_rgba(79,70,229,0.3)]' }} text-white rounded-xl transition-all font-black text-[9px] uppercase tracking-widest shrink-0 active:scale-95">
                                        Push
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if(method_exists($inventory, 'hasPages') && $inventory->hasPages())
                    <div class="mt-10 border-t border-slate-800 pt-8">
                        {{ $inventory->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        @endif
    </div>
</div>
@endsection
