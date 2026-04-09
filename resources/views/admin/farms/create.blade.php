@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-[fade-in_0.8s_ease-out]">
    {{-- Header Section --}}
    <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-800/60 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-2xl shadow-2xl">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/10 blur-[80px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <h1 class="text-4xl font-black text-white tracking-tighter mb-2">Add New <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-teal-300">Farm</span></h1>
            <p class="text-slate-400 font-medium">Create a high-fidelity property listing mapped to the platform schema.</p>
        </div>
        <a href="{{ route('admin.farms.index') }}" class="relative z-10 px-6 py-3 bg-slate-900/50 hover:bg-slate-700 text-slate-300 hover:text-white font-bold rounded-xl transition-all border border-slate-700 hover:border-slate-500 flex items-center gap-3 group shadow-lg">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Registry
        </a>
    </div>


    <form action="{{ route('admin.farms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 pb-24">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- Left Column: Form Details --}}
            <div class="xl:col-span-8 space-y-8">

                {{-- [SECTION: BASIC INFO] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl group">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Core Information
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Property Name --}}
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Property Name <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner placeholder-slate-500 font-medium" placeholder="e.g. The Royal Green Estate" required>
                            </div>
                        </div>

                        {{-- Capacity --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Max Capacity <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <input type="number" name="capacity" value="{{ old('capacity') }}" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner placeholder-slate-500 font-medium" placeholder="e.g. 25" required>
                            </div>
                        </div>

                        {{-- Rating --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Initial Rating <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-amber-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                </div>
                                <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', 5.0) }}" class="w-full bg-slate-900 border border-slate-700 text-emerald-400 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner font-bold" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: ADMINISTRATION & FINANCIALS] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl group">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-sm">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Administration & Financials
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Owner ID --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Owner Assignment <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <select name="owner_id" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-3.5 appearance-none cursor-pointer transition-all shadow-inner font-medium" required>
                                    <option value="" disabled selected>Select Farm Owner...</option>
                                    @if(isset($owners))
                                        @foreach($owners as $owner)
                                            <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>{{ $owner->name }} (#{{ $owner->id }})</option>
                                        @endforeach
                                    @else
                                        <option value="1" selected>Admin (Self-Owned)</option>
                                    @endif
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                            </div>
                        </div>

                        {{-- Commission Rate --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Commission Rate (%) <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </div>
                                <input type="number" step="0.1" name="commission_rate" value="{{ old('commission_rate', 10.0) }}" class="w-full bg-slate-900 border border-slate-700 text-emerald-400 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner font-bold" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-600 font-black text-[10px]">%</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: LOCATION & MAP] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-sm">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Location & Geolocation
                    </div>
                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Governorate --}}
                            <div class="space-y-1">
                                <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Governorate <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <select name="governorate" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-3.5 appearance-none cursor-pointer transition-all shadow-inner font-medium" required>
                                        <option value="" disabled selected>Select Region...</option>
                                        @foreach(['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Mafraq', 'Jerash', 'Ajloun', 'Balqa', 'Madaba', 'Karak', 'Tafilah', 'Ma\'an'] as $gov)
                                            <option value="{{ $gov }}" {{ old('governorate') == $gov ? 'selected' : '' }}>{{ $gov }}</option>
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
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    </div>
                                    <input type="text" name="location" value="{{ old('location') }}" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner placeholder-slate-500 font-medium" placeholder="e.g. Road 15, Near Valley" required>
                                </div>
                            </div>
                        </div>

                        {{-- Google Maps URL --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest">Google Maps URL</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </div>
                                <input type="url" name="location_link" value="{{ old('location_link') }}" class="w-full bg-slate-900 border border-slate-700 text-blue-400 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner placeholder-slate-500 font-medium" placeholder="https://goo.gl/maps/...">
                            </div>
                        </div>

                        {{-- Map --}}
                        <div class="space-y-4 pt-4 border-t border-slate-700/50">
                            <label class="block text-xs font-black text-emerald-400 mb-2 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v10.764a1 1 0 01-1.447.894L15 18M5 18l-1.553.776A1 1 0 012 17.882V7.118a1 1 0 011.447-.894L8 8m-3 10V8m10 10l-4.553-2.276A1 1 0 019 14.882V4.118a1 1 0 011.447-.894L15 6m0 12V6"/></svg>
                                Interactive Logistics Pin
                            </label>

                            <div class="rounded-3xl overflow-hidden border-2 border-slate-700 shadow-[0_0_25px_rgba(0,0,0,0.5)] h-[450px] relative">
                                <div id="admin-farm-map" class="absolute inset-0"></div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="relative bg-slate-900/80 rounded-xl border border-slate-700 p-3 flex items-center shadow-inner">
                                    <span class="px-3 text-[10px] font-black text-emerald-500 uppercase tracking-widest border-r border-slate-700">Latitude</span>
                                    <input type="text" name="latitude" id="lat" value="{{ old('latitude', '31.9522') }}" readonly class="bg-transparent text-slate-300 font-mono text-sm w-full pl-3 outline-none cursor-not-allowed">
                                </div>
                                <div class="relative bg-slate-900/80 rounded-xl border border-slate-700 p-3 flex items-center shadow-inner">
                                    <span class="px-3 text-[10px] font-black text-emerald-500 uppercase tracking-widest border-r border-slate-700">Longitude</span>
                                    <input type="text" name="longitude" id="lng" value="{{ old('longitude', '35.2332') }}" readonly class="bg-transparent text-slate-300 font-mono text-sm w-full pl-3 outline-none cursor-not-allowed">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: PRICING] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-sm">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Financial Strategy (JOD)
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Base Price --}}
                        <div class="md:col-span-2 p-6 bg-slate-900/60 rounded-3xl border border-emerald-500/30 shadow-inner group hover:bg-slate-900/80 transition-all relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-500/10 rounded-bl-full pointer-events-none"></div>
                            <label class="block text-xs font-black text-emerald-400 group-hover:text-emerald-300 mb-2 uppercase tracking-widest flex items-center gap-1">Base Listing Price <span class="text-rose-500">*</span></label>
                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-500/50">
                                    <span class="text-xl font-black uppercase">JOD</span>
                                </div>
                                <input type="number" step="0.01" name="price_per_night" value="{{ old('price_per_night', 50) }}" class="w-full bg-slate-900 border border-emerald-500/30 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-16 py-4 text-3xl font-black transition-all shadow-inner" required>
                            </div>
                        </div>

                        {{-- Full Day --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Full Day (24h) <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <input type="number" step="0.01" name="price_per_full_day" value="{{ old('price_per_full_day', 60) }}" class="w-full bg-slate-900 border border-slate-700 text-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-3.5 text-lg font-bold transition-all shadow-inner" required>
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
                                <input type="number" step="0.01" name="price_per_morning_shift" value="{{ old('price_per_morning_shift', 30) }}" class="w-full bg-slate-900 border border-slate-700 text-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-3.5 text-lg font-bold transition-all shadow-inner" required>
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
                                <input type="number" step="0.01" name="price_per_evening_shift" value="{{ old('price_per_evening_shift', 40) }}" class="w-full bg-slate-900 border border-slate-700 text-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-3.5 text-lg font-bold transition-all shadow-inner" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-600 font-black text-[10px]">JOD</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: DESCRIPTION] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-sm">
                        Property Details
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Full Description <span class="text-rose-500">*</span></label>
                        <div class="relative group">
                            <div class="absolute top-4 left-4 text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </div>
                            <textarea name="description" rows="6" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-4 resize-none leading-relaxed transition-all shadow-inner font-medium placeholder-slate-500" required>{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column: Media & Publish --}}
            <div class="xl:col-span-4 space-y-8">

                {{-- [SECTION: STATUS & APPROVAL] --}}
                <div class="bg-slate-800/80 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl sticky top-8 z-20">
                    <h3 class="block text-xs font-black text-slate-300 uppercase tracking-widest text-center mb-6 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Visibility & Approval
                    </h3>

                    <div class="space-y-6">
                        {{-- is_approved --}}
                        <div class="bg-slate-900/50 p-5 rounded-2xl border border-slate-700">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Admin Approval Flag</label>
                            <div class="relative">
                                <select name="is_approved" class="w-full bg-slate-800 border border-slate-600 text-white font-black uppercase tracking-widest text-xs py-3 px-4 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 appearance-none" required>
                                    <option value="1" selected>✅ Approved</option>
                                    <option value="0">❌ Not Approved</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none opacity-50"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                            </div>
                        </div>

                        {{-- status --}}
                        <div class="bg-slate-900/50 p-5 rounded-2xl border border-slate-700">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Listing Status</label>
                            <div class="relative">
                                <select name="status" class="w-full bg-slate-800 border border-slate-600 text-white font-black uppercase tracking-widest text-xs py-3 px-4 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 appearance-none" required>
                                    <option value="active" selected>🟢 Active</option>
                                    <option value="maintenance">🟡 Maintenance</option>
                                    <option value="suspended">🔴 Suspended</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none opacity-50"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: MEDIA] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-sm">
                        Media Library
                    </div>
                    <div class="space-y-8">
                        {{-- Main Cover --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest">Primary Cover <span class="text-rose-500">*</span></label>
                            <div class="relative group h-64 rounded-3xl border-2 border-dashed border-slate-600 flex items-center justify-center hover:border-emerald-400 hover:bg-emerald-400/5 transition-all overflow-hidden cursor-pointer bg-slate-900/60 shadow-inner">
                                <input type="file" name="main_image" class="absolute inset-0 opacity-0 cursor-pointer z-10" required>
                                <div class="text-center group-hover:scale-110 transition-transform duration-500">
                                    <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-500/20 group-hover:text-emerald-400 text-slate-500 transition-colors shadow-lg">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest group-hover:text-emerald-400">Upload Cover Photo</span>
                                </div>
                            </div>
                        </div>

                        {{-- Additional Gallery --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest">Additional Gallery (Optional)</label>
                            <div class="relative group h-32 rounded-3xl border-2 border-dashed border-slate-600 flex flex-col items-center justify-center hover:border-blue-400 hover:bg-blue-400/5 transition-all cursor-pointer bg-slate-900/60 shadow-inner">
                                <input type="file" name="images[]" multiple class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                <div class="text-center text-slate-500 group-hover:text-blue-400 transition-colors">
                                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" /></svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Select Multiple Photos</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action --}}
                <div class="pt-4 sticky top-[450px] z-20">
                    <button type="submit" class="w-full py-5 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white font-black rounded-2xl shadow-[0_15px_30px_-10px_rgba(16,185,129,0.5)] transition-all flex items-center justify-center gap-3 transform hover:-translate-y-1 active:scale-95 uppercase tracking-[0.2em] text-xs">
                        Register Farm to Network
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.farms.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-500 hover:text-rose-400 transition-colors uppercase tracking-widest group">
                            <svg class="w-4 h-4 group-hover:-rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Discard Entry
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
        const latInput = document.getElementById('lat');
        const lngInput = document.getElementById('lng');
        
        const initialPos = { 
            lat: parseFloat(latInput.value) || 31.9522, 
            lng: parseFloat(lngInput.value) || 35.2332 
        };

        const map = new google.maps.Map(document.getElementById('admin-farm-map'), {
            zoom: 9,
            center: initialPos,
            styles: [
                { "elementType": "geometry", "stylers": [{ "color": "#1d2c4d" }] },
                { "elementType": "labels.text.fill", "stylers": [{ "color": "#8ec3b9" }] },
                { "elementType": "labels.text.stroke", "stylers": [{ "color": "#1a3646" }] },
                { "featureType": "administrative.country", "elementType": "geometry.stroke", "stylers": [{ "color": "#4b6878" }] },
                { "featureType": "landscape.man_made", "elementType": "geometry.stroke", "stylers": [{ "color": "#334e62" }] },
                { "featureType": "poi", "elementType": "geometry", "stylers": [{ "color": "#283d6a" }] },
                { "featureType": "road", "elementType": "geometry", "stylers": [{ "color": "#304a7d" }] },
                { "featureType": "water", "elementType": "geometry", "stylers": [{ "color": "#0e1626" }] }
            ],
            disableDefaultUI: false
        });

        const marker = new google.maps.Marker({
            position: initialPos,
            map: map,
            draggable: true,
            title: "Farm Pin"
        });

        function updateCoords(lat, lng) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
        }

        marker.addListener('dragend', function() {
            const pos = marker.getPosition();
            updateCoords(pos.lat(), pos.lng());
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
