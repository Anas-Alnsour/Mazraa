@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-16 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-16 relative">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight mb-4">Farm Supplies <span class="text-blue-600">Marketplace</span></h1>
            <p class="text-lg font-bold text-gray-400 max-w-2xl mx-auto uppercase tracking-widest mb-8">Premium supplies delivered directly to your farm by our B2B partners.</p>

            @auth
            <div class="flex justify-center mt-2 animate-fade-in">
                <a href="{{ route('supplies.my_orders') }}" class="inline-flex items-center gap-2 bg-white border-2 border-gray-200 text-gray-800 hover:text-blue-600 hover:border-blue-300 font-black py-3.5 px-8 rounded-2xl transition-all shadow-sm text-xs uppercase tracking-widest transform hover:-translate-y-1">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Track My Active Orders
                </a>
            </div>
            @endauth
        </div>

        @if(session('success'))
            <div class="max-w-3xl mx-auto bg-green-50 border-l-4 border-green-500 text-green-800 p-5 rounded-r-2xl shadow-sm font-bold mb-10 animate-fade-in flex items-center gap-3">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($supplies as $supply)
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 group flex flex-col transform hover:-translate-y-1">

                    <div class="relative h-60 bg-gray-100 overflow-hidden">
                        @if($supply->image)
                            <img src="{{ Storage::url($supply->image) }}" alt="{{ $supply->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        @endif

                        <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-md px-4 py-2 rounded-2xl text-sm font-black text-blue-600 shadow-lg border border-white/20">
                            {{ number_format($supply->price, 2) }} <span class="text-[10px] text-gray-400 uppercase tracking-widest">JOD</span>
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-black text-gray-900 line-clamp-1 mb-2">{{ $supply->name }}</h3>

                        <p class="text-sm font-bold text-gray-400 line-clamp-2 mb-6 flex-1">
                            {{ $supply->description ?: 'No description provided.' }}
                        </p>

                        <div class="flex items-center justify-between mt-auto pb-4 border-b border-gray-100 mb-4">
                            <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ $supply->stock }} Available
                            </div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest truncate max-w-[100px]" title="{{ $supply->company->name ?? 'Vendor' }}">
                                {{ $supply->company->name ?? 'Vendor' }}
                            </span>
                        </div>

<form action="{{ route('cart.add', $supply->id) }}" method="POST" class="flex items-center gap-2 w-full mb-3" x-data="{ qty: 1, max: {{ $supply->stock }} }">
    @csrf
    <input type="hidden" name="quantity" x-model="qty">

    <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl w-24 p-1 shadow-inner">
        <button type="button" @click="if(qty > 1) qty--" class="w-7 h-7 flex items-center justify-center rounded-lg bg-white text-gray-500 hover:text-red-500 hover:bg-red-50 shadow-sm border border-gray-100 transition-colors active:scale-95 focus:outline-none">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" /></svg>
        </button>

        <span class="text-xs font-black text-gray-900 w-6 text-center select-none" x-text="qty"></span>

        <button type="button" @click="if(qty < max) qty++" class="w-7 h-7 flex items-center justify-center rounded-lg bg-white text-gray-500 hover:text-blue-600 hover:bg-blue-50 shadow-sm border border-gray-100 transition-colors active:scale-95 focus:outline-none">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
        </button>
    </div>

    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-2.5 px-2 rounded-xl transition-all duration-200 uppercase tracking-widest text-[10px] shadow-sm transform active:scale-95 flex items-center justify-center gap-1">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        Add
    </button>
</form>

                        <a href="{{ route('supplies.show', $supply->id) }}" class="block w-full text-center text-gray-400 hover:text-blue-600 font-black py-2 rounded-xl transition-all duration-200 uppercase tracking-widest text-[10px]">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center bg-white rounded-[3rem] border border-gray-100 shadow-sm">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 mb-6">
                        <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">Marketplace is Empty</h3>
                    <p class="text-sm font-bold text-gray-400 max-w-md mx-auto">Our B2B vendors are currently restocking their supplies. Please check back soon!</p>
                </div>
            @endforelse
        </div>

        @if($supplies->hasPages())
            <div class="mt-16 flex justify-center">
                {{ $supplies->links() }}
            </div>
        @endif

    </div>

    @auth
    <div class="fixed bottom-8 right-8 z-50">
        <a href="{{ route('cart.view') }}" class="flex items-center gap-3 bg-gray-900 hover:bg-black text-white px-6 py-4 rounded-full shadow-2xl hover:shadow-[0_10px_40px_rgba(0,0,0,0.4)] transition-all transform hover:-translate-y-1 group border border-gray-800">
            <div class="relative">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="absolute -top-2 -right-2 bg-emerald-500 text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center border-2 border-gray-900 animate-pulse">✓</span>
            </div>
            <span class="text-xs font-black uppercase tracking-widest group-hover:text-emerald-400 transition-colors">Go to Checkout</span>
        </a>
    </div>
    @endauth

</div>
@endsection
