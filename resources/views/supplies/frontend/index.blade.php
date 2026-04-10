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

{{-- 💡 Alpine Data Scope for Cart Modal --}}
<div x-data="{
    // Sprint 4: Direct cart actions implemented on cards
}">

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

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center justify-between shadow-sm fade-in-up">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <a href="{{ route('cart.view') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-colors shadow-lg shadow-emerald-600/30">View Cart</a>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 flex items-center gap-3 shadow-sm fade-in-up">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold text-sm">{{ session('error') }}</span>
                </div>
            @endif

            @auth
                @if(auth()->user()->role === 'user' && empty($eligibleBookings))
                    <div class="mb-8 bg-amber-50 border border-amber-200 rounded-2xl p-6 shadow-sm flex items-start gap-4 fade-in-up">
                        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0 text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-amber-900 uppercase tracking-widest">Action Required</h3>
                            <p class="text-sm text-amber-800 font-medium mt-1">You can only order supplies up to 2 hours before your active farm booking starts, and during your stay. You currently have no eligible bookings within this timeframe.</p>
                            <a href="{{ route('explore') }}" class="inline-block mt-3 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-colors shadow-md shadow-amber-600/20">Book a Farm First</a>
                        </div>
                    </div>
                @endif
            @endauth

            {{-- 🌟 Category Filter Bar --}}
            @php
                $categories = [
                    'لحوم ومشاوي', 'خضار وفواكه', 'مقبلات وسلطات', 'أدوات ومعدات الشواء',
                    'تسالي وحلويات', 'مشروبات وثلج', 'مستلزمات السفرة والنظافة', 'ألعاب وترفيه'
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
                    <a href="{{ route('orders.my_orders') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gray-50 border border-gray-200 text-gray-600 font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-gray-100 hover:text-[#1d5c42] hover:border-[#1d5c42]/30 transition-colors shadow-sm flex items-center gap-2 active:scale-95">
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

            {{-- Products Grid --}}
            @if ($supplies->isEmpty())
                <div class="flex flex-col items-center justify-center py-32 bg-white rounded-[3rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 fade-in-up delay-300">
                    <div class="w-28 h-28 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-8 border border-gray-100 shadow-inner">
                        <svg class="w-14 h-14 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 mb-3 tracking-tight">{{ $currentCategory ? 'No items in this category' : 'Marketplace is Empty' }}</h3>
                    <p class="text-gray-500 mb-10 font-medium text-lg text-center max-w-lg">Our partners are currently restocking. Please check back soon!</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach ($supplies as $index => $supply)
                        <div class="group bg-white rounded-[2.5rem] border border-slate-100/80 shadow-[0_10px_40px_-15px_rgba(0,0,0,0.05)] hover:shadow-[0_30px_60px_-12px_rgba(29,92,66,0.12)] transition-all duration-500 flex flex-col h-full overflow-hidden fade-in-up-stagger" style="animation-delay: {{ $index * 0.05 }}s">
                            
                            {{-- 📷 Image Section --}}
                            <div class="relative h-56 overflow-hidden">
                                <img src="{{ $supply->image ? Storage::url($supply->image) : 'https://images.unsplash.com/photo-1589923188900-85dae523342b?q=80&w=2070' }}" 
                                     alt="{{ $supply->name }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                
                                {{-- Badges --}}
                                <div class="absolute top-4 left-4 flex flex-col gap-2">
                                    @if($supply->category)
                                        <span class="bg-black/40 backdrop-blur-md text-white text-[9px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest border border-white/10">
                                            {{ $supply->category }}
                                        </span>
                                    @endif
                                </div>

                                <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            </div>

                            {{-- 📝 Content Section --}}
                            <div class="p-6 flex flex-col flex-1">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-black text-slate-900 group-hover:text-[#1d5c42] transition-colors leading-tight line-clamp-1 truncate">
                                        {{ $supply->name }}
                                    </h3>
                                </div>
                                <p class="text-[11px] font-medium text-slate-400 line-clamp-2 mb-4 leading-relaxed h-8">
                                    {{ $supply->description ?? 'Premium quality supply for your farm stay experience.' }}
                                </p>

                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-2xl font-black text-emerald-600 tracking-tighter">{{ number_format($supply->price, 2) }}</span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">JOD</span>
                                    </div>
                                </div>

                                {{-- 🛒 Action Bar (The Fix) --}}
                                <div class="mt-auto">
                                    @auth
                                        @if(auth()->user()->role === 'user')
                                            <form action="{{ route('cart.add', $supply->id) }}" method="POST" class="flex items-center gap-3">
                                                @csrf
                                                {{-- Qty Selector --}}
                                                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl p-1.5 shadow-inner" x-data="{ qty: 1 }">
                                                    <button type="button" @click="if(qty > 1) qty--" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white text-slate-600 shadow-sm hover:text-red-500 transition-all font-black text-sm">-</button>
                                                    <input type="number" name="quantity" x-model="qty" readonly class="w-10 text-center bg-transparent border-none focus:ring-0 text-sm font-black text-slate-800 p-0 pointer-events-none">
                                                    <button type="button" @click="if(qty < {{ $supply->stock }}) qty++" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white text-slate-600 shadow-sm hover:text-emerald-600 transition-all font-black text-sm">+</button>
                                                </div>

                                                {{-- Add Button --}}
                                                <button type="submit" class="flex-1 bg-[#1d5c42] hover:bg-[#154230] text-white font-black py-3.5 px-4 rounded-2xl transition-all duration-300 shadow-[0_10px_20px_rgba(29,92,66,0.15)] hover:shadow-[0_15px_30px_rgba(29,92,66,0.3)] active:scale-95 flex items-center justify-center gap-2 group/btn">
                                                    <svg class="w-4 h-4 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                                    <span class="text-[10px] uppercase tracking-widest">Add</span>
                                                </button>
                                            </form>
                                        @else
                                            <div class="w-full py-3.5 px-4 bg-slate-100 text-slate-400 font-black text-[10px] text-center rounded-2xl uppercase tracking-widest">
                                                Customer Only
                                            </div>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="block w-full text-center bg-[#1d5c42] hover:bg-[#154230] text-white font-black py-4 rounded-2xl transition-all shadow-lg uppercase tracking-widest text-[10px]">
                                            Login to Shop
                                        </a>
                                    @endauth
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

        {{-- 🌟 Floating Cart Button (يظهر إذا كان في السلة منتجات) 🌟 --}}
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
</div>
@endsection
