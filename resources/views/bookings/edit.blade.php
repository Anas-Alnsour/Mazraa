@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
    {{-- 1. Prepare Blocked Slots Data (PHP) --}}
    @php
        $blockedSlots = $booking->farm->bookings()
            ->where('id', '!=', $booking->id)
            ->where('start_time', '>=', now()->startOfDay())
            ->get()
            ->map(function ($b) {
                $start = \Carbon\Carbon::parse($b->start_time);
                // 10 AM is morning, 10 PM (22:00) is evening
                $shiftType = $start->format('H') == '10' ? 'morning' : 'evening';
                return [
                    'date' => $start->format('Y-m-d'),
                    'shift' => $shiftType
                ];
            });
    @endphp

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Booking</h1>
                <p class="mt-1 text-gray-500 text-sm">Update reservation (10-Hours Shift).</p>
            </div>
            <a href="{{ route('bookings.my_bookings') }}" class="text-gray-500 hover:text-green-600 transition flex items-center gap-1 text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Cancel
            </a>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8"
             x-data="bookingForm()"
             x-init="initData()"> @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                    <ul class="list-disc pl-5 space-y-1 text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="space-y-8" @submit.prevent="submitForm">
                @csrf
                @method('PUT')

                <div class="relative group">
                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Event Type</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>
                        </div>
                        <select name="event_type" class="w-full pl-11 pr-10 py-3.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none transition-all appearance-none cursor-pointer font-medium text-gray-700">
                            <option value="Birthday" {{ old('event_type', $booking->event_type) == 'Birthday' ? 'selected' : '' }}>Birthday</option>
                            <option value="Wedding" {{ old('event_type', $booking->event_type) == 'Wedding' ? 'selected' : '' }}>Wedding</option>
                            <option value="Other" {{ old('event_type', $booking->event_type) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="start_time" :value="finalStartTime">
                <input type="hidden" name="end_time" :value="finalEndTime">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3 ml-1">Select Date & Shift</label>
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-200">
                        <div class="space-y-4">

                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase mb-1 block">Booking Date</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <input type="date"
                                           x-model="selectedDate"
                                           @change="updateTimes()"
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none font-medium text-gray-700 transition">
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase mb-1 block">Select Shift (10 Hours)</label>
                                <div class="grid grid-cols-2 gap-3">

                                    <button type="button"
                                        @click="selectShift('morning')"
                                        :disabled="isShiftBlocked('morning')"
                                        :class="{
                                            'bg-white border-green-500 text-green-700 ring-2 ring-green-100 shadow-md': selectedShift === 'morning' && !isShiftBlocked('morning'),
                                            'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300': selectedShift !== 'morning' && !isShiftBlocked('morning'),
                                            'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed opacity-75': isShiftBlocked('morning')
                                        }"
                                        class="py-4 px-2 rounded-xl border flex flex-col items-center justify-center gap-2 transition-all duration-200 group relative overflow-hidden">

                                        <div x-show="isShiftBlocked('morning')" class="absolute inset-0 bg-gray-50/50 flex items-center justify-center">
                                            <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full uppercase tracking-wide">Booked</span>
                                        </div>

                                        <svg class="w-8 h-8" :class="selectedShift === 'morning' ? 'text-yellow-500' : (isShiftBlocked('morning') ? 'text-gray-300' : 'text-gray-400 group-hover:text-yellow-500')" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <span class="font-bold">Morning</span>
                                        <span class="text-xs opacity-75">10:00 AM - 8:00 PM</span>
                                    </button>

                                    <button type="button"
                                        @click="selectShift('evening')"
                                        :disabled="isShiftBlocked('evening')"
                                        :class="{
                                            'bg-white border-purple-500 text-purple-700 ring-2 ring-purple-100 shadow-md': selectedShift === 'evening' && !isShiftBlocked('evening'),
                                            'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300': selectedShift !== 'evening' && !isShiftBlocked('evening'),
                                            'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed opacity-75': isShiftBlocked('evening')
                                        }"
                                        class="py-4 px-2 rounded-xl border flex flex-col items-center justify-center gap-2 transition-all duration-200 group relative overflow-hidden">

                                        <div x-show="isShiftBlocked('evening')" class="absolute inset-0 bg-gray-50/50 flex items-center justify-center">
                                            <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full uppercase tracking-wide">Booked</span>
                                        </div>

                                        <svg class="w-8 h-8" :class="selectedShift === 'evening' ? 'text-purple-500' : (isShiftBlocked('evening') ? 'text-gray-300' : 'text-gray-400 group-hover:text-purple-500')" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                        <span class="font-bold">Evening</span>
                                        <span class="text-xs opacity-75">10:00 PM - 8:00 AM</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="pt-6 flex flex-col-reverse sm:flex-row gap-4">
                    <a href="{{ route('bookings.my_bookings') }}"
                       class="w-full sm:w-1/3 py-3.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition text-center shadow-sm">
                        Cancel
                    </a>
                    <button type="submit"
                        :disabled="!selectedShift || isShiftBlocked(selectedShift)"
                        :class="{'opacity-50 cursor-not-allowed': !selectedShift || isShiftBlocked(selectedShift), 'hover:bg-green-700 hover:shadow-green-300 hover:-translate-y-0.5': selectedShift && !isShiftBlocked(selectedShift)}"
                        class="w-full sm:w-2/3 py-3.5 bg-green-600 text-white font-bold rounded-xl shadow-lg shadow-green-200 transition-all flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Update Booking
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function bookingForm() {
            return {
                // Initialize values
                selectedDate: '{{ \Carbon\Carbon::parse($booking->start_time)->format("Y-m-d") }}',
                // If hour is 10 => morning, else (probably 22) => evening
                selectedShift: '{{ \Carbon\Carbon::parse($booking->start_time)->format("H") == "10" ? "morning" : "evening" }}',

                blockedSlots: @json($blockedSlots),

                finalStartTime: '',
                finalEndTime: '',

                initData() {
                    // Calculate times immediately on load so hidden inputs are filled
                    this.updateTimes();
                },

                selectShift(shift) {
                    if(!this.isShiftBlocked(shift)) {
                        this.selectedShift = shift;
                        this.updateTimes();
                    }
                },

                updateTimes() {
                    if (!this.selectedDate || !this.selectedShift) return;

                    if (this.selectedShift === 'morning') {
                        // Morning: 10:00 AM to 08:00 PM (Same Day)
                        this.finalStartTime = this.selectedDate + ' 10:00:00';
                        this.finalEndTime = this.selectedDate + ' 20:00:00';
                    } else {
                        // Evening: 10:00 PM to 08:00 AM (Next Day)
                        this.finalStartTime = this.selectedDate + ' 22:00:00';

                        // Calculate next day
                        let dateObj = new Date(this.selectedDate);
                        dateObj.setDate(dateObj.getDate() + 1);
                        let nextDay = dateObj.toISOString().split('T')[0];

                        this.finalEndTime = nextDay + ' 08:00:00';
                    }
                },

                isShiftBlocked(shiftType) {
                    if (!this.selectedDate) return false;
                    return this.blockedSlots.some(slot =>
                        slot.date === this.selectedDate && slot.shift === shiftType
                    );
                },

                submitForm(e) {
                    this.updateTimes(); // Ensure values are fresh before submit

                    if (!this.finalStartTime || !this.finalEndTime) {
                        alert('Please select a valid date and shift.');
                        return;
                    }

                    if (this.isShiftBlocked(this.selectedShift)) {
                        alert('This shift is already booked by another user.');
                        return;
                    }

                    e.target.submit();
                }
            }
        }
    </script>
@endsection
