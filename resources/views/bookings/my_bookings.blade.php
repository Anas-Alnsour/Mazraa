@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')

<style>
    /* Premium Animations & Utilities */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }
    .animate-float-slow { animation: float 8s ease-in-out infinite; }

    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }

    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* Image Zoom Transition */
    .image-zoom { transition: transform 1.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .booking-card-wrapper:hover .image-zoom { transform: scale(1.08); }

    /* Elegant Ticket Divider */
    .ticket-divider {
        border-bottom: 1px dashed rgba(0, 0, 0, 0.08);
    }
</style>

<div class="bg-[#f4f7f6] min-h-screen pb-24 font-sans selection:bg-[#1d5c42] selection:text-white" x-data="{
    reviewModalOpen: false,
    reviewableId: null,
    reviewableType: 'farm',
    rating: 0,
    hoverRating: 0,
    comment: '',
    farmName: '',
    openReviewModal(id, name) {
        this.reviewableId = id;
        this.farmName = name;
        this.rating = 0;
        this.hoverRating = 0;
        this.comment = '';
        this.reviewModalOpen = true;
    },
    closeReviewModal() {
        this.reviewModalOpen = false;
    }
}">

    {{-- ==========================================
         1. PREMIUM HERO SECTION (MINI DARK MODE)
         ========================================== --}}
    <div class="relative w-full h-[40vh] min-h-[350px] flex flex-col justify-center items-center bg-[#0a0a0a] overflow-hidden pt-12">

        {{-- High Quality Background Image --}}
        <div class="absolute inset-0 w-full h-full overflow-hidden">
            <img src="{{ asset('backgrounds/home.JPG') }}" alt="My Bookings"
                 class="w-full h-full object-cover opacity-30 scale-105 animate-[pulse_25s_ease-in-out_infinite] grayscale-[20%]">
        </div>

        {{-- Vignette & Smooth Gradient Overlays --}}
        <div class="absolute inset-0 shadow-[inset_0_0_150px_rgba(0,0,0,0.9)]"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#f4f7f6] via-transparent to-[#0a0a0a]/60"></div>

        {{-- Glowing Orbs for Depth --}}
        <div class="absolute top-1/4 left-1/4 w-[30rem] h-[30rem] bg-[#1d5c42]/20 rounded-full blur-[120px] animate-float-slow pointer-events-none mix-blend-screen"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[25rem] h-[25rem] bg-[#c2a265]/15 rounded-full blur-[100px] animate-float-slow pointer-events-none mix-blend-screen" style="animation-delay: 2s;"></div>

        {{-- Main Hero Text Container --}}
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto pb-10 fade-in-up">
            <div class="inline-flex items-center gap-2 py-1.5 px-5 rounded-full bg-white/5 border border-white/10 text-[#f4e4c1] text-[10px] font-black tracking-[0.25em] uppercase backdrop-blur-xl mb-5 shadow-2xl">
                <span class="w-1.5 h-1.5 rounded-full bg-[#c2a265] animate-ping absolute"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#c2a265] relative"></span>
                Itinerary Overview
            </div>

            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4 drop-shadow-2xl">
                My <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-[#f4e4c1] to-[#c2a265] italic font-serif font-medium pr-2">Bookings</span>
            </h1>

            <p class="text-sm md:text-base text-gray-300 font-medium max-w-lg mx-auto leading-relaxed drop-shadow-lg opacity-90">
                Manage your upcoming luxury escapes, view past adventures, and handle your transport logistics seamlessly.
            </p>
        </div>
    </div>

    {{-- ==========================================
         2. MAIN CONTENT (ACTIONS & GRID)
         ========================================== --}}
    <div class="max-w-[96%] xl:max-w-7xl mx-auto relative z-30 -mt-20">

        {{-- Glassmorphic Actions Bar --}}
        <div class="bg-white/90 backdrop-blur-2xl rounded-[2rem] p-4 lg:p-5 shadow-[0_20px_40px_-10px_rgba(0,0,0,0.08)] border border-white flex flex-col sm:flex-row justify-between items-center gap-4 mb-12 fade-in-up" style="animation-delay: 0.2s;">
            <div class="px-3 flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-[#1d5c42]/10 text-[#1d5c42] flex items-center justify-center font-black text-xl shadow-inner border border-[#1d5c42]/20">
                    {{ $bookings->count() }}
                </div>
                <div>
                    <p class="text-sm font-black text-gray-900 uppercase tracking-widest">Total Escapes</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Recorded in your history</p>
                </div>
            </div>

            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('favorites.index') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gray-50 border border-gray-200 text-gray-600 font-black text-[10px] md:text-xs uppercase tracking-widest rounded-xl hover:bg-white hover:text-rose-500 hover:border-rose-200 hover:shadow-md transition-all flex items-center gap-2 active:scale-95 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    <span class="hidden md:inline">Saved</span> Favorites
                </a>
                <a href="{{ route('explore') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gradient-to-tr from-[#1d5c42] to-[#14402e] hover:to-[#0f3022] text-white font-black text-[10px] md:text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-[#1d5c42]/30 hover:shadow-xl hover:shadow-[#1d5c42]/40 transition-all flex items-center gap-2 active:scale-95 group border border-white/10">
                    <svg class="w-4 h-4 group-hover:rotate-45 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    New Booking
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-8 bg-emerald-50 border border-emerald-100 p-5 rounded-2xl flex items-center gap-4 shadow-sm fade-in-up" style="animation-delay: 0.3s;">
                <div class="bg-emerald-500 p-2 rounded-full text-white shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                <p class="text-emerald-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-8 bg-rose-50 border border-rose-100 p-5 rounded-2xl flex items-center gap-4 shadow-sm fade-in-up" style="animation-delay: 0.3s;">
                <div class="bg-rose-500 p-2 rounded-full text-white shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                <p class="text-rose-800 font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Grid Content --}}
        @if ($bookings->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 fade-in-up" style="animation-delay: 0.4s;">
                <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-6 border border-gray-100 shadow-inner">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-2xl md:text-3xl font-black text-gray-900 mb-2 tracking-tight">No Escapes Booked Yet</h3>
                <p class="text-gray-500 mb-8 font-medium text-sm md:text-base max-w-sm text-center">Your itinerary is currently empty. It's time to discover a new destination and take a break!</p>
                <a href="{{ route('explore') }}" class="px-8 py-4 bg-gradient-to-tr from-[#1d5c42] to-[#14402e] text-white font-black text-xs uppercase tracking-widest rounded-xl hover:shadow-[0_10px_30px_rgba(29,92,66,0.3)] transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                    Start Exploring
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($bookings as $index => $booking)
                    @php
                        // Status styling logic (Refined Premium Colors)
                        $rawStatus = strtolower($booking->status ?? 'pending');
                        $statusClasses = '';
                        $statusIcon = '';

                        if($rawStatus === 'confirmed' || $rawStatus === 'paid') {
                            $statusClasses = 'text-emerald-700 bg-emerald-50 border-emerald-200';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>';
                        } elseif($rawStatus === 'cancelled') {
                            $statusClasses = 'text-rose-700 bg-rose-50 border-rose-200';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>';
                        } else {
                            // Pending / Pending Verification
                            $statusClasses = 'text-amber-700 bg-amber-50 border-amber-200';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                        }

                        $displayStatus = ucwords(str_replace('_', ' ', $rawStatus));

                        // Time & Range Logic
                        $startDate = \Carbon\Carbon::parse($booking->start_time);
                        $endDate = \Carbon\Carbon::parse($booking->end_time);
                        $isMultiDay = $startDate->startOfDay()->diffInDays($endDate->startOfDay()) > 1;
                    @endphp

                    <div class="booking-card-wrapper fade-in-up-stagger group bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] hover:shadow-[0_25px_50px_rgba(29,92,66,0.1)] border border-gray-100 hover:border-[#1d5c42]/20 overflow-hidden transition-all duration-500 flex flex-col h-full relative" style="animation-delay: {{ 0.2 + ($index * 0.1) }}s;">

                        {{-- Image Header (Inside a padding frame like the new explore page) --}}
                        <div class="p-3 pb-0">
                            <div class="relative h-56 md:h-64 overflow-hidden rounded-[1.5rem] bg-gray-100 shadow-inner">
                                <img src="{{ $booking->farm?->main_image ? asset('storage/' . $booking->farm->main_image) : asset('backgrounds/home.JPG') }}"
                                     onerror="this.onerror=null;this.src='{{ asset('backgrounds/home.JPG') }}';"
                                     alt="Farm Image"
                                     class="image-zoom w-full h-full object-cover text-transparent relative -z-10">

                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                                {{-- Rating Badge --}}
                                @if(isset($booking->farm->average_rating) && $booking->farm->average_rating > 0)
                                    <div class="absolute top-3 right-3 bg-black/40 backdrop-blur-md px-3 py-1.5 rounded-xl text-[11px] font-bold text-white tracking-widest shadow-lg flex items-center gap-1.5 z-10 border border-white/20">
                                        <svg class="w-3.5 h-3.5 text-[#f4e4c1]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        {{ number_format($booking->farm->average_rating, 1) }}
                                    </div>
                                @endif

                                {{-- Booking Dates Overlay --}}
                                <div class="absolute bottom-4 left-5 z-10 text-white w-full pr-5">
                                    @if($isMultiDay)
                                        <p class="text-[9px] font-black uppercase tracking-widest text-[#f4e4c1] mb-1 drop-shadow-sm">Stay Duration</p>
                                        <p class="font-black text-lg tracking-tight leading-none drop-shadow-lg flex items-center gap-1">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('M d') }} <span class="text-white/50 text-sm font-normal">→</span> {{ \Carbon\Carbon::parse($booking->end_time)->format('M d, Y') }}
                                        </p>
                                    @else
                                        <p class="text-[9px] font-black uppercase tracking-widest text-[#f4e4c1] mb-1 drop-shadow-sm">Check-in Date</p>
                                        <p class="font-black text-2xl md:text-3xl tracking-tight leading-none drop-shadow-lg">
                                            {{ $startDate->format('M d, Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="px-4 md:px-6 pt-5 pb-6 flex flex-col flex-1">

                            <div class="mb-4 flex flex-wrap items-center gap-2 w-full">
                                {{-- Booking Status Badge --}}
                                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg {{ $statusClasses }} border font-black text-[9px] uppercase tracking-widest shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $statusIcon !!}</svg>
                                    {{ $displayStatus }}
                                </div>

                                {{-- Transport Status Badge --}}
                                @if($booking->requires_transport && $booking->transport)
                                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-cyan-50 text-cyan-700 border border-cyan-200 font-black text-[9px] uppercase tracking-widest shadow-sm">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                        {{ str_replace('_', ' ', $booking->transport->status) }}
                                    </div>
                                @endif
                            </div>

                            <h2 class="text-xl md:text-2xl font-black text-gray-900 mb-5 group-hover:text-[#1d5c42] transition-colors line-clamp-1 tracking-tight">
                                {{ $booking->farm?->name ?? 'Deleted Farm' }}
                            </h2>

                            {{-- Clean Premium Ticket Box --}}
                            <div class="mb-6 bg-gray-50/60 rounded-2xl border border-gray-100 overflow-hidden shadow-[inset_0_2px_10px_rgba(0,0,0,0.01)]">

                                {{-- Booking ID --}}
                                <div class="flex items-center justify-between p-3.5 ticket-divider">
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Booking ID</p>
                                    <p class="text-[11px] font-black text-gray-900 tracking-widest">#MZ-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>

                                {{-- Occasion --}}
                                <div class="flex items-center justify-between p-3.5 ticket-divider">
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Occasion</p>
                                    <p class="text-[11px] font-bold text-gray-800 capitalize">{{ $booking->event_type ?? 'Standard Stay' }}</p>
                                </div>

                                {{-- Time --}}
                                <div class="flex items-center justify-between p-3.5">
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Time</p>
                                    <div class="flex items-center gap-1.5 text-[11px] font-bold text-gray-800">
                                        <span class="bg-white px-2 py-0.5 rounded shadow-sm border border-gray-100">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</span>
                                        <span class="text-gray-300 text-[10px]">➝</span>
                                        <span class="bg-white px-2 py-0.5 rounded shadow-sm border border-gray-100">{{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons (Grid Layout) --}}
                            <div class="mt-auto grid grid-cols-2 gap-2">
                                {{-- View Details --}}
                                <a href="{{ route('bookings.show', $booking->id) }}"
                                   class="col-span-1 py-3.5 bg-gray-900 text-white font-black text-[9px] md:text-[10px] uppercase tracking-widest rounded-xl hover:bg-[#1d5c42] hover:shadow-lg hover:shadow-[#1d5c42]/30 transition-all text-center active:scale-95 flex items-center justify-center gap-1.5">
                                    Details
                                </a>

                                {{-- Contextual Second Button --}}
                                @if($rawStatus !== 'cancelled' && $rawStatus !== 'completed' && $rawStatus !== 'finished')
                                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');"
                                          class="col-span-1 h-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full h-full py-3.5 bg-white text-rose-500 font-black text-[9px] md:text-[10px] uppercase tracking-widest rounded-xl hover:bg-rose-50 hover:text-rose-600 transition-colors border border-rose-100 text-center shadow-sm active:scale-95 flex items-center justify-center gap-1.5">
                                            Cancel
                                        </button>
                                    </form>
                                @elseif($rawStatus === 'completed' || $rawStatus === 'finished')
                                    <button type="button" @click="openReviewModal({{ $booking->farm?->id ?? 'null' }}, '{{ addslashes($booking->farm?->name ?? 'Deleted Farm') }}')"
                                            class="col-span-1 h-full py-3.5 bg-white text-emerald-600 font-black text-[9px] md:text-[10px] uppercase tracking-widest rounded-xl hover:bg-emerald-50 hover:border-emerald-300 transition-colors border border-emerald-200 text-center shadow-sm active:scale-95 flex items-center justify-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        Review
                                    </button>
                                @else
                                    <div class="col-span-1 py-3.5 bg-gray-50 text-gray-400 font-black text-[9px] md:text-[10px] uppercase tracking-widest rounded-xl border border-gray-100 text-center cursor-not-allowed flex items-center justify-center gap-1.5 shadow-inner">
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

    {{-- ==========================================
         3. WRITE A REVIEW MODAL (PREMIUM UI)
         ========================================== --}}
    <div x-show="reviewModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            {{-- Backdrop Blur --}}
            <div x-show="reviewModalOpen" x-transition.opacity.duration.400ms class="fixed inset-0 bg-[#0a0a0a]/70 backdrop-blur-md transition-opacity" @click="closeReviewModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Panel --}}
            <div x-show="reviewModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl shadow-[#1d5c42]/20 transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-gray-100">

                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="reviewable_id" x-model="reviewableId">
                    <input type="hidden" name="reviewable_type" x-model="reviewableType">
                    <input type="hidden" name="rating" x-model="rating">

                    {{-- Modal Header --}}
                    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-xl font-black text-gray-900 flex items-center gap-2.5">
                            <div class="bg-[#1d5c42]/10 p-2 rounded-xl text-[#1d5c42]">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            Rate Experience
                        </h3>
                        <button type="button" @click="closeReviewModal" class="text-gray-400 hover:text-rose-500 bg-white rounded-full p-2 shadow-sm border border-gray-100 focus:outline-none transition-all hover:rotate-90">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-8 space-y-8">
                        <div class="text-center">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">How was your stay at</p>
                            <h4 class="text-2xl font-black text-gray-900 tracking-tight" x-text="farmName"></h4>
                        </div>

                        {{-- Dynamic Stars --}}
                        <div>
                            <div class="flex justify-center items-center gap-2 cursor-pointer mb-3" @mouseleave="hoverRating = 0">
                                <template x-for="star in 5">
                                    <svg
                                        @click="rating = star"
                                        @mouseenter="hoverRating = star"
                                        :class="{'text-amber-400 scale-110 drop-shadow-md': (hoverRating || rating) >= star, 'text-gray-100': (hoverRating || rating) < star}"
                                        class="h-12 w-12 fill-current transition-all duration-300 transform"
                                        viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </template>
                            </div>
                            <p class="text-xs text-center font-bold tracking-widest uppercase transition-colors" :class="rating > 0 ? 'text-[#1d5c42]' : 'text-transparent'" x-text="rating + ' out of 5 Stars'"></p>
                        </div>

                        {{-- Comment Box --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Comment <span class="text-gray-300">(Optional)</span></label>
                            <textarea name="comment" x-model="comment" rows="3" placeholder="Tell us what made your stay special..."
                                class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-[#1d5c42]/10 focus:border-[#1d5c42] transition-all resize-none text-sm shadow-inner font-medium text-gray-800 placeholder-gray-400"></textarea>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex gap-3">
                        <button type="submit" :disabled="rating === 0" :class="rating === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[#14402e] hover:shadow-xl hover:shadow-[#1d5c42]/30 hover:-translate-y-0.5 active:scale-95'" class="w-full px-6 py-4 rounded-2xl bg-[#1d5c42] text-white font-black text-[11px] tracking-widest uppercase transition-all flex items-center justify-center gap-2 focus:outline-none">
                            Submit Review
                            <svg x-show="rating > 0" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
