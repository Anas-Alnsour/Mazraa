@extends('layouts.app')

@section('title', $farm->name)

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
                        {{ $farm->location }}
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
                        <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
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
                    <div class="w-full h-[500px] overflow-hidden rounded-[2.5rem] relative border border-gray-200 bg-gray-50 flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-gray-400 font-black tracking-widest uppercase text-sm">No images available</span>
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
                                <p class="font-black text-gray-900 text-lg">{{ number_format($farm->average_rating, 1) }} <span class="text-sm font-bold text-gray-400">/ 5.0</span></p>
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
                            <div id="farm-map" class="w-full h-[350px] rounded-[2rem] z-0"></div>
                        </div>
                        <div class="flex items-start gap-3 px-2">
                            <svg class="w-6 h-6 text-[#1d5c42] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div>
                                <p class="font-black text-gray-900">{{ $farm->name }}</p>
                                <p class="text-gray-500 font-medium text-sm">{{ $farm->location }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ⭐️ Reviews Section --}}
                    <div class="pt-6">
                        <x-reviews-section
                            :reviews="$farm->reviews"
                            :reviewable-id="$farm->id"
                            reviewable-type="farm"
                            :average-rating="$farm->average_rating"
                        />
                    </div>
                </div>

                {{-- Right Column: Sticky Booking Card --}}
                <div class="lg:w-[40%] xl:w-[35%] relative">
                    <div class="booking-card rounded-[2.5rem] p-8 md:p-10 sticky top-28 z-20">

                        <div class="flex items-baseline gap-2 border-b border-gray-200/60 pb-6 mb-8">
                            <span class="text-4xl font-black text-gray-900 tracking-tighter">{{ number_format($farm->price_per_night, 0) }}</span>
                            <span class="text-lg font-bold text-gray-900">JOD</span>
                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">/ Shift</span>
                        </div>

                        {{-- 💡 AlpineJS Form Data for Toggling Modes --}}
                        <form action="{{ route('farms.book', $farm->id) }}" method="POST" id="bookingForm" class="space-y-6" x-data="{ bookingMode: 'single' }">
                            @csrf

                            <input type="hidden" name="booking_mode" x-model="bookingMode">
                            <input type="hidden" name="start_time" id="start_time">
                            <input type="hidden" name="end_time" id="end_time">
                            <input type="hidden" name="requires_transport" id="requires_transport" value="0">
                            <input type="hidden" name="transport_cost" id="transport_cost" value="0">
                            <input type="hidden" name="pickup_lat" id="pickup_lat">
                            <input type="hidden" name="pickup_lng" id="pickup_lng">

                            {{-- 🌟 iOS Style Segmented Control --}}
                            <div class="flex p-1.5 bg-gray-100/80 rounded-2xl mb-6 border border-gray-200/50">
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

                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Event Type</label>
                                <div class="relative">
                                    <select name="event_type" id="eventType" class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-5 text-sm font-bold text-gray-800 focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-all outline-none appearance-none shadow-sm" required>
                                        <option value="">Select an occasion...</option>
                                        <option value="Birthday">🎉 Birthday Celebration</option>
                                        <option value="Wedding">💍 Wedding / Engagement</option>
                                        <option value="Other">✨ Family Gathering / Other</option>
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
                                        class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-5 text-sm font-bold text-gray-800 focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-all outline-none shadow-sm cursor-pointer bg-[url('data:image/svg+xml;base64,PHN2ZyBmaWxsPSJub25lIiBzdHJva2U9IiM5Y2EzYWYiIHZpZXdCb3g9IjAgMCAyNCAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIHN0cm9rZS13aWR0aD0iMiIgZD0iTTggN1YzbTggNHYzTTEgMTFoMThNNSAyMWgxNGExIDEgMCAwMDItMlY3YTEgMSAwIDAwLTItMkg1YTEgMSAwIDAwLTIgMnYxMmExIDEgMCAwMDIgMnoiPjwvcGF0aD48L3N2Zz4=')] bg-no-repeat bg-[right_1.25rem_center] bg-[length:1.25rem_1.25rem]">
                                </div>

                                {{-- 🌟 UX: Color Legend for the Calendar --}}
                                <div class="flex flex-wrap items-center justify-center gap-3 mb-6 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                    <div class="flex items-center gap-1.5 text-[9px] font-black text-gray-600 uppercase tracking-widest" title="Morning is booked, Evening is available">
                                        <div class="w-4 h-4 rounded-md border border-orange-300" style="background: linear-gradient(135deg, #ffedd5 50%, #ffffff 50%);"></div> Morning Booked
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[9px] font-black text-gray-600 uppercase tracking-widest" title="Evening is booked, Morning is available">
                                        <div class="w-4 h-4 rounded-md border border-indigo-300" style="background: linear-gradient(135deg, #ffffff 50%, #e0e7ff 50%);"></div> Evening Booked
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[9px] font-black text-gray-600 uppercase tracking-widest" title="Fully Booked">
                                        <div class="w-4 h-4 rounded-md bg-[#f1f5f9] border border-gray-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </div> Full
                                    </div>
                                </div>

                                <div id="shiftsContainer" style="display: none;" class="animate-fade-in">
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Available Shifts</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button type="button" id="btnMorning" onclick="selectShift('morning')"
                                            class="shift-btn border-2 border-gray-200 bg-white p-4 rounded-2xl flex flex-col items-center justify-center hover:border-[#1d5c42] hover:shadow-md transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed group">
                                            <span class="text-sm font-black text-gray-800 transition-colors">☀️ Morning</span>
                                            <span class="text-[10px] font-bold text-gray-500 mt-1">10 AM - 8 PM</span>
                                        </button>

                                        <button type="button" id="btnEvening" onclick="selectShift('evening')"
                                            class="shift-btn border-2 border-gray-200 bg-white p-4 rounded-2xl flex flex-col items-center justify-center hover:border-indigo-500 hover:shadow-md transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed group">
                                            <span class="text-sm font-black text-gray-800 transition-colors">🌙 Evening</span>
                                            <span class="text-[10px] font-bold text-gray-500 mt-1">10 PM - 8 AM</span>
                                        </button>

                                        <button type="button" id="btnFullDay" onclick="selectShift('full_day')"
                                            class="shift-btn col-span-2 border-2 border-gray-200 bg-white p-4 rounded-2xl flex flex-col items-center justify-center hover:border-[#c2a265] hover:shadow-md transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed group">
                                            <span class="text-sm font-black text-gray-800 transition-colors">👑 Full Day Experience</span>
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

                            {{-- Transport Integration Toggle --}}
                            @if($farm->latitude && $farm->longitude)
                            <div class="border-t border-gray-200/60 pt-6 mt-4">
                                <label class="flex items-center cursor-pointer group bg-gray-50 p-4 rounded-2xl border border-gray-200 transition-colors hover:bg-green-50 hover:border-green-200">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" id="toggleTransport" class="w-5 h-5 rounded-md border-gray-300 text-[#1d5c42] focus:ring-[#1d5c42] transition-all cursor-pointer">
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <span class="block text-sm font-black text-gray-900 group-hover:text-[#1d5c42] transition-colors">Add Shuttle Transport</span>
                                        <span class="block text-[10px] font-bold text-gray-500 mt-0.5">Let us drive you to the farm</span>
                                    </div>
                                </label>

                                <div id="transportSection" class="hidden mt-4 space-y-4 animate-fade-in">

                                    {{-- Location Search Bar --}}
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Search Your Location</label>
                                        <div class="flex gap-2">
                                            <input type="text" id="pickup_search" placeholder="e.g. Amman, 7th Circle" class="flex-1 bg-white border border-gray-200 rounded-xl py-3 px-4 text-sm font-bold text-gray-800 focus:ring-2 focus:ring-[#1d5c42] outline-none">
                                            <button type="button" onclick="searchPickupLocation()" class="bg-[#1d5c42] text-white px-4 rounded-xl font-bold hover:bg-[#154230] transition-colors">Search</button>
                                        </div>
                                    </div>

                                    <div class="relative p-1.5 bg-white border border-gray-200 rounded-2xl shadow-sm">
                                        {{-- Map Container --}}
                                        <div id="pickup-map-wrapper" class="w-full h-[200px] rounded-xl z-0 relative overflow-hidden transition-all duration-300">
                                            <div id="pickup-map" class="w-full h-full"></div>
                                            {{-- Expand Button overlay --}}
                                            <button type="button" id="expandMapBtn" class="absolute bottom-3 right-3 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg shadow-md text-xs font-black text-gray-800 hover:text-[#1d5c42] border border-gray-200 z-[1000] flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                                                Expand
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-500 font-bold text-center">Click or drag on the map to set exact pickup point</p>

                                    <div id="transportCalc" class="hidden bg-blue-50 border border-blue-100 rounded-xl p-4 flex justify-between items-center animate-fade-in">
                                        <div class="text-blue-800">
                                            <span class="block text-[10px] font-black uppercase tracking-widest opacity-70">Distance</span>
                                            <span class="text-sm font-bold"><span id="distVal"></span> km</span>
                                        </div>
                                        <div class="text-blue-800 text-right">
                                            <span class="block text-[10px] font-black uppercase tracking-widest opacity-70">Est. Cost</span>
                                            <span class="text-sm font-black"><span id="costVal"></span> JOD</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Comprehensive Invoice --}}
                            <div id="bookingSummary" class="hidden bg-gray-900 text-white p-6 rounded-2xl shadow-xl mt-6 animate-fade-in relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-[#1d5c42]/20 rounded-bl-[100px] blur-2xl"></div>

                                <h3 class="font-black text-gray-300 text-[10px] uppercase mb-5 tracking-[0.2em] border-b border-gray-700/50 pb-3">Invoice Summary</h3>
                                <div class="space-y-3 text-sm relative z-10">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-gray-400" id="farmRentalLabel">Farm Rental</span>
                                        <span class="font-bold text-white" id="farmRentalDisplay">0.00 JOD</span>
                                    </div>
                                    <div id="invoiceTransport" class="flex justify-between items-center hidden">
                                        <span class="font-medium text-gray-400">Transport Fee</span>
                                        <span class="font-bold text-white" id="invoiceTransportCost">0.00 JOD</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-gray-400">Platform Tax (16%)</span>
                                        <span class="font-bold text-white" id="invoiceTax">0.00 JOD</span>
                                    </div>
                                    <div class="flex justify-between items-end pt-4 border-t border-gray-700/50 mt-3">
                                        <span class="font-black text-gray-300 uppercase tracking-widest text-xs">Total Due</span>
                                        <span class="font-black text-[#c2a265] text-2xl" id="invoiceTotal">0.00 JOD</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="confirmBookingBtn" disabled
                                class="relative w-full flex justify-center items-center py-5 px-4 mt-6 rounded-2xl text-sm font-black uppercase tracking-widest text-white bg-gradient-to-r from-[#1d5c42] to-[#154230] hover:shadow-[0_10px_25px_rgba(29,92,66,0.4)] focus:outline-none transition-all duration-300 ease-out overflow-hidden group hover:-translate-y-1 active:translate-y-0 active:scale-[0.98] disabled:bg-none disabled:bg-gray-200 disabled:text-gray-400 disabled:shadow-none disabled:cursor-not-allowed disabled:transform-none">
                                <span class="absolute top-0 -left-full w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:animate-[shimmer_1.5s_infinite]"></span>
                                <span class="relative z-10 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    Proceed to Payment
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </span>
                            </button>
                            <p class="text-center text-[10px] text-gray-400 font-bold mt-2">You will be redirected to Visa / CliQ secure checkout.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Map Backdrop for Fullscreen --}}
    <div id="mapBackdrop"></div>

    @push('scripts')
    {{-- Leaflet JS & Flatpickr JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <?php
        $bookingsData = [];
        if(isset($farm->bookings) && $farm->bookings) {
            foreach($farm->bookings as $b) {
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
        const farmPrice = <?php echo floatval($farm->price_per_night ?? 0); ?>;
        const farmLat = <?php echo $farm->latitude ? '"' . $farm->latitude . '"' : 'null'; ?>;
        const farmLng = <?php echo $farm->longitude ? '"' . $farm->longitude . '"' : 'null'; ?>;
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

        // Parse bookings into a hashmap for the calendar UI
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
                // Adjust for timezone to get strict YYYY-MM-DD
                const localDate = new Date(dayElem.dateObj.getTime() - (dayElem.dateObj.getTimezoneOffset() * 60000)).toISOString().split('T')[0];
                if (morningBookedDates.includes(localDate)) {
                    dayElem.classList.add('booked-morning');
                } else if (eveningBookedDates.includes(localDate)) {
                    dayElem.classList.add('booked-evening');
                }
            }
        };

        // Init Single Day Calendar
        const singlePicker = flatpickr("#booking_date_flatpickr", {
            ...fpConfig,
            onChange: function(selectedDates, dateStr, instance) {
                if(dateStr) {
                    document.getElementById('booking_date').value = dateStr;
                    checkAvailability(dateStr);

                    // Reset Shift selection WITHOUT hiding the container!
                    selectedShift = false;
                    currentShiftType = '';
                    document.getElementById('confirmBookingBtn').disabled = true;
                    document.getElementById('bookingSummary').classList.add('hidden');
                    document.querySelectorAll('.shift-btn').forEach(btn => {
                        btn.classList.remove('bg-green-50', 'border-[#1d5c42]', 'bg-indigo-50', 'border-indigo-500', 'bg-amber-50', 'border-[#c2a265]');
                        btn.classList.add('border-gray-200', 'bg-white');
                    });

                    // Explicitly Show the container using style display block
                    document.getElementById('shiftsContainer').style.display = 'block';
                }
            }
        });

        // Init Multi Day Calendars (Two Separate Inputs for Check-in / Check-out)
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

        // --- FORM & SHIFT LOGIC ---
        window.resetBookingForm = function() {
            singlePicker.clear();
            multiStartPicker.clear();
            multiEndPicker.clear();
            resetShiftSelection();
            document.getElementById('shiftsContainer').style.display = 'none';
        };

        function resetShiftSelection() {
            selectedShift = false;
            currentShiftType = '';
            document.getElementById('confirmBookingBtn').disabled = true;
            document.getElementById('bookingSummary').classList.add('hidden');
            document.getElementById('start_time').value = '';
            document.getElementById('end_time').value = '';

            document.querySelectorAll('.shift-btn').forEach(btn => {
                btn.classList.remove('bg-green-50', 'border-[#1d5c42]', 'bg-indigo-50', 'border-indigo-500', 'bg-amber-50', 'border-[#c2a265]');
                btn.classList.add('border-gray-200', 'bg-white');
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

            document.querySelectorAll('.shift-btn').forEach(btn => {
                btn.classList.remove('bg-green-50', 'border-[#1d5c42]', 'bg-indigo-50', 'border-indigo-500', 'bg-amber-50', 'border-[#c2a265]');
                btn.classList.add('border-gray-200', 'bg-white');
            });

            currentShiftType = type;

            if (type === 'morning') {
                btnMorning.classList.add('bg-green-50', 'border-[#1d5c42]');
                document.getElementById('start_time').value = dateVal + ' 10:00:00';
                document.getElementById('end_time').value = dateVal + ' 20:00:00';
            } else if (type === 'evening') {
                btnEvening.classList.add('bg-indigo-50', 'border-indigo-500');
                let tomorrow = new Date(dateVal);
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('start_time').value = dateVal + ' 22:00:00';
                document.getElementById('end_time').value = tomorrow.toISOString().split('T')[0] + ' 08:00:00';
            } else if (type === 'full_day') {
                btnFullDay.classList.add('bg-amber-50', 'border-[#c2a265]');
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
                base = (farmPrice * 2) * multiDaysCount;
                label = `Farm Rental (${multiDaysCount} Nights)`;
            } else if (currentShiftType === 'full_day') {
                base = farmPrice * 2;
                label = 'Farm Rental (Full Day)';
            } else {
                base = farmPrice;
                label = 'Farm Rental (1 Shift)';
            }

            let totalBeforeTax = base + transportCost;
            let tax = totalBeforeTax * 0.16;
            let grandTotal = totalBeforeTax + tax;

            document.getElementById('farmRentalLabel').innerText = label;
            document.getElementById('farmRentalDisplay').innerText = base.toFixed(2) + ' JOD';
            document.getElementById('invoiceTax').innerText = tax.toFixed(2) + ' JOD';
            document.getElementById('invoiceTotal').innerText = grandTotal.toFixed(2) + ' JOD';

            document.getElementById('bookingSummary').classList.remove('hidden');
        }

        /* --- TRANSPORT LOGIC WITH ADDRESS SEARCH AND FULLSCREEN --- */
        let pickupMap, pickupMarker;
        document.addEventListener('DOMContentLoaded', function () {
            if(farmLat && farmLat !== 'null' && farmLng && farmLng !== 'null') {
                var map = L.map('farm-map', {scrollWheelZoom: false}).setView([farmLat, farmLng], 13);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
                }).addTo(map);

                const farmName = <?php echo json_encode($farm->name); ?>;

                var customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: "<div style='background-color:#1d5c42; width:20px; height:20px; border-radius:50%; border:3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3);'></div>",
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });

                L.marker([farmLat, farmLng], {icon: customIcon}).addTo(map).bindPopup("<b style='color:#1d5c42'>" + farmName + "</b>").openPopup();

                const toggle = document.getElementById('toggleTransport');
                const section = document.getElementById('transportSection');
                const invRow = document.getElementById('invoiceTransport');

                if(toggle && section) {
                    toggle.addEventListener('change', function() {
                        if(this.checked) {
                            section.classList.remove('hidden');
                            document.getElementById('requires_transport').value = "1";
                            if(invRow) invRow.classList.remove('hidden');

                            if(!pickupMap) {
                                pickupMap = L.map('pickup-map').setView([31.9522, 35.9334], 12);
                                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(pickupMap);

                                pickupMap.on('click', function(e) {
                                    setPickupMarker(e.latlng.lat, e.latlng.lng);
                                });
                            }
                            setTimeout(() => pickupMap.invalidateSize(), 200);
                        } else {
                            section.classList.add('hidden');
                            if(invRow) invRow.classList.add('hidden');
                            document.getElementById('requires_transport').value = "0";
                            transportCost = 0;
                            updateInvoice();
                        }
                    });
                }

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
                        } else {
                            originalMapContainer.appendChild(mapWrapper);
                            mapWrapper.classList.remove('map-fullscreen');
                            backdrop.classList.remove('active');
                            expandBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg> Expand`;
                            document.body.style.overflow = 'auto';
                        }
                        setTimeout(() => pickupMap.invalidateSize(), 300);
                    });
                }
            }
        });

        window.searchPickupLocation = function() {
            const query = document.getElementById('pickup_search').value;
            if(!query) return;

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=JO`)
            .then(res => res.json())
            .then(data => {
                if(data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    pickupMap.setView([lat, lon], 14);
                    setPickupMarker(lat, lon);
                } else {
                    alert("Location not found in Jordan. Try a more specific address.");
                }
            });
        };

        function setPickupMarker(lat, lng) {
            if(pickupMarker) pickupMap.removeLayer(pickupMarker);
            pickupMarker = L.marker([lat, lng]).addTo(pickupMap);

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
                        document.getElementById('transportCalc').classList.remove('hidden');
                        document.getElementById('invoiceTransportCost').innerText = transportCost.toFixed(2) + ' JOD';
                        updateInvoice();
                    }
                });
        }
    </script>
    @endpush
@endsection
