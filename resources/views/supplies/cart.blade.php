@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Shopping Cart</h1>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl flex items-center gap-3 shadow-sm animate-fade-in-up">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl flex items-center gap-3 shadow-sm animate-fade-in-up">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if($cartOrders->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border border-gray-100 shadow-sm text-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Your cart is empty</h2>
                <p class="text-gray-500 mb-8 max-w-md">Looks like you haven't added any supplies to your cart yet.</p>
                <a href="{{ route('supplies.index') }}" class="px-8 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 shadow-lg shadow-green-200 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-8">

                <div class="lg:w-2/3 space-y-6">
                    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 space-y-6">
                            @foreach($cartOrders as $order)
                                <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-gray-100 last:border-0 last:pb-0">

                                    <div class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden">
                                        <img src="{{ $order->supply->image ? Storage::url($order->supply->image) : 'https://via.placeholder.com/150' }}"
                                             alt="{{ $order->supply->name }}"
                                             class="w-full h-full object-cover">
                                    </div>

                                    <div class="flex-1 text-center sm:text-left">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $order->supply->name }}</h3>
                                        <p class="text-sm text-gray-500">Price: <span class="font-semibold text-gray-700">{{ $order->supply->price }} JD</span></p>
                                    </div>

                                    <div class="flex flex-col items-center sm:items-end gap-3">

                                        <form action="{{ route('cart.update', $order->id) }}" method="POST" class="flex items-center">
                                            @csrf
                                            @method('PUT')
                                            <div class="flex items-center border border-gray-200 rounded-lg bg-gray-50">
                                                <input type="number" name="quantity" min="1" max="{{ $order->supply->stock }}" value="{{ $order->quantity }}"
                                                       class="w-16 p-2 bg-transparent text-center text-sm font-bold focus:outline-none border-none">
                                                <button type="submit" class="p-2 text-blue-600 hover:bg-blue-100 transition rounded-r-lg" title="Update Quantity">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                </button>
                                            </div>
                                        </form>

                                        <div class="font-extrabold text-green-700 text-lg">
                                            {{ $order->total_price }} JD
                                        </div>

                                        <form action="{{ route('cart.remove', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium flex items-center gap-1 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if ($cartOrders->hasPages())
                        <div class="flex justify-center">
                            <div class="bg-white shadow-sm rounded-xl px-4 py-2 border border-gray-100">
                                {{ $cartOrders->links('pagination.green') }}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="lg:w-1/3">
                    <div class="bg-white rounded-[2rem] shadow-lg border border-gray-100 p-6 sticky top-24">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h3>

                        <div class="space-y-4 border-b border-gray-100 pb-6 mb-6">
                            <div class="flex justify-between items-center text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-semibold">{{ $total }} JD</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-600">
                                <span>Shipping Estimate</span>
                                <span class="text-green-600 font-medium">Free</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-8">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-2xl font-extrabold text-green-700">{{ $total }} JD</span>
                        </div>

                        <form action="{{ route('cart.place_order') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-4 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-green-600 hover:shadow-green-200 transition-all duration-300 transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
                                <span>Checkout</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </button>
                        </form>

                        <div class="mt-6 text-center">
                            <a href="{{ route('supplies.index') }}" class="text-sm text-gray-500 hover:text-green-600 font-medium underline transition">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
@endsection
