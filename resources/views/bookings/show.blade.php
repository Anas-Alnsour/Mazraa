@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Booking Details</h1>

    <div class="bg-white shadow p-6 rounded">
        <p><strong>Farm:</strong> {{ $booking->farm->name }}</p>
        <p><strong>Event Type:</strong> {{ $booking->event_type }}</p>
        <p><strong>Start Time:</strong> {{ $booking->start_time }}</p>
        <p><strong>End Time:</strong> {{ $booking->end_time }}</p>

        <div class="mt-4 space-x-2">
            <!-- زر تعديل الحجز -->
            <a href="{{ route('bookings.edit', $booking->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit Booking</a>

            <!-- زر إلغاء الحجز -->
            <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Cancel Booking</button>
            </form>
        </div>
    </div>
</div>
@endsection
