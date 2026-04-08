@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-[fade-in_0.8s_ease-out]">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-800/50 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter mb-2">Add New <span class="text-emerald-400">Farm</span></h1>
            <p class="text-slate-400 font-medium">Create a high-fidelity farm listing for the platform.</p>
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

    <form action="{{ route('admin.farms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 pb-20">
        @csrf

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
                            <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="e.g. Royal Green Escape" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Max Capacity</label>
                            <input type="number" name="capacity" value="{{ old('capacity') }}" class="form-input" placeholder="e.g. 25" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Initial Rating</label>
                            <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', 5.0) }}" class="form-input" required>
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
                                    <option value="" disabled selected>Select Governorate</option>
                                    @foreach(['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Mafraq', 'Jerash', 'Ajloun', 'Balqa', 'Madaba', 'Karak', 'Tafilah', 'Ma\'an'] as $gov)
                                        <option value="{{ $gov }}" {{ old('governorate') == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Specific Address</label>
                                <input type="text" name="location" value="{{ old('location') }}" class="form-input" placeholder="e.g. Near Dead Sea Hotel" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Google Maps Link (Optional)</label>
                            <input type="url" name="location_link" value="{{ old('location_link') }}" class="form-input" placeholder="https://goo.gl/maps/...">
                        </div>

                        <div class="space-y-4">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1 px-1">Interactive Map Pin</label>
                            <p class="text-xs text-slate-400 font-medium px-1 italic">Click on map to set coordinates for logistics module.</p>
                            <div id="admin-farm-map" class="h-80 w-full rounded-2xl border border-slate-700 shadow-inner"></div>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="latitude" id="lat" value="{{ old('latitude', '31.9522') }}" readonly class="form-input bg-slate-950/50 text-slate-500 cursor-not-allowed text-xs font-mono">
                                <input type="text" name="longitude" id="lng" value="{{ old('longitude', '35.2332') }}" readonly class="form-input bg-slate-950/50 text-slate-500 cursor-not-allowed text-xs font-mono">
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
                            <input type="number" step="0.01" name="price_per_night" value="{{ old('price_per_night', 50) }}" class="form-input" required>
                        </div>
                        <div class="p-4 bg-slate-900/50 rounded-2xl border border-slate-700/30">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Morning Shift</label>
                            <input type="number" step="0.01" name="price_per_morning_shift" value="{{ old('price_per_morning_shift', 30) }}" class="form-input" required>
                        </div>
                        <div class="p-4 bg-slate-900/50 rounded-2xl border border-slate-700/30">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Evening Shift</label>
                            <input type="number" step="0.01" name="price_per_evening_shift" value="{{ old('price_per_evening_shift', 40) }}" class="form-input" required>
                        </div>
                        <div class="p-4 bg-emerald-500/5 rounded-2xl border border-emerald-500/20">
                            <label class="block text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-2">Full Day (24h)</label>
                            <input type="number" step="0.01" name="price_per_full_day" value="{{ old('price_per_full_day', 60) }}" class="form-input border-emerald-500/30" required>
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
                            <option value="approved" selected>Approved (Visible)</option>
                            <option value="pending">Pending Audit</option>
                            <option value="rejected">Rejected / Hidden</option>
                        </select>
                        <p class="text-[10px] text-slate-500 font-medium text-center">Approved farms appear in search results immediately.</p>
                    </div>
                </div>

                {{-- Images --}}
                <div class="bg-slate-800/30 p-8 rounded-[2.5rem] border border-slate-700/50 backdrop-blur-md">
                    <h3 class="text-lg font-black text-white mb-6">Media Upload</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Main Cover</label>
                            <div class="relative group h-40 rounded-2xl border-2 border-dashed border-slate-700 flex items-center justify-center hover:border-emerald-500/50 transition-all overflow-hidden cursor-pointer">
                                <input type="file" name="main_image" class="absolute inset-0 opacity-0 cursor-pointer z-10" required>
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-slate-500 group-hover:text-emerald-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Upload Cover</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Gallery Photos</label>
                            <div class="relative group h-24 rounded-2xl border-2 border-dashed border-slate-700 flex items-center justify-center hover:border-emerald-500/50 transition-all cursor-pointer">
                                <input type="file" name="images[]" multiple class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                <div class="text-center">
                                    <svg class="w-6 h-6 text-slate-500 group-hover:text-emerald-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action --}}
                <button type="submit" class="w-full py-6 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-[2rem] shadow-2xl shadow-emerald-900/40 transition-all flex items-center justify-center gap-3 transform active:scale-95 group uppercase tracking-widest text-sm">
                    Register Farm
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-slate-800/30 p-8 rounded-[2.5rem] border border-slate-700/50 backdrop-blur-md">
            <h3 class="text-xl font-black text-white mb-6">Detailed Description</h3>
            <textarea name="description" rows="6" class="form-input resize-none" placeholder="Enter amenities, rules, and property details..." required>{{ old('description') }}</textarea>
        </div>
    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('admin-farm-map').setView([31.9522, 35.2332], 9);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        var marker = L.marker([31.9522, 35.2332], {draggable: true}).addTo(map);
        
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
