@extends('layouts.app')

@section('title', $farm->name)

@section('content')
    <div class="bg-[#f9f8f4] min-h-screen pb-20 font-sans pt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm animate-fade-in">
                    <p class="text-green-800 font-bold">{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm animate-fade-in">
                    <p class="text-red-800 font-bold">{{ session('error') }}</p>
                </div>
            @endif

            <div class="mb-8 flex justify-between items-end">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight mb-2">{{ $farm->name }}</h1>
                    <div class="flex items-center text-sm font-bold text-gray-500">
                        <svg class="w-4 h-4 mr-1 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ $farm->location }}
                    </div>
                </div>
                <a href="{{ route('explore') }}" class="hidden md:flex items-center text-sm font-bold text-gray-500 hover:text-[#1d5c42] transition-colors bg-white px-4 py-2 rounded-full border border-gray-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Explore
                </a>
            </div>

            <div class="bg-white p-2 rounded-[2.5rem] shadow-sm border border-gray-100 mb-12">
                @if ($farm->images->count() > 0)
                    <div x-data="gallery({
                        images: [
                            @foreach ($farm->images as $image)
                            '{{ asset('storage/' . $image->image_url) }}',
                            @endforeach
                        ]
                    })">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 h-[50vh] min-h-[400px] rounded-[2rem] overflow-hidden">
                            @if(isset($farm->images[0]))
                            <div class="md:col-span-2 row-span-2 relative group cursor-pointer overflow-hidden">
                                <img src="{{ asset('storage/' . $farm->images[0]->image_url) }}" @click="open(0)" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Main Image">
                                <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                            </div>
                            @endif

                            @foreach($farm->images->skip(1)->take(4) as $index => $image)
                                <div class="hidden md:block relative group cursor-pointer overflow-hidden">
                                    <img src="{{ asset('storage/' . $image->image_url) }}" @click="open({{ $loop->index + 1 }})" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Gallery Image">
                                    <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                                </div>
                            @endforeach
                        </div>

                        <div x-show="isOpen" x-transition style="display: none;"
                            class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 backdrop-blur-sm"
                            @keydown.window.escape="close">
                            <button @click="close" class="absolute top-5 right-5 text-white text-4xl hover:text-red-500 transition-colors">&times;</button>
                            <button @click="prev" class="absolute left-5 text-white text-6xl hover:text-[#1d5c42] transition-colors">‹</button>
                            <img :src="images[current]" class="max-h-[85vh] max-w-[85vw] rounded-2xl shadow-2xl border-4 border-white/10">
                            <button @click="next" class="absolute right-5 text-white text-6xl hover:text-[#1d5c42] transition-colors">›</button>
                        </div>
                    </div>
                @else
                    <div class="w-full h-[400px] overflow-hidden rounded-[2rem] relative">
                        <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : 'https://via.placeholder.com/800x400' }}" alt="{{ $farm->name }}" class="w-full h-full object-cover">
                    </div>
                @endif
            </div>

            <div class="flex flex-col lg:flex-row gap-12">

                <div class="lg:w-2/3 space-y-10">

                    <div class="flex flex-wrap gap-4 border-b border-gray-200 pb-8">
                        <div class="bg-white px-6 py-4 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3">
                            <div class="bg-green-50 p-2 rounded-xl text-[#1d5c42]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Capacity</p>
                                <p class="font-bold text-gray-900">Up to {{ $farm->capacity }} Guests</p>
                            </div>
                        </div>

                        <div class="bg-white px-6 py-4 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3">
                            <div class="bg-amber-50 p-2 rounded-xl text-amber-500">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Rating</p>
                                <p class="font-bold text-gray-900">{{ $farm->rating ?? 'New' }} / 5.0</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-2xl font-black text-gray-900 mb-4">About this escape</h3>
                        <p class="text-gray-600 leading-relaxed font-medium whitespace-pre-line break-all">
                            {{ $farm->description }}
                        </p>
                    </div>
                </div>

                <div class="lg:w-1/3">
                    <div class="bg-white rounded-[2rem] shadow-2xl border border-gray-100 p-8 sticky top-8">

                        <div class="flex items-end gap-2 border-b border-gray-100 pb-6 mb-6">
                            <span class="text-4xl font-black text-[#1d5c42]">${{ number_format($farm->price_per_night, 0) }}</span>
                            <span class="text-sm font-bold text-gray-400 mb-1">/ Shift</span>
                        </div>

                        <form action="{{ route('farms.book', $farm->id) }}" method="POST" id="bookingForm" class="space-y-5">
                            @csrf

                            <input type="hidden" name="start_time" id="start_time">
                            <input type="hidden" name="end_time" id="end_time">

                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Event Type</label>
                                <div class="relative">
                                    <select name="event_type" id="eventType" class="w-full bg-gray-50 border-none rounded-xl py-4 px-4 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-[#1d5c42] transition-all outline-none appearance-none" required>
                                        <option value="">Select Type...</option>
                                        <option value="Birthday">🎉 Birthday</option>
                                        <option value="Wedding">💍 Wedding</option>
                                        <option value="Other">✨ Other</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Select Date</label>
                                <input type="date" name="booking_date" id="booking_date" min="{{ now()->toDateString() }}" required
                                    class="w-full bg-gray-50 border-none rounded-xl py-4 px-4 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-[#1d5c42] transition-all outline-none">
                            </div>

                            <div id="shiftsContainer" class="hidden">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Select Shift</label>
                                <div class="space-y-3">
                                    <button type="button" id="btnMorning" onclick="selectShift('morning')"
                                        class="shift-btn w-full border-2 border-gray-200 bg-white p-4 rounded-xl flex flex-col items-center justify-center hover:border-green-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span class="text-sm font-black text-gray-800">☀️ Morning Shift</span>
                                        <span class="text-[10px] font-bold text-gray-500 mt-1">10:00 AM - 08:00 PM</span>
                                    </button>

                                    <button type="button" id="btnEvening" onclick="selectShift('evening')"
                                        class="shift-btn w-full border-2 border-gray-200 bg-white p-4 rounded-xl flex flex-col items-center justify-center hover:border-green-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span class="text-sm font-black text-gray-800">🌙 Evening Shift</span>
                                        <span class="text-[10px] font-bold text-gray-500 mt-1">10:00 PM - 08:00 AM (Next Day)</span>
                                    </button>
                                </div>
                                <p id="shiftError" class="text-red-500 text-xs font-bold mt-2 text-center hidden">This shift is already booked.</p>
                            </div>

                            <div id="bookingSummary" class="hidden bg-green-50/50 p-4 rounded-xl border border-green-100">
                                <h3 class="font-black text-[#1d5c42] text-xs uppercase mb-3">Booking Summary</h3>
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-gray-600">Date: <span id="summaryDate" class="font-bold text-gray-900"></span></p>
                                    <p class="text-sm font-medium text-gray-600">Shift: <span id="summaryShift" class="font-bold text-gray-900"></span></p>
                                </div>
                            </div>

                            <button type="submit" id="submitBtn" disabled
                                class="w-full bg-[#183126] text-white font-black py-4 rounded-xl shadow-lg hover:bg-[#10231b] hover:shadow-xl transition-all transform active:scale-95 disabled:bg-gray-300 disabled:cursor-not-allowed disabled:transform-none uppercase tracking-widest text-sm mt-4">
                                Confirm Booking
                            </button>
                        </form>

                        <div class="mt-4 pt-4 border-t border-gray-100">
                            @auth
                                @php
                                    $isFav = auth()->user()->favorites()->where('farm_id', $farm->id)->exists();
                                @endphp
                                @if ($isFav)
                                    <form action="{{ route('favorites.destroy', $farm->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-50 text-red-600 border border-red-100 hover:bg-red-100 px-5 py-3 rounded-xl transition font-black text-xs uppercase tracking-widest flex justify-center items-center gap-2">
                                            ❤️ Remove Favorite
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('favorites.store', $farm->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-yellow-50 text-yellow-700 border border-yellow-100 hover:bg-yellow-100 px-5 py-3 rounded-xl transition font-black text-xs uppercase tracking-widest flex justify-center items-center gap-2">
                                            ⭐ Add to Favorites
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="block text-center text-[#1d5c42] font-bold text-xs hover:underline uppercase tracking-widest">
                                    Login to add to favorites
                                </a>
                            @endauth
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const existingBookings = @json($farm->bookings->map(fn($b) => [
            'date' => \Carbon\Carbon::parse($b->start_time)->toDateString(),
            'hour' => (int) \Carbon\Carbon::parse($b->start_time)->format('H'),
        ]));

        const dateInput = document.getElementById('booking_date');
        const shiftsContainer = document.getElementById('shiftsContainer');
        const btnMorning = document.getElementById('btnMorning');
        const btnEvening = document.getElementById('btnEvening');
        const submitBtn = document.getElementById('submitBtn');

        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');

        const bookingSummary = document.getElementById('bookingSummary');
        const summaryDate = document.getElementById('summaryDate');
        const summaryShift = document.getElementById('summaryShift');

        dateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            if (!selectedDate) return;

            shiftsContainer.classList.remove('hidden');
            resetSelection();
            checkAvailability(selectedDate);
        });

        function checkAvailability(date) {
            btnMorning.disabled = false;
            btnEvening.disabled = false;
            btnMorning.classList.remove('bg-gray-100', 'opacity-50', 'cursor-not-allowed');
            btnEvening.classList.remove('bg-gray-100', 'opacity-50', 'cursor-not-allowed');

            existingBookings.forEach(booking => {
                if (booking.date === date) {
                    if (booking.hour === 10) {
                        btnMorning.disabled = true;
                        btnMorning.classList.add('bg-gray-100', 'opacity-50', 'cursor-not-allowed');
                        btnMorning.innerHTML += '<br><span class="text-red-500 font-bold text-xs mt-1 bg-red-50 px-2 py-0.5 rounded">BOOKED</span>';
                    }
                    if (booking.hour === 22) {
                        btnEvening.disabled = true;
                        btnEvening.classList.add('bg-gray-100', 'opacity-50', 'cursor-not-allowed');
                        btnEvening.innerHTML += '<br><span class="text-red-500 font-bold text-xs mt-1 bg-red-50 px-2 py-0.5 rounded">BOOKED</span>';
                    }
                }
            });
        }

        function selectShift(type) {
            const dateVal = dateInput.value;
            if (!dateVal) return;

            document.querySelectorAll('.shift-btn').forEach(btn => {
                btn.classList.remove('bg-green-50', 'border-green-500', 'ring-2', 'ring-green-200');
                btn.classList.add('border-gray-200', 'bg-white');
            });

            let startDateTime = '';
            let endDateTime = '';
            let shiftName = '';

            if (type === 'morning') {
                btnMorning.classList.add('bg-green-50', 'border-green-500', 'ring-2', 'ring-green-200');
                btnMorning.classList.remove('border-gray-200', 'bg-white');

                startDateTime = dateVal + ' 10:00:00';
                endDateTime = dateVal + ' 20:00:00';
                shiftName = 'Morning (10:00 AM - 08:00 PM)';

            } else if (type === 'evening') {
                btnEvening.classList.add('bg-green-50', 'border-green-500', 'ring-2', 'ring-green-200');
                btnEvening.classList.remove('border-gray-200', 'bg-white');

                let tomorrow = new Date(dateVal);
                tomorrow.setDate(tomorrow.getDate() + 1);
                let tomorrowStr = tomorrow.toISOString().split('T')[0];

                startDateTime = dateVal + ' 22:00:00';
                endDateTime = tomorrowStr + ' 08:00:00';
                shiftName = 'Evening (10:00 PM - 08:00 AM)';
            }

            startTimeInput.value = startDateTime;
            endTimeInput.value = endDateTime;

            bookingSummary.classList.remove('hidden');
            summaryDate.innerText = dateVal;
            summaryShift.innerText = shiftName;
            submitBtn.disabled = false;
        }

        function resetSelection() {
            startTimeInput.value = '';
            endTimeInput.value = '';
            bookingSummary.classList.add('hidden');
            submitBtn.disabled = true;
            document.querySelectorAll('.shift-btn').forEach(btn => {
                btn.classList.remove('bg-green-50', 'border-green-500', 'ring-2', 'ring-green-200');
                btn.classList.add('border-gray-200', 'bg-white');
                const span = btn.querySelector('.text-red-500');
                if(span) span.remove();
            });
        }
    </script>
@endsection
