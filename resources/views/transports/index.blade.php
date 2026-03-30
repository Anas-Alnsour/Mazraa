@extends('layouts.app')

@section('title', 'My Transport Bookings')

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
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-[#blue-600]/20 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-4">
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter mb-4 drop-shadow-2xl fade-in-up">
                My <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-200 to-blue-500">Transports</span>
            </h1>
            <p class="text-base md:text-lg text-gray-300 font-medium max-w-xl mx-auto fade-in-up leading-relaxed" style="animation-delay: 0.1s;">
                Track and manage your luxury farm shuttle round-trips.
            </p>
        </div>
    </div>

    {{-- ==========================================
         2. MAIN CONTENT
         ========================================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-30">

        {{-- Actions Bar --}}
        <div class="flex flex-col sm:flex-row justify-between items-center bg-white p-4 rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-100 mb-8 gap-4 fade-in-up" style="animation-delay: 0.2s;">
            <div class="px-5">
                <p class="text-sm font-black text-gray-800 uppercase tracking-widest flex items-center gap-2">
                    Total Shuttles: <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-lg border border-blue-100">{{ $transports->count() }}</span>
                </p>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('bookings.my_bookings') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gray-50 border border-gray-200 text-gray-600 font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-gray-100 hover:text-[#1d5c42] transition-colors shadow-sm flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Farm Bookings
                </a>
                <a href="{{ route('explore') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:shadow-[0_8px_25px_rgba(37,99,235,0.4)] transition-all flex items-center gap-2 active:scale-95 hover:-translate-y-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Book New Trip
                </a>
            </div>
        </div>

        {{-- 💡 TRANSPORT POLICY DISCLAIMER --}}
        <div class="bg-blue-50/80 border border-blue-100 p-6 mb-10 rounded-2xl shadow-sm fade-in-up" style="animation-delay: 0.25s;">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 bg-blue-100 p-2 rounded-xl text-blue-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-blue-900 uppercase tracking-widest mb-1">Transport Booking Policy</h3>
                    <div class="text-sm text-blue-800/80 font-medium leading-relaxed">
                        <p>
                            Transport can only be reserved alongside a farm booking. If you forgot to add a shuttle, you can add it by <strong class="text-blue-900">editing your farm booking</strong>, provided your check-in is <strong class="text-blue-900">more than 48 hours away</strong>. For urgent requests within 48 hours, please contact Technical Support.
                        </p>
                    </div>
                </div>
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
        @if ($transports->isEmpty())
            <div class="flex flex-col items-center justify-center py-32 bg-white rounded-[3rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 fade-in-up" style="animation-delay: 0.3s;">
                <div class="w-28 h-28 bg-blue-50 rounded-[2rem] flex items-center justify-center mb-8 border border-blue-100 shadow-inner">
                    <svg class="w-14 h-14 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900 mb-3 tracking-tight">No Transport Bookings Yet</h3>
                <p class="text-gray-500 mb-10 font-medium text-lg">Transport is booked directly alongside your premium farm stays.</p>
                <a href="{{ route('explore') }}" class="px-10 py-5 bg-gradient-to-r from-[#1d5c42] to-[#154230] text-white font-black text-sm uppercase tracking-widest rounded-2xl hover:shadow-[0_10px_30px_rgba(29,92,66,0.4)] transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                    Book a Farm & Shuttle
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 lg:gap-10">
                @foreach ($transports as $index => $transport)
                    @php
                        // Status styling logic for Transports
                        $rawStatus = strtolower($transport->status ?? 'pending');
                        $statusClasses = '';
                        $statusIcon = '';
                        $statusBg = '';

                        if($rawStatus === 'completed') {
                            $statusClasses = 'text-green-700 bg-green-100/50 border-green-200';
                            $statusBg = 'bg-green-50/50';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>';
                        } elseif($rawStatus === 'cancelled' || $rawStatus === 'rejected') {
                            $statusClasses = 'text-red-700 bg-red-100/50 border-red-200';
                            $statusBg = 'bg-red-50/50';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>';
                        } elseif(in_array($rawStatus, ['accepted', 'assigned', 'in_progress'])) {
                            $statusClasses = 'text-[#1d5c42] bg-[#1d5c42]/10 border-[#1d5c42]/20';
                            $statusBg = 'bg-[#1d5c42]/5';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"></path>';
                        } else {
                            // Pending
                            $statusClasses = 'text-amber-700 bg-amber-100/50 border-amber-200';
                            $statusBg = 'bg-amber-50/50';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                        }

                        $displayStatus = ucwords(str_replace('_', ' ', $rawStatus));
                    @endphp

                    <div class="booking-card-wrapper fade-in-up-stagger group bg-white rounded-[2.5rem] shadow-[0_10px_30px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.12)] border border-gray-100 overflow-hidden transition-all duration-500 flex flex-col h-full relative" style="animation-delay: {{ 0.1 + ($index * 0.1) }}s;">

                        {{-- Image Header (Uses Destination Farm Image) --}}
                        <div class="relative h-64 overflow-hidden rounded-t-[2.5rem]">
                            <img src="{{ optional($transport->farm)->main_image ? asset('storage/' . $transport->farm->main_image) : asset('backgrounds/home.JPG') }}"
                                 onerror="this.onerror=null;this.src='{{ asset('backgrounds/home.JPG') }}';"
                                 alt="Farm Image"
                                 class="booking-image w-full h-full object-cover transition-transform duration-[1.5s] ease-out text-transparent">

                            {{-- Gradient Overlay --}}
                            <div class="image-gradient-overlay absolute inset-0"></div>

                            {{-- Passengers Badge (Top Right) --}}
                            <div class="absolute top-5 right-5 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-2xl text-xs font-black text-gray-900 shadow-lg flex items-center gap-1.5 z-10 border border-white/50">
                                <svg class="w-3.5 h-3.5 text-[#c2a265]" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                                {{ $transport->passengers }} Pax
                            </div>

                            {{-- Arrival Date overlay --}}
                            <div class="absolute bottom-5 left-6 z-10 text-white w-full pr-8">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/70 mb-1 drop-shadow-sm">Estimated Arrival</p>
                                <p class="font-black text-xl tracking-tight leading-none drop-shadow-lg flex items-center gap-2">
                                    {{ optional($transport->Farm_Arrival_Time)->format('M d, Y') ?? 'N/A' }}
                                    @if($transport->Farm_Arrival_Time)
                                        <span class="text-xs font-bold text-white/80 bg-black/30 px-2 py-1 rounded-lg backdrop-blur-sm">{{ $transport->Farm_Arrival_Time->format('h:i A') }}</span>
                                    @endif
                                </p>
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

                            <h2 class="text-2xl font-black text-gray-900 mb-1 group-hover:text-blue-600 transition-colors line-clamp-1 tracking-tight">
                                {{ $transport->transport_type }}
                            </h2>
                            <p class="text-xs font-bold text-[#c2a265] uppercase tracking-widest mb-6">
                                {{ number_format($transport->price, 2) }} JOD
                            </p>

                            {{-- Clean Info Box --}}
                            <div class="space-y-0 mb-8 {{ $statusBg }} rounded-2xl border border-gray-100/50 overflow-hidden shadow-[inset_0_2px_10px_rgba(0,0,0,0.02)]">

                                {{-- Route --}}
                                <div class="flex items-center justify-between p-4 border-b border-gray-200/50">
                                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Route</p>
                                    <div class="text-[11px] font-bold text-gray-800 text-right flex items-center gap-1 justify-end w-2/3">
                                        <span class="truncate">{{ $transport->start_and_return_point }}</span>
                                        <span class="text-[#c2a265] flex-shrink-0">➝</span>
                                        <span class="truncate text-[#1d5c42]">{{ optional($transport->farm)->name ?? 'Destination' }}</span>
                                    </div>
                                </div>

                                {{-- Assigned Fleet Company --}}
                                <div class="flex items-center justify-between p-4 border-b border-gray-200/50">
                                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Fleet Partner</p>
                                    @if($transport->company)
                                        <p class="text-xs font-bold text-gray-900 truncate max-w-[60%] capitalize">{{ $transport->company->name }}</p>
                                    @else
                                        <p class="text-[9px] font-bold text-amber-600 uppercase tracking-widest animate-pulse">Assigning...</p>
                                    @endif
                                </div>

                                {{-- 💡 Driver Details (منفصل ومربوط بالداتابيز) --}}
                                <div class="flex items-center justify-between p-4 border-b border-gray-200/50">
                                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Driver</p>
                                    @if($transport->driver)
                                        <p class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                            <span class="text-gray-400">👤</span> {{ $transport->driver->name }}
                                        </p>
                                    @else
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Not Assigned</p>
                                    @endif
                                </div>

                                {{-- 💡 Vehicle Details (منفصل ومربوط بالداتابيز) --}}
                                <div class="flex items-center justify-between p-4">
                                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Vehicle</p>
                                    @if($transport->vehicle)
                                        <div class="text-right flex flex-col items-end gap-1.5">
                                            <p class="text-[11px] font-bold text-gray-800 flex items-center gap-1.5">
                                                <span class="text-gray-400">🚐</span>
                                                {{ $transport->vehicle->name ?? $transport->vehicle->model ?? 'Shuttle' }}
                                                @if($transport->vehicle->color)
                                                    <span class="text-gray-300 mx-0.5">|</span> {{ $transport->vehicle->color }}
                                                @endif
                                            </p>
                                            <div class="bg-gray-100 border border-gray-200 px-2 py-0.5 rounded shadow-sm text-[9px] font-black text-gray-700 uppercase tracking-widest">
                                                {{ $transport->vehicle->license_plate }}
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Not Assigned</p>
                                    @endif
                                </div>

                            </div>

                            {{-- Action Buttons --}}
                            <div class="mt-auto grid grid-cols-2 gap-3">
                                {{-- View Details --}}
                                <a href="{{ route('transports.show', $transport->id) }}"
                                   class="py-4 bg-gray-900 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-[#1d5c42] hover:shadow-[0_8px_20px_rgba(29,92,66,0.3)] transition-all text-center active:scale-95 flex items-center justify-center gap-2">
                                    View Details
                                </a>

                                {{-- Cancel Form (Only if pending) --}}
                                @if($rawStatus === 'pending')
                                <form action="{{ route('transports.destroy', $transport->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to cancel this transport request?');"
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
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Locked
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            @if($transports->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $transports->links() }}
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
