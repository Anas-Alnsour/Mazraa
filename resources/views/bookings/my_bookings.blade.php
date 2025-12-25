@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">My Bookings</h1>
                <p class="mt-2 text-gray-600">Manage your upcoming and past farm visits.</p>
            </div>

            <div class="flex gap-3 w-full md:w-auto">
                <a href="{{ route('explore') }}" class="flex-1 md:flex-none justify-center px-6 py-3 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:text-green-600 transition shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Explore Farms
                </a>
                <a href="{{ route('favorites.index') }}" class="flex-1 md:flex-none justify-center px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    From Favorites
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if ($bookings->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl shadow-sm border border-gray-100">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Bookings Yet</h3>
                <p class="text-gray-500 mb-6">You haven't made any farm bookings yet.</p>
                <a href="{{ route('explore') }}" class="px-8 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 shadow-md transition transform hover:-translate-y-0.5">
                    Book Your First Farm
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach ($bookings as $booking)
                    <div class="group bg-white rounded-3xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden transition-all duration-300 flex flex-col h-full">

                        <div class="relative h-52 overflow-hidden">
                            <img src="{{ $booking->farm->main_image ? asset('storage/' . $booking->farm->main_image) : 'https://via.placeholder.com/600x400' }}"
                                alt="{{ $booking->farm->name }}"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">

                            <div class="absolute top-4 right-4 bg-white/95 backdrop-blur px-3 py-1.5 rounded-xl text-sm font-bold text-gray-800 shadow-sm flex items-center gap-1">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ $booking->farm->rating ?? 'New' }}
                            </div>

                            <div class="absolute bottom-4 left-4 bg-green-600/90 backdrop-blur text-white px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}
                            </div>
                        </div>

                        <div class="p-6 flex flex-col flex-1">

                            <h2 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-green-600 transition-colors line-clamp-1">
                                {{ $booking->farm->name }}
                            </h2>

                            <div class="space-y-3 mb-6">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0 text-blue-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wide">Event</p>
                                        <p class="text-sm font-semibold text-gray-700 capitalize">{{ $booking->event_type ?? 'Standard' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0 text-green-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wide">Time</p>
                                        <p class="text-sm font-semibold text-gray-700">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-auto pt-6 border-t border-gray-100 grid grid-cols-3 gap-2">

                                {{-- View --}}
                                <a href="{{ route('bookings.show', $booking->id) }}"
                                   class="py-2 bg-gray-50 text-gray-600 font-bold text-sm rounded-xl hover:bg-gray-100 hover:text-green-600 transition text-center border border-gray-200 flex items-center justify-center" title="View Details">
                                    View
                                </a>

                                {{-- Edit (Added) --}}
                                <a href="{{ route('bookings.edit', $booking->id) }}"
                                   class="py-2 bg-blue-50 text-blue-600 font-bold text-sm rounded-xl hover:bg-blue-100 hover:text-blue-700 transition text-center border border-blue-200 flex items-center justify-center" title="Edit Booking">
                                    Edit
                                </a>

                                {{-- Cancel --}}
                                <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to cancel this booking?');"
                                      class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full py-2 bg-white text-red-500 font-bold text-sm rounded-xl hover:bg-red-50 hover:text-red-600 transition border border-red-100 flex items-center justify-center" title="Cancel Booking">
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
