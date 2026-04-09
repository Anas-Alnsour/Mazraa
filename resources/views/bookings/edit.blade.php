@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
    {{-- Flatpickr CSS for Custom Calendar --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

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
        /* Utility class to fix calendar bug */
        .d-none { display: none !important; }

        /* Fullscreen Map Styles for Pickup */
        .map-fullscreen {
            position: fixed !important;
            top: 5% !important;
            left: 5% !important;
            width: 90% !important;
            height: 90% !important;
            z-index: 9999 !important;
            border-radius: 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
            border: 4px solid #1d5c42 !important;
        }
        #mapBackdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(8px);
            z-index: 9998;
        }
        #mapBackdrop.active { display: block; }
    </style>

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

            @php
                $startDate = \Carbon\Carbon::parse($booking->start_time);
                $endDate = \Carbon\Carbon::parse($booking->end_time);
                $isMultiDay = $startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay()) > 1;
                $farm = $booking->farm;
            @endphp

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
                            {{ $farm->location }}
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
                <div class="p-8 md:p-12 w-full md:w-[60%] flex flex-col justify-between">
                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" id="editBookingForm" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="booking_mode" id="booking_mode" value="{{ $isMultiDay ? 'multi' : 'single' }}">
                        <input type="hidden" name="booking_date" id="booking_date" value="{{ $startDate->format('Y-m-d') }}">
                        <input type="hidden" name="start_time" id="start_time" value="{{ $booking->start_time }}">
                        <input type="hidden" name="end_time" id="end_time" value="{{ $booking->end_time }}">

                        {{-- New Hidden Inputs for Transport --}}
                        <input type="hidden" name="requires_transport" id="requires_transport" value="{{ $booking->requires_transport ? '1' : '0' }}">
                        <input type="hidden" name="transport_cost" id="transport_cost" value="{{ $booking->transport_cost ?? 0 }}">
                        <input type="hidden" name="pickup_lat" id="pickup_lat" value="{{ $booking->pickup_lat }}">
                        <input type="hidden" name="pickup_lng" id="pickup_lng" value="{{ $booking->pickup_lng }}">

                        <div class="flex items-baseline gap-2 border-b border-gray-200/60 pb-6 mb-8">
                            <span class="text-4xl font-black text-gray-900 tracking-tighter">{{ number_format($farm->price_per_full_day, 0) }}</span>
                            <span class="text-lg font-bold text-gray-900">JOD</span>
                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">/ Shift Base</span>
                        </div>

                        {{-- TABS --}}
                        <div class="flex p-1.5 bg-gray-100/80 rounded-2xl border border-gray-200/50">
                            <button type="button" id="tab_single" onclick="setMode('single')"
                                class="flex-1 py-3 text-[10px] md:text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 focus:outline-none {{ !$isMultiDay ? 'bg-white shadow-md text-[#1d5c42]' : 'text-gray-500 hover:text-gray-800' }}">
                                Single Day
                            </button>
                            <button type="button" id="tab_multi" onclick="setMode('multi')"
                                class="flex-1 py-3 text-[10px] md:text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 focus:outline-none {{ $isMultiDay ? 'bg-white shadow-md text-blue-600' : 'text-gray-500 hover:text-gray-800' }}">
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

                        {{-- 🌟 Transport Toggle (NEW SECTION) 🌟 --}}
                        @if($farm->latitude && $farm->longitude)
                        <div class="border-t border-gray-200/60 pt-6 mt-4">
                            <label class="flex items-center cursor-pointer group bg-gray-50 p-4 rounded-2xl border border-gray-200 transition-colors hover:bg-green-50 hover:border-green-200">
                                <div class="relative flex items-center">
                                    <input type="checkbox" id="toggleTransport" class="w-5 h-5 rounded-md border-gray-300 text-[#1d5c42] focus:ring-[#1d5c42] transition-all cursor-pointer" {{ $booking->requires_transport ? 'checked' : '' }}>
                                </div>
                                <div class="ml-4 flex-1">
                                    <span class="block text-sm font-black text-gray-900 group-hover:text-[#1d5c42] transition-colors">Add/Modify Shuttle Transport</span>
                                    <span class="block text-[10px] font-bold text-gray-500 mt-0.5">Automated driver dispatch</span>
                                </div>
                            </label>

                            <div id="transportSection" class="{{ $booking->requires_transport ? '' : 'd-none' }} mt-4 space-y-4 animate-fade-in">
                                {{-- Passengers Count --}}
                                <div class="mb-4">
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Number of Passengers</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <input type="number" name="transport_passengers" id="transport_passengers" min="1" max="{{ $farm->capacity }}" value="{{ \App\Models\Transport::where('farm_id', $farm->id)->where('user_id', Auth::id())->where('status', '!=', 'cancelled')->value('passengers') ?? 1 }}"
                                            class="w-full bg-white border border-gray-200 rounded-2xl py-4 pl-12 pr-4 text-sm font-bold text-gray-800 focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] outline-none shadow-sm transition-all">
                                    </div>
                                </div>

                                {{-- Location Search Bar --}}
                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Search Your Location</label>
                                    <div class="flex gap-2">
                                        <input type="text" id="searchAddress" class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-bold text-gray-800 focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] outline-none transition-all shadow-sm" placeholder="Enter neighborhood or city...">
                                        <button type="button" onclick="searchLocation()" class="bg-[#1d5c42] hover:bg-[#154632] text-white px-5 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all active:scale-95 shadow-md">
                                            Search
                                        </button>
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-500 font-bold text-center">Click or drag on the map to set exact pickup point</p>

                                <div id="transportCalc" class="d-none bg-blue-50 border border-blue-100 rounded-xl p-4 flex justify-between items-center animate-fade-in">
                                    <div class="text-blue-800">
                                        <span class="block text-[10px] font-black uppercase tracking-widest opacity-70">Distance</span>
                                        <span class="text-sm font-bold"><span id="distVal"></span> km</span>
                                    </div>
                                    <div class="text-blue-800 text-right">
                                        <span class="block text-[10px] font-black uppercase tracking-widest opacity-70">Transport Fee</span>
                                        <span class="text-lg font-black"><span id="costVal"></span> JOD</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2 h-64 md:h-[300px]" id="originalPickupMapContainer">
                                    <div class="relative rounded-[2rem] overflow-hidden border-2 border-gray-200 shadow-sm">
                                        <div class="absolute top-4 left-4 z-[400] bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-lg text-[10px] font-black text-[#1d5c42] border border-gray-200 shadow-sm uppercase tracking-widest">
                                            Farm Location
                                        </div>
                                        <div id="farm-map" class="w-full h-full z-10"></div>
                                    </div>

                                    {{-- 💡 NEW Expandable Pickup Map Wrapper --}}
                                    <div class="relative rounded-[2rem] overflow-hidden border-2 border-[#1d5c42] shadow-md ring-4 ring-[#1d5c42]/10 transition-all duration-300" id="pickup-map-wrapper">
                                        <div class="absolute top-4 left-4 z-[400] bg-[#1d5c42]/90 backdrop-blur-md px-3 py-1.5 rounded-lg text-[10px] font-black text-white border border-[#1d5c42] shadow-sm uppercase tracking-widest flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                                            Pickup Point
                                        </div>
                                        <div id="pickup-map" class="w-full h-full z-10"></div>
                                        <button type="button" id="expandPickupMapBtn" class="absolute bottom-3 right-3 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg shadow-md text-xs font-black text-gray-800 hover:text-[#1d5c42] border border-gray-200 z-[1000] flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                                            Expand
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- MODE 1: SINGLE DAY --}}
                        <div id="mode_single_container" class="{{ $isMultiDay ? 'd-none' : 'block' }}">
                            <div class="mb-4 relative mt-6">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Select Date</label>
                                <input type="text" id="booking_date_flatpickr" placeholder="Select your check-in date"
                                    class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-5 text-sm font-bold text-gray-800 cursor-pointer focus:ring-4 focus:ring-yellow-500/20 focus:border-yellow-500 outline-none transition-all">
                            </div>

                            <div id="shiftsContainer" class="mt-6 animate-fade-in {{ $isMultiDay ? 'd-none' : 'block' }}">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Available Shifts</label>
                                <div class="grid grid-cols-2 gap-3">
                                    @php $currentHour = $startDate->format('H'); @endphp
                                    <button type="button" id="btnMorning" onclick="selectShift('morning')"
                                        class="shift-btn border-2 p-4 rounded-2xl flex flex-col items-center transition-all duration-300 {{ !$isMultiDay && $currentHour == '10' && $startDate->format('Y-m-d') == $endDate->format('Y-m-d') ? 'bg-green-50 border-[#1d5c42]' : 'border-gray-200 bg-white hover:border-[#1d5c42]' }}">
                                        <span class="text-sm font-black {{ !$isMultiDay && $currentHour == '10' && $startDate->format('Y-m-d') == $endDate->format('Y-m-d') ? 'text-[#1d5c42]' : 'text-gray-800' }}">☀️ Morning</span>
                                        <span class="text-[10px] font-bold text-gray-500 mt-1">10 AM - 8 PM</span>
                                    </button>

                                    <button type="button" id="btnEvening" onclick="selectShift('evening')"
                                        class="shift-btn border-2 p-4 rounded-2xl flex flex-col items-center transition-all duration-300 {{ !$isMultiDay && $currentHour == '22' ? 'bg-indigo-50 border-indigo-500' : 'border-gray-200 bg-white hover:border-indigo-500' }}">
                                        <span class="text-sm font-black {{ !$isMultiDay && $currentHour == '22' ? 'text-indigo-600' : 'text-gray-800' }}">🌙 Evening</span>
                                        <span class="text-[10px] font-bold text-gray-500 mt-1">10 PM - 8 AM</span>
                                    </button>

                                    <button type="button" id="btnFullDay" onclick="selectShift('full_day')"
                                        class="shift-btn col-span-2 border-2 p-4 rounded-2xl flex flex-col items-center transition-all duration-300 {{ !$isMultiDay && $currentHour == '10' && $startDate->format('Y-m-d') != $endDate->format('Y-m-d') ? 'bg-amber-50 border-[#c2a265]' : 'border-gray-200 bg-white hover:border-[#c2a265]' }}">
                                        <span class="text-sm font-black {{ !$isMultiDay && $currentHour == '10' && $startDate->format('Y-m-d') != $endDate->format('Y-m-d') ? 'text-[#c2a265]' : 'text-gray-800' }}">👑 Full Day Experience</span>
                                        <span class="text-[10px] font-bold text-gray-500 mt-1">10:00 AM - 08:00 AM</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- MODE 2: MULTI DAY --}}
                        <div id="mode_multi_container" class="{{ $isMultiDay ? 'block' : 'd-none' }}">
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

                        <div id="dynamicInvoice" class="d-none mt-10 mb-8 p-6 bg-blue-50/50 rounded-3xl border border-blue-100 transition-all">
                            <p class="text-[10px] text-blue-500 font-black uppercase mb-4">Payment Summary (Updated)</p>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-bold text-gray-700">New Total (inc. Tax)</span>
                                <span class="text-lg font-black text-gray-900"><span id="newTotalDisplay">0.00</span> JOD</span>
                            </div>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm font-bold text-gray-500">Originally Paid</span>
                                <span class="text-md font-black text-gray-500">- {{ number_format($booking->total_price, 2) }} JOD</span>
                            </div>
                            <div class="pt-4 border-t border-blue-200/60 flex justify-between items-center">
                                <span class="text-sm font-black text-gray-800" id="differenceLabel">Difference</span>
                                <span id="differenceDisplay" class="text-2xl font-black text-gray-900">0.00</span>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-10 pt-8 border-t border-gray-100 flex flex-col sm:flex-row gap-3">
                            <button type="button" onclick="window.location='{{ route('bookings.show', $booking->id) }}'" class="w-full sm:w-1/3 py-4 bg-white border-2 border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-gray-50 transition-colors">Cancel</button>
                            <button type="submit" id="confirmBookingBtn" disabled class="w-full sm:w-2/3 py-4 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl bg-yellow-500 hover:bg-yellow-600 shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2">
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
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=marker&callback=initAllMaps" async defer></script>
    <script>
        const farmPrice = <?php echo floatval($farm->price_per_night ?? 0); ?>;
        const originalPrice = <?php echo floatval($booking->total_price ?? 0); ?>;
        let transportCost = <?php echo floatval($booking->transport_cost ?? 0); ?>;
        const initialRequiresTransport = <?php echo $booking->requires_transport ? 'true' : 'false'; ?>;

        let selectedShift = true;
        let currentShiftType = '{{ $isMultiDay ? "multi_day" : ($startDate->format("H") == "10" && $startDate->format("Y-m-d") == $endDate->format("Y-m-d") ? "morning" : ($startDate->format("H") == "22" ? "evening" : "full_day")) }}';
        let multiDaysCount = <?php echo $isMultiDay ? $startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay()) : 1; ?>;

        const fullyBookedDates = [];
        let morningBookedDates = [];
        let eveningBookedDates = [];
        let dateMap = {};

        const existingBookings = <?php echo json_encode($bookingsData ?? []); ?>;
        const blockedDates = <?php echo json_encode($blockedData ?? []); ?>;

        existingBookings.forEach(b => {
            if(!dateMap[b.date]) dateMap[b.date] = { morning: false, evening: false };
            if(b.hour === 10) dateMap[b.date].morning = true;
            if(b.hour === 22) dateMap[b.date].evening = true;
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
            disableMobile: true,
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

        let singlePickerInstance;
        let multiStartPickerInstance;
        let multiEndPickerInstance;

        function initCalendars() {
            if(singlePickerInstance) singlePickerInstance.destroy();
            if(multiStartPickerInstance) multiStartPickerInstance.destroy();
            if(multiEndPickerInstance) multiEndPickerInstance.destroy();

            singlePickerInstance = flatpickr("#booking_date_flatpickr", {
                ...fpConfig,
                defaultDate: document.getElementById('booking_mode').value === 'single' ? document.getElementById('booking_date').value : null,
                onChange: function(dates, str) {
                    if(str) {
                        document.getElementById('booking_date').value = str;
                        document.getElementById('shiftsContainer').classList.remove('d-none');
                        resetShiftSelection();
                    }
                }
            });

            multiStartPickerInstance = flatpickr("#multi_start_date", {
                ...fpConfig,
                defaultDate: document.getElementById('booking_mode').value === 'multi' ? document.getElementById('booking_date').value : null,
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

        window.setMode = function(mode) {
            document.getElementById('booking_mode').value = mode;

            if(mode === 'single') {
                document.getElementById('mode_single_container').classList.remove('d-none');
                document.getElementById('mode_multi_container').classList.add('d-none');
                document.getElementById('tab_single').className = "flex-1 py-3 text-[10px] md:text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 focus:outline-none bg-white shadow-md text-[#1d5c42]";
                document.getElementById('tab_multi').className = "flex-1 py-3 text-[10px] md:text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 focus:outline-none text-gray-500 hover:text-gray-800";
                document.getElementById('shiftsContainer').classList.add('d-none');
            } else {
                document.getElementById('mode_multi_container').classList.remove('d-none');
                document.getElementById('mode_single_container').classList.add('d-none');
                document.getElementById('tab_multi').className = "flex-1 py-3 text-[10px] md:text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 focus:outline-none bg-white shadow-md text-blue-600";
                document.getElementById('tab_single').className = "flex-1 py-3 text-[10px] md:text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 focus:outline-none text-gray-500 hover:text-gray-800";
            }

            resetShiftSelection();
            document.getElementById('dynamicInvoice').classList.add('d-none');
            document.getElementById('staticOldInvoice').classList.remove('d-none');

            initCalendars();
        }

        function resetShiftSelection() {
            selectedShift = false; currentShiftType = '';
            document.getElementById('confirmBookingBtn').disabled = true;
            document.querySelectorAll('.shift-btn').forEach(btn => {
                btn.className = "shift-btn border-2 p-4 rounded-2xl flex flex-col items-center transition-all duration-300 border-gray-200 bg-white hover:shadow-md";
                btn.querySelector('span').className = "text-sm font-black text-gray-800";
            });
            document.getElementById('dynamicInvoice').classList.add('d-none');
            document.getElementById('staticOldInvoice').classList.remove('d-none');
        }

        window.selectShift = function(type) {
            const dateVal = document.getElementById('booking_date').value;
            if (!dateVal) return;
            resetShiftSelection(); currentShiftType = type;

            let btn = type === 'morning' ? document.getElementById('btnMorning') : (type === 'evening' ? document.getElementById('btnEvening') : document.getElementById('btnFullDay'));
            let colorClass = type === 'morning' ? 'bg-green-50 border-[#1d5c42]' : (type === 'evening' ? 'bg-indigo-50 border-indigo-500' : 'bg-amber-50 border-[#c2a265]');
            let textClass = type === 'morning' ? 'text-[#1d5c42]' : (type === 'evening' ? 'text-indigo-600' : 'text-[#c2a265]');

            btn.className = `shift-btn border-2 p-4 rounded-2xl flex flex-col items-center transition-all duration-300 ${colorClass}`;
            btn.querySelector('span').className = `text-sm font-black ${textClass}`;

            if (type === 'morning') {
                document.getElementById('start_time').value = dateVal + ' 10:00:00';
                document.getElementById('end_time').value = dateVal + ' 20:00:00';
            } else if (type === 'evening') {
                let tomorrow = new Date(dateVal); tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('start_time').value = dateVal + ' 22:00:00';
                document.getElementById('end_time').value = tomorrow.toISOString().split('T')[0] + ' 08:00:00';
            } else {
                let tomorrow = new Date(dateVal); tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('start_time').value = dateVal + ' 10:00:00';
                document.getElementById('end_time').value = tomorrow.toISOString().split('T')[0] + ' 08:00:00';
            }
            selectedShift = true; document.getElementById('confirmBookingBtn').disabled = false; updateInvoice();
        }

        window.checkMultiDayLogic = function() {
            let sVal = document.getElementById('multi_start_date').value;
            let eVal = document.getElementById('multi_end_date').value;
            if(!sVal || !eVal) return;

            let sDate = new Date(sVal); let eDate = new Date(eVal);
            if(eDate <= sDate) { alert("Check-out date must be after Check-in date."); multiEndPickerInstance.clear(); return; }

            multiDaysCount = Math.ceil(Math.abs(eDate - sDate) / (1000 * 60 * 60 * 24));
            document.getElementById('start_time').value = sDate.toISOString().split('T')[0] + ' 10:00:00';
            document.getElementById('end_time').value = eDate.toISOString().split('T')[0] + ' 08:00:00';
            selectedShift = true; currentShiftType = 'multi_day'; document.getElementById('confirmBookingBtn').disabled = false; updateInvoice();
        }

        function updateInvoice() {
            if(!selectedShift) return;
            let base = currentShiftType === 'multi_day' ? (farmPrice * 2) * multiDaysCount : (currentShiftType === 'full_day' ? farmPrice * 2 : farmPrice);
            let totalBeforeTax = base + transportCost;
            let tax = totalBeforeTax * 0.16;
            let newTotal = totalBeforeTax + tax;
            let diff = newTotal - originalPrice;

            document.getElementById('newTotalDisplay').innerText = newTotal.toFixed(2);
            let diffDisplay = document.getElementById('differenceDisplay'), diffLabel = document.getElementById('differenceLabel'), btnText = document.getElementById('btnText');

            if (diff > 0) {
                diffLabel.innerText = "Amount to Pay (Upgrade)"; diffDisplay.innerText = diff.toFixed(2) + ' JOD'; diffDisplay.className = "text-2xl font-black text-blue-700"; btnText.innerText = "Pay Difference & Save";
            } else if (diff < 0) {
                diffLabel.innerText = "Amount to Refund"; diffDisplay.innerText = Math.abs(diff).toFixed(2) + ' JOD'; diffDisplay.className = "text-2xl font-black text-green-600"; btnText.innerText = "Save & Issue Partial Refund";
            } else {
                diffLabel.innerText = "No Additional Charges"; diffDisplay.innerText = "0.00 JOD"; diffDisplay.className = "text-2xl font-black text-gray-700"; btnText.innerText = "Save Changes";
            }
            document.getElementById('dynamicInvoice').classList.remove('d-none'); document.getElementById('staticOldInvoice').classList.add('d-none');
        }

        /* --- TRANSPORT LOGIC WITH GOOGLE MAPS --- */
        let fMap, pickupMap, pickupMarker;
        const farmLat = <?php echo $farm->latitude ? '"' . $farm->latitude . '"' : 'null'; ?>;
        const farmLng = <?php echo $farm->longitude ? '"' . $farm->longitude . '"' : 'null'; ?>;
        const bookingLat = document.getElementById('pickup_lat').value;
        const bookingLng = document.getElementById('pickup_lng').value;

        window.initAllMaps = function() {
            initCalendars();
            document.getElementById('confirmBookingBtn').disabled = false;
            updateInvoice();

            if(farmLat && farmLat !== 'null' && farmLng && farmLng !== 'null') {
                const farmPos = { lat: parseFloat(farmLat), lng: parseFloat(farmLng) };
                fMap = new google.maps.Map(document.getElementById('farm-map'), {
                    zoom: 13,
                    center: farmPos,
                    disableDefaultUI: true,
                    zoomControl: true,
                    scrollwheel: false
                });

                new google.maps.Marker({
                    position: farmPos,
                    map: fMap,
                    title: "{{ $farm->name }}"
                });

                const toggle = document.getElementById('toggleTransport');
                const section = document.getElementById('transportSection');

                if(initialRequiresTransport && toggle) {
                    section.classList.remove('d-none');
                    initPickupMap();
                    calculateRoute(bookingLat, bookingLng);
                }

                if(toggle && section) {
                    toggle.addEventListener('change', function() {
                        if(this.checked) {
                            section.classList.remove('d-none');
                            document.getElementById('requires_transport').value = "1";
                            if(!pickupMap) {
                                initPickupMap();
                            }
                        } else {
                            section.classList.add('d-none');
                            document.getElementById('requires_transport').value = "0";
                            transportCost = 0;
                            document.getElementById('transportCalc').classList.add('d-none');
                            updateInvoice();
                        }
                    });
                }

                // Fullscreen Map Logic
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
                        } else {
                            originalContainer.appendChild(mapWrapper);
                            mapWrapper.classList.remove('map-fullscreen');
                            backdrop.classList.remove('active');
                            expandBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg> Expand`;
                            document.body.style.overflow = 'auto';
                        }
                    });
                }
            }
        };

        function initPickupMap() {
            const startLat = bookingLat ? parseFloat(bookingLat) : 31.9522;
            const startLng = bookingLng ? parseFloat(bookingLng) : 35.9334;

            pickupMap = new google.maps.Map(document.getElementById('pickup-map'), {
                zoom: 12,
                center: { lat: startLat, lng: startLng }
            });

            pickupMarker = new google.maps.Marker({
                position: { lat: startLat, lng: startLng },
                map: pickupMap,
                draggable: true
            });

            pickupMap.addListener('click', function(e) {
                pickupMarker.setPosition(e.latLng);
                calculateRoute(e.latLng.lat(), e.latLng.lng());
            });

            pickupMarker.addListener('dragend', function() {
                const pos = pickupMarker.getPosition();
                calculateRoute(pos.lat(), pos.lng());
            });

            if(bookingLat && bookingLng) {
                calculateRoute(startLat, startLng);
            }
        }

        window.searchLocation = function() {
            const query = document.getElementById('searchAddress').value;
            if(!query) return;
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}, Jordan`)
                .then(res => res.json())
                .then(data => {
                    if(data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        const pos = { lat, lng: lon };
                        pickupMap.setCenter(pos);
                        pickupMap.setZoom(14);
                        pickupMarker.setPosition(pos);
                        calculateRoute(lat, lon);
                    } else { alert("Location not found. Please try a different search term."); }
                });
        }

        function calculateRoute(lat, lng) {
            document.getElementById('pickup_lat').value = lat;
            document.getElementById('pickup_lng').value = lng;
            fetch(`https://router.project-osrm.org/route/v1/driving/${lng},${lat};${farmLng},${farmLat}?overview=false`)
                .then(res => res.json())
                .then(data => {
                    if(data.routes && data.routes.length > 0) {
                        let distanceKm = (data.routes[0].distance / 1000).toFixed(1);
                        transportCost = parseFloat((distanceKm * 0.5).toFixed(2));
                        document.getElementById('distVal').innerText = distanceKm;
                        document.getElementById('costVal').innerText = transportCost;
                        document.getElementById('transport_cost').value = transportCost;
                        document.getElementById('transportCalc').classList.remove('d-none');
                        updateInvoice();
                    }
                });
        }
    </script>
    @endpush
@endsection
