@extends('layouts.admin')

@section('title', 'Create Farm Node')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }

    /* God Mode Inputs */
    .admin-input {
        background: rgba(2, 6, 23, 0.6) !important;
        border: 1px solid rgba(51, 65, 85, 0.8) !important;
        color: #f8fafc !important;
        transition: all 0.3s ease !important;
        border-radius: 1rem !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.3) !important;
    }
    .admin-input:focus {
        background: rgba(15, 23, 42, 1) !important;
        border-color: #10b981 !important;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2), inset 0 2px 4px rgba(0,0,0,0.3) !important;
        outline: none !important;
    }

    /* God Mode Select */
    .dark-select {
        appearance: none;
        background-color: rgba(2, 6, 23, 0.6) !important;
        border: 1px solid rgba(51, 65, 85, 0.8) !important;
        color: #fff !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2310b981' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 7l5 5 5-5'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        border-radius: 1rem !important;
        transition: all 0.3s ease;
    }
    .dark-select:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2) !important;
        outline: none;
    }
    .dark-select option { background-color: #0f172a; color: #fff; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-8 animate-god-in pb-24 fade-in-up">

    {{-- 🌟 Header Section --}}
    <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/10 blur-[80px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-3 shadow-inner text-slate-400">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> New Registry
            </div>
            <h1 class="text-4xl font-black text-white tracking-tighter mb-2">Add New <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-teal-300">Farm</span></h1>
            <p class="text-slate-400 font-medium">Create a high-fidelity property listing mapped to the platform schema.</p>
        </div>
        <a href="{{ route('admin.farms.index') }}" class="relative z-10 px-6 py-4 bg-slate-950 hover:bg-slate-800 text-slate-400 hover:text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl transition-all border border-slate-800 hover:border-slate-600 flex items-center justify-center gap-3 shadow-inner active:scale-95">
            <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Registry
        </a>
    </div>

    <form action="{{ route('admin.farms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        {{-- 💡 THE FIX: Added items-start to grid so sticky works --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start relative">

            {{-- 🌟 Left Column: Form Details --}}
            <div class="xl:col-span-8 space-y-8">

                {{-- [SECTION: BASIC INFO] --}}
                <div class="bg-slate-900/60 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-inner">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Core Information
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                        {{-- Property Name --}}
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Property Name <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full admin-input pl-12 font-bold text-sm" placeholder="e.g. The Royal Green Estate" required>
                            </div>
                        </div>

                        {{-- Capacity --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Max Capacity <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <input type="number" name="capacity" value="{{ old('capacity') }}" class="w-full admin-input pl-12 font-bold text-sm" placeholder="e.g. 25" required>
                            </div>
                        </div>

                        {{-- Rating --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Initial Rating <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-amber-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                </div>
                                <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', 5.0) }}" class="w-full admin-input pl-12 font-black text-emerald-400 text-sm" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: ADMINISTRATION & FINANCIALS] --}}
                <div class="bg-slate-900/60 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-inner">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Administration & Financials
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                        {{-- Owner ID --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Owner Assignment <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-emerald-400 transition-colors z-10">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <select name="owner_id" class="w-full dark-select pl-12 font-bold text-sm" required>
                                    <option value="" disabled selected>Select Farm Owner...</option>
                                    @if(isset($owners))
                                        @foreach($owners as $owner)
                                            <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>{{ $owner->name }} (#{{ $owner->id }})</option>
                                        @endforeach
                                    @else
                                        <option value="1" selected>Admin (Self-Owned)</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- Commission Rate --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Commission Rate (%) <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </div>
                                <input type="number" step="0.1" name="commission_rate" value="{{ old('commission_rate', 10.0) }}" class="w-full admin-input pl-12 pr-8 font-black text-emerald-400 text-sm" required>
                                <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none text-slate-600 font-black text-sm">%</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: LOCATION & MAP] --}}
                <div class="bg-slate-900/60 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-inner">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Location & Geolocation
                    </div>

                    <div class="space-y-8 relative z-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Governorate --}}
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Governorate <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-emerald-400 transition-colors z-10">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <select name="governorate" class="w-full dark-select pl-12 font-bold text-sm" required>
                                        <option value="" disabled selected>Select Region...</option>
                                        @foreach(['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Mafraq', 'Jerash', 'Ajloun', 'Balqa', 'Madaba', 'Karak', 'Tafilah', 'Ma\'an'] as $gov)
                                            <option value="{{ $gov }}" {{ old('governorate') == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Street Address <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-emerald-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    </div>
                                    <input type="text" name="location" value="{{ old('location') }}" class="w-full admin-input pl-12 font-bold text-sm" placeholder="e.g. Road 15, Near Valley" required>
                                </div>
                            </div>
                        </div>

                        {{-- Google Maps URL --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Google Maps URL</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </div>
                                <input type="url" name="location_link" value="{{ old('location_link') }}" class="w-full admin-input pl-12 font-mono text-blue-400 text-sm" placeholder="https://goo.gl/maps/...">
                            </div>
                        </div>

                        {{-- Map --}}
                        <div class="space-y-4 pt-6 border-t border-slate-800">
                            <label class="block text-[10px] font-black text-emerald-400 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v10.764a1 1 0 01-1.447.894L15 18M5 18l-1.553.776A1 1 0 012 17.882V7.118a1 1 0 011.447-.894L8 8m-3 10V8m10 10l-4.553-2.276A1 1 0 019 14.882V4.118a1 1 0 011.447-.894L15 6m0 12V6"/></svg>
                                Interactive Routing Pin
                            </label>

                            <div class="rounded-3xl overflow-hidden border-4 border-slate-950 shadow-[0_0_30px_rgba(0,0,0,0.8)] h-[450px] relative">
                                <div id="admin-farm-map" class="absolute inset-0"></div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="relative bg-slate-950 rounded-[1.25rem] border border-slate-800 p-3.5 flex items-center shadow-inner">
                                    <span class="px-3 text-[10px] font-black text-emerald-500 uppercase tracking-widest border-r border-slate-800">LAT</span>
                                    <input type="text" name="latitude" id="lat" value="{{ old('latitude', '31.9522') }}" readonly class="bg-transparent text-slate-300 font-mono text-sm w-full pl-4 outline-none cursor-not-allowed">
                                </div>
                                <div class="relative bg-slate-950 rounded-[1.25rem] border border-slate-800 p-3.5 flex items-center shadow-inner">
                                    <span class="px-3 text-[10px] font-black text-emerald-500 uppercase tracking-widest border-r border-slate-800">LNG</span>
                                    <input type="text" name="longitude" id="lng" value="{{ old('longitude', '35.9334') }}" readonly class="bg-transparent text-slate-300 font-mono text-sm w-full pl-4 outline-none cursor-not-allowed">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: PRICING] --}}
                <div class="bg-slate-900/60 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                    <div class="absolute -left-20 -top-20 w-64 h-64 bg-amber-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-amber-400 uppercase tracking-widest mb-8 shadow-inner relative z-10">
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Financial Strategy (JOD)
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">

                        {{-- Base Price --}}
                        <div class="md:col-span-2 p-8 bg-[#020617] rounded-[2rem] border border-amber-500/30 shadow-[inset_0_2px_15px_rgba(0,0,0,0.5)] group hover:border-amber-500/50 transition-all relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-32 h-32 bg-amber-500/10 rounded-bl-[4rem] pointer-events-none"></div>
                            <label class="block text-[10px] font-black text-amber-500 mb-4 uppercase tracking-widest flex items-center gap-2">Base Listing Price <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-amber-500/50">
                                    <span class="text-xl font-black uppercase">JOD</span>
                                </div>
                                <input type="number" step="0.01" name="price_per_night" value="{{ old('price_per_night', 50) }}" class="w-full bg-transparent border-none text-white rounded-xl focus:ring-0 pl-16 py-2 text-4xl font-black outline-none" required>
                            </div>
                        </div>

                        {{-- Full Day --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Full Day (24h) <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-amber-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <input type="number" step="0.01" name="price_per_full_day" value="{{ old('price_per_full_day', 60) }}" class="w-full admin-input pl-12 font-bold text-sm" required>
                            </div>
                        </div>

                        {{-- Morning Shift --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Morning Shift <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-amber-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <input type="number" step="0.01" name="price_per_morning_shift" value="{{ old('price_per_morning_shift', 30) }}" class="w-full admin-input pl-12 font-bold text-sm" required>
                            </div>
                        </div>

                        {{-- Evening Shift --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Evening Shift <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-indigo-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                </div>
                                <input type="number" step="0.01" name="price_per_evening_shift" value="{{ old('price_per_evening_shift', 40) }}" class="w-full admin-input pl-12 font-bold text-sm" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: DESCRIPTION] --}}
                <div class="bg-slate-900/60 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-inner">
                        Property Details
                    </div>
                    <div class="space-y-2 relative z-10">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Full Description <span class="text-rose-500">*</span></label>
                        <div class="relative group">
                            <div class="absolute top-5 left-5 text-slate-600 group-focus-within:text-emerald-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </div>
                            <textarea name="description" rows="6" class="w-full admin-input pl-14 py-5 resize-none leading-relaxed font-medium" placeholder="Detailed property description..." required>{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 🌟 Right Column: Media & Publish (MADE STICKY) 🌟 --}}
            <div class="xl:col-span-4 h-full relative">
                <div class="sticky top-28 space-y-8 pb-10">

                    {{-- [SECTION: STATUS & APPROVAL] --}}
                    <div class="bg-slate-900/90 p-8 rounded-[3rem] border border-slate-800 backdrop-blur-3xl shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
                        <h3 class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] text-center mb-8 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Visibility Control
                        </h3>

                        <div class="space-y-6">
                            {{-- is_approved --}}
                            <div class="bg-[#020617] p-5 rounded-3xl border border-slate-800 shadow-inner">
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">Approval Flag</label>
                                <div class="relative z-20">
                                    <select name="is_approved" class="w-full dark-select font-bold text-xs" required>
                                        <option value="1" selected>✅ Approved</option>
                                        <option value="0">❌ Pending / Denied</option>
                                    </select>
                                </div>
                            </div>

                            {{-- status --}}
                            <div class="bg-[#020617] p-5 rounded-3xl border border-slate-800 shadow-inner">
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">Listing Status</label>
                                <div class="relative z-10">
                                    <select name="status" class="w-full dark-select font-bold text-xs" required>
                                        <option value="active" selected>🟢 Active</option>
                                        <option value="maintenance">🟡 Maintenance</option>
                                        <option value="suspended">🔴 Suspended</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="mt-10 pt-6 border-t border-slate-800">
                            <button type="submit" class="w-full py-5 bg-gradient-to-r from-emerald-600 to-teal-500 hover:to-teal-400 text-white font-black rounded-[1.25rem] shadow-[0_10px_30px_rgba(16,185,129,0.3)] transition-all flex items-center justify-center gap-3 active:scale-95 uppercase tracking-[0.2em] text-[10px]">
                                Register Farm
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>

                    {{-- [SECTION: MEDIA LIBRARY] --}}
                    <div class="bg-slate-900/60 p-8 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-inner">
                            Media Repository
                        </div>

                        <div class="space-y-8">
                            {{-- Main Cover --}}
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest flex justify-between items-center ml-1">
                                    Primary Cover <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative h-64 rounded-3xl overflow-hidden group border-2 border-dashed border-slate-600 bg-[#020617] hover:border-emerald-500 hover:bg-emerald-500/5 transition-all shadow-inner cursor-pointer flex flex-col items-center justify-center">
                                    <input type="file" name="main_image" class="absolute inset-0 opacity-0 cursor-pointer z-10" required>
                                    <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center mb-4 group-hover:bg-emerald-500/20 text-slate-500 group-hover:text-emerald-400 transition-colors shadow-lg">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-emerald-400">Upload Cover Photo</span>
                                </div>
                            </div>

                            {{-- Additional Gallery --}}
                            <div class="space-y-3 pt-6 border-t border-slate-800">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Additional Gallery</label>
                                <div class="relative h-32 rounded-3xl border-2 border-dashed border-slate-600 flex flex-col items-center justify-center hover:border-emerald-500 hover:bg-emerald-500/5 transition-all cursor-pointer bg-slate-900/60 shadow-inner group">
                                    <input type="file" name="images[]" multiple class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                    <div class="text-center text-slate-500 group-hover:text-emerald-400 transition-colors">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" /></svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Select Multiple</span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
            lng: parseFloat(lngInput.value) || 35.9334
        };

        const darkStyle = [
            { "elementType": "geometry", "stylers": [{ "color": "#020617" }] },
            { "elementType": "labels.text.stroke", "stylers": [{ "color": "#020617" }] },
            { "elementType": "labels.text.fill", "stylers": [{ "color": "#64748b" }] },
            { "featureType": "administrative.locality", "elementType": "labels.text.fill", "stylers": [{ "color": "#94a3b8" }] },
            { "featureType": "poi", "elementType": "labels.text.fill", "stylers": [{ "color": "#475569" }] },
            { "featureType": "poi.park", "elementType": "geometry", "stylers": [{ "color": "#0f172a" }] },
            { "featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [{ "color": "#334155" }] },
            { "featureType": "road", "elementType": "geometry", "stylers": [{ "color": "#1e293b" }] },
            { "featureType": "road", "elementType": "geometry.stroke", "stylers": [{ "color": "#0f172a" }] },
            { "featureType": "road", "elementType": "labels.text.fill", "stylers": [{ "color": "#64748b" }] },
            { "featureType": "road.highway", "elementType": "geometry", "stylers": [{ "color": "#334155" }] },
            { "featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{ "color": "#0f172a" }] },
            { "featureType": "water", "elementType": "geometry", "stylers": [{ "color": "#0f172a" }] },
            { "featureType": "water", "elementType": "labels.text.fill", "stylers": [{ "color": "#475569" }] },
            { "featureType": "water", "elementType": "labels.text.stroke", "stylers": [{ "color": "#020617" }] }
        ];

        const map = new google.maps.Map(document.getElementById('admin-farm-map'), {
            zoom: 9,
            center: initialPos,
            styles: darkStyle,
            disableDefaultUI: false
        });

        const markerIcon = {
            path: 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z',
            fillColor: '#10b981', /* Emerald Color */
            fillOpacity: 1,
            strokeWeight: 1,
            strokeColor: '#020617',
            scale: 1.5,
            anchor: new google.maps.Point(12, 24),
        };

        const marker = new google.maps.Marker({
            position: initialPos,
            map: map,
            draggable: true,
            icon: markerIcon,
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
