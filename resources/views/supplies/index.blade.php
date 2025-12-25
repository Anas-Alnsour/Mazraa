@extends('layouts.app')

@section('title', 'Order Supplies')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-12 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-green-900 tracking-tight">Farm Supplies</h1>
                <p class="mt-2 text-gray-600">Premium quality feeds, seeds, and tools for your farm.</p>
            </div>

            <a href="{{ route('cart.view') }}" class="group relative inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition-all duration-300 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                View Cart
            </a>
        </div>

        @if (session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl flex items-center gap-3 shadow-sm animate-fade-in-up">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach ($supplies as $info)
                <div class="group bg-white rounded-[1.5rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full overflow-hidden hover:-translate-y-1">

                    <div class="relative h-56 overflow-hidden bg-gray-100">
                        <img src="{{ $info->image ? Storage::url($info->image) : 'https://via.placeholder.com/800x600?text=No+Image' }}"
                             alt="{{ $info->name }}"
                             class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">

                        <div class="absolute top-3 left-3">
                            @if($info->stock > 0)
                                <span class="px-3 py-1 bg-green-500/90 backdrop-blur text-white text-xs font-bold uppercase rounded-lg shadow-sm">
                                    In Stock: {{ $info->stock }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-500/90 backdrop-blur text-white text-xs font-bold uppercase rounded-lg shadow-sm">
                                    Out of Stock
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6 flex flex-col flex-1">
                        <div class="mb-4">
                            <h2 class="text-lg font-bold text-gray-900 leading-tight mb-2 group-hover:text-green-700 transition">
                                {{ $info->name }}
                            </h2>
                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                                {{ \Illuminate\Support\Str::limit($info->description, 100) }}
                            </p>
                        </div>

                        <div class="mt-auto flex items-end justify-between mb-5">
                            <div>
                                <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Price</span>
                                <div class="text-2xl font-extrabold text-green-700">
                                    {{ $info->price }} <span class="text-sm font-bold text-green-600">JD</span>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('cart.add', $info->id) }}" method="POST"
                              class="border-t border-gray-100 pt-5"
                              x-data="{ qty: 1, max: {{ $info->stock }} }">
                            @csrf

                            <div class="flex gap-3">
                                <div class="flex items-center border border-gray-200 rounded-xl bg-gray-50 h-11">
                                    <button type="button" @click="qty > 1 ? qty-- : null" class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-red-500 transition disabled:opacity-50">
                                        -
                                    </button>
                                    <input type="number" name="quantity" x-model="qty" readonly
                                           class="w-8 bg-transparent text-center font-bold text-gray-800 text-sm focus:outline-none p-0 border-none h-full">
                                    <button type="button" @click="qty < max ? qty++ : null" class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-green-500 transition disabled:opacity-50">
                                        +
                                    </button>
                                </div>

                                <button type="submit"
                                        @if($info->stock <= 0) disabled @endif
                                        class="flex-1 bg-gray-900 text-white font-bold text-sm rounded-xl hover:bg-green-600 disabled:bg-gray-300 disabled:cursor-not-allowed transition-all duration-300 shadow-md flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    Add
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($supplies->hasPages())
            <div class="mt-16 flex justify-center pb-8">
                <div class="bg-white shadow-sm rounded-2xl px-6 py-3 border border-gray-100">
                    {{ $supplies->withQueryString()->links('pagination.green') }}
                </div>
            </div>
        @endif

    </div>
@endsection
