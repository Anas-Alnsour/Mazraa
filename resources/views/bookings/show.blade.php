@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Booking Details</h1>
                <p class="mt-1 text-gray-500 font-medium">Reference ID: <span class="text-green-600">#{{ $booking->id }}</span></p>
            </div>

            <a href="{{ route('bookings.my_bookings') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:text-green-600 hover:border-green-200 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Bookings
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-xl overflow-hidden border border-gray-100 flex flex-col md:flex-row">

            <div class="relative w-full md:w-2/5 h-64 md:h-auto min-h-[300px]">
                <img src="{{ $booking->farm->main_image ? asset('storage/' . $booking->farm->main_image) : 'https://via.placeholder.com/600x800' }}"
                     alt="{{ $booking->farm->name }}"
                     class="w-full h-full object-cover">

                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent md:hidden"></div>

                <div class="absolute top-4 left-4 bg-white/95 backdrop-blur px-3 py-1.5 rounded-xl text-sm font-bold text-gray-800 shadow-sm flex items-center gap-1">
                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    {{ $booking->farm->rating ?? 'New' }}
                </div>
            </div>

            <div class="p-8 w-full md:w-3/5 flex flex-col justify-between">

                <div>
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $booking->farm->name }}</h2>
                            <div class="flex items-center text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $booking->farm->location }}
                            </div>
                        </div>
                        <span class="px-4 py-1.5 bg-green-100 text-green-700 text-xs font-bold uppercase rounded-full tracking-wide shadow-sm">
                            Confirmed
                        </span>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-start gap-3">
                                <div class="p-2 bg-white rounded-lg text-blue-500 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wide">Start Date</p>
                                    <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500 font-medium">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</p>
                                </div>
                            </div>

                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-start gap-3">
                                <div class="p-2 bg-white rounded-lg text-red-500 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wide">End Date</p>
                                    <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->end_time)->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500 font-medium">{{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-xl border border-purple-100">
                            <div class="p-1.5 bg-white rounded-lg text-purple-600 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs text-purple-400 font-bold uppercase tracking-wide mr-2">Event:</span>
                                <span class="text-sm font-bold text-purple-800 capitalize">{{ $booking->event_type ?? 'Standard Visit' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-center sm:text-left">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Total Cost</p>
                        <div class="flex items-baseline gap-1">
                            <p class="text-4xl font-black text-green-700">{{ $booking->farm->price_per_night }}</p>
                            <span class="text-lg font-bold text-green-700">JD</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('bookings.edit', $booking->id) }}"
                           class="flex-1 sm:flex-none px-6 py-3.5 bg-blue-50 text-blue-600 font-bold rounded-2xl hover:bg-blue-100 hover:text-blue-700 transition shadow-sm flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>

                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel? This action cannot be undone.');" class="flex-1 sm:flex-none">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-6 py-3.5 bg-red-50 text-red-600 font-bold rounded-2xl hover:bg-red-100 hover:text-red-700 transition shadow-sm flex justify-center items-center gap-2 group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Cancel
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
