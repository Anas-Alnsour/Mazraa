@extends('layouts.app')

@section('title', $supply->name)

@section('content')
    <div class="max-w-3xl my-10 mx-auto py-12 px-8 bg-white rounded-2xl shadow-lg">
        <h1 class="text-3xl font-extrabold text-green-800 mb-6 text-center">{{ $supply->name }}</h1>

        <div class="mt-6 flex flex-col md:flex-row md:items-start md:space-x-6">
            <div class="md:flex-1 md:order-1">
                <p class="text-gray-700 mb-4">{{ $supply->description }}</p>            
                <p class="text-gray-500 mb-6">Available Stock: {{ $supply->stock }}</p>
                <p class="text-green-700 font-extrabold text-lg mb-4">Price: ${{ $supply->price }}</p>

                <form action="{{ route('cart.add', $supply->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">Quantity:</label>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $supply->stock }}"
                            class="w-40 border border-gray-300 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div class="text-center md:text-left ">
                        <button type="submit"
                            class="px-8 py-3 bg-blue-600 text-white rounded-xl shadow-md hover:bg-blue-700 transition-all duration-300 transform hover:scale-105">
                            Add to Cart
                        </button>
                    </div>
                </form>
            </div>

            <div class="md:w-1/3 mt-4 md:mt-0 md:order-2">
                <img src="{{ $supply->image ? Storage::url($supply->image) : 'https://via.placeholder.com/800x400' }}"
                    alt="{{ $supply->name }}"
                    class="w-full h-full rounded-xl object-cover shadow-md hover:shadow-xl transition-all transform hover:scale-105 duration-300 min-h-[200px] max-h-[300px]">
            </div>
        </div>
    </div>
@endsection
