@extends('layouts.app')

@section('title', 'Edit Supply')

@section('content')
    <div class="container mx-auto py-10">
        <h1 class="text-4xl font-bold mb-8 text-center text-green-600">Edit Supply </h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.supplies.update', $supply->id) }}" method="POST" enctype="multipart/form-data"
            class="max-w-4xl my-10 mx-auto py-12 px-8 bg-white rounded-2xl shadow-lg "
            style="margin-bottom:5em ;margin-left:20em ;margin-right:10em ">
            @csrf
            @method('PUT')
            {{-- Name, Description and Image layout (image right on md+) --}}
            {{-- <div class="max-w-3xl my-10 mx-auto py-12 px-8 bg-white rounded-2xl shadow-lg"> --}}
            <div class="md:flex md:items-center md:space-x-6 flex-col">
                <div class="md:flex-1">
                    {{-- Name --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block mb-2 font-semibold">Supply Name</label>
                            <input type="text" name="name"
                                class="w-full border p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400"
                                placeholder="Supply Name" value="{{ old('name', $supply->name) }}">
                        </div>
                        {{-- Quantity --}}
                        <div>
                            <label class="block mb-2 font-semibold text-gray-700">Quantity:</label>
                            <input type="number" name="quantity" min="1"
                                value="{{ old('quantity', $supply->stock) }}" placeholder=" Quantitye"
                                class="w-full border  bg-gray-50 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400 transition">
                        </div>

                        {{-- Price --}}
                        <div>
                            <label class="block mb-2 font-semibold">Price ($)</label>
                            <input type="number" name="price" value="{{ old('price', $supply->price) }}"
                                placeholder="Price" min="0" step="any"
                                class="w-full border p-3 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-400">

                        </div>

                    </div>

                    <div class="flex flex-row-reverse">
                      {{-- Image update --}}
                        <!-- عرض الصورة الحالية -->
                        <div x-data="{ preview: '{{ asset('storage/' . $supply->image) }}' }">

                            <!-- عرض الصورة -->
                            <div class="mb-3 ">
                                <label class="block mb-2 font-semibold text-gray-700">Current image:</label><br>
                                <img :src="preview" alt="Current Image" class="w-48 h-auto rounded">
                            </div>

                            <!-- زر مخصص -->
                            <div class="flex items-center space-x-4">
                                <label for="image"
                                    class="cursor-pointer px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow-md 
                                            hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Choose image
                                </label>
                            </div>
                            <!-- الحقل مخفي -->
                            <input type="file" name="image" id="image" class="hidden"
                                x-on:change="
                                            const file = $event.target.files[0];
                             Preview = file ? URL.createObjectURL(file) : null;
                              "
                                @change="preview = URL.createObjectURL($event.target.files[0])">
                            <!-- معاينة الصورة -->
                            <div class="mt-4">
                                <img x-show="Preview" x-cloak :src="Preview"
                                    class="w-48 h-48 object-cover rounded-lg border shadow-md">
                            </div>
                        </div>
                    

                        {{-- description --}}

                        <div class="mt-4 mr-10 w-full">
                        <label class="block mb-2 font-semibold ">description</label>
                        <textarea name="description" class="w-full border p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                            placeholder="Extra description">{{ old('description', $supply->description) }}</textarea>
                        </div>
                     </div>
                </div>


            </div>

            <div class="text-center">
                <button type="submit"
                    class="px-8 py-3 bg-green-600 text-white rounded-lg  hover:bg-green-700 font-semibold  transition">
                    Update Supply
                </button>
            </div>
            {{-- </div> --}}
        </form>
    </div>

@endsection
