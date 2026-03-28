@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<style>
    /* Smooth Fade In Stagger */
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* Ticket styling */
    .ticket-dashed-border {
        background-image: linear-gradient(to bottom, #e5e7eb 50%, transparent 50%);
        background-size: 2px 20px;
        background-repeat: repeat-y;
        width: 2px;
    }

    /* Gradient overlay for images */
    .image-gradient-overlay {
        background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.4) 50%, rgba(15, 23, 42, 0) 100%);
    }
</style>

<div class="bg-[#f8fafc] min-h-screen pb-24 font-sans pt-36 selection:bg-[#1d5c42] selection:text-white">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header & Back Button --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4 fade-in-up">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white text-gray-500 text-[10px] font-black uppercase tracking-widest mb-3 border border-gray-200 shadow-sm">
                    Booking Reference
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">
                    #MZ-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                </h1>
            </div>

            <a href="{{ route('bookings.my_bookings') }}"
               class="inline-flex items-center gap-2 px-6 py-4 bg-white border border-gray-200 text-gray-700 font-black text-[10px] md:text-xs uppercase tracking-widest rounded-2xl hover:bg-gray-50 hover:text-[#1d5c42] hover:border-[#1d5c42]/30 transition-all shadow-[0_4px_15px_rgba(0,0,0,0.03)] active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Bookings
            </a>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-8 bg-green-50 border border-green-200 p-5 rounded-2xl flex items-center gap-4 shadow-sm fade-in-up" style="animation-delay: 0.1s;">
                <div class="bg-green-500 p-2 rounded-full text-white shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                <p class="text-green-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @php
            // Logic for Dates and Status
            $startDate = \Carbon\Carbon::parse($booking->start_time);
            $endDate = \Carbon\Carbon::parse($booking->end_time);

            // 💡 FIX: Using copy() prevents resetting the original time to 12:00 AM!
            $isMultiDay = $startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay()) > 1;

            $rawStatus = strtolower($booking->status ?? 'pending');
            $statusClasses = '';
            $statusIcon = '';
            $statusBg = '';

            if($rawStatus === 'confirmed' || $rawStatus === 'paid') {
                $statusClasses = 'text-green-700 bg-green-100/80 border-green-200';
                $statusBg = 'bg-green-50/40';
                $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>';
            } elseif($rawStatus === 'cancelled') {
                $statusClasses = 'text-red-700 bg-red-100/80 border-red-200';
                $statusBg = 'bg-red-50/40';
                $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>';
            } else {
                $statusClasses = 'text-amber-700 bg-amber-100/80 border-amber-200';
                $statusBg = 'bg-amber-50/40';
                $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
            }
            $displayStatus = ucwords(str_replace('_', ' ', $rawStatus));
        @endphp

        {{-- Main Ticket Container --}}
        <div class="bg-white rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden border border-gray-100 flex flex-col md:flex-row fade-in-up" style="animation-delay: 0.2s;">

            {{-- LEFT: Farm Image & Basic Info --}}
            <div class="relative w-full md:w-[45%] h-80 md:h-auto min-h-[450px]">
                <img src="{{ $booking->farm->main_image ? asset('storage/' . $booking->farm->main_image) : asset('backgrounds/home.JPG') }}"
                     onerror="this.onerror=null;this.src='{{ asset('backgrounds/home.JPG') }}';"
                     alt="Farm Image"
                     class="w-full h-full object-cover text-transparent">

                <div class="image-gradient-overlay absolute inset-0"></div>

                {{-- Status Badge on Image --}}
                <div class="absolute top-6 left-6 z-20 px-4 py-2 rounded-xl {{ $statusClasses }} border-2 font-black text-xs uppercase tracking-widest shadow-lg flex items-center gap-2 backdrop-blur-md bg-white/90">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $statusIcon !!}</svg>
                    {{ $displayStatus }}
                </div>

                {{-- Farm Info --}}
                <div class="absolute bottom-6 left-6 right-6 z-20">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-lg text-white text-[10px] font-black uppercase tracking-widest border border-white/20 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ number_format($booking->farm->average_rating ?? 0, 1) }}
                        </div>
                        <div class="bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-lg text-white text-[10px] font-black uppercase tracking-widest border border-white/20 capitalize">
                            {{ $booking->event_type ?? 'Standard Stay' }}
                        </div>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight leading-none drop-shadow-xl mb-2">
                        {{ $booking->farm->name }}
                    </h2>
                    <p class="text-white/80 font-medium text-sm flex items-center gap-1.5 drop-shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ $booking->farm->location }}
                    </p>
                </div>
            </div>

            {{-- Visual Divider for Desktop --}}
            <div class="hidden md:flex flex-col justify-between items-center py-8 bg-gray-50/50">
                <div class="w-6 h-6 rounded-full bg-[#f8fafc] -mt-11 shadow-inner"></div>
                <div class="ticket-dashed-border h-full mx-3 opacity-50"></div>
                <div class="w-6 h-6 rounded-full bg-[#f8fafc] -mb-11 shadow-inner"></div>
            </div>

            {{-- RIGHT: Booking Details & Actions --}}
            <div class="p-8 md:p-10 w-full md:w-[55%] flex flex-col justify-between">

                <div class="space-y-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-4 mb-6">Reservation Details</h3>

                    {{-- Dates Box --}}
                    <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100 shadow-[inset_0_2px_10px_rgba(0,0,0,0.02)]">

                        {{-- Date Section (Shows Check-in/Check-out for all types of bookings) --}}
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Check-in</p>
                                <p class="text-lg font-black text-gray-900 tracking-tight">{{ $startDate->format('M d, Y') }}</p>
                            </div>
                            <div class="px-4 text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Check-out</p>
                                <p class="text-lg font-black text-gray-900 tracking-tight">{{ $endDate->format('M d, Y') }}</p>
                            </div>
                        </div>

                        {{-- Time Section --}}
                        <div class="pt-4 border-t border-gray-200/60 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-[#1d5c42]/10 flex items-center justify-center text-[#1d5c42]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Access Time</p>
                            </div>
                            <p class="text-sm font-bold text-gray-800">
                                {{ $startDate->format('h:i A') }} <span class="text-gray-400 mx-1">➝</span> {{ $endDate->format('h:i A') }}
                            </p>
                        </div>
                    </div>

                </div>

                <div class="mt-10">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6 mb-8 p-6 {{ $statusBg }} rounded-3xl border border-gray-100 shadow-sm">
                        <div class="text-center sm:text-left w-full">
                            <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest mb-1">Total Payment</p>
                            <div class="flex items-baseline justify-center sm:justify-start gap-1">
                                <p class="text-4xl font-black text-gray-900 tracking-tighter">{{ number_format($booking->total_price ?? ($booking->farm->price_per_night * 1.16), 2) }}</p>
                                <span class="text-sm font-bold text-gray-500">JOD</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a href="{{ route('bookings.edit', $booking->id) }}"
                           class="py-4 bg-gray-900 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-[#1d5c42] hover:shadow-[0_8px_20px_rgba(29,92,66,0.3)] transition-all text-center shadow-md active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Modify Booking
                        </a>

                        @if($rawStatus !== 'cancelled' && $rawStatus !== 'completed')
                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel? This action cannot be undone.');" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-4 bg-white text-red-500 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-red-50 hover:text-red-600 transition-colors border-2 border-red-100 text-center shadow-sm active:scale-95 flex items-center justify-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Cancel Booking
                            </button>
                        </form>
                        @else
                        <div class="py-4 bg-gray-50 text-gray-400 font-black text-[10px] uppercase tracking-widest rounded-2xl border-2 border-gray-100 text-center cursor-not-allowed flex items-center justify-center gap-2 shadow-inner">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Modification Locked
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

