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

        <form action="{{ route('admin.supplies.update', $supply->id) }}" method="POST" class="bg-white p-6 rounded-2xl shadow-lg space-y-6 "
            style="margin-bottom:5em ;margin-left:10em ;margin-right:10em ">
            @csrf
            @method('PUT')
            {{-- Name & Quantity & Price --}}

            {{-- Name --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block mb-2 font-semibold">Supply Name</label>
                    <input type="text" name="name" class="w-full border p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400"  placeholder="Supply Name"
                        value="{{ old('name',$supply->name) }}">
                </div>
                {{-- Quantity --}}
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Quantity:</label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity',$supply->stock) }}"
                        placeholder=" Quantitye"
                        class="w-full border  bg-gray-50 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400 transition">
                </div>

                {{-- Price --}}
                <div>
                    <label class="block mb-2 font-semibold">Price ($)</label>
                    <input type="number" name="price" value="{{ old('price',$supply->price) }}" placeholder="Price" min="0"
                        step="any" class="w-full border p-3 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-400" >

                </div>

            </div>


            {{-- description --}}

            <div>
                <label class="block mb-2 font-semibold ">description</label>
                <textarea name="description" class="w-full border p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" 
                    placeholder="Extra description">{{ old('description',$supply->description) }}</textarea>
            </div>

            <div class="text-center">
                <button type="submit"
                    class="px-8 py-3 bg-green-600 text-white rounded-lg  hover:bg-green-700 font-semibold  transition">
                    Update Supply
                </button>
            </div>

        </form>
    </div>

@endsection
