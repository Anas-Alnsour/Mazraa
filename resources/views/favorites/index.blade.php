@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">My Favorite Farms</h1>
                <p class="mt-1 text-gray-500 text-sm">Your saved collection of farms.</p>
            </div>

            <a href="{{ route('explore') }}" class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-bold rounded-xl shadow-lg hover:bg-green-700 transition transform hover:-translate-y-0.5 gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Explore More
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl flex items-center gap-3 shadow-sm animate-fade-in-up">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl flex items-center gap-3 shadow-sm animate-fade-in-up">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if($favorites->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border border-gray-100 shadow-sm text-center">
                <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Favorites Yet</h3>
                <p class="text-gray-500 mb-6">Start exploring farms and save them here.</p>
                <a href="{{ route('explore') }}" class="px-8 py-3 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 hover:border-gray-300 shadow-sm transition">
                    Browse Farms
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($favorites as $farm)
                    <div class="group bg-white rounded-[1.5rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full overflow-hidden hover:-translate-y-1">

                        <div class="relative h-56 overflow-hidden">
                            <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : 'https://via.placeholder.com/600x400' }}"
                                 alt="{{ $farm->name }}"
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">

                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>

                            @if($farm->rating)
                                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur px-3 py-1.5 rounded-xl text-sm font-bold text-gray-800 shadow-sm flex items-center gap-1">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    {{ $farm->rating }}
                                </div>
                            @endif
                        </div>

                        <div class="p-6 flex flex-col flex-1">

                            <div class="mb-4">
                                <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-green-700 transition line-clamp-1">
                                    {{ $farm->name }}
                                </h2>
                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">
                                    {{ Str::limit($farm->description, 100) }}
                                </p>
                            </div>

                            <div class="mt-auto pt-4 border-t border-gray-100 flex gap-3">
                                <a href="{{ route('farms.show', $farm->id) }}"
                                   class="flex-1 py-2.5 bg-blue-50 text-blue-600 font-bold text-sm rounded-xl hover:bg-blue-100 transition text-center border border-blue-100 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    View
                                </a>

                                <form action="{{ route('favorites.destroy', $farm->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Remove from favorites?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-2.5 bg-red-50 text-red-600 font-bold text-sm rounded-xl hover:bg-red-100 transition border border-red-100 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
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
@endsection
