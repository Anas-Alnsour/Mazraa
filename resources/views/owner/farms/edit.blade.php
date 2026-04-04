<x-owner-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.farms.index') }}" class="p-2 text-gray-400 hover:bg-gray-100 hover:text-[#020617] rounded-full transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-[#020617] tracking-tight">Edit Property Details</h1>
                <p class="text-sm text-gray-500 mt-1">Make changes to <span class="font-bold text-[#c2a265]">{{ $farm->name }}</span> below.</p>
            </div>
        </div>
    </x-slot>

    <div class="pb-24">
        <!-- Main Form -->
        <form action="{{ route('owner.farms.update', $farm->id) }}" method="POST" enctype="multipart/form-data" class="max-w-5xl mx-auto space-y-8">
            @csrf
            @method('PUT')

            <!-- Form Validation Errors (Global) -->
            @if ($errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 shadow-sm animate-fade-in-up">
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

            <!-- Section 1: General Information -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-[#020617] flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        General Information
                    </h3>

                    <!-- Quick Status Indicator -->
                    <div>
                        @if($farm->is_approved === 1)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span> Live
                            </span>
                        @elseif($farm->is_approved === 0)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                <span class="w-2 h-2 rounded-full bg-yellow-500"></span> Pending Review
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span> Rejected
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-6 sm:p-8 space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Farm Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $farm->name) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium" required>
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="5" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium resize-y" required>{{ old('description', $farm->description) }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Pricing & Accommodations -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-[#020617] flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Pricing & Capacity
                    </h3>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Database Column: price_per_night -->
                        <div>
                            <label for="price_per_night" class="block text-sm font-bold text-gray-700 mb-2">Price Per Night (JOD) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold">JD</span>
                                </div>
                                <input type="number" step="0.01" min="0" id="price_per_night" name="price_per_night" value="{{ old('price_per_night', $farm->price_per_night) }}" class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium" required>
                            </div>
                            @error('price_per_night') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="capacity" class="block text-sm font-bold text-gray-700 mb-2">Max Guests <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <input type="number" min="1" id="capacity" name="capacity" value="{{ old('capacity', $farm->capacity) }}" class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium" required>
                            </div>
                            @error('capacity') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="rooms" class="block text-sm font-bold text-gray-700 mb-2">Total Rooms <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                </div>
                                <input type="number" min="1" id="rooms" name="rooms" value="{{ old('rooms', $farm->rooms) }}" class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium" required>
                            </div>
                            @error('rooms') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Location -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-[#020617] flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Location Details
                    </h3>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="region" class="block text-sm font-bold text-gray-700 mb-2">Governorate / Region <span class="text-red-500">*</span></label>
                            <select id="region" name="region" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium appearance-none" required>
                                <option value="" disabled>Select Region</option>
                                @foreach(['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Madaba', 'Jerash', 'Ajloun', 'Salt', 'Karak', 'Tafilah', 'Maan', 'Mafraq'] as $r)
                                    <option value="{{ $r }}" {{ old('region', $farm->region) == $r ? 'selected' : '' }}>{{ $r == 'Salt' ? 'Salt / Balqa' : ($r == 'Maan' ? "Ma'an" : $r) }}</option>
                                @endforeach
                            </select>
                            @error('region') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-bold text-gray-700 mb-2">City / Area <span class="text-red-500">*</span></label>
                            <input type="text" id="city" name="city" value="{{ old('city', $farm->city) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium" required>
                            @error('city') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Media -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-[#020617] flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Media Gallery
                    </h3>
                </div>
                <div class="p-6 sm:p-8">

                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <!-- Current Image Display -->
                        <div class="lg:col-span-2 flex flex-col items-center sm:items-start">
                            <label class="block text-sm font-bold text-gray-700 mb-3 text-center sm:text-left">Current Primary Image</label>
                            <div class="relative w-full max-w-sm rounded-2xl overflow-hidden shadow-md border-4 border-white bg-gray-100 ring-1 ring-gray-200 group">
                                @if($farm->images && $farm->images->count() > 0)
                                    <img src="{{ Storage::url($farm->images->first()->image_path) }}" alt="{{ $farm->name }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-sm font-medium">No Image Uploaded</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-sm">
                                    <span class="text-white text-sm font-bold tracking-wide">Currently Active</span>
                                </div>
                            </div>
                        </div>

                        <!-- Upload New Image Zone -->
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-bold text-gray-700 mb-3">Replace Primary Image <span class="text-xs font-normal text-gray-500 ml-1">(Optional)</span></label>
                            <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl hover:border-[#1d5c42]/50 hover:bg-gray-50 transition-colors group relative cursor-pointer overflow-hidden h-48 items-center">
                                <div class="space-y-3 text-center relative z-10">
                                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto shadow-sm border border-gray-100 group-hover:scale-110 transition-transform">
                                        <svg class="w-7 h-7 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    </div>
                                    <div class="flex flex-col items-center text-sm text-gray-600">
                                        <label for="main_image" class="relative cursor-pointer rounded-md font-bold text-[#1d5c42] hover:text-[#154531] focus-within:outline-none">
                                            <span>Upload a new file</span>
                                            <!-- Note: Not required on Edit, only if they want to change it -->
                                            <input id="main_image" name="main_image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1 mt-0.5">to replace the current one</p>
                                    </div>
                                    <p class="text-xs text-gray-500 font-medium max-w-[200px] mx-auto">PNG, JPG, WEBP up to 5MB.</p>
                                </div>
                            </div>
                            @error('main_image') <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Action Bar -->
            <div class="fixed bottom-0 inset-x-0 sm:static sm:bottom-auto sm:inset-x-auto z-20 bg-white sm:bg-transparent border-t border-gray-200 sm:border-t-0 p-4 sm:p-0 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] sm:shadow-none flex flex-col sm:flex-row items-center justify-end gap-3 sm:gap-4 mt-8">
                <a href="{{ route('owner.farms.index') }}" class="w-full sm:w-auto px-6 py-3.5 bg-white hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition-all border border-gray-200 shadow-sm text-center">
                    Cancel Changes
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-[#1d5c42] hover:bg-[#154531] text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-[#1d5c42]/20 hover:shadow-xl hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Update Farm Details
                </button>
            </div>

        </form>
    </div>

    <!-- Script to preview selected file name -->
    <script>
        document.getElementById('main_image').addEventListener('change', function(e) {
            if(e.target.files.length > 0) {
                var fileName = e.target.files[0].name;
                var textContainer = e.target.closest('.space-y-3').querySelector('.text-xs');
                textContainer.innerHTML = '<span class="text-[#1d5c42] font-bold block mb-1">New Image Queued: </span>' + fileName;
            }
        });
    </script>
</x-owner-layout>
