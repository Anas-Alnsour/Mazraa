@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<style>
    /* Smooth Fade In Stagger */
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    .fade-in-up-stagger { animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* Gradient overlay for images */
    .image-gradient-overlay {
        background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.4) 50%, rgba(15, 23, 42, 0) 100%);
    }

    /* Card Hover Effects */
    .booking-card-wrapper:hover .booking-image {
        transform: scale(1.08);
    }
</style>

{{-- 💡 pt-36 to push content further down from the fixed navbar --}}
<div class="bg-[#f8fafc] min-h-screen pb-24 font-sans pt-36 selection:bg-[#1d5c42] selection:text-white">

    {{-- ==========================================
         1. HERO SECTION (MINI)
         ========================================== --}}
    <div class="relative w-full h-[30vh] min-h-[280px] flex items-center justify-center bg-[#020617] overflow-hidden mb-12 rounded-[2.5rem] mx-auto max-w-[96%] xl:max-w-[94%] shadow-2xl shadow-gray-900/10">
        <img src="{{ asset('backgrounds/home.JPG') }}" alt="Background"
             class="absolute inset-0 w-full h-full object-cover opacity-30 animate-[pulse_15s_ease-in-out_infinite] grayscale-[20%]">

        <div class="absolute inset-0 bg-gradient-to-b from-[#020617]/95 via-[#020617]/70 to-[#f8fafc]/10"></div>
        <div class="absolute top-1/4 left-1/3 w-96 h-96 bg-[#1d5c42]/30 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-[#c2a265]/20 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-4">
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter mb-4 drop-shadow-2xl fade-in-up">
                My <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-[#f4e4c1] to-[#c2a265]">Bookings</span>
            </h1>
            <p class="text-base md:text-lg text-gray-300 font-medium max-w-xl mx-auto fade-in-up leading-relaxed" style="animation-delay: 0.1s;">
                Manage your upcoming luxury escapes and view your past adventures.
            </p>
        </div>
    </div>

    {{-- ==========================================
         2. MAIN CONTENT
         ========================================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-30">

        {{-- Actions Bar --}}
        <div class="flex flex-col sm:flex-row justify-between items-center bg-white p-4 rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-100 mb-12 gap-4 fade-in-up" style="animation-delay: 0.2s;">
            <div class="px-5">
                <p class="text-sm font-black text-gray-800 uppercase tracking-widest flex items-center gap-2">
                    Total Escapes: <span class="bg-[#1d5c42]/10 text-[#1d5c42] px-3 py-1 rounded-lg text-lg">{{ $bookings->count() }}</span>
                </p>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('favorites.index') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gray-50 border border-gray-200 text-gray-600 font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-gray-100 hover:text-red-500 transition-colors shadow-sm flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    Favorites
                </a>
                <a href="{{ route('explore') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gradient-to-r from-[#1d5c42] to-[#154230] text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:shadow-[0_8px_25px_rgba(29,92,66,0.4)] transition-all flex items-center gap-2 active:scale-95 hover:-translate-y-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Explore More
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-8 bg-green-50 border border-green-200 p-5 rounded-2xl flex items-center gap-4 shadow-sm fade-in-up" style="animation-delay: 0.3s;">
                <div class="bg-green-500 p-2 rounded-full text-white shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                <p class="text-green-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-8 bg-red-50 border border-red-200 p-5 rounded-2xl flex items-center gap-4 shadow-sm fade-in-up" style="animation-delay: 0.3s;">
                <div class="bg-red-500 p-2 rounded-full text-white shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                <p class="text-red-800 font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Grid Content --}}
        @if ($bookings->isEmpty())
            <div class="flex flex-col items-center justify-center py-32 bg-white rounded-[3rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 fade-in-up" style="animation-delay: 0.3s;">
                <div class="w-28 h-28 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-8 border border-gray-100 shadow-inner">
                    <svg class="w-14 h-14 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900 mb-3 tracking-tight">No Escapes Booked Yet</h3>
                <p class="text-gray-500 mb-10 font-medium text-lg">You haven't made any farm bookings yet. It's time for a break!</p>
                <a href="{{ route('explore') }}" class="px-10 py-5 bg-gradient-to-r from-[#1d5c42] to-[#154230] text-white font-black text-sm uppercase tracking-widest rounded-2xl hover:shadow-[0_10px_30px_rgba(29,92,66,0.4)] transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                    Book Your First Farm
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 lg:gap-10">
                @foreach ($bookings as $index => $booking)
                    @php
                        // Status styling logic
                        $rawStatus = strtolower($booking->status ?? 'pending');
                        $statusClasses = '';
                        $statusIcon = '';
                        $statusBg = '';

                        if($rawStatus === 'confirmed' || $rawStatus === 'paid') {
                            $statusClasses = 'text-green-700 bg-green-100/50 border-green-200';
                            $statusBg = 'bg-green-50/50';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>';
                        } elseif($rawStatus === 'cancelled') {
                            $statusClasses = 'text-red-700 bg-red-100/50 border-red-200';
                            $statusBg = 'bg-red-50/50';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>';
                        } else {
                            // Pending / Pending Verification
                            $statusClasses = 'text-amber-700 bg-amber-100/50 border-amber-200';
                            $statusBg = 'bg-amber-50/50';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                        }

                        // Clean up the text for display
                        $displayStatus = ucwords(str_replace('_', ' ', $rawStatus));

                        // Time & Range Logic
                        $startDate = \Carbon\Carbon::parse($booking->start_time);
                        $endDate = \Carbon\Carbon::parse($booking->end_time);
                        $isMultiDay = $startDate->startOfDay()->diffInDays($endDate->startOfDay()) > 1;
                    @endphp

                    <div class="booking-card-wrapper fade-in-up-stagger group bg-white rounded-[2.5rem] shadow-[0_10px_30px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.12)] border border-gray-100 overflow-hidden transition-all duration-500 flex flex-col h-full relative" style="animation-delay: {{ 0.1 + ($index * 0.1) }}s;">

                        {{-- Image Header --}}
                        <div class="relative h-64 overflow-hidden rounded-t-[2.5rem]">
                            <img src="{{ $booking->farm->main_image ? asset('storage/' . $booking->farm->main_image) : asset('backgrounds/home.JPG') }}"
                                 onerror="this.onerror=null;this.src='{{ asset('backgrounds/home.JPG') }}';"
                                 alt="Farm Image"
                                 class="booking-image w-full h-full object-cover transition-transform duration-[1.5s] ease-out text-transparent">

                            {{-- Gradient Overlay for text readability --}}
                            <div class="image-gradient-overlay absolute inset-0"></div>

                            {{-- Rating Badge (Top Right) --}}
                            <div class="absolute top-5 right-5 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-2xl text-xs font-black text-gray-900 shadow-lg flex items-center gap-1.5 z-10 border border-white/50">
                                <svg class="w-3.5 h-3.5 text-[#c2a265]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ number_format($booking->farm->average_rating ?? 0, 1) }}
                            </div>

                            {{-- Booking Date overlay on image --}}
                            <div class="absolute bottom-5 left-6 z-10 text-white w-full pr-8">
                                @if($isMultiDay)
                                    <p class="text-[10px] font-black uppercase tracking-widest text-white/70 mb-1 drop-shadow-sm">Stay Dates</p>
                                    <p class="font-black text-xl tracking-tight leading-none drop-shadow-lg flex items-center gap-1">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }} <span class="text-white/50 text-sm font-normal">→</span> {{ \Carbon\Carbon::parse($booking->end_time)->format('M d, Y') }}
                                    </p>
                                @else
                                    <p class="text-[10px] font-black uppercase tracking-widest text-white/70 mb-1 drop-shadow-sm">Check-in Date</p>
                                    <p class="font-black text-3xl tracking-tight leading-none drop-shadow-lg">
                                        {{ $startDate->format('M d, Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-6 md:p-8 flex flex-col flex-1 bg-white">

                            {{-- 🌟 PROMINENT STATUS BADGE --}}
                            <div class="mb-5 flex items-center w-full">
                                <div class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl {{ $statusClasses }} border-2 font-black text-xs uppercase tracking-widest shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $statusIcon !!}</svg>
                                    {{ $displayStatus }}
                                </div>
                            </div>

                            <h2 class="text-2xl font-black text-gray-900 mb-6 group-hover:text-[#1d5c42] transition-colors line-clamp-1 tracking-tight">
                                {{ $booking->farm->name }}
                            </h2>

                            {{-- Clean Info Box --}}
                            <div class="space-y-0 mb-8 {{ $statusBg }} rounded-2xl border border-gray-100/50 overflow-hidden shadow-[inset_0_2px_10px_rgba(0,0,0,0.02)]">

                                {{-- Booking ID --}}
                                <div class="flex items-center justify-between p-4 border-b border-gray-200/50">
                                     <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Booking ID</p>
                                     <p class="text-xs font-black text-gray-900 tracking-widest">#MZ-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>

                                {{-- Event Type --}}
                                <div class="flex items-center justify-between p-4 border-b border-gray-200/50">
                                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Occasion</p>
                                    <p class="text-xs font-bold text-gray-800 capitalize">{{ $booking->event_type ?? 'Standard Stay' }}</p>
                                </div>

                                {{-- Stay Dates --}}
                                <div class="flex items-center justify-between p-4 border-b border-gray-200/50">
                                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Stay Dates</p>
                                    <p class="text-xs font-bold text-gray-800 text-right flex items-center gap-1 justify-end">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}
                                        <span class="text-gray-400">➝</span>
                                        {{ \Carbon\Carbon::parse($booking->end_time)->format('M d, Y') }}
                                    </p>
                                </div>
                                {{-- Time --}}
                                <div class="flex items-center justify-between p-4">
                                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Time</p>
                                    <p class="text-xs font-bold text-gray-800 text-right flex items-center gap-1 justify-end">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                        <span class="text-gray-400">➝</span>
                                        {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                    </p>
                                </div>

                            </div>

                            {{-- Action Buttons --}}
                            <div class="mt-auto grid grid-cols-2 gap-3">
                                {{-- View --}}
                                <a href="{{ route('bookings.show', $booking->id) }}"
                                   class="py-4 bg-gray-900 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-[#1d5c42] hover:shadow-[0_8px_20px_rgba(29,92,66,0.3)] transition-all text-center active:scale-95 flex items-center justify-center gap-2">
                                    View Details
                                </a>

                                {{-- Cancel Form (Only if pending or not completed) --}}
                                @if($rawStatus !== 'cancelled' && $rawStatus !== 'completed')
                                <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');"
                                      class="w-full h-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full h-full py-4 bg-white text-red-500 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-red-50 hover:text-red-600 transition-colors border-2 border-red-100 text-center shadow-sm active:scale-95 flex items-center justify-center gap-2">
                                        Cancel
                                    </button>
                                </form>
                                @else
                                <div class="py-4 bg-gray-50 text-gray-400 font-black text-[10px] uppercase tracking-widest rounded-2xl border-2 border-gray-100 text-center cursor-not-allowed flex items-center justify-center gap-1.5 h-full shadow-inner">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Locked
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
