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
                <p class="text-[10px] uppercase tracking-widest text-teal-200 font-black mb-1">Total Invoices</p>
                <p class="text-4xl font-black text-white">{{ $groupedOrders->count() }}</p>
            </div>
        </div>
    </div>

    {{-- ==========================================
         ORDERS GRID (GROUPED BY INVOICE)
         ========================================== --}}

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if($groupedOrders->isEmpty())
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center flex flex-col items-center justify-center">
            <div class="w-24 h-24 bg-teal-50 text-teal-500 rounded-full flex items-center justify-center mb-6 shadow-inner">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 tracking-tight">No Deliveries Found</h3>
            <p class="mt-2 text-gray-500 font-medium max-w-sm">You currently have no assigned supply deliveries. Take a break!</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($groupedOrders as $invoiceId => $items)
                @php
                    $firstItem = $items->first();
                    $invoiceTotal = $items->sum('total_price');
                @endphp

                <div class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">

                    {{-- Invoice Header --}}
                    <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-teal-600 mb-1">Invoice ID</p>
                                <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $invoiceId }}</h3>
                            </div>

                            @if($firstItem->booking->farm->location_link)
                                <a href="{{ $firstItem->booking->farm->location_link }}" target="_blank" class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-600/30 hover:bg-teal-700 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </a>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Destination Farm</p>
                                <p class="text-sm font-bold text-gray-800">{{ $firstItem->booking->farm->name ?? 'N/A' }}</p>
                                <p class="text-[11px] text-gray-500 font-medium line-clamp-1">{{ $firstItem->booking->farm->location ?? 'No location details' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer</p>
                                <p class="text-sm font-bold text-gray-800">{{ $firstItem->user->name ?? 'Guest' }}</p>
                                <a href="tel:{{ $firstItem->user->phone }}" class="text-[11px] text-teal-600 font-black flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $firstItem->user->phone ?? 'Call' }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Items List --}}
                    <div class="p-6 space-y-4 flex-grow">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Order Items</p>
                        @foreach($items as $item)
                            <div class="flex justify-between items-center bg-gray-50/50 p-3 rounded-2xl border border-gray-100/50">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-[10px] font-black text-teal-600 border border-gray-100 shadow-sm">{{ $item->quantity }}x</span>
                                    <span class="text-sm font-bold text-gray-700">{{ $item->supply->name }}</span>
                                </div>
                                <span class="text-xs font-bold text-gray-400">{{ number_format($item->total_price, 2) }} JOD</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Status Update Controls --}}
                    <div class="p-6 bg-white border-t border-gray-50">
                        <form action="{{ route('driver.supply.update_status', $invoiceId) }}" method="POST" class="flex flex-col gap-4">
                            @csrf @method('PUT')

                            <div class="relative group">
                                <select name="status" class="w-full rounded-2xl border-gray-200 bg-gray-50 text-gray-900 text-xs font-black uppercase tracking-widest px-5 py-4 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 appearance-none transition-all cursor-pointer">
                                    <option value="waiting_driver" {{ $firstItem->status == 'waiting_driver' ? 'selected' : '' }}>Picking Up Order</option>
                                    <option value="in_way" {{ $firstItem->status == 'in_way' ? 'selected' : '' }}>On My Way to Farm</option>
                                    <option value="delivered" {{ $firstItem->status == 'delivered' ? 'selected' : '' }}>Package Delivered</option>
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 bg-[#1d5c42] hover:bg-[#154230] text-white text-xs font-black uppercase tracking-widest rounded-2xl transition-all shadow-lg shadow-emerald-900/20 active:scale-[0.98]">
                                Update Delivery Progress
                            </button>
                        </form>

                        @if($firstItem->status === 'delivered')
                            <p class="text-center text-[9px] font-black text-emerald-500 uppercase tracking-widest mt-4 flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                Delivery Successfully Logged
                            </p>
                        @endif
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
