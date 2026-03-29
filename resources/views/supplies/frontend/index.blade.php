@extends('layouts.app')

@section('title', 'Farm Supplies Marketplace')

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
    .delay-300 { animation-delay: 0.3s; }

    /* Gradient overlay for images */
    .image-gradient-overlay {
        background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.4) 50%, rgba(15, 23, 42, 0) 100%);
    }

    /* Card Hover Effects */
    .product-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 25px 50px -12px rgba(29, 92, 66, 0.15); border-color: rgba(29, 92, 66, 0.2); }

    /* Hide Scrollbar for Filter Menu */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

{{-- 💡 pt-36 to push content further down from the fixed navbar --}}
<div class="bg-[#f8fafc] min-h-screen pb-24 font-sans pt-36 selection:bg-[#1d5c42] selection:text-white relative">

    {{-- ==========================================
         1. HERO SECTION (MINI)
         ========================================== --}}
    <div class="relative w-full h-[30vh] min-h-[280px] flex items-center justify-center bg-[#020617] overflow-hidden mb-10 rounded-[2.5rem] mx-auto max-w-[96%] xl:max-w-[94%] shadow-2xl shadow-gray-900/10">
        <img src="{{ asset('backgrounds/home.JPG') }}" alt="Marketplace Background"
             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1595856358173-9883ea54b036?q=80&w=2070&auto=format&fit=crop';"
             class="absolute inset-0 w-full h-full object-cover opacity-30 animate-[pulse_15s_ease-in-out_infinite] grayscale-[20%]">

        <div class="absolute inset-0 bg-gradient-to-b from-[#020617]/95 via-[#020617]/70 to-[#f8fafc]/10"></div>
        <div class="absolute top-1/4 left-1/3 w-96 h-96 bg-[#1d5c42]/30 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-[#c2a265]/20 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-4">
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter mb-4 drop-shadow-2xl fade-in-up">
                Farm <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-[#f4e4c1] to-[#c2a265]">Supplies</span>
            </h1>
            <p class="text-base md:text-lg text-gray-300 font-medium max-w-xl mx-auto fade-in-up leading-relaxed" style="animation-delay: 0.1s;">
                Premium supplies delivered directly to your farm by our B2B partners.
            </p>
        </div>
    </div>

    {{-- ==========================================
         2. MAIN CONTENT
         ========================================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-30">

        {{-- 🌟 Category Filter Bar --}}
        @php
            $categories = [
                'لحوم ومشاوي',
                'خضار وفواكه',
                'مقبلات وسلطات',
                'أدوات ومعدات الشواء',
                'تسالي وحلويات',
                'مشروبات وثلج',
                'مستلزمات السفرة والنظافة',
                'ألعاب وترفيه'
            ];
            $currentCategory = request('category');
        @endphp

        <div class="mb-8 fade-in-up" style="animation-delay: 0.15s;">
            <div class="flex overflow-x-auto hide-scrollbar gap-3 pb-4 px-2">
                <a href="{{ route('supplies.market') }}"
                   class="whitespace-nowrap px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all border-2 flex items-center justify-center {{ !$currentCategory ? 'bg-[#1d5c42] text-white border-[#1d5c42] shadow-[0_8px_20px_rgba(29,92,66,0.3)]' : 'bg-white text-gray-500 border-gray-100 hover:border-[#1d5c42]/30 hover:text-[#1d5c42] hover:bg-gray-50' }}">
                    الكل (All)
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('supplies.market', ['category' => $category]) }}"
                       class="whitespace-nowrap px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all border-2 flex items-center justify-center {{ $currentCategory === $category ? 'bg-[#1d5c42] text-white border-[#1d5c42] shadow-[0_8px_20px_rgba(29,92,66,0.3)]' : 'bg-white text-gray-500 border-gray-100 hover:border-[#1d5c42]/30 hover:text-[#1d5c42] hover:bg-gray-50' }}">
                        {{ $category }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Actions Bar --}}
        <div class="flex flex-col sm:flex-row justify-between items-center bg-white p-4 rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-100 mb-12 gap-4 fade-in-up" style="animation-delay: 0.2s;">
            <div class="px-5">
                <p class="text-sm font-black text-gray-800 uppercase tracking-widest flex items-center gap-2">
                    {{ $currentCategory ? $currentCategory : 'Available Items' }}: <span class="bg-[#1d5c42]/10 text-[#1d5c42] px-3 py-1 rounded-lg text-lg">{{ $supplies->total() }}</span>
                </p>
            </div>

            @auth
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('supplies.my_orders') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gray-50 border border-gray-200 text-gray-600 font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-gray-100 hover:text-[#1d5c42] hover:border-[#1d5c42]/30 transition-colors shadow-sm flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Track Orders
                </a>
                <a href="{{ route('cart.view') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gradient-to-r from-[#1d5c42] to-[#154230] text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:shadow-[0_8px_25px_rgba(29,92,66,0.4)] transition-all flex items-center gap-2 active:scale-95 hover:-translate-y-1">
                    <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Checkout Cart
                </a>
            </div>
            @endauth
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-200 p-5 rounded-2xl flex items-center gap-4 shadow-sm fade-in-up delay-300">
                <div class="bg-green-500 p-2 rounded-full text-white shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                <p class="text-green-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Products Grid --}}
        @if ($supplies->isEmpty())
            <div class="flex flex-col items-center justify-center py-32 bg-white rounded-[3rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 fade-in-up delay-300">
                <div class="w-28 h-28 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-8 border border-gray-100 shadow-inner">
                    <svg class="w-14 h-14 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900 mb-3 tracking-tight">{{ $currentCategory ? 'No items in this category' : 'Marketplace is Empty' }}</h3>
                <p class="text-gray-500 mb-10 font-medium text-lg text-center max-w-lg">Our B2B vendors are currently restocking their premium supplies. Please check back soon!</p>
               @if($currentCategory)
                    <a href="{{ route('supplies.market') }}" class="text-[#1d5c42] font-black uppercase tracking-widest text-sm hover:underline">View All Items</a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 lg:gap-10">
                @foreach ($supplies as $index => $supply)
                    <div class="product-card fade-in-up-stagger bg-white rounded-[2.5rem] shadow-[0_10px_30px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden group flex flex-col h-full relative" style="animation-delay: {{ 0.2 + ($index * 0.1) }}s;">

                        {{-- Product Image & Overlays --}}
                        <div class="relative h-64 overflow-hidden rounded-t-[2.5rem] bg-gray-50">
                            @if($supply->image)
                                <img src="{{ Storage::url($supply->image) }}" alt="{{ $supply->name }}" class="w-full h-full object-cover transition-transform duration-[1.5s] ease-out group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50/50">
                                    <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif

                            {{-- Price Badge (Top Right) --}}
                            <div class="absolute top-5 right-5 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-2xl text-sm font-black text-[#1d5c42] shadow-lg flex items-baseline gap-1 z-10 border border-white/50">
                                {{ number_format($supply->price, 2) }} <span class="text-[9px] text-gray-400 uppercase tracking-widest">JOD</span>
                            </div>

                            {{-- Category Badge (Top Left) --}}
                            @if($supply->category)
                                <div class="absolute top-5 left-5 bg-black/60 backdrop-blur-sm px-3 py-1.5 rounded-lg text-[9px] font-black text-white uppercase tracking-widest shadow-sm border border-white/10">
                                    {{ $supply->category }}
                                </div>
                            @endif
                        </div>

                        {{-- Product Details --}}
                        <div class="p-6 md:p-8 flex flex-col flex-1 bg-white">
                            <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-[#1d5c42] transition-colors line-clamp-1 tracking-tight">{{ $supply->name }}</h3>

                            <p class="text-xs font-bold text-gray-400 line-clamp-2 mb-6 flex-1 leading-relaxed">
                                {{ $supply->description ?: 'No description provided for this premium supply.' }}
                            </p>

                            {{-- Vendor & Stock Info Box --}}
                            <div class="flex items-center justify-between mt-auto pb-5 border-b border-gray-100/80 mb-5">
                                <div class="flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-[#1d5c42] bg-[#1d5c42]/10 px-3 py-1.5 rounded-lg border border-[#1d5c42]/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#1d5c42] animate-pulse"></span>
                                    {{ $supply->stock }} Left
                                </div>
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest truncate max-w-[110px] flex items-center gap-1" title="{{ $supply->company->name ?? 'Premium Vendor' }}">
                                    <svg class="w-3 h-3 text-[#c2a265]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    {{ $supply->company->name ?? 'Vendor' }}
                                </span>
                            </div>

                            {{-- Actions --}}
                            <div class="mt-auto flex flex-col gap-3">
                                {{-- Add to Cart Form (Alpine.js) --}}
                                <form action="{{ route('cart.add', $supply->id) }}" method="POST" class="flex items-center gap-3 w-full" x-data="{ qty: 1, max: {{ $supply->stock }} }">
                                    @csrf
                                    <input type="hidden" name="quantity" x-model="qty">

                                    {{-- Custom Quantity Adjuster --}}
                                    <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl w-24 p-1.5 shadow-inner">
                                        <button type="button" @click="if(qty > 1) qty--" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white text-gray-500 hover:text-red-500 hover:bg-red-50 shadow-sm border border-gray-100 transition-colors active:scale-95 focus:outline-none">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" /></svg>
                                        </button>
                                        <span class="text-xs font-black text-gray-900 w-6 text-center select-none" x-text="qty"></span>
                                        <button type="button" @click="if(qty < max) qty++" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white text-gray-500 hover:text-[#1d5c42] hover:bg-[#1d5c42]/10 shadow-sm border border-gray-100 transition-colors active:scale-95 focus:outline-none">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                                        </button>
                                    </div>

                                    {{-- Add Button --}}
                                    <button type="submit" class="flex-1 bg-[#1d5c42] hover:bg-[#154230] text-white font-black py-3 px-2 rounded-xl transition-all duration-300 uppercase tracking-widest text-[10px] md:text-xs shadow-md hover:shadow-[0_8px_20px_rgba(29,92,66,0.3)] transform active:scale-95 flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        Add
                                    </button>
                                </form>

                                {{-- View Details --}}
                                <a href="{{ route('supplies.show', $supply->id) }}" class="block w-full text-center text-gray-500 hover:text-[#1d5c42] border-2 border-gray-100 hover:border-[#1d5c42]/30 font-black py-3 rounded-xl transition-all duration-200 uppercase tracking-widest text-[10px] bg-white hover:bg-gray-50 active:scale-95">
                                    View Full Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- 🌟 Pagination --}}
        @if($supplies->hasPages())
            <div class="mt-16 flex justify-center fade-in-up delay-300">
                {{ $supplies->appends(request()->query())->links() }}
            </div>
        @endif

    </div>

    {{-- 🌟 Floating Cart Button --}}
    @auth
    <div class="fixed bottom-8 right-8 z-50 fade-in-up delay-300">
        <a href="{{ route('cart.view') }}" class="flex items-center gap-3 bg-gradient-to-r from-[#1d5c42] to-[#154230] text-white px-7 py-4 rounded-full shadow-2xl hover:shadow-[0_15px_40px_rgba(29,92,66,0.4)] transition-all duration-300 transform hover:-translate-y-1.5 group border border-[#154230]">
            <div class="relative">
                <svg class="w-6 h-6 transform group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="absolute -top-2 -right-2 bg-yellow-500 text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center border border-yellow-600 shadow-sm animate-pulse">✓</span>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest text-white/90 group-hover:text-white transition-colors">Go to Checkout</span>
        </a>
    </div>
    @endauth

</div>
@endsection
