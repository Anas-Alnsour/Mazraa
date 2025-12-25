@extends('layouts.app')

@section('title', 'Explore Farms')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="mb-8 text-center md:text-left">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
            Explore Farms
        </h1>
        <p class="mt-2 text-gray-600">
            Find the perfect farm for your special events and getaways.
        </p>
    </div>

    <!-- Search & Sort -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-10">

        <form method="GET" action="{{ route('explore') }}">

            <!-- Search Row -->
            <div class="flex flex-col md:flex-row gap-4 mb-4">

                <!-- Name -->
                <div class="flex-grow relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           name="name"
                           value="{{ request('name') }}"
                           placeholder="Search by name..."
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50
                                  focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                </div>

                <!-- Location -->
                <div class="flex-grow relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           name="location"
                           value="{{ request('location') }}"
                           placeholder="Location"
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50
                                  focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                </div>
            </div>

            <!-- Sort Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

                <!-- Sort Price -->
                <div>
                    <select name="price_sort"
                            onchange="this.form.submit()"
                            class="w-full appearance-none px-4 py-3 rounded-xl border border-gray-200 bg-white
                                   focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none cursor-pointer">
                        <option value="">Sort by Price</option>
                        <option value="asc" {{ request('price_sort') === 'asc' ? 'selected' : '' }}>Low → High</option>
                        <option value="desc" {{ request('price_sort') === 'desc' ? 'selected' : '' }}>High → Low</option>
                    </select>
                </div>

                <!-- Sort Rating -->
                <div>
                    <select name="rating_sort"
                            onchange="this.form.submit()"
                            class="w-full appearance-none px-4 py-3 rounded-xl border border-gray-200 bg-white
                                   focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none cursor-pointer">
                        <option value="">Sort by Rating</option>
                        <option value="asc" {{ request('rating_sort') === 'asc' ? 'selected' : '' }}>Low → High</option>
                        <option value="desc" {{ request('rating_sort') === 'desc' ? 'selected' : '' }}>High → Low</option>
                    </select>
                </div>

            </div>

            <!-- Search Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="px-8 bg-green-600 text-white font-bold py-3 rounded-xl
                               hover:bg-green-700 shadow-md transition">
                    Search
                </button>
            </div>

        </form>
    </div>

    <!-- Farms Grid -->
    @if($farms->isEmpty())
        <div class="text-center py-20 text-gray-500">
            No farms found.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach ($farms as $farm)
                <div class="flex flex-col bg-white rounded-3xl shadow-sm hover:shadow-xl
                            border border-gray-100 overflow-hidden transition">

                    <img src="{{ $farm->main_image ? asset('storage/'.$farm->main_image) : 'https://via.placeholder.com/800x600' }}"
                         class="h-56 w-full object-cover">

                    <div class="p-6 flex flex-col flex-1">

                        <h2 class="text-xl font-bold text-gray-900 mb-1">
                            {{ $farm->name }}
                        </h2>

                        <p class="text-sm text-gray-500 mb-3">
                            📍 {{ $farm->location }}
                        </p>

                        <p class="text-gray-600 text-sm mb-6">
                            {{ Str::limit($farm->description, 100) }}
                        </p>

                        <div class="mt-auto flex items-center justify-between">

                            <div>
                                <span class="text-2xl font-bold text-green-700">
                                    {{ $farm->price_per_night }} JD
                                </span>
                                <span class="text-xs text-gray-400">/ 12h</span>
                            </div>

                            <a href="{{ route('farms.show', $farm->id) }}"
                               class="px-5 py-2 bg-gray-900 text-white rounded-xl
                                      hover:bg-green-600 transition">
                                View Details
                            </a>

                        </div>

                    </div>
                </div>
            @endforeach

        </div>

        <!-- Pagination -->
        <div class="mt-16 flex justify-center">
            {{ $farms->withQueryString()->links('pagination.green') }}
        </div>
    @endif

</div>
@endsection
