@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="max-w-5xl mx-auto p-6 space-y-6">

    <h1 class="text-4xl font-extrabold text-green-800 text-center mb-8">
        Booking Details
    </h1>

    <div
        class="bg-white rounded-2xl shadow-lg p-6 flex flex-col hover:shadow-2xl transition-shadow duration-300">

        <!-- Main Image -->
        <img
            src="{{ $booking->farm->main_image
                ? asset('storage/' . $booking->farm->main_image)
                : 'https://via.placeholder.com/800x400' }}"
            alt="{{ $booking->farm->name }}"
            class="w-full h-64 object-cover rounded-xl mb-6">

        <!-- Info -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-green-800 mb-4">
                {{ $booking->farm->name }}
            </h2>

            <p class="text-gray-700 mb-2">
                <span class="font-semibold">Event:</span>
                {{ $booking->event_type ?? '-' }}
            </p>

            <p class="text-gray-700 mb-2">
                <span class="font-semibold">Start:</span>
                {{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}
            </p>

            <p class="text-gray-700">
                <span class="font-semibold">End:</span>
                {{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, H:i') }}
            </p>
        </div>

        <!-- Actions -->
        <div class="flex flex-wrap gap-4">
            <a
                href="{{ route('bookings.edit', $booking->id) }}"
                class="flex-1 text-center px-6 py-3 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 shadow-md transition-all duration-300">
                Edit Booking
            </a>

            <form
                action="{{ route('bookings.destroy', $booking->id) }}"
                method="POST"
                class="flex-1"
                onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="w-full px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 shadow-md transition-all duration-300">
                    Cancel Booking
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
