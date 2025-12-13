@extends('layouts.app')

@section('title', 'Add New Farm')

@section('content')
    <div 
        x-data="{
            mainPreview: null,
            galleryPreviews: []
        }"
        class="max-w-4xl mx-auto bg-white p-8 mt-10 rounded-xl shadow-lg"
    >
        <h1 class="text-3xl font-bold text-green-700 mb-6 text-center">Add New Farm</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.farms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-gray-700 font-semibold">Farm Name</label>
                <input type="text" name="name" class="w-full border rounded-lg p-3" placeholder="Farm name..." required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Location</label>
                <input type="text" name="location" class="w-full border rounded-lg p-3" placeholder="City / Area" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold">Price Per Night ($)</label>
                    <input type="number" name="price_per_night" class="w-full border rounded-lg p-3" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold">Capacity (People)</label>
                    <input type="number" name="capacity" class="w-full border rounded-lg p-3" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold">Rating ⭐</label>
                    <input type="number" name="rating" class="w-full border rounded-lg p-3" step="0.1" min="1" max="5" required>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Main Image</label>
                <input 
                    type="file" 
                    name="main_image" 
                    class="w-full border rounded-lg p-3"
                    x-on:change="
                        const file = $event.target.files[0];
                        mainPreview = file ? URL.createObjectURL(file) : null;
                    "
                >
                <div class="mt-3">
                    <img 
                        x-show="mainPreview" 
                        x-cloak
                        :src="mainPreview" 
                        class="w-40 h-40 object-cover rounded border"
                    >
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Description</label>
                <textarea name="description" rows="4" class="w-full border rounded-lg p-3" placeholder="Write farm details..."
                          required></textarea>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Gallery Images</label>
                <input 
                    type="file" 
                    name="images[]" 
                    multiple 
                    class="border p-2 rounded w-full"
                    x-on:change="
                        galleryPreviews = [];
                        for (const file of $event.target.files) {
                            galleryPreviews.push(URL.createObjectURL(file));
                        }
                    "
                >
                <p class="text-sm text-gray-600">You can upload multiple images</p>

                <div class="mt-3 flex flex-wrap gap-3">
                    <template x-for="(img, index) in galleryPreviews" :key="index">
                        <img 
                            :src="img" 
                            class="w-24 h-24 object-cover rounded border"
                        >
                    </template>
                </div>
            </div>

            <div class="text-center">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-lg font-semibold shadow">
                    + Add Farm
                </button>
            </div>
        </form>
    </div>
@endsection
