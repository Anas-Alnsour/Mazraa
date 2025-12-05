@extends('layouts.app')

@section('title', 'Edit Farm')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 mt-10 rounded-xl shadow-lg">
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

        <div>
            <label class="block text-gray-700 font-semibold">Main Image</label>
            <input type="file" name="main_image" class="w-full border rounded-lg p-3">
            
            @if($farm->main_image)
                <img src="{{ asset('storage/'.$farm->main_image) }}" class="h-24 rounded mt-3 border">
            @endif
        </div>
        <div class="mb-4">
    <label class="block font-semibold mb-2">Gallery Images</label>
    <input type="file" name="images[]" multiple class="border p-2 rounded w-full">
    <p class="text-sm text-gray-600">You can upload multiple images</p>
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
