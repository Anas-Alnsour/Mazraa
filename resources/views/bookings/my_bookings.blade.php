@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
    <div class="max-w-6xl mx-auto p-6 space-y-6">

        <h1 class="text-4xl font-extrabold text-green-800 text-center mb-8">
            My Bookings
        </h1>

        <!-- Flash messages -->
        @if (session('success'))
            <div class="bg-green-200 text-green-800 p-4 rounded-lg shadow-md text-center">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-200 text-red-800 p-4 rounded-lg shadow-md text-center">
                {{ session('error') }}
            </div>
        @endif
        <div class="flex gap-5  " >
        <a href="{{ route('explore') }}"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition transform hover:scale-105">
            Explore Farms
        </a>
        <a href="{{ route('favorites.index') }}"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition transform hover:scale-105">
            Book from your Favorites
        </a>
        </div>
        @if ($bookings->isEmpty())
            <p class="text-gray-600 text-center text-lg">
                You have no upcoming bookings.
            </p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($bookings as $booking)
                    <div
                        class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl flex flex-col">

                        <!-- Image -->
                        <div class="relative">
                            <img src="{{ $booking->farm->main_image
                                ? asset('storage/' . $booking->farm->main_image)
                                : 'https://via.placeholder.com/600x400' }}"
                                alt="{{ $booking->farm->name }}" class="w-full h-52 sm:h-56 md:h-48 object-cover">
                            <div
                                class="absolute top-3 right-3 bg-white bg-opacity-80 px-2 py-1 rounded-full text-sm font-semibold text-gray-800">
                                ⭐ {{ $booking->farm->rating ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex flex-col justify-between flex-1">
                            <div>
                                <h2 class="text-2xl font-bold text-green-800 mb-2 hover:text-green-600 transition">
                                    {{ $booking->farm->name }}
                                </h2>

                                <p class="text-gray-600 mb-1">
                                    <span class="font-semibold">Event:</span>
                                    {{ $booking->event_type ?? '-' }}
                                </p>

                                <p class="text-gray-600 mb-1">
                                    <span class="font-semibold">Start-time:</span>
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}
                                </p>

                                <p class="text-gray-600">
                                    <span class="font-semibold">End-time:</span>
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, H:i') }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-3 mt-6">
                                <a href="{{ route('bookings.show', $booking->id) }}"
                                    class="flex-1 text-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-md transition-all duration-300">
                                    View Details
                                </a>

                                <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="flex-1"
                                    onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 shadow-md transition-all duration-300">
                                        Cancel
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
