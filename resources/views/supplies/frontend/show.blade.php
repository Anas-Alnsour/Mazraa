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

{{-- 💡 Alpine Data Scope for Cart Modal --}}
<div x-data="{
    showModal: false,
    selectedItem: null,
    quantity: 1,
    selectedBooking: '',

    openModal(supply) {
        this.selectedItem = supply;
        this.quantity = 1;
        this.selectedBooking = '';
        this.showModal = true;
    },
    closeModal() {
        this.showModal = false;
        setTimeout(() => { this.selectedItem = null; }, 300);
    },
    increment() {
        if(this.quantity < this.selectedItem.stock) this.quantity++;
    },
    decrement() {
        if(this.quantity > 1) this.quantity--;
    }
}">

    <div class="bg-[#f8fafc] min-h-screen py-32 selection:bg-[#1d5c42] selection:text-white relative">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 🌟 Premium Breadcrumbs / Back Navigation --}}
            <div class="flex items-center flex-wrap gap-2 mb-10 fade-in-up">
                <a href="{{ route('supplies.market') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-[#1d5c42] font-black transition-colors uppercase tracking-widest text-[10px] group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Marketplace
                </a>

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
                        <img src="{{ asset('storage/supplies/' . $supply->image) }}" alt="{{ $supply->name }}" class="w-full h-auto rounded-[2rem] shadow-lg object-cover transform hover:scale-105 transition-transform duration-700 ease-out">
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
                <div class="md:w-1/2 p-10 lg:p-14 flex flex-col justify-center bg-white">

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

                    <div class="mt-auto relative z-20 flex justify-end">
                        @auth
                            @php
                                $hasEligibleBookings = false;
                                if (auth()->user()->role === 'user') {
                                    $userBookings = \App\Models\FarmBooking::where('user_id', auth()->id())
                                        ->whereIn('status', ['confirmed', 'completed'])
                                        ->get();
                                    foreach ($userBookings as $b) {
                                        if ($b->isWithinSupplyCheckoutWindow()) {
                                            $hasEligibleBookings = true;
                                            break;
                                        }
                                    }
                                }
                            @endphp

                            @if(auth()->user()->role === 'user' && $hasEligibleBookings)
                                <button @click="openModal({{ $supply->toJson() }})" class="w-full sm:w-2/3 bg-[#1d5c42] hover:bg-[#154230] text-white font-black py-4 px-8 rounded-2xl shadow-lg hover:shadow-[0_10px_25px_rgba(29,92,66,0.3)] transition-all transform active:scale-95 flex items-center justify-center gap-3 uppercase tracking-widest text-[10px] md:text-xs focus:outline-none">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" /></svg>
                                    Add to Cart
                                </button>
                            @elseif(auth()->user()->role === 'user' && !$hasEligibleBookings)
                                <button disabled class="w-full sm:w-2/3 bg-gray-100 text-gray-400 font-black py-4 px-8 rounded-2xl uppercase tracking-widest text-[10px] md:text-xs cursor-not-allowed">
                                    No Eligible Booking
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="w-full sm:w-2/3 text-center bg-[#1d5c42] hover:bg-[#154230] text-white font-black py-4 px-8 rounded-2xl shadow-lg transition-all transform active:scale-95 uppercase tracking-widest text-[10px] md:text-xs">
                                    Log in to Order
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full sm:w-2/3 text-center bg-[#1d5c42] hover:bg-[#154230] text-white font-black py-4 px-8 rounded-2xl shadow-lg transition-all transform active:scale-95 uppercase tracking-widest text-[10px] md:text-xs">
                                Log in to Order
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- ⭐️ Product Reviews Section ⭐️ --}}
            <div class="fade-in-up delay-200">
                <x-reviews-section
                    :reviews="$supply->reviews"
                    :reviewable-id="$supply->id"
                    reviewable-type="supply"
                    :average-rating="$supply->average_rating"
                />
            </div>

        </div>

        {{-- 🌟 Floating Cart Button --}}
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

        {{-- 💡 Add to Cart Modal (Alpine) 💡 --}}
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100">

                    <template x-if="selectedItem">
                        <form :action="`/cart/add/${selectedItem.id}`" method="POST">
                            @csrf

                            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                <h3 class="text-lg font-bold text-gray-900" x-text="selectedItem.name"></h3>
                                <button type="button" @click="closeModal" class="text-gray-400 hover:text-gray-500 bg-white rounded-full p-1 shadow-sm border border-gray-200 focus:outline-none">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="p-6 space-y-6">
                                <div>
                                    <label for="booking_id" class="block text-sm font-bold text-gray-700 mb-2">Select Delivery Destination <span class="text-red-500">*</span></label>
                                    @php
                                        $modalEligibleBookings = [];
                                        if (auth()->check() && auth()->user()->role === 'user') {
                                            $modalUserBookings = \App\Models\FarmBooking::with('farm')->where('user_id', auth()->id())->whereIn('status', ['confirmed', 'completed'])->get();
                                            foreach ($modalUserBookings as $b) {
                                                if ($b->isWithinSupplyCheckoutWindow()) {
                                                    $modalEligibleBookings[] = $b;
                                                }
                                            }
                                        }
                                    @endphp
                                    <select id="booking_id" name="booking_id" x-model="selectedBooking" class="w-full rounded-2xl border-gray-200 focus:border-[#1d5c42] focus:ring-[#1d5c42] px-4 py-3 bg-gray-50 hover:bg-white transition-colors font-medium text-gray-900 appearance-none" required>
                                        <option value="" disabled>Choose your active booking</option>
                                        @foreach($modalEligibleBookings as $booking)
                                            <option value="{{ $booking->id }}">
                                                {{ $booking->farm->name }} ({{ \Carbon\Carbon::parse($booking->start_time)->format('M d, H:i') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 font-medium mt-2">Drivers will deliver your order directly to this farm.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Quantity</label>
                                    <div class="flex items-center gap-4 bg-gray-50 p-2 rounded-2xl border border-gray-200 w-max shadow-inner">
                                        <button type="button" @click="decrement" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-gray-600 shadow-sm hover:bg-gray-100 font-black transition-colors focus:outline-none">-</button>
                                        <input type="number" name="quantity" x-model="quantity" readonly class="w-12 text-center bg-transparent border-none focus:ring-0 text-lg font-black text-gray-900 p-0 pointer-events-none">
                                        <button type="button" @click="increment" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-gray-600 shadow-sm hover:bg-gray-100 font-black transition-colors focus:outline-none">+</button>
                                    </div>
                                </div>

                                <div class="bg-emerald-50 rounded-2xl p-4 flex justify-between items-center border border-emerald-100">
                                    <span class="text-sm font-bold text-emerald-900 uppercase tracking-widest">Total Price</span>
                                    <span class="text-xl font-black text-[#1d5c42]" x-text="(selectedItem.price * quantity).toFixed(2) + ' JOD'"></span>
                                </div>
                            </div>

                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex gap-3">
                                <button type="button" @click="closeModal" class="flex-1 px-6 py-3.5 rounded-xl border border-gray-200 bg-white text-gray-700 font-black text-[10px] sm:text-xs tracking-widest uppercase hover:bg-gray-50 transition-colors focus:outline-none">Cancel</button>
                                <button type="submit" :disabled="!selectedBooking" :class="!selectedBooking ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[#154230] shadow-lg shadow-[#1d5c42]/30 active:scale-95'" class="flex-1 px-6 py-3.5 rounded-xl bg-[#1d5c42] text-white font-black text-[10px] sm:text-xs tracking-widest uppercase transition-all transform focus:outline-none">Confirm Order</button>
                            </div>
                        </form>
                    </template>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
