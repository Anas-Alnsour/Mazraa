@extends('layouts.app')

@section('title', $supply->name)

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6 bg-white rounded-2xl shadow-lg">
    <h1 class="text-3xl font-extrabold text-green-800 mb-6 text-center">{{ $supply->name }}</h1>

    <p class="text-gray-700 mb-4">{{ $supply->description }}</p>
    <p class="text-green-700 font-extrabold text-lg mb-4">Price: ${{ $supply->price }}</p>
    <p class="text-gray-500 mb-6">Available Stock: {{ $supply->stock }}</p>

    <form action="{{ route('supplies.order', $supply->id) }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-2 font-semibold text-gray-700">Quantity:</label>
            <input type="number" name="quantity" value="1" min="1" max="{{ $supply->stock }}"
                   class="w-full border border-gray-300 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </div>
        <div class="text-center">
            <button type="submit"
                    class="px-8 py-3 bg-blue-600 text-white rounded-xl shadow-md hover:bg-blue-700 transition-all duration-300 transform hover:scale-105">
                Order Now
            </button>
        </div>
    </form>
</div>
@endsection
