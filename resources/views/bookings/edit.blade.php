@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6 bg-white rounded-2xl shadow-lg">
    <h1 class="text-3xl font-extrabold text-green-800 mb-8 text-center">Edit Booking</h1>

    @if($errors->any())
        <div class="bg-red-200 text-red-800 p-4 rounded-lg mb-6 shadow-inner">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-2 font-semibold text-gray-700">Event Type</label>
            <input type="text" name="event_type" value="{{ old('event_type', $booking->event_type) }}"
                   class="w-full border border-gray-300 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 transition">
        </div>

        <div>
            <label class="block mb-2 font-semibold text-gray-700">Start Time</label>
            <input type="datetime-local" name="start_time"
                   value="{{ old('start_time', \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d\TH:i')) }}"
                   class="w-full border border-gray-300 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 transition">
        </div>

        <div>
            <label class="block mb-2 font-semibold text-gray-700">End Time</label>
            <input type="datetime-local" name="end_time"
                   value="{{ old('end_time', \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d\TH:i')) }}"
                   class="w-full border border-gray-300 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 transition">
        </div>

        <div class="text-center">
            <button type="submit"
                    class="px-8 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 shadow-md transition-all duration-300 transform hover:scale-105">
                Update Booking
            </button>
        </div>
    </form>
</div>
@endsection
