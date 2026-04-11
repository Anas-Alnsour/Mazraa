@extends('layouts.supply')

@section('title', 'Operations & Dispatch Center')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }

    .table-scroll::-webkit-scrollbar { height: 8px; width: 6px; }
    .table-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb:hover { background: #3b82f6; }

    /* Select styling for Dark Mode */
    select option { background-color: #0f172a; color: #f8fafc; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-10 pb-24 animate-god-in">

    {{-- 🌟 1. Floating Toast Notifications --}}
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" class="fixed top-24 right-5 z-[150] flex flex-col gap-4 pointer-events-none">
        @if(session('success'))
            <div x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10" class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-emerald-500/40 rounded-2xl shadow-[0_20px_50px_rgba(16,185,129,0.2)] p-5 flex items-start gap-4 w-96 relative overflow-hidden" x-cloak>
                <div class="absolute bottom-0 left-0 h-1 bg-emerald-500 w-full animate-[progress-shrink_6s_linear_forwards]"></div>
                <div class="bg-emerald-500/20 p-2.5 rounded-xl text-emerald-400 shrink-0 border border-emerald-500/30 shadow-inner"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                <div class="flex-1 mt-0.5"><h4 class="font-black text-white text-[11px] uppercase tracking-[0.2em]">Dispatch Success</h4><p class="text-slate-400 text-xs mt-1.5 font-medium leading-relaxed">{{ session('success') }}</p></div>
                <button @click="show = false" class="text-slate-600 hover:text-white transition-colors p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif

        @if(session('error'))
            <div x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10" class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-rose-500/40 rounded-2xl shadow-[0_20px_50px_rgba(244,63,94,0.2)] p-5 flex items-start gap-4 w-96 relative overflow-hidden" x-cloak>
                <div class="absolute bottom-0 left-0 h-1 bg-rose-500 w-full animate-[progress-shrink_6s_linear_forwards]"></div>
                <div class="bg-rose-500/20 p-2.5 rounded-xl text-rose-400 shrink-0 border border-rose-500/30 shadow-inner"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                <div class="flex-1 mt-0.5"><h4 class="font-black text-white text-[11px] uppercase tracking-[0.2em]">System Alert</h4><p class="text-slate-400 text-xs mt-1.5 font-medium leading-relaxed">{{ session('error') }}</p></div>
                <button @click="show = false" class="text-slate-600 hover:text-white transition-colors p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif
    </div>
    <style>@keyframes progress-shrink { from { width: 100%; } to { width: 0%; } }</style>

    {{-- 🌟 2. Financial Reports & Stats (God Mode Grid) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        {{-- Gross Sales --}}
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-xl relative overflow-hidden hover:border-emerald-500/40 transition-all duration-500 group fade-in-up-stagger" style="animation-delay: 0.1s;">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-[50px] group-hover:bg-emerald-500/20 transition-all duration-500"></div>
            <div class="flex items-center gap-4 mb-6 relative z-10">
                <div class="w-12 h-12 bg-slate-950 rounded-xl flex items-center justify-center text-emerald-400 border border-slate-800 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Gross Sales</p>
            </div>
            <div class="relative z-10 flex items-baseline gap-2">
                <p class="text-4xl font-black text-white tracking-tighter">{{ number_format($financials['gross'], 2) }}</p>
                <span class="text-xs font-bold text-emerald-500 uppercase tracking-widest">JOD</span>
            </div>
        </div>

        {{-- Platform Fees --}}
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-xl relative overflow-hidden hover:border-rose-500/40 transition-all duration-500 group fade-in-up-stagger" style="animation-delay: 0.2s;">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-rose-500/10 rounded-full blur-[50px] group-hover:bg-rose-500/20 transition-all duration-500"></div>
            <div class="flex items-center gap-4 mb-6 relative z-10">
                <div class="w-12 h-12 bg-slate-950 rounded-xl flex items-center justify-center text-rose-500 border border-slate-800 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                </div>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Platform Fees</p>
            </div>
            <div class="relative z-10 flex items-baseline gap-2">
                <p class="text-4xl font-black text-rose-400 tracking-tighter">- {{ number_format($financials['commission'], 2) }}</p>
                <span class="text-xs font-bold text-rose-500 uppercase tracking-widest">JOD</span>
            </div>
        </div>

        {{-- Net Profit --}}
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-indigo-500/30 shadow-[0_0_30px_rgba(99,102,241,0.1)] relative overflow-hidden hover:border-indigo-400/50 transition-all duration-500 group fade-in-up-stagger" style="animation-delay: 0.3s;">
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-[60px] group-hover:bg-indigo-500/30 transition-all duration-500"></div>
            <div class="flex items-center gap-4 mb-6 relative z-10">
                <div class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-400 border border-indigo-500/30 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Net Profit</p>
            </div>
            <div class="relative z-10 flex items-baseline gap-2">
                <p class="text-4xl font-black text-white tracking-tighter">{{ number_format($financials['net'], 2) }}</p>
                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">JOD</span>
            </div>
        </div>

        {{-- Active Drivers --}}
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-xl relative overflow-hidden hover:border-amber-500/40 transition-all duration-500 group fade-in-up-stagger" style="animation-delay: 0.4s;">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-amber-500/10 rounded-full blur-[50px] group-hover:bg-amber-500/20 transition-all duration-500"></div>
            <div class="flex items-center gap-4 mb-6 relative z-10">
                <div class="w-12 h-12 bg-slate-950 rounded-xl flex items-center justify-center text-amber-500 border border-slate-800 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Fleet Nodes</p>
            </div>
            <div class="relative z-10 flex items-center justify-between">
                <p class="text-4xl font-black text-white tracking-tighter">{{ $drivers->count() }}</p>
                <span class="px-3 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[9px] font-black uppercase tracking-widest shadow-sm">Active Drivers</span>
            </div>
        </div>
    </div>

    {{-- 🌟 3. Dispatching & Order Management --}}
    <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 overflow-hidden backdrop-blur-2xl shadow-2xl fade-in-up" style="animation-delay: 0.5s;">

        {{-- Header --}}
        <div class="p-8 md:p-10 border-b border-slate-800 bg-slate-950/40 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
            <div>
                <h3 class="text-2xl font-black text-white tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-blue-500/10 rounded-xl border border-blue-500/20 text-blue-400 shadow-[0_0_15px_rgba(59,130,246,0.2)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    Dispatch Core
                </h3>
                <p class="text-[10px] font-black text-slate-500 mt-2 uppercase tracking-[0.2em] ml-1">Route and assign incoming orders</p>
            </div>
            <a href="{{ route('supplies.drivers.create') }}" class="group flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-400 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 px-6 py-3.5 rounded-2xl transition-all shadow-sm active:scale-95">
                <svg class="w-4 h-4 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Deploy New Driver
            </a>
        </div>

        {{-- Table --}}
        @if($recentOrders->count() > 0)
            <div class="w-full overflow-x-auto table-scroll bg-slate-900/20">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead class="bg-slate-950/80 border-b border-slate-800">
                        <tr>
                            <th class="px-8 py-6 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Order Trace</th>
                            <th class="px-8 py-6 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Client Identity</th>
                            <th class="px-8 py-6 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Payload (Asset)</th>
                            <th class="px-8 py-6 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Net Yield</th>
                            <th class="px-8 py-6 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap text-center">Status</th>
                            <th class="px-8 py-6 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap text-right">Fleet Routing</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/40">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-slate-800/40 transition-colors group">

                                {{-- Order Trace --}}
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <span class="px-3 py-1.5 rounded-lg bg-slate-950 border border-slate-700 text-[10px] font-black font-mono text-indigo-400 uppercase tracking-widest shadow-inner group-hover:border-indigo-500/50 transition-colors">
                                        #{{ substr($order->id, 0, 8) }}
                                    </span>
                                </td>

                                {{-- Client Identity --}}
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <p class="text-sm font-black text-white group-hover:text-blue-400 transition-colors truncate max-w-[150px]">{{ $order->user->name ?? 'Unknown Client' }}</p>
                                </td>

                                {{-- Payload --}}
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <p class="text-sm font-black text-slate-300 truncate max-w-[200px] mb-1.5">{{ $order->supply->name ?? 'Deleted Asset' }}</p>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-slate-950 border border-slate-800 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        Units: <span class="text-white">{{ $order->quantity }}</span>
                                    </span>
                                </td>

                                {{-- Net Yield --}}
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <p class="text-xl font-black text-emerald-400 tracking-tighter">{{ number_format($order->net_company_amount ?? 0, 2) }} <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest ml-0.5">JOD</span></p>
                                </td>

                                {{-- Status Matrix --}}
                                <td class="px-8 py-6 whitespace-nowrap text-center">
                                    @php
                                        $statusClass = match($order->status) {
                                            'completed', 'delivered' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.1)]',
                                            'in_way' => 'bg-blue-500/10 text-blue-400 border-blue-500/30 shadow-[0_0_15px_rgba(59,130,246,0.1)]',
                                            default => 'bg-amber-500/10 text-amber-500 border-amber-500/30 animate-pulse shadow-[0_0_15px_rgba(245,158,11,0.1)]',
                                        };
                                        $statusText = match($order->status) {
                                            'completed', 'delivered' => 'Delivered',
                                            'in_way' => 'On Route',
                                            default => 'Pending',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                        @if($order->status == 'pending') <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> @endif
                                        {{ $statusText }}
                                    </span>
                                </td>

                                {{-- Fleet Routing / Assign Driver --}}
                                <td class="px-8 py-6 text-right whitespace-nowrap">
                                    @if($order->driver_id)
                                        <div class="flex items-center justify-end gap-3 opacity-90 group-hover:opacity-100 transition-opacity">
                                            <div class="flex flex-col text-right">
                                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-0.5">Active Driver</span>
                                                <span class="text-sm font-black text-white">{{ $order->driver->name }}</span>
                                            </div>
                                            <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-sm font-black border border-blue-500/30 shadow-inner shrink-0">
                                                {{ strtoupper(substr($order->driver->name, 0, 1)) }}
                                            </div>
                                        </div>
                                    @else
                                        {{-- Dispatch Form (Ultra Modern) --}}
                                        <form method="POST" action="{{ route('supplies.assign_driver', $order->id) }}" class="flex items-center justify-end gap-2">
                                            @csrf @method('PATCH')
                                            <div class="relative">
                                                <select name="driver_id" required class="appearance-none bg-slate-950 border border-slate-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl pl-4 pr-10 py-3 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer hover:bg-slate-900 shadow-inner min-w-[140px]">
                                                    <option value="" disabled selected>Select Driver...</option>
                                                    @foreach($drivers as $driver)
                                                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-500">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                                                </div>
                                            </div>
                                            <button type="submit" class="p-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-all shadow-[0_0_15px_rgba(59,130,246,0.3)] active:scale-95 flex-shrink-0" title="Dispatch Order">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-32 text-center flex flex-col items-center">
                <div class="w-24 h-24 bg-slate-950 rounded-[2rem] flex items-center justify-center mb-6 shadow-inner border border-slate-800 relative z-10">
                    <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Dispatch Queue Empty</h3>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest max-w-sm">No active orders awaiting dispatch protocol.</p>
            </div>
        @endif
    </div>

</div>
@endsection
