@extends('layouts.app')

@section('title', 'Order Tracking - Farm Supplies')

@section('content')
<style>
    /* Smooth Fade In Stagger */
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    .fade-in-up-stagger { animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="bg-[#f8fafc] min-h-screen py-32 selection:bg-[#1d5c42] selection:text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- 🌟 Header Section --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-6 fade-in-up">
            <div>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">Order <span class="text-[#1d5c42]">Tracking</span></h1>
                <p class="text-sm font-bold text-gray-400 mt-3 uppercase tracking-widest">Monitor your farm supplies in real-time</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('supplies.market') }}" class="inline-flex items-center gap-2 bg-white border-2 border-gray-100 text-gray-700 hover:text-[#1d5c42] hover:border-[#1d5c42]/30 font-black py-3 px-6 rounded-2xl transition-all shadow-sm text-[10px] md:text-xs uppercase tracking-widest transform hover:-translate-y-1 active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Continue Shopping
                </a>
            </div>
        </div>

        {{-- 💡 10-MINUTE CANCELLATION POLICY DISCLAIMER (Jules Banner) --}}
        <div class="bg-amber-50/80 border border-amber-200 p-6 mb-10 rounded-2xl shadow-sm fade-in-up">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 bg-amber-100 p-2 rounded-xl text-amber-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-amber-900 uppercase tracking-widest mb-1">10-Minute Cancellation Rule</h3>
                    <div class="text-sm text-amber-800/80 font-medium leading-relaxed">
                        <p>
                            To ensure fast dispatching and delivery to your farm, <strong class="text-amber-900">you can only edit or cancel an order within exactly 10 minutes of placing it.</strong> Once this window passes, the order is locked and assigned to a driver.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🌟 Flash Messages --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-5 rounded-2xl shadow-sm font-bold mb-10 fade-in-up flex items-center gap-4">
                <div class="bg-emerald-500 p-2 rounded-full text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                </div>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 p-5 rounded-2xl shadow-sm font-bold mb-10 fade-in-up flex items-center gap-4">
                <div class="bg-red-500 p-2 rounded-full text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-sm font-bold">{{ session('error') }}</p>
            </div>
        @endif

        @if($groupedOrders->count() > 0)
            <div class="space-y-10">
                @foreach($groupedOrders as $index => $invoice)
                    @php
                        // Assuming invoice is grouped by invoice_id, $items is the collection of orders
                        $items = $invoice;
                        $invoiceId = $items->first()->order_id ?? $items->first()->id;
                        $invoiceStatus = $items->first()->status;
                        $invoiceTotal = $items->sum('total_price');
                        $orderDate = $items->first()->created_at;

                        // 💡 الـ Business Logic الجديد للتتبع
                        $isPreparing = in_array($invoiceStatus, ['pending', 'processing', 'accepted', 'ready', 'waiting_driver', 'in_way', 'delivered']);
                        $isWaitingDriver = in_array($invoiceStatus, ['accepted', 'ready', 'waiting_driver', 'in_way', 'delivered']);
                        $isInWay = in_array($invoiceStatus, ['in_way', 'delivered']);
                        $isDelivered = in_array($invoiceStatus, ['delivered']);

                        $steps = [
                            ['title' => 'Preparing', 'sub' => 'يتم التجهيز بالمتجر', 'active' => $isPreparing],
                            ['title' => 'Waiting Driver', 'sub' => 'بانتظار السائق', 'active' => $isWaitingDriver],
                            ['title' => 'On the Way', 'sub' => 'في الطريق للمزرعة', 'active' => $isInWay],
                            ['title' => 'Delivered', 'sub' => 'وصل الطلب', 'active' => $isDelivered],
                        ];

                        // حساب نسبة اكتمال شريط التقدم الأخضر
                        $activeCount = count(array_filter(array_column($steps, 'active')));
                        $progressPercentage = $activeCount > 0 ? (($activeCount - 1) / (count($steps) - 1)) * 100 : 0;
                    @endphp

                    <div class="bg-white rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden relative fade-in-up-stagger" style="animation-delay: {{ (float)$index * 0.1 }}s;">

                        {{-- 📦 Invoice Header & Tracker --}}
                        <div class="bg-gray-50/50 p-8 lg:p-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10 border-b border-gray-100">
                            <div>
                                <div class="flex items-center gap-4 mb-3">
                                    <span class="text-3xl font-black text-gray-900 tracking-tighter">{{ $invoiceId }}</span>
                                    <span class="text-[10px] font-black text-[#1d5c42] bg-[#1d5c42]/10 border border-[#1d5c42]/20 px-3 py-1.5 rounded-lg uppercase tracking-widest">{{ $items->count() }} items</span>
                                </div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $orderDate->format('M d, Y - h:i A') }}
                                </p>
                            </div>

                            <div class="w-full lg:w-3/5">
                                @if(in_array($invoiceStatus, ['cancelled', 'rejected']))
                                    <div class="w-full text-center py-4 relative z-10 bg-red-50 backdrop-blur-sm rounded-2xl border border-red-100">
                                        <span class="inline-flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-red-600">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                            Order Cancelled
                                        </span>
                                    </div>
                                @else
                                    <div class="relative flex items-center justify-between pt-6">
                                        {{-- Background Line --}}
                                        <div class="absolute left-0 top-10 w-full h-1.5 bg-gray-200 rounded-full z-0"></div>
                                        {{-- Progress Line (Green) --}}
                                        <div class="absolute left-0 top-10 h-1.5 bg-[#1d5c42] rounded-full z-0 transition-all duration-1000 ease-out" style="width: {{ $progressPercentage }}%;"></div>

                                        @foreach($steps as $key => $step)
                                            <div class="relative z-10 flex flex-col items-center gap-3">
                                                <div class="h-10 w-10 rounded-full flex items-center justify-center border-4 transition-all duration-500 bg-white {{ $step['active'] ? 'border-[#1d5c42] text-[#1d5c42] shadow-[0_0_15px_rgba(29,92,66,0.3)]' : 'border-gray-200 text-transparent' }}">
                                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                </div>
                                                <div class="text-center bg-gray-50/80 px-2 rounded backdrop-blur-sm">
                                                    <span class="block text-[9px] md:text-[10px] font-black uppercase tracking-widest {{ $step['active'] ? 'text-gray-900' : 'text-gray-400' }}">{{ $step['title'] }}</span>
                                                    <span class="block text-[8px] font-bold text-[#c2a265] mt-0.5">{{ $step['sub'] }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- 🚚 Driver Info Section --}}
                        @if(in_array($invoiceStatus, ['waiting_driver', 'in_way', 'delivered']) && $items->first()->driver)
                            <div class="bg-[#1d5c42]/5 border-b border-[#1d5c42]/10 px-10 py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                                <div class="flex items-center gap-5">
                                    <div class="h-14 w-14 rounded-2xl bg-white shadow-sm flex items-center justify-center text-[#1d5c42] border border-[#1d5c42]/20">
                                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Driver Assigned</p>
                                        <p class="text-base font-black text-gray-900">{{ $items->first()->driver->name }}</p>
                                    </div>
                                </div>
                                @if($items->first()->driver->phone)
                                    <a href="tel:{{ $items->first()->driver->phone }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 hover:text-[#1d5c42] hover:border-[#1d5c42]/50 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm active:scale-95">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                        Call Driver
                                    </a>
                                @endif
                            </div>
                        @endif

                        {{-- 🛒 Products List --}}
                        <div class="p-8 lg:p-10">
                            <ul class="space-y-6">
                                @foreach($items as $item)
                                    <li class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 p-4 rounded-2xl hover:bg-gray-50/50 transition-colors border border-transparent hover:border-gray-100">
                                        <div class="flex items-center gap-6">
                                            <div class="h-20 w-20 rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0 shadow-sm relative group">
                                                @if($item->supply && $item->supply->image)
                                                    <img src="{{ Storage::url($item->supply->image) }}" class="h-full w-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                                @else
                                                    <div class="h-full w-full flex items-center justify-center text-gray-300">
                                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                @if($item->supply && $item->supply->category)
                                                    <span class="text-[8px] font-black text-[#c2a265] uppercase tracking-widest block mb-1">{{ $item->supply->category }}</span>
                                                @endif
                                                <h4 class="text-lg font-black text-gray-900">{{ $item->supply->name ?? 'Product Unavailable' }}</h4>
                                                <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest flex items-center gap-2">
                                                    <span>Qty: <strong class="text-gray-700">{{ $item->quantity }}</strong></span>
                                                    <span class="text-gray-300">|</span>
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3 h-3 text-[#c2a265]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                        By {{ $item->supply->company->name ?? 'Vendor' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-left sm:text-right flex flex-col sm:items-end ml-20 sm:ml-0">
                                            <span class="text-2xl font-black text-[#1d5c42] tracking-tighter">{{ number_format($item->total_price, 2) }}</span>
                                            <span class="text-[9px] text-gray-400 uppercase tracking-widest font-black">JOD Total</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-8 pt-8 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 p-6 rounded-3xl">
                                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Grand Invoice Total</span>
                                <span class="text-3xl font-black text-[#1d5c42] tracking-tighter">{{ number_format($invoiceTotal, 2) }} <span class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">JOD</span></span>
                            </div>
                        </div>

                        {{-- 💡 10 Minute Cancellation Logic Enforced Frontend --}}
                        @if(!in_array($invoiceStatus, ['cancelled', 'delivered']))
                            <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex justify-end items-center gap-4">
                                @if(now()->diffInMinutes($orderDate) <= 10)
                                    <span class="text-[10px] font-black text-amber-500 flex items-center gap-2 uppercase tracking-widest">
                                        <svg class="h-4 w-4 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ 10 - now()->diffInMinutes($orderDate) }} min left to cancel
                                    </span>
                                    <form action="{{ route('orders.destroy', $items->first()->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure you want to cancel this entire invoice? This action cannot be reversed.');" class="inline-flex items-center px-5 py-2.5 border border-red-200 shadow-sm text-[10px] font-black uppercase tracking-widest rounded-xl text-red-600 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            Cancel Invoice
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] font-black text-gray-400 flex items-center gap-2 uppercase tracking-widest">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Modification window closed
                                    </span>
                                @endif
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        @else
            {{-- 🌟 Empty State --}}
            <div class="py-24 px-10 text-center bg-white rounded-[4rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 fade-in-up delay-100">
                <div class="inline-flex items-center justify-center w-32 h-32 rounded-[2rem] bg-gray-50 mb-8 border border-gray-100 shadow-inner">
                    <svg class="h-14 w-14 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900 mb-3 tracking-tight">No Active Orders</h3>
                <p class="text-sm font-bold text-gray-400 max-w-md mx-auto mb-10 uppercase tracking-widest leading-relaxed">You haven't placed any premium supply orders yet.</p>
                <a href="{{ route('supplies.market') }}" class="inline-flex items-center gap-3 bg-[#1d5c42] hover:bg-[#154230] text-white font-black py-5 px-10 rounded-2xl shadow-[0_10px_20px_rgba(29,92,66,0.2)] hover:shadow-[0_15px_30px_rgba(29,92,66,0.3)] transition-all transform active:scale-95 uppercase tracking-widest text-[10px] md:text-xs">
                    Browse Marketplace
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
