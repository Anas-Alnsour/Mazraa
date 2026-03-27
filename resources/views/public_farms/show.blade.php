@extends('layouts.app')

@section('title', $farm->name)

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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

            <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight mb-2">{{ $farm->name }}</h1>
                    <div class="flex items-center text-sm font-bold text-gray-500">
                        <svg class="w-4 h-4 mr-1 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ $farm->location }}
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        @php
                            $isFavorited = auth()->user()->favorites()->where('farm_id', $farm->id)->exists();
                        @endphp
                        <form action="{{ $isFavorited ? route('favorites.destroy', $farm->id) : route('favorites.store', $farm->id) }}" method="POST">
                            @csrf
                            @if($isFavorited) @method('DELETE') @endif
                            <button type="submit" title="{{ $isFavorited ? 'Remove from Favorites' : 'Add to Favorites' }}"
                                class="flex items-center justify-center p-3 rounded-full {{ $isFavorited ? 'bg-red-50 border-red-200 text-red-500 hover:bg-red-100' : 'bg-white border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50' }} transition-all shadow-sm border">
                                <svg class="w-5 h-5" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </form>
                    @endauth

                    <a href="{{ route('explore') }}" class="hidden md:flex items-center text-sm font-bold text-gray-500 hover:text-[#1d5c42] transition-colors bg-white px-4 py-2.5 rounded-full border border-gray-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Explore
                    </a>
                </div>
            </div>

            {{-- Image Gallery --}}
            <div class="bg-white p-2 rounded-[2.5rem] shadow-sm border border-gray-100 mb-12">
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
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 h-[50vh] min-h-[400px] rounded-[2rem] overflow-hidden">
                            @if($farm->main_image || isset($farm->images[0]))
                            <div class="md:col-span-2 row-span-2 relative group cursor-pointer overflow-hidden">
                                <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : asset('storage/' . $farm->images[0]->image_url) }}" @click="open(0)" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Main Image">
                                <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                            </div>
                            @endif

                            @foreach($farm->images->take(4) as $index => $image)
                                <div class="hidden md:block relative group cursor-pointer overflow-hidden">
                                    <img src="{{ asset('storage/' . $image->image_url) }}" @click="open({{ $farm->main_image ? $loop->index + 1 : $loop->index }})" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Gallery Image">
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
                    <div class="w-full h-[400px] overflow-hidden rounded-[2rem] relative border bg-gray-100 flex items-center justify-center">
                       <span class="text-gray-400 font-bold">No images available</span>
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
                                <p class="font-bold text-gray-900">{{ number_format($farm->average_rating, 1) }} / 5.0</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-2xl font-black text-gray-900 mb-4">About this escape</h3>
                        <p class="text-gray-600 leading-relaxed font-medium whitespace-pre-line break-all">
                            {{ $farm->description }}
                        </p>
                    </div>

                    {{-- 📍 Mini-Map Integration --}}
                    @if($farm->latitude && $farm->longitude)
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                            Location
                        </h3>
                        <div id="farm-map" class="w-full h-[300px] rounded-[2rem] border-4 border-white shadow-lg z-0"></div>
                    </div>
                    @endif

                    {{-- ⭐️ Reviews Section --}}
                    <x-reviews-section
                        :reviews="$farm->reviews"
                        :reviewable-id="$farm->id"
                        reviewable-type="farm"
                        :average-rating="$farm->average_rating"
                    />
                </div>

                <div class="lg:w-1/3">
                    <div class="bg-white rounded-[2.5rem] shadow-2xl border border-gray-100 p-8 sticky top-8 z-10">

                        <div class="flex items-end gap-2 border-b border-gray-100 pb-6 mb-6">
                            <span class="text-4xl font-black text-[#1d5c42]">{{ number_format($farm->price_per_night, 0) }} JOD</span>
                            <span class="text-sm font-bold text-gray-400 mb-1">/ Shift</span>
                        </div>

                        {{-- 💡 AlpineJS Form Data for Toggling Modes --}}
                        <form action="{{ route('farms.book', $farm->id) }}" method="POST" id="bookingForm" class="space-y-5" x-data="{ bookingMode: 'single' }">
                            @csrf

                            <input type="hidden" name="booking_mode" x-model="bookingMode">
                            <input type="hidden" name="start_time" id="start_time">
                            <input type="hidden" name="end_time" id="end_time">
                            <input type="hidden" name="requires_transport" id="requires_transport" value="0">
                            <input type="hidden" name="transport_cost" id="transport_cost" value="0">
                            <input type="hidden" name="pickup_lat" id="pickup_lat">
                            <input type="hidden" name="pickup_lng" id="pickup_lng">

                            {{-- 🌟 The Toggle Switch for Single/Multi Day --}}
                            <div class="flex p-1 bg-gray-100 rounded-xl mb-2">
                                <button type="button" @click="bookingMode = 'single'; window.resetBookingForm();"
                                    :class="bookingMode === 'single' ? 'bg-white shadow-sm text-[#1d5c42]' : 'text-gray-500 hover:text-gray-700'"
                                    class="flex-1 py-2 text-xs font-black uppercase tracking-widest rounded-lg transition-all focus:outline-none">
                                    Single Day
                                </button>
                                <button type="button" @click="bookingMode = 'multi'; window.resetBookingForm();"
                                    :class="bookingMode === 'multi' ? 'bg-white shadow-sm text-blue-700' : 'text-gray-500 hover:text-gray-700'"
                                    class="flex-1 py-2 text-xs font-black uppercase tracking-widest rounded-lg transition-all focus:outline-none">
                                    Multiple Days
                                </button>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Event Type</label>
                                <select name="event_type" id="eventType" class="w-full bg-gray-50 border-none rounded-xl py-4 px-4 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-[#1d5c42] transition-all outline-none" required>
                                    <option value="">Select Type...</option>
                                    <option value="Birthday">🎉 Birthday</option>
                                    <option value="Wedding">💍 Wedding</option>
                                    <option value="Other">✨ Other</option>
                                </select>
                            </div>

                            {{-- 🌟 MODE 1: SINGLE DAY UI --}}
                            <div x-show="bookingMode === 'single'" x-transition>
                                <div class="mb-4">
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Select Date</label>
                                    <input type="date" id="booking_date" min="{{ now()->toDateString() }}"
                                        class="w-full bg-gray-50 border-none rounded-xl py-4 px-4 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-[#1d5c42] transition-all outline-none">
                                </div>

                                <div id="shiftsContainer" class="hidden">
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Select Shift</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="button" id="btnMorning" onclick="selectShift('morning')"
                                            class="shift-btn border-2 border-gray-200 bg-white p-3 rounded-xl flex flex-col items-center justify-center hover:border-green-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span class="text-sm font-black text-gray-800">☀️ Morning</span>
                                            <span class="text-[9px] font-bold text-gray-500 mt-1">10AM - 8PM</span>
                                        </button>

                                        <button type="button" id="btnEvening" onclick="selectShift('evening')"
                                            class="shift-btn border-2 border-gray-200 bg-white p-3 rounded-xl flex flex-col items-center justify-center hover:border-green-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span class="text-sm font-black text-gray-800">🌙 Evening</span>
                                            <span class="text-[9px] font-bold text-gray-500 mt-1">10PM - 8AM</span>
                                        </button>

                                        <button type="button" id="btnFullDay" onclick="selectShift('full_day')"
                                            class="shift-btn col-span-2 border-2 border-gray-200 bg-white p-3 rounded-xl flex flex-col items-center justify-center hover:border-green-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span class="text-sm font-black text-gray-800">👑 Full Day (Both Shifts)</span>
                                            <span class="text-[10px] font-bold text-gray-500 mt-1">10:00 AM - 08:00 AM (Next Day)</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- 🌟 MODE 2: MULTIPLE DAYS UI --}}
                            <div x-show="bookingMode === 'multi'" x-transition x-cloak>
                                <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl mb-4">
                                    <p class="text-xs text-blue-800 font-black flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Multi-Day Booking
                                    </p>
                                    <p class="text-[10px] font-bold text-blue-600 leading-relaxed">
                                        Selecting multiple days will automatically reserve the <strong class="text-blue-800">Full Day (Both Shifts)</strong> for the entire selected duration.
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Check-in</label>
                                        <input type="date" id="multi_start_date" min="{{ now()->toDateString() }}"
                                            class="w-full bg-gray-50 border-none rounded-xl py-3 px-3 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Check-out</label>
                                        <input type="date" id="multi_end_date" min="{{ now()->toDateString() }}"
                                            class="w-full bg-gray-50 border-none rounded-xl py-3 px-3 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                                    </div>
                                </div>
                            </div>

                            {{-- Transport Integration Toggle --}}
                            @if($farm->latitude && $farm->longitude)
                            <div class="border-t border-gray-100 pt-4 mt-2">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" id="toggleTransport" class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    <span class="ml-3 text-sm font-black text-gray-800 group-hover:text-green-600 transition-colors">Add Shuttle Transport</span>
                                </label>

                                <div id="transportSection" class="hidden mt-3 space-y-3">
                                    <p class="text-xs text-gray-500 font-medium">Click on the map below to set your pickup location.</p>
                                    <div id="pickup-map" class="w-full h-[150px] rounded-xl border border-gray-200 z-0 relative"></div>
                                    <p id="transportCalc" class="text-xs font-bold text-blue-600 hidden animate-fade-in">Distance: <span id="distVal"></span> km | Estimated Cost: <span id="costVal"></span> JOD</p>
                                </div>
                            </div>
                            @endif

                            {{-- Comprehensive Invoice --}}
                            <div id="bookingSummary" class="hidden bg-gray-50 p-5 rounded-2xl border border-gray-100">
                                <h3 class="font-black text-gray-800 text-sm uppercase mb-4 tracking-widest border-b border-gray-200 pb-2">Comprehensive Invoice</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-500" id="farmRentalLabel">Farm Rental (1 Shift)</span>
                                        <span class="font-bold text-gray-900" id="farmRentalDisplay">0.00 JOD</span>
                                    </div>
                                    <div id="invoiceTransport" class="flex justify-between hidden">
                                        <span class="font-medium text-gray-500">Transport Fee</span>
                                        <span class="font-bold text-gray-900" id="invoiceTransportCost">0.00 JOD</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-500">Platform Tax (16%)</span>
                                        <span class="font-bold text-gray-900" id="invoiceTax">0.00 JOD</span>
                                    </div>
                                    <div class="flex justify-between pt-3 border-t border-gray-200 mt-2">
                                        <span class="font-black text-gray-800">Total Due</span>
                                        <span class="font-black text-[#1d5c42] text-lg" id="invoiceTotal">0.00 JOD</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="confirmBookingBtn" disabled
                                class="w-full bg-[#183126] text-white font-black py-4 rounded-xl shadow-lg hover:bg-[#10231b] hover:shadow-xl transition-all transform active:scale-95 disabled:bg-gray-300 disabled:cursor-not-allowed disabled:transform-none uppercase tracking-widest text-sm mt-4">
                                Confirm Booking
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- Leaflet JS & Logic --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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
        const farmPrice = <?php echo floatval($farm->price_per_night ?? 0); ?>;
        const farmLat = <?php echo $farm->latitude ? '"' . $farm->latitude . '"' : 'null'; ?>;
        const farmLng = <?php echo $farm->longitude ? '"' . $farm->longitude . '"' : 'null'; ?>;

        let transportCost = 0;
        let selectedShift = false;
        let currentShiftType = '';
        let multiDaysCount = 1;

        // 💡 Function to reset the form when toggling modes
        window.resetBookingForm = function() {
            selectedShift = false;
            currentShiftType = '';
            document.getElementById('confirmBookingBtn').disabled = true;
            document.getElementById('bookingSummary').classList.add('hidden');

            // Clear inputs
            document.getElementById('booking_date').value = '';
            document.getElementById('multi_start_date').value = '';
            document.getElementById('multi_end_date').value = '';

            document.getElementById('shiftsContainer').classList.add('hidden');
            document.querySelectorAll('.shift-btn').forEach(btn => {
                btn.classList.remove('bg-green-50', 'border-green-500');
                btn.classList.add('border-gray-200', 'bg-white');
            });
        };

        function updateInvoice() {
            if(!selectedShift) return;

            let base = 0;
            let label = '';

            if (currentShiftType === 'multi_day') {
                base = (farmPrice * 2) * multiDaysCount;
                label = `Farm Rental (${multiDaysCount} Days)`;
            } else if (currentShiftType === 'full_day') {
                base = farmPrice * 2;
                label = 'Farm Rental (Full Day)';
            } else {
                base = farmPrice;
                label = 'Farm Rental (1 Shift)';
            }

            let totalBeforeTax = base + transportCost;
            let tax = totalBeforeTax * 0.16; // 16% Tax
            let grandTotal = totalBeforeTax + tax;

            document.getElementById('farmRentalLabel').innerText = label;
            document.getElementById('farmRentalDisplay').innerText = base.toFixed(2) + ' JOD';
            document.getElementById('invoiceTax').innerText = tax.toFixed(2) + ' JOD';
            document.getElementById('invoiceTotal').innerText = grandTotal.toFixed(2) + ' JOD';

            document.getElementById('bookingSummary').classList.remove('hidden');
        }

        const existingBookings = <?php echo json_encode($bookingsData); ?>;
        const blockedDates = <?php echo json_encode($blockedData); ?>;

        /* --- SINGLE DAY LOGIC --- */
        const dateInput = document.getElementById('booking_date');
        const shiftsContainer = document.getElementById('shiftsContainer');
        const btnMorning = document.getElementById('btnMorning');
        const btnEvening = document.getElementById('btnEvening');
        const btnFullDay = document.getElementById('btnFullDay');
        const confirmBookingBtn = document.getElementById('confirmBookingBtn');

        dateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            if (!selectedDate) return;

            shiftsContainer.classList.remove('hidden');
            checkAvailability(selectedDate);

            selectedShift = false;
            currentShiftType = '';
            confirmBookingBtn.disabled = true;
            document.querySelectorAll('.shift-btn').forEach(btn => {
                btn.classList.remove('bg-green-50', 'border-green-500');
                btn.classList.add('border-gray-200', 'bg-white');
            });
        });

        function checkAvailability(date) {
            btnMorning.disabled = false; btnEvening.disabled = false; btnFullDay.disabled = false;
            btnMorning.classList.remove('bg-gray-100', 'opacity-50', 'cursor-not-allowed');
            btnEvening.classList.remove('bg-gray-100', 'opacity-50', 'cursor-not-allowed');
            btnFullDay.classList.remove('bg-gray-100', 'opacity-50', 'cursor-not-allowed');

            let isMorningBooked = false;
            let isEveningBooked = false;

            existingBookings.forEach(booking => {
                if (booking.date === date) {
                    if (booking.hour === 10) isMorningBooked = true;
                    if (booking.hour === 22) isEveningBooked = true;
                }
            });

            blockedDates.forEach(block => {
                if (block.date === date) {
                    if (block.shift === 'morning' || block.shift === 'full_day') isMorningBooked = true;
                    if (block.shift === 'evening' || block.shift === 'full_day') isEveningBooked = true;
                }
            });

            if (isMorningBooked) {
                btnMorning.disabled = true;
                btnMorning.classList.add('bg-gray-100', 'opacity-50', 'cursor-not-allowed');
                btnFullDay.disabled = true;
                btnFullDay.classList.add('bg-gray-100', 'opacity-50', 'cursor-not-allowed');
            }
            if (isEveningBooked) {
                btnEvening.disabled = true;
                btnEvening.classList.add('bg-gray-100', 'opacity-50', 'cursor-not-allowed');
                btnFullDay.disabled = true;
                btnFullDay.classList.add('bg-gray-100', 'opacity-50', 'cursor-not-allowed');
            }
        }

        function selectShift(type) {
            const dateVal = dateInput.value;
            if (!dateVal) {
                alert('Please select a date first.');
                return;
            }

            document.querySelectorAll('.shift-btn').forEach(btn => {
                btn.classList.remove('bg-green-50', 'border-green-500');
                btn.classList.add('border-gray-200', 'bg-white');
            });

            currentShiftType = type;

            if (type === 'morning') {
                btnMorning.classList.add('bg-green-50', 'border-green-500');
                document.getElementById('start_time').value = dateVal + ' 10:00:00';
                document.getElementById('end_time').value = dateVal + ' 20:00:00';
            } else if (type === 'evening') {
                btnEvening.classList.add('bg-green-50', 'border-green-500');
                let tomorrow = new Date(dateVal);
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('start_time').value = dateVal + ' 22:00:00';
                document.getElementById('end_time').value = tomorrow.toISOString().split('T')[0] + ' 08:00:00';
            } else if (type === 'full_day') {
                btnFullDay.classList.add('bg-green-50', 'border-green-500');
                let tomorrow = new Date(dateVal);
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('start_time').value = dateVal + ' 10:00:00';
                document.getElementById('end_time').value = tomorrow.toISOString().split('T')[0] + ' 08:00:00';
            }

            selectedShift = true;
            confirmBookingBtn.disabled = false;
            updateInvoice();
        }

        /* --- MULTI DAY LOGIC --- */
        const multiStartInput = document.getElementById('multi_start_date');
        const multiEndInput = document.getElementById('multi_end_date');

        function checkMultiDay() {
            if(multiStartInput.value && multiEndInput.value) {
                let sDate = new Date(multiStartInput.value);
                let eDate = new Date(multiEndInput.value);

                if(eDate <= sDate) {
                    alert('Check-out date must be after Check-in date.');
                    confirmBookingBtn.disabled = true;
                    selectedShift = false;
                    document.getElementById('bookingSummary').classList.add('hidden');
                    return;
                }

                // Check if any date in range is blocked (Frontend validation)
                let isBlocked = false;
                let currentDate = new Date(sDate);
                while(currentDate < eDate) {
                    let checkDateStr = currentDate.toISOString().split('T')[0];

                    // Check bookings
                    existingBookings.forEach(booking => {
                        if (booking.date === checkDateStr) isBlocked = true;
                    });
                    // Check blocked dates
                    blockedDates.forEach(block => {
                        if (block.date === checkDateStr) isBlocked = true;
                    });

                    currentDate.setDate(currentDate.getDate() + 1);
                }

                if(isBlocked) {
                    alert('One or more days in your selected range are already booked. Please choose different dates.');
                    confirmBookingBtn.disabled = true;
                    selectedShift = false;
                    document.getElementById('bookingSummary').classList.add('hidden');
                    return;
                }

                // Calculation
                let diffTime = Math.abs(eDate - sDate);
                multiDaysCount = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                document.getElementById('start_time').value = multiStartInput.value + ' 10:00:00';
                document.getElementById('end_time').value = multiEndInput.value + ' 08:00:00';

                selectedShift = true;
                currentShiftType = 'multi_day';
                confirmBookingBtn.disabled = false;
                updateInvoice();
            }
        }

        multiStartInput.addEventListener('change', checkMultiDay);
        multiEndInput.addEventListener('change', checkMultiDay);

        /* --- TRANSPORT LOGIC --- */
        document.addEventListener('DOMContentLoaded', function () {
            if(farmLat && farmLat !== 'null' && farmLng && farmLng !== 'null') {
                var map = L.map('farm-map', {scrollWheelZoom: false}).setView([farmLat, farmLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                const farmName = <?php echo json_encode($farm->name); ?>;
                L.marker([farmLat, farmLng]).addTo(map).bindPopup("<b>" + farmName + "</b>").openPopup();

                const toggle = document.getElementById('toggleTransport');
                const section = document.getElementById('transportSection');
                const invRow = document.getElementById('invoiceTransport');
                let pickupMap, pickupMarker;

                if(toggle && section) {
                    toggle.addEventListener('change', function() {
                        if(this.checked) {
                            section.classList.remove('hidden');
                            document.getElementById('requires_transport').value = "1";
                            if(invRow) invRow.classList.remove('hidden');

                            if(!pickupMap) {
                                pickupMap = L.map('pickup-map').setView([31.9522, 35.9334], 8);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(pickupMap);

                                pickupMap.on('click', function(e) {
                                    if(pickupMarker) pickupMap.removeLayer(pickupMarker);
                                    pickupMarker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(pickupMap);

                                    document.getElementById('pickup_lat').value = e.latlng.lat;
                                    document.getElementById('pickup_lng').value = e.latlng.lng;
                                    calculateTransportCost(e.latlng.lat, e.latlng.lng);
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

                function calculateTransportCost(pLat, pLng) {
                    fetch(`https://router.project-osrm.org/route/v1/driving/${pLng},${pLat};${farmLng},${farmLat}?overview=false`)
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
            }
        });
    </script>
    @endpush
@endsection
