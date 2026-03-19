<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-900 tracking-tight tracking-tighter">Farm Builder</h2>
                <p class="text-sm text-green-600 font-bold mt-1 uppercase tracking-widest">Interactive Dashboard v2.5</p>
            </div>
            <a href="{{ route('owner.farms.index') }}" class="group bg-white hover:bg-gray-50 text-gray-700 font-bold py-2.5 px-6 rounded-2xl border border-gray-200 shadow-sm transition-all flex items-center gap-2">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        [x-cloak] { display: none !important; }
        #map { height: 500px; width: 100%; border-radius: 2rem; z-index: 1 !important; }
        .leaflet-container { z-index: 1 !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
    </style>

    <div class="py-10" x-data="farmGallery()">
        <div class="max-w-5xl mx-auto">
            <form action="{{ route('owner.farms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="farmForm">
                @csrf

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-10">
                    <div class="flex items-center gap-3 mb-8 border-b pb-4">
                        <div class="p-2 bg-green-100 rounded-xl text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">Farm Photos Gallery</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <label class="block text-sm font-black text-gray-700 uppercase">Main Cover Image *</label>
                            <div class="relative group h-72 rounded-[2.5rem] overflow-hidden border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center transition-all">
                                <template x-if="mainPreview">
                                    <div class="relative w-full h-full">
                                        <img :src="mainPreview" class="w-full h-full object-cover cursor-zoom-in" @click="showFull(mainPreview)">
                                        <button type="button" @click="mainPreview = null; $refs.mainInput.value = ''" class="absolute top-4 right-4 bg-red-500 text-white p-2 rounded-full shadow-lg hover:bg-red-600 transition-colors z-30">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>
                                <div x-show="!mainPreview" class="text-center">
                                    <div class="mx-auto w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-4">
                                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </div>
                                    <p class="text-[10px] font-black text-gray-400 tracking-widest uppercase">Upload Cover</p>
                                </div>
                                <input type="file" name="image" x-ref="mainInput" required class="absolute inset-0 opacity-0 cursor-pointer z-20" @change="updateMain">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-black text-gray-700 uppercase">Additional Photos (<span x-text="galleryPreviews.length"></span>)</label>
                            <div class="grid grid-cols-2 gap-4 h-72 overflow-y-auto custom-scrollbar pr-2">
                                <template x-for="(item, index) in galleryPreviews" :key="index">
                                    <div class="relative h-32 rounded-3xl overflow-hidden group shadow-sm border border-gray-100">
                                        <img :src="item.src" class="w-full h-full object-cover cursor-zoom-in" @click="showFull(item.src)">
                                        <button type="button" @click="removeGallery(index)" class="absolute top-2 right-2 bg-black/50 backdrop-blur-md text-white p-1.5 rounded-full hover:bg-red-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>
                                <label class="h-32 rounded-3xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center cursor-pointer hover:bg-green-50 hover:border-green-400 transition-all">
                                    <input type="file" name="gallery[]" multiple class="hidden" @change="updateGallery">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-10">
                    <div class="flex items-center gap-3 mb-8 border-b pb-4">
                        <div class="p-2 bg-amber-100 rounded-xl text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">Location & Mapping</h3>
                    </div>

                    <div class="space-y-6">
                        <div class="relative group">
                            <label for="location" class="block text-sm font-bold text-gray-700 mb-2 px-1">Farm Address / Area Search</label>
                            <input type="text" name="location" id="location" required
                                class="w-full rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-green-500/10 focus:border-green-500 py-5 px-8 transition-all font-bold text-gray-800"
                                placeholder="Search for a city or district...">
                            <div id="search-loading" class="absolute right-6 top-14 hidden">
                                <svg class="animate-spin h-5 w-5 text-green-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>

                        <div id="map"></div>

                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-10">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-black text-gray-700 mb-2">Farm Name *</label>
                            <input type="text" name="name" required class="w-full rounded-2xl border-gray-100 bg-gray-50 py-5 px-8 focus:bg-white transition-all font-bold" placeholder="The Golden Farm">
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-700 mb-2">Price Per Night (JOD)</label>
                            <input type="number" name="price_per_night" required class="w-full rounded-2xl border-gray-100 bg-gray-50 py-5 px-8 focus:bg-white transition-all font-bold" placeholder="150">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-black text-gray-700 mb-2">Brief Description</label>
                            <textarea name="description" rows="4" required class="w-full rounded-[2rem] border-gray-100 bg-gray-50 p-8 focus:bg-white transition-all font-medium" placeholder="Describe your rooms, pool, and vibes..."></textarea>
                        </div>
                        <div class="md:col-span-1">
                             <label class="block text-sm font-black text-gray-700 mb-2">Capacity</label>
                             <input type="number" name="capacity" required class="w-full rounded-2xl border-gray-100 bg-gray-50 py-5 px-8 focus:bg-white transition-all font-bold" placeholder="Max Guests">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-between bg-gray-900 rounded-[3rem] p-10 text-white gap-8 shadow-2xl">
                    <div class="text-center md:text-left">
                        <p class="font-black text-2xl leading-tight tracking-tighter uppercase">Launch Listing</p>
                        <p class="text-gray-400 text-sm mt-1">Review your location and gallery before publishing.</p>
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-green-500 hover:bg-green-400 text-white font-black py-6 px-20 rounded-2xl shadow-[0_10px_40px_rgba(34,197,94,0.3)] transition-all transform hover:-translate-y-1 active:scale-95 text-lg">
                        SAVE FARM
                    </button>
                </div>
            </form>
        </div>

        <div x-show="lightboxOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-10 overflow-hidden" @click.self="lightboxOpen = false">
            <button @click="lightboxOpen = false" class="absolute top-10 right-10 text-white hover:text-green-500 transition-colors">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <img :src="lightboxSrc" class="max-w-full max-h-full rounded-2xl shadow-2xl object-contain animate-fade-in">
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function farmGallery() {
            return {
                mainPreview: null,
                galleryPreviews: [],
                lightboxOpen: false,
                lightboxSrc: '',
                updateMain(e) {
                    const file = e.target.files[0];
                    if (file) this.mainPreview = URL.createObjectURL(file);
                },
                updateGallery(e) {
                    const files = Array.from(e.target.files);
                    files.forEach(file => {
                        this.galleryPreviews.push({
                            file: file,
                            src: URL.createObjectURL(file)
                        });
                    });
                },
                removeGallery(index) {
                    this.galleryPreviews.splice(index, 1);
                },
                showFull(src) {
                    this.lightboxSrc = src;
                    this.lightboxOpen = true;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // حل مشكلة تحميل الخريطة في الحاوية المخفية
            setTimeout(() => {
                const map = L.map('map', { scrollWheelZoom: false }).setView([31.9454, 35.9284], 10);
                L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png').addTo(map);

                let marker;
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                const locInput = document.getElementById('location');
                const loading = document.getElementById('search-loading');

                map.on('click', async function(e) {
                    const { lat, lng } = e.latlng;
                    updateMarker(lat, lng);

                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                        const data = await response.json();
                        if(data.display_name) locInput.value = data.display_name;
                    } catch (err) { console.error("Reverse failed"); }
                });

                let timer;
                locInput.addEventListener('input', function() {
                    clearTimeout(timer);
                    loading.classList.remove('hidden');
                    timer = setTimeout(async () => {
                        const query = locInput.value;
                        if(query.length < 3) { loading.classList.add('hidden'); return; }

                        try {
                            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=1`);
                            const data = await response.json();
                            if(data.length > 0) {
                                const { lat, lon } = data[0];
                                map.setView([lat, lon], 14);
                                updateMarker(parseFloat(lat), parseFloat(lon));
                            }
                        } catch (err) { console.error("Search failed"); }
                        loading.classList.add('hidden');
                    }, 800);
                });

                function updateMarker(lat, lng) {
                    if (marker) marker.setLatLng([lat, lng]);
                    else marker = L.marker([lat, lng]).addTo(map);
                    latInput.value = lat.toFixed(6);
                    lngInput.value = lng.toFixed(6);
                    map.panTo([lat, lng]);
                }
            }, 500);
        });
    </script>
</x-dashboard-layout>
