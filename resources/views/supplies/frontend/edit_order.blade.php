@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <a href="{{ route('supplies.my_orders') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold mb-10 transition-colors uppercase tracking-widest text-xs group">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Orders
        </a>

        <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden p-10 lg:p-14 relative">

            <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-50 rounded-full opacity-50 blur-3xl pointer-events-none"></div>

            <div class="text-center mb-10 relative z-10">
                <h1 class="text-3xl font-black text-gray-900 mb-2 tracking-tight">Update Quantity</h1>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Modify pending order <span class="text-blue-600 bg-blue-50 px-2 py-1 rounded-md">{{ $order->order_id ?? 'ORD-'.$order->id }}</span></p>
            </div>

            <div class="flex items-center gap-6 p-6 bg-gray-50 rounded-[2rem] border border-gray-100 mb-10 relative z-10">
                @if($order->supply && $order->supply->image)
                    <img src="{{ Storage::url($order->supply->image) }}" alt="Product" class="h-24 w-24 rounded-2xl object-cover shadow-sm border border-white">
                @endif
                <div>
                    <h3 class="text-xl font-black text-gray-900 mb-1">{{ $order->supply->name ?? 'Product' }}</h3>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest"><span class="text-blue-600 font-black">{{ number_format($order->supply->price, 2) }}</span> JOD / unit</p>
                </div>
            </div>

            <form action="{{ route('supplies.update_order', $order->id) }}" method="POST" class="relative z-10">
                @csrf
                @method('PUT')

                <div class="mb-10 text-center">
                    <label for="quantity" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Required Quantity</label>
                    <div class="max-w-[200px] mx-auto">
                        <input type="number" name="quantity" id="quantity" min="1" max="{{ $order->supply->stock + $order->quantity }}" value="{{ old('quantity', $order->quantity) }}" required
                            class="block w-full px-6 py-5 bg-white border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 text-3xl font-black text-center text-gray-900 transition-all shadow-inner">
                    </div>
                    @error('quantity')
                        <p class="text-red-500 text-[10px] font-bold mt-3 uppercase tracking-widest">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('supplies.my_orders') }}" class="w-full sm:w-auto inline-flex justify-center items-center bg-white border-2 border-gray-100 text-gray-700 hover:bg-gray-50 font-black py-4 px-10 rounded-2xl transition-all shadow-sm uppercase tracking-widest text-xs transform active:scale-95">
                        Cancel
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-10 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 uppercase tracking-widest text-xs transform active:scale-95">
                        Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
