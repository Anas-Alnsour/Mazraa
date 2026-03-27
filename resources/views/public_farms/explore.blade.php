@extends('layouts.app')

@section('title', 'Explore Farms & Escapes')

@section('content')

<style>
    /* Advanced Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }
    .animate-float-slow { animation: float 6s ease-in-out infinite; }

    /* Elegant Search Dock with Smooth Shadow */
    .search-dock {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0,0,0,0.02);
    }

    /* Input Reset & Enhancements */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Card Hover Effects */
    .farm-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .farm-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -5px rgba(29, 92, 66, 0.1);
        border-color: rgba(29, 92, 66, 0.15);
    }
    .image-zoom { transition: transform 1.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .farm-card:hover .image-zoom { transform: scale(1.05); }
</style>

<div class="bg-[#f8fafc] min-h-screen pb-24 font-sans selection:bg-[#1d5c42] selection:text-white">

    {{-- ==========================================
         1. PREMIUM HERO SECTION
         ========================================== --}}
    <div class="relative w-full h-[70vh] min-h-[600px] flex items-center justify-center bg-[#020617] overflow-hidden">
        {{-- High Quality Background Image (Opacity increased to 75 for better visibility) --}}
        <img src="{{ asset('backgrounds/home.JPG') }}" alt="Explore Escapes"
             class="absolute inset-0 w-full h-full object-cover opacity-75 scale-105 animate-[pulse_20s_ease-in-out_infinite]">

        {{-- Smooth Gradient Overlay for perfect text contrast (Lightened to show image more) --}}
        <div class="absolute inset-0 bg-gradient-to-b from-[#020617]/80 via-[#020617]/40 to-[#f8fafc]"></div>

        {{-- Glowing Orbs for Depth (Brand Colors) --}}
        <div class="absolute top-1/4 left-1/4 w-[40rem] h-[40rem] bg-[#1d5c42]/30 rounded-full blur-[120px] animate-float-slow pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[30rem] h-[30rem] bg-[#c2a265]/20 rounded-full blur-[100px] animate-float-slow pointer-events-none" style="animation-delay: 2s;"></div>

        {{-- Main Hero Text Container --}}
        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto pb-20">
            <div class="animate-[fade-in-up_0.8s_ease-out]">
                <div class="inline-flex items-center gap-2 py-2 px-5 rounded-full bg-black/30 border border-white/20 text-[#c2a265] text-xs font-bold tracking-[0.2em] uppercase backdrop-blur-md mb-8 shadow-xl">
                    <span class="w-2 h-2 rounded-full bg-[#c2a265] animate-ping absolute"></span>
                    <span class="w-2 h-2 rounded-full bg-[#c2a265] relative"></span>
                    Curated Experiences
                </div>
            </div>

            {{-- Title with crisp contrast --}}
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-6 leading-tight animate-[fade-in-up_1s_ease-out_0.2s_both] drop-shadow-[0_5px_5px_rgba(0,0,0,0.5)]">
                Find Your Perfect <br class="hidden md:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-[#f4e4c1] to-[#c2a265] relative inline-block">
                    Getaway
                    <span class="absolute -bottom-2 left-0 w-full h-2 bg-gradient-to-r from-transparent via-[#c2a265] to-transparent opacity-50 blur-sm"></span>
                </span>
            </h1>

            <p class="text-lg md:text-xl text-gray-100 font-medium max-w-2xl mx-auto leading-relaxed animate-[fade-in-up_1s_ease-out_0.4s_both] drop-shadow-[0_2px_4px_rgba(0,0,0,0.6)]">
                Explore our exclusive collection of luxury farms, private chalets, and breathtaking estates tailored for unforgettable moments.
            </p>
        </div>
    </div>

    {{-- ==========================================
         2. CLEAR & PROMINENT SEARCH DOCK
         ========================================== --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-30 -mt-28 mb-24 animate-[fade-in-up_1s_ease-out_0.6s_both]">
        <div class="search-dock rounded-[2rem] md:rounded-full p-2 transition-all duration-300">
            <form method="GET" action="{{ route('explore') }}" class="flex flex-col md:flex-row items-center w-full divide-y md:divide-y-0 md:divide-x divide-gray-200">

                {{-- Destination --}}
                <div class="w-full md:w-[35%] px-6 py-3 hover:bg-gray-50 rounded-[1.5rem] md:rounded-full transition-colors cursor-pointer group">
                    <label class="block text-[11px] font-black text-gray-800 uppercase tracking-widest mb-1 group-hover:text-[#1d5c42] transition-colors">Where</label>
                    <input type="text" name="location" value="{{ request('location') }}" placeholder="Search destinations..."
                           class="w-full bg-transparent border-none text-gray-900 placeholder-gray-400 focus:ring-0 p-0 font-semibold text-base outline-none truncate transition-all">
                </div>

                {{-- Price Range --}}
                <div class="w-full md:w-[40%] flex px-6 py-3 hover:bg-gray-50 rounded-[1.5rem] md:rounded-full transition-colors group">
                    <div class="w-1/2 pr-3">
                        <label class="block text-[11px] font-black text-gray-800 uppercase tracking-widest mb-1 group-hover:text-[#1d5c42] transition-colors">Min Price</label>
                        <div class="flex items-center text-gray-900">
                            <span class="text-xs font-bold text-gray-400 mr-1">JOD</span>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0"
                                   class="w-full bg-transparent border-none text-gray-900 placeholder-gray-400 focus:ring-0 p-0 font-semibold text-base outline-none transition-all">
                        </div>
                    </div>
                    <div class="w-px h-8 bg-gray-200 my-auto mx-2"></div>
                    <div class="w-1/2 pl-3">
                        <label class="block text-[11px] font-black text-gray-800 uppercase tracking-widest mb-1 group-hover:text-[#1d5c42] transition-colors">Max Price</label>
                        <div class="flex items-center text-gray-900">
                            <span class="text-xs font-bold text-gray-400 mr-1">JOD</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Any"
                                   class="w-full bg-transparent border-none text-gray-900 placeholder-gray-400 focus:ring-0 p-0 font-semibold text-base outline-none transition-all">
                        </div>
                    </div>
                </div>

                {{-- Guests & Search Button --}}
                <div class="w-full md:w-[25%] pl-6 pr-2 py-2 flex items-center justify-between hover:bg-gray-50 rounded-[1.5rem] md:rounded-full transition-colors group">
                    <div class="flex-1 py-1">
                        <label class="block text-[11px] font-black text-gray-800 uppercase tracking-widest mb-1 group-hover:text-[#1d5c42] transition-colors">Guests</label>
                        <input type="number" name="capacity" value="{{ request('capacity') }}" placeholder="Add guests"
                               class="w-full bg-transparent border-none text-gray-900 placeholder-gray-400 focus:ring-0 p-0 font-semibold text-base outline-none transition-all">
                    </div>
                    <button type="submit" class="bg-[#1d5c42] hover:bg-[#154230] text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all transform active:scale-95 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ==========================================
         3. FARMS GRID (PREMIUM CARDS)
         ========================================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12 flex flex-col sm:flex-row sm:justify-between sm:items-end border-b border-gray-200 pb-6 gap-4">
        <div>
            <h2 class="text-4xl font-black text-gray-900 tracking-tight">Available Escapes</h2>
            <p class="text-base font-medium text-gray-500 mt-2">Discover handpicked locations for your next retreat.</p>
        </div>
        <div class="inline-flex items-center gap-2 bg-white px-5 py-2.5 rounded-full shadow-sm border border-gray-200">
            <span class="w-2.5 h-2.5 rounded-full bg-[#1d5c42] animate-pulse"></span>
            <span class="text-xs font-black text-[#1d5c42] uppercase tracking-widest">{{ $farms->total() }} Locations</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($farms as $index => $farm)
                <div class="farm-card bg-white rounded-[2rem] overflow-hidden flex flex-col group relative border border-gray-100"
                     style="animation: fade-in-up 0.8s ease-out {{ $index * 0.1 }}s both;">

                    {{-- 💖 Modern Favorite Button --}}
                    <div class="absolute top-4 right-4 z-20">
                        @auth
                            @php
                                $isFavorited = auth()->user()->favorites()->where('farm_id', $farm->id)->exists();
                            @endphp
                            <form action="{{ $isFavorited ? route('favorites.destroy', $farm->id) : route('favorites.store', $farm->id) }}" method="POST">
                                @csrf
                                @if($isFavorited) @method('DELETE') @endif
                                <button title="Toggle Favorite" class="p-2.5 rounded-full backdrop-blur-md transition-all shadow-md active:scale-95 hover:scale-105 {{ $isFavorited ? 'bg-white text-red-500' : 'bg-black/40 text-white hover:bg-white hover:text-red-500' }}">
                                    <svg class="w-5 h-5 {{ $isFavorited ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block p-2.5 rounded-full backdrop-blur-md bg-black/40 text-white hover:bg-white hover:text-red-500 transition-all shadow-md active:scale-95 hover:scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </a>
                        @endauth
                    </div>

                    {{-- Guest Favorite Badge --}}
                    @if(isset($farm->rating) && $farm->rating >= 4.5)
                        <div class="absolute top-4 left-4 z-20 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-full shadow-md flex items-center gap-1.5 border border-gray-100">
                            <svg class="w-3.5 h-3.5 text-[#c2a265]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-900">Top Rated</span>
                        </div>
                    @endif

                    {{-- Image Container --}}
                    <a href="{{ route('farms.show', $farm->id) }}" class="block relative w-full h-72 bg-gray-100 overflow-hidden">
                        @if($farm->main_image)
                            <img src="{{ asset('storage/' . $farm->main_image) }}" alt="{{ $farm->name }}" class="image-zoom w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>

                    {{-- Card Body --}}
                    <a href="{{ route('farms.show', $farm->id) }}" class="p-6 flex-1 flex flex-col block bg-white">

                        {{-- Header Row: Title & Rating --}}
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-gray-900 truncate pr-4 group-hover:text-[#1d5c42] transition-colors">{{ $farm->name }}</h3>
                            <div class="flex items-center gap-1 shrink-0 bg-gray-50 px-2 py-1 rounded border border-gray-100">
                                <svg class="w-3.5 h-3.5 text-[#1d5c42]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <span class="text-sm font-bold text-gray-800">{{ $farm->rating ?? 'New' }}</span>
                            </div>
                        </div>

                        {{-- Location --}}
                        <p class="text-sm text-gray-500 mb-4 truncate font-medium flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ Str::limit($farm->location, 40) }}
                        </p>

                        {{-- Quick Info Labels --}}
                        <div class="flex flex-wrap gap-2 mb-6 mt-auto">
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-[11px] font-bold uppercase tracking-wider">
                                Up to {{ $farm->capacity }} Guests
                            </span>
                        </div>

                        {{-- Price Footer --}}
                        <div class="border-t border-gray-100 pt-4 flex justify-between items-center mt-2">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-0.5">Price / Night</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xl font-black text-[#1d5c42]">{{ number_format($farm->price_per_night, 0) }}</span>
                                    <span class="text-xs font-bold text-gray-500">JOD</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-white bg-gray-900 group-hover:bg-[#1d5c42] px-5 py-2.5 rounded-xl transition-colors duration-300">
                                View Details
                            </span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full py-24 text-center">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-gray-200">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Escapes Found</h3>
                    <p class="text-base text-gray-500 max-w-sm mx-auto">Try adjusting your filters or destination to discover hidden gems.</p>
                </div>
            @endforelse
        </div>

        {{-- Custom Pagination --}}
        <div class="mt-16 flex justify-center pb-8">
            {{ $farms->links() }}
        </div>

    </div>
</div>
@endsection
