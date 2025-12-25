@extends('layouts.app')

@section('title', 'Explore Farms')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8 text-center md:text-left">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
            Explore Farms
        </h1>
        <p class="mt-2 text-gray-600">
            Find the perfect farm for your special events and getaways.
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-10">

        <form method="GET" action="{{ route('explore') }}">

            <div class="flex flex-col md:flex-row gap-4 mb-4">

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

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

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

            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="px-8 bg-green-600 text-white font-bold py-3 rounded-xl
                               hover:bg-green-700 shadow-md transition">
                    Search
                </button>
            </div>

        </form>
    </div>

    @if($farms->isEmpty())
        <div class="text-center py-20 text-gray-500">
            No farms found.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach ($farms as $farm)
                <div class="group flex flex-col bg-white rounded-3xl shadow-sm hover:shadow-xl
                            border border-gray-100 overflow-hidden transition duration-300 hover:-translate-y-1">

                    {{-- تغليف الصورة بـ relative لوضع التقييم فوقها --}}
                    <div class="relative h-56 w-full">
                        <img src="{{ $farm->main_image ? asset('storage/'.$farm->main_image) : 'https://via.placeholder.com/800x600' }}"
                             class="h-full w-full object-cover transform group-hover:scale-105 transition duration-700">

                        {{-- ================= بداية كود التقييم (Rating Badge) ================= --}}
                        <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm px-2.5 py-1.5 rounded-xl shadow-md flex items-center gap-1.5">
                            @if($farm->rating && $farm->rating > 0)
                                {{-- أيقونة النجمة الصفراء --}}
                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                                {{-- الرقم --}}
                                <span class="text-sm font-bold text-gray-800 pt-0.5">
                                    {{ number_format($farm->rating, 1) }}
                                </span>
                            @else
                                {{-- في حال لا يوجد تقييم --}}
                                <span class="text-xs font-bold text-green-600 uppercase tracking-wide px-1">New</span>
                            @endif
                        </div>
                        {{-- ================= نهاية كود التقييم ================= --}}

                    </div>

                    <div class="p-6 flex flex-col flex-1">

                        <h2 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-green-700 transition">
                            {{ $farm->name }}
                        </h2>

                        <p class="text-sm text-gray-500 mb-3 flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $farm->location }}
                        </p>

                        <p class="text-gray-600 text-sm mb-6 line-clamp-2">
                            {{ Str::limit($farm->description, 100) }}
                        </p>

                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-50">

                            <div>
                                <span class="text-2xl font-bold text-green-700">
                                    {{ $farm->price_per_night }} JD
                                </span>
                                <span class="text-xs text-gray-400 font-medium">/ 12h</span>
                            </div>

                            <a href="{{ route('farms.show', $farm->id) }}"
                               class="px-5 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl
                                      hover:bg-green-600 hover:shadow-lg hover:shadow-green-200 transition transform active:scale-95">
                                View Details
                            </a>

                        </div>

                    </div>
                </div>
            @endforeach

        </div>

        <div class="mt-16 flex justify-center">
            {{ $farms->withQueryString()->links('pagination.green') }}
        </div>
    @endif

</div>
@endsection
