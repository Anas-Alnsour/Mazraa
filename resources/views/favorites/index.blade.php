@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">My Favorite Farms</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-200 text-red-800 p-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    @if($favorites->isEmpty())
        <p class="text-gray-600">You have no favorite farms yet.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($favorites as $farm)
                <div class="bg-white rounded shadow p-4 flex">
                    <img src="{{ $farm->main_image ?? 'https://via.placeholder.com/200x120' }}" alt="{{ $farm->name }}" class="w-40 h-28 object-cover rounded mr-4">
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold">{{ $farm->name }}</h2>
                        <p class="text-gray-600">{{ Str::limit($farm->description, 120) }}</p>
                        <div class="mt-3 flex items-center gap-2">
                            <a href="{{ route('farms.show', $farm->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">View</a>

                            <form action="{{ route('favorites.destroy', $farm->id) }}" method="POST" onsubmit="return confirm('Remove from favorites?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection