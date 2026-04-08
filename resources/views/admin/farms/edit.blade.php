@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-[fade-in_0.8s_ease-out]">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-800/50 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter mb-2">Edit <span class="text-emerald-400">Farm</span></h1>
            <p class="text-slate-400 font-medium">Updating: <span class="text-white">{{ $farm->name }}</span> (ID: #{{ $farm->id }})</p>
        </div>
        <a href="{{ route('admin.farms.index') }}" class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-bold rounded-xl transition-all border border-slate-600 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to List
        </a>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-container { z-index: 1 !important; background: #0f172a; }
        .form-input { @apply w-full bg-slate-900/50 border-slate-700 rounded-2xl px-5 py-4 text-white placeholder-slate-600 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all shadow-inner; }
    </style>

    <form action="{{ route('admin.farms.update', $farm->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 pb-20">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Details --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- General Info --}}
                <div class="bg-slate-800/30 p-8 rounded-[2.5rem] border border-slate-700/50 backdrop-blur-md">
                    <h3 class="text-xl font-black text-white mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 bg-emerald-500/10 text-emerald-400 rounded-lg flex items-center justify-center text-sm">01</span>
                        General Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Farm Name</label>
                            <input type="text" name="name" value="{{ old('name', $farm->name) }}" class="form-input" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Max Capacity</label>
                            <input type="number" name="capacity" value="{{ old('capacity', $farm->capacity) }}" class="form-input" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Rating</label>
                            <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', $farm->rating) }}" class="form-input" required>
                        </div>
                    </div>
                </div>

                {{-- Location & Map --}}
                <div class="bg-slate-800/30 p-8 rounded-[2.5rem] border border-slate-700/50 backdrop-blur-md">
                    <h3 class="text-xl font-black text-white mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 bg-blue-500/10 text-blue-400 rounded-lg flex items-center justify-center text-sm">02</span>
                        Location & Geolocation
                    </h3>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Governorate</label>
                                <select name="governorate" class="form-input appearance-none cursor-pointer" required>
                                    @foreach(['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Mafraq', 'Jerash', 'Ajloun', 'Balqa', 'Madaba', 'Karak', 'Tafilah', 'Ma\'an'] as $gov)
                                        <option value="{{ $gov }}" {{ old('governorate', $farm->governorate) == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Specific Address</label>
                                <input type="text" name="location" value="{{ old('location', $farm->location) }}" class="form-input" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Google Maps Link</label>
                            <input type="url" name="location_link" value="{{ old('location_link', $farm->location_link) }}" class="form-input">
                        </div>

                        <div class="space-y-4">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1 px-1">Interactive Map Pin</label>
                            <div id="admin-farm-map" class="h-80 w-full rounded-2xl border border-slate-700 shadow-inner"></div>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="latitude" id="lat" value="{{ old('latitude', $farm->latitude ?? '31.9522') }}" readonly class="form-input bg-slate-950/50 text-slate-500 cursor-not-allowed text-xs font-mono">
                                <input type="text" name="longitude" id="lng" value="{{ old('longitude', $farm->longitude ?? '35.2332') }}" readonly class="form-input bg-slate-950/50 text-slate-500 cursor-not-allowed text-xs font-mono">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="bg-slate-800/30 p-8 rounded-[2.5rem] border border-slate-700/50 backdrop-blur-md">
                    <h3 class="text-xl font-black text-white mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-500/10 text-amber-400 rounded-lg flex items-center justify-center text-sm">03</span>
                        Pricing Configuration (JOD)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-slate-900/50 rounded-2xl border border-slate-700/30">
                            <label class="block text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-2">Base Price (Display)</label>
                            <input type="number" step="0.01" name="price_per_night" value="{{ old('price_per_night', $farm->price_per_night) }}" class="form-input" required>
                        </div>
                        <div class="p-4 bg-slate-900/50 rounded-2xl border border-slate-700/30">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Morning Shift</label>
                            <input type="number" step="0.01" name="price_per_morning_shift" value="{{ old('price_per_morning_shift', $farm->price_per_morning_shift) }}" class="form-input" required>
                        </div>
                        <div class="p-4 bg-slate-900/50 rounded-2xl border border-slate-700/30">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Evening Shift</label>
                            <input type="number" step="0.01" name="price_per_evening_shift" value="{{ old('price_per_evening_shift', $farm->price_per_evening_shift) }}" class="form-input" required>
                        </div>
                        <div class="p-4 bg-emerald-500/5 rounded-2xl border border-emerald-500/20">
                            <label class="block text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-2">Full Day (24h)</label>
                            <input type="number" step="0.01" name="price_per_full_day" value="{{ old('price_per_full_day', $farm->price_per_full_day) }}" class="form-input border-emerald-500/30" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar (Images & Status) --}}
            <div class="space-y-8">
                {{-- Status Card --}}
                <div class="bg-slate-800/30 p-8 rounded-[2.5rem] border border-slate-700/50 backdrop-blur-md">
                    <h3 class="text-lg font-black text-white mb-6">Approval Status</h3>
                    <div class="space-y-4">
                        <select name="status" class="form-input bg-emerald-500/5 border-emerald-500/20 text-emerald-400 font-bold uppercase tracking-widest text-xs" required>
                            <option value="approved" {{ old('status', $farm->status) == 'approved' ? 'selected' : '' }}>Approved (Visible)</option>
                            <option value="pending" {{ old('status', $farm->status) == 'pending' ? 'selected' : '' }}>Pending Audit</option>
                            <option value="rejected" {{ old('status', $farm->status) == 'rejected' ? 'selected' : '' }}>Rejected / Hidden</option>
                        </select>
                    </div>
                </div>

                {{-- Images --}}
                <div class="bg-slate-800/30 p-8 rounded-[2.5rem] border border-slate-700/50 backdrop-blur-md">
                    <h3 class="text-lg font-black text-white mb-6">Media Gallery</h3>
                    <div class="space-y-8">
                        {{-- Main Image --}}
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Main Cover</label>
                            <div class="relative h-44 rounded-2xl overflow-hidden group shadow-xl border border-slate-700">
                                <img src="{{ $farm->main_image ? asset('storage/'.$farm->main_image) : 'https://via.placeholder.com/400x300' }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center p-4">
                                    <input type="file" name="main_image" class="absolute inset-0 opacity-0 cursor-pointer">
                                    <span class="text-[10px] font-black text-white uppercase tracking-[0.2em] border border-white/30 px-4 py-2 rounded-lg">Replace Cover</span>
                                </div>
                            </div>
                        </div>

                        {{-- Gallery --}}
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Gallery Items</label>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($farm->images as $img)
                                    <div class="relative h-20 rounded-xl overflow-hidden group border border-slate-700">
                                        <img src="{{ asset('storage/'.$img->image_url) }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-rose-600/60 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                            <input type="checkbox" name="remove_gallery_images[]" value="{{ $img->id }}" class="w-5 h-5 rounded border-none text-rose-600 bg-white ring-0 focus:ring-0 cursor-pointer">
                                        </div>
                                    </div>
                                @endforeach
                                {{-- Add Button --}}
                                <div class="relative h-20 rounded-xl border-2 border-dashed border-slate-700 flex items-center justify-center hover:border-emerald-500/50 transition-all cursor-pointer">
                                    <input type="file" name="images[]" multiple class="absolute inset-0 opacity-0 cursor-pointer">
                                    <svg class="w-6 h-6 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                </div>
                            </div>
                            <p class="text-[8px] text-slate-500 font-bold text-center uppercase">Hover to select for deletion</p>
                        </div>
                    </div>
                </div>

                {{-- Action --}}
                <button type="submit" class="w-full py-6 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-[2rem] shadow-2xl shadow-emerald-900/40 transition-all flex items-center justify-center gap-3 transform active:scale-95 group uppercase tracking-widest text-sm">
                    Update Listing
                    <svg class="w-5 h-5 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                </button>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-slate-800/30 p-8 rounded-[2.5rem] border border-slate-700/50 backdrop-blur-md">
            <h3 class="text-xl font-black text-white mb-6">Detailed Description</h3>
            <textarea name="description" rows="6" class="form-input resize-none" required>{{ old('description', $farm->description) }}</textarea>
        </div>
    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var initialLat = {{ old('latitude', $farm->latitude ?? 31.9522) }};
        var initialLng = {{ old('longitude', $farm->longitude ?? 35.2332) }};

        var map = L.map('admin-farm-map').setView([initialLat, initialLng], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        var marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);
        
        function updateCoords(lat, lng) {
            document.getElementById('lat').value = lat.toFixed(8);
            document.getElementById('lng').value = lng.toFixed(8);
        }

        marker.on('dragend', function(e) {
            updateCoords(marker.getLatLng().lat, marker.getLatLng().lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
        });
    });
</script>
@endsection
