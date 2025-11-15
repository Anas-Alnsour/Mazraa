@extends('layouts.app')

@section('title', 'Order Supplies')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6 space-y-8">

    <h1 class="text-4xl font-extrabold text-green-800 text-center mb-8">Available Supplies</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-4 rounded-lg shadow-md text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($supplies as $supply)
            <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between hover:shadow-2xl transition-shadow duration-300">
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-green-800 mb-2">{{ $supply->name }}</h2>
                    <p class="text-gray-600 mb-2">{{ $supply->description }}</p>
                    <p class="text-green-700 font-extrabold text-lg mb-2">${{ $supply->price }}</p>
                    <p class="text-gray-500 mb-2">Stock: {{ $supply->stock }}</p>
                </div>

                <a href="{{ route('supplies.show', $supply->id) }}"
                   class="mt-auto px-4 py-2 bg-blue-600 text-white rounded-xl text-center hover:bg-blue-700 shadow-md transition-all duration-300 transform hover:scale-105">
                    Order
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
