@extends('layouts.app')

@section('title', 'Explore Farms')

@section('content')
    <div class="bg-[#f9f8f4] min-h-screen pb-20 font-sans">

        <div class="relative w-full h-[50vh] min-h-[400px] flex items-center justify-center bg-gray-900">
            <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=2000&auto=format&fit=crop" alt="Farm Landscape" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay">

            <div class="relative z-10 text-center px-4 max-w-4xl mx-auto -mt-8 animate-fade-in">
                <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-6 drop-shadow-lg leading-tight">
                    DISCOVER YOUR<br>UNIQUE ESCAPE
                </h1>
                <p class="text-lg text-gray-100 font-medium max-w-2xl mx-auto drop-shadow-md">
                    Find agri-escapes to curate your modern farm portfolio, list properties, or book memorable agricultural stays.
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 -mt-14 mb-16">
            <div class="bg-[#b46146] p-3 md:p-4 rounded-[2.5rem] md:rounded-full shadow-2xl border-4 border-white/20">
                <form method="GET" action="{{ route('explore') }}" class="flex flex-col md:flex-row items-center justify-between gap-4 md:gap-0">

                    <div class="w-full md:w-1/4 px-6 md:border-r border-white/20 pb-4 md:pb-0">
                        <label class="block text-[10px] font-bold text-white/70 uppercase tracking-widest mb-1">Location</label>
                        <div class="flex items-center text-white">
                            <svg class="w-5 h-5 mr-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <input type="text" name="location" value="{{ request('location') }}" placeholder="Where to?" class="w-full bg-transparent border-none text-white placeholder-white/60 focus:ring-0 p-0 font-bold text-sm outline-none">
                        </div>
                    </div>

                    <div class="w-full md:w-1/4 px-6 md:border-r border-white/20 pb-4 md:pb-0">
                        <label class="block text-[10px] font-bold text-white/70 uppercase tracking-widest mb-1">Min Price</label>
                        <div class="flex items-center text-white">
                            <span class="mr-2 opacity-80 font-bold">JOD</span>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" class="w-full bg-transparent border-none text-white placeholder-white/60 focus:ring-0 p-0 font-bold text-sm outline-none">
                        </div>
                    </div>

                    <div class="w-full md:w-1/4 px-6 md:border-r border-white/20 pb-4 md:pb-0">
                        <label class="block text-[10px] font-bold text-white/70 uppercase tracking-widest mb-1">Max Price</label>
                        <div class="flex items-center text-white">
                            <span class="mr-2 opacity-80 font-bold">JOD</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="1000" class="w-full bg-transparent border-none text-white placeholder-white/60 focus:ring-0 p-0 font-bold text-sm outline-none">
                        </div>
                    </div>

                    <div class="w-full md:w-1/4 px-6 pb-4 md:pb-0">
                        <label class="block text-[10px] font-bold text-white/70 uppercase tracking-widest mb-1">Guests</label>
                        <div class="flex items-center text-white">
                            <svg class="w-5 h-5 mr-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <input type="number" name="capacity" value="{{ request('capacity') }}" placeholder="e.g. 10" class="w-full bg-transparent border-none text-white placeholder-white/60 focus:ring-0 p-0 font-bold text-sm outline-none">
                        </div>
                    </div>

                    <div class="w-full md:w-auto px-2 flex justify-center pb-4 md:pb-0">
                        <button type="submit" class="w-full md:w-auto whitespace-nowrap bg-[#1d5c42] hover:bg-[#154230] text-white font-bold py-4 px-8 md:py-3 rounded-full shadow-lg transition-all transform active:scale-95 text-sm uppercase tracking-wider">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 flex justify-between items-end">
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Featured Escapes</h2>
            <p class="text-sm font-bold text-gray-500">{{ $farms->total() }} Farms Available</p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($farms as $farm)
                    <div class="bg-[#fdfdfb] rounded-[2rem] shadow-md hover:shadow-2xl border border-gray-100 overflow-hidden flex flex-col hover:-translate-y-1 transition-all duration-300 group">

                        <div class="relative h-64 bg-gray-200 overflow-hidden p-2">
                            <div class="w-full h-full rounded-[1.5rem] overflow-hidden relative">
                                @if($farm->main_image)
                                    <img src="{{ asset('storage/' . $farm->main_image) }}" alt="{{ $farm->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gray-300 flex items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>

                            <div class="absolute top-5 left-5 bg-[#c2a265] text-white px-4 py-1.5 rounded-full shadow-md backdrop-blur-sm border border-white/20">
                                <span class="font-black text-sm">{{ number_format($farm->price_per_night, 0) }} JOD</span>
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-xl font-black text-gray-900 mb-1 truncate">{{ $farm->name }}</h3>

                            <div class="flex items-center text-xs font-bold text-gray-500 mb-5">
                                <svg class="w-3.5 h-3.5 mr-1 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path></svg>
                                {{ Str::limit($farm->location, 35) }}
                            </div>

                            <div class="flex items-center gap-4 mb-6 mt-auto border-t border-gray-100 pt-4">
                                <div class="flex items-center text-xs font-black text-gray-600 uppercase tracking-widest">
                                    <svg class="w-4 h-4 text-[#1d5c42] mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    {{ $farm->capacity }} Guests
                                </div>
                                <div class="flex items-center text-xs font-black text-gray-600 uppercase tracking-widest">
                                    <svg class="w-4 h-4 text-amber-500 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    {{ $farm->rating ?? 'New' }}
                                </div>
                            </div>

                            <a href="{{ route('farms.show', $farm->id) }}" class="w-full bg-[#183126] hover:bg-[#10231b] text-white text-center py-3.5 rounded-[1rem] font-bold text-xs uppercase tracking-widest transition-all shadow-md active:scale-95">
                                View Details & Book
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-gray-100 shadow-sm">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-800 mb-1 uppercase">No Escapes Found</h3>
                        <p class="text-sm font-bold text-gray-400">Try adjusting your search criteria to discover hidden gems.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-12 flex justify-center">
                {{ $farms->links() }}
            </div>

        </div>
    </div>
@endsection
