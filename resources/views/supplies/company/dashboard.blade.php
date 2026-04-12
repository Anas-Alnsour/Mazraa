<x-supply-layout>
    <x-slot name="header">Order Management Command Center</x-slot>

    <style>
        .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
        .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
        @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    </style>

    <div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-10 pb-24 pt-4 animate-god-in">

        {{-- 🌟 1. Header Section (Ultra-Modern) --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 bg-slate-900/80 p-8 md:p-12 rounded-[3rem] border border-slate-800 shadow-2xl relative overflow-hidden backdrop-blur-2xl transition-all hover:border-emerald-500/30 fade-in-up">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                <div class="w-16 h-16 rounded-[1.5rem] bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.2)] shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 shadow-inner mx-auto md:mx-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Supply Node Active
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-1">Command <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">Center</span></h1>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-2">Manage incoming orders and logistics.</p>
                </div>
            </div>

            <div class="relative z-10 w-full md:w-auto mt-6 md:mt-0 flex justify-center md:justify-end gap-3">
                <a href="{{ route('supplies.items.create') }}" class="w-full md:w-auto px-8 py-5 bg-gradient-to-tr from-emerald-600 to-emerald-400 hover:to-emerald-300 text-slate-950 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_30px_rgba(16,185,129,0.3)] transform hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    New Product
                </a>
            </div>
        </div>

        {{-- 🌟 2. Metrics Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 fade-in-up" style="animation-delay: 0.1s;">
            <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-xl relative overflow-hidden hover:border-emerald-500/40 transition-all duration-500 group">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-[50px] group-hover:bg-emerald-500/20 transition-all duration-500"></div>
                <div class="flex items-center gap-4 mb-6 relative z-10">
                    <div class="w-12 h-12 bg-slate-950 rounded-xl flex items-center justify-center text-emerald-400 border border-slate-800 shadow-inner group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Net Revenue</p>
                </div>
                <div class="relative z-10 flex items-baseline gap-2">
                    <p class="text-4xl font-black text-white tracking-tighter">{{ number_format($totalRevenue ?? 0, 2) }}</p>
                    <span class="text-xs font-bold text-emerald-500 uppercase tracking-widest">JOD</span>
                </div>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-xl relative overflow-hidden hover:border-amber-500/40 transition-all duration-500 group">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-amber-500/10 rounded-full blur-[50px] group-hover:bg-amber-500/20 transition-all duration-500"></div>
                <div class="flex items-center gap-4 mb-6 relative z-10">
                    <div class="w-12 h-12 bg-slate-950 rounded-xl flex items-center justify-center text-amber-500 border border-slate-800 shadow-inner group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Active Deliveries</p>
                </div>
                <div class="relative z-10 flex items-baseline gap-2">
                    <p class="text-4xl font-black text-white tracking-tighter">{{ $activeOrdersCount ?? 0 }}</p>
                    <span class="text-xs font-bold text-amber-500 uppercase tracking-widest">Orders</span>
                </div>
            </div>
        </div>

        {{-- 🌟 3. Grouped Orders List --}}
        <div class="space-y-6 fade-in-up" style="animation-delay: 0.2s;">
            @forelse($groupedOrders as $invoiceId => $items)
                @php
                    $firstItem = $items->first();
                    $invoiceTotal = $items->sum('net_company_amount');
                @endphp

                <div class="bg-slate-900/60 rounded-[3rem] shadow-2xl border border-slate-800 overflow-hidden backdrop-blur-2xl transition-all hover:border-slate-700">

                    {{-- Invoice Header --}}
                    <div class="px-8 py-6 bg-slate-950/40 border-b border-slate-800 flex flex-col md:flex-row justify-between md:items-center gap-6">
                        <div>
                            <div class="flex items-center gap-4 mb-2">
                                <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    Invoice #{{ $invoiceId }}
                                </h3>
                                <span class="text-[10px] font-bold text-slate-500 bg-slate-950 px-3 py-1 rounded-lg border border-slate-800">{{ $firstItem->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="text-[11px] font-bold text-slate-400 flex items-center gap-2 mt-2 bg-slate-950/50 inline-flex px-3 py-1.5 rounded-lg border border-slate-800/50">
                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Destination: <span class="text-white">{{ $firstItem->booking->farm->name ?? 'Farm' }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col items-start md:items-end gap-3">
                            @if(in_array($firstItem->status, ['pending_payment', 'pending_verification']))
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest bg-slate-800 text-slate-400 border border-slate-700 shadow-inner">Awaiting Customer Payment</span>
                            @elseif($firstItem->status === 'pending')
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-400 border border-amber-500/30 shadow-[0_0_15px_rgba(245,158,11,0.2)]">Payment Confirmed - Prepare Order</span>
                            @elseif($firstItem->status === 'delivered')
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.2)]">Delivered Successfully</span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest bg-blue-500/10 text-blue-400 border border-blue-500/30 shadow-inner">{{ str_replace('_', ' ', $firstItem->status) }}</span>
                            @endif

                            @if($firstItem->driver_id)
                                <div class="text-[10px] font-bold text-slate-400 flex items-center gap-2 bg-slate-950 px-3 py-2 rounded-xl border border-slate-800 shadow-inner">
                                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    Agent: <span class="text-white">{{ $firstItem->driver->name }}</span> ({{ $firstItem->driver->phone }})
                                </div>
                            @else
                                <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest bg-rose-500/10 px-3 py-1.5 rounded-lg border border-rose-500/20">No Agent Assigned Yet</span>
                            @endif
                        </div>
                    </div>

                    {{-- Invoice Items --}}
                    <div class="p-8 divide-y divide-slate-800/50">
                        @foreach($items as $item)
                            <div class="py-4 flex justify-between items-center group">
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 rounded-[1rem] bg-slate-950 border border-slate-700 overflow-hidden shrink-0 flex items-center justify-center shadow-inner relative group-hover:border-emerald-500/30 transition-colors">
                                        @if($item->supply->image)
                                            <img src="{{ Storage::url($item->supply->image) }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                                        @else
                                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="block text-sm font-black text-white group-hover:text-emerald-400 transition-colors tracking-tight">{{ $item->supply->name }}</span>
                                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Qty: <span class="text-white">{{ $item->quantity }}</span></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="block text-base font-black text-emerald-400">{{ number_format($item->net_company_amount, 2) }} <span class="text-[9px] text-slate-500">JOD</span></span>
                                    <span class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1">Gross: {{ number_format($item->total_price, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Invoice Footer --}}
                    <div class="px-8 py-5 bg-slate-950/60 border-t border-slate-800 flex justify-end items-center gap-4 shadow-inner">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Invoice Net Yield:</span>
                        <span class="text-2xl font-black text-white tracking-tighter">{{ number_format($invoiceTotal, 2) }} <span class="text-sm font-bold text-emerald-500 uppercase tracking-widest ml-1">JOD</span></span>
                    </div>
                </div>
            @empty
                <div class="py-32 text-center bg-slate-900/40 rounded-[3rem] border border-slate-800 border-dashed flex flex-col items-center shadow-inner relative overflow-hidden">
                    <div class="w-24 h-24 bg-slate-950 rounded-[2rem] flex items-center justify-center mb-6 shadow-inner border border-slate-800 relative z-10">
                        <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Ledger Empty</h3>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] max-w-sm mx-auto leading-relaxed">No active orders assigned to your node right now.</p>
                </div>
            @endforelse
        </div>

    </div>
</x-supply-layout>
