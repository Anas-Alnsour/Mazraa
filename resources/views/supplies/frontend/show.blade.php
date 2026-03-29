@extends('layouts.app')

@section('title', $supply->name . ' - Farm Supplies')

@section('content')
<style>
    /* Smooth Fade In Stagger */
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="bg-[#f8fafc] min-h-screen py-32 selection:bg-[#1d5c42] selection:text-white relative">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- 🌟 Premium Breadcrumbs / Back Navigation --}}
        <div class="flex items-center flex-wrap gap-2 mb-10 fade-in-up">
            <a href="{{ route('supplies.market') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-[#1d5c42] font-black transition-colors uppercase tracking-widest text-[10px] group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Marketplace
            </a>

            {{-- 💡 معالجة الأقسام القديمة --}}
            <span class="text-gray-300 font-bold">/</span>
            <a href="{{ route('supplies.market', $supply->category ? ['category' => $supply->category] : []) }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-[#1d5c42] font-black transition-colors uppercase tracking-widest text-[10px]">
                {{ $supply->category ?: 'الكل' }}
            </a>

            <span class="text-gray-300 font-bold">/</span>
            <span class="text-gray-800 font-black uppercase tracking-widest text-[10px] truncate max-w-[200px]">{{ $supply->name }}</span>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 p-5 rounded-2xl shadow-sm font-bold mb-10 fade-in-up flex items-center gap-4">
                <div class="bg-red-500 p-2 rounded-full text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-sm font-bold">{{ session('error') }}</p>
            </div>
        @endif

        {{-- 🌟 Main Product Card --}}
        <div class="bg-white rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden flex flex-col md:flex-row mb-12 fade-in-up delay-100">

            {{-- Image Section --}}
            <div class="md:w-1/2 bg-gray-50/50 p-8 lg:p-12 flex items-center justify-center relative border-b md:border-b-0 md:border-r border-gray-100">
                @if($supply->image)
                    <img src="{{ Storage::url($supply->image) }}" alt="{{ $supply->name }}" class="w-full h-auto rounded-[2rem] shadow-lg object-cover transform hover:scale-105 transition-transform duration-700 ease-out">
                @else
                    <div class="h-64 w-64 bg-white rounded-full flex items-center justify-center text-gray-300 shadow-inner border border-gray-100">
                        <svg class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                @endif
                <div class="absolute top-8 left-8 bg-white/90 backdrop-blur px-4 py-2 rounded-xl text-[10px] font-black text-[#1d5c42] shadow-sm border border-gray-200 uppercase tracking-widest flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    In Stock
                </div>
            </div>

            {{-- Details Section --}}
            <div class="md:w-1/2 p-10 lg:p-14 flex flex-col justify-center bg-white" x-data="{ quantity: 1, price: {{ $supply->price }}, showConfirm: false }">

                <div class="mb-4 flex flex-wrap items-center gap-3">
                    @if($supply->category)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-[#c2a265]/10 text-[#c2a265] border border-[#c2a265]/20">
                            {{ $supply->category }}
                        </span>
                    @endif
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-[#1d5c42]/10 text-[#1d5c42] border border-[#1d5c42]/20">
                        Verified Partner
                    </span>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-1">
                        <svg class="w-3 h-3 text-[#c2a265]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        By {{ $supply->company->name ?? 'Farm Supply Co.' }}
                    </span>
                </div>

                <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6 leading-tight tracking-tight">{{ $supply->name }}</h1>

                <div class="flex items-end gap-2 mb-8 pb-8 border-b border-gray-100/80">
                    <span class="text-5xl font-black text-[#1d5c42] tracking-tighter">{{ number_format($supply->price, 2) }}</span>
                    <span class="text-sm font-black text-gray-400 mb-2 uppercase tracking-widest">JOD / Unit</span>
                </div>

                <div class="text-gray-500 font-bold mb-10 leading-relaxed text-sm">
                    <p>{{ $supply->description ?: 'No detailed description available for this premium product.' }}</p>
                </div>

                <div class="mb-8 p-5 bg-[#1d5c42]/5 rounded-2xl border border-[#1d5c42]/10 flex items-center gap-5">
                    <div class="h-12 w-12 rounded-xl bg-white flex items-center justify-center shadow-sm text-[#1d5c42] border border-gray-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <div>
                        <h4 class="font-black text-[#1d5c42] text-[10px] md:text-xs uppercase tracking-widest">Current Availability</h4>
                        <p class="text-xs font-bold text-gray-600 mt-1"><span class="text-[#c2a265] font-black">{{ $supply->stock }} Units</span> ready for dispatch</p>
                    </div>
                </div>

                <form action="{{ route('cart.add', $supply->id) }}" method="POST" class="mt-auto relative z-20">
                    @csrf
                    <input type="hidden" name="quantity" x-model="quantity">

                    <div class="mb-6 p-5 bg-gray-50 rounded-2xl border border-gray-100 flex justify-between items-center shadow-inner">
                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Value</span>
                        <span class="text-2xl font-black text-[#1d5c42]"><span x-text="(quantity * price).toFixed(2)"></span> <span class="text-[10px] text-gray-400 uppercase">JOD</span></span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        {{-- 🌟 أزرار التحكم بالكمية المحدثة والبارزة 🌟 --}}
                        <div class="w-full sm:w-1/3 flex items-center justify-between bg-white border border-gray-200 rounded-2xl p-1.5 shadow-sm">
                            <button type="button" @click="if(quantity > 1) quantity--" class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-50 border border-gray-200 text-gray-600 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all shadow-sm active:scale-95 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" /></svg>
                            </button>

                            <span class="text-2xl font-black text-gray-900 w-12 text-center select-none" x-text="quantity"></span>

                            <button type="button" @click="if(quantity < {{ $supply->stock }}) quantity++" class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-50 border border-gray-200 text-gray-600 hover:bg-[#1d5c42] hover:text-white hover:border-[#1d5c42] transition-all shadow-sm active:scale-95 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </div>
                        {{-- نهاية أزرار التحكم --}}

                        <button type="button" @click="showConfirm = true" class="w-full sm:w-2/3 bg-[#1d5c42] hover:bg-[#154230] text-white font-black py-4 px-8 rounded-2xl shadow-lg hover:shadow-[0_10px_25px_rgba(29,92,66,0.3)] transition-all transform active:scale-95 flex items-center justify-center gap-3 uppercase tracking-widest text-[10px] md:text-xs">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" /></svg>
                            Add to Cart
                        </button>
                    </div>

                    {{-- 🌟 Premium Alpine Confirmation Modal --}}
                    <div x-show="showConfirm" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-[#020617]/80 backdrop-blur-md"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0">

                        <div @click.away="showConfirm = false" class="bg-white rounded-[3rem] p-10 max-w-md w-full mx-4 shadow-2xl transform border border-gray-100"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-8 scale-95">

                            <div class="w-20 h-20 bg-[#1d5c42]/10 rounded-full flex items-center justify-center mb-6 mx-auto text-[#1d5c42] border border-[#1d5c42]/20">
                                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>

                            <h3 class="text-2xl font-black text-center text-gray-900 mb-2 tracking-tight">Confirm Order</h3>
                            <p class="text-xs font-bold text-center text-gray-500 mb-8 uppercase tracking-widest leading-relaxed">
                                You are about to purchase <br><span class="text-[#1d5c42] font-black text-base" x-text="quantity"></span> units of <span class="text-gray-900">{{ $supply->name }}</span>
                            </p>

                            <div class="bg-gray-50 rounded-2xl p-5 mb-8 border border-gray-100">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Unit Price</span>
                                    <span class="text-sm font-black text-gray-900">{{ number_format($supply->price, 2) }} JOD</span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-gray-200/60">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total to pay</span>
                                    <span class="text-xl font-black text-[#1d5c42]" x-text="(quantity * price).toFixed(2) + ' JOD'"></span>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <button type="button" @click="showConfirm = false" class="flex-1 bg-white border-2 border-gray-100 hover:bg-gray-50 hover:border-gray-200 text-gray-500 hover:text-gray-900 font-black py-4 rounded-2xl transition-all transform active:scale-95 uppercase tracking-widest text-[10px]">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 bg-[#1d5c42] hover:bg-[#154230] text-white font-black py-4 rounded-2xl shadow-[0_8px_20px_rgba(29,92,66,0.3)] transition-all transform active:scale-95 uppercase tracking-widest text-[10px]">
                                    Confirm
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        {{-- ⭐️ Company Reviews Section ⭐️ --}}
        @if($supply->company)
            <div class="fade-in-up delay-200">
                <x-reviews-section
                    :reviews="$supply->company->receivedReviews"
                    :reviewable-id="$supply->company->id"
                    reviewable-type="supply_company"
                    :average-rating="$supply->company->company_rating"
                />
            </div>
        @endif

    </div>

    {{-- 🌟 Floating Cart Button (يظهر فقط إذا تم إضافة منتج بنجاح أو إذا كان في السلة منتجات) 🌟 --}}
    @auth
        @php
            $hasItemsInCart = \App\Models\SupplyOrder::where('user_id', auth()->id())->where('status', 'cart')->exists();
        @endphp

        @if(session('success') || $hasItemsInCart)
            <div class="fixed bottom-8 right-8 z-[90] fade-in-up delay-300">
                <a href="{{ route('cart.view') }}" class="flex items-center gap-3 bg-gradient-to-r from-[#1d5c42] to-[#154230] text-white px-7 py-4 rounded-full shadow-[0_20px_50px_rgba(29,92,66,0.5)] hover:shadow-[0_25px_60px_rgba(29,92,66,0.6)] transition-all duration-300 transform hover:-translate-y-1.5 group border border-[#154230]">
                    <div class="relative">
                        <svg class="w-6 h-6 transform group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="absolute -top-2 -right-2 bg-[#c2a265] text-white text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center border border-white/20 shadow-sm animate-pulse">✓</span>
                    </div>
                    <span class="text-[10px] md:text-xs font-black uppercase tracking-widest text-white/90 group-hover:text-white transition-colors">Go to Checkout</span>
                </a>
            </div>
        @endif
    @endauth

</div>
@endsection
