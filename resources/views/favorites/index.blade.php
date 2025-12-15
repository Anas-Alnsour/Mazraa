@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
<div class="max-w-6xl mx-auto p-6 space-y-6">

    <h1 class="text-4xl font-extrabold text-green-800 text-center mb-8">
        My Favorite Farms
    </h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-4 rounded-lg shadow-md text-center">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-200 text-red-800 p-4 rounded-lg shadow-md text-center">
            {{ session('error') }}
        </div>
    @endif

    @if($favorites->isEmpty())
        <p class="text-gray-600 text-center text-lg">
            You have no favorite farms yet.
        </p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($favorites as $farm)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl flex flex-col">

                    <!-- Image -->
                    <div class="relative">
                        <img
                            src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : 'https://via.placeholder.com/600x400' }}"
                            alt="{{ $farm->name }}"
                            class="w-full h-52 sm:h-56 md:h-48 object-cover">
                        @if($farm->rating)
                            <div class="absolute top-3 right-3 bg-white bg-opacity-80 px-2 py-1 rounded-full text-sm font-semibold text-gray-800">
                                ⭐ {{ $farm->rating }}
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex flex-col justify-between flex-1">
                        <div>
                            <h2 class="text-2xl font-bold text-green-800 mb-2 hover:text-green-600 transition">
                                {{ $farm->name }}
                            </h2>

                            <p class="text-gray-700 leading-relaxed">
                                {{ Str::limit($farm->description, 120) }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 mt-6">
                            <a
                                href="{{ route('farms.show', $farm->id) }}"
                                class="flex-1 text-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-md transition-all duration-300">
                                View
                            </a>

                            <form
                                action="{{ route('favorites.destroy', $farm->id) }}"
                                method="POST"
                                class="flex-1"
                                onsubmit="return confirm('Remove from favorites?');">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 shadow-md transition-all duration-300">
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
