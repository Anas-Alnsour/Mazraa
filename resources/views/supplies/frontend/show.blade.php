@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <a href="{{ route('supplies.market') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold mb-10 transition-colors uppercase tracking-widest text-xs group">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Marketplace
        </a>

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-5 rounded-r-2xl shadow-sm font-bold mb-10 animate-fade-in flex items-center gap-3">
                <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row mb-12">

            <div class="md:w-1/2 bg-gray-50 p-10 flex items-center justify-center relative">
                @if($supply->image)
                    <img src="{{ Storage::url($supply->image) }}" alt="{{ $supply->name }}" class="max-w-full h-auto rounded-[2rem] shadow-md object-cover transform hover:scale-105 transition-transform duration-500">
                @else
                    <div class="h-64 w-64 bg-white rounded-full flex items-center justify-center text-gray-300 shadow-inner">
                        <svg class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                @endif
                <div class="absolute top-6 left-6 bg-white px-4 py-2 rounded-xl text-xs font-black text-gray-800 shadow-sm uppercase tracking-widest flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    In Stock
                </div>
            </div>

            <div class="md:w-1/2 p-10 lg:p-14 flex flex-col justify-center bg-white" x-data="{ quantity: 1, price: {{ $supply->price }}, showConfirm: false }">

                <div class="mb-4 flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">
                        Verified Partner
                    </span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">By {{ $supply->company->name ?? 'Farm Supply Co.' }}</span>
                </div>

                <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6 leading-tight">{{ $supply->name }}</h1>

                <div class="flex items-end gap-3 mb-8 pb-8 border-b border-gray-100">
                    <span class="text-5xl font-black text-blue-600">{{ number_format($supply->price, 2) }}</span>
                    <span class="text-sm font-bold text-gray-400 mb-2 uppercase tracking-widest">JOD / Unit</span>
                </div>

                <div class="text-gray-500 font-medium mb-10 leading-relaxed text-sm">
                    <p>{{ $supply->description ?: 'No detailed description available for this premium product.' }}</p>
                </div>

                <div class="mb-8 p-5 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-5">
                    <div class="h-12 w-12 rounded-xl bg-white flex items-center justify-center shadow-sm text-emerald-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <div>
                        <h4 class="font-black text-emerald-800 text-sm uppercase tracking-widest">Current Availability</h4>
                        <p class="text-xs font-bold text-emerald-600 mt-1">{{ $supply->stock }} Units ready for immediate dispatch</p>
                    </div>
                </div>

                <form action="{{ route('cart.add', $supply->id) }}" method="POST" class="mt-auto relative z-20">
                    @csrf

                    <div class="mb-6 p-4 bg-blue-50/50 rounded-2xl border border-blue-100 flex justify-between items-center">
                        <span class="text-xs font-black text-blue-800 uppercase tracking-widest">Total Value</span>
                        <span class="text-2xl font-black text-blue-600"><span x-text="(quantity * price).toFixed(2)"></span> <span class="text-xs text-blue-400 uppercase">JOD</span></span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-full sm:w-1/3 relative">
                            <label for="quantity" class="absolute -top-2.5 left-4 bg-white px-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">QTY</label>
                            <input type="number" name="quantity" id="quantity" min="1" max="{{ $supply->stock }}" x-model="quantity" required
                                class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 text-xl font-black text-center text-gray-900 transition-all">
                            @error('quantity')
                                <p class="text-red-500 text-[10px] font-bold mt-2 uppercase text-center">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="button" @click="showConfirm = true" class="w-full sm:w-2/3 bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transition-all transform active:scale-95 flex items-center justify-center gap-3 uppercase tracking-widest text-sm">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" /></svg>
                            Add to Cart
                        </button>
                    </div>

                    {{-- Alpine Confirmation Modal --}}
                    <div x-show="showConfirm" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0">

                        <div @click.away="showConfirm = false" class="bg-white rounded-[2.5rem] p-10 max-w-md w-full mx-4 shadow-2xl transform"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-8 scale-95">

                            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6 mx-auto text-blue-500">
                                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>

                            <h3 class="text-2xl font-black text-center text-gray-900 mb-2">Confirm Order</h3>
                            <p class="text-sm font-bold text-center text-gray-500 mb-8 uppercase tracking-widest leading-relaxed">
                                You are about to purchase <br><span class="text-blue-600 font-black text-lg" x-text="quantity"></span> units of <span class="text-gray-900">{{ $supply->name }}</span>
                            </p>

                            <div class="bg-gray-50 rounded-2xl p-5 mb-8 border border-gray-100">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Unit Price</span>
                                    <span class="text-sm font-black text-gray-900">{{ number_format($supply->price, 2) }} JOD</span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Total to pay</span>
                                    <span class="text-xl font-black text-blue-600" x-text="(quantity * price).toFixed(2) + ' JOD'"></span>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <button type="button" @click="showConfirm = false" class="flex-1 bg-white border-2 border-gray-100 hover:bg-gray-50 text-gray-700 font-black py-4 rounded-2xl transition-all transform active:scale-95 uppercase tracking-widest text-[10px]">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all transform active:scale-95 uppercase tracking-widest text-[10px]">
                                    Confirm Purchase
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        {{-- ⭐️ Company Reviews Section ⭐️ --}}
        {{-- We review the vendor supplying this item --}}
        <x-reviews-section
            :reviews="$supply->company->receivedReviews"
            :reviewable-id="$supply->company->id"
            reviewable-type="supply_company"
            :average-rating="$supply->company->company_rating"
        />

    </div>
</div>
@endsection
