@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    {{-- Flatpickr CSS for Custom Calendar --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

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

        /* 🌟 UX: Custom Flatpickr Styles */
        .flatpickr-day.booked-morning:not(.flatpickr-disabled, .selected, .startRange, .endRange, .inRange) {
            background: linear-gradient(135deg, #ffedd5 50%, #ffffff 50%) !important;
            border-color: #fdba74 !important;
            color: #1e293b !important;
            font-weight: 900;
        }
        .flatpickr-day.booked-evening:not(.flatpickr-disabled, .selected, .startRange, .endRange, .inRange) {
            background: linear-gradient(135deg, #ffffff 50%, #e0e7ff 50%) !important;
            border-color: #a5b4fc !important;
            color: #1e293b !important;
            font-weight: 900;
        }
        .flatpickr-day.flatpickr-disabled {
            background: #f1f5f9 !important;
            color: #94a3b8 !important;
            text-decoration: line-through;
        }

        .image-gradient-overlay {
            background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.4) 50%, rgba(15, 23, 42, 0) 100%);
        }
    </style>

    <div class="bg-[#f8fafc] min-h-screen pb-24 font-sans pt-36 selection:bg-[#1d5c42] selection:text-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header & Back Button --}}
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

            {{-- Flash Messages --}}
            @if ($errors->any())
                <div class="mb-8 bg-red-50 border border-red-200 p-5 rounded-2xl shadow-sm fade-in-up" style="animation-delay: 0.1s;">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="bg-red-500 p-1.5 rounded-full text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                        <p class="text-red-800 font-bold text-sm">Please fix the following errors:</p>
                    </div>
                    <ul class="list-disc pl-10 space-y-1 text-xs font-bold text-red-600">
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

            {{-- Main Ticket Container --}}
            <div class="bg-white rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden border border-gray-100 flex flex-col md:flex-row fade-in-up" style="animation-delay: 0.2s;">

                {{-- LEFT: Farm Image & Basic Info --}}
                <div class="relative w-full md:w-[40%] h-80 md:h-auto min-h-[450px]">
                    <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : asset('backgrounds/home.JPG') }}"
                         onerror="this.onerror=null;this.src='{{ asset('backgrounds/home.JPG') }}';"
                         alt="Farm Image"
                         class="w-full h-full object-cover text-transparent">

                    <div class="image-gradient-overlay absolute inset-0"></div>

                    {{-- Rating Badge --}}
                    <div class="absolute top-6 left-6 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-2xl text-xs font-black text-gray-900 shadow-lg flex items-center gap-1.5 z-10 border border-white/50">
                        <svg class="w-3.5 h-3.5 text-[#c2a265]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        {{ number_format($farm->average_rating ?? 0, 1) }}
                    </div>

                    {{-- Farm Info Overlay --}}
                    <div class="absolute bottom-6 left-6 right-6 z-20 text-white">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/20 backdrop-blur-md border border-white/20 text-[10px] font-black uppercase tracking-widest mb-3">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            {{ $farm->location }}
                        </div>
                        <h2 class="text-3xl md:text-5xl font-black tracking-tight leading-none drop-shadow-xl">
                            {{ $farm->name }}
                        </h2>
                    </div>
                </div>

                {{-- Visual Divider for Desktop --}}
                <div class="hidden md:flex flex-col justify-between items-center py-10 bg-gray-50/30">
                    <div class="w-8 h-8 rounded-full bg-[#f8fafc] -mt-14 shadow-inner"></div>
                    <div class="ticket-dashed-border h-full mx-4 opacity-50"></div>
                    <div class="w-8 h-8 rounded-full bg-[#f8fafc] -mb-14 shadow-inner"></div>
                </div>

                {{-- RIGHT: Edit Form --}}
                <div class="p-8 md:p-12 w-full md:w-[60%] flex flex-col justify-between">

                    {{-- Alpine Component Initialization with Old Values --}}
                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" id="editBookingForm" class="space-y-8"
                          x-data="{ bookingMode: '{{ $isMultiDay ? 'multi' : 'single' }}' }">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="booking_mode" x-model="bookingMode">
                        <input type="hidden" name="booking_date" id="booking_date" value="{{ $startDate->format('Y-m-d') }}">
                        <input type="hidden" name="start_time" id="start_time" value="{{ $booking->start_time }}">
                        <input type="hidden" name="end_time" id="end_time" value="{{ $booking->end_time }}">

                        <div class="flex items-baseline gap-2 border-b border-gray-200/60 pb-6 mb-8">
                            <span class="text-4xl font-black text-gray-900 tracking-tighter">{{ number_format($farm->price_per_night, 0) }}</span>
                            <span class="text-lg font-bold text-gray-900">JOD</span>
                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">/ Shift</span>
                        </div>

                        {{-- Segmented Control --}}
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
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-500">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- 🌟 MODE 1: SINGLE DAY UI --}}
                        <div x-show="bookingMode === 'single'" x-transition.opacity.duration.300ms>
                            <div class="mb-4 relative">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Select Date</label>
                                <input type="text" id="booking_date_flatpickr" placeholder="Select your check-in date"
                                    class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-5 text-sm font-bold text-gray-800 focus:ring-4 focus:ring-yellow-500/20 focus:border-yellow-500 transition-all outline-none shadow-sm cursor-pointer bg-[url('data:image/svg+xml;base64,PHN2ZyBmaWxsPSJub25lIiBzdHJva2U9IiM5Y2EzYWYiIHZpZXdCb3g9IjAgMCAyNCAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIHN0cm9rZS13aWR0aD0iMiIgZD0iTTggN1YzbTggNHYzTTEgMTFoMThNNSAyMWgxNGExIDEgMCAwMDItMlY3YTEgMSAwIDAwLTItMkg1YTEgMSAwIDAwLTIgMnYxMmExIDEgMCAwMDIgMnoiPjwvcGF0aD48L3N2Zz4=')] bg-no-repeat bg-[right_1.25rem_center] bg-[length:1.25rem_1.25rem]">
                            </div>

                            <div class="flex flex-wrap items-center justify-center gap-3 mb-6 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <div class="flex items-center gap-1.5 text-[9px] font-black text-gray-600 uppercase tracking-widest">
                                    <div class="w-4 h-4 rounded-md border border-orange-300" style="background: linear-gradient(135deg, #ffedd5 50%, #ffffff 50%);"></div> Morning Booked
                                </div>
                                <div class="flex items-center gap-1.5 text-[9px] font-black text-gray-600 uppercase tracking-widest">
                                    <div class="w-4 h-4 rounded-md border border-indigo-300" style="background: linear-gradient(135deg, #ffffff 50%, #e0e7ff 50%);"></div> Evening Booked
                                </div>
                            </div>

                            <div id="shiftsContainer" class="{{ $isMultiDay ? 'hidden' : '' }} animate-fade-in">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Available Shifts</label>
                                <div class="grid grid-cols-2 gap-3">
                                    @php $currentHour = $startDate->format('H'); @endphp
                                    <button type="button" id="btnMorning" onclick="selectShift('morning')"
                                        class="shift-btn border-2 p-4 rounded-2xl flex flex-col items-center justify-center transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed group {{ !$isMultiDay && $currentHour == '10' && $startDate->format('Y-m-d') == $endDate->format('Y-m-d') ? 'bg-green-50 border-[#1d5c42]' : 'border-gray-200 bg-white hover:border-[#1d5c42] hover:shadow-md' }}">
                                        <span class="text-sm font-black transition-colors {{ !$isMultiDay && $currentHour == '10' && $startDate->format('Y-m-d') == $endDate->format('Y-m-d') ? 'text-[#1d5c42]' : 'text-gray-800' }}">☀️ Morning</span>
                                        <span class="text-[10px] font-bold text-gray-500 mt-1">10 AM - 8 PM</span>
                                    </button>

                                    <button type="button" id="btnEvening" onclick="selectShift('evening')"
                                        class="shift-btn border-2 p-4 rounded-2xl flex flex-col items-center justify-center transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed group {{ !$isMultiDay && $currentHour == '22' ? 'bg-indigo-50 border-indigo-500' : 'border-gray-200 bg-white hover:border-indigo-500 hover:shadow-md' }}">
                                        <span class="text-sm font-black transition-colors {{ !$isMultiDay && $currentHour == '22' ? 'text-indigo-600' : 'text-gray-800' }}">🌙 Evening</span>
                                        <span class="text-[10px] font-bold text-gray-500 mt-1">10 PM - 8 AM</span>
                                    </button>

                                    <button type="button" id="btnFullDay" onclick="selectShift('full_day')"
                                        class="shift-btn col-span-2 border-2 p-4 rounded-2xl flex flex-col items-center justify-center transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed group {{ !$isMultiDay && $currentHour == '10' && $startDate->format('Y-m-d') != $endDate->format('Y-m-d') ? 'bg-amber-50 border-[#c2a265]' : 'border-gray-200 bg-white hover:border-[#c2a265] hover:shadow-md' }}">
                                        <span class="text-sm font-black transition-colors {{ !$isMultiDay && $currentHour == '10' && $startDate->format('Y-m-d') != $endDate->format('Y-m-d') ? 'text-[#c2a265]' : 'text-gray-800' }}">👑 Full Day Experience</span>
                                        <span class="text-[10px] font-bold text-gray-500 mt-1">10:00 AM - 08:00 AM (Next Day)</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- 🌟 MODE 2: MULTIPLE DAYS UI --}}
                        <div x-show="bookingMode === 'multi'" x-transition.opacity.duration.300ms x-cloak>
                            <div class="bg-blue-50/50 border border-blue-100 p-5 rounded-2xl mb-5">
                                <p class="text-xs text-blue-800 font-black flex items-center gap-2 mb-2 uppercase tracking-widest">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Multi-Day Stay
                                </p>
                                <p class="text-[11px] font-medium text-blue-700 leading-relaxed">
                                    Selecting multiple days reserves the <strong class="font-black">Full Day</strong>. Please select your <strong class="font-black">Check-in</strong> and <strong class="font-black">Check-out</strong> dates below.
                                </p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Check-in</label>
                                    <input type="text" id="multi_start_date" placeholder="Select Date"
                                        class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-4 text-sm font-bold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm cursor-pointer bg-[url('data:image/svg+xml;base64,PHN2ZyBmaWxsPSJub25lIiBzdHJva2U9IiM5Y2EzYWYiIHZpZXdCb3g9IjAgMCAyNCAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIHN0cm9rZS13aWR0aD0iMiIgZD0iTTggN1YzbTggNHYzTTEgMTFoMThNNSAyMWgxNGExIDEgMCAwMDItMlY3YTEgMSAwIDAwLTItMkg1YTEgMSAwIDAwLTIgMnYxMmExIDEgMCAwMDIgMnoiPjwvcGF0aD48L3N2Zz4=')] bg-no-repeat bg-[right_1.25rem_center] bg-[length:1.25rem_1.25rem]">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Check-out</label>
                                    <input type="text" id="multi_end_date" placeholder="Select Date"
                                        class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-4 text-sm font-bold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm cursor-pointer bg-[url('data:image/svg+xml;base64,PHN2ZyBmaWxsPSJub25lIiBzdHJva2U9IiM5Y2EzYWYiIHZpZXdCb3g9IjAgMCAyNCAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIHN0cm9rZS13aWR0aD0iMiIgZD0iTTggN1YzbTggNHYzTTEgMTFoMThNNSAyMWgxNGExIDEgMCAwMDItMlY3YTEgMSAwIDAwLTItMkg1YTEgMSAwIDAwLTIgMnYxMmExIDEgMCAwMDIgMnoiPjwvcGF0aD48L3N2Zz4=')] bg-no-repeat bg-[right_1.25rem_center] bg-[length:1.25rem_1.25rem]">
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-10 pt-8 border-t border-gray-100 flex flex-col sm:flex-row gap-3">
                            <button type="button" onclick="window.location='{{ route('bookings.show', $booking->id) }}'"
                                    class="w-full sm:w-1/3 py-4 bg-white border-2 border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-gray-50 transition-colors text-center active:scale-95 shadow-sm">
                                Cancel
                            </button>
                            <button type="submit" id="confirmBookingBtn"
                                class="w-full sm:w-2/3 py-4 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl bg-yellow-500 hover:bg-yellow-600 hover:shadow-[0_8px_20px_rgba(234,179,8,0.3)] transition-all text-center shadow-md active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>

    @push('scripts')
    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <?php
        // Fetch all bookings EXCEPT the current one being edited
        $bookingsData = [];
        $otherBookings = $farm->bookings()->where('id', '!=', $booking->id)->get();
        if(isset($otherBookings) && $otherBookings) {
            foreach($otherBookings as $b) {
                if($b->start_time) {
                    $bookingsData[] = [
                        'date' => \Carbon\Carbon::parse($b->start_time)->toDateString(),
                        'hour' => (int) \Carbon\Carbon::parse($b->start_time)->format('H')
                    ];
                }
            }
        }
        $blockedData = [];
        if(isset($farm->blockedDates) && $farm->blockedDates) {
            foreach($farm->blockedDates as $bd) {
                if($bd->date) {
                    $blockedData[] = [
                        'date' => \Carbon\Carbon::parse($bd->date)->toDateString(),
                        'shift' => $bd->shift
                    ];
                }
            }
        }
    ?>

    <script>
        // --- CONSTANTS ---
        const existingBookings = <?php echo json_encode($bookingsData); ?>;
        const blockedDates = <?php echo json_encode($blockedData); ?>;

        let selectedShift = <?php echo $isMultiDay ? 'true' : 'true'; ?>;
        let currentShiftType = '';

        @if($isMultiDay)
            currentShiftType = 'multi_day';
        @else
            @if($startDate->format('H') == '10' && $startDate->format('Y-m-d') == $endDate->format('Y-m-d'))
                currentShiftType = 'morning';
            @elseif($startDate->format('H') == '22')
                currentShiftType = 'evening';
            @else
                currentShiftType = 'full_day';
            @endif
        @endif

        // --- CALCULATE BOOKED DATES FOR CALENDAR ---
        let fullyBookedDates = [];
        let morningBookedDates = [];
        let eveningBookedDates = [];

        let dateMap = {};
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
            if(status.morning && status.evening) {
                fullyBookedDates.push(date);
            } else if (status.morning) {
                morningBookedDates.push(date);
            } else if (status.evening) {
                eveningBookedDates.push(date);
            }
        }

        // --- FLATPICKR INITIALIZATION ---
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

        // Init Single Day Calendar
        const singlePicker = flatpickr("#booking_date_flatpickr", {
            ...fpConfig,
            defaultDate: <?php echo !$isMultiDay ? '"' . $startDate->format('Y-m-d') . '"' : 'null'; ?>,
            onChange: function(selectedDates, dateStr, instance) {
                if(dateStr) {
                    document.getElementById('booking_date').value = dateStr;

                    const shiftsContainer = document.getElementById('shiftsContainer');
                    shiftsContainer.classList.remove('hidden');
                    shiftsContainer.style.display = '';

                    checkAvailability(dateStr);

                    selectedShift = false;
                    currentShiftType = '';
                    document.getElementById('confirmBookingBtn').disabled = true;

                    document.querySelectorAll('.shift-btn').forEach(btn => {
                        btn.classList.remove('bg-green-50', 'border-[#1d5c42]', 'bg-indigo-50', 'border-indigo-500', 'bg-amber-50', 'border-[#c2a265]');
                        btn.classList.add('border-gray-200', 'bg-white');

                        const titleSpan = btn.querySelector('span:first-child');
                        if(titleSpan) {
                            titleSpan.classList.remove('text-[#1d5c42]', 'text-indigo-600', 'text-[#c2a265]');
                            titleSpan.classList.add('text-gray-800');
                        }
                    });
                }
            }
        });

        // Init Multi Day Calendars
        let multiStartPicker = flatpickr("#multi_start_date", {
            ...fpConfig,
            defaultDate: <?php echo $isMultiDay ? '"' . $startDate->format('Y-m-d') . '"' : 'null'; ?>,
            onChange: function(selectedDates, dateStr) {
                if(dateStr) {
                    let nextDay = new Date(selectedDates[0]);
                    nextDay.setDate(nextDay.getDate() + 1);
                    multiEndPicker.set('minDate', nextDay);
                    checkMultiDayLogic();
                }
            }
        });

        let multiEndPicker = flatpickr("#multi_end_date", {
            ...fpConfig,
            defaultDate: <?php echo $isMultiDay ? '"' . $endDate->format('Y-m-d') . '"' : 'null'; ?>,
            onChange: function(selectedDates, dateStr) {
                if(dateStr) {
                    checkMultiDayLogic();
                }
            }
        });

        // --- FORM & SHIFT LOGIC ---
        window.resetBookingForm = function() {
            singlePicker.clear();
            multiStartPicker.clear();
            multiEndPicker.clear();
            resetShiftSelection();

            const shiftsContainer = document.getElementById('shiftsContainer');
            shiftsContainer.style.display = '';
            shiftsContainer.classList.add('hidden');
        };

        function resetShiftSelection() {
            selectedShift = false;
            currentShiftType = '';
            document.getElementById('confirmBookingBtn').disabled = true;
            document.getElementById('start_time').value = '';
            document.getElementById('end_time').value = '';

            document.querySelectorAll('.shift-btn').forEach(btn => {
                btn.classList.remove('bg-green-50', 'border-[#1d5c42]', 'bg-indigo-50', 'border-indigo-500', 'bg-amber-50', 'border-[#c2a265]');
                btn.classList.add('border-gray-200', 'bg-white');

                const titleSpan = btn.querySelector('span:first-child');
                if(titleSpan) {
                    titleSpan.classList.remove('text-[#1d5c42]', 'text-indigo-600', 'text-[#c2a265]');
                    titleSpan.classList.add('text-gray-800');
                }
            });
        }

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

        window.selectShift = function(type) {
            const dateVal = document.getElementById('booking_date').value;
            if (!dateVal) return;

            resetShiftSelection();
            currentShiftType = type;

            if (type === 'morning') {
                btnMorning.classList.remove('border-gray-200', 'bg-white');
                btnMorning.classList.add('bg-green-50', 'border-[#1d5c42]');
                const span = btnMorning.querySelector('span:first-child');
                span.classList.remove('text-gray-800'); span.classList.add('text-[#1d5c42]');

                document.getElementById('start_time').value = dateVal + ' 10:00:00';
                document.getElementById('end_time').value = dateVal + ' 20:00:00';
            } else if (type === 'evening') {
                btnEvening.classList.remove('border-gray-200', 'bg-white');
                btnEvening.classList.add('bg-indigo-50', 'border-indigo-500');
                const span = btnEvening.querySelector('span:first-child');
                span.classList.remove('text-gray-800'); span.classList.add('text-indigo-600');

                let tomorrow = new Date(dateVal);
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('start_time').value = dateVal + ' 22:00:00';
                document.getElementById('end_time').value = tomorrow.toISOString().split('T')[0] + ' 08:00:00';
            } else if (type === 'full_day') {
                btnFullDay.classList.remove('border-gray-200', 'bg-white');
                btnFullDay.classList.add('bg-amber-50', 'border-[#c2a265]');
                const span = btnFullDay.querySelector('span:first-child');
                span.classList.remove('text-gray-800'); span.classList.add('text-[#c2a265]');

                let tomorrow = new Date(dateVal);
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('start_time').value = dateVal + ' 10:00:00';
                document.getElementById('end_time').value = tomorrow.toISOString().split('T')[0] + ' 08:00:00';
            }

            selectedShift = true;
            document.getElementById('confirmBookingBtn').disabled = false;
        }

        function checkMultiDayLogic() {
            let sVal = document.getElementById('multi_start_date').value;
            let eVal = document.getElementById('multi_end_date').value;

            if(!sVal || !eVal) return;

            let sDate = new Date(sVal);
            let eDate = new Date(eVal);

            if(eDate <= sDate) {
                alert("Check-out date must be after Check-in date.");
                multiEndPicker.clear();
                return;
            }

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
                multiStartPicker.clear();
                multiEndPicker.clear();
                resetShiftSelection();
                return;
            }

            document.getElementById('start_time').value = sDate.toISOString().split('T')[0] + ' 10:00:00';
            document.getElementById('end_time').value = eDate.toISOString().split('T')[0] + ' 08:00:00';

            selectedShift = true;
            currentShiftType = 'multi_day';
            document.getElementById('confirmBookingBtn').disabled = false;
        }

        // On Load, if it's single day, ensure the calendar has the date selected internally to show available shifts
        document.addEventListener('DOMContentLoaded', function() {
            @if(!$isMultiDay)
                checkAvailability('{{ $startDate->format("Y-m-d") }}');
            @else
                document.getElementById('confirmBookingBtn').disabled = false;
            @endif
        });
    </script>
    @endpush
@endsection
