@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-[fade-in_0.8s_ease-out]">
    {{-- Header Section --}}
    <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-800/60 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-2xl shadow-2xl">
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-blue-500/10 blur-[80px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <h1 class="text-4xl font-black text-white tracking-tighter mb-2">Edit <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-300">Farm Profile</span></h1>
            <p class="text-slate-400 font-medium flex items-center gap-2">
                Modifying: <span class="text-white font-bold bg-slate-900/50 px-3 py-1 rounded-lg border border-slate-700">{{ $farm->name }}</span>
                <span class="text-[10px] text-blue-400 font-mono font-black uppercase tracking-widest bg-blue-500/10 px-2 py-1 rounded border border-blue-500/20">ID: #{{ str_pad($farm->id, 5, '0', STR_PAD_LEFT) }}</span>
            </p>
        </div>
        <a href="{{ route('admin.farms.index') }}" class="relative z-10 px-6 py-3 bg-slate-900/50 hover:bg-slate-700 text-slate-300 hover:text-white font-bold rounded-xl transition-all border border-slate-700 hover:border-slate-500 flex items-center gap-3 group shadow-lg">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Registry
        </a>
    </div>


    <form action="{{ route('admin.farms.update', $farm->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 pb-24">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- Left Column: Form Details --}}
            <div class="xl:col-span-8 space-y-8">

                {{-- [SECTION: BASIC INFO] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl group">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-blue-400 uppercase tracking-widest mb-8 shadow-sm group-hover:border-blue-500/30 transition-colors">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Core Information
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Property Name --}}
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Property Name <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <input type="text" name="name" value="{{ old('name', $farm->name) }}" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner font-medium" required>
                            </div>
                        </div>

                        {{-- Capacity --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Max Capacity <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <input type="number" name="capacity" value="{{ old('capacity', $farm->capacity) }}" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner font-medium" required>
                            </div>
                        </div>

                        {{-- Rating --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Platform Rating <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-amber-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                </div>
                                <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', $farm->rating) }}" class="w-full bg-slate-900 border border-slate-700 text-blue-400 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner font-bold" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: ADMINISTRATION & FINANCIALS] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl group">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-blue-400 uppercase tracking-widest mb-8 shadow-sm group-hover:border-blue-500/30 transition-colors">
                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Administration & Financials
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Owner ID --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Owner Assignment <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <select name="owner_id" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-3.5 appearance-none cursor-pointer transition-all shadow-inner font-medium" required>
                                    @if(isset($owners))
                                        @foreach($owners as $owner)
                                            <option value="{{ $owner->id }}" {{ old('owner_id', $farm->owner_id) == $owner->id ? 'selected' : '' }}>{{ $owner->name }} (#{{ $owner->id }})</option>
                                        @endforeach
                                    @else
                                        <option value="{{ $farm->owner_id }}" selected>Owner ID: {{ $farm->owner_id }}</option>
                                    @endif
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                            </div>
                        </div>

                        {{-- Commission Rate --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Commission Rate (%) <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </div>
                                <input type="number" step="0.1" name="commission_rate" value="{{ old('commission_rate', $farm->commission_rate) }}" class="w-full bg-slate-900 border border-slate-700 text-blue-400 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner font-bold" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-600 font-black text-[10px]">%</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: LOCATION & MAP] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-blue-400 uppercase tracking-widest mb-8 shadow-sm">
                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Location & Geolocation
                    </div>
                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Governorate --}}
                            <div class="space-y-1">
                                <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Governorate <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <select name="governorate" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-3.5 appearance-none cursor-pointer transition-all shadow-inner font-medium" required>
                                        @foreach(['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Mafraq', 'Jerash', 'Ajloun', 'Balqa', 'Madaba', 'Karak', 'Tafilah', 'Ma\'an'] as $gov)
                                            <option value="{{ $gov }}" {{ old('governorate', $farm->governorate) == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="space-y-1">
                                <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Street Address <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    </div>
                                    <input type="text" name="location" value="{{ old('location', $farm->location) }}" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner font-medium" required>
                                </div>
                            </div>
                        </div>

                        {{-- Google Maps URL --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest">Google Maps URL</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </div>
                                <input type="url" name="location_link" value="{{ old('location_link', $farm->location_link) }}" class="w-full bg-slate-900 border border-slate-700 text-blue-400 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner font-medium">
                            </div>
                        </div>

                        {{-- Map --}}
                        <div class="space-y-4 pt-4 border-t border-slate-700/50">
                            <label class="block text-xs font-black text-blue-400 mb-2 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v10.764a1 1 0 01-1.447.894L15 18M5 18l-1.553.776A1 1 0 012 17.882V7.118a1 1 0 011.447-.894L8 8m-3 10V8m10 10l-4.553-2.276A1 1 0 019 14.882V4.118a1 1 0 011.447-.894L15 6m0 12V6"/></svg>
                                Interactive Logistics Pin
                            </label>

                            <div class="rounded-3xl overflow-hidden border-2 border-slate-700 shadow-[0_0_25px_rgba(0,0,0,0.5)] h-[450px] relative">
                                <div id="admin-farm-map" class="absolute inset-0"></div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="relative bg-slate-900/80 rounded-xl border border-slate-700 p-3 flex items-center shadow-inner">
                                    <span class="px-3 text-[10px] font-black text-blue-500 uppercase tracking-widest border-r border-slate-700">Latitude</span>
                                    <input type="text" name="latitude" id="lat" value="{{ old('latitude', $farm->latitude ?? '31.9522') }}" readonly class="bg-transparent text-slate-300 font-mono text-sm w-full pl-3 outline-none cursor-not-allowed">
                                </div>
                                <div class="relative bg-slate-900/80 rounded-xl border border-slate-700 p-3 flex items-center shadow-inner">
                                    <span class="px-3 text-[10px] font-black text-blue-500 uppercase tracking-widest border-r border-slate-700">Longitude</span>
                                    <input type="text" name="longitude" id="lng" value="{{ old('longitude', $farm->longitude ?? '35.2332') }}" readonly class="bg-transparent text-slate-300 font-mono text-sm w-full pl-3 outline-none cursor-not-allowed">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: PRICING] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-blue-400 uppercase tracking-widest mb-8 shadow-sm">
                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Financial Strategy (JOD)
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Base Price --}}
                        <div class="md:col-span-2 p-6 bg-slate-900/60 rounded-3xl border border-blue-500/30 shadow-inner group hover:bg-slate-900/80 transition-all relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-24 h-24 bg-blue-500/10 rounded-bl-full pointer-events-none"></div>
                            <label class="block text-xs font-black text-blue-400 group-hover:text-blue-300 mb-2 uppercase tracking-widest flex items-center gap-1">Base Listing Price <span class="text-rose-500">*</span></label>
                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-500/50">
                                    <span class="text-xl font-black uppercase">JOD</span>
                                </div>
                                <input type="number" step="0.01" name="price_per_night" value="{{ old('price_per_night', $farm->price_per_night) }}" class="w-full bg-slate-900 border border-blue-500/30 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-16 py-4 text-3xl font-black transition-all shadow-inner" required>
                            </div>
                        </div>

                        {{-- Full Day --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Full Day (24h) <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <input type="number" step="0.01" name="price_per_full_day" value="{{ old('price_per_full_day', $farm->price_per_full_day) }}" class="w-full bg-slate-900 border border-slate-700 text-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-3.5 text-lg font-bold transition-all shadow-inner" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-600 font-black text-[10px]">JOD</div>
                            </div>
                        </div>

                        {{-- Morning Shift --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Morning Shift <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-amber-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <input type="number" step="0.01" name="price_per_morning_shift" value="{{ old('price_per_morning_shift', $farm->price_per_morning_shift) }}" class="w-full bg-slate-900 border border-slate-700 text-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-3.5 text-lg font-bold transition-all shadow-inner" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-600 font-black text-[10px]">JOD</div>
                            </div>
                        </div>

                        {{-- Evening Shift --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Evening Shift <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                </div>
                                <input type="number" step="0.01" name="price_per_evening_shift" value="{{ old('price_per_evening_shift', $farm->price_per_evening_shift) }}" class="w-full bg-slate-900 border border-slate-700 text-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-3.5 text-lg font-bold transition-all shadow-inner" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-600 font-black text-[10px]">JOD</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: DESCRIPTION] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-blue-400 uppercase tracking-widest mb-8 shadow-sm">
                        Property Details
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Full Description <span class="text-rose-500">*</span></label>
                        <div class="relative group">
                            <div class="absolute top-4 left-4 text-slate-500 group-focus-within:text-blue-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </div>
                            <textarea name="description" rows="6" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-4 resize-none leading-relaxed transition-all shadow-inner font-medium" required>{{ old('description', $farm->description) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column: Media & Publish --}}
            <div class="xl:col-span-4 space-y-8">

                {{-- [SECTION: STATUS & APPROVAL] --}}
                <div class="bg-slate-800/80 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl sticky top-8 z-20">
                    <h3 class="block text-xs font-black text-slate-300 uppercase tracking-widest text-center mb-6 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Visibility & Approval
                    </h3>

                    <div class="space-y-6">
                        {{-- is_approved --}}
                        <div class="bg-slate-900/50 p-5 rounded-2xl border border-slate-700">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Admin Approval Flag</label>
                            <div class="relative">
                                <select name="is_approved" class="w-full bg-slate-800 border border-slate-600 text-white font-black uppercase tracking-widest text-xs py-3 px-4 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 appearance-none" required>
                                    <option value="1" {{ old('is_approved', $farm->is_approved) == 1 ? 'selected' : '' }}>✅ Approved</option>
                                    <option value="0" {{ old('is_approved', $farm->is_approved) == 0 ? 'selected' : '' }}>❌ Pending / Denied</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none opacity-50"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                            </div>
                        </div>

                        {{-- status --}}
                        <div class="bg-slate-900/50 p-5 rounded-2xl border border-slate-700">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Listing Status</label>
                            <div class="relative">
                                <select name="status" class="w-full bg-slate-800 border border-slate-600 text-white font-black uppercase tracking-widest text-xs py-3 px-4 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 appearance-none" required>
                                    <option value="active" {{ old('status', $farm->status) == 'active' ? 'selected' : '' }}>🟢 Active</option>
                                    <option value="maintenance" {{ old('status', $farm->status) == 'maintenance' ? 'selected' : '' }}>🟡 Maintenance</option>
                                    <option value="suspended" {{ old('status', $farm->status) == 'suspended' ? 'selected' : '' }}>🔴 Suspended</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none opacity-50"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: MEDIA LIBRARY] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-blue-400 uppercase tracking-widest mb-8 shadow-sm">
                        Media Management
                    </div>
                    <div class="space-y-8">
                        {{-- Main Cover --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex justify-between items-center">
                                Primary Cover
                                <span class="text-[9px] bg-blue-500/20 text-blue-400 px-2 py-1 rounded">Current</span>
                            </label>
                            <div class="relative h-64 rounded-3xl overflow-hidden group shadow-[0_10px_25px_rgba(0,0,0,0.5)] border-2 border-slate-700 bg-slate-900">
                                <img src="{{ $farm->main_image ? asset('storage/'.$farm->main_image) : 'https://via.placeholder.com/400x300' }}" class="w-full h-full object-cover group-hover:scale-110 group-hover:blur-sm transition-all duration-500">
                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center p-4">
                                    <input type="file" name="main_image" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                    <div class="flex flex-col items-center transform translate-y-4 group-hover:translate-y-0 transition-all">
                                        <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center mb-3 text-blue-400 border border-blue-500/30">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        </div>
                                        <span class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Replace Cover</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Gallery --}}
                        <div class="space-y-4 pt-4 border-t border-slate-700/50">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex justify-between items-end">
                                Additional Gallery
                                <span class="text-[9px] bg-rose-500/10 text-rose-400 px-2 py-1 rounded border border-rose-500/20 tracking-normal normal-case">Tap to flag for deletion</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                @if(isset($farm->images) && $farm->images->count() > 0)
                                    @foreach($farm->images as $img)
                                        <div class="relative h-28 rounded-2xl overflow-hidden group border border-slate-700 shadow-lg cursor-pointer" onclick="const cb = this.querySelector('input'); cb.checked = !cb.checked; this.classList.toggle('ring-2'); this.classList.toggle('ring-rose-500'); this.querySelector('.overlay').classList.toggle('opacity-100');">
                                            <img src="{{ asset('storage/'.$img->image_url) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                            <div class="overlay absolute inset-0 bg-rose-900/80 opacity-0 transition-opacity flex flex-col items-center justify-center backdrop-blur-[2px]">
                                                <input type="checkbox" name="remove_gallery_images[]" value="{{ $img->id }}" onclick="event.stopPropagation()" class="hidden">
                                                <div class="w-8 h-8 rounded-full bg-rose-500/20 flex items-center justify-center mb-1">
                                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </div>
                                                <span class="text-[9px] font-black text-white uppercase tracking-widest">Delete</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                {{-- Add New --}}
                                <div class="relative h-28 rounded-2xl border-2 border-dashed border-slate-600 flex flex-col items-center justify-center hover:border-blue-400 hover:bg-blue-400/5 transition-all cursor-pointer bg-slate-900/50 group shadow-inner">
                                    <input type="file" name="images[]" multiple class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                    <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center mb-2 group-hover:bg-blue-500/20 transition-colors shadow">
                                        <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest group-hover:text-blue-400">Add More</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action --}}
                <div class="pt-4 sticky top-[450px] z-20">
                    <button type="submit" class="w-full py-5 bg-gradient-to-r from-blue-600 to-indigo-500 hover:from-blue-500 hover:to-indigo-400 text-white font-black rounded-2xl shadow-[0_15px_30px_-10px_rgba(59,130,246,0.5)] transition-all flex items-center justify-center gap-3 transform hover:-translate-y-1 active:scale-95 uppercase tracking-[0.2em] text-xs">
                        Save Modifications
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    </button>
                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.farms.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-500 hover:text-rose-400 transition-colors uppercase tracking-widest group">
                            <svg class="w-4 h-4 group-hover:-rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Cancel Editing
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=marker&callback=initAdminMap" async defer></script>
<script>
    function initAdminMap() {
        const darkStyle = [
            { "elementType": "geometry", "stylers": [{ "color": "#242f3e" }] },
            { "elementType": "labels.text.stroke", "stylers": [{ "color": "#242f3e" }] },
            { "elementType": "labels.text.fill", "stylers": [{ "color": "#746855" }] },
            { "featureType": "administrative.locality", "elementType": "labels.text.fill", "stylers": [{ "color": "#d59563" }] },
            { "featureType": "poi", "elementType": "labels.text.fill", "stylers": [{ "color": "#d59563" }] },
            { "featureType": "poi.park", "elementType": "geometry", "stylers": [{ "color": "#263c3f" }] },
            { "featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [{ "color": "#6b9a76" }] },
            { "featureType": "road", "elementType": "geometry", "stylers": [{ "color": "#38414e" }] },
            { "featureType": "road", "elementType": "geometry.stroke", "stylers": [{ "color": "#212a37" }] },
            { "featureType": "road", "elementType": "labels.text.fill", "stylers": [{ "color": "#9ca5b3" }] },
            { "featureType": "road.highway", "elementType": "geometry", "stylers": [{ "color": "#746855" }] },
            { "featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{ "color": "#1f2835" }] },
            { "featureType": "road.highway", "elementType": "labels.text.fill", "stylers": [{ "color": "#f3d19c" }] },
            { "featureType": "transit", "elementType": "geometry", "stylers": [{ "color": "#2f3948" }] },
            { "featureType": "transit.station", "elementType": "labels.text.fill", "stylers": [{ "color": "#d59563" }] },
            { "featureType": "water", "elementType": "geometry", "stylers": [{ "color": "#17263c" }] },
            { "featureType": "water", "elementType": "labels.text.fill", "stylers": [{ "color": "#515c6d" }] },
            { "featureType": "water", "elementType": "labels.text.stroke", "stylers": [{ "color": "#17263c" }] }
        ];

        const initialPos = {
            lat: {{ old('latitude', $farm->latitude ?? 31.9522) }},
            lng: {{ old('longitude', $farm->longitude ?? 35.9334) }}
        };

        const map = new google.maps.Map(document.getElementById('admin-farm-map'), {
            zoom: 14,
            center: initialPos,
            styles: darkStyle,
            mapTypeControl: false,
            streetViewControl: false
        });

        const marker = new google.maps.Marker({
            position: initialPos,
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP
        });

        function updateCoords(lat, lng) {
            document.getElementById('lat').value = lat.toFixed(6);
            document.getElementById('lng').value = lng.toFixed(6);
        }

        marker.addListener('dragend', function() {
            const p = marker.getPosition();
            updateCoords(p.lat(), p.lng());
        });

        map.addListener('click', function(e) {
            marker.setPosition(e.latLng);
            updateCoords(e.latLng.lat(), e.latLng.lng());
        });
    }
    window.initAdminMap = initAdminMap;
</script>
@endpush
@endsection
