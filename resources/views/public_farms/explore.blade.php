@extends('layouts.app')

@section('title', 'Explore Farms & Escapes')

@section('content')

<style>
    /* Premium Animations & Utilities */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }
    .animate-float-slow { animation: float 8s ease-in-out infinite; }

    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }

    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* Input Reset & Enhancements */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Image Zoom Transition */
    .image-zoom { transition: transform 1.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .group:hover .image-zoom { transform: scale(1.08); }
</style>

{{-- Main Wrapper --}}
<div class="bg-[#f4f7f6] min-h-screen pb-24 font-sans selection:bg-[#1d5c42] selection:text-white">

    {{-- ==========================================
         1. PREMIUM HERO SECTION (DARK MODE)
         ========================================== --}}
    <div class="relative w-full h-[65vh] min-h-[550px] flex flex-col justify-center items-center bg-[#0a0a0a] overflow-hidden pt-20">

        {{-- High Quality Background Image --}}
        <div class="absolute inset-0 w-full h-full overflow-hidden">
            <img src="{{ asset('backgrounds/home.JPG') }}" alt="Explore Escapes"
                 class="w-full h-full object-cover opacity-40 scale-105 animate-[pulse_25s_ease-in-out_infinite]">
        </div>

        {{-- Vignette & Smooth Gradient Overlays --}}
        <div class="absolute inset-0 shadow-[inset_0_0_150px_rgba(0,0,0,0.9)]"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#f4f7f6] via-transparent to-[#0a0a0a]/50"></div>

        {{-- Glowing Orbs for Depth (More subtle for dark mode) --}}
        <div class="absolute top-1/4 left-1/4 w-[35rem] h-[35rem] bg-[#1d5c42]/20 rounded-full blur-[120px] animate-float-slow pointer-events-none mix-blend-screen"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[30rem] h-[30rem] bg-[#c2a265]/15 rounded-full blur-[120px] animate-float-slow pointer-events-none mix-blend-screen" style="animation-delay: 2s;"></div>

        {{-- Main Hero Text Container --}}
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto pb-20 fade-in-up">
            <div class="inline-flex items-center gap-2 py-1.5 px-5 rounded-full bg-white/5 border border-white/10 text-[#f4e4c1] text-[10px] font-black tracking-[0.25em] uppercase backdrop-blur-xl mb-6 shadow-2xl">
                <span class="w-1.5 h-1.5 rounded-full bg-[#c2a265] animate-ping absolute"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#c2a265] relative"></span>
                Premium Collection
            </div>

            <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white tracking-tighter mb-6 leading-[1.1] drop-shadow-2xl">
                Find Your Perfect <br class="hidden md:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-[#f4e4c1] to-[#c2a265] italic font-serif font-medium pr-2">
                    Getaway
                </span>
            </h1>

            <p class="text-base md:text-lg text-gray-300 font-medium max-w-2xl mx-auto leading-relaxed drop-shadow-lg opacity-80">
                Explore our exclusive collection of luxury farms, private chalets, and breathtaking estates tailored for unforgettable moments.
            </p>
        </div>
    </div>

    {{-- ==========================================
         2. CLEAR & PROMINENT SEARCH DOCK (DARK GLASS)
         ========================================== --}}
    <div class="max-w-[96%] xl:max-w-6xl mx-auto relative z-30 -mt-28 mb-20 fade-in-up" style="animation-delay: 0.2s;">
        <div class="bg-[#13131a]/80 backdrop-blur-2xl rounded-[2rem] lg:rounded-full p-2 md:p-3 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] border border-white/10">
            <form method="GET" action="{{ route('explore') }}" class="flex flex-col lg:flex-row items-center w-full lg:divide-x divide-white/10 gap-2 lg:gap-0">

                {{-- Name Input --}}
                <div class="w-full lg:w-[32%] px-6 py-3 md:py-4 rounded-2xl lg:rounded-l-full focus-within:bg-white/5 hover:bg-white/5 transition-all duration-300 group cursor-text" onclick="document.getElementById('search_name').focus()">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-focus-within:text-[#c2a265] transition-colors">Where to?</label>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500 group-focus-within:text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" id="search_name" name="name" value="{{ request('name') }}" placeholder="Search estates..."
                               class="w-full bg-transparent border-none text-white placeholder-gray-500 focus:ring-0 p-0 font-bold text-sm md:text-base outline-none truncate">
                    </div>
                </div>

                {{-- Governorate Select --}}
                <div class="w-full lg:w-[28%] px-6 py-3 md:py-4 rounded-2xl focus-within:bg-white/5 hover:bg-white/5 transition-all duration-300 group relative">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-[#c2a265] transition-colors">Region</label>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        <select name="governorate" class="w-full bg-transparent border-none text-white placeholder-gray-500 focus:ring-0 p-0 font-bold text-sm md:text-base outline-none appearance-none cursor-pointer">
                            <option value="" class="text-gray-900">All Regions</option>
                            @foreach(['Amman', 'Irbid', 'Zarqa', 'Aqaba', 'Jerash', 'Madaba', 'Salt', 'Karak', 'Ajloun', 'Mafraq', 'Tafilah', 'Ma\'an'] as $gov)
                                <option value="{{ $gov }}" class="text-gray-900" {{ request('governorate') == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                            @endforeach
                        </select>
                        <svg class="w-4 h-4 text-gray-500 absolute right-6 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                {{-- Price Range (Min / Max) --}}
                <div class="w-full lg:w-[30%] flex rounded-2xl overflow-hidden focus-within:bg-white/5 hover:bg-white/5 transition-all duration-300 group">
                    <div class="w-1/2 px-6 py-3 md:py-4 border-r border-white/10 cursor-text" onclick="document.getElementById('min_price').focus()">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-focus-within:text-[#c2a265] transition-colors">Min Price</label>
                        <div class="flex items-center">
                            <span class="text-[10px] font-black text-gray-500 mr-1.5">JOD</span>
                            <input type="number" id="min_price" name="min_price" value="{{ request('min_price') }}" placeholder="0"
                                   class="w-full bg-transparent border-none text-white placeholder-gray-500 focus:ring-0 p-0 font-bold text-sm md:text-base outline-none">
                        </div>
                    </div>
                    <div class="w-1/2 px-6 py-3 md:py-4 cursor-text" onclick="document.getElementById('max_price').focus()">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-focus-within:text-[#c2a265] transition-colors">Max Price</label>
                        <div class="flex items-center">
                            <span class="text-[10px] font-black text-gray-500 mr-1.5">JOD</span>
                            <input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}" placeholder="Any"
                                   class="w-full bg-transparent border-none text-white placeholder-gray-500 focus:ring-0 p-0 font-bold text-sm md:text-base outline-none">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="w-full lg:w-[15%] p-2 flex items-center justify-center lg:justify-end gap-2 mt-2 lg:mt-0 shrink-0">
                    @if(request()->anyFilled(['name', 'governorate', 'min_price', 'max_price']))
                        <a href="{{ route('explore') }}" title="Clear Filters" class="bg-white/5 hover:bg-red-500/20 text-gray-400 hover:text-red-400 w-12 h-12 md:w-14 md:h-14 rounded-full transition-all transform active:scale-95 flex items-center justify-center shrink-0 border border-white/10 hover:border-red-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                    <button type="submit" class="bg-gradient-to-tr from-[#1d5c42] to-[#14402e] hover:to-[#1d5c42] text-white h-12 md:h-14 px-8 rounded-full shadow-lg shadow-[#1d5c42]/30 border border-white/10 hover:shadow-[#1d5c42]/50 transition-all transform active:scale-95 flex items-center justify-center gap-2 flex-1 lg:flex-none group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <span class="lg:hidden font-black uppercase text-xs tracking-widest">Search</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ==========================================
         3. FARMS GRID (REFINED UX CARDS)
         ========================================== --}}
    <div class="max-w-[96%] xl:max-w-7xl mx-auto mb-10 flex flex-col sm:flex-row sm:justify-between sm:items-end border-b border-gray-200 pb-6 gap-4 fade-in-up" style="animation-delay: 0.3s;">
        <div>
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <span class="w-2 h-8 rounded-full bg-[#c2a265]"></span>
                Available Escapes
            </h2>
            <p class="text-sm md:text-base font-medium text-gray-500 mt-2 ml-5">Discover handpicked locations for your next retreat.</p>
        </div>
        <div class="inline-flex items-center gap-2 bg-white px-5 py-2.5 rounded-2xl shadow-sm border border-gray-100">
            <span class="w-2 h-2 rounded-full bg-[#1d5c42] animate-pulse"></span>
            <span class="text-[10px] font-black text-gray-700 uppercase tracking-widest">{{ $farms->total() }} Locations Found</span>
        </div>
    </div>

    <div class="max-w-[96%] xl:max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @forelse ($farms as $index => $farm)
                <div class="group bg-white rounded-[2rem] p-3 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 hover:border-[#1d5c42]/30 hover:shadow-[0_25px_50px_rgba(29,92,66,0.12)] transform transition-all duration-500 hover:-translate-y-2 flex flex-col relative"
                     style="animation: fade-in-up 0.8s ease-out {{ ($index % 6) * 0.1 }}s both;">

                    {{-- Inner Card Image Wrapper --}}
                    <div class="relative aspect-[4/3] rounded-[1.5rem] overflow-hidden mb-5 bg-gray-100 shadow-inner">

                        {{-- 💖 Glassmorphic Favorite Button --}}
                        <div class="absolute top-3 right-3 z-30">
                            @auth
                                @php
                                    $isFavorited = auth()->user()->favorites()->where('farm_id', $farm->id)->exists();
                                @endphp
                                <form action="{{ $isFavorited ? route('favorites.destroy', $farm->id) : route('favorites.store', $farm->id) }}" method="POST">
                                    @csrf
                                    @if($isFavorited) @method('DELETE') @endif
                                    <button title="Toggle Favorite" class="p-2.5 rounded-xl backdrop-blur-md transition-all duration-300 shadow-lg active:scale-95 hover:scale-105 border border-white/20 {{ $isFavorited ? 'bg-white/90 text-red-500' : 'bg-black/40 text-white hover:bg-white/90 hover:text-red-500' }}">
                                        <svg class="w-5 h-5 {{ $isFavorited ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="block p-2.5 rounded-xl backdrop-blur-md bg-black/40 text-white hover:bg-white/90 hover:text-red-500 transition-all duration-300 shadow-lg border border-white/20 active:scale-95 hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                </a>
                            @endauth
                        </div>

                        {{-- ⭐ Glassmorphic Rating Badge --}}
                        @if(isset($farm->average_rating) && $farm->average_rating > 0)
                            <div class="absolute top-3 left-3 z-30 bg-black/40 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-lg border border-white/20 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#f4e4c1]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <span class="text-[11px] font-bold text-white tracking-widest">{{ number_format($farm->average_rating, 1) }}</span>
                            </div>
                        @endif

                        <a href="{{ route('farms.show', $farm->id) }}" class="absolute inset-0 z-10 block w-full h-full">
                            @if($farm->main_image)
                                <img src="{{ asset('storage/' . $farm->main_image) }}" alt="{{ $farm->name }}"
                                     class="w-full h-full object-cover image-zoom relative -z-10">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gray-100 relative -z-10">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </a>
                    </div>

                    {{-- Card Content Info --}}
                    <div class="px-3 pb-2 flex flex-col flex-1 relative z-0">
                        <div class="flex justify-between items-start gap-4 mb-2">
                            <h3 class="text-lg font-black text-gray-900 truncate group-hover:text-[#1d5c42] transition-colors tracking-tight">
                                <a href="{{ route('farms.show', $farm->id) }}" class="focus:outline-none">{{ $farm->name }}</a>
                            </h3>
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shrink-0 border border-gray-200">
                                {{ $farm->governorate }}
                            </span>
                        </div>

                        <p class="text-sm font-medium text-gray-500 flex items-center gap-1.5 opacity-90 mb-5 line-clamp-1">
                            <svg class="w-4 h-4 text-[#c2a265] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            {{ Str::limit($farm->location, 35) }}
                        </p>

                        {{-- 💡 NEW: Stacked Pricing with Icons --}}
                        <div class="mt-auto bg-gray-50/50 rounded-2xl p-3 border border-gray-100 mb-4">
                            <div class="flex items-center justify-between py-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    Morning
                                </span>
                                <span class="text-xs font-black text-gray-900">JOD {{ number_format($farm->price_per_morning_shift ?? 0, 0) }}</span>
                            </div>
                            <div class="flex items-center justify-between py-1 border-t border-gray-100/80">
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                    Evening
                                </span>
                                <span class="text-xs font-black text-gray-900">JOD {{ number_format($farm->price_per_evening_shift ?? 0, 0) }}</span>
                            </div>
                            <div class="flex items-center justify-between py-1 border-t border-gray-100/80">
                                <span class="text-[10px] font-black uppercase tracking-widest text-[#1d5c42] flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Full Day
                                </span>
                                <span class="text-sm font-black text-[#1d5c42]">JOD {{ number_format($farm->price_per_full_day ?? 0, 0) }}</span>
                            </div>
                        </div>

                        {{-- 💡 NEW: Prominent Details Button --}}
                        <a href="{{ route('farms.show', $farm->id) }}" class="w-full bg-white text-[#1d5c42] border-2 border-[#1d5c42]/10 hover:border-[#1d5c42] hover:bg-[#1d5c42] hover:text-white px-4 py-3 rounded-xl text-xs font-black uppercase tracking-widest text-center transition-all duration-300 shadow-sm hover:shadow-lg focus:outline-none">
                            View Details
                        </a>

                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center bg-white rounded-[2.5rem] border border-gray-100 shadow-sm fade-in-up">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 10h.01M14 14h.01M10 14h.01M14 10h.01"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">No Escapes Found</h3>
                    <p class="text-sm font-medium text-gray-500 max-w-sm mx-auto mb-6">We couldn't find any farms matching your specific filters. Try adjusting your search criteria.</p>
                    <a href="{{ route('explore') }}" class="inline-flex items-center gap-2 bg-[#1d5c42] text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg hover:shadow-[#1d5c42]/30 hover:bg-[#14402e] transition-all">
                        Clear All Filters
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Custom Pagination Wrapper --}}
        @if($farms->hasPages())
            <div class="mt-16 flex justify-center pb-8 fade-in-up" style="animation-delay: 0.8s;">
                <div class="bg-white px-6 py-3 rounded-full shadow-sm border border-gray-100">
                    {{ $farms->links() }}
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
