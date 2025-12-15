@extends('layouts.app')

@section('title', 'Explore Farms')

@section('content')
    <div class="max-w-7xl mx-auto p-6">

        <!-- Search & Filters -->
        <form method="GET" action="{{ route('explore') }}" class="flex flex-wrap items-center gap-3 md:gap-4 mb-6">

            <input
                type="text"
                name="name"
                placeholder="Search by name"
                value="{{ request('name') }}"
                class="h-10 w-full sm:w-56 md:w-64 border rounded px-3"
            >

            <input
                type="text"
                name="location"
                placeholder="Location"
                value="{{ request('location') }}"
                class="h-10 w-full sm:w-48 md:w-56 border rounded px-3"
            >

            <select
                name="price_sort"
                class="h-10 w-36 md:w-40 border rounded px-3 pr-10"
            >
                <option value="">Sort by price</option>
                <option value="asc"  {{ request('price_sort')  === 'asc'  ? 'selected' : '' }}>Low → High</option>
                <option value="desc" {{ request('price_sort')  === 'desc' ? 'selected' : '' }}>High → Low</option>
            </select>

            <select
                name="rating_sort"
                class="h-10 w-36 md:w-40 border rounded px-3 pr-10"
            >
                <option value="">Sort by rating</option>
                <option value="asc"  {{ request('rating_sort') === 'asc'  ? 'selected' : '' }}>Low → High</option>
                <option value="desc" {{ request('rating_sort') === 'desc' ? 'selected' : '' }}>High → Low</option>
            </select>

            <button
                type="submit"
                class="h-10 px-5 bg-green-600 text-white rounded hover:bg-green-700"
            >
                Search
            </button>
        </form>

        <!-- Farms Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($farms as $farm)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                    <div class="relative">
                        <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : 'https://via.placeholder.com/800x400' }}"
                            alt="{{ $farm->name }}"
                            class="w-full h-52 sm:h-56 md:h-48 object-cover">
                        <div class="absolute top-3 right-3 bg-white bg-opacity-80 px-2 py-1 rounded-full text-sm font-semibold text-gray-800">
                            ⭐ {{ $farm->rating }}
                        </div>
                    </div>
                    <div class="p-5">
                        <h2 class="text-2xl font-bold text-gray-900 mb-1 hover:text-green-600 transition">{{ $farm->name }}</h2>
                        <p class="text-gray-500 text-sm mb-3">{{ $farm->location }}</p>
                        <p class="text-gray-700 mb-4">{{ Str::limit($farm->description, 120) }}</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-semibold text-gray-900">${{ $farm->price_per_night }} / 12h</span>
                            <span class="text-green-600 font-bold">⭐ {{ $farm->rating }}</span>
                        </div>
                        <a href="{{ route('farms.show', $farm->id) }}"
                            class="block text-center bg-green-600 text-white font-semibold py-2 rounded-xl hover:bg-green-700 transition">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
