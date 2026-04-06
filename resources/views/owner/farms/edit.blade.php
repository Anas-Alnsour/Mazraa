<x-owner-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.farms.index') }}" class="p-2 text-gray-400 hover:bg-gray-100 hover:text-[#020617] rounded-full transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-[#020617] tracking-tight">Edit Farm: <span class="text-[#1d5c42]">{{ $farm->name }}</span></h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Make changes to your property. <span class="text-amber-600 font-bold px-2 py-0.5 bg-amber-50 rounded-md">Note: Saving edits will trigger a new Admin Approval review.</span></p>
            </div>
        </div>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        /* Fix for map z-index overlapping dropdowns */
        .leaflet-container { z-index: 10 !important; }
    </style>

    <div class="pb-24 pt-8">
        <form action="{{ route('owner.farms.update', $farm->id) }}" method="POST" enctype="multipart/form-data" class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="p-4 rounded-3xl bg-red-50 border border-red-200 shadow-sm mb-6 animate-fade-in-up">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h3 class="text-sm font-bold text-red-800">Please fix the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#1d5c42] flex items-center justify-center border border-emerald-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">General Information</h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Farm Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $farm->name) }}" class="w-full rounded-2xl border-gray-200 focus:border-[#1d5c42] focus:ring-[#1d5c42] transition-all px-4 py-3 bg-gray-50 hover:bg-white text-gray-900 font-medium" required>
                        @error('name') <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="capacity" class="block text-sm font-bold text-gray-700 mb-2">Capacity (Max Persons) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $farm->capacity) }}" min="1" class="w-full pl-12 rounded-2xl border-gray-200 focus:border-[#1d5c42] focus:ring-[#1d5c42] transition-all px-4 py-3 bg-gray-50 hover:bg-white text-gray-900 font-medium" required>
                        </div>
                        @error('capacity') <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Location & Interactive Map</h3>
                        <p class="text-[10px] font-bold text-gray-400 mt-0.5 uppercase tracking-widest">Pinpoint your farm for transport drivers</p>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="governorate" class="block text-sm font-bold text-gray-700 mb-2">Governorate <span class="text-red-500">*</span></label>
                        <select id="governorate" name="governorate" class="w-full rounded-2xl border-gray-200 focus:border-[#1d5c42] focus:ring-[#1d5c42] transition-all px-4 py-3 bg-gray-50 hover:bg-white cursor-pointer text-gray-900 font-medium" required>
                            @foreach(['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Mafraq', 'Jerash', 'Ajloun', 'Balqa', 'Madaba', 'Karak', 'Tafilah', 'Ma\'an'] as $gov)
                                <option value="{{ $gov }}" {{ old('governorate', $farm->governorate) == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                            @endforeach
                        </select>
                        @error('governorate') <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-bold text-gray-700 mb-2">Detailed Address <span class="text-red-500">*</span></label>
                        <input type="text" id="location" name="location" value="{{ old('location', $farm->location ?? $farm->location_link) }}" class="w-full rounded-2xl border-gray-200 focus:border-[#1d5c42] focus:ring-[#1d5c42] transition-all px-4 py-3 bg-gray-50 hover:bg-white text-gray-900 font-medium" required>
                        @error('location') <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="location_link" class="block text-sm font-bold text-gray-700 mb-2">Google Maps Link <span class="text-gray-400 text-xs font-medium">(Optional)</span></label>
                        <input type="url" id="location_link" name="location_link" value="{{ old('location_link', $farm->location_link) }}" class="w-full rounded-2xl border-gray-200 focus:border-[#1d5c42] focus:ring-[#1d5c42] transition-all px-4 py-3 bg-gray-50 hover:bg-white text-gray-900 font-medium">
                        @error('location_link') <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 mt-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pinpoint on Map <span class="text-red-500">*</span></label>
                        <p class="text-xs text-gray-500 font-medium mb-3">Click on the map or drag the pin to update the exact coordinates of your farm.</p>

                        <div id="farm-map" class="w-full h-96 rounded-2xl border-2 border-gray-200 shadow-inner overflow-hidden mb-4"></div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="latitude" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Latitude</label>
                                <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $farm->latitude) }}" class="w-full rounded-xl border-gray-200 bg-gray-100 text-gray-600 font-mono text-sm cursor-not-allowed" readonly required>
                                @error('latitude') <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="longitude" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Longitude</label>
                                <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $farm->longitude) }}" class="w-full rounded-xl border-gray-200 bg-gray-100 text-gray-600 font-mono text-sm cursor-not-allowed" readonly required>
                                @error('longitude') <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-[#c2a265] flex items-center justify-center border border-amber-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Pricing Configuration</h3>
                        <p class="text-[10px] font-bold text-gray-400 mt-0.5 uppercase tracking-widest">Base prices in JOD</p>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="price_per_morning_shift" class="block text-sm font-bold text-gray-700 mb-2">Morning Shift <span class="text-[10px] text-gray-400 font-medium">(Daytime)</span> <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-bold sm:text-sm">JOD</span>
                            </div>
                            <input type="number" step="0.01" min="0" id="price_per_morning_shift" name="price_per_morning_shift" value="{{ old('price_per_morning_shift', $farm->price_per_morning_shift) }}" class="w-full pl-14 rounded-2xl border-gray-200 focus:border-[#1d5c42] focus:ring-[#1d5c42] transition-all px-4 py-3 bg-gray-50 hover:bg-white font-black text-gray-900" required>
                        </div>
                        @error('price_per_morning_shift') <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="price_per_evening_shift" class="block text-sm font-bold text-gray-700 mb-2">Evening Shift <span class="text-[10px] text-gray-400 font-medium">(Nighttime)</span> <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-bold sm:text-sm">JOD</span>
                            </div>
                            <input type="number" step="0.01" min="0" id="price_per_evening_shift" name="price_per_evening_shift" value="{{ old('price_per_evening_shift', $farm->price_per_evening_shift) }}" class="w-full pl-14 rounded-2xl border-gray-200 focus:border-[#1d5c42] focus:ring-[#1d5c42] transition-all px-4 py-3 bg-gray-50 hover:bg-white font-black text-gray-900" required>
                        </div>
                        @error('price_per_evening_shift') <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="price_per_full_day" class="block text-sm font-bold text-gray-700 mb-2">Full Day <span class="text-[10px] text-gray-400 font-medium">(24 Hours)</span> <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-bold sm:text-sm">JOD</span>
                            </div>
                            <input type="number" step="0.01" min="0" id="price_per_full_day" name="price_per_full_day" value="{{ old('price_per_full_day', $farm->price_per_full_day) }}" class="w-full pl-14 rounded-2xl border-gray-200 focus:border-[#c2a265] focus:ring-[#c2a265] transition-all px-4 py-3 bg-amber-50/50 hover:bg-amber-50 font-black text-[#c2a265]" required>
                        </div>
                        @error('price_per_full_day') <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Farm Description</h3>
                </div>
                <div class="p-8">
                    <div>
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Detailed Description <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="6" class="w-full rounded-2xl border-gray-200 focus:border-[#1d5c42] focus:ring-[#1d5c42] transition-all px-4 py-3 bg-gray-50 hover:bg-white resize-y text-gray-900 font-medium" required>{{ old('description', $farm->description) }}</textarea>
                        @error('description') <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Media Uploads</h3>
                </div>
                <div class="p-8 space-y-8">

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-4">Main Cover Image <span class="text-[10px] text-gray-400 font-medium tracking-widest uppercase ml-1">(Upload a new one to replace)</span></label>
                        <div class="flex items-center gap-6">
                            @if($farm->main_image)
                                <div class="shrink-0">
                                    <img src="{{ asset('storage/' . $farm->main_image) }}" alt="Cover" class="w-32 h-32 object-cover rounded-3xl shadow-sm border border-gray-100">
                                </div>
                            @endif
                            <input name="main_image" type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-xs file:font-black file:tracking-widest file:uppercase file:bg-emerald-50 file:text-[#1d5c42] hover:file:bg-emerald-100 transition-colors cursor-pointer">
                        </div>
                        @error('main_image') <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="border-t border-gray-100 pt-8">
                        <label class="block text-sm font-bold text-gray-700 mb-4">Manage Current Gallery Images</label>

                        @if($farm->images->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                                @foreach($farm->images as $image)
                                    <div class="relative group rounded-2xl overflow-hidden shadow-sm border border-gray-100 aspect-square">
                                        <img src="{{ asset('storage/' . $image->image_url) }}" alt="Gallery Image" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                            <label class="flex items-center gap-2 text-white text-xs font-black uppercase tracking-widest cursor-pointer bg-red-600/90 px-4 py-2 rounded-xl shadow-sm hover:bg-red-700 transition-colors">
                                                <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="rounded-md text-red-600 focus:ring-red-500 bg-white border-none w-4 h-4 cursor-pointer">
                                                Delete
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-2xl border border-gray-100 p-6 text-center mb-8">
                                <p class="text-sm font-bold text-gray-500">No gallery images uploaded yet.</p>
                            </div>
                        @endif

                        <label class="block text-sm font-bold text-gray-700 mb-4">Add New Gallery Images <span class="text-[10px] text-gray-400 font-medium tracking-widest uppercase ml-1">(Optional)</span></label>
                        <input name="gallery[]" type="file" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-xs file:font-black file:tracking-widest file:uppercase file:bg-emerald-50 file:text-[#1d5c42] hover:file:bg-emerald-100 transition-colors cursor-pointer">
                        @error('gallery') <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                        @error('gallery.*') <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="fixed bottom-0 inset-x-0 sm:static sm:bottom-auto sm:inset-x-auto z-20 bg-white sm:bg-transparent border-t border-gray-200 sm:border-t-0 p-4 sm:p-0 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] sm:shadow-none flex flex-col sm:flex-row items-center justify-end gap-3 sm:gap-4 mt-8">
                <a href="{{ route('owner.farms.index') }}" class="w-full sm:w-auto px-6 py-3.5 bg-white hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition-all border border-gray-200 shadow-sm text-center">
                    Cancel
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 py-3.5 px-10 border border-transparent shadow-lg shadow-[#1d5c42]/20 text-sm font-black tracking-widest uppercase rounded-xl text-white bg-[#1d5c42] hover:bg-[#154230] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1d5c42] transform hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save Changes & Submit
                </button>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var latInput = document.getElementById('latitude');
            var lngInput = document.getElementById('longitude');

            // Check for previously saved DB coordinates or validation error fallbacks
            var initialLat = latInput.value ? parseFloat(latInput.value) : 31.9522; // Default: Amman
            var initialLng = lngInput.value ? parseFloat(lngInput.value) : 35.2332;

            // Initialize Map
            var map = L.map('farm-map').setView([initialLat, initialLng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

            // Add Draggable Marker
            var marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);

            // Update hidden inputs when marker moves
            function updateInputs(lat, lng) {
                latInput.value = lat.toFixed(8);
                lngInput.value = lng.toFixed(8);
            }

            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                updateInputs(position.lat, position.lng);
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });
        });
    </script>
</x-owner-layout>
