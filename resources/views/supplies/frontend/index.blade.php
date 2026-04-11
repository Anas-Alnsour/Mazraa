@extends('layouts.app')

@section('title', 'Farm Supplies Marketplace')

@section('content')
<style>
    /* Premium Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }
    .animate-float-slow { animation: float 8s ease-in-out infinite; }

    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }

    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* Input Reset & Enhancements */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Hide Scrollbar for Filter Menu */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Quantity Input Styling */
    .qty-input {
        background: transparent;
        border: none;
        text-align: center;
        width: 2.5rem;
        font-weight: 900;
        color: #111827;
        font-size: 0.875rem;
        pointer-events: none;
    }
    .qty-input:focus { outline: none; box-shadow: none; }
</style>

<div x-data="{}">
    <div class="bg-[#f4f7f6] min-h-screen pb-24 font-sans selection:bg-[#1d5c42] selection:text-white relative">

        {{-- ==========================================
             1. HERO SECTION (PREMIUM THEME)
             ========================================== --}}
        <div class="relative w-full h-[40vh] min-h-[350px] flex flex-col justify-center items-center bg-[#0a0a0a] overflow-hidden pt-12 rounded-b-[3rem] shadow-2xl">
            <div class="absolute inset-0 w-full h-full overflow-hidden">
                <img src="{{ asset('backgrounds/home.JPG') }}" alt="Marketplace"
                     onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1595856358173-9883ea54b036?q=80&w=2070&auto=format&fit=crop';"
                     class="w-full h-full object-cover opacity-30 scale-105 animate-[pulse_25s_ease-in-out_infinite] grayscale-[10%]">
            </div>
            <div class="absolute inset-0 shadow-[inset_0_0_150px_rgba(0,0,0,0.9)]"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#f4f7f6] via-transparent to-[#0a0a0a]/60"></div>

            <div class="absolute top-1/4 left-1/4 w-[30rem] h-[30rem] bg-[#1d5c42]/20 rounded-full blur-[120px] animate-float-slow pointer-events-none mix-blend-screen"></div>
            <div class="absolute bottom-1/4 right-1/4 w-[25rem] h-[25rem] bg-yellow-500/10 rounded-full blur-[100px] animate-float-slow pointer-events-none mix-blend-screen" style="animation-delay: 2s;"></div>

            <div class="relative z-10 text-center px-4 max-w-4xl mx-auto pb-10 fade-in-up">
                <div class="inline-flex items-center gap-2 py-1.5 px-5 rounded-full bg-white/5 border border-white/10 text-[#f4e4c1] text-[10px] font-black tracking-[0.25em] uppercase backdrop-blur-xl mb-5 shadow-2xl">
                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-ping absolute"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 relative"></span>
                    Smart Delivery
                </div>
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4 drop-shadow-2xl">
                    Farm <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-yellow-200 to-yellow-500 italic font-serif font-medium pr-2">Supplies</span>
                </h1>
                <p class="text-sm md:text-base text-gray-300 font-medium max-w-lg mx-auto leading-relaxed drop-shadow-lg opacity-90">
                    Premium supplies, groceries, and essentials delivered directly to your farm by our B2B partners.
                </p>
            </div>
        </div>

        {{-- ==========================================
             2. MAIN CONTENT
             ========================================== --}}
        <div class="max-w-[96%] xl:max-w-7xl mx-auto relative z-30 -mt-20">

            {{-- 🌟 Filter Categories (Glassmorphic Scroll) --}}
            @php
                $categories = [
                    'لحوم ومشاوي', 'خضار وفواكه', 'مقبلات وسلطات', 'أدوات ومعدات الشواء',
                    'تسالي وحلويات', 'مشروبات وثلج', 'مستلزمات السفرة والنظافة', 'ألعاب وترفيه'
                ];
                $currentCategory = request('category');
            @endphp

            <div class="mb-8 fade-in-up" style="animation-delay: 0.15s;">
                <div class="bg-white/90 backdrop-blur-2xl rounded-[2rem] p-3 shadow-[0_20px_40px_-10px_rgba(0,0,0,0.08)] border border-white">
                    <div class="flex overflow-x-auto hide-scrollbar gap-2 px-1 py-1">
                        <a href="{{ route('supplies.market') }}"
                           class="whitespace-nowrap px-6 py-3 rounded-xl font-black text-[10px] md:text-xs uppercase tracking-widest transition-all border flex items-center justify-center flex-shrink-0 {{ !$currentCategory ? 'bg-[#1d5c42] text-white border-[#1d5c42] shadow-[0_8px_20px_rgba(29,92,66,0.3)]' : 'bg-gray-50 text-gray-500 border-gray-100 hover:bg-white hover:border-[#1d5c42]/30 hover:text-[#1d5c42] shadow-sm' }}">
                            الكل (All)
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('supplies.market', ['category' => $category]) }}"
                               class="whitespace-nowrap px-6 py-3 rounded-xl font-black text-[10px] md:text-xs uppercase tracking-widest transition-all border flex items-center justify-center flex-shrink-0 {{ $currentCategory === $category ? 'bg-[#1d5c42] text-white border-[#1d5c42] shadow-[0_8px_20px_rgba(29,92,66,0.3)]' : 'bg-gray-50 text-gray-500 border-gray-100 hover:bg-white hover:border-[#1d5c42]/30 hover:text-[#1d5c42] shadow-sm' }}">
                                {{ $category }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Actions Bar --}}
            <div class="flex flex-col sm:flex-row justify-between items-center bg-white p-4 rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-100 mb-12 gap-4 fade-in-up" style="animation-delay: 0.2s;">
                <div class="px-5">
                    <p class="text-sm font-black text-gray-800 uppercase tracking-widest flex items-center gap-3">
                        {{ $currentCategory ? $currentCategory : 'Available Items' }}
                        <span class="bg-[#1d5c42]/10 text-[#1d5c42] px-4 py-1.5 rounded-xl text-sm border border-[#1d5c42]/20">{{ $supplies->total() }}</span>
                    </p>
                </div>
                @auth
                <div class="flex flex-wrap sm:flex-nowrap gap-3 w-full sm:w-auto">
                    <a href="{{ route('orders.my_orders') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gray-50 border border-gray-200 text-gray-600 font-black text-[10px] md:text-xs uppercase tracking-widest rounded-[1.25rem] hover:bg-white hover:text-[#1d5c42] hover:border-[#1d5c42]/30 transition-colors shadow-sm flex items-center gap-2 active:scale-95">
                        <svg class="w-4 h-4 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Track Orders
                    </a>
                    <a href="{{ route('cart.view') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gradient-to-tr from-[#1d5c42] to-[#14402e] hover:to-[#0f3022] text-white font-black text-[10px] md:text-xs uppercase tracking-widest rounded-[1.25rem] shadow-lg shadow-[#1d5c42]/30 hover:shadow-xl hover:shadow-[#1d5c42]/40 transition-all flex items-center gap-2 active:scale-95 border border-[#1d5c42]/50">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Checkout Cart
                    </a>
                </div>
                @endauth
            </div>

            {{-- Flash Messages & Rules --}}
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-200 p-5 rounded-[1.5rem] flex items-center justify-between shadow-sm fade-in-up">
                    <div class="flex items-center gap-4">
                        <div class="bg-emerald-500 p-2 rounded-full text-white shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                        <span class="font-bold text-emerald-800 text-sm">{{ session('success') }}</span>
                    </div>
                    <a href="{{ route('cart.view') }}" class="hidden sm:block px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-colors shadow-lg active:scale-95">View Cart</a>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 bg-rose-50 border border-rose-200 p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm fade-in-up">
                    <div class="bg-rose-500 p-2 rounded-full text-white shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                    <span class="font-bold text-rose-800 text-sm">{{ session('error') }}</span>
                </div>
            @endif

            @auth
                @if(auth()->user()->role === 'user' && empty($eligibleBookings))
                    <div class="mb-10 bg-amber-50 border border-amber-200 rounded-[2rem] p-6 shadow-sm flex items-start gap-4 fade-in-up">
                        <div class="flex-shrink-0 bg-amber-100 p-3 rounded-2xl text-amber-600 border border-amber-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-amber-900 uppercase tracking-widest mb-1.5">Action Required</h3>
                            <p class="text-sm text-amber-800/80 font-medium leading-relaxed">You can only order supplies up to 2 hours before your active farm booking starts, and during your stay. You currently have no eligible bookings within this timeframe.</p>
                            <a href="{{ route('explore') }}" class="inline-block mt-4 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-colors shadow-lg shadow-amber-500/20 active:scale-95 border border-amber-600/50">Book a Farm First</a>
                        </div>
                    </div>
                @endif
            @endauth

            {{-- Products Grid --}}
            @if ($supplies->isEmpty())
                <div class="flex flex-col items-center justify-center py-32 bg-white rounded-[3rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 fade-in-up">
                    <div class="w-28 h-28 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-8 border border-gray-100 shadow-inner">
                        <svg class="w-14 h-14 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 mb-3 tracking-tight">{{ $currentCategory ? 'No items in this category' : 'Marketplace is Empty' }}</h3>
                    <p class="text-gray-500 mb-10 font-medium text-lg text-center max-w-lg">Our partners are currently restocking. Please check back soon!</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
                    @foreach ($supplies as $index => $supply)
                        <div class="group bg-white rounded-[2.5rem] shadow-[0_10px_30px_rgba(0,0,0,0.03)] hover:shadow-[0_25px_50px_rgba(29,92,66,0.1)] border border-gray-100 hover:border-[#1d5c42]/30 overflow-hidden transition-all duration-500 flex flex-col h-full fade-in-up-stagger" style="animation-delay: {{ $index * 0.05 }}s">

                            {{-- 📷 Image Section --}}
                            <div class="p-3 pb-0">
                                <div class="relative h-60 overflow-hidden rounded-[1.8rem] bg-gray-50 shadow-inner border border-gray-100">
                                    <img src="{{ $supply->image ? Storage::url($supply->image) : 'https://images.unsplash.com/photo-1589923188900-85dae523342b?q=80&w=2070' }}"
                                         alt="{{ $supply->name }}"
                                         class="w-full h-full object-cover transition-transform duration-[1.5s] ease-out group-hover:scale-110">

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                                    @if($supply->category)
                                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-md text-[#1d5c42] text-[9px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest border border-white shadow-lg">
                                            {{ $supply->category }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- 📝 Content Section --}}
                            <div class="px-6 pt-5 pb-6 flex flex-col flex-1">
                                <h3 class="text-xl font-black text-gray-900 group-hover:text-[#1d5c42] transition-colors leading-tight line-clamp-1 truncate tracking-tight mb-2">
                                    {{ $supply->name }}
                                </h3>

                                <p class="text-xs font-medium text-gray-500 line-clamp-2 mb-5 leading-relaxed h-8">
                                    {{ $supply->description ?? 'Premium quality supply for your farm stay experience.' }}
                                </p>

                                <div class="mb-6 flex items-baseline gap-1 bg-gray-50 w-max px-4 py-2 rounded-[1rem] border border-gray-100 shadow-inner">
                                    <span class="text-2xl font-black text-[#1d5c42] tracking-tighter">{{ number_format($supply->price, 2) }}</span>
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">JOD</span>
                                </div>

                                {{-- 🛒 Action Bar (Add to Cart) --}}
                                <div class="mt-auto border-t border-gray-100 pt-5">
                                    @auth
                                        @if(auth()->user()->role === 'user')
                                            <form action="{{ route('cart.add', $supply->id) }}" method="POST" class="flex flex-col xl:flex-row items-center gap-3">
                                                @csrf
                                                {{-- Sleek Qty Selector --}}
                                                <div class="flex items-center justify-between w-full xl:w-auto bg-white border border-gray-200 rounded-[1.25rem] p-1 shadow-sm h-12" x-data="{ qty: 1 }">
                                                    <button type="button" @click="if(qty > 1) qty--" class="w-10 h-full flex items-center justify-center rounded-[1rem] bg-gray-50 text-gray-600 hover:text-rose-500 hover:bg-rose-50 transition-colors font-black text-lg active:scale-95 focus:outline-none">-</button>
                                                    <input type="number" name="quantity" x-model="qty" readonly class="qty-input">
                                                    <button type="button" @click="if(qty < {{ $supply->stock }}) qty++" class="w-10 h-full flex items-center justify-center rounded-[1rem] bg-gray-50 text-gray-600 hover:text-[#1d5c42] hover:bg-emerald-50 transition-colors font-black text-lg active:scale-95 focus:outline-none">+</button>
                                                </div>

                                                {{-- Add Button --}}
                                                <button type="submit" class="w-full xl:flex-1 h-12 bg-[#1d5c42] hover:bg-[#14402e] text-white font-black rounded-[1.25rem] transition-all duration-300 shadow-[0_10px_20px_rgba(29,92,66,0.2)] hover:shadow-[0_15px_25px_rgba(29,92,66,0.3)] active:scale-95 flex items-center justify-center gap-2 group/btn focus:outline-none border border-[#1d5c42]/50">
                                                    <svg class="w-4 h-4 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                                    <span class="text-[10px] md:text-xs uppercase tracking-widest">Add to Cart</span>
                                                </button>
                                            </form>
                                        @else
                                            <div class="w-full h-12 bg-gray-50 border border-gray-200 text-gray-400 font-black text-[10px] flex items-center justify-center rounded-[1.25rem] uppercase tracking-widest shadow-inner">
                                                Customer Accounts Only
                                            </div>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="flex w-full h-12 items-center justify-center text-center bg-gray-900 hover:bg-black text-white font-black rounded-[1.25rem] transition-all shadow-[0_10px_20px_rgba(0,0,0,0.2)] uppercase tracking-widest text-[10px] active:scale-95 border border-gray-700">
                                            Login to Purchase
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
                <div class="mt-16 flex justify-center fade-in-up delay-300 pb-8">
                    <div class="bg-white px-8 py-4 rounded-full shadow-[0_10px_30px_rgba(0,0,0,0.05)] border border-gray-100">
                        {{ $supplies->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif

        </div>

        {{-- 🌟 Floating Cart Button (Premium Edition) --}}
        @auth
            @php
                $hasItemsInCart = \App\Models\SupplyOrder::where('user_id', auth()->id())->where('status', 'cart')->exists();
            @endphp

            @if(session('success') || $hasItemsInCart)
                <div class="fixed bottom-8 right-8 z-[90] fade-in-up delay-300">
                    <a href="{{ route('cart.view') }}" class="flex items-center gap-3 bg-gradient-to-r from-[#1d5c42] to-[#0f3022] text-white px-6 py-4 rounded-full shadow-[0_20px_50px_rgba(29,92,66,0.6)] hover:shadow-[0_25px_60px_rgba(29,92,66,0.8)] transition-all duration-300 transform hover:-translate-y-2 group border border-white/20">
                        <div class="relative bg-white/10 p-2 rounded-full border border-white/20 shadow-inner group-hover:bg-white/20 transition-colors">
                            <svg class="w-6 h-6 transform group-hover:scale-110 transition-transform text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[9px] font-black w-5 h-5 rounded-full flex items-center justify-center shadow-md animate-pulse border border-white">!</span>
                        </div>
                        <span class="text-[11px] font-black uppercase tracking-widest text-white pr-2">Review Cart</span>
                    </a>
                </div>
            @endif
        @endauth

    </div>
</div>
@endsection
