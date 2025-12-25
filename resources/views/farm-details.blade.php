@extends('layouts.app')

@section('title', $farm->name)

@section('content')
    <div class="max-w-6xl mx-auto p-6 space-y-6">

        @if (session('success'))
            <div class="bg-green-200 text-green-800 p-4 rounded-lg shadow-md text-center">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-200 text-red-800 p-4 rounded-lg shadow-md text-center">
                {{ session('error') }}
            </div>
        @endif

        <div class="w-full h-96 overflow-hidden rounded-3xl shadow-lg">
            <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : 'https://via.placeholder.com/800x400' }}"
                alt="{{ $farm->name }}"
                class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-lg space-y-4">
            <h1 class="text-4xl font-extrabold text-green-800">{{ $farm->name }}</h1>
            <p class="text-gray-600"><span class="font-semibold">Location:</span> {{ $farm->location }}</p>

            <p class="text-gray-700 whitespace-pre-line break-all max-w-full ">{{ $farm->description }}</p>

            <div class="flex flex-wrap gap-4 text-gray-700">
                <span><span class="font-semibold">Capacity:</span> {{ $farm->capacity }} persons</span>
                <span><span class="font-semibold">Price per Shift:</span> ${{ $farm->price_per_night }}</span>
                <span><span class="font-semibold">Rating:</span> ⭐ {{ $farm->rating }}</span>
            </div>

            @if ($farm->images->count() > 0)
                <div x-data="gallery({
                    images: [
                        @foreach ($farm->images as $image)
                '{{ asset('storage/' . $image->image_url) }}', @endforeach
                    ]
                })">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                        @foreach ($farm->images as $index => $image)
                            <div class="overflow-hidden rounded-xl shadow-md">
                                <img src="{{ asset('storage/' . $image->image_url) }}" alt="Farm Image"
                                    @click="open({{ $index }})"
                                    class="w-full h-48 object-cover cursor-pointer transform hover:scale-105 transition duration-500">
                            </div>
                        @endforeach
                    </div>

                    <div x-show="isOpen" x-transition
                        class="fixed inset-0 bg-black/80 flex items-center justify-center z-50"
                        @keydown.window.escape="close">
                        <button @click="close" class="absolute top-5 right-5 text-white text-4xl">&times;</button>
                        <button @click="prev" class="absolute left-5 text-white text-5xl">‹</button>
                        <img :src="images[current]" class="max-h-[90vh] max-w-[90vw] rounded-lg shadow-lg">
                        <button @click="next" class="absolute right-5 text-white text-5xl">›</button>
                    </div>
                </div>
            @endif


            <div class="mt-8 border-t pt-6">
                <h2 class="text-2xl font-semibold mb-4 text-green-800">Book this farm</h2>

                <form action="{{ route('farms.book', $farm->id) }}" method="POST" class="space-y-5" id="bookingForm">
                    @csrf

                    <input type="hidden" name="start_time" id="start_time">
                    <input type="hidden" name="end_time" id="end_time">

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Choose Event Type:</label>
                        <select name="event_type" id="eventType" class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-green-500 outline-none" required>
                            <option value="">Select Type</option>
                            <option value="Birthday">Birthday</option>
                            <option value="Wedding">Wedding</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Select Date:</label>
                        <input type="date" name="booking_date" id="booking_date"
                            class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-green-500 outline-none"
                            min="{{ now()->toDateString() }}" required>
                    </div>

                    <div id="shiftsContainer" class="hidden">
                        <label class="block mb-2 font-medium text-gray-700">Select Shift:</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <button type="button" id="btnMorning" onclick="selectShift('morning')"
                                class="shift-btn border-2 border-gray-200 p-4 rounded-xl flex flex-col items-center justify-center hover:border-green-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="text-lg font-bold text-gray-800">☀️ Morning Shift</span>
                                <span class="text-sm text-gray-500">10:00 AM - 08:00 PM</span>
                            </button>

                            <button type="button" id="btnEvening" onclick="selectShift('evening')"
                                class="shift-btn border-2 border-gray-200 p-4 rounded-xl flex flex-col items-center justify-center hover:border-green-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="text-lg font-bold text-gray-800">🌙 Evening Shift</span>
                                <span class="text-sm text-gray-500">10:00 PM - 08:00 AM (Next Day)</span>
                            </button>
                        </div>
                        <p id="shiftError" class="text-red-500 text-sm mt-2 hidden">This shift is already booked.</p>
                    </div>

                    <div id="bookingSummary" class="hidden bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <h3 class="font-semibold text-green-800 mb-2">Booking Summary</h3>
                        <p><strong>Date:</strong> <span id="summaryDate"></span></p>
                        <p><strong>Shift:</strong> <span id="summaryShift"></span></p>
                    </div>

                    <button type="submit" id="submitBtn" disabled
                        class="w-full bg-green-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-green-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Confirm Booking
                    </button>
                </form>
            </div>


            <div class="mt-6 flex justify-end">
                @auth
                    @php
                        $isFav = auth()->user()->favorites()->where('farm_id', $farm->id)->exists();
                    @endphp

                    @if ($isFav)
                        <form action="{{ route('favorites.destroy', $farm->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-100 text-red-600 px-5 py-2 rounded-xl border border-red-200 hover:bg-red-200 transition font-semibold">
                                ❤️ Remove from Favorites
                            </button>
                        </form>
                    @else
                        <form action="{{ route('favorites.store', $farm->id) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit"
                                class="bg-yellow-100 text-yellow-700 px-5 py-2 rounded-xl border border-yellow-200 hover:bg-yellow-200 transition font-semibold">
                                ⭐ Add to Favorites
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="text-green-600 font-semibold hover:underline">
                        Login to add to favorites
                    </a>
                @endauth
            </div>

        </div>
    </div>

    <script>
        // 1. جلب الحجوزات من الباك اند
        const existingBookings = @json($farm->bookings->map(fn($b) => [
            'date' => \Carbon\Carbon::parse($b->start_time)->toDateString(),
            'hour' => (int) \Carbon\Carbon::parse($b->start_time)->format('H'),
        ]));

        const dateInput = document.getElementById('booking_date');
        const shiftsContainer = document.getElementById('shiftsContainer');
        const btnMorning = document.getElementById('btnMorning');
        const btnEvening = document.getElementById('btnEvening');
        const submitBtn = document.getElementById('submitBtn');

        // Hidden Inputs
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');

        // Summary
        const bookingSummary = document.getElementById('bookingSummary');
        const summaryDate = document.getElementById('summaryDate');
        const summaryShift = document.getElementById('summaryShift');

        // عند تغيير التاريخ
        dateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            if (!selectedDate) return;

            // إظهار قسم الفترات
            shiftsContainer.classList.remove('hidden');
            resetSelection();

            // فحص الحجوزات الموجودة لتعطيل الأزرار المحجوبة
            checkAvailability(selectedDate);
        });

        function checkAvailability(date) {
            // إعادة تفعيل الأزرار أولاً
            btnMorning.disabled = false;
            btnEvening.disabled = false;
            btnMorning.classList.remove('bg-gray-100', 'opacity-50', 'cursor-not-allowed');
            btnEvening.classList.remove('bg-gray-100', 'opacity-50', 'cursor-not-allowed');

            // البحث في الحجوزات
            existingBookings.forEach(booking => {
                if (booking.date === date) {
                    // إذا كان هناك حجز يبدأ الساعة 10 صباحاً -> عطل الفترة الصباحية
                    if (booking.hour === 10) {
                        btnMorning.disabled = true;
                        btnMorning.classList.add('bg-gray-100', 'opacity-50', 'cursor-not-allowed');
                        btnMorning.innerHTML += '<br><span class="text-red-500 font-bold text-xs">BOOKED</span>';
                    }
                    // إذا كان هناك حجز يبدأ الساعة 22 (10 مساءً) -> عطل الفترة المسائية
                    if (booking.hour === 22) {
                        btnEvening.disabled = true;
                        btnEvening.classList.add('bg-gray-100', 'opacity-50', 'cursor-not-allowed');
                        btnEvening.innerHTML += '<br><span class="text-red-500 font-bold text-xs">BOOKED</span>';
                    }
                }
            });
        }

        function selectShift(type) {
            const dateVal = dateInput.value;
            if (!dateVal) return;

            // Reset visual styles
            document.querySelectorAll('.shift-btn').forEach(btn => {
                btn.classList.remove('bg-green-100', 'border-green-600', 'ring-2', 'ring-green-500');
                btn.classList.add('border-gray-200');
            });

            let startDateTime = '';
            let endDateTime = '';
            let shiftName = '';

            if (type === 'morning') {
                // تلوين الزر المختار
                btnMorning.classList.add('bg-green-100', 'border-green-600', 'ring-2', 'ring-green-500');
                btnMorning.classList.remove('border-gray-200');

                // تعيين الوقت (10 ص - 8 م)
                startDateTime = dateVal + ' 10:00:00';
                endDateTime = dateVal + ' 20:00:00';
                shiftName = 'Morning (10:00 AM - 08:00 PM)';

            } else if (type === 'evening') {
                // تلوين الزر المختار
                btnEvening.classList.add('bg-green-100', 'border-green-600', 'ring-2', 'ring-green-500');
                btnEvening.classList.remove('border-gray-200');

                // تعيين الوقت (10 م - 8 ص اليوم التالي)
                // نحتاج حساب اليوم التالي للتاريخ المختار
                let tomorrow = new Date(dateVal);
                tomorrow.setDate(tomorrow.getDate() + 1);
                let tomorrowStr = tomorrow.toISOString().split('T')[0];

                startDateTime = dateVal + ' 22:00:00'; // 10 PM
                endDateTime = tomorrowStr + ' 08:00:00'; // 8 AM Next Day
                shiftName = 'Evening (10:00 PM - 08:00 AM)';
            }

            // تحديث الحقول المخفية
            startTimeInput.value = startDateTime;
            endTimeInput.value = endDateTime;

            // تحديث الملخص
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
                btn.classList.remove('bg-green-100', 'border-green-600', 'ring-2', 'ring-green-500');
                // Remove existing "BOOKED" text if re-checking
                const span = btn.querySelector('.text-red-500');
                if(span) span.remove();
            });
        }
    </script>
@endsection
