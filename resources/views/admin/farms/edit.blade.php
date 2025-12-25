@extends('layouts.app')

@section('title', 'Edit Farm')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Farm</h1>
                <p class="mt-1 text-gray-500 text-sm">Update farm details, pricing, and images.</p>
            </div>
            <a href="{{ route('admin.farms.index') }}" class="text-gray-500 hover:text-green-600 transition flex items-center gap-1 text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Cancel
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <ul class="list-disc pl-5 space-y-1 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.farms.update', $farm->id) }}" method="POST" enctype="multipart/form-data"
              class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 space-y-8"
              x-data="{
                  mainPreview: null,
                  mainVisible: {{ $farm->main_image ? 'true' : 'false' }},
                  galleryPreviews: []
              }">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Farm Name</label>
                    <input type="text" name="name" value="{{ old('name', $farm->name) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none transition-all placeholder-gray-400" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                    <div class="relative">
                        <input type="text" name="location" value="{{ old('location', $farm->location) }}" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none transition-all placeholder-gray-400" required>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Price Per Night (JD)</label>
                    <input type="number" name="price_per_night" value="{{ old('price_per_night', $farm->price_per_night) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none transition-all" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $farm->capacity) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none transition-all" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rating (1-5)</label>
                    <input type="number" name="rating" value="{{ old('rating', $farm->rating) }}" step="0.1" min="1" max="5" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none transition-all" required>
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Main Image</label>

                    <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:bg-gray-50 transition relative cursor-pointer group mb-4">
                        <input type="file" name="main_image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                               x-ref="mainInput"
                               x-on:change="
                                   const file = $event.target.files[0];
                                   if(file) {
                                       mainPreview = URL.createObjectURL(file);
                                       mainVisible = true;
                                       if($refs.removeMain) $refs.removeMain.checked = false;
                                   }
                               ">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-green-500 transition" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            <span class="mt-2 block text-sm">Click to replace main image</span>
                        </div>
                    </div>

                    <div x-show="mainVisible" x-cloak class="relative rounded-xl overflow-hidden border border-gray-200 shadow-sm group w-full h-48">
                        <img :src="mainPreview ? mainPreview : '{{ $farm->main_image ? asset('storage/' . $farm->main_image) : '' }}'"
                             class="w-full h-full object-cover">

                        <button type="button"
                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-md transition transform hover:scale-110"
                                x-on:click="
                                    mainVisible = false;
                                    mainPreview = null;
                                    if($refs.mainInput) $refs.mainInput.value = '';
                                    if($refs.removeMain) $refs.removeMain.checked = true;
                                ">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <input type="checkbox" name="remove_main_image" value="1" class="hidden" x-ref="removeMain">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gallery Images</label>

                    <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:bg-gray-50 transition relative cursor-pointer group mb-4">
                        <input type="file" name="images[]" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                               x-on:change="galleryPreviews = Array.from($event.target.files).map(file => URL.createObjectURL(file))">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-green-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="mt-2 block text-sm">Upload new photos</span>
                        </div>
                    </div>

                    <div x-show="galleryPreviews.length > 0" class="mb-4">
                        <p class="text-xs font-bold text-green-600 mb-2 uppercase tracking-wide">New Images</p>
                        <div class="grid grid-cols-3 gap-3">
                            <template x-for="img in galleryPreviews">
                                <img :src="img" class="h-20 w-full object-cover rounded-lg border border-gray-200">
                            </template>
                        </div>
                    </div>

                    @if($farm->images->count())
                        <div>
                            <p class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Current Images</p>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach($farm->images as $image)
                                    <div class="relative group" x-data="{ removed: false }" x-show="!removed">
                                        <img src="{{ asset('storage/'.$image->image_url) }}" class="h-20 w-full object-cover rounded-lg border border-gray-200">

                                        <input type="checkbox" name="remove_gallery_images[]" value="{{ $image->id }}" class="hidden" x-ref="removeInput">

                                        <button type="button"
                                                class="absolute top-1 right-1 bg-red-500/90 hover:bg-red-600 text-white rounded-full p-1 shadow-sm opacity-0 group-hover:opacity-100 transition-all transform hover:scale-110"
                                                @click="removed = true; $refs.removeInput.checked = true;">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>

                                        <div x-show="removed" class="absolute inset-0 bg-red-50/80 flex items-center justify-center rounded-lg border-2 border-red-200">
                                            <span class="text-xs font-bold text-red-600">Removed</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <hr class="border-gray-100">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="5" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none transition-all resize-none placeholder-gray-400" required>{{ old('description', $farm->description) }}</textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-xl shadow-lg hover:shadow-yellow-200 transition-all transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Update Farm Details
                </button>
            </div>

        </form>
    </div>
@endsection
