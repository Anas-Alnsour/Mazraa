<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-900 tracking-tight tracking-tighter">Edit Farm</h2>
                <p class="text-sm text-green-600 font-bold mt-1 uppercase tracking-widest">Editing: {{ $farm->name }}</p>
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

    <div class="py-10" x-data="farmGalleryHandler()">
        <div class="max-w-5xl mx-auto">
            <form action="{{ route('owner.farms.update', $farm->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="farmForm">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-10">
                    <div class="flex items-center gap-3 mb-8 border-b pb-4">
                        <div class="p-2 bg-green-100 rounded-xl text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">Media Management</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <label class="block text-sm font-black text-gray-700 uppercase">Cover Image (Main)</label>
                            <div class="relative group h-72 rounded-[2.5rem] overflow-hidden border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center transition-all">
                                <template x-if="mainPreview">
                                    <div class="relative w-full h-full">
                                        <img :src="mainPreview" class="w-full h-full object-cover cursor-zoom-in" @click="showFull(mainPreview)">
                                        <button type="button" @click="mainPreview = null; $refs.mainInput.value = ''" class="absolute top-4 right-4 bg-red-500 text-white p-2 rounded-full shadow-lg hover:bg-red-600 transition-colors z-30">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>

                                <div x-show="!mainPreview" class="w-full h-full relative">
                                    @if($farm->main_image)
                                        <img src="{{ asset('storage/' . $farm->main_image) }}" class="w-full h-full object-cover grayscale-[0.3] group-hover:grayscale-0 transition-all">
                                        <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <p class="text-white font-bold bg-green-600 px-4 py-2 rounded-full text-xs">Click to Change Cover</p>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            <p class="text-[10px] font-black text-gray-400 mt-2">No Cover Photo</p>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" name="image" x-ref="mainInput" class="absolute inset-0 opacity-0 cursor-pointer z-20" @change="updateMain">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-black text-gray-700 uppercase">Gallery (Photos: <span x-text="galleryPreviews.length + {{ $farm->images->count() }} - deleteIds.length"></span>)</label>
                            <div class="grid grid-cols-2 gap-4 h-72 overflow-y-auto custom-scrollbar pr-2 p-4 bg-gray-50 rounded-[2.5rem] border border-gray-100">

                                @foreach($farm->images as $img)
                                    <div class="relative h-28 rounded-2xl overflow-hidden shadow-sm group border border-gray-200" x-show="!deleteIds.includes({{ $img->id }})">
                                        <img src="{{ asset('storage/' . $img->image_url) }}" class="w-full h-full object-cover cursor-zoom-in" @click="showFull('{{ asset('storage/' . $img->image_url) }}')">
                                        <button type="button" @click="deleteIds.push({{ $img->id }})" class="absolute top-2 right-2 bg-red-500 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-all shadow-md">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                @endforeach

                                <template x-for="(item, index) in galleryPreviews" :key="index">
                                    <div class="relative h-28 rounded-2xl overflow-hidden group shadow-md border-2 border-green-400">
                                        <img :src="item.src" class="w-full h-full object-cover cursor-zoom-in" @click="showFull(item.src)">
                                        <div class="absolute top-2 left-2 bg-green-600 text-[8px] text-white px-2 py-0.5 rounded-full font-black uppercase">New</div>
                                        <button type="button" @click="removeGallery(index)" class="absolute top-2 right-2 bg-black/50 text-white p-1.5 rounded-full hover:bg-red-500 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>

                                <label class="h-28 rounded-2xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center cursor-pointer hover:bg-white hover:border-green-400 transition-all">
                                    <input type="file" name="gallery[]" multiple class="hidden" @change="updateGallery">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <template x-for="id in deleteIds">
                    <input type="hidden" name="delete_images[]" :value="id">
                </template>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-10">
                    <div class="flex items-center gap-3 mb-8 border-b pb-4">
                        <div class="p-2 bg-amber-100 rounded-xl text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">Location & Mapping</h3>
                    </div>

                    <div class="space-y-6">
                        <div class="relative group">
                            <label for="location" class="block text-sm font-bold text-gray-700 mb-2 px-1">Farm Address / Search</label>
                            <input type="text" name="location" id="location" value="{{ $farm->location }}" required
                                class="w-full rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-green-500/10 focus:border-green-500 py-5 px-8 transition-all font-bold text-gray-800">
                            <div id="search-loading" class="absolute right-6 top-14 hidden">
                                <svg class="animate-spin h-5 w-5 text-green-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>

                        <div id="map"></div>
                        <input type="hidden" name="latitude" id="latitude" value="{{ $farm->latitude }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ $farm->longitude }}">
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-10">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-black text-gray-700 mb-2">Farm Name *</label>
                            <input type="text" name="name" value="{{ $farm->name }}" required class="w-full rounded-2xl border-gray-100 bg-gray-50 py-5 px-8 focus:bg-white transition-all font-bold">
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-700 mb-2">Price Per Night (JOD)</label>
                            <input type="number" name="price_per_night" value="{{ $farm->price_per_night }}" required class="w-full rounded-2xl border-gray-100 bg-gray-50 py-5 px-8 focus:bg-white transition-all font-bold">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-black text-gray-700 mb-2">Brief Description</label>
                            <textarea name="description" rows="5" required class="w-full rounded-[2rem] border-gray-100 bg-gray-50 p-8 focus:bg-white transition-all font-medium">{{ $farm->description }}</textarea>
                        </div>
                        <div class="md:col-span-1">
                             <label class="block text-sm font-black text-gray-700 mb-2">Capacity</label>
                             <input type="number" name="capacity" value="{{ $farm->capacity }}" required class="w-full rounded-2xl border-gray-100 bg-gray-50 py-5 px-8 focus:bg-white transition-all font-bold">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-between bg-gray-900 rounded-[3rem] p-10 text-white gap-8 shadow-2xl">
                    <div class="text-center md:text-left">
                        <p class="font-black text-2xl leading-tight tracking-tighter uppercase">Confirm Changes</p>
                        <p class="text-gray-400 text-sm mt-1 uppercase font-bold tracking-widest">Status: <span class="text-amber-400">{{ $farm->status }}</span></p>
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-green-500 hover:bg-green-400 text-white font-black py-6 px-20 rounded-2xl shadow-[0_10px_40px_rgba(34,197,94,0.3)] transition-all transform hover:-translate-y-1 text-lg">
                        UPDATE FARM
                    </button>
                </div>
            </form>
        </div>

        <div x-show="lightboxOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-10 overflow-hidden" @click.self="lightboxOpen = false">
            <button @click="lightboxOpen = false" class="absolute top-10 right-10 text-white hover:text-green-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <img :src="lightboxSrc" class="max-w-full max-h-full rounded-2xl shadow-2xl object-contain animate-fade-in">
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function farmGalleryHandler() {
            return {
                mainPreview: null,
                galleryPreviews: [],
                deleteIds: [],
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
            const oldLat = {{ $farm->latitude ?? 31.9454 }};
            const oldLng = {{ $farm->longitude ?? 35.9284 }};

            setTimeout(() => {
                const map = L.map('map', { scrollWheelZoom: false }).setView([oldLat, oldLng], 14);
                L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png').addTo(map);

                let marker = L.marker([oldLat, oldLng]).addTo(map);

                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                const locInput = document.getElementById('location');
                const loading = document.getElementById('search-loading');

                map.on('click', async function(e) {
                    const { lat, lng } = e.latlng;
                    if (marker) marker.setLatLng([lat, lng]);
                    else marker = L.marker([lat, lng]).addTo(map);

                    latInput.value = lat.toFixed(6);
                    lngInput.value = lng.toFixed(6);

                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                        const data = await response.json();
                        if(data.display_name) locInput.value = data.display_name;
                    } catch (err) {}
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
                                if (marker) marker.setLatLng([lat, lon]);
                                latInput.value = parseFloat(lat).toFixed(6);
                                lngInput.value = parseFloat(lon).toFixed(6);
                            }
                        } catch (err) {}
                        loading.classList.add('hidden');
                    }, 800);
                });
            }, 500);
        });
    </script>
</x-dashboard-layout>
