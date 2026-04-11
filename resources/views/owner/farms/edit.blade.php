<x-owner-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 animate-fade-in-up">
            <div class="flex items-center gap-4">
                <a href="{{ route('owner.farms.index') }}" class="p-3 bg-slate-900 border border-slate-800 text-slate-400 hover:text-emerald-400 hover:border-emerald-500/30 hover:bg-emerald-500/10 rounded-2xl transition-all shadow-inner active:scale-95 group">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-900/80 border border-slate-700 text-[9px] font-black text-emerald-400 uppercase tracking-widest mb-1 shadow-inner">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Property Configuration
                    </div>
                    <h1 class="text-3xl font-black text-white tracking-tighter">Edit Farm: <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-teal-400">{{ $farm->name }}</span></h1>
                </div>
            </div>
            <div class="text-left md:text-right hidden md:block">
                <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mt-1 bg-amber-500/10 border border-amber-500/20 px-3 py-1.5 rounded-lg inline-block shadow-inner">Note: Saving edits triggers Super Admin Review</p>
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
    </style>

    <div class="pb-24 pt-4 fade-in-up">
        <form action="{{ route('owner.farms.update', $farm->id) }}" method="POST" enctype="multipart/form-data" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            @csrf
            @method('PUT')

            {{-- 🌟 Error Handling --}}
            @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/30 rounded-[2rem] p-6 shadow-inner backdrop-blur-md mb-8">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-rose-500/20 flex items-center justify-center shrink-0 border border-rose-500/30 text-rose-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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
                        <input type="text" id="name" name="name" value="{{ old('name', $farm->name) }}" class="w-full owner-input px-5 py-4 font-bold text-sm" required>
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label for="capacity" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Capacity (Max Persons) <span class="text-emerald-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $farm->capacity) }}" min="1" class="w-full owner-input pl-14 pr-5 py-4 font-bold text-sm" required>
                        </div>
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label for="description" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Detailed Description <span class="text-emerald-500">*</span></label>
                        <textarea id="description" name="description" rows="6" class="w-full owner-input px-5 py-5 resize-y font-medium text-sm leading-relaxed" required>{{ old('description', $farm->description) }}</textarea>
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
                            @foreach(['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Mafraq', 'Jerash', 'Ajloun', 'Balqa', 'Madaba', 'Karak', 'Tafilah', 'Ma\'an'] as $gov)
                                <option value="{{ $gov }}" {{ old('governorate', $farm->governorate) == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="location" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Detailed Address <span class="text-emerald-500">*</span></label>
                        <input type="text" id="location" name="location" value="{{ old('location', $farm->location ?? $farm->location_link) }}" class="w-full owner-input px-5 py-4 font-bold text-sm" required>
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label for="location_link" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Google Maps Link <span class="text-slate-600">(Optional)</span></label>
                        <input type="url" id="location_link" name="location_link" value="{{ old('location_link', $farm->location_link) }}" class="w-full owner-input px-5 py-4 font-mono text-blue-400 text-sm">
                    </div>

                    <div class="md:col-span-2 mt-4 space-y-4">
                        <div class="flex items-center gap-2">
                            <label class="block text-[10px] font-black text-blue-400 uppercase tracking-widest">Pinpoint on Interactive Map <span class="text-emerald-500">*</span></label>
                        </div>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Drag the pin to update exact coordinates. Required for accurate supply routing.</p>

                        <div class="rounded-3xl overflow-hidden border-4 border-slate-950 shadow-[0_0_30px_rgba(0,0,0,0.8)] h-[450px] relative">
                            <div id="farm-map" class="absolute inset-0"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative bg-slate-950 rounded-[1.25rem] border border-slate-800 p-3.5 flex items-center shadow-inner">
                                <span class="px-3 text-[10px] font-black text-blue-500 uppercase tracking-widest border-r border-slate-800">LAT</span>
                                <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $farm->latitude) }}" class="bg-transparent text-slate-300 font-mono text-sm w-full pl-4 outline-none cursor-not-allowed" readonly required>
                            </div>
                            <div class="relative bg-slate-950 rounded-[1.25rem] border border-slate-800 p-3.5 flex items-center shadow-inner">
                                <span class="px-3 text-[10px] font-black text-blue-500 uppercase tracking-widest border-r border-slate-800">LNG</span>
                                <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $farm->longitude) }}" class="bg-transparent text-slate-300 font-mono text-sm w-full pl-4 outline-none cursor-not-allowed" readonly required>
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
                            <input type="number" step="0.01" min="0" id="price_per_morning_shift" name="price_per_morning_shift" value="{{ old('price_per_morning_shift', $farm->price_per_morning_shift) }}" class="w-full owner-input pl-16 py-4 font-black text-lg text-white focus:border-amber-500 focus:ring-amber-500" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="price_per_evening_shift" class="block text-[10px] font-black text-amber-500 uppercase tracking-widest ml-1">Evening Shift <span class="text-slate-500">(Nighttime)</span> <span class="text-emerald-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <span class="text-amber-500/50 font-black text-xs uppercase tracking-widest">JOD</span>
                            </div>
                            <input type="number" step="0.01" min="0" id="price_per_evening_shift" name="price_per_evening_shift" value="{{ old('price_per_evening_shift', $farm->price_per_evening_shift) }}" class="w-full owner-input pl-16 py-4 font-black text-lg text-white focus:border-amber-500 focus:ring-amber-500" required>
                        </div>
                    </div>

                    <div class="space-y-2 p-6 bg-[#020617] rounded-[2rem] border border-amber-500/30 shadow-[inset_0_2px_15px_rgba(0,0,0,0.5)]">
                        <label for="price_per_full_day" class="block text-[10px] font-black text-amber-400 uppercase tracking-widest">Full Day <span class="text-slate-500">(24 Hours)</span> <span class="text-emerald-500">*</span></label>
                        <div class="relative mt-2">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-amber-500/50 font-black text-xs uppercase tracking-widest">JOD</span>
                            </div>
                            <input type="number" step="0.01" min="0" id="price_per_full_day" name="price_per_full_day" value="{{ old('price_per_full_day', $farm->price_per_full_day) }}" class="w-full bg-transparent border-none text-white rounded-xl focus:ring-0 pl-14 py-2 text-3xl font-black outline-none" required>
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
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Manage high-quality property photos</p>
                    </div>
                </div>

                <div class="p-8 md:p-10 space-y-10 relative z-10">
                    {{-- Main Cover Image --}}
                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                            Main Cover Image <span class="text-slate-600 normal-case">(Upload new to replace)</span>
                        </label>

                        <div class="flex flex-col sm:flex-row items-center gap-8">
                            @if($farm->main_image)
                                <div class="relative w-40 h-40 rounded-[2rem] border-4 border-slate-800 overflow-hidden flex-shrink-0 shadow-[0_0_20px_rgba(0,0,0,0.5)] bg-[#020617] group">
                                    <img src="{{ asset('storage/' . $farm->main_image) }}" alt="Cover" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent pointer-events-none"></div>
                                    <span class="absolute bottom-3 w-full text-center text-[9px] font-black text-emerald-400 uppercase tracking-widest drop-shadow-md z-10">Active</span>
                                </div>
                            @endif

                            <div class="flex-1 w-full relative group h-40 rounded-[2rem] border-2 border-dashed border-slate-700 bg-[#020617] hover:border-emerald-500 hover:bg-emerald-500/5 transition-all shadow-inner cursor-pointer flex flex-col items-center justify-center" onclick="document.getElementById('main_image').click()">
                                <input id="main_image" name="main_image" type="file" class="hidden" accept="image/*">
                                <div class="text-center transform transition-all duration-500 group-hover:scale-105">
                                    <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center mx-auto mb-3 text-slate-500 group-hover:text-emerald-400 group-hover:bg-emerald-500/20 border border-slate-700 group-hover:border-emerald-500/30 transition-colors shadow-lg">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    </div>
                                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-emerald-400 transition-colors">Upload Replacement</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Gallery Images --}}
                    <div class="space-y-4 pt-6 border-t border-slate-800">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Current Gallery Images</label>

                        @if($farm->images->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                                @foreach($farm->images as $image)
                                    <div class="relative group rounded-2xl overflow-hidden shadow-sm border border-slate-700 aspect-square cursor-pointer" onclick="const cb = this.querySelector('input'); cb.checked = !cb.checked; this.classList.toggle('ring-2'); this.classList.toggle('ring-rose-500'); this.querySelector('.overlay').classList.toggle('opacity-100');">
                                        <img src="{{ asset('storage/' . $image->image_url) }}" alt="Gallery Image" class="w-full h-full object-cover transition-transform group-hover:scale-110 duration-500">
                                        <div class="overlay absolute inset-0 bg-rose-950/80 opacity-0 transition-opacity flex flex-col items-center justify-center backdrop-blur-sm">
                                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" onclick="event.stopPropagation()" class="hidden">
                                            <div class="w-8 h-8 rounded-full bg-rose-500/20 border border-rose-500/50 flex items-center justify-center mb-1 shadow-[0_0_10px_rgba(244,63,94,0.5)]">
                                                <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </div>
                                            <span class="text-[9px] font-black text-white uppercase tracking-widest">Delete</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-slate-950/50 rounded-2xl border border-slate-800 p-6 text-center mb-8 shadow-inner">
                                <p class="text-[10px] uppercase tracking-widest font-bold text-slate-500">No additional gallery images available.</p>
                            </div>
                        @endif

                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mt-6">Append New Gallery Images <span class="text-slate-600 normal-case">(Optional)</span></label>
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
                    Save Changes & Submit
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=marker&callback=initMap" async defer></script>
    <script>
        function initMap() {
            var latInput = document.getElementById('latitude');
            var lngInput = document.getElementById('longitude');

            var initialPos = {
                lat: latInput.value ? parseFloat(latInput.value) : 31.9522,
                lng: lngInput.value ? parseFloat(lngInput.value) : 35.2332
            };

            // Dark Map Style for God Mode
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

            const map = new google.maps.Map(document.getElementById('farm-map'), {
                zoom: 14,
                center: initialPos,
                styles: darkStyle,
                disableDefaultUI: false,
            });

            // Custom Blue/Teal Pin for Map
            const markerIcon = {
                path: 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z',
                fillColor: '#10b981',
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
                title: "Farm Location"
            });

            function updateInputs(lat, lng) {
                latInput.value = lat.toFixed(8);
                lngInput.value = lng.toFixed(8);
            }

            marker.addListener('dragend', function() {
                const pos = marker.getPosition();
                updateInputs(pos.lat(), pos.lng());
            });

            map.addListener('click', function(e) {
                marker.setPosition(e.latLng);
                updateInputs(e.latLng.lat(), e.latLng.lng());
            });
        }
        window.initMap = initMap;

        // Custom File Input Preview Feedback
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
