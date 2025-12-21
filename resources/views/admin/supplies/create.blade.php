@extends('layouts.app')

@section('title', 'New Supply')

@section('content')
    <div class="container mx-auto py-10">
        <h1 class="text-4xl font-bold mb-8 text-center text-blue-600">Add Supplies Request</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.supplies.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl shadow-lg space-y-6 "
            style="margin-bottom:5em ;margin-left:20em ;margin-right:20em ">

            @csrf

            {{-- Name & Quantity & Price --}}

            {{-- Name --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block mb-2 font-semibold">Supply Name</label>
                    <input type="text" name="name" class="w-full border p-3 rounded-xl" placeholder="Supply Name"
                        value="{{ old('name') }}">
                </div>
                {{-- Quantity --}}
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Quantity:</label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity') }}"
                        placeholder=" Quantitye"
                        class="w-full border  bg-gray-50 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                {{-- Price --}}
                <div>
                    <label class="block mb-2 font-semibold">Price ($)</label>
                    <input type="number" name="price" value="{{ old('price') }}" placeholder="Price" min="0"
                        step="any" class="w-full border p-3 rounded-xl bg-gray-50">

                </div>
            </div>

            {{-- Image Upload --}}
            <div x-data="{ mainPreview: null }" class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Supply Image
                </label>
                <!-- زر مخصص -->
                <div class="flex items-center space-x-4">
                    <label for="image"
                        class="cursor-pointer px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow-md 
                                            hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Choose image
                    </label>
                    <span x-show="!mainPreview" x-cloak class="text-gray-500 text-sm">No image selected</span>
                </div>
                <!-- الحقل مخفي -->
                <input type="file" name="image" id="image" class="hidden"
                    x-on:change="
            const file = $event.target.files[0];
            mainPreview = file ? URL.createObjectURL(file) : null;
        ">
                <!-- معاينة الصورة -->
                <div class="mt-4">
                    <img x-show="mainPreview" x-cloak :src="mainPreview"
                        class="w-48 h-48 object-cover rounded-lg border shadow-md">
                </div>
            </div>


            {{-- description --}}
            <div>
                <label class="block mb-2 font-semibold">description</label>
                <textarea name="description" class="w-full border p-3 rounded-lg" placeholder="Extra description">{{ old('description') }}</textarea>
            </div>

            <div class="text-center">
                <button type="submit"
                    class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold  transition">
                    Create Suplly
                </button>
            </div>

        </form>
    </div>

@endsection
