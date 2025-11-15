@extends('layouts.app')

@section('title', $farm->name)

@section('content')
<div class="max-w-6xl mx-auto p-6 space-y-6">

    <!-- Flash messages -->
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

    <!-- Main Image -->
    <div class="w-full h-96 overflow-hidden rounded-3xl shadow-lg">
        <img src="{{ $farm->main_image ?? 'https://via.placeholder.com/800x400' }}" alt="{{ $farm->name }}" class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
    </div>

    <!-- Farm Info -->
    <div class="bg-white p-6 rounded-3xl shadow-lg space-y-4">
        <h1 class="text-4xl font-extrabold text-green-800">{{ $farm->name }}</h1>
        <p class="text-gray-600"><span class="font-semibold">Location:</span> {{ $farm->location }}</p>
        <p class="text-gray-700">{{ $farm->description }}</p>
        <div class="flex flex-wrap gap-4 text-gray-700">
            <span><span class="font-semibold">Capacity:</span> {{ $farm->capacity }} persons</span>
            <span><span class="font-semibold">Price per 12h:</span> ${{ $farm->price_per_night }}</span>
            <span><span class="font-semibold">Rating:</span> ⭐ {{ $farm->rating }}</span>
        </div>

        <!-- Gallery Images -->
        @if($farm->images->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                @foreach($farm->images as $image)
                    <div class="overflow-hidden rounded-xl shadow-md">
                        <img src="{{ $image->image_path }}" alt="Farm Image" class="w-full h-48 object-cover transform hover:scale-105 transition duration-500">
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Availability -->
        <div class="mt-6">
            <h2 class="text-2xl font-semibold mb-2 text-green-800">Availability</h2>
            <ul class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                @php
                    $all_hours = collect(range(8, 20));
                    $booked_hours = $farm->bookings->pluck('start_time')->map(function($dt){ return \Carbon\Carbon::parse($dt)->format('H'); });
                @endphp
                @foreach($all_hours as $hour)
                    <li class="px-2 py-1 rounded text-center text-sm font-medium {{ $booked_hours->contains($hour) ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-700' }}">
                        {{ $hour }}:00 - {{ $hour+1 }}:00<br>
                        {{ $booked_hours->contains($hour) ? 'Booked' : 'Available' }}
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Booking Form -->
        <div class="mt-6">
            <h2 class="text-2xl font-semibold mb-4 text-green-800">Book this farm</h2>
            <form action="{{ route('farms.book', $farm->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block mb-1 font-medium">Select Date & Time:</label>
                    <input type="datetime-local" name="start_time" class="border rounded-lg px-3 py-2 w-full" required>
                </div>

                <div>
                    <label class="block mb-1 font-medium">Choose Event Type (optional):</label>
                    <select name="event_type" class="border rounded-lg px-3 py-2 w-full">
                        <option value="">None</option>
                        <option value="Birthday">Birthday</option>
                        <option value="Wedding">Wedding</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg hover:bg-green-700 transform hover:scale-105 transition-all duration-300">
                    Book Now
                </button>
            </form>
        </div>

        <!-- Favorite Button -->
        <div class="mt-4">
            @auth
                @php
                    $isFav = auth()->user()->favorites()->where('farm_id', $farm->id)->exists();
                @endphp

                @if($isFav)
                    <form action="{{ route('favorites.destroy', $farm->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 text-white px-5 py-2 rounded-xl shadow-md hover:bg-red-600 transition transform hover:scale-105">
                            Remove from Favorites
                        </button>
                    </form>
                @else
                    <form action="{{ route('favorites.store', $farm->id) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit"
                            class="bg-yellow-500 text-white px-5 py-2 rounded-xl shadow-md hover:bg-yellow-600 transition transform hover:scale-105">
                            Add to Favorites
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}"
                    class="bg-yellow-500 text-white px-5 py-2 rounded-xl shadow-md hover:bg-yellow-600 transition transform hover:scale-105">
                    Login to add favorites
                </a>
            @endauth
        </div>

    </div>
</div>
@endsection
