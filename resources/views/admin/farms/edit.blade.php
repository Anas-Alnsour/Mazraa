@extends('layouts.app')

@section('title', 'Edit Farm')

@section('content')
<div
    class="max-w-4xl mx-auto bg-white p-8 mt-10 rounded-xl shadow-lg"
    x-data="{
        mainPreview: null,
        mainVisible: {{ $farm->main_image ? 'true' : 'false' }},
        galleryPreviews: []
    }"
>
    <h1 class="text-3xl font-bold text-yellow-600 mb-6 text-center">Edit Farm</h1>

    <form action="{{ route('admin.farms.update', $farm->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 font-semibold">Farm Name</label>
            <input type="text" name="name" value="{{ $farm->name }}" class="w-full border rounded-lg p-3">
        </div>

        <div>
            <label class="block text-gray-700 font-semibold">Location</label>
            <input type="text" name="location" value="{{ $farm->location }}" class="w-full border rounded-lg p-3">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-gray-700 font-semibold">Price Per Night</label>
                <input type="number" name="price_per_night" value="{{ $farm->price_per_night }}" class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Capacity</label>
                <input type="number" name="capacity" value="{{ $farm->capacity }}" class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Rating ⭐</label>
                <input type="number" name="rating" value="{{ $farm->rating }}" class="w-full border rounded-lg p-3" step="0.1" min="1" max="5">
            </div>
        </div>

        {{-- MAIN IMAGE + PREVIEW (Alpine) --}}
        <div>
            <label class="block text-gray-700 font-semibold">Main Image</label>

            <input
                type="file"
                name="main_image"
                class="w-full border rounded-lg p-3"
                x-ref="mainInput"
                x-on:change="
                    const file = $event.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        mainPreview = ev.target.result;
                        mainVisible = true;
                        if ($refs.removeMain) $refs.removeMain.checked = false;
                    };
                    reader.readAsDataURL(file);
                "
            >

            <div
                id="main-image-wrapper"
                class="relative inline-block mt-3"
                x-show="mainVisible"
                x-cloak
            >
                <img
                    id="main-image-img"
                    :src="mainPreview ? mainPreview : '{{ $farm->main_image ? asset('storage/' . $farm->main_image) : '' }}'"
                    class="h-24 rounded border"
                >

                {{-- this will be sent to backend when user removes old main image --}}
                <input
                    type="checkbox"
                    name="remove_main_image"
                    id="remove_main_image"
                    value="1"
                    class="hidden"
                    x-ref="removeMain"
                >

                <button
                    type="button"
                    class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700
                           text-white rounded-full w-6 h-6 flex items-center justify-center
                           cursor-pointer shadow text-sm"
                    x-on:click="
                        mainVisible = false;
                        mainPreview = null;
                        if ($refs.mainInput) $refs.mainInput.value = '';
                        if ($refs.removeMain) $refs.removeMain.checked = true;
                    "
                >
                    ✕
                </button>
            </div>
        </div>

        {{-- GALLERY IMAGES + EXISTING + PREVIEW (Alpine) --}}
        <div class="mb-4">
            <label class="block font-semibold mb-2">Gallery Images</label>

            <input
                type="file"
                id="gallery-input"
                name="images[]"
                multiple
                class="border p-2 rounded w-full"
                x-on:change="
                    galleryPreviews = [];
                    const files = Array.from($event.target.files || []);
                    files.forEach(file => {
                        if (!file.type.startsWith('image/')) return;
                        const reader = new FileReader();
                        reader.onload = ev => {
                            galleryPreviews.push(ev.target.result);
                        };
                        reader.readAsDataURL(file);
                    });
                "
            >

            <p class="text-sm text-gray-600 mt-1">
                To select multiple images, hold <strong>Ctrl</strong> (Windows) or <strong>Shift</strong> while clicking.
            </p>

            {{-- existing gallery images --}}
            @if($farm->images->count())
                <div id="gallery-existing" class="mt-3 flex flex-wrap gap-4">
                    @foreach($farm->images as $image)
                        <div
                            class="relative inline-block gallery-item"
                            x-data="{ removed: false }"
                            x-show="!removed"
                            x-cloak
                        >
                            <img
                                src="{{ asset('storage/'.$image->image_url) }}"
                                class="h-24 w-32 object-cover rounded border"
                            >

                            {{-- this hidden input is enabled when user clicks X, so backend knows which IDs to delete --}}
                            <input
                                type="hidden"
                                name="remove_gallery_images[]"
                                value="{{ $image->id }}"
                                class="gallery-remove-input"
                                x-ref="removeInput"
                                disabled
                            >

                            <button
                                type="button"
                                class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700
                                       text-white rounded-full w-6 h-6 flex items-center justify-center
                                       cursor-pointer shadow text-sm"
                                x-on:click="
                                    removed = true;
                                    if ($refs.removeInput) $refs.removeInput.disabled = false;
                                "
                            >
                                ✕
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- previews for newly selected gallery images --}}
            <div id="gallery-preview" class="mt-3 flex flex-wrap gap-4">
                <template x-for="(img, index) in galleryPreviews" :key="index">
                    <div class="relative inline-block">
                        <img :src="img" class="h-24 w-32 object-cover rounded border">
                    </div>
                </template>
            </div>
        </div>

        <div>
            <label class="block text-gray-700 font-semibold">Description</label>
            <textarea name="description" rows="4" class="w-full border rounded-lg p-3">{{ $farm->description }}</textarea>
        </div>

        <div class="text-center">
            <button type="submit"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg text-lg font-semibold shadow">
                Update Farm
            </button>
        </div>
    </form>
</div>
@endsection