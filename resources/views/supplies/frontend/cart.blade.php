@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-12">
            <h1 class="text-4xl font-black text-gray-900 tracking-tight">Shopping Cart</h1>
            <p class="text-sm font-bold text-gray-400 mt-2 uppercase tracking-widest">Review your items before checkout</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-5 rounded-r-2xl shadow-sm font-bold mb-8 flex items-center gap-3">
                <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-5 rounded-r-2xl shadow-sm font-bold mb-8 flex items-center gap-3">
                <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($cartItems->count() > 0)
            <div class="flex flex-col lg:flex-row gap-10">
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                        <ul class="divide-y divide-gray-100">
                            @foreach($cartItems as $item)
                                <li class="p-8 flex items-center sm:items-start gap-6 hover:bg-gray-50/50 transition-colors">
                                    <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-2xl border border-gray-100 bg-gray-50 shadow-sm">
                                        @if($item->supply && $item->supply->image)
                                            <img src="{{ Storage::url($item->supply->image) }}" alt="{{ $item->supply->name }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-gray-300">
                                                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 flex flex-col sm:flex-row justify-between h-full py-1">
                                        <div class="flex flex-col justify-between">
                                            <div>
                                                <h3 class="text-xl font-black text-gray-900">{{ $item->supply->name ?? 'Product Unavailable' }}</h3>
                                                <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">By {{ $item->supply->company->name ?? 'Vendor' }}</p>
                                            </div>

<div class="mt-4 flex items-center gap-3">
    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2" x-data="{ qty: {{ $item->quantity }}, max: {{ $item->supply->stock }} }">
        @csrf
        @method('PUT')
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">QTY:</span>

        <input type="hidden" name="quantity" x-model="qty">

        <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg w-20 p-1 shadow-inner">
            <button type="button" @click="if(qty > 1) qty--" class="w-5 h-5 flex items-center justify-center rounded bg-white text-gray-500 hover:text-red-500 hover:bg-red-50 shadow-sm border border-gray-100 transition-colors focus:outline-none active:scale-95">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" /></svg>
            </button>

            <span class="text-xs font-black text-gray-900 w-4 text-center select-none" x-text="qty"></span>

            <button type="button" @click="if(qty < max) qty++" class="w-5 h-5 flex items-center justify-center rounded bg-white text-gray-500 hover:text-blue-600 hover:bg-blue-50 shadow-sm border border-gray-100 transition-colors focus:outline-none active:scale-95">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
            </button>
        </div>

        <button type="submit" class="text-[10px] font-black text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg border border-blue-100 uppercase tracking-widest transition-colors active:scale-95 shadow-sm">Update</button>
    </form>
    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-l border-gray-200 pl-3">{{ number_format($item->supply->price, 2) }} JOD / unit</span>
</div>
                                        </div>

                                        <div class="mt-4 sm:mt-0 flex flex-col sm:items-end justify-between">
                                            <p class="text-2xl font-black text-gray-900">{{ number_format($item->total_price, 2) }} <span class="text-[10px] text-gray-400 uppercase tracking-widest">JOD</span></p>
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="mt-4">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-xl transition-colors border border-red-100 active:scale-95">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="lg:w-1/3">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sticky top-28">
                        <h2 class="text-xl font-black text-gray-900 mb-8 border-b border-gray-100 pb-4">Order Summary</h2>

                        <div class="flex justify-between items-center text-sm font-bold text-gray-500 mb-4">
                            <span class="uppercase tracking-widest text-[10px]">Subtotal ({{ $cartItems->count() }} items)</span>
                            <span class="text-gray-900">{{ number_format($cartTotal, 2) }} JOD</span>
                        </div>

                        <div class="flex justify-between items-center text-sm font-bold text-gray-500 mb-8 border-b border-gray-100 pb-8">
                            <span class="uppercase tracking-widest text-[10px]">Platform Fee</span>
                            <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100 uppercase tracking-widest">Free for buyers</span>
                        </div>

                        <div class="flex justify-between items-end mb-10">
                            <span class="text-sm font-black text-gray-400 uppercase tracking-widest mb-1">Grand Total</span>
                            <span class="text-4xl font-black text-blue-600">{{ number_format($cartTotal, 2) }} <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">JOD</span></span>
                        </div>

                        <form action="{{ route('cart.place_order') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-5 px-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 transform active:scale-95 flex items-center justify-center gap-3 uppercase tracking-widest text-sm">
                                Confirm & Checkout
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            </button>
                        </form>

                        <a href="{{ route('supplies.market') }}" class="mt-6 block text-center text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 transition-colors">
                            ← Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 p-24 text-center">
                <div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-gray-50 mb-8 border border-gray-100">
                    <svg class="h-14 w-14 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <h2 class="text-3xl font-black text-gray-900 mb-3">Your Cart is Empty</h2>
                <p class="text-sm font-bold text-gray-400 mb-10 max-w-md mx-auto uppercase tracking-widest">Looks like you haven't added any premium farm supplies yet.</p>
                <a href="{{ route('supplies.market') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-10 rounded-2xl shadow-lg hover:shadow-xl transition-all transform active:scale-95 uppercase tracking-widest text-xs">
                    Browse Marketplace
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
