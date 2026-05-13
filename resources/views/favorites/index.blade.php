@extends('layouts.app')

@section('title', 'My Favorite Farms')

@section('content')
<style>
    /* Premium Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }
    .animate-float-slow { animation: float 8s ease-in-out infinite; }

    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }

    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* Image Zoom */
    .image-zoom { transition: transform 1.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .booking-card-wrapper:hover .image-zoom { transform: scale(1.08); }
</style>

<div class="bg-[#f4f7f6] min-h-screen pb-24 font-sans selection:bg-[#1d5c42] selection:text-white">

    {{-- ==========================================
         1. HERO SECTION (MINI DARK MODE)
         ========================================== --}}
    <div class="relative w-full h-[40vh] min-h-[350px] flex flex-col justify-center items-center bg-[#0a0a0a] overflow-hidden pt-12">
        <div class="absolute inset-0 w-full h-full overflow-hidden">
            <img src="{{ asset('backgrounds/home.JPG') }}" alt="Background"
                 class="w-full h-full object-cover opacity-30 scale-105 animate-[pulse_25s_ease-in-out_infinite] grayscale-[20%]">
        </div>
        <div class="absolute inset-0 shadow-[inset_0_0_150px_rgba(0,0,0,0.9)]"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#f4f7f6] via-transparent to-[#0a0a0a]/60"></div>
        <div class="absolute top-1/4 left-1/4 w-[30rem] h-[30rem] bg-[#1d5c42]/20 rounded-full blur-[120px] animate-float-slow pointer-events-none mix-blend-screen"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[25rem] h-[25rem] bg-rose-500/15 rounded-full blur-[100px] animate-float-slow pointer-events-none mix-blend-screen" style="animation-delay: 2s;"></div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto pb-10 fade-in-up">
            <div class="inline-flex items-center gap-2 py-1.5 px-5 rounded-full bg-white/5 border border-white/10 text-[#f4e4c1] text-[10px] font-black tracking-[0.25em] uppercase backdrop-blur-xl mb-5 shadow-2xl">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping absolute"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 relative"></span>
                Wishlist
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4 drop-shadow-2xl">
                My <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-rose-200 to-rose-400 italic font-serif font-medium pr-2">Favorites</span>
            </h1>
            <p class="text-sm md:text-base text-gray-300 font-medium max-w-lg mx-auto leading-relaxed drop-shadow-lg opacity-90">
                Your personal collection of dream escapes and luxury farm stays.
            </p>
        </div>
    </div>

    {{-- ==========================================
         2. MAIN CONTENT
         ========================================== --}}
    <div class="max-w-[96%] xl:max-w-7xl mx-auto relative z-30 -mt-20">

        {{-- Glassmorphic Actions Bar --}}
        <div class="bg-white/90 backdrop-blur-2xl rounded-[2rem] p-4 lg:p-5 shadow-[0_20px_40px_-10px_rgba(0,0,0,0.08)] border border-white flex flex-col sm:flex-row justify-between items-center gap-4 mb-12 fade-in-up" style="animation-delay: 0.2s;">
            <div class="px-3 flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center font-black text-xl shadow-inner border border-rose-100">
                    {{ $favorites->count() }}
                </div>
                <div>
                    <p class="text-sm font-black text-gray-900 uppercase tracking-widest">Saved Farms</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">In your wishlist</p>
                </div>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('bookings.my_bookings') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gray-50 border border-gray-200 text-gray-600 font-black text-[10px] md:text-xs uppercase tracking-widest rounded-xl hover:bg-white hover:text-blue-600 hover:border-blue-200 hover:shadow-md transition-all flex items-center gap-2 active:scale-95 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    My Bookings
                </a>
                <a href="{{ route('explore') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gradient-to-tr from-[#1d5c42] to-[#14402e] hover:to-[#0f3022] text-white font-black text-[10px] md:text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-[#1d5c42]/30 hover:shadow-xl hover:shadow-[#1d5c42]/40 transition-all flex items-center gap-2 active:scale-95 group border border-white/10">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Explore More
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-8 bg-emerald-50 border border-emerald-100 p-5 rounded-2xl flex items-center gap-4 shadow-sm fade-in-up" style="animation-delay: 0.3s;">
                <div class="bg-emerald-500 p-2 rounded-full text-white shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                <p class="text-emerald-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-8 bg-rose-50 border border-rose-100 p-5 rounded-2xl flex items-center gap-4 shadow-sm fade-in-up" style="animation-delay: 0.3s;">
                <div class="bg-rose-500 p-2 rounded-full text-white shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                <p class="text-rose-800 font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Grid Content --}}
        @if ($favorites->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 fade-in-up" style="animation-delay: 0.4s;">
                <div class="w-24 h-24 bg-rose-50 rounded-[2rem] flex items-center justify-center mb-6 border border-rose-100 shadow-inner">
                    <svg class="w-12 h-12 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="text-2xl md:text-3xl font-black text-gray-900 mb-2 tracking-tight">No Favorites Yet</h3>
                <p class="text-gray-500 mb-8 font-medium text-sm md:text-base max-w-sm text-center">Start exploring farms and build your wishlist of dream destinations.</p>
                <a href="{{ route('explore') }}" class="px-8 py-4 bg-gradient-to-tr from-[#1d5c42] to-[#14402e] text-white font-black text-xs uppercase tracking-widest rounded-xl hover:shadow-[0_10px_30px_rgba(29,92,66,0.3)] transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                    Browse Farms
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($favorites as $index => $farm)
                    <div class="booking-card-wrapper fade-in-up-stagger group bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] hover:shadow-[0_25px_50px_rgba(225,29,72,0.08)] border border-gray-100 hover:border-rose-100 overflow-hidden transition-all duration-500 flex flex-col h-full relative" style="animation-delay: {{ 0.2 + ($index * 0.1) }}s;">

                        {{-- Image Header --}}
                        <div class="p-3 pb-0">
                            <div class="relative h-56 md:h-64 overflow-hidden rounded-[1.5rem] bg-gray-100 shadow-inner">
                                <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : asset('backgrounds/home.JPG') }}"
                                     onerror="this.onerror=null;this.src='{{ asset('backgrounds/home.JPG') }}';"
                                     alt="{{ $farm->name }}"
                                     class="image-zoom w-full h-full object-cover text-transparent relative z-10">

                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                                {{-- Rating Badge --}}
                                <div class="absolute top-3 right-3 bg-black/40 backdrop-blur-md px-3 py-1.5 rounded-xl text-[11px] font-bold text-white tracking-widest shadow-lg flex items-center gap-1.5 z-10 border border-white/20">
                                    <svg class="w-3.5 h-3.5 text-[#f4e4c1]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    {{ number_format($farm->average_rating ?? $farm->rating ?? 0, 1) }}
                                </div>

                                {{-- Location Overlay --}}
                                <div class="absolute bottom-4 left-5 z-10 text-white w-full pr-5">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-[#f4e4c1] mb-1 drop-shadow-sm flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        Location
                                    </p>
                                    <p class="font-black text-2xl md:text-3xl tracking-tight leading-none drop-shadow-lg">
                                        {{ $farm->governorate ?? 'Jordan' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="px-4 md:px-6 pt-5 pb-6 flex flex-col flex-1">
                            <h2 class="text-xl md:text-2xl font-black text-gray-900 mb-4 group-hover:text-rose-600 transition-colors line-clamp-1 tracking-tight">
                                {{ $farm->name }}
                            </h2>

                            {{-- Description Box --}}
                            <div class="mb-6 bg-gray-50/60 rounded-2xl border border-gray-100 overflow-hidden shadow-[inset_0_2px_10px_rgba(0,0,0,0.01)] flex-1 p-4">
                                <p class="text-xs md:text-sm text-gray-500 font-medium leading-relaxed line-clamp-3">
                                    {{ Str::limit($farm->description ?? 'Discover this beautiful property, perfect for your next getaway.', 120) }}
                                </p>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="mt-auto grid grid-cols-2 gap-2">
                                <a href="{{ route('farms.show', $farm->id) }}"
                                   class="col-span-1 py-3.5 bg-gray-900 text-white font-black text-[9px] md:text-[10px] uppercase tracking-widest rounded-xl hover:bg-[#1d5c42] hover:shadow-lg hover:shadow-[#1d5c42]/30 transition-all text-center active:scale-95 flex items-center justify-center gap-1.5">
                                    View Farm
                                </a>

                                <form action="{{ route('favorites.destroy', $farm->id) }}" method="POST"
                                      onsubmit="return confirm('Remove from favorites?');"
                                      class="col-span-1 h-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full h-full py-3.5 bg-white text-rose-500 font-black text-[9px] md:text-[10px] uppercase tracking-widest rounded-xl hover:bg-rose-50 hover:text-rose-600 transition-colors border border-rose-100 text-center shadow-sm active:scale-95 flex items-center justify-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
