@extends('layouts.driver')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">

    {{-- ==========================================
         HERO SECTION (SUPPLY THEME: TEAL/EMERALD)
         ========================================== --}}
    <div class="relative bg-gradient-to-r from-teal-600 to-emerald-700 rounded-3xl p-8 sm:p-10 text-white shadow-[0_20px_50px_rgba(20,184,166,0.3)] overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/4 w-40 h-40 bg-teal-900/20 rounded-full blur-2xl pointer-events-none"></div>
        <svg class="absolute bottom-0 right-10 w-48 h-48 text-white/5 transform translate-y-10 pointer-events-none" fill="currentColor" viewBox="0 0 24 24"><path d="M20 8h-3V4H3v13h2v-2h14v2h2V10l-3-2zM5 15V6h10v9H5zm14 0h-2v-3h-4v-2h3.5l2.5 1.67V15z"/></svg>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-black uppercase tracking-widest mb-4 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                    Logistics Hub
                </div>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight mb-2">Delivery Operations</h1>
                <p class="text-teal-100 font-medium max-w-md leading-relaxed">Manage your assigned supply drops, track your routes, and confirm deliveries for farm owners.</p>
            </div>

            <div class="bg-black/10 backdrop-blur-xl border border-white/10 p-5 rounded-2xl text-center min-w-[140px] shadow-inner transform hover:scale-105 transition-transform">
                <p class="text-[10px] uppercase tracking-widest text-teal-200 font-black mb-1">Active Deliveries</p>
                <p class="text-4xl font-black text-white">{{ $orders->count() }}</p>
            </div>
        </div>
    </div>

    {{-- ==========================================
         ORDERS GRID
         ========================================== --}}
    @if($orders->isEmpty())
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center flex flex-col items-center justify-center transform hover:-translate-y-1 transition-all">
            <div class="w-24 h-24 bg-teal-50 text-teal-500 rounded-full flex items-center justify-center mb-6 shadow-inner">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 tracking-tight">No Active Orders</h3>
            <p class="mt-2 text-gray-500 font-medium max-w-sm">You're all caught up! You currently have no assigned supply deliveries. Take a break.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden flex flex-col transform hover:-translate-y-1 transition-all duration-300 group">

                    {{-- Card Header --}}
                    <div class="p-6 border-b border-gray-50 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-teal-50 to-transparent rounded-bl-full opacity-50 pointer-events-none"></div>

                        <div class="flex justify-between items-start mb-5 relative z-10">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-teal-600 mb-1">Order ID</p>
                                <h3 class="text-2xl font-black text-gray-900 tracking-tight">#{{ $order->id }}</h3>
                            </div>
                            <div class="bg-teal-50 px-3 py-1.5 rounded-xl border border-teal-100">
                                <span class="text-sm font-black text-teal-700">{{ number_format($order->total_price, 2) }} <span class="text-[10px] uppercase">JOD</span></span>
                            </div>
                        </div>

                        {{-- Order Details --}}
                        <div class="space-y-3 relative z-10">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 w-6 h-6 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Destination</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $order->farmBooking->farm->name ?? 'Farm Location' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 w-6 h-6 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Customer</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $order->user->name ?? 'N/A' }} <span class="text-gray-400 font-medium ml-1">({{ $order->user->phone ?? 'N/A' }})</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Visual Status Progress --}}
                    <div class="px-6 py-5 bg-gray-50/50 flex-grow">
                        @php
                            $progress = 0;
                            if($order->status == 'pending') $progress = 25;
                            if($order->status == 'waiting_driver') $progress = 50;
                            if($order->status == 'in_way') $progress = 75;
                            if($order->status == 'delivered') $progress = 100;
                        @endphp

                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Delivery Status</span>
                            <span class="text-xs font-black {{ $progress == 100 ? 'text-emerald-500' : 'text-teal-600' }}">{{ $progress }}%</span>
                        </div>

                        <div class="relative w-full h-2 bg-gray-200 rounded-full overflow-hidden mb-3">
                            <div style="width: {{ $progress }}%" class="absolute top-0 left-0 h-full bg-gradient-to-r {{ $progress == 100 ? 'from-emerald-400 to-emerald-500' : 'from-teal-400 to-teal-600' }} transition-all duration-700 ease-out"></div>
                        </div>

                        <div class="flex justify-between text-[9px] font-bold uppercase tracking-wider text-gray-400">
                            <span class="{{ $progress >= 25 ? 'text-teal-600' : '' }}">Prep</span>
                            <span class="{{ $progress >= 50 ? 'text-teal-600' : '' }}">Waiting</span>
                            <span class="{{ $progress >= 75 ? 'text-teal-600' : '' }}">In Way</span>
                            <span class="{{ $progress >= 100 ? 'text-emerald-500' : '' }}">Done</span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="p-6 bg-white border-t border-gray-50">
                        <form action="{{ route('supply.driver.update_status', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            @if($order->status == 'waiting_driver')
                                <button name="status" value="in_way" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-orange-500 hover:to-orange-600 text-white font-black text-sm uppercase tracking-widest py-3.5 px-4 rounded-2xl shadow-[0_8px_20px_rgba(245,158,11,0.3)] hover:shadow-[0_8px_25px_rgba(245,158,11,0.4)] transition-all transform active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Start Delivery
                                </button>
                            @elseif($order->status == 'in_way')
                                <button name="status" value="delivered" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-teal-500 hover:to-teal-600 text-white font-black text-sm uppercase tracking-widest py-3.5 px-4 rounded-2xl shadow-[0_8px_20px_rgba(16,185,129,0.3)] hover:shadow-[0_8px_25px_rgba(16,185,129,0.4)] transition-all transform active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    Mark Delivered
                                </button>
                            @elseif($order->status == 'delivered')
                                <button type="button" disabled class="w-full flex items-center justify-center gap-2 bg-gray-100 text-gray-400 font-black text-sm uppercase tracking-widest py-3.5 px-4 rounded-2xl cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Completed
                                </button>
                            @else
                                <button type="button" disabled class="w-full flex items-center justify-center gap-2 bg-gray-100 text-gray-400 font-black text-sm uppercase tracking-widest py-3.5 px-4 rounded-2xl cursor-not-allowed">
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Preparing Store...
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
</style>
@endsection
