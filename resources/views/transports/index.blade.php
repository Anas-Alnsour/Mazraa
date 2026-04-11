@extends('layouts.app')

@section('title', 'My Transport Bookings')

@section('content')
<style>
    /* Premium Animations */
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

    /* Image Zoom */
    .image-zoom { transition: transform 1.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .booking-card-wrapper:hover .image-zoom { transform: scale(1.08); }

    /* Elegant Ticket Divider */
    .ticket-divider { border-bottom: 1px dashed rgba(0, 0, 0, 0.08); }
</style>

<div class="bg-[#f4f7f6] min-h-screen pb-24 font-sans selection:bg-[#1d5c42] selection:text-white">

    {{-- ==========================================
         1. HERO SECTION (MINI DARK MODE)
         ========================================== --}}
    <div class="relative w-full h-[40vh] min-h-[350px] flex flex-col justify-center items-center bg-[#0a0a0a] overflow-hidden pt-12">
        <div class="absolute inset-0 w-full h-full overflow-hidden">
            <img src="{{ asset('backgrounds/home.JPG') }}" alt="Background"
                 class="w-full h-full object-cover opacity-30 scale-105 animate-[pulse_25s_ease-in-out_infinite] grayscale-[20%]">
        </div>
        <div class="absolute inset-0 shadow-[inset_0_0_150px_rgba(0,0,0,0.9)]"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#f4f7f6] via-transparent to-[#0a0a0a]/60"></div>
        <div class="absolute top-1/4 left-1/4 w-[30rem] h-[30rem] bg-blue-600/20 rounded-full blur-[120px] animate-float-slow pointer-events-none mix-blend-screen"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[25rem] h-[25rem] bg-indigo-500/15 rounded-full blur-[100px] animate-float-slow pointer-events-none mix-blend-screen" style="animation-delay: 2s;"></div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto pb-10 fade-in-up">
            <div class="inline-flex items-center gap-2 py-1.5 px-5 rounded-full bg-white/5 border border-white/10 text-blue-200 text-[10px] font-black tracking-[0.25em] uppercase backdrop-blur-xl mb-5 shadow-2xl">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-ping absolute"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 relative"></span>
                Logistics & Travel
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4 drop-shadow-2xl">
                My <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-200 to-blue-500 italic font-serif font-medium pr-2">Transports</span>
            </h1>
            <p class="text-sm md:text-base text-gray-300 font-medium max-w-lg mx-auto leading-relaxed drop-shadow-lg opacity-90">
                Track and manage your luxury farm shuttle round-trips seamlessly.
            </p>
        </div>
    </div>

    {{-- ==========================================
         2. MAIN CONTENT
         ========================================== --}}
    <div class="max-w-[96%] xl:max-w-7xl mx-auto relative z-30 -mt-20">

        {{-- Glassmorphic Actions Bar --}}
        <div class="bg-white/90 backdrop-blur-2xl rounded-[2rem] p-4 lg:p-5 shadow-[0_20px_40px_-10px_rgba(0,0,0,0.08)] border border-white flex flex-col sm:flex-row justify-between items-center gap-4 mb-8 fade-in-up" style="animation-delay: 0.2s;">
            <div class="px-3 flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xl shadow-inner border border-blue-100">
                    {{ $transports->count() }}
                </div>
                <div>
                    <p class="text-sm font-black text-gray-900 uppercase tracking-widest">Total Shuttles</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Linked to your bookings</p>
                </div>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('bookings.my_bookings') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gray-50 border border-gray-200 text-gray-600 font-black text-[10px] md:text-xs uppercase tracking-widest rounded-xl hover:bg-white hover:text-[#1d5c42] hover:border-[#1d5c42]/30 hover:shadow-md transition-all flex items-center gap-2 active:scale-95 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Farm Bookings
                </a>
                <a href="{{ route('explore') }}" class="flex-1 sm:flex-none justify-center px-6 py-4 bg-gradient-to-tr from-blue-600 to-indigo-700 text-white font-black text-[10px] md:text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-blue-600/30 hover:shadow-xl hover:shadow-blue-600/40 transition-all flex items-center gap-2 active:scale-95 group border border-white/10">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Book New Trip
                </a>
            </div>
        </div>

        {{-- 💡 TRANSPORT POLICY DISCLAIMER (Premium Glass Design) --}}
        <div class="mb-12 bg-blue-50/60 backdrop-blur-md border border-blue-100 rounded-3xl p-6 md:p-8 shadow-[inset_0_2px_10px_rgba(0,0,0,0.02)] fade-in-up" style="animation-delay: 0.25s;">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 bg-blue-600 text-white p-3 rounded-2xl shadow-lg shadow-blue-600/20 border border-blue-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-blue-900 uppercase tracking-widest mb-1.5">Transport Booking Policy</h3>
                    <div class="text-sm text-blue-800/90 font-medium leading-relaxed max-w-4xl">
                        Transport can only be reserved alongside a farm booking. If you forgot to add a shuttle, you can add it by <span class="font-black border-b border-blue-300 pb-0.5">editing your farm booking</span>, provided your check-in is <span class="font-black border-b border-blue-300 pb-0.5">more than 48 hours away</span>.
                    </div>
                </div>
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
        @if ($transports->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 bg-white rounded-[3rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 fade-in-up" style="animation-delay: 0.3s;">
                <div class="w-24 h-24 bg-blue-50 rounded-[2rem] flex items-center justify-center mb-6 border border-blue-100 shadow-inner">
                    <svg class="w-12 h-12 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <h3 class="text-2xl md:text-3xl font-black text-gray-900 mb-2 tracking-tight">No Shuttles Booked</h3>
                <p class="text-gray-500 mb-8 font-medium text-sm md:text-base text-center max-w-sm">Transport is booked directly alongside your premium farm stays.</p>
                <a href="{{ route('explore') }}" class="px-8 py-4 bg-gradient-to-tr from-[#1d5c42] to-[#14402e] text-white font-black text-xs uppercase tracking-widest rounded-xl hover:shadow-[0_10px_30px_rgba(29,92,66,0.3)] transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                    Book a Farm & Shuttle
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($transports as $index => $transport)
                    @php
                        // Status styling logic for Transports
                        $rawStatus = strtolower($transport->status ?? 'pending');
                        $statusClasses = '';
                        $statusIcon = '';

                        if($rawStatus === 'completed') {
                            $statusClasses = 'text-emerald-700 bg-emerald-50 border-emerald-200';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>';
                        } elseif($rawStatus === 'cancelled' || $rawStatus === 'rejected') {
                            $statusClasses = 'text-rose-700 bg-rose-50 border-rose-200';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>';
                        } elseif(in_array($rawStatus, ['accepted', 'assigned', 'in_progress'])) {
                            $statusClasses = 'text-[#1d5c42] bg-[#1d5c42]/10 border-[#1d5c42]/20';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>';
                        } else {
                            $statusClasses = 'text-amber-700 bg-amber-50 border-amber-200';
                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                        }

                        $displayStatus = ucwords(str_replace('_', ' ', $rawStatus));
                    @endphp

                    <div class="booking-card-wrapper fade-in-up-stagger group bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] hover:shadow-[0_25px_50px_rgba(37,99,235,0.1)] border border-gray-100 hover:border-blue-100 overflow-hidden transition-all duration-500 flex flex-col h-full relative" style="animation-delay: {{ 0.2 + ($index * 0.1) }}s;">

                        {{-- Image Header (Uses Destination Farm Image) --}}
                        <div class="p-3 pb-0">
                            <div class="relative h-56 md:h-64 overflow-hidden rounded-[1.5rem] bg-gray-100 shadow-inner">
                                <img src="{{ optional($transport->farm)->main_image ? asset('storage/' . $transport->farm->main_image) : asset('backgrounds/home.JPG') }}"
                                     onerror="this.onerror=null;this.src='{{ asset('backgrounds/home.JPG') }}';"
                                     alt="Farm Image"
                                     class="image-zoom w-full h-full object-cover text-transparent relative -z-10">

                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                                {{-- Passengers Badge (Top Right) --}}
                                <div class="absolute top-3 right-3 bg-black/40 backdrop-blur-md px-3 py-1.5 rounded-xl text-[11px] font-bold text-white tracking-widest shadow-lg flex items-center gap-1.5 z-10 border border-white/20">
                                    <svg class="w-3.5 h-3.5 text-[#f4e4c1]" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                                    {{ $transport->passengers }} Pax
                                </div>

                                {{-- Arrival Date overlay --}}
                                <div class="absolute bottom-4 left-5 z-10 text-white w-full pr-5">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-[#f4e4c1] mb-1 drop-shadow-sm">Est. Arrival</p>
                                    <p class="font-black text-xl md:text-2xl tracking-tight leading-none drop-shadow-lg flex items-center gap-2">
                                        {{ optional($transport->Farm_Arrival_Time)->format('M d, Y') ?? 'N/A' }}
                                        @if($transport->Farm_Arrival_Time)
                                            <span class="text-[10px] md:text-xs font-black text-white bg-black/40 border border-white/20 px-2 py-1 rounded-lg backdrop-blur-md tracking-widest uppercase">{{ optional($transport->Farm_Arrival_Time)->format('h:i A') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="px-4 md:px-6 pt-5 pb-6 flex flex-col flex-1 bg-white">

                            <div class="mb-4 flex items-center w-full">
                                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg {{ $statusClasses }} border font-black text-[9px] uppercase tracking-widest shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $statusIcon !!}</svg>
                                    {{ $displayStatus }}
                                </div>
                            </div>

                            <div class="flex justify-between items-start gap-4 mb-5">
                                <h2 class="text-xl font-black text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1 tracking-tight">
                                    {{ $transport->transport_type }}
                                </h2>
                                <p class="text-sm font-black text-[#1d5c42] shrink-0 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">
                                    {{ number_format($transport->price, 2) }} JOD
                                </p>
                            </div>

                            {{-- Clean Premium Ticket Box --}}
                            <div class="mb-6 bg-gray-50/60 rounded-2xl border border-gray-100 overflow-hidden shadow-[inset_0_2px_10px_rgba(0,0,0,0.01)]">

                                {{-- Route --}}
                                <div class="flex flex-col p-3.5 ticket-divider">
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-2">Route</p>
                                    <div class="text-[11px] font-bold text-gray-800 flex items-center gap-2 w-full bg-white p-2 rounded-xl shadow-sm border border-gray-100">
                                        <span class="truncate max-w-[40%] pl-1" title="{{ $transport->start_and_return_point }}">{{ Str::limit($transport->start_and_return_point, 15) }}</span>
                                        <span class="text-blue-400 flex-shrink-0 flex-1 text-center font-normal">→</span>
                                        <span class="truncate max-w-[40%] text-[#1d5c42] pr-1 text-right" title="{{ optional($transport->farm)->name }}">{{ Str::limit(optional($transport->farm)->name ?? 'Destination', 15) }}</span>
                                    </div>
                                </div>

                                {{-- Details Grid --}}
                                <div class="grid grid-cols-2 divide-x divide-gray-100 border-b border-gray-100 border-dashed">
                                    {{-- Fleet --}}
                                    <div class="p-3.5 flex flex-col items-center text-center">
                                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-1">Fleet Partner</p>
                                        @if($transport->company)
                                            <p class="text-[10px] font-black text-gray-900 truncate w-full px-1 capitalize">{{ $transport->company->name }}</p>
                                        @else
                                            <p class="text-[9px] font-bold text-amber-500 uppercase tracking-widest animate-pulse bg-amber-50 px-2 py-0.5 rounded border border-amber-100">Assigning</p>
                                        @endif
                                    </div>
                                    {{-- Driver --}}
                                    <div class="p-3.5 flex flex-col items-center text-center">
                                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-1">Driver</p>
                                        @if($transport->driver)
                                            <p class="text-[10px] font-black text-gray-900 truncate w-full px-1 flex items-center justify-center gap-1">
                                                <span class="text-gray-400">👤</span> {{ explode(' ', trim($transport->driver->name))[0] }}
                                            </p>
                                        @else
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Pending</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Vehicle --}}
                                <div class="p-3.5 flex items-center justify-between bg-white">
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Vehicle</p>
                                    @if($transport->vehicle)
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-black text-gray-800 uppercase tracking-widest">{{ $transport->vehicle->name ?? $transport->vehicle->model ?? 'Van' }}</span>
                                            <span class="bg-gray-100 border border-gray-200 px-2 py-0.5 rounded shadow-sm text-[9px] font-black text-gray-600 uppercase tracking-widest">
                                                {{ $transport->vehicle->license_plate ?? 'N/A' }}
                                            </span>
                                        </div>
                                    @else
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Not Assigned</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Action Buttons (Grid Layout) --}}
                            <div class="mt-auto grid grid-cols-2 gap-2">
                                <a href="{{ route('transports.show', $transport->id) }}"
                                   class="col-span-1 py-3.5 bg-gray-900 text-white font-black text-[9px] md:text-[10px] uppercase tracking-widest rounded-xl hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-600/30 transition-all text-center active:scale-95 flex items-center justify-center gap-1.5">
                                    Details
                                </a>

                                @if($rawStatus === 'pending')
                                    <form action="{{ route('transports.destroy', $transport->id) }}" method="POST"
                                          onsubmit="return confirm('Cancel this transport request?');"
                                          class="col-span-1 h-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full h-full py-3.5 bg-white text-rose-500 font-black text-[9px] md:text-[10px] uppercase tracking-widest rounded-xl hover:bg-rose-50 hover:text-rose-600 transition-colors border border-rose-100 text-center shadow-sm active:scale-95 flex items-center justify-center gap-1.5">
                                            Cancel
                                        </button>
                                    </form>
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

            @if($transports->hasPages())
                <div class="mt-16 flex justify-center pb-8 fade-in-up" style="animation-delay: 0.8s;">
                    <div class="bg-white px-6 py-3 rounded-full shadow-sm border border-gray-100">
                        {{ $transports->links() }}
                    </div>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
