@extends('layouts.app')

@section('title', $farm->name)

@section('content')
    <div class="max-w-6xl mx-auto p-6 space-y-6">

        <!-- Flash messages -->
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

        <!-- Main Image -->
        <div class="w-full h-96 overflow-hidden rounded-3xl shadow-lg">
            <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : 'https://via.placeholder.com/800x400' }}"
                alt="{{ $farm->name }}"
                class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
        </div>

        <!-- Farm Info -->
        <div class="bg-white p-6 rounded-3xl shadow-lg space-y-4">
            <h1 class="text-4xl font-extrabold text-green-800">{{ $farm->name }}</h1>
            <p class="text-gray-600"><span class="font-semibold">Location:</span> {{ $farm->location }}</p>

            <p class="text-gray-700 whitespace-pre-line break-all max-w-full ">{{ $farm->description }}</p>


            <div class="flex flex-wrap gap-4 text-gray-700">
                <span><span class="font-semibold">Capacity:</span> {{ $farm->capacity }} persons</span>
                <span><span class="font-semibold">Price per 12h:</span> ${{ $farm->price_per_night }}</span>
                <span><span class="font-semibold">Rating:</span> ⭐ {{ $farm->rating }}</span>
            </div>

            <!-- Gallery Images -->
            @if ($farm->images->count() > 0)
                <div x-data="gallery({
                    images: [
                        @foreach ($farm->images as $image)
                '{{ asset('storage/' . $image->image_url) }}', @endforeach
                    ]
                })">

                    <!-- Grid الصور -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                        @foreach ($farm->images as $index => $image)
                            <div class="overflow-hidden rounded-xl shadow-md">
                                <img src="{{ asset('storage/' . $image->image_url) }}" alt="Farm Image"
                                    @click="open({{ $index }})"
                                    class="w-full h-48 object-cover cursor-pointer transform hover:scale-105 transition duration-500">
                            </div>
                        @endforeach
                    </div>

                    <!-- Lightbox -->
                    <div x-show="isOpen" x-transition
                        class="fixed inset-0 bg-black/80 flex items-center justify-center z-50"
                        @keydown.window.escape="close">
                        <!-- إغلاق -->
                        <button @click="close" class="absolute top-5 right-5 text-white text-4xl">&times;</button>

                        <!-- السابق -->
                        <button @click="prev" class="absolute left-5 text-white text-5xl">‹</button>

                        <!-- الصورة -->
                        <img :src="images[current]" class="max-h-[90vh] max-w-[90vw] rounded-lg shadow-lg">

                        <!-- التالي -->
                        <button @click="next" class="absolute right-5 text-white text-5xl">›</button>
                    </div>

                </div>
            @endif



            <!-- Booking Form -->
            <div class="mt-6">
                <h2 class="text-2xl font-semibold mb-4 text-green-800">Book this farm</h2>
                <form action="{{ route('farms.book', $farm->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Hidden input لحفظ الوقت النهائي -->
                    <input type="hidden" name="start_time" id="start_time">
                    <input type="hidden" name="end_time" id="end_time">

                    <!-- Choose Event Type -->
                    <div>
                        <label class="block mb-1 font-medium">Choose Event Type:</label>
                        <select name="event_type" id="eventType" class="border rounded-lg px-3 py-2 w-full" required>
                            <option value="">None</option>
                            <option value="Birthday">Birthday</option>
                            <option value="Wedding">Wedding</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Date Picker -->
                    <div class="mt-4">
                        <label class="block mb-1 font-medium">Select Date:</label>
                        <input type="date" name="booking_date" id="booking_date"
                            class="border rounded-lg px-3 py-2 w-full" min="{{ now()->toDateString() }}" required>
                    </div>

                    <!-- Available Times -->
                    <div class="mt-4 hidden" id="timeSection">
                        <h2 class="text-xl font-semibold">Available Times</h2>
                        <div id="timeGrid" class="grid grid-cols-4 gap-2 mt-2"></div>
                    </div>

                    <!-- Booking Summary -->
                    <div class="mt-4 hidden" id="bookingSummary">
                        <h2 class="text-xl font-semibold">Booking Summary</h2>
                        <p><strong>Event Type:</strong> <span id="summaryType"></span></p>
                        <p><strong>Date:</strong> <span id="summaryDate"></span></p>
                        <p><strong>Time:</strong> <span id="summaryTime"></span></p>
                    </div>

                    <!-- Submit -->
                    <div class="mt-4">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Book Now</button>
                    </div>
                </form>

                <!-- Booking Data -->
                <script>
                    window.bookingData = {
                        durations: {
                            Wedding: 2,
                            Birthday: 2,
                            Other: 10
                        },
                        bookings: @json(
                            $farm->bookings->map(fn($b) => [
                                    'date' => \Carbon\Carbon::parse($b->start_time)->toDateString(),
                                    'hour' => (int) \Carbon\Carbon::parse($b->start_time)->format('H'),
                                ]))
                    };
                </script>
            </div>


            <!--
                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg hover:bg-green-700 transform hover:scale-105 transition-all duration-300">
                        Book Now
                    </button>
                    </form>
                </div>
            -->
            <!-- Favorite Button -->
            <div class="mt-4">
                @auth
                    @php
                        $isFav = auth()->user()->favorites()->where('farm_id', $farm->id)->exists();
                    @endphp

                    @if ($isFav)
                        <form action="{{ route('favorites.destroy', $farm->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 text-white px-5 py-2 rounded-xl shadow-md hover:bg-red-600 transition transform hover:scale-105">
                                Remove from Favorites
                            </button>
                        </form>
                    @else
                        <form action="{{ route('favorites.store', $farm->id) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit"
                                class="bg-yellow-500 text-white px-5 py-2 rounded-xl shadow-md hover:bg-yellow-600 transition transform hover:scale-105">
                                Add to Favorites
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="bg-yellow-500 text-white px-5 py-2 rounded-xl shadow-md hover:bg-yellow-600 transition transform hover:scale-105">
                        Login to add favorites
                    </a>
                @endauth
            </div>

        </div>
    </div>
@endsection
