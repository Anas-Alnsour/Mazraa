@extends('layouts.app')

@section('title', 'Shopping Cart - Farm Supplies')

@section('content')
<style>
    /* Smooth Fade In Stagger */
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    .fade-in-up-stagger { animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    .qty-input { background: transparent; border: none; text-align: center; width: 2.5rem; font-weight: 900; color: #111827; font-size: 0.875rem; pointer-events: none; }
    .qty-input:focus { outline: none; box-shadow: none; }
</style>

<div class="bg-[#f8fafc] min-h-screen py-32 selection:bg-[#1d5c42] selection:text-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- 🌟 Premium Header --}}
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 fade-in-up">
            <div>
                <a href="{{ route('supplies.market') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-[#1d5c42] font-black transition-colors uppercase tracking-widest text-[10px] group mb-4">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Continue Shopping
                </a>
                <h1 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tight">Shopping <span class="text-[#1d5c42]">Cart</span></h1>
                <p class="text-sm font-bold text-gray-400 mt-3 uppercase tracking-widest">Review your premium supplies before checkout</p>
            </div>

            @if($cartItems->count() > 0)
            <div class="bg-white px-5 py-3 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.03)] border border-gray-100 flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full bg-[#1d5c42] animate-pulse"></span>
                <span class="text-[10px] font-black text-gray-800 uppercase tracking-widest">{{ $cartItems->count() }} Premium Items</span>
            </div>
            @endif
        </div>

        {{-- 💡 STRICT TIMING POLICY DISCLAIMER --}}
        <div class="bg-blue-50/80 border border-blue-100 p-6 mb-8 rounded-2xl shadow-sm fade-in-up">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 bg-blue-100 p-2 rounded-xl text-blue-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-blue-900 uppercase tracking-widest mb-1">Strict Checkout Policy</h3>
                    <div class="text-sm text-blue-800/80 font-medium leading-relaxed">
                        <p>
                            You may add items to your cart at any time. However, to ensure fresh and prompt delivery,
                            <strong class="text-blue-900">checkout and payment are strictly allowed ONLY during your active farm booking duration, or exactly 2 hours before it starts.</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🌟 Flash Messages --}}
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 p-5 rounded-2xl shadow-sm font-bold mb-8 fade-in-up flex items-center gap-4">
                <div class="bg-red-500 p-2 rounded-full text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-sm font-bold">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-5 rounded-2xl shadow-sm font-bold mb-8 fade-in-up flex items-center gap-4">
                <div class="bg-emerald-500 p-2 rounded-full text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                </div>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        @endif

        @if($cartItems->count() > 0)
            <div class="flex flex-col lg:flex-row gap-10">

                {{-- 🌟 Cart Items List --}}
                <div class="lg:w-2/3 fade-in-up delay-100">
                    <div class="bg-white rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
                        <ul class="divide-y divide-gray-100">
                            @foreach($cartItems as $index => $item)
                                <li class="p-8 lg:p-10 flex flex-col sm:flex-row items-center sm:items-start gap-8 hover:bg-gray-50/50 transition-colors fade-in-up-stagger" style="animation-delay: {{ 0.1 + ($index * 0.1) }}s;">

                                    {{-- Product Image --}}
                                    <div class="h-32 w-32 flex-shrink-0 overflow-hidden rounded-[2rem] border border-gray-100 bg-gray-50 shadow-sm relative group">
                                        @if($item->supply && $item->supply->image)
                                            <img src="{{ asset('storage/supplies/' . $item->supply->image) }}" alt="{{ $item->supply->name }}" class="h-full w-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-gray-300">
                                                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 w-full flex flex-col sm:flex-row justify-between h-full gap-6">
                                        <div class="flex flex-col justify-between">
                                            <div>
                                                @if($item->supply && $item->supply->category)
                                                    <span class="inline-block px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-[#c2a265]/10 text-[#c2a265] border border-[#c2a265]/20 mb-3">
                                                        {{ $item->supply->category }}
                                                    </span>
                                                @endif
                                                <span class="block text-2xl font-black text-gray-900 line-clamp-1 tracking-tight">
                                                    {{ $item->supply->name ?? 'Product Unavailable' }}
                                                </span>

                                                <p class="text-[10px] font-bold text-gray-400 mt-2 uppercase tracking-widest flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-[#c2a265]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                    By {{ $localCompany ? $localCompany->name : 'Mazraa Official' }}
                                                </p>
                                            </div>

                                            <div class="mt-6 flex flex-wrap items-center gap-4">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-3 bg-gray-50 p-1.5 rounded-2xl border border-gray-100" x-data="{ qty: {{ $item->quantity }} }">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="quantity" x-model="qty">

                                                    {{-- Premium Quantity Adjuster --}}
                                                    <div class="flex items-center justify-between w-28">
                                                        <button type="button" @click="if(qty > 1) qty--" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white text-gray-500 hover:text-red-500 hover:bg-red-50 hover:border-red-200 border border-transparent shadow-sm transition-all focus:outline-none active:scale-95">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" /></svg>
                                                        </button>

                                                        <span class="text-sm font-black text-gray-900 w-8 text-center select-none" x-text="qty"></span>

                                                        <button type="button" @click="qty++" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white text-gray-500 hover:text-[#1d5c42] hover:bg-[#1d5c42]/10 hover:border-[#1d5c42]/20 border border-transparent shadow-sm transition-all focus:outline-none active:scale-95">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                                                        </button>
                                                    </div>

                                                    <button type="submit" class="text-[9px] font-black text-white bg-[#1d5c42] hover:bg-[#154230] px-4 py-2.5 rounded-xl uppercase tracking-widest transition-colors active:scale-95 shadow-md">Update</button>
                                                </form>

                                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-l-2 border-gray-100 pl-4 py-1">
                                                    {{ number_format($item->supply->price ?? 0, 2) }} JOD <span class="text-gray-300">/ unit</span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-4 sm:mt-0 flex flex-row sm:flex-col items-center sm:items-end justify-between border-t sm:border-t-0 border-gray-100 pt-4 sm:pt-0">
                                            <div class="text-left sm:text-right flex flex-col sm:items-end ml-20 sm:ml-0 mb-4">
                                                <span class="text-2xl font-black text-[#1d5c42] tracking-tighter">{{ number_format($item->total_price ?? 0, 2) }}</span>
                                                <span class="text-[9px] text-gray-400 uppercase tracking-widest font-black">JOD Total</span>
                                            </div>

                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[9px] font-black uppercase tracking-widest text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-5 py-3 rounded-xl transition-colors border border-red-100 active:scale-95 flex items-center gap-1.5 shadow-sm">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- 🌟 Order Summary (Sticky Sidebar) --}}
                <div class="lg:w-1/3 fade-in-up delay-200">
                    <div class="bg-white rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 p-8 lg:p-10 sticky top-32">
                        <h2 class="text-2xl font-black text-gray-900 mb-8 border-b border-gray-100 pb-6 tracking-tight">Order Summary</h2>

                        <div class="flex justify-between items-center text-sm font-bold text-gray-500 mb-5">
                            <span class="uppercase tracking-widest text-[10px] text-gray-400">Subtotal ({{ $cartItems->count() }} items)</span>
                            <span class="text-gray-900 font-black">{{ number_format($cartTotal, 2) }} JOD</span>
                        </div>

                        <div class="flex justify-between items-center text-sm font-bold text-gray-500 mb-8 border-b border-gray-100 pb-8">
                            <span class="uppercase tracking-widest text-[10px] text-gray-400">Platform Fee</span>
                            <span class="text-[9px] font-black text-[#c2a265] bg-[#c2a265]/10 px-3 py-1.5 rounded-lg border border-[#c2a265]/20 uppercase tracking-widest flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                Free
                            </span>
                        </div>

                        <div class="flex justify-between items-end mb-10 bg-gray-50 p-6 rounded-[2rem] border border-gray-100">
                            <div>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Grand Total</span>
                                <span class="text-xs text-gray-500 font-bold uppercase tracking-widest">Incl. VAT</span>
                            </div>
                            <div class="text-right">
                                <span class="text-4xl font-black text-[#1d5c42] block tracking-tighter">{{ number_format($cartTotal, 2) }}</span>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">JOD</span>
                            </div>
                        </div>

                        {{-- [Sprint 4 UX] Global Destination Selection --}}
                        <form action="{{ route('cart.place_order') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label for="booking_id" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Delivery Destination <span class="text-red-500">*</span></label>
                                @if(isset($eligibleBookings) && count($eligibleBookings) > 0)
                                    <select id="booking_id" name="booking_id" class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:border-[#1d5c42] focus:ring-[#1d5c42] px-5 py-4 text-xs font-bold text-gray-900 transition-all cursor-pointer hover:bg-gray-100/50" required>
                                        <option value="" disabled selected>Select an active booking...</option>
                                        @foreach($eligibleBookings as $booking)
                                            <option value="{{ $booking->id }}">
                                                {{ $booking->farm->name }} ({{ \Carbon\Carbon::parse($booking->start_time)->format('M d') }} stay)
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-[9px] text-gray-400 font-bold mt-2 italic px-1">Note: Supplies are only delivered during your farm stay.</p>
                                @else
                                    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                                        <p class="text-[9px] font-black text-amber-700 uppercase tracking-widest">No Eligible Bookings</p>
                                        <p class="text-[8px] text-amber-600 font-bold mt-1">Visit the Explore page to book a farm before ordering supplies.</p>
                                        <a href="{{ route('explore') }}" class="inline-block mt-3 text-[9px] font-black text-[#1d5c42] hover:underline">Book Now</a>
                                    </div>
                                @endif
                            </div>

                            <button type="submit"
                                @if(!isset($eligibleBookings) || count($eligibleBookings) === 0) disabled title="Please select a booking first" @endif
                                class="w-full bg-[#1d5c42] hover:bg-[#154230] disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed shadow-[0_10px_20px_rgba(29,92,66,0.2)] hover:shadow-[0_15px_30px_rgba(29,92,66,0.3)] text-white font-black py-5 px-6 rounded-2xl transition-all duration-300 transform active:scale-95 flex items-center justify-center gap-3 uppercase tracking-widest text-[10px] md:text-xs">
                                Confirm & Checkout
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </button>
                        </form>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col items-center gap-4">
                            <div class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                Secure Encrypted Checkout
                            </div>
                            <div class="flex gap-3 opacity-60">
                                <div class="w-10 h-6 bg-gray-200 rounded border border-gray-300 flex items-center justify-center text-[8px] font-black text-gray-500">VISA</div>
                                <div class="w-10 h-6 bg-gray-200 rounded border border-gray-300 flex items-center justify-center text-[8px] font-black text-gray-500">MC</div>
                                <div class="w-10 h-6 bg-gray-200 rounded border border-gray-300 flex items-center justify-center text-[8px] font-black text-gray-500">CLIQ</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- 🌟 Empty Cart State --}}
            <div class="bg-white rounded-[4rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 p-20 text-center fade-in-up delay-100">
                <div class="inline-flex items-center justify-center w-32 h-32 rounded-[2rem] bg-gray-50 mb-8 border border-gray-100 shadow-inner">
                    <svg class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Your Cart is Empty</h2>
                <p class="text-sm font-bold text-gray-400 mb-10 max-w-md mx-auto uppercase tracking-widest leading-relaxed">Looks like you haven't added any premium farm supplies yet. Let's fix that!</p>
                <a href="{{ route('supplies.market') }}" class="inline-flex items-center gap-3 bg-[#1d5c42] hover:bg-[#154230] text-white font-black py-5 px-10 rounded-2xl shadow-[0_10px_20px_rgba(29,92,66,0.2)] hover:shadow-[0_15px_30px_rgba(29,92,66,0.3)] transition-all transform active:scale-95 uppercase tracking-widest text-[10px] md:text-xs">
                    Browse Marketplace
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
