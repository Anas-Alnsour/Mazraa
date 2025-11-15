@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="max-w-6xl mx-auto p-6 space-y-6">

    <h1 class="text-4xl font-extrabold text-green-800 text-center mb-8">My Upcoming Bookings</h1>

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

    @if($bookings->isEmpty())
        <p class="text-gray-600 text-center text-lg">You have no upcoming bookings.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between hover:shadow-2xl transition-shadow duration-300">
                    <div class="mb-4">
                        <h2 class="text-2xl font-bold text-green-800 mb-2">{{ $booking->farm->name }}</h2>
                        <p class="text-gray-600 mb-1"><span class="font-semibold">Event:</span> {{ $booking->event_type ?? '-' }}</p>
                        <p class="text-gray-600 mb-1"><span class="font-semibold">Start:</span> {{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}</p>
                        <p class="text-gray-600"><span class="font-semibold">End:</span> {{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, H:i') }}</p>
                    </div>

                    <div class="flex flex-wrap gap-3 mt-4">
                        <a href="{{ route('bookings.show', $booking->id) }}" class="flex-1 text-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-md transition-all duration-300">
                            View Details
                        </a>

                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 shadow-md transition-all duration-300">
                                Cancel
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
