<x-owner-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 animate-fade-in-up">
            <div class="flex items-center gap-4">
                <a href="{{ route('owner.farms.index') }}" class="p-3 bg-slate-900 border border-slate-800 text-slate-400 hover:text-emerald-400 hover:border-emerald-500/30 hover:bg-emerald-500/10 rounded-2xl transition-all shadow-inner active:scale-95 group">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-900/80 border border-slate-700 text-[9px] font-black text-emerald-400 uppercase tracking-widest mb-1 shadow-inner">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Property Registration
                    </div>
                    <h1 class="text-3xl font-black text-white tracking-tighter">List a New <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-teal-400">Farm</span></h1>
                </div>
            </div>
            <div class="text-left md:text-right hidden md:block">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Status: Pending Super Admin Approval</p>
            </div>
        </div>
    </x-slot>

    <style>
        .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
        @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }

        /* God Mode Inputs */
        .owner-input {
            background: rgba(2, 6, 23, 0.6) !important;
            border: 1px solid rgba(51, 65, 85, 0.8) !important;
            color: #f8fafc !important;
            transition: all 0.3s ease !important;
            border-radius: 1.25rem !important;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.3) !important;
        }
        .owner-input:focus {
            background: rgba(15, 23, 42, 1) !important;
            border-color: #10b981 !important;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2), inset 0 2px 4px rgba(0,0,0,0.3) !important;
            outline: none !important;
        }
        .owner-input::placeholder { color: #475569 !important; font-weight: 500; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;}

        /* Custom Input Autofill Dark Mode Fix */
        input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus, input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #020617 inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
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
            border-radius: 1.25rem !important;
            transition: all 0.3s ease;
        }
        .dark-select:focus { border-color: #10b981 !important; box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2) !important; outline: none; }
        .dark-select option { background-color: #0f172a; color: #fff; }

        /* Leaflet Map Styling (Fallback) */
        .leaflet-container { background: #020617 !important; border-radius: 1.5rem; }
    </style>

    {{-- Leaflet CSS for Fallback Map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="pb-24 pt-4 fade-in-up">
        <form action="{{ route('owner.farms.store') }}" method="POST" enctype="multipart/form-data" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            @csrf

            {{-- 🌟 Error Handling --}}
            @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/30 rounded-[2rem] p-6 shadow-inner backdrop-blur-md mb-8">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-rose-500/20 flex items-center justify-center shrink-0 border border-rose-500/30 text-rose-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xs font-black text-rose-400 uppercase tracking-widest mb-2">Validation Failed</h3>
                            <ul class="list-disc pl-5 font-bold text-sm text-slate-300 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- [SECTION 1: GENERAL INFORMATION] --}}
            <div class="bg-slate-900/60 rounded-[3rem] shadow-2xl border border-slate-800 overflow-hidden relative backdrop-blur-2xl">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="px-8 py-6 border-b border-slate-800 bg-slate-950/40 flex items-center gap-4 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20 shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-white">General Information</h3>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Core Property Details</p>
                    </div>
                </div>

                <div class="p-8 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                    <div class="md:col-span-2 space-y-2">
                        <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Farm Name <span class="text-emerald-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. The Green Oasis Farm" class="w-full owner-input px-5 py-4 font-bold text-sm" required>
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label for="capacity" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Capacity (Max Persons) <span class="text-emerald-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <input type="number" id="capacity" name="capacity" value="{{ old('capacity') }}" min="1" placeholder="e.g. 20" class="w-full owner-input pl-14 pr-5 py-4 font-bold text-sm" required>
                        </div>
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label for="description" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Detailed Description <span class="text-emerald-500">*</span></label>
                        <textarea id="description" name="description" rows="6" class="w-full owner-input px-5 py-5 resize-y font-medium text-sm leading-relaxed" placeholder="Describe your farm's amenities, vibe, and features... What makes it special?" required>{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- [SECTION 2: LOCATION & MAP] --}}
            <div class="bg-slate-900/60 rounded-[3rem] shadow-2xl border border-slate-800 overflow-hidden relative backdrop-blur-2xl">
                <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-blue-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="px-8 py-6 border-b border-slate-800 bg-slate-950/40 flex items-center gap-4 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center border border-blue-500/20 shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-white">Location & Geolocation</h3>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Pinpoint your property for guests & drivers</p>
                    </div>
                </div>

                <div class="p-8 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                    <div class="space-y-2">
                        <label for="governorate" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Governorate <span class="text-emerald-500">*</span></label>
                        <select id="governorate" name="governorate" class="w-full dark-select px-5 py-4 font-bold text-sm" required>
                            <option value="" disabled {{ old('governorate') ? '' : 'selected' }}>Select Governorate</option>
                            @foreach(['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Mafraq', 'Jerash', 'Ajloun', 'Balqa', 'Madaba', 'Karak', 'Tafilah', 'Ma\'an'] as $gov)
                                <option value="{{ $gov }}" {{ old('governorate') == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="location" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Detailed Address <span class="text-emerald-500">*</span></label>
                        <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="e.g. Dead Sea Hwy, Near XYZ Resort" class="w-full owner-input px-5 py-4 font-bold text-sm" required>
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label for="location_link" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Google Maps Link <span class="text-slate-600">(Optional)</span></label>
                        <input type="url" id="location_link" name="location_link" value="{{ old('location_link') }}" placeholder="https://maps.google.com/..." class="w-full owner-input px-5 py-4 font-mono text-blue-400 text-sm">
                    </div>

                    <div class="md:col-span-2 mt-4 space-y-4">
                        <div class="flex items-center gap-2">
                            <label class="block text-[10px] font-black text-blue-400 uppercase tracking-widest">Pinpoint on Interactive Map <span class="text-emerald-500">*</span></label>
                        </div>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Drag the pin to set exact coordinates. Required for accurate supply routing.</p>

                        <div class="rounded-3xl overflow-hidden border-4 border-slate-950 shadow-[0_0_30px_rgba(0,0,0,0.8)] h-[450px] relative">
                            <div id="farm-map" class="absolute inset-0 z-0"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative bg-slate-950 rounded-[1.25rem] border border-slate-800 p-3.5 flex items-center shadow-inner">
                                <span class="px-3 text-[10px] font-black text-blue-500 uppercase tracking-widest border-r border-slate-800">LAT</span>
                                <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}" class="bg-transparent text-slate-300 font-mono text-sm w-full pl-4 outline-none cursor-not-allowed" readonly required>
                            </div>
                            <div class="relative bg-slate-950 rounded-[1.25rem] border border-slate-800 p-3.5 flex items-center shadow-inner">
                                <span class="px-3 text-[10px] font-black text-blue-500 uppercase tracking-widest border-r border-slate-800">LNG</span>
                                <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}" class="bg-transparent text-slate-300 font-mono text-sm w-full pl-4 outline-none cursor-not-allowed" readonly required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- [SECTION 3: PRICING] --}}
            <div class="bg-slate-900/60 rounded-[3rem] shadow-2xl border border-slate-800 overflow-hidden relative backdrop-blur-2xl">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-amber-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="px-8 py-6 border-b border-slate-800 bg-slate-950/40 flex items-center gap-4 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center border border-amber-500/20 shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-white">Pricing Configuration</h3>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Base prices configured in JOD</p>
                    </div>
                </div>

                <div class="p-8 md:p-10 grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                    <div class="space-y-2">
                        <label for="price_per_morning_shift" class="block text-[10px] font-black text-amber-500 uppercase tracking-widest ml-1">Morning Shift <span class="text-slate-500">(Daytime)</span> <span class="text-emerald-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <span class="text-amber-500/50 font-black text-xs uppercase tracking-widest">JOD</span>
                            </div>
                            <input type="number" step="0.01" min="0" id="price_per_morning_shift" name="price_per_morning_shift" value="{{ old('price_per_morning_shift') }}" class="w-full owner-input pl-16 py-4 font-black text-lg text-white focus:border-amber-500 focus:ring-amber-500" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="price_per_evening_shift" class="block text-[10px] font-black text-amber-500 uppercase tracking-widest ml-1">Evening Shift <span class="text-slate-500">(Nighttime)</span> <span class="text-emerald-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <span class="text-amber-500/50 font-black text-xs uppercase tracking-widest">JOD</span>
                            </div>
                            <input type="number" step="0.01" min="0" id="price_per_evening_shift" name="price_per_evening_shift" value="{{ old('price_per_evening_shift') }}" class="w-full owner-input pl-16 py-4 font-black text-lg text-white focus:border-amber-500 focus:ring-amber-500" required>
                        </div>
                    </div>

                    <div class="space-y-2 p-6 bg-[#020617] rounded-[2rem] border border-amber-500/30 shadow-[inset_0_2px_15px_rgba(0,0,0,0.5)]">
                        <label for="price_per_full_day" class="block text-[10px] font-black text-amber-400 uppercase tracking-widest">Full Day <span class="text-slate-500">(24 Hours)</span> <span class="text-emerald-500">*</span></label>
                        <div class="relative mt-2">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-amber-500/50 font-black text-xs uppercase tracking-widest">JOD</span>
                            </div>
                            <input type="number" step="0.01" min="0" id="price_per_full_day" name="price_per_full_day" value="{{ old('price_per_full_day') }}" class="w-full bg-transparent border-none text-white rounded-xl focus:ring-0 pl-14 py-2 text-3xl font-black outline-none" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- [SECTION 4: MEDIA UPLOADS] --}}
            <div class="bg-slate-900/60 rounded-[3rem] shadow-2xl border border-slate-800 overflow-hidden relative backdrop-blur-2xl">
                <div class="px-8 py-6 border-b border-slate-800 bg-slate-950/40 flex items-center gap-4 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center border border-purple-500/20 shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-white">Media Matrix</h3>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Upload high-quality property photos</p>
                    </div>
                </div>

                <div class="p-8 md:p-10 space-y-10 relative z-10">
                    {{-- Main Cover Image --}}
                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Main Cover Image <span class="text-emerald-500">*</span></label>
                        <div class="flex justify-center px-6 pt-8 pb-10 border-2 border-slate-700 border-dashed rounded-[2rem] bg-[#020617] hover:bg-emerald-500/5 hover:border-emerald-500 transition-all cursor-pointer group shadow-inner" onclick="document.getElementById('main_image').click()">
                            <div class="space-y-4 text-center transform transition-all duration-500 group-hover:scale-105">
                                <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto shadow-lg border border-slate-700 group-hover:border-emerald-500/30 group-hover:bg-emerald-500/10 transition-colors">
                                    <svg class="h-8 w-8 text-slate-500 group-hover:text-emerald-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="main_image" class="relative font-black text-emerald-400 uppercase tracking-widest text-[11px] cursor-pointer">
                                        <span>Select Main Image</span>
                                        <input id="main_image" name="main_image" type="file" class="sr-only" accept="image/*" required>
                                    </label>
                                </div>
                                <p class="text-[9px] font-bold text-slate-500 tracking-widest uppercase main-image-text">PNG, JPG, WEBP up to 5MB</p>
                            </div>
                        </div>
                    </div>

                    {{-- Gallery Images --}}
                    <div class="space-y-4 pt-6 border-t border-slate-800">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Gallery Images <span class="text-slate-600 normal-case">(Optional but recommended)</span></label>
                        <div class="flex justify-center px-6 pt-6 pb-8 border-2 border-slate-700 border-dashed rounded-[2rem] bg-[#020617] hover:bg-blue-500/5 hover:border-blue-500 transition-all cursor-pointer group shadow-inner" onclick="document.getElementById('gallery').click()">
                            <div class="space-y-4 text-center transform transition-all duration-500 group-hover:scale-105">
                                <div class="w-14 h-14 bg-slate-800 rounded-full flex items-center justify-center mx-auto shadow-lg border border-slate-700 group-hover:border-blue-500/30 group-hover:bg-blue-500/10 transition-colors">
                                    <svg class="h-6 w-6 text-slate-500 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="gallery" class="relative font-black text-blue-400 uppercase tracking-widest text-[10px] cursor-pointer">
                                        <span>Upload Multiple Files</span>
                                        <input id="gallery" name="gallery[]" type="file" multiple class="sr-only" accept="image/*">
                                    </label>
                                </div>
                                <p class="text-[9px] font-bold text-slate-500 tracking-widest uppercase gallery-text">Select multiple files (Max 5MB each)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 🌟 Action Buttons Footer --}}
            <div class="fixed bottom-0 inset-x-0 sm:static sm:bottom-auto sm:inset-x-auto z-40 bg-slate-950/90 sm:bg-transparent border-t border-slate-800 sm:border-t-0 p-4 sm:p-0 shadow-[0_-10px_40px_rgba(0,0,0,0.5)] sm:shadow-none flex flex-col sm:flex-row items-center justify-end gap-4 mt-12 backdrop-blur-xl sm:backdrop-blur-none">
                <a href="{{ route('owner.farms.index') }}" class="w-full sm:w-auto px-10 py-5 bg-slate-900 text-slate-400 hover:text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl transition-all border border-slate-800 hover:border-slate-600 shadow-inner text-center active:scale-95">
                    Cancel
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 py-5 px-12 rounded-2xl shadow-[0_10px_30px_rgba(16,185,129,0.3)] text-[11px] font-black tracking-[0.2em] uppercase text-slate-950 bg-gradient-to-r from-teal-500 to-emerald-400 hover:to-emerald-300 transition-all transform active:scale-95 border border-emerald-400/50">
                    Submit Farm for Approval
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Leaflet Map Scripts (Fallback Solution) --}}
    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var latInput = document.getElementById('latitude');
            var lngInput = document.getElementById('longitude');

            // Default Amman Coordinates
            var initialLat = latInput.value ? parseFloat(latInput.value) : 31.9522;
            var initialLng = lngInput.value ? parseFloat(lngInput.value) : 35.9334;

            // Initialize OpenStreetMap via Leaflet
            var map = L.map('farm-map', {
                center: [initialLat, initialLng],
                zoom: 12,
                zoomControl: true
            });

            // Add Dark Tile Layer (CartoDB Dark Matter)
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            // Custom Emerald Marker
            var emeraldIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color:#10b981;width:20px;height:20px;border-radius:50%;border:3px solid #020617;box-shadow:0 0 15px rgba(16,185,129,0.8);"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            var marker = L.marker([initialLat, initialLng], {
                icon: emeraldIcon,
                draggable: true
            }).addTo(map);

            function updateInputs(lat, lng) {
                latInput.value = lat.toFixed(6);
                lngInput.value = lng.toFixed(6);
            }

            // Drag event
            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                updateInputs(position.lat, position.lng);
            });

            // Click event
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });

            // Initialize default values if empty
            if (!latInput.value || !lngInput.value) {
                updateInputs(initialLat, initialLng);
            }
        });

        // File upload text preview scripts
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('main_image').addEventListener('change', function(e) {
                if(e.target.files.length > 0) {
                    var fileName = e.target.files[0].name;
                    document.querySelector('.main-image-text').innerHTML = '<span class="text-emerald-400 font-black">SELECTED: </span> <span class="text-white">' + fileName + '</span>';
                }
            });

            document.getElementById('gallery').addEventListener('change', function(e) {
                if(e.target.files.length > 0) {
                    var count = e.target.files.length;
                    document.querySelector('.gallery-text').innerHTML = '<span class="text-blue-400 font-black">' + count + ' FILE(S) READY</span>';
                }
            });
        });
    </script>
    @endpush
</x-owner-layout>
