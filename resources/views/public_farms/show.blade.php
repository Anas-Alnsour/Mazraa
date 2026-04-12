@extends('layouts.app')

@section('title', $farm->name)

@section('content')
    {{-- Flatpickr CSS for Custom Calendar --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    {{-- ✅ تم إضافة Leaflet CSS للخريطة البديلة --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* Smooth Fade In Stagger */
        .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
        .fade-in-up-stagger { animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Premium Booking Card */
        .booking-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08), inset 0 1px 0 rgba(255, 255, 255, 1);
        }

        @keyframes shimmer { 100% { transform: translateX(100%); } }

        /* 🌟 UX: Custom Flatpickr Styles */
        /* Morning Booked -> Show Orange Top-Left, White Bottom-Right */
        .flatpickr-day.booked-morning:not(.flatpickr-disabled, .selected, .startRange, .endRange, .inRange) {
            background: linear-gradient(135deg, #ffedd5 50%, #ffffff 50%) !important;
            border-color: #fdba74 !important;
            color: #1e293b !important;
            font-weight: 900;
        }
        /* Evening Booked -> Show White Top-Left, Indigo Bottom-Right */
        .flatpickr-day.booked-evening:not(.flatpickr-disabled, .selected, .startRange, .endRange, .inRange) {
            background: linear-gradient(135deg, #ffffff 50%, #e0e7ff 50%) !important;
            border-color: #a5b4fc !important;
            color: #1e293b !important;
            font-weight: 900;
        }
        /* Fully Booked -> Grayed out and crossed */
        .flatpickr-day.flatpickr-disabled {
            background: #f1f5f9 !important;
            color: #94a3b8 !important;
            text-decoration: line-through;
        }

        /* Fullscreen Map Styles */
        .map-fullscreen {
            position: fixed !important;
            top: 5% !important;
            left: 5% !important;
            width: 90% !important;
            height: 90% !important;
            z-index: 9999 !important;
            border-radius: 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
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

    <div class="bg-[#f8fafc] min-h-screen pb-24 font-sans pt-12 selection:bg-[#1d5c42] selection:text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 p-4 rounded-2xl shadow-sm flex items-center gap-3 fade-in-up">
                    <div class="bg-green-500 p-1.5 rounded-full text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                    <p class="text-green-800 font-bold text-sm">{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-2xl shadow-sm flex items-center gap-3 fade-in-up">
                    <div class="bg-red-500 p-1.5 rounded-full text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                    <p class="text-red-800 font-bold text-sm">{{ session('error') }}</p>
                </div>
            @endif

            {{-- 1. HEADER SECTION --}}
            <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 fade-in-up">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[#1d5c42]/10 text-[#1d5c42] text-[10px] font-black uppercase tracking-widest mb-3 border border-[#1d5c42]/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#1d5c42] animate-pulse"></span> Luxury Escape
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight mb-3 leading-tight">{{ $farm->name }}</h1>
                    <div class="flex items-center text-sm font-bold text-gray-500">
                        <svg class="w-5 h-5 mr-1.5 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ $farm->location ?? $farm->governorate }}
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    @auth
                        @php
                            $isFavorited = auth()->user()->favorites()->where('farm_id', $farm->id)->exists();
                        @endphp
                        <form action="{{ $isFavorited ? route('favorites.destroy', $farm->id) : route('favorites.store', $farm->id) }}" method="POST" class="w-full md:w-auto">
                            @csrf
                            @if($isFavorited) @method('DELETE') @endif
                            <button type="submit" title="{{ $isFavorited ? 'Remove from Favorites' : 'Add to Favorites' }}"
                                class="w-full md:w-auto flex items-center justify-center gap-2 px-5 py-3 rounded-2xl {{ $isFavorited ? 'bg-red-50 border-red-200 text-red-500 hover:bg-red-100' : 'bg-white border-gray-200 text-gray-500 hover:text-red-500 hover:border-red-200 hover:bg-red-50' }} transition-all shadow-sm border font-bold text-xs uppercase tracking-widest active:scale-95">
                                <svg class="w-5 h-5" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                <span class="md:hidden">{{ $isFavorited ? 'Saved' : 'Save' }}</span>
                            </button>
                        </form>
                    @endauth

                    <a href="{{ route('explore') }}" class="hidden md:flex items-center text-xs font-black text-gray-500 hover:text-[#1d5c42] transition-colors bg-white px-6 py-3 rounded-2xl border border-gray-200 shadow-sm uppercase tracking-widest group">
                        <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back
                    </a>
                </div>
            </div>

            {{-- 2. PREMIUM MASONRY IMAGE GALLERY --}}
            <div class="mb-12 fade-in-up" style="animation-delay: 0.1s;">
                @if ($farm->images->count() > 0)
                    <div x-data="{
                        isOpen: false,
                        current: 0,
                        images: [
                            @if($farm->main_image) '{{ asset('storage/' . $farm->main_image) }}', @endif
                            @foreach ($farm->images as $image)
                            '{{ asset('storage/' . $image->image_url) }}',
                            @endforeach
                        ],
                        open(index) { this.current = index; this.isOpen = true; document.body.style.overflow = 'hidden'; },
                        close() { this.isOpen = false; document.body.style.overflow = 'auto'; },
                        next() { this.current = (this.current + 1) % this.images.length; },
                        prev() { this.current = (this.current - 1 + this.images.length) % this.images.length; }
                    }">
                        <div class="grid grid-cols-1 md:grid-cols-4 md:grid-rows-2 gap-3 h-[50vh] md:h-[65vh] min-h-[400px] rounded-[2.5rem] overflow-hidden shadow-lg border border-gray-200/50">
                            @if($farm->main_image || isset($farm->images[0]))
                            <div class="md:col-span-2 md:row-span-2 relative group cursor-pointer overflow-hidden h-full w-full">
                                <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : asset('storage/' . $farm->images[0]->image_url) }}"
                                     @click="open(0)" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" alt="Main Image">
                                <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors duration-500"></div>
                            </div>
                            @endif

                            @foreach($farm->images->take(4) as $index => $image)
                                <div class="hidden md:block relative group cursor-pointer overflow-hidden h-full w-full">
                                    <img src="{{ asset('storage/' . $image->image_url) }}"
                                         @click="open({{ $farm->main_image ? $loop->index + 1 : $loop->index }})"
                                         class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Gallery Image">
                                    <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors duration-500"></div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Fullscreen Modal --}}
                        <div x-show="isOpen" x-transition.opacity.duration.300ms style="display: none;"
                            class="fixed inset-0 bg-[#020617]/95 flex items-center justify-center z-[200] backdrop-blur-xl"
                            @keydown.window.escape="close">
                            <div class="absolute top-0 inset-x-0 p-6 flex justify-between items-center bg-gradient-to-b from-black/50 to-transparent">
                                <span class="text-white font-bold tracking-widest text-xs uppercase" x-text="`${current + 1} / ${images.length}`"></span>
                                <button @click="close" class="text-white/70 hover:text-white p-2 rounded-full hover:bg-white/10 transition-all">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <button @click="prev" class="absolute left-4 md:left-10 p-4 rounded-full bg-white/5 text-white hover:bg-white/20 transition-all backdrop-blur-md">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <img :src="images[current]" class="max-h-[85vh] max-w-[90vw] md:max-w-[75vw] rounded-2xl shadow-2xl object-contain transition-transform duration-300">
                            <button @click="next" class="absolute right-4 md:right-10 p-4 rounded-full bg-white/5 text-white hover:bg-white/20 transition-all backdrop-blur-md">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="w-full h-[500px] overflow-hidden rounded-[2.5rem] relative border border-gray-200 bg-slate-50 flex flex-col items-center justify-center p-12">
                        <div class="w-24 h-24 bg-white rounded-full shadow-sm flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-2">Collection Coming Soon</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center max-w-xs leading-relaxed">The owner hasn't uploaded high-resolution imagery for this hidden gem yet.</p>
                    </div>
                @endif
            </div>

            {{-- 3. MAIN CONTENT LAYOUT (SPLIT SCREEN) --}}
            <div class="flex flex-col lg:flex-row gap-12 fade-in-up" style="animation-delay: 0.2s;">

                {{-- Left Column: Farm Details --}}
                <div class="lg:w-[60%] xl:w-[65%] space-y-12">

                    {{-- Quick Highlights --}}
                    <div class="flex flex-wrap gap-4 border-b border-gray-200/80 pb-10">
                        <div class="bg-white px-6 py-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 flex-1 min-w-[200px]">
                            <div class="bg-[#1d5c42]/10 p-3 rounded-xl text-[#1d5c42]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Capacity</p>
                                <p class="font-black text-gray-900 text-lg">Up to {{ $farm->capacity }} Guests</p>
                            </div>
                        </div>

                        <div class="bg-white px-6 py-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 flex-1 min-w-[200px]">
                            <div class="bg-[#c2a265]/10 p-3 rounded-xl text-[#c2a265]">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Guest Rating</p>
                                <p class="font-black text-gray-900 text-lg">{{ number_format($farm->average_rating, 1) }} <span class="text-sm font-bold text-gray-400">/ 5.0 @if($farm->reviews_count > 0)({{ $farm->reviews_count }} Reviews)@endif</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="prose prose-lg max-w-none">
                        <h3 class="text-2xl font-black text-gray-900 mb-6 tracking-tight">About this escape</h3>
                        <p class="text-gray-600 leading-loose font-medium whitespace-pre-line text-justify">
                            {{ $farm->description }}
                        </p>
                    </div>

                    {{-- 📍 Map Integration with Exact Address --}}
                    @if($farm->latitude && $farm->longitude)
                    <div class="pt-6">
                        <h3 class="text-2xl font-black text-gray-900 mb-6 tracking-tight">Location Details</h3>
                        <div class="p-2 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 mb-4">
                            <div id="farm-map-display" class="w-full h-[350px] rounded-[2rem] z-0"></div>
                        </div>
                        <div class="flex items-start gap-3 px-2">
                            <svg class="w-6 h-6 text-[#1d5c42] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div>
                                <p class="font-black text-gray-900">{{ $farm->name }}</p>
                                <p class="text-gray-500 font-medium text-sm">{{ $farm->location ?? $farm->governorate }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ⭐️ Reviews Section --}}
                    @php
                        $canReview = false;
                        if(auth()->check()) {
                            $canReview = \App\Models\FarmBooking::where('user_id', auth()->id())
                                ->where('farm_id', $farm->id)
                                ->whereIn('status', ['completed', 'finished'])
                                ->exists();
                        }
                    @endphp
                    <div class="pt-6">
                        <x-reviews-section
                            :reviews="$farm->reviews"
                            :reviewable-id="$farm->id"
                            reviewable-type="farm"
                            :average-rating="$farm->average_rating"
                            :can-review="$canReview"
                        />
                    </div>
                </div>

                {{-- Right Column: Sticky Booking Card --}}
                <div class="lg:w-[40%] xl:w-[35%] relative">
                    <div class="bg-white border border-slate-200 rounded-[2.5rem] p-8 md:p-10 sticky top-28 z-20 shadow-2xl shadow-slate-200/50">

                        <div class="flex items-baseline justify-between mb-8 pb-6 border-b border-gray-100">
                            <div>
                                <span class="text-3xl font-black text-slate-900 tracking-tighter">{{ number_format($farm->price_per_morning_shift ?? 0, 0) }}</span>
                                <span class="text-sm font-bold text-slate-500 uppercase tracking-widest">JOD / shift</span>
                            </div>
                            <div class="flex items-center gap-1.5 font-bold text-sm text-slate-900">
                                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                {{ number_format($farm->average_rating, 1) }}
                            </div>
                        </div>

                        {{-- Finalized Form Structure --}}
                        <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm" class="space-y-6" x-data="{ bookingMode: 'single' }">
                            @csrf
                            <input type="hidden" name="farm_id" value="{{ $farm->id }}">
                            <input type="hidden" name="booking_date" id="booking_date">
                            <input type="hidden" name="booking_mode" x-model="bookingMode">
                            <input type="hidden" name="shift" id="shift_input">
                            <input type="hidden" name="start_time" id="start_time">
                            <input type="hidden" name="end_time" id="end_time">
                            <input type="hidden" name="requires_transport" id="requires_transport" value="0">
                            <input type="hidden" name="transport_cost" id="transport_cost" value="0">
                            <input type="hidden" name="pickup_lat" id="pickup_lat">
                            <input type="hidden" name="pickup_lng" id="pickup_lng">

                            {{-- iOS Style Mode Switcher --}}
                            <div class="grid grid-cols-2 p-1.5 bg-slate-50 border border-slate-100 rounded-2xl mb-8">
                                <button type="button" @click="bookingMode = 'single'; window.resetBookingForm();"
                                    :class="bookingMode === 'single' ? 'bg-white shadow-md text-emerald-700' : 'text-slate-400 hover:text-slate-600'"
                                    class="py-3 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all outline-none">
                                    Single Day
                                </button>
                                <button type="button" @click="bookingMode = 'multi'; window.resetBookingForm();"
                                    :class="bookingMode === 'multi' ? 'bg-white shadow-md text-emerald-700' : 'text-slate-400 hover:text-slate-600'"
                                    class="py-3 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all outline-none">
                                    Multiple Days
                                </button>
                            </div>

                            {{-- Single Day Selection --}}
                            <div x-show="bookingMode === 'single'" class="space-y-6 animate-fade-in">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2">Check-in Date</label>
                                    <div class="relative">
                                        <input type="text" id="booking_date_flatpickr" placeholder="Select your check-in date..."
                                            class="w-full bg-white border border-slate-200 rounded-2xl py-4 px-5 text-sm font-bold text-slate-800 focus:ring-4 focus:ring-emerald-50 focus:border-emerald-600 transition-all outline-none cursor-pointer">
                                        <svg class="absolute right-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>

                                    {{-- 💡 Calendar Guide Note --}}
                                    <div class="mt-3 px-3 py-2 bg-slate-50 border border-slate-100 rounded-xl">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 border-b border-slate-200 pb-1">Availability Guide:</p>
                                        <div class="flex items-center gap-3 text-[10px] font-bold text-slate-600 flex-wrap">
                                            <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full border-2 border-orange-300 bg-orange-100"></span> Morning Booked</div>
                                            <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full border-2 border-indigo-300 bg-indigo-100"></span> Evening Booked</div>
                                            <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full border-2 border-slate-300 bg-slate-200"></span> Fully Booked</div>
                                        </div>
                                    </div>
                                </div>

                                <div id="shiftsContainer" style="display: none;" class="space-y-4">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Available Shifts</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button type="button" id="btnMorning" onclick="selectShift('morning')"
                                            class="shift-btn group p-4 border-2 border-slate-100 rounded-2xl bg-white hover:border-emerald-600 flex flex-col items-center justify-center transition-all disabled:opacity-50">
                                            <span class="text-xs font-black uppercase tracking-widest transition-colors mb-1">☀️ Morning</span>
                                            <span class="text-[9px] font-bold text-slate-400">8 AM - 5 PM</span>
                                        </button>
                                        <button type="button" id="btnEvening" onclick="selectShift('evening')"
                                            class="shift-btn group p-4 border-2 border-slate-100 rounded-2xl bg-white hover:border-emerald-600 flex flex-col items-center justify-center transition-all disabled:opacity-50">
                                            <span class="text-xs font-black uppercase tracking-widest transition-colors mb-1">🌙 Evening</span>
                                            <span class="text-[9px] font-bold text-slate-400">7 PM - 6 AM</span>
                                        </button>
                                        <button type="button" id="btnFullDay" onclick="selectShift('full_day')"
                                            class="shift-btn group col-span-2 p-4 border-2 border-slate-100 rounded-2xl bg-white hover:border-emerald-600 flex flex-col items-center justify-center transition-all disabled:opacity-50">
                                            <span class="text-xs font-black uppercase tracking-widest transition-colors mb-1">👑 Full Experience</span>
                                            <span class="text-[9px] font-bold text-slate-400">24 Hours Immersion</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Multi Day Selection --}}
                            <div x-show="bookingMode === 'multi'" x-cloak class="space-y-6 animate-fade-in">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2">Check-in</label>
                                        <input type="text" id="multi_start_date" placeholder="Date"
                                            class="w-full bg-white border border-slate-200 rounded-2xl py-4 px-4 text-sm font-bold text-slate-800 focus:ring-4 focus:ring-emerald-50 focus:border-emerald-600 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2">Check-out</label>
                                        <input type="text" id="multi_end_date" placeholder="Date"
                                            class="w-full bg-white border border-slate-200 rounded-2xl py-4 px-4 text-sm font-bold text-slate-800 focus:ring-4 focus:ring-emerald-50 focus:border-emerald-600 outline-none">
                                    </div>
                                </div>
                            </div>

                            {{-- Event Type --}}
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Event Type</label>
                                <div class="relative">
                                    <select name="event_type" id="eventType" class="w-full bg-white border border-slate-200 rounded-2xl py-4 px-5 text-sm font-bold text-slate-800 focus:ring-4 focus:ring-emerald-50 focus:border-emerald-600 transition-all outline-none appearance-none shadow-sm cursor-pointer" required>
                                        <option value="">Select an occasion...</option>
                                        <option value="Birthday">🎉 Birthday Celebration</option>
                                        <option value="Wedding">💍 Wedding / Engagement</option>
                                        <option value="Other">✨ Family Gathering / Other</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-slate-400">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Transport Integration --}}
                            @if($farm->latitude && $farm->longitude)
                            <div class="pt-6 border-t border-slate-100">
                                <label class="flex items-center cursor-pointer group bg-slate-50 p-4 rounded-2xl border border-slate-100 transition-all hover:bg-emerald-50 hover:border-emerald-200">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" id="toggleTransport" class="w-5 h-5 rounded-lg border-slate-300 text-emerald-600 focus:ring-emerald-500 transition-all cursor-pointer">
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <span class="block text-sm font-black text-slate-900 group-hover:text-emerald-700 transition-colors">Add Shuttle Transport</span>
                                        <span class="block text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-tighter">Premium pickup & drop-off</span>
                                    </div>
                                </label>

                                <div id="transportSection" style="display: none;" class="mt-6 space-y-6 animate-fade-in">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2">Number of Passengers</label>
                                        <div class="relative">
                                            <input type="number" name="passengers" id="transport_passengers" min="1" max="{{ $farm->capacity }}" value="1"
                                                class="w-full bg-white border border-slate-200 rounded-2xl py-4 px-5 text-sm font-bold text-slate-800 focus:ring-4 focus:ring-emerald-50 focus:border-emerald-600 outline-none shadow-sm transition-all"
                                                placeholder="e.g. 4">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2">Search Your Location</label>
                                        <div class="flex gap-2">
                                            <input type="text" id="pickup_search" placeholder="e.g. Amman, 7th Circle" class="flex-1 bg-white border border-slate-200 rounded-2xl py-4 px-5 text-sm font-bold text-slate-800 focus:ring-4 focus:ring-emerald-50 focus:border-emerald-600 outline-none">
                                            <button type="button" onclick="searchPickupLocation()" class="bg-slate-900 text-white px-6 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-colors">Search</button>
                                        </div>
                                    </div>

                                    {{-- Map Display Area --}}
                                    <div class="relative p-1.5 bg-white border border-slate-200 rounded-2xl shadow-sm" id="originalMapContainer">
                                        <div id="pickup-map-wrapper" class="w-full h-[200px] rounded-2xl z-0 relative overflow-hidden transition-all duration-300">
                                            <div id="pickup-map" class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-sm">Map Loading...</div>
                                            <button type="button" id="expandMapBtn" class="absolute bottom-3 right-3 bg-white/95 backdrop-blur px-4 py-2 rounded-xl shadow-md text-[10px] font-black text-slate-800 hover:text-emerald-600 border border-slate-100 z-[1000] flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                                                EXPAND
                                            </button>
                                        </div>
                                    </div>

                                    <div id="transportCalc" style="display: none;" class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 flex justify-between items-center animate-fade-in mt-4">
                                        <div class="text-emerald-900">
                                            <span class="block text-[9px] font-black uppercase tracking-widest opacity-60 mb-0.5">Route Distance</span>
                                            <span class="text-sm font-black tracking-tighter"><span id="distVal"></span> km</span>
                                        </div>
                                        <div class="text-emerald-900 text-right">
                                            <span class="block text-[9px] font-black uppercase tracking-widest opacity-60 mb-0.5">Shuttle Service</span>
                                            <span class="text-sm font-black tracking-tighter"><span id="costVal"></span> JOD</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Invoice Layout --}}
                            <div id="bookingSummary" style="display: none;" class="bg-slate-900 text-white p-7 rounded-3xl shadow-xl mt-8 animate-fade-in">
                                <h3 class="font-black text-slate-400 text-[9px] uppercase mb-4 tracking-[0.2em] border-b border-white/10 pb-3">ESTIMATED INVOICE</h3>
                                <div class="space-y-4 text-[11px] font-bold">
                                    <div class="flex justify-between items-center text-slate-300">
                                        <span id="farmRentalLabel">Farm Rental</span>
                                        <span id="farmRentalDisplay" class="text-white">0.00 JOD</span>
                                    </div>
                                    <div id="invoiceTransport" style="display: none;" class="flex justify-between items-center text-slate-300">
                                        <span>Transport Shuttle</span>
                                        <span id="invoiceTransportCost" class="text-white">0.00 JOD</span>
                                    </div>
                                    <div class="flex justify-between items-center text-slate-300">
                                        <span>Platform Service</span>
                                        <span id="invoiceTax" class="text-white">0.00 JOD</span>
                                    </div>
                                    <div class="flex justify-between items-end pt-5 border-t border-white/10 mt-2">
                                        <span class="font-black text-slate-400 uppercase tracking-widest text-[10px]">Total Due</span>
                                        <span id="invoiceTotal" class="font-black text-emerald-400 text-2xl tracking-tighter">0.00 JOD</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="confirmBookingBtn" disabled
                                class="w-full py-5 rounded-2xl bg-emerald-600 text-white font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-emerald-600/30 hover:bg-emerald-700 hover:-translate-y-1 transition-all active:translate-y-0 active:scale-95 flex items-center justify-center gap-2 group disabled:bg-slate-100 disabled:text-slate-400 disabled:shadow-none disabled:transform-none">
                                Reserve My Spot
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            </button>

                            <div class="flex items-center justify-center gap-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mt-4">
                                <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg> Secure Pay</span>
                                <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Instant</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Map Backdrop for Fullscreen --}}
    <div id="mapBackdrop"></div>

    @push('scripts')
    {{-- ✅ تم الاعتماد على مكتبة Leaflet لتكون البديل الآمن لخرائط جوجل --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=marker&callback=initAllMaps"></script>

    <?php
        $bookingsData = [];
        if(isset($farm->bookings) && $farm->bookings) {
            foreach($farm->bookings as $b) {
                if($b->start_time && !in_array($b->status, ['cancelled', 'completed'])) {
                    $bookingsData[] = [
                        'date' => \Carbon\Carbon::parse($b->start_time)->toDateString(),
                        'shift' => $b->event_type
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
        const priceMorning = <?php echo floatval($farm->price_per_morning_shift ?? 0); ?>;
        const priceEvening = <?php echo floatval($farm->price_per_evening_shift ?? 0); ?>;
        const priceFullDay = <?php echo floatval($farm->price_per_full_day ?? 0); ?>;

        let farmLat = <?php echo $farm->latitude ? '"' . $farm->latitude . '"' : 'null'; ?>;
        let farmLng = <?php echo $farm->longitude ? '"' . $farm->longitude . '"' : 'null'; ?>;
        const farmNameStr = '<?php echo addslashes($farm->name); ?>';

        // Fallback to Amman if Farm coordinates are totally missing
        if(!farmLat || farmLat === 'null') farmLat = 31.9522;
        if(!farmLng || farmLng === 'null') farmLng = 35.9334;

        const existingBookings = <?php echo json_encode($bookingsData); ?>;
        const blockedDates = <?php echo json_encode($blockedData); ?>;

        let transportCost = 0;
        let selectedShift = false;
        let currentShiftType = '';
        let multiDaysCount = 1;

        // --- CALCULATE BOOKED DATES FOR CALENDAR ---
        let fullyBookedDates = [];
        let morningBookedDates = [];
        let eveningBookedDates = [];

        let dateMap = {};
        const addStatus = (date, shift) => {
            if(!dateMap[date]) dateMap[date] = { morning: false, evening: false };
            if(shift === 'morning' || shift === 'full_day') dateMap[date].morning = true;
            if(shift === 'evening' || shift === 'full_day') dateMap[date].evening = true;
        };
        existingBookings.forEach(b => addStatus(b.date, b.shift));
        blockedDates.forEach(b => addStatus(b.date, b.shift));

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

        const singlePicker = flatpickr("#booking_date_flatpickr", {
            ...fpConfig,
            onChange: function(selectedDates, dateStr, instance) {
                if(dateStr) {
                    document.getElementById('booking_date').value = dateStr;
                    const shiftsContainer = document.getElementById('shiftsContainer');
                    shiftsContainer.classList.remove('hidden');
                    shiftsContainer.style.display = 'block';
                    checkAvailability(dateStr);
                    resetShiftSelection();
                }
            }
        });

        let multiStartPicker = flatpickr("#multi_start_date", {
            ...fpConfig,
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
            onChange: function(selectedDates, dateStr) {
                if(dateStr) {
                    checkMultiDayLogic();
                }
            }
        });

        window.setBookingMode = function(mode) {
            document.getElementById('booking_mode').value = mode;
            if(mode === 'single') {
                document.getElementById('singleDayUI').style.display = 'block';
                document.getElementById('multiDayUI').style.display = 'none';
            } else {
                document.getElementById('singleDayUI').style.display = 'none';
                document.getElementById('multiDayUI').style.display = 'block';
            }
            resetBookingForm();
        }

        window.resetBookingForm = function() {
            singlePicker.clear();
            multiStartPicker.clear();
            multiEndPicker.clear();
            document.getElementById('shiftsContainer').style.display = 'none';
            resetShiftSelection();
        };

        function resetShiftSelection() {
            selectedShift = false;
            currentShiftType = '';
            document.getElementById('shift_input').value = '';
            document.getElementById('confirmBookingBtn').disabled = true;
            document.getElementById('bookingSummary').style.display = 'none';
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
            document.getElementById('shift_input').value = type;

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
                let tomorrow = new Date(dateVal);
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('start_time').value = dateVal + ' 19:00:00';
                document.getElementById('end_time').value = tomorrow.toISOString().split('T')[0] + ' 06:00:00';
            } else if (type === 'full_day') {
                btnFullDay.classList.remove('border-gray-200', 'bg-white');
                btnFullDay.classList.add('bg-amber-50', 'border-[#c2a265]');
                btnFullDay.querySelector('span:first-child').classList.replace('text-gray-800', 'text-[#c2a265]');
                let tomorrow = new Date(dateVal);
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('start_time').value = dateVal + ' 10:00:00';
                document.getElementById('end_time').value = tomorrow.toISOString().split('T')[0] + ' 08:00:00';
            }

            selectedShift = true;
            document.getElementById('confirmBookingBtn').disabled = false;
            updateInvoice();
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

            let diffTime = Math.abs(eDate - sDate);
            multiDaysCount = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            document.getElementById('booking_date').value = sDate.toISOString().split('T')[0];
            document.getElementById('shift_input').value = 'full_day';
            document.getElementById('start_time').value = sDate.toISOString().split('T')[0] + ' 10:00:00';
            document.getElementById('end_time').value = eDate.toISOString().split('T')[0] + ' 08:00:00';

            selectedShift = true;
            currentShiftType = 'multi_day';
            document.getElementById('confirmBookingBtn').disabled = false;
            updateInvoice();
        }

        function updateInvoice() {
            if(!selectedShift) return;

            let base = 0;
            let label = '';

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
            let tax = totalBeforeTax * 0.16;
            let grandTotal = totalBeforeTax + tax;

            document.getElementById('farmRentalLabel').innerText = label;
            document.getElementById('farmRentalDisplay').innerText = base.toFixed(2) + ' JOD';
            document.getElementById('invoiceTax').innerText = tax.toFixed(2) + ' JOD';
            document.getElementById('invoiceTotal').innerText = grandTotal.toFixed(2) + ' JOD';
            document.getElementById('bookingSummary').style.display = 'block';
        }

        /* --- 🌍 DUAL MAP SYSTEM (GOOGLE OR LEAFLET) --- */
        let pickupMapObj, pickupMarkerObj;

        window.initAllMaps = function() {
            const farmMapEl = document.getElementById('farm-map-display');
            const pickupMapEl = document.getElementById('pickup-map');

            // Check if Google Maps is available
            const useGoogleMaps = (typeof google === 'object' && typeof google.maps === 'object');

            if (farmMapEl) {
                if (useGoogleMaps) {
                    const farmPos = { lat: parseFloat(farmLat), lng: parseFloat(farmLng) };
                    const fMap = new google.maps.Map(farmMapEl, {
                        zoom: 13, center: farmPos, disableDefaultUI: true, zoomControl: true
                    });
                    new google.maps.Marker({ position: farmPos, map: fMap, title: farmNameStr });
                } else {
                    const lMap = L.map('farm-map-display').setView([farmLat, farmLng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(lMap);
                    L.marker([farmLat, farmLng]).addTo(lMap).bindPopup(farmNameStr).openPopup();
                }
            }

            const toggle = document.getElementById('toggleTransport');
            const section = document.getElementById('transportSection');
            const invRow = document.getElementById('invoiceTransport');

            if(toggle && section) {
                toggle.addEventListener('change', function() {
                    if(this.checked) {
                        section.style.display = 'block';
                        document.getElementById('requires_transport').value = "1";
                        if(invRow) invRow.style.display = 'flex';

                        if(!pickupMapObj) {
                            const defaultLat = 31.9522, defaultLng = 35.9334;

                            if (useGoogleMaps) {
                                pickupMapObj = new google.maps.Map(pickupMapEl, { zoom: 12, center: { lat: defaultLat, lng: defaultLng } });
                                pickupMapObj.addListener('click', function(e) {
                                    setPickupMarkerDual(e.latLng.lat(), e.latLng.lng(), true);
                                });
                            } else {
                                pickupMapObj = L.map('pickup-map').setView([defaultLat, defaultLng], 12);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(pickupMapObj);
                                pickupMapObj.on('click', function(e) {
                                    setPickupMarkerDual(e.latlng.lat, e.latlng.lng, false);
                                });
                            }
                        }
                    } else {
                        section.style.display = 'none';
                        if(invRow) invRow.style.display = 'none';
                        document.getElementById('requires_transport').value = "0";
                        transportCost = 0;
                        document.getElementById('transport_cost').value = 0;
                        document.getElementById('transportCalc').style.display = 'none';
                        updateInvoice();
                    }
                });
            }
        };

        function setPickupMarkerDual(lat, lng, isGoogle) {
            if (isGoogle) {
                if(pickupMarkerObj) pickupMarkerObj.setMap(null);
                pickupMarkerObj = new google.maps.Marker({ position: { lat, lng }, map: pickupMapObj });
            } else {
                if(pickupMarkerObj) pickupMapObj.removeLayer(pickupMarkerObj);
                pickupMarkerObj = L.marker([lat, lng]).addTo(pickupMapObj);
            }

            document.getElementById('pickup_lat').value = lat;
            document.getElementById('pickup_lng').value = lng;

            // Calculate Route
            fetch(`https://router.project-osrm.org/route/v1/driving/${lng},${lat};${farmLng},${farmLat}?overview=false`)
                .then(res => res.json())
                .then(data => {
                    if(data.routes && data.routes.length > 0) {
                        let distanceKm = (data.routes[0].distance / 1000).toFixed(1);
                        transportCost = parseFloat((25 + (distanceKm * 0.5)).toFixed(2));
                        document.getElementById('distVal').innerText = distanceKm;
                        document.getElementById('costVal').innerText = transportCost;
                        document.getElementById('transport_cost').value = transportCost;
                        document.getElementById('transportCalc').style.display = 'flex';
                        document.getElementById('invoiceTransportCost').innerText = transportCost.toFixed(2) + ' JOD';
                        updateInvoice();
                    }
                });
        }

        window.searchPickupLocation = function() {
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
                        pickupMapObj.setCenter({ lat, lng: lon });
                        pickupMapObj.setZoom(14);
                    } else {
                        pickupMapObj.setView([lat, lon], 14);
                    }
                    setPickupMarkerDual(lat, lon, isGoogle);
                } else {
                    alert("Location not found in Jordan. Try a more specific address.");
                }
            });
        };

        // Fullscreen map logic
        const expandBtn = document.getElementById('expandMapBtn');
        const mapWrapper = document.getElementById('pickup-map-wrapper');
        const originalMapContainer = document.getElementById('originalMapContainer');
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
                    originalMapContainer.appendChild(mapWrapper);
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
