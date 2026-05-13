@extends('layouts.app')

@section('title', 'Edit Booking #MZ-'.$booking->id)

@section('content')
    {{-- Flatpickr CSS for Custom Calendar --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    {{-- Leaflet CSS for Fallback Map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .ticket-dashed-border {
            background-image: linear-gradient(to bottom, #e5e7eb 50%, transparent 50%);
            background-size: 2px 20px;
            background-repeat: repeat-y;
            width: 2px;
        }
        /* 🌟 UX: Custom Flatpickr Styles */
        .flatpickr-day.booked-morning:not(.flatpickr-disabled, .selected, .startRange, .endRange, .inRange) {
            background: linear-gradient(135deg, #ffedd5 50%, #ffffff 50%) !important;
            border-color: #fdba74 !important; color: #1e293b !important; font-weight: 900;
        }
        .flatpickr-day.booked-evening:not(.flatpickr-disabled, .selected, .startRange, .endRange, .inRange) {
            background: linear-gradient(135deg, #ffffff 50%, #e0e7ff 50%) !important;
            border-color: #a5b4fc !important; color: #1e293b !important; font-weight: 900;
        }
        .flatpickr-day.flatpickr-disabled {
            background: #f1f5f9 !important; color: #94a3b8 !important; text-decoration: line-through;
        }
        .image-gradient-overlay {
            background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.4) 50%, rgba(15, 23, 42, 0) 100%);
        }
        .d-none { display: none !important; }

        /* Fullscreen Map Styles */
        .map-fullscreen {
            position: fixed !important; top: 5% !important; left: 5% !important; width: 90% !important; height: 90% !important;
            z-index: 9999 !important; border-radius: 2rem !important; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
            border: 4px solid #1d5c42 !important;
        }
        #mapBackdrop {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); z-index: 9998;
        }
        #mapBackdrop.active { display: block; }
    </style>

    @php
        $startDate = \Carbon\Carbon::parse($booking->start_time);
        $endDate = \Carbon\Carbon::parse($booking->end_time);
        $diffDays = $startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay());
        $startHour = $startDate->format('H');

        // حساب الشفت الأصلي بناءً على الأوقات المعتمدة في النظام
        $isMultiDay = false;
        $initialShift = 'full_day';

        if ($diffDays > 1) {
            $isMultiDay = true;
            $initialShift = 'multi_day';
        } elseif ($startHour == '08' || $startHour == '10' && $endDate->format('H') == '17') {
            $initialShift = 'morning';
        } elseif ($startHour == '19' || $startHour == '22') {
            $initialShift = 'evening';
        }

        $farm = $booking->farm;
    @endphp

    <div class="bg-[#f8fafc] min-h-screen pb-24 font-sans pt-36 selection:bg-[#1d5c42] selection:text-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4 fade-in-up">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-yellow-100/50 text-yellow-700 text-[10px] font-black uppercase tracking-widest mb-3 border border-yellow-200 shadow-sm">
                        Modification Mode
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">
                        Edit Booking #MZ-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                    </h1>
                </div>

                <a href="{{ route('bookings.show', $booking->id) }}"
                   class="inline-flex items-center gap-2 px-6 py-4 bg-white border border-gray-200 text-gray-700 font-black text-[10px] md:text-xs uppercase tracking-widest rounded-2xl hover:bg-gray-50 hover:text-red-500 hover:border-red-200 transition-all shadow-[0_4px_15px_rgba(0,0,0,0.03)] active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Discard Changes
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-8 bg-red-50 border border-red-200 p-5 rounded-2xl shadow-sm fade-in-up">
                    <ul class="list-disc pl-5 space-y-1 text-xs font-bold text-red-600">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden border border-gray-100 flex flex-col md:flex-row fade-in-up" style="animation-delay: 0.2s;">

                {{-- LEFT: Farm Image --}}
                <div class="relative w-full md:w-[40%] h-80 md:h-auto min-h-[450px]">
                    <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : asset('backgrounds/home.JPG') }}"
                         onerror="this.onerror=null;this.src='{{ asset('backgrounds/home.JPG') }}';"
                         class="w-full h-full object-cover text-transparent">
                    <div class="image-gradient-overlay absolute inset-0"></div>
                    <div class="absolute top-6 left-6 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-2xl text-xs font-black text-gray-900 shadow-lg flex items-center gap-1.5 z-10 border border-white/50">
                        <svg class="w-3.5 h-3.5 text-[#c2a265]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        {{ number_format($farm->average_rating ?? 0, 1) }}
                    </div>
                    <div class="absolute bottom-6 left-6 right-6 z-20 text-white">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/20 backdrop-blur-md border border-white/20 text-[10px] font-black uppercase tracking-widest mb-3">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            {{ $farm->location ?? $farm->governorate }}
                        </div>
                        <h2 class="text-3xl md:text-5xl font-black tracking-tight leading-none drop-shadow-xl">{{ $farm->name }}</h2>
                    </div>
                </div>

                <div class="hidden md:flex flex-col justify-between items-center py-10 bg-gray-50/30">
                    <div class="w-8 h-8 rounded-full bg-[#f8fafc] -mt-14 shadow-inner"></div>
                    <div class="ticket-dashed-border h-full mx-4 opacity-50"></div>
                    <div class="w-8 h-8 rounded-full bg-[#f8fafc] -mb-14 shadow-inner"></div>
                </div>

                {{-- RIGHT: Edit Form --}}
                <div class="p-8 md:p-12 w-full md:w-[60%] flex flex-col justify-between" x-data="{ bookingMode: '{{ $isMultiDay ? 'multi' : 'single' }}' }">
                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" id="editBookingForm" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="booking_mode" id="booking_mode" x-model="bookingMode">
                        <input type="hidden" name="booking_date" id="booking_date" value="{{ $startDate->format('Y-m-d') }}">
                        <input type="hidden" name="shift" id="shift_input" value="{{ $initialShift }}">
                        <input type="hidden" name="start_time" id="start_time" value="{{ $booking->start_time }}">
                        <input type="hidden" name="end_time" id="end_time" value="{{ $booking->end_time }}">
                        <input type="hidden" name="distance" id="distance_input" value="0">

                        <input type="hidden" name="requires_transport" id="requires_transport" value="{{ $booking->requires_transport ? '1' : '0' }}">
                        <input type="hidden" name="transport_cost" id="transport_cost" value="{{ $booking->transport_cost ?? 0 }}">
                        <input type="hidden" name="pickup_lat" id="pickup_lat" value="{{ $booking->pickup_lat }}">
                        <input type="hidden" name="pickup_lng" id="pickup_lng" value="{{ $booking->pickup_lng }}">

                        {{-- Main Price Display (Dynamic) --}}
                        <div class="flex items-baseline gap-2 border-b border-gray-200/60 pb-6 mb-8">
                            <span id="displayPrice" class="text-4xl font-black text-gray-900 tracking-tighter">—</span>
                            <span class="text-lg font-bold text-gray-900">JOD</span>
                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">/ Base Rental</span>
                        </div>

                        {{-- TABS (Alpine JS) --}}
                        <div class="flex p-1.5 bg-gray-100/80 rounded-2xl border border-gray-200/50">
                            <button type="button" @click="bookingMode = 'single'; window.resetBookingForm();"
                                :class="bookingMode === 'single' ? 'bg-white shadow-md text-[#1d5c42]' : 'text-gray-500 hover:text-gray-800'"
                                class="flex-1 py-3 text-[10px] md:text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 focus:outline-none">
                                Single Day
                            </button>
                            <button type="button" @click="bookingMode = 'multi'; window.resetBookingForm();"
                                :class="bookingMode === 'multi' ? 'bg-white shadow-md text-blue-600' : 'text-gray-500 hover:text-gray-800'"
                                class="flex-1 py-3 text-[10px] md:text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 focus:outline-none">
                                Multiple Days
                            </button>
                        </div>

                        {{-- Event Type --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Event Type</label>
                            <div class="relative">
                                <select name="event_type" id="eventType" class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-5 text-sm font-bold text-gray-800 focus:ring-4 focus:ring-yellow-500/20 focus:border-yellow-500 transition-all outline-none appearance-none shadow-sm" required>
                                    <option value="Birthday" {{ old('event_type', $booking->event_type) == 'Birthday' ? 'selected' : '' }}>🎉 Birthday Celebration</option>
                                    <option value="Wedding" {{ old('event_type', $booking->event_type) == 'Wedding' ? 'selected' : '' }}>💍 Wedding / Engagement</option>
                                    <option value="Other" {{ old('event_type', $booking->event_type) == 'Other' ? 'selected' : '' }}>✨ Family Gathering / Other</option>
                                </select>
                            </div>
                        </div>

                        {{-- 🌟 Transport Integration 🌟 --}}
                        @if($farm->latitude && $farm->longitude)
                        <div class="border-t border-gray-200/60 pt-6 mt-4">
                            <label class="flex items-center cursor-pointer group bg-gray-50 p-4 rounded-2xl border border-gray-200 transition-colors hover:bg-emerald-50 hover:border-emerald-200">
                                <div class="relative flex items-center">
                                    <input type="checkbox" id="toggleTransport" class="w-5 h-5 rounded-md border-gray-300 text-[#1d5c42] focus:ring-[#1d5c42] transition-all cursor-pointer" {{ $booking->requires_transport ? 'checked' : '' }}>
                                </div>
                                <div class="ml-4 flex-1">
                                    <span class="block text-sm font-black text-gray-900 group-hover:text-[#1d5c42] transition-colors">Add/Modify Shuttle Transport</span>
                                    <span class="block text-[10px] font-bold text-gray-500 mt-0.5">Automated driver dispatch</span>
                                </div>
                            </label>

                            <div id="transportSection" class="{{ $booking->requires_transport ? '' : 'd-none' }} mt-4 space-y-4 animate-fade-in">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Number of Passengers</label>
                                    <input type="number" name="passengers" id="transport_passengers" min="1" max="{{ $farm->capacity }}" value="{{ \App\Models\Transport::where('farm_booking_id', $booking->id)->value('passengers') ?? 1 }}"
                                        class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-5 text-sm font-bold text-gray-800 focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] outline-none shadow-sm transition-all">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Search Your Location</label>
                                    <div class="flex gap-2">
                                        <input type="text" id="pickup_search" class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-bold text-gray-800 focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] outline-none transition-all shadow-sm" placeholder="e.g. Amman, 7th Circle">
                                        <button type="button" onclick="searchLocation()" class="bg-[#1d5c42] hover:bg-[#154632] text-white px-5 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all active:scale-95 shadow-md">Search</button>
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-500 font-bold text-center">Click or drag on the map to set exact pickup point</p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2 h-64 md:h-[300px]" id="originalPickupMapContainer">
                                    <div class="relative rounded-[2rem] overflow-hidden border-2 border-gray-200 shadow-sm">
                                        <div class="absolute top-4 left-4 z-[400] bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-lg text-[10px] font-black text-[#1d5c42] border border-gray-200 shadow-sm uppercase tracking-widest">
                                            Farm Location
                                        </div>
                                        <div id="farm-map" class="w-full h-full z-10"></div>
                                    </div>

                                    <div class="relative rounded-[2rem] overflow-hidden border-2 border-[#1d5c42] shadow-md ring-4 ring-[#1d5c42]/10 transition-all duration-300" id="pickup-map-wrapper">
                                        <div class="absolute top-4 left-4 z-[400] bg-[#1d5c42]/90 backdrop-blur-md px-3 py-1.5 rounded-lg text-[10px] font-black text-white border border-[#1d5c42] shadow-sm uppercase tracking-widest flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span> Pickup Point
                                        </div>
                                        <div id="pickup-map" class="w-full h-full z-10 bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-sm">Map Loading...</div>
                                        <button type="button" id="expandPickupMapBtn" class="absolute bottom-3 right-3 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg shadow-md text-xs font-black text-gray-800 hover:text-[#1d5c42] border border-gray-200 z-[1000] flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                                            Expand
                                        </button>
                                    </div>
                                </div>

                                <div id="transportCalc" class="d-none bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex justify-between items-center animate-fade-in">
                                    <div class="text-emerald-900">
                                        <span class="block text-[10px] font-black uppercase tracking-widest opacity-70">Distance</span>
                                        <span class="text-sm font-bold"><span id="distVal">0</span> km</span>
                                    </div>
                                    <div class="text-emerald-900 text-right">
                                        <span class="block text-[10px] font-black uppercase tracking-widest opacity-70">Transport Fee</span>
                                        <span class="text-lg font-black"><span id="costVal">{{ $booking->transport_cost ?? 0 }}</span> JOD</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- MODE 1: SINGLE DAY --}}
                        <div x-show="bookingMode === 'single'" class="animate-fade-in">
                            <div class="mb-4 relative mt-6">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Select Date</label>
                                <input type="text" id="booking_date_flatpickr" placeholder="Select your check-in date"
                                    class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-5 text-sm font-bold text-gray-800 cursor-pointer focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-600 outline-none transition-all">
                            </div>

                            <div id="shiftsContainer" class="mt-6 animate-fade-in">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Available Shifts</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <button type="button" id="btnMorning" onclick="selectShift('morning')"
                                        class="shift-btn group border-2 p-4 rounded-2xl flex flex-col items-center transition-all duration-300 border-gray-200 bg-white hover:border-[#1d5c42]">
                                        <span class="text-sm font-black text-gray-800 transition-colors">☀️ Morning</span>
                                        <span class="text-[10px] font-bold text-gray-500 mt-1">8 AM - 5 PM</span>
                                    </button>
                                    <button type="button" id="btnEvening" onclick="selectShift('evening')"
                                        class="shift-btn group border-2 p-4 rounded-2xl flex flex-col items-center transition-all duration-300 border-gray-200 bg-white hover:border-indigo-500">
                                        <span class="text-sm font-black text-gray-800 transition-colors">🌙 Evening</span>
                                        <span class="text-[10px] font-bold text-gray-500 mt-1">7 PM - 6 AM</span>
                                    </button>
                                    <button type="button" id="btnFullDay" onclick="selectShift('full_day')"
                                        class="shift-btn group col-span-2 border-2 p-4 rounded-2xl flex flex-col items-center transition-all duration-300 border-gray-200 bg-white hover:border-[#c2a265]">
                                        <span class="text-sm font-black text-gray-800 transition-colors">👑 Full Day Experience</span>
                                        <span class="text-[10px] font-bold text-gray-500 mt-1">24 Hours Immersion</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- MODE 2: MULTI DAY --}}
                        <div x-show="bookingMode === 'multi'" x-cloak class="animate-fade-in">
                            <div class="grid grid-cols-2 gap-4 mt-6">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Check-in</label>
                                    <input type="text" id="multi_start_date" placeholder="Select Date" class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-4 text-sm font-bold cursor-pointer focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Check-out</label>
                                    <input type="text" id="multi_end_date" placeholder="Select Date" class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-4 text-sm font-bold cursor-pointer focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- Invoices --}}
                        <div id="staticOldInvoice" class="mt-10 mb-8 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                            <p class="text-[10px] text-gray-500 font-black uppercase mb-1">Original Payment</p>
                            <p class="text-4xl font-black text-gray-900">{{ number_format($booking->total_price, 2) }} <span class="text-sm">JOD</span></p>
                        </div>

                        <div id="dynamicInvoice" class="d-none mt-10 mb-8 p-6 bg-slate-900 text-white rounded-3xl border border-slate-800 transition-all shadow-xl">
                            <p class="text-[10px] text-slate-400 font-black uppercase mb-4 tracking-[0.2em] border-b border-white/10 pb-3">ESTIMATED INVOICE (UPDATED)</p>

                            <div class="space-y-4 text-[11px] font-bold">
                                <div class="flex justify-between items-center text-slate-300">
                                    <span id="farmRentalLabel">Farm Rental</span>
                                    <span id="farmRentalDisplay" class="text-white">0.00 JOD</span>
                                </div>
                                <div id="invoiceTransport" class="d-none justify-between items-center text-slate-300">
                                    <span>Transport Shuttle</span>
                                    <span id="invoiceTransportCost" class="text-white">0.00 JOD</span>
                                </div>
                                <div class="flex justify-between items-center text-slate-300">
                                    <span>Platform Service (10%)</span>
                                    <span id="invoiceTax" class="text-white">0.00 JOD</span>
                                </div>

                                <div class="pt-4 border-t border-white/10 flex justify-between items-center">
                                    <span class="text-sm font-bold text-slate-300">New Total</span>
                                    <span class="text-lg font-black text-white"><span id="newTotalDisplay">0.00</span> JOD</span>
                                </div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-sm font-bold text-slate-500">Originally Paid</span>
                                    <span class="text-md font-black text-slate-500">- {{ number_format($booking->total_price, 2) }} JOD</span>
                                </div>
                                <div class="pt-4 border-t border-white/10 flex justify-between items-center">
                                    <span class="text-sm font-black text-slate-400 uppercase tracking-widest" id="differenceLabel">Difference</span>
                                    <span id="differenceDisplay" class="text-2xl font-black text-white">0.00</span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-10 pt-8 border-t border-gray-100 flex flex-col sm:flex-row gap-3">
                            <button type="button" onclick="window.location='{{ route('bookings.show', $booking->id) }}'" class="w-full sm:w-1/3 py-4 bg-white border-2 border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-gray-50 transition-colors">Cancel</button>
                            <button type="submit" id="confirmBookingBtn" disabled class="w-full sm:w-2/3 py-4 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl bg-yellow-500 hover:bg-yellow-600 shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                <span id="btnText">Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Map Backdrop for Fullscreen --}}
    <div id="mapBackdrop"></div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=marker&callback=initAllMaps" async defer></script>

    <script>
        // --- CONSTANTS FROM DB ---
        const priceMorning = <?php echo floatval($farm->price_per_morning_shift ?? 0); ?>;
        const priceEvening = <?php echo floatval($farm->price_per_evening_shift ?? 0); ?>;
        const priceFullDay = <?php echo floatval($farm->price_per_full_day ?? 0); ?>;
        const originalPrice = <?php echo floatval($booking->total_price ?? 0); ?>;

        let transportCost = <?php echo floatval($booking->transport_cost ?? 0); ?>;
        const initialRequiresTransport = <?php echo $booking->requires_transport ? 'true' : 'false'; ?>;

        let selectedShift = true;
        let currentShiftType = '{{ $initialShift }}';
        let multiDaysCount = <?php echo $isMultiDay ? $diffDays : 1; ?>;

        const fullyBookedDates = [];
        let morningBookedDates = [];
        let eveningBookedDates = [];
        let dateMap = {};

        const existingBookings = <?php echo json_encode($bookingsData ?? []); ?>;
        const blockedDates = <?php echo json_encode($blockedData ?? []); ?>;

        existingBookings.forEach(b => {
            if(!dateMap[b.date]) dateMap[b.date] = { morning: false, evening: false };
            if(b.shift === 'morning' || b.shift === 'full_day') dateMap[b.date].morning = true;
            if(b.shift === 'evening' || b.shift === 'full_day') dateMap[b.date].evening = true;
        });
        blockedDates.forEach(b => {
            if(!dateMap[b.date]) dateMap[b.date] = { morning: false, evening: false };
            if(b.shift === 'morning' || b.shift === 'full_day') dateMap[b.date].morning = true;
            if(b.shift === 'evening' || b.shift === 'full_day') dateMap[b.date].evening = true;
        });

        for (const [date, status] of Object.entries(dateMap)) {
            if(status.morning && status.evening) { fullyBookedDates.push(date); }
            else if (status.morning) { morningBookedDates.push(date); }
            else if (status.evening) { eveningBookedDates.push(date); }
        }

        const fpConfig = {
            minDate: "today",
            disableMobile: false,
            disable: fullyBookedDates,
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const localDate = new Date(dayElem.dateObj.getTime() - (dayElem.dateObj.getTimezoneOffset() * 60000)).toISOString().split('T')[0];
                if (morningBookedDates.includes(localDate)) {
                    dayElem.classList.add('booked-morning');
                    dayElem.title = "Morning Booked (Evening available)";
                } else if (eveningBookedDates.includes(localDate)) {
                    dayElem.classList.add('booked-evening');
                    dayElem.title = "Evening Booked (Morning available)";
                }
            }
        };

        let singlePickerInstance, multiStartPickerInstance, multiEndPickerInstance;

        function initCalendars() {
            if(singlePickerInstance) singlePickerInstance.destroy();
            if(multiStartPickerInstance) multiStartPickerInstance.destroy();
            if(multiEndPickerInstance) multiEndPickerInstance.destroy();

            singlePickerInstance = flatpickr("#booking_date_flatpickr", {
                ...fpConfig,
                defaultDate: document.getElementById('booking_mode').value === 'single' ? '{{ $startDate->format('Y-m-d') }}' : null,
                onChange: function(dates, str) {
                    if(str) {
                        document.getElementById('booking_date').value = str;
                        document.getElementById('shiftsContainer').classList.remove('d-none');
                        checkAvailability(str);
                        resetShiftSelection();
                    }
                }
            });

            multiStartPickerInstance = flatpickr("#multi_start_date", {
                ...fpConfig,
                defaultDate: document.getElementById('booking_mode').value === 'multi' ? '{{ $startDate->format('Y-m-d') }}' : null,
                onChange: function(dates, str) {
                    if(str) {
                        let n = new Date(dates[0]); n.setDate(n.getDate() + 1);
                        multiEndPickerInstance.set('minDate', n);
                        checkMultiDayLogic();
                    }
                }
            });

            multiEndPickerInstance = flatpickr("#multi_end_date", {
                ...fpConfig,
                defaultDate: document.getElementById('booking_mode').value === 'multi' ? '{{ $endDate->format('Y-m-d') }}' : null,
                onChange: function(dates, str) {
                    if(str) checkMultiDayLogic();
                }
            });
        }

        window.resetBookingForm = function() {
            singlePickerInstance.clear();
            multiStartPickerInstance.clear();
            multiEndPickerInstance.clear();
            document.getElementById('shiftsContainer').classList.add('d-none');
            resetShiftSelection();
        };

        const btnMorning = document.getElementById('btnMorning');
        const btnEvening = document.getElementById('btnEvening');
        const btnFullDay = document.getElementById('btnFullDay');

        function checkAvailability(date) {
            btnMorning.disabled = false; btnEvening.disabled = false; btnFullDay.disabled = false;
            btnMorning.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
            btnEvening.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
            btnFullDay.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-100');

            if(dateMap[date]) {
                if (dateMap[date].morning) {
                    btnMorning.disabled = true; btnMorning.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
                    btnFullDay.disabled = true; btnFullDay.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
                }
                if (dateMap[date].evening) {
                    btnEvening.disabled = true; btnEvening.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
                    btnFullDay.disabled = true; btnFullDay.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
                }
            }
        }

        function resetShiftSelection() {
            selectedShift = false; currentShiftType = '';
            document.getElementById('shift_input').value = '';
            document.getElementById('confirmBookingBtn').disabled = true;
            document.getElementById('displayPrice').textContent = '—';

            document.querySelectorAll('.shift-btn').forEach(btn => {
                btn.classList.remove('bg-green-50', 'border-[#1d5c42]', 'bg-indigo-50', 'border-indigo-500', 'bg-amber-50', 'border-[#c2a265]');
                btn.classList.add('border-gray-200', 'bg-white');
                const titleSpan = btn.querySelector('span:first-child');
                if(titleSpan) {
                    titleSpan.classList.remove('text-[#1d5c42]', 'text-indigo-600', 'text-[#c2a265]');
                    titleSpan.classList.add('text-gray-800');
                }
            });

            document.getElementById('dynamicInvoice').classList.add('d-none');
            document.getElementById('staticOldInvoice').classList.remove('d-none');
        }

        window.selectShift = function(type) {
            const dateVal = document.getElementById('booking_date').value;
            if (!dateVal) return;

            resetShiftSelection();
            currentShiftType = type;
            document.getElementById('shift_input').value = type;

            const priceMap = { morning: priceMorning, evening: priceEvening, full_day: priceFullDay };
            document.getElementById('displayPrice').textContent = priceMap[type].toLocaleString();

            if (type === 'morning') {
                btnMorning.classList.remove('border-gray-200', 'bg-white');
                btnMorning.classList.add('bg-green-50', 'border-[#1d5c42]');
                btnMorning.querySelector('span:first-child').classList.replace('text-gray-800', 'text-[#1d5c42]');
                document.getElementById('start_time').value = dateVal + ' 08:00:00';
                document.getElementById('end_time').value = dateVal + ' 17:00:00';
            } else if (type === 'evening') {
                btnEvening.classList.remove('border-gray-200', 'bg-white');
                btnEvening.classList.add('bg-indigo-50', 'border-indigo-500');
                btnEvening.querySelector('span:first-child').classList.replace('text-gray-800', 'text-indigo-600');
                let tomorrow = new Date(dateVal); tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('start_time').value = dateVal + ' 19:00:00';
                document.getElementById('end_time').value = tomorrow.toISOString().split('T')[0] + ' 06:00:00';
            } else if (type === 'full_day') {
                btnFullDay.classList.remove('border-gray-200', 'bg-white');
                btnFullDay.classList.add('bg-amber-50', 'border-[#c2a265]');
                btnFullDay.querySelector('span:first-child').classList.replace('text-gray-800', 'text-[#c2a265]');
                let tomorrow = new Date(dateVal); tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('start_time').value = dateVal + ' 10:00:00';
                document.getElementById('end_time').value = tomorrow.toISOString().split('T')[0] + ' 08:00:00';
            }

            selectedShift = true;
            document.getElementById('confirmBookingBtn').disabled = false;
            updateInvoice();
        }

        window.checkMultiDayLogic = function() {
            let sVal = document.getElementById('multi_start_date').value;
            let eVal = document.getElementById('multi_end_date').value;
            if(!sVal || !eVal) return;

            let sDate = new Date(sVal); let eDate = new Date(eVal);
            if(eDate <= sDate) { alert("Check-out date must be after Check-in date."); multiEndPickerInstance.clear(); return; }

            let isInvalid = false;
            let curr = new Date(sDate);
            while(curr <= eDate) {
                let chkStr = new Date(curr.getTime() - (curr.getTimezoneOffset() * 60000)).toISOString().split('T')[0];
                if(fullyBookedDates.includes(chkStr) || morningBookedDates.includes(chkStr) || eveningBookedDates.includes(chkStr)) {
                    isInvalid = true; break;
                }
                curr.setDate(curr.getDate() + 1);
            }

            if(isInvalid) {
                alert("Your selected range contains days that are partially or fully booked. Please select a clear range.");
                multiStartPickerInstance.clear(); multiEndPickerInstance.clear();
                resetShiftSelection();
                return;
            }

            multiDaysCount = Math.ceil(Math.abs(eDate - sDate) / (1000 * 60 * 60 * 24));
            document.getElementById('booking_date').value = sDate.toISOString().split('T')[0];
            document.getElementById('shift_input').value = 'full_day';
            document.getElementById('start_time').value = sDate.toISOString().split('T')[0] + ' 10:00:00';
            document.getElementById('end_time').value = eDate.toISOString().split('T')[0] + ' 08:00:00';

            document.getElementById('displayPrice').textContent = (priceFullDay * multiDaysCount).toLocaleString();

            selectedShift = true;
            currentShiftType = 'multi_day';
            document.getElementById('confirmBookingBtn').disabled = false;
            updateInvoice();
        }

        function updateInvoice() {
            if(!selectedShift) return;

            let base = 0; let label = '';
            if (currentShiftType === 'multi_day') {
                base = priceFullDay * multiDaysCount;
                label = `Farm Rental (${multiDaysCount} Days)`;
            } else if (currentShiftType === 'full_day') {
                base = priceFullDay;
                label = 'Farm Rental (Full Day)';
            } else if (currentShiftType === 'morning') {
                base = priceMorning;
                label = 'Farm Rental (Morning)';
            } else {
                base = priceEvening;
                label = 'Farm Rental (Evening)';
            }

            let totalBeforeTax = base + transportCost;
            let tax = totalBeforeTax * 0.10; // ✅ الضريبة الصحيحة 10%
            let newTotal = totalBeforeTax + tax;
            let diff = newTotal - originalPrice;

            document.getElementById('farmRentalLabel').innerText = label;
            document.getElementById('farmRentalDisplay').innerText = base.toFixed(2) + ' JOD';
            document.getElementById('invoiceTax').innerText = tax.toFixed(2) + ' JOD';
            document.getElementById('newTotalDisplay').innerText = newTotal.toFixed(2);

            const invTransport = document.getElementById('invoiceTransport');
            if(transportCost > 0) {
                invTransport.classList.remove('d-none');
                invTransport.classList.add('flex');
                document.getElementById('invoiceTransportCost').innerText = transportCost.toFixed(2) + ' JOD';
            } else {
                invTransport.classList.add('d-none');
                invTransport.classList.remove('flex');
            }

            let diffDisplay = document.getElementById('differenceDisplay'), diffLabel = document.getElementById('differenceLabel'), btnText = document.getElementById('btnText');

            if (diff > 0) {
                diffLabel.innerText = "Amount to Pay (Upgrade)";
                diffDisplay.innerText = diff.toFixed(2) + ' JOD';
                diffDisplay.className = "text-2xl font-black text-emerald-400";
                btnText.innerText = "Pay Difference & Save";
            } else if (diff < 0) {
                diffLabel.innerText = "Amount to Refund";
                diffDisplay.innerText = Math.abs(diff).toFixed(2) + ' JOD';
                diffDisplay.className = "text-2xl font-black text-blue-400";
                btnText.innerText = "Save & Issue Partial Refund";
            } else {
                diffLabel.innerText = "No Additional Charges";
                diffDisplay.innerText = "0.00 JOD";
                diffDisplay.className = "text-2xl font-black text-slate-300";
                btnText.innerText = "Save Changes";
            }
            document.getElementById('dynamicInvoice').classList.remove('d-none');
            document.getElementById('staticOldInvoice').classList.add('d-none');
        }

        /* --- 🌍 DUAL MAP SYSTEM --- */
        let pickupMapObj, pickupMarkerObj, fMap;
        let farmLat = <?php echo $farm->latitude ? '"' . $farm->latitude . '"' : 'null'; ?>;
        let farmLng = <?php echo $farm->longitude ? '"' . $farm->longitude . '"' : 'null'; ?>;
        const farmNameStr = '<?php echo addslashes($farm->name); ?>';

        let bookingLat = document.getElementById('pickup_lat').value;
        let bookingLng = document.getElementById('pickup_lng').value;

        if(!farmLat || farmLat === 'null') farmLat = 31.9522;
        if(!farmLng || farmLng === 'null') farmLng = 35.9334;

        window.initAllMaps = function() {
            initCalendars();

            // تهيئة الخيارات المحددة مسبقاً بناء على الداتابيز
            if (currentShiftType !== 'multi_day') {
                checkAvailability('{{ $startDate->format('Y-m-d') }}');
                selectShift('{{ $initialShift }}');
            } else {
                checkMultiDayLogic();
            }

            const farmMapEl = document.getElementById('farm-map');
            const pickupMapEl = document.getElementById('pickup-map');
            const useGoogleMaps = (typeof google === 'object' && typeof google.maps === 'object');

            if (farmMapEl) {
                if (useGoogleMaps) {
                    const farmPos = { lat: parseFloat(farmLat), lng: parseFloat(farmLng) };
                    fMap = new google.maps.Map(farmMapEl, { zoom: 13, center: farmPos, disableDefaultUI: true, zoomControl: true });
                    new google.maps.Marker({ position: farmPos, map: fMap, title: farmNameStr });
                } else {
                    const lMap = L.map('farm-map').setView([farmLat, farmLng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(lMap);
                    L.marker([farmLat, farmLng]).addTo(lMap).bindPopup(farmNameStr).openPopup();
                }
            }

            const toggle = document.getElementById('toggleTransport');
            const section = document.getElementById('transportSection');

            if(initialRequiresTransport && toggle) {
                section.classList.remove('d-none');
                initPickupMap(useGoogleMaps);
                if(bookingLat && bookingLng) calculateRoute(bookingLat, bookingLng);
            }

            if(toggle && section) {
                toggle.addEventListener('change', function() {
                    if(this.checked) {
                        section.classList.remove('d-none');
                        document.getElementById('requires_transport').value = "1";
                        if(!pickupMapObj) initPickupMap(useGoogleMaps);
                    } else {
                        section.classList.add('d-none');
                        document.getElementById('requires_transport').value = "0";
                        transportCost = 0;
                        document.getElementById('transport_cost').value = 0;
                        document.getElementById('transportCalc').classList.add('d-none');
                        updateInvoice();
                    }
                });
            }
        };

        function initPickupMap(useGoogleMaps) {
            const startLat = bookingLat ? parseFloat(bookingLat) : 31.9522;
            const startLng = bookingLng ? parseFloat(bookingLng) : 35.9334;

            if (useGoogleMaps) {
                pickupMapObj = new google.maps.Map(document.getElementById('pickup-map'), { zoom: 12, center: { lat: startLat, lng: startLng } });
                pickupMapObj.addListener('click', function(e) { setPickupMarkerDual(e.latLng.lat(), e.latLng.lng(), true); });
            } else {
                pickupMapObj = L.map('pickup-map').setView([startLat, startLng], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(pickupMapObj);
                pickupMapObj.on('click', function(e) { setPickupMarkerDual(e.latlng.lat, e.latlng.lng, false); });
            }
            if(bookingLat && bookingLng) setPickupMarkerDual(startLat, startLng, useGoogleMaps);
        }

        function setPickupMarkerDual(lat, lng, isGoogle) {
            if (isGoogle) {
                if(pickupMarkerObj) pickupMarkerObj.setMap(null);
                pickupMarkerObj = new google.maps.Marker({ position: { lat, lng }, map: pickupMapObj, draggable: true });
                pickupMarkerObj.addListener('dragend', function() {
                    const pos = pickupMarkerObj.getPosition();
                    calculateRoute(pos.lat(), pos.lng());
                });
            } else {
                if(pickupMarkerObj) pickupMapObj.removeLayer(pickupMarkerObj);
                pickupMarkerObj = L.marker([lat, lng], {draggable: true}).addTo(pickupMapObj);
                pickupMarkerObj.on('dragend', function(e) {
                    const pos = e.target.getLatLng();
                    calculateRoute(pos.lat, pos.lng);
                });
            }
            calculateRoute(lat, lng);
        }

        window.searchLocation = function() {
            const query = document.getElementById('pickup_search').value;
            if(!query) return;
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=JO`)
            .then(res => res.json())
            .then(data => {
                if(data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    const isGoogle = (typeof google === 'object' && typeof google.maps === 'object');

                    if (isGoogle) {
                        pickupMapObj.setCenter({ lat, lng: lon }); pickupMapObj.setZoom(14);
                    } else {
                        pickupMapObj.setView([lat, lon], 14);
                    }
                    setPickupMarkerDual(lat, lon, isGoogle);
                } else { alert("Location not found. Try a more specific address."); }
            });
        };

        function calculateRoute(lat, lng) {
            document.getElementById('pickup_lat').value = lat;
            document.getElementById('pickup_lng').value = lng;
            fetch(`https://router.project-osrm.org/route/v1/driving/${lng},${lat};${farmLng},${farmLat}?overview=false`)
                .then(res => res.json())
                .then(data => {
                    if(data.routes && data.routes.length > 0) {
                        let distanceKm = (data.routes[0].distance / 1000).toFixed(1);
                        transportCost = parseFloat((10 + (distanceKm * 0.5)).toFixed(2)); // ✅ المعادلة الصحيحة + 10 دنانير الأساسية
                        document.getElementById('distance_input').value = distanceKm;
                        document.getElementById('distVal').innerText = distanceKm;
                        document.getElementById('costVal').innerText = transportCost;
                        document.getElementById('transport_cost').value = transportCost;
                        document.getElementById('transportCalc').classList.remove('d-none');
                        updateInvoice();
                    }
                });
        }

        // Fullscreen map logic
        const expandBtn = document.getElementById('expandPickupMapBtn');
        const mapWrapper = document.getElementById('pickup-map-wrapper');
        const originalContainer = document.getElementById('originalPickupMapContainer');
        const backdrop = document.getElementById('mapBackdrop');
        let isExpanded = false;

        if(expandBtn) {
            expandBtn.addEventListener('click', function(e) {
                e.preventDefault();
                isExpanded = !isExpanded;
                if(isExpanded) {
                    document.body.appendChild(mapWrapper);
                    mapWrapper.classList.add('map-fullscreen');
                    backdrop.classList.add('active');
                    expandBtn.innerHTML = "Collapse Map";
                    document.body.style.overflow = 'hidden';
                    if (pickupMapObj && typeof pickupMapObj.invalidateSize === 'function') setTimeout(() => pickupMapObj.invalidateSize(), 300);
                } else {
                    originalContainer.appendChild(mapWrapper);
                    mapWrapper.classList.remove('map-fullscreen');
                    backdrop.classList.remove('active');
                    expandBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg> Expand`;
                    document.body.style.overflow = 'auto';
                    if (pickupMapObj && typeof pickupMapObj.invalidateSize === 'function') setTimeout(() => pickupMapObj.invalidateSize(), 300);
                }
            });
        }
    </script>
    @endpush
@endsection
