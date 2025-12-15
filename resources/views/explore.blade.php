@extends('layouts.app')

@section('title', 'Explore Farms')

@section('content')
    <div class="max-w-7xl mx-auto p-6">

        <!-- Search & Filters -->
        <!--<form method="GET" action="{{ route('explore') }}" class="flex flex-wrap gap-4 mb-6">
            <input type="text" name="name" placeholder="Search by name" value="{{ request('name') }}"
                class="border rounded px-3 py-2">
            <input type="text" name="location" placeholder="Location" value="{{ request('location') }}"
                class="border rounded px-3 py-2">
            <select name="price_sort" class="border rounded px-3 py-2">
                <option value="">Sort by price</option>
                <option value="asc" {{ request('price_sort') == 'asc' ? 'selected' : '' }}>Low to High</option>
                <option value="desc" {{ request('price_sort') == 'desc' ? 'selected' : '' }}>High to Low</option>
            </select>
            <select name="rating_sort" class="border rounded px-3 py-2">
                <option value="">Sort by rating</option>
                <option value="asc" {{ request('rating_sort') == 'asc' ? 'selected' : '' }}>Low to High</option>
                <option value="desc" {{ request('rating_sort') == 'desc' ? 'selected' : '' }}>High to Low</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Search</button>
        </form>-->
        {{-- Search & Filters --}}
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($farms as $farm)
                <div class="bg-white shadow rounded overflow-hidden">
                    <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : 'https://via.placeholder.com/800x400' }}"
                        alt="{{ $farm->name }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-xl font-bold">{{ $farm->name }}</h2>
                        <p class="text-gray-600">{{ $farm->location }}</p>
                        <p class="text-gray-700 mt-2">{{ Str::limit($farm->description, 100) }}</p>
                        <div class="flex justify-between mt-3 items-center">
                            <span class="font-semibold">${{ $farm->price_per_night }} / 12h</span>
                            <span class="text-yellow-500 font-bold">⭐ {{ $farm->rating }}</span>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('farms.show', $farm->id) }}"
                                class="block text-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">View
                                Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
